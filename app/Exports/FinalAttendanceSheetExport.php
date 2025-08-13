<?php

namespace App\Exports;

use App\AttendanceDetail;
use App\EmpDet;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FinalAttendanceSheetExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;
    protected $daysInMonth;
    protected $carbonDate;
    private $rowNumber = 1;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
        $this->carbonDate = Carbon::createFromFormat('Y-m', $this->filters['month'])->startOfMonth();
        $this->daysInMonth = $this->carbonDate->daysInMonth;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // This method contains the SAME logic as your controller to fetch and process the data.
        // It fetches ALL employees matching the criteria, not just a paginated set.
        
        $startDate = $this->carbonDate->copy()->format('Y-m-d');
        $endDate = $this->carbonDate->copy()->endOfMonth()->format('Y-m-d');

        $allEmployeeQuery = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
            ->where('emp_dets.branchId', $this->filters['branchId'])
            ->when($this->filters['section'] ?? null, function ($q, $section) { return $q->where('departments.section', $section); })
            ->when($this->filters['organisation'] ?? null, function ($q, $organisation) { return $q->where('emp_dets.organisation', $organisation); })
            ->when($this->filters['search_query'] ?? null, function ($q, $search) {
                return $q->where(function ($query) use ($search) {
                    $query->where('emp_dets.name', 'like', "%{$search}%")->orWhere('emp_dets.empCode', 'like', "%{$search}%");
                });
            });
        
        $allEmployeeIds = $allEmployeeQuery->pluck('emp_dets.id')->toArray();
        if (empty($allEmployeeIds)) { 
            return collect(); // Return empty collection if no employees found
        }

        $allAttendances = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
            ->whereIn('attendance_details.empId', $allEmployeeIds)
            ->whereBetween('attendance_details.forDate', [$startDate, $endDate])
            ->select('attendance_details.*', 'emp_dets.name', 'emp_dets.empCode', 'emp_dets.jobJoingDate', 'emp_dets.lastDate')
            ->orderBy('emp_dets.empCode')->orderBy('attendance_details.forDate')->get();
            
        $attendancesByEmployee = $allAttendances->groupBy('empId');
        $processedEmployees = collect();

        foreach ($attendancesByEmployee as $empId => $employeeDays) {
            $employeeInfo = $employeeDays->first();
            $dailyDataMap = $employeeDays->keyBy('forDate');
            $processedDailyStatus = [];
            $sandwichDeductionCount = 0;

            // This is the SAME processing logic from the controller to ensure data consistency
            // STEP 1 & 2...
            for ($d = 1; $d <= $this->daysInMonth; $d++) {
                $currentDate = $this->carbonDate->copy()->day($d);
                $dayData = $dailyDataMap->get($currentDate->format('Y-m-d'));
                if (!$dayData) { $processedDailyStatus[$d] = null; continue; }
                $finalStatus = $dayData->dayStatus;
                if ($d == 1 && in_array($finalStatus, ['WO', 'LH', 'H'])) {
                    $presentDaysCount = $employeeDays->filter(function($day) { return in_array($day->dayStatus, ['P', 'PL', 'PH']); })->count();
                    if ($presentDaysCount <= 2) { $finalStatus = 'A'; }
                }
                if (in_array($finalStatus, ['WO', 'LH', 'H'])) {
                    $firstWorkingDayBefore = null; $firstWorkingDayAfter = null;
                    for ($i = $d - 1; $i >= 1; $i--) {
                        $prevDay = $dailyDataMap->get($this->carbonDate->copy()->day($i)->format('Y-m-d'));
                        if ($prevDay && !in_array($prevDay->dayStatus, ['WO', 'LH', 'H'])) { $firstWorkingDayBefore = $prevDay; break; }
                    }
                    for ($i = $d + 1; $i <= $this->daysInMonth; $i++) {
                        $nextDay = $dailyDataMap->get($this->carbonDate->copy()->day($i)->format('Y-m-d'));
                        if ($nextDay && !in_array($nextDay->dayStatus, ['WO', 'LH', 'H'])) { $firstWorkingDayAfter = $nextDay; break; }
                    }
                    if ($firstWorkingDayBefore && $firstWorkingDayAfter && (in_array($firstWorkingDayBefore->dayStatus, ['A', '0']) && $firstWorkingDayBefore->AGFStatus == 0) && (in_array($firstWorkingDayAfter->dayStatus, ['A', '0']) && $firstWorkingDayAfter->AGFStatus == 0)) {
                        $finalStatus = 'A';
                        $sandwichDeductionCount++;
                    }
                }
                $processedDailyStatus[$d] = (object)['status' => $finalStatus, 'dayData' => $dayData];
            }
            // ... (Weekly absence rule)
            for ($d = 1; $d <= $this->daysInMonth; $d++) {
                $currentDate = $this->carbonDate->copy()->day($d);
                if ($currentDate->dayOfWeek == Carbon::SUNDAY) {
                    $sundayIndex = $d;
                    $sundayStatusInfo = $processedDailyStatus[$sundayIndex] ?? null;
                    if ($sundayStatusInfo && $sundayStatusInfo->status == 'WO') {
                        $startOfWeek = $currentDate->copy()->subDays(6); $endOfWeek = $currentDate->copy()->subDay();
                        $weeklyAbsenceCount = 0.0;
                        for ($weekDay = $startOfWeek->copy(); $weekDay->lte($endOfWeek); $weekDay->addDay()) {
                            if (!$weekDay->isSameMonth($this->carbonDate)) continue;
                            $statusInfo = $processedDailyStatus[$weekDay->day] ?? null;
                            if (!$statusInfo) continue;
                            if (in_array($statusInfo->status, ['A', '0']) && $statusInfo->dayData->AGFStatus == 0) { $weeklyAbsenceCount += 1.0; } 
                            elseif ($statusInfo->status == 'PH' && $statusInfo->dayData->AGFStatus == 0) { $weeklyAbsenceCount += 0.5; }
                        }
                        if ($weeklyAbsenceCount >= 3.5) { $processedDailyStatus[$sundayIndex]->status = 'A'; } 
                        elseif ($weeklyAbsenceCount >= 3) { $processedDailyStatus[$sundayIndex]->status = 'PH'; }
                    }
                }
            }

            // STEP 3...
            $totals = ['present' => 0.0, 'absent' => 0.0, 'weekly_leave' => 0.0, 'extra_work' => 0.0];
            $lateMarkCount = 0;
            for ($d = 1; $d <= $this->daysInMonth; $d++) {
                $statusInfo = $processedDailyStatus[$d] ?? null;
                if (!$statusInfo) { $statusInfo = (object)['status' => '-', 'dayData' => null]; }
                $finalStatus = $statusInfo->status;
                $dayData = $statusInfo->dayData;
                if ($dayData) {
                    if ($dayData->AGFStatus != 0) {
                         if($dayData->AGFDayStatus == 'Full Day') $finalStatus = 'P';
                         if($dayData->AGFDayStatus == 'Half Day') $finalStatus = 'PH';
                    } elseif (!in_array($finalStatus, ['WO', 'LH', 'H'])) {
                         if (!$dayData->inTime || !$dayData->outTime || !$dayData->officeInTime || !$dayData->officeOutTime) {
                             $finalStatus = 'A';
                         } else {
                            $officeStartTime = Carbon::parse($dayData->forDate . ' ' . $dayData->officeInTime);
                            $officeEndTime = Carbon::parse($dayData->forDate . ' ' . $dayData->officeOutTime);
                            $actualInTime = Carbon::parse($dayData->inTime);
                            $actualOutTime = Carbon::parse($dayData->outTime);
                            $requiredMinutes = $officeEndTime->diffInMinutes($officeStartTime);
                            $actualMinutesWorked = $actualOutTime->diffInMinutes($actualInTime);
                            $requiredHalfDayMinutes = $requiredMinutes / 2;
                            $baseStatus = 'A';
                            if ($actualMinutesWorked >= $requiredMinutes) $baseStatus = 'P';
                            elseif ($actualMinutesWorked >= $requiredHalfDayMinutes) $baseStatus = 'PH';
                            $finalStatus = $baseStatus;
                            $isLate = false;
                            $minutesLeftEarly = $officeEndTime->diffInMinutes($actualOutTime, false);
                            if ($minutesLeftEarly > 15) {
                                if ($finalStatus === 'PH') $finalStatus = 'A'; elseif ($finalStatus === 'P') $finalStatus = 'PH';
                            }
                            $minutesLate = $actualInTime->diffInMinutes($officeStartTime, false);
                            if ($minutesLate > 60) {
                                if ($finalStatus !== 'A') $finalStatus = 'PH'; $isLate = true;
                            } elseif ($minutesLate > 7) {
                                if ($finalStatus === 'P') $finalStatus = 'PL'; $isLate = true;
                            }
                            if ($isLate) $lateMarkCount++;
                         }
                    }
                }
                switch ($finalStatus) {
                    case 'P': case 'PL': $totals['present'] += 1.0; break;
                    case 'A': case '0': $totals['absent'] += 1.0; break;
                    case 'PH': $totals['present'] += 0.5; $totals['absent'] += 0.5; break;
                    case 'WO': case 'LH': case 'H': $totals['weekly_leave'] += 1.0; break;
                }
                $statusInfo->status = $finalStatus;
            }
            $lateMarkDeduction = floor($lateMarkCount / 3);
            if ($lateMarkDeduction > 0) {
                if ($totals['present'] >= $lateMarkDeduction) {
                    $totals['present'] -= $lateMarkDeduction;
                    $totals['absent'] += $lateMarkDeduction;
                }
            }
            $totals['late_mark_deductions'] = $lateMarkDeduction;
            $totals['sandwich_deductions'] = $sandwichDeductionCount;
            $totals['wl_total'] = $lateMarkDeduction + $sandwichDeductionCount;
            $totals['final_total'] = $totals['present'] + ($employeeDays->where('AGFStatus', '!=', 0)->whereIn('statusInfo.status', ['WO', 'LH', 'H'])->count() * 1.0) + $totals['weekly_leave'];
            $employeeInfo->salaryHoldRelease = $employeeDays->last()->salaryHoldRelease ?? 0;
            
            $processedEmployees->push(['info' => $employeeInfo, 'days' => $processedDailyStatus, 'totals' => $totals]);
        }
        
        return $processedEmployees;
    }

    public function headings(): array
    {
        $headings = ['#', 'Emp Code', 'Employee Name'];
        for ($day = 1; $day <= $this->daysInMonth; $day++) {
            $headings[] = $day;
        }
        $headings = array_merge($headings, [
            'Present', 'Absent', 'H/WO', 'WL', 'Payable', 'Status'
        ]);
        return $headings;
    }

    public function map($employee): array
    {
        $mappedRow = [
            $this->rowNumber++,
            $employee['info']->empCode,
            $employee['info']->name,
        ];
        
        for ($day = 1; $day <= $this->daysInMonth; $day++) {
            $dayData = $employee['days'][$day] ?? null;
            $status = $dayData ? $dayData->status : '-';
            if ($status === 'PL') $status = 'PBL';
            $mappedRow[] = $status;
        }
        
        $mappedRow = array_merge($mappedRow, [
            $employee['totals']['present'],
            $employee['totals']['absent'],
            $employee['totals']['weekly_leave'],
            $employee['totals']['wl_total'],
            $employee['totals']['final_total'],
            ($employee['info']->salaryHoldRelease == 1) ? 'Hold' : 'Release',
        ]);

        return $mappedRow;
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Bold the first row (headings)
            'A' => ['font' => ['bold' => true]], // Bold the '#' column
            'B' => ['font' => ['bold' => true]], // Bold the 'Emp Code' column
            'C' => ['font' => ['bold' => true]], // Bold the 'Employee Name' column
        ];
    }
}