<?php

namespace App\Services;

use App\AttendanceDetail;
use App\EmpApplication;
use App\EmpChangeDay;
use App\Retention;
use App\EmpAdvRs;
use App\EmpDebit;
use App\EmpMr;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AttendanceProcessorService
{
    public function processSingleEmployeeReport(int $employeeId, string $forMonth): ?array
    {
        // --- STAGE 1: DATE SETUP ---
        try {
            $carbonDate = Carbon::createFromFormat('Y-m', $forMonth)->startOfMonth();
        } catch (\Exception $e) {
            // Invalid month format provided.
            return null;
        }

        // --- STAGE 2: FETCH & PROCESS DATA ---
        // Call the private function which contains the core logic.
        $calculationResult = $this->_processSingleEmployeeAttendance($employeeId, $forMonth);
        
        // --- STAGE 3: VALIDATE AND EXTRACT THE RESULT ---
        if (
            !$calculationResult ||
            !is_array($calculationResult) ||
            !isset($calculationResult['processedEmployees']) ||
            !($calculationResult['processedEmployees'] instanceof Collection) ||
            $calculationResult['processedEmployees']->isEmpty()
        ) {
            // No valid employee data was found or an error occurred.
            return null;
        }

        // Since this function is for a single employee, we take the first item.
        $singleEmployeeData = $calculationResult['processedEmployees']->first();

        // Extract the final components for the return structure.
        $info   = $singleEmployeeData['info']   ?? [];
        $days   = $singleEmployeeData['days']   ?? [];
        $totals = $singleEmployeeData['totals'] ?? [];

        // --- STAGE 4: RETURN FINAL STRUCTURED DATA ---
        // This structure matches what the frontend expects.
        return [
            'info'   => $info,
            'days'   => $days,
            'totals' => $totals,
        ];
    }

    /**
     * Contains the core business logic for calculating attendance for one employee.
     *
     * @param int $employeeId
     * @param string $month
     * @return array|null
     */
    private function _processSingleEmployeeAttendance(int $employeeId, string $month): ?array
    {
        // --- 1. INITIALIZATION AND DATE SETUP ---
        try {
            $carbonDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } catch (\Exception $e) { return null; }

        // --- MODIFICATION START: Handle current month vs. past months ---
        $today = Carbon::now();
        $isCurrentMonth = $carbonDate->isSameMonth($today);

        if ($isCurrentMonth) {
            // If it's the current month, only process up to today.
            $startDate = $carbonDate->copy()->toDateString();
            $endDate = $today->copy()->toDateString(); // End date is today
            $daysToProcess = $today->day; // Loop only up to today's day number
        } else {
            // For past months, process the full month.
            $startDate = $carbonDate->copy()->toDateString();
            $endDate = $carbonDate->copy()->endOfMonth()->toDateString();
            $daysToProcess = $carbonDate->daysInMonth;
        }
        // --- MODIFICATION END ---

        // --- 3. EFFICIENT DATABASE QUERY ---
        $allAttendances = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select(
                'attendance_details.*', 'emp_dets.name', 'emp_dets.empCode', 'emp_dets.jobJoingDate', 'emp_dets.lastDate',
                'emp_dets.startTime', 'emp_dets.endTime','emp_dets.id as attendEmpId','emp_dets.organisation','emp_dets.salaryScale',
                'emp_dets.bankAccountNo','emp_dets.bankIFSCCode','emp_dets.bankName',
                'designations.name as designationName', 'contactus_land_pages.branchName'
            )          
            ->whereBetween('attendance_details.forDate', [$startDate, $endDate])
            ->where('emp_dets.id', $employeeId)
            ->orderBy('emp_dets.empCode')
            ->orderBy('attendance_details.forDate')
            ->get();

        if ($allAttendances->isEmpty()) {
            return null;
        }

        $employeeIds = $allAttendances->pluck('attendEmpId')->unique();
        $dayChanges = EmpChangeDay::where('month', $month)->whereIn('empId', $employeeIds)->get()->keyBy('empId');
        $retentions = Retention::where('month', $month)->whereIn('empId', $employeeIds)->get()->keyBy('empId');
        $employeeAdvances = EmpAdvRs::where('startDate', '>=', $month)->where('endDate', '<=', $month)->where('status', 0)->where('accountStatus', 1)->whereIn('empId', $employeeIds)->get()->keyBy('empId');
        $empDebits = EmpDebit::where('forMonth',$month)->whereIn('empId', $employeeIds)->where('status', 0)->get()->keyBy('empId');
        
        // --- 4. PROCESS DATA WITH ALL BUSINESS RULES ---
        $attendancesByEmployee = $allAttendances->groupBy('empId');
        $processedEmployees = collect();
        

        foreach ($attendancesByEmployee as $empId => $employeeDays) {
            $employeeInfo = $employeeDays->first();
            
            $joiningDate = $employeeInfo->jobJoingDate ? Carbon::parse($employeeInfo->jobJoingDate)->startOfDay() : null;
            $lastWorkingDate = $employeeInfo->lastDate ? Carbon::parse($employeeInfo->lastDate)->startOfDay() : null;

            $dailyDataMap = $employeeDays->keyBy(function($day) {
                return Carbon::parse($day->forDate)->format('Y-m-d');
            });

            $processedDailyStatus = [];
            $sandwitchDayDed = 0;
            $weeklyRuleDeductions = 0.0;

            // region Business Rule Processing
            // The main loop now uses $daysToProcess instead of $daysInMonth
            for ($d = 1; $d <= $daysToProcess; $d++) {
                $currentDate = $carbonDate->copy()->day($d)->startOfDay();

                if (($joiningDate && $currentDate->lt($joiningDate)) || ($lastWorkingDate && $currentDate->gt($lastWorkingDate))) {
                    $processedDailyStatus[$d] = (object)['status' => 'NE', 'dayData' => null];
                    continue;
                }

                $dayData = $dailyDataMap->get($currentDate->format('Y-m-d'));
                if (!$dayData) { $processedDailyStatus[$d] = null; continue; }
                $finalStatus = $dayData->dayStatus;

                if ($d == 1 && in_array($finalStatus, ['WO', 'LH'])) {
                    $otherDays = $employeeDays->filter(function($day) use ($currentDate) {
                        return $day->forDate != $currentDate->format('Y-m-d');
                    });
                    if ($otherDays->isNotEmpty()) {
                        $presentDaysCount = $otherDays->filter(function($day) {
                            return in_array($day->dayStatus, ['P', 'PL', 'PH', 'PLH']);
                        })->count();
                        if ($presentDaysCount <= 2) {
                            $finalStatus = 'A';
                            $sandwitchDayDed++;
                        }
                    }
                }

                if (in_array($finalStatus, ['WO', 'LH'])) {
                    $firstWorkingDayBefore = null; $firstWorkingDayAfter = null;
                    for ($i = $d - 1; $i >= 1; $i--) {
                        $prevDayStatus = $processedDailyStatus[$i] ?? null;
                        if ($prevDayStatus && !in_array($prevDayStatus->status, ['WO', 'LH', 'NE'])) { $firstWorkingDayBefore = $prevDayStatus; break; }
                    }
                    for ($i = $d + 1; $i <= $daysToProcess; $i++) {
                        $nextDay = $dailyDataMap->get($carbonDate->copy()->day($i)->format('Y-m-d'));
                        if ($nextDay && !in_array($nextDay->dayStatus, ['WO', 'LH'])) { $firstWorkingDayAfter = (object)['status' => $nextDay->dayStatus, 'dayData' => $nextDay]; break; }
                    }
                    if ($firstWorkingDayBefore && $firstWorkingDayAfter && (in_array($firstWorkingDayBefore->status, ['A', '0']) && $firstWorkingDayBefore->dayData->AGFStatus == 0) && (in_array($firstWorkingDayAfter->status, ['A', '0']) && $firstWorkingDayAfter->dayData->AGFStatus == 0)) {
                        $finalStatus = 'A';
                        $sandwitchDayDed++;
                    }
                }
                $processedDailyStatus[$d] = (object)['status' => $finalStatus, 'dayData' => $dayData];
            }

            if ($daysToProcess >= 4) {
                $day1Info = $processedDailyStatus[1] ?? null;
                $isDay1Holiday = $day1Info && in_array($day1Info->status, ['WO', 'LH', 'H']);
                $areNext5DaysAbsent = ($processedDailyStatus[2] ?? null) && in_array($processedDailyStatus[2]->status, ['A', '0']) && $processedDailyStatus[2]->dayData->AGFStatus == 0 &&
                                      ($processedDailyStatus[3] ?? null) && in_array($processedDailyStatus[3]->status, ['A', '0']) && $processedDailyStatus[3]->dayData->AGFStatus == 0 &&
                                      ($processedDailyStatus[4] ?? null) && in_array($processedDailyStatus[4]->status, ['A', '0']) && $processedDailyStatus[4]->dayData->AGFStatus == 0 &&
                                      ($processedDailyStatus[5] ?? null) && in_array($processedDailyStatus[5]->status, ['A', '0']) && $processedDailyStatus[5]->dayData->AGFStatus == 0 &&
                                      ($processedDailyStatus[6] ?? null) && in_array($processedDailyStatus[6]->status, ['A', '0']) && $processedDailyStatus[6]->dayData->AGFStatus == 0;
                if ($isDay1Holiday && $areNext5DaysAbsent) {
                    $processedDailyStatus[1]->status = 'A'; 
                    $sandwitchDayDed++;
                }
            }
            
            for ($d = 1; $d <= $daysToProcess; $d++) {
                $currentDate = $carbonDate->copy()->day($d);
                if ($currentDate->dayOfWeek == Carbon::SATURDAY) {
                    $mondayIndex = $d - 5;
                    if ($mondayIndex < 1) continue;
                    $isFullWeekAbsent = true;
                    for ($i = $mondayIndex; $i <= ($d-1); $i++) {
                        $dayInfo = $processedDailyStatus[$i] ?? null;
                        if (!$dayInfo || !in_array($dayInfo->status, ['A', '0']) || $dayInfo->dayData->AGFStatus != 0) {
                            $isFullWeekAbsent = false;
                            break;
                        }
                    }
                    if ($isFullWeekAbsent) {
                        if (($processedDailyStatus[$d] ?? null) && in_array($processedDailyStatus[$d]->status, ['WO', 'LH', 'H'])) {
                            $processedDailyStatus[$d]->status = 'A'; $sandwitchDayDed++;
                        }
                        if (($d+1) <= $daysToProcess && ($processedDailyStatus[$d+1] ?? null) && in_array($processedDailyStatus[$d+1]->status, ['WO', 'LH', 'H'])) {
                            $processedDailyStatus[$d+1]->status = 'A'; $sandwitchDayDed++;
                        }
                    }
                }
            }

            $weeklyConfig = [ 'ABSENT' => ['A', '0'], 'HALF_DAY' => ['PH', 'PLH'], 'HOLIDAY' => ['H', 'LH'], 'WEEKLY_OFF' => 'WO', 'STANDARD_WORK_DAYS' => 6, 'ABSENT_THRESHOLD_RATIO' => 3.5 / 6, 'HALF_DAY_THRESHOLD_RATIO' => 3.0 / 6 ];
            for ($d = 1; $d <= $daysToProcess; $d++) {
                $currentDate = $carbonDate->copy()->day($d);
                if ($currentDate->dayOfWeek == Carbon::SUNDAY) {
                    $sundayIndex = $d;
                    $sundayStatusInfo = $processedDailyStatus[$sundayIndex] ?? null;
                    if ($sundayStatusInfo && $sundayStatusInfo->status == $weeklyConfig['WEEKLY_OFF']) {
                        $startOfWeek = $currentDate->copy()->subDays(6); $endOfWeek = $currentDate->copy()->subDay();
                        $weeklyAbsenceCount = 0.0; $weeklyHolidayCount = 0;
                        for ($weekDay = $startOfWeek->copy(); $weekDay->lte($endOfWeek); $weekDay->addDay()) {
                            if (!$weekDay->isSameMonth($carbonDate)) continue;
                            $statusInfo = $processedDailyStatus[$weekDay->day] ?? null;
                            if (!$statusInfo) continue;
                            if (in_array($statusInfo->status, $weeklyConfig['ABSENT']) && $statusInfo->dayData->AGFStatus == 0) $weeklyAbsenceCount += 1.0;
                            elseif (in_array($statusInfo->status, $weeklyConfig['HALF_DAY']) && $statusInfo->dayData->AGFStatus == 0) $weeklyAbsenceCount += 0.5;
                            elseif (in_array($statusInfo->status, $weeklyConfig['HOLIDAY'])) $weeklyHolidayCount++;
                        }
                        $actualWorkDays = $weeklyConfig['STANDARD_WORK_DAYS'] - $weeklyHolidayCount;
                        if ($actualWorkDays > 0) {
                            if ($weeklyAbsenceCount >= ($weeklyConfig['ABSENT_THRESHOLD_RATIO'] * $actualWorkDays)) { $processedDailyStatus[$sundayIndex]->status = 'A'; $weeklyRuleDeductions += 1.0; } 
                            elseif ($weeklyAbsenceCount >= ($weeklyConfig['HALF_DAY_THRESHOLD_RATIO'] * $actualWorkDays)) { $processedDailyStatus[$sundayIndex]->status = 'PH'; $weeklyRuleDeductions += 0.5; }
                        }
                    }
                }
            }
            // endregion
            
            $totals = ['present' => 0.0, 'absent' => 0.0, 'weekly_leave' => 0.0, 'extra_work' => 0.0];
            $lateMarkCount = 0;
            $finalDailyObjects = [];
            for ($d = 1; $d <= $daysToProcess; $d++) {
                $statusInfo = $processedDailyStatus[$d] ?? null;
                if (!$statusInfo) { $finalDailyObjects[$d] = null; continue; }
                
                if ($statusInfo->status == 'NE') {
                    $finalDailyObjects[$d] = (object)['status' => '0', 'class' => 'attend-0', 'forDate' => $carbonDate->copy()->day($d)->format('Y-m-d'), 'officeInTime' => null, 'officeOutTime' => null, 'inTime' => null, 'outTime' => null, 'workingHr' => null, 'AGFStatus' => null, 'repAuthStatus' => null, 'HRStatus' => null, 'startTime' => null, 'endTime' => null, 'AGFDayStatus' => null, 'deviceInTime'=>null, 'deviceOutTime'=>null ];
                    continue;
                }
                
                $finalStatus = $statusInfo->status;
                $dayData = $statusInfo->dayData;
                $isLate = false;

                if (in_array($finalStatus, ['P', 'PL', 'PLH']) && $dayData->inTime && $dayData->outTime && $dayData->officeInTime && $dayData->officeOutTime) {
                    $officeStartTime = Carbon::parse($dayData->forDate . ' ' . $dayData->officeInTime);
                    $actualInTime = Carbon::parse($dayData->inTime);
                    $actualMinutesWorked = Carbon::parse($dayData->outTime)->diffInMinutes($actualInTime);
                    $requiredHalfDayMinutes = Carbon::parse($dayData->forDate . ' ' . $dayData->officeOutTime)->diffInMinutes($officeStartTime) / 2;
                    if ($actualMinutesWorked < $requiredHalfDayMinutes && $dayData->AGFStatus == 0) {
                        $finalStatus = 'A';
                    } else {
                        $leftEarly = Carbon::parse($dayData->outTime)->isBefore(Carbon::parse($dayData->forDate . ' ' . $dayData->officeOutTime)->subMinutes(15));
                        $shiftMidpoint = $officeStartTime->copy()->addMinutes($requiredHalfDayMinutes);
                        $isHalfDayDueToShiftSpan = !(Carbon::parse($dayData->inTime)->lt($shiftMidpoint) && Carbon::parse($dayData->outTime)->gt($shiftMidpoint));
                        if ($isHalfDayDueToShiftSpan && $leftEarly && $dayData->AGFStatus == 0) $finalStatus = 'A';
                        elseif (($isHalfDayDueToShiftSpan || $leftEarly) && $dayData->AGFStatus == 0) $finalStatus = 'PH';
                        else {
                            if ($actualInTime->isAfter($officeStartTime->copy()->addMinutes(7)) && $dayData->AGFStatus == 0) { $isLate = true; if ($finalStatus == 'P') $finalStatus = 'PL'; } 
                            else { $finalStatus = ($dayData->AGFDayStatus == 'Full Day') ? 'P' : 'PH'; }
                        }
                    }
                } else {
                    if($finalStatus == 'A' && $dayData->AGFStatus != 0) $finalStatus = ($dayData->AGFDayStatus == 'Full Day') ? 'P' : 'PH';
                }

                if ($isLate) $lateMarkCount++;
                if (in_array($finalStatus, ['WO', 'LH']) && $dayData->AGFStatus != 0) $totals['extra_work'] += ($dayData->AGFDayStatus == 'Full Day') ? 1.0 : 0.5;
                if(($dayData->dayStatus == 'PH' || $dayData->dayStatus == 'PLH')  && $dayData->AGFStatus != 0) $finalStatus = ($dayData->AGFDayStatus == 'Full Day') ? 'P' : 'PH';

                switch ($finalStatus) {
                    case 'P': case 'PL': $totals['present'] += 1.0; break;
                    case 'A': case '0': $totals['absent'] += 1.0; break;
                    case 'PH':case 'PLH': $totals['present'] += 0.5; $totals['absent'] += 0.5; break;
                    case 'WO': case 'LH': $totals['weekly_leave'] += 1.0; break;
                }
                $finalDailyObjects[$d] = (object)['officeInTime' => $dayData->officeInTime,'officeOutTime' => $dayData->officeOutTime,'status' => $finalStatus, 'class' => 'attend-'.$finalStatus, 'forDate' => $dayData->forDate, 'inTime' => $dayData->inTime, 'outTime' => $dayData->outTime, 'workingHr' => $dayData->workingHr, 'AGFStatus' => $dayData->AGFStatus, 'repAuthStatus' => $dayData->repAuthStatus, 'HRStatus' => $dayData->HRStatus, 'startTime'=>$dayData->startTime, 'endTime'=>$dayData->endTime, 'AGFDayStatus' => $dayData->AGFDayStatus, 'deviceInTime'=>$dayData->deviceInTime, 'deviceOutTime'=>$dayData->deviceOutTime];
            }

            $lateMarkDeduction = floor($lateMarkCount / 3);
            if ($lateMarkDeduction > 0) {
                $totals['present'] -= $lateMarkDeduction;
                $totals['absent'] += $lateMarkDeduction;
            }
            
            $totals['late_mark_deductions'] = $lateMarkDeduction;
            $totals['sandwitch_deductions'] = $sandwitchDayDed;
            $totals['weekly_rule_deductions'] = $weeklyRuleDeductions;
            $totals['total_deductions'] = $lateMarkDeduction + $sandwitchDayDed + $weeklyRuleDeductions;
            
            $totals['present'] = $totals['present'] + $totals['weekly_leave'];
            $totals['final_total'] = $totals['present'] + $totals['extra_work'];
            $totals['absent'] = $totals['absent'] - $totals['total_deductions'];
            
            if ($changeData = $dayChanges->get($employeeInfo->attendEmpId)) {
                $totals['is_edited'] = true;
                $totals['remark'] = $changeData->remark;
                $totals['new_present'] = $changeData->newPresentDays;
                $totals['new_absent'] = $changeData->newAbsentDays;
                $totals['new_wl'] = $changeData->newWLDays;
                $totals['new_extra_work'] = $changeData->newExtraDays;
                $totals['new_final_total'] = $changeData->newDays;
            } else {
                $totals['is_edited'] = false;
            }

            $retentionData = $retentions->get($empId);
            $employeeAdvanceData = $employeeAdvances->get($empId);
            $empDebitData = $empDebits->get($empId);

            $totals['empCode'] = $employeeInfo->empCode;
            $totals['organisation'] = $employeeInfo->organisation;
            $totals['retention'] = $retentionData->retentionAmount ?? 0;
            $totals['prevGrossSalary'] = EmpMr::where('empId', $employeeInfo->attendEmpId)->where('forDate', '2025-03')->value('grossSalary') ?? 0;
            $totals['grossSalary'] = $employeeInfo->salaryScale;
            $totals['advanceAgainstSalary'] = $employeeAdvanceData->deduction ?? 0;
            $totals['otherDeduction'] = $empDebitData->amount ?? 0;
            $totals['bankAccountNo'] = $employeeInfo->bankAccountNo;
            $totals['bankIFSCCode'] = $employeeInfo->bankIFSCCode;
            $totals['bankName'] = $employeeInfo->bankName;
            $totals['salaryStatus'] = 0;
            
            $employeeInfo->finalSalaryStatus = $employeeDays->last()->salaryHoldRelease ?? 0;
            $processedEmployees->push(['info' => $employeeInfo, 'days' => $finalDailyObjects, 'totals' => $totals]);
        }

        return [
            'processedEmployees' => $processedEmployees,
            'carbonDate' => $carbonDate,
            'daysInMonth' => $daysToProcess // Return the number of days processed
        ];
    }
}
