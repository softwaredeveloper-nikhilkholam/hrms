<?php

namespace App\Http\Controllers\admin;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\SalarySheetImport; 
use App\Exports\AttendanceReportExport;
use App\AttendanceFinalDetail;
use App\Helpers\Utility;
use App\PayrollApplication;
use App\Designation;
use App\EmpMr;
use App\Department;
use App\ContactusLandPage;
use App\EmpDet;
use App\AttendanceDetail;
use App\EmpAdvRs;
use App\EmpDebit;
use App\AttendanceJob;
use App\EmpChangeDay;
use App\EmpApplication;
use App\SalaryHoldRelease;
use App\MonthlyAttendanceSummary;
use App\Retention;
use Auth;
use DB;
use Excel;
use PDF;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator; // Don't forget to import Validator 


class EmpAttendancesController extends Controller
{   
    public function index(Request $request)
    {
        $userType = Auth::user()->userType;
        
        $util = new Utility();
        $departments = Department::where('active', 1)->orderBy('name')->pluck('name', 'id');
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        return view('admin.attendance.list')->with(['month'=>0,'departments'=>$departments, 'branches'=>$branches]);            
    
        
        if($user_type == '21' || $user_type == '11')
        {
            if($userType == '21')
            {
                $empIds = EmpDet::where('reportingId', $empId)->pluck('id');
            }
            elseif($userType == '11')
            {
                $empIds1 = EmpDet::where('reportingId', $empId)->pluck('id');
                $empIds2 = EmpDet::whereIn('reportingId', $empIds1)->pluck('id');
                $collection = collect($empIds1);
                $merged = $collection->merge($empIds2);
                $empIds = $merged->all(); 
            }

            $attendances = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->select('attendance_details.*', 'emp_dets.name','emp_dets.startTime','emp_dets.endTime', 
            'emp_dets.firmType','emp_dets.jobJoingDate', 'designations.name as designationName')
            ->where('attendance_details.month', $month)
            ->where('attendance_details.year', $year)
            ->where('emp_dets.firmType', 1)
            ->where('emp_dets.jobJoingDate', '<=',date('Y-m-d'))
            ->where('emp_dets.active', 1);

        }
        else
        {
            $util = new Utility();
            $departments = Department::where('active', 1)->orderBy('name')->pluck('name', 'id');
            $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
            return view('admin.attendance.list')->with(['month'=>0,'departments'=>$departments, 'branches'=>$branches]);            
        }

        $util = new Utility();
        $departments = Department::where('active', 1)->orderBy('name')->pluck('name', 'id');
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        return view('admin.attendance.list')->with(['month'=>0,'departments'=>$departments, 'branches'=>$branches]);
    } 

    public function getDiff($t1, $t2)
    {
        $date1 = strtotime($t1); 
        $date2 = strtotime($t2); 

        $diff = abs($date2 - $date1);
        $years = floor($diff / (365*60*60*24)); 
        $months = floor(($diff - $years * 365*60*60*24)
                                    / (30*60*60*24)); 
        $days = floor(($diff - $years * 365*60*60*24 - 
                    $months*30*60*60*24)/ (60*60*24));
        $hours = floor(($diff - $years * 365*60*60*24 
            - $months*30*60*60*24 - $days*60*60*24)
                                        / (60*60)); 
        $minutes = floor(($diff - $years * 365*60*60*24 
                - $months*30*60*60*24 - $days*60*60*24 
                                - $hours*60*60)/ 60); 
       
        return $hours.'.'.$minutes;
    }

    public function getPuntchTime($forDate, $empCode)
    {
        $attendDet = AttendanceDetail::where('empCode', $empCode)->where('forDate', $forDate)->first();
        $attendDet['inTime'] = date('H:i:s', strtotime($attendDet->inTime));
        $attendDet['outTime'] = date('H:i:s', strtotime($attendDet->outTime));
        return $attendDet;
    }

    public function empPolicy()
    {
        $emps = EmpDet::select('departmentId','designationId','branchId','id')->get();
        foreach($emps as $temp)
        {
            $branchCt = ContactusLandPage::where('id', $temp->branchId)->count();
            if($branchCt == 0)
            {
                $tp = EmpDet::find($temp->id);
                $tp->branchId = 11;
                $tp->save();
            }

            $deptCt = Department::where('id', $temp->departmentId)->count();
            if($deptCt == 0)
            {
                $tp = EmpDet::find($temp->id);
                $tp->departmentId = 3;
                $tp->save();
            }

            $desigCt = Designation::where('id', $temp->designationId)->count();
            if($desigCt == 0)
            {
                $tp = EmpDet::find($temp->id);
                $tp->designationId = 2;
                $tp->save();
            }
        }

        $branches = ContactusLandPage::whereActive(1)->orderBy('branchName')->pluck('branchName', 'id');
        $departments = Department::whereActive(1)->orderBy('name')->pluck('name', 'id');
        $designations = Designation::whereActive(1)->orderBy('name')->pluck('name', 'id');
        return view('admin.attendance.empPolicy')->with(['branches'=>$branches, 'departments'=>$departments, 'designations'=>$designations]);
    }

    public function updateEmpPolicy(Request $request)
    {
        $empCode =  $request->empCode;
        $section =  $request->section;
        $departmentId =  $request->departmentId;
        $designationId =  $request->designationId;
        $fromDate =  $request->fromDate;
        $toDate =  $request->toDate;

        if($empCode == '' && $section == '' && $departmentId == '' && $designationId == '' && $fromDate == '' && $toDate == '')
            return redirect()->back()->withInput()->with("error","Invalid entry..");

        $attendance = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('attendance_details.id');

        if($empCode != '')
        {
            $attendance=$attendance->where('emp_dets.empCode', $empCode);
        }
        else
        {
            if($section != '')
                $attendance=$attendance->where('departments.section', $section);

            if($departmentId != '')
                $attendance=$attendance->where('departments.id', $departmentId);

            if($designationId != '')
                $attendance=$attendance->where('designations.id', $designationId);
        }

        if($fromDate != '')
            $attendance=$attendance->where('attendance_details.forDate', '>=', $fromDate);

        if($toDate != '')
            $attendance=$attendance->where('attendance_details.forDate', '<=', $fromDate);

        if($fromDate == '' && $toDate == '')
            $attendance=$attendance->where('attendance_details.forDate', '>=', date('Y-m-d'));

        $attendances=$attendance->get();
        if($attendance)
        {
            foreach($attendances as $attend)
            {
                $temp = AttendanceDetail::find($attend->id);
                if(isset($request->pol1))
                    $temp->weeklyOffPaid=1;

                if(isset($request->pol2))
                    $temp->sandwich3Days=1;

                if(isset($request->pol3))
                    $temp->saturday3rd=1;

                if(isset($request->pol4))
                    $temp->indDayStatus=1;

                if(isset($request->pol5))
                    $temp->repubDayStatus=1;

                if(isset($request->pol6))
                    $temp->sandwichStatus=1;

                if(isset($request->pol7))
                    $temp->lateMarkAllowed=1;

                if(isset($request->pol8))
                    $temp->travellingAllowed=1;

                if(isset($request->pol9))
                    $temp->extraWorkAllowed=1;

                if(isset($request->pol10))
                    $temp->nightShiftAllowed=1;

                $temp->save();
            }
        }
        else
        {
            return redirect()->back()->withInput()->with("error","Record not found...");
        }

        return redirect('/home')->with("success","Custom HR Policy Updated Successfully..");

    }


    private function _calculateAttendanceData(int $branchId, string $finalMonth, ?string $section, ?string $empCode = null)
    {
        // --- 1. INITIALIZATION AND DATE SETUP ---
        try {
            $carbonDate = Carbon::createFromFormat('Y-m', $finalMonth)->startOfMonth();
        } catch (\Exception $e) {
            \Log::error("Invalid month format in _calculateAttendanceData: " . $finalMonth . " - " . $e->getMessage());
            return false; // Indicate failure
        }

        $startDate = $carbonDate->copy()->format('Y-m-d');
        // Conditionally set the end date based on the new requirement.
        if ($finalMonth == date('Y-m')) {
            // If it's the current month, the end date is today.
            $endDate = Carbon::now()->format('Y-m-d');
        } else {
            // For any other month, the end date is the last day of that month.
            $endDate = $carbonDate->copy()->endOfMonth()->format('Y-m-d');
        }
        $daysInMonth = $carbonDate->daysInMonth;


        // --- 3. EFFICIENT DATABASE QUERY ---
        $allAttendances = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select(
                'attendance_details.*', 'emp_dets.name', 'emp_dets.empCode', 'emp_dets.jobJoingDate', 'emp_dets.lastDate',
                'emp_dets.startTime', 'emp_dets.endTime', 'emp_dets.id as attendEmpId', 'emp_dets.organisation', 'emp_dets.salaryScale',
                'emp_dets.bankAccountNo', 'emp_dets.bankIFSCCode', 'emp_dets.bankName'
                , 'designations.name as designationName', 'contactus_land_pages.branchName'
            )
            ->whereBetween('attendance_details.forDate', [$startDate, $endDate])
            ->where('emp_dets.branchId', $branchId)
            ->when($section, function ($q, $section) {
                return $q->where('departments.section', $section);
            })
            ->when($empCode, function ($q, $empCode) { // Apply empCode filter if provided
                return $q->where('emp_dets.empCode', $empCode);
            })
            ->orderBy('emp_dets.empCode')->orderBy('attendance_details.forDate')
            ->get();

        if ($allAttendances->isEmpty()) {
            return false; // Indicate no records found, let calling function handle redirect
        }

        $employeeIds = $allAttendances->pluck('attendEmpId')->unique();
        $dayChanges = EmpChangeDay::where('month', $finalMonth)
            ->whereIn('empId', $employeeIds)
            ->get()
            ->keyBy('empId');

        $retentions = Retention::where('month', $finalMonth)
            ->whereIn('empId', $employeeIds)
            ->get()
            ->keyBy('empId');

        $employeeAdvances = EmpAdvRs::where('startDate', '>=', $finalMonth)
            ->where('endDate', '<=', $finalMonth)
            ->where('status', 0)
            ->where('accountStatus', 1)
            ->whereIn('empId', $employeeIds)
            ->get()
            ->keyBy('empId');

        $empDebits = EmpDebit::where('forMonth', $finalMonth)
            ->whereIn('empId', $employeeIds)
            ->where('status', 0)
            ->get()
            ->keyBy('empId');

        // Calculate previous month for EmpMr query
        $previousMonth = $carbonDate->copy()->subMonth()->format('Y-m');

        // --- 4. PROCESS DATA WITH ALL BUSINESS RULES ---
        $attendancesByEmployee = $allAttendances->groupBy('empId');
        $processedEmployees = collect();

        foreach ($attendancesByEmployee as $empId => $employeeDays) {
            $employeeInfo = $employeeDays->first();

            // Parse joining and last dates to handle pre-joining/post-leaving periods.
            $joiningDate = $employeeInfo->jobJoingDate ? Carbon::parse($employeeInfo->jobJoingDate)->startOfDay() : null;
            $lastWorkingDate = $employeeInfo->lastDate ? Carbon::parse($employeeInfo->lastDate)->startOfDay() : null;

            $dailyDataMap = $employeeDays->keyBy(function ($day) {
                return Carbon::parse($day->forDate)->format('Y-m-d');
            });

            $processedDailyStatus = [];
            $sandwitchDayDed = 0;
            $weeklyRuleDeductions = 0.0;
            $lateMarkCount = 0;

            // region Business Rule Processing
            // =========================================================================
            // STEP 1: PRELIMINARY AND SANDWICH RULE PROCESSING
            // =========================================================================
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $currentDate = $carbonDate->copy()->day($d)->startOfDay();

                // Check if the day is outside the employment period.
                if (($joiningDate && $currentDate->lt($joiningDate)) || ($lastWorkingDate && $currentDate->gt($lastWorkingDate))) {
                    // Mark with a special status 'NE' (Not Employed). This will be handled in the final loop.
                    // This prevents these days from affecting any business rule calculations.
                    $processedDailyStatus[$d] = (object)['status' => 'NE', 'dayData' => null];
                    continue; // Skip all rule processing for this day.
                }

                $dayData = $dailyDataMap->get($currentDate->format('Y-m-d'));
                if (!$dayData) {
                    $processedDailyStatus[$d] = null;
                    continue;
                }
                $finalStatus = $dayData->dayStatus;

                if ($d == 1 && in_array($finalStatus, ['WO', 'LH'])) {
                    $otherDays = $employeeDays->filter(function ($day) use ($currentDate) {
                        return $day->forDate != $currentDate->format('Y-m-d');
                    });
                    if ($otherDays->isNotEmpty()) {
                        $presentDaysCount = $otherDays->filter(function ($day) {
                            return in_array($day->dayStatus, ['P', 'PL', 'PH', 'PLH']);
                        })->count();
                        if ($presentDaysCount <= 2) {
                            $finalStatus = 'A';
                            $sandwitchDayDed++;
                        }
                    }
                }

                if (in_array($finalStatus, ['WO', 'LH'])) {
                    $firstWorkingDayBefore = null;
                    $firstWorkingDayAfter = null;
                    for ($i = $d - 1; $i >= 1; $i--) {
                        $prevDayStatus = $processedDailyStatus[$i] ?? null;
                        if ($prevDayStatus && !in_array($prevDayStatus->status, ['WO', 'LH', 'NE'])) {
                            $firstWorkingDayBefore = $prevDayStatus;
                            break;
                        }
                    }
                    for ($i = $d + 1; $i <= $daysInMonth; $i++) {
                        $nextDay = $dailyDataMap->get($carbonDate->copy()->day($i)->format('Y-m-d'));
                        if ($nextDay && !in_array($nextDay->dayStatus, ['WO', 'LH'])) {
                            $firstWorkingDayAfter = (object)['status' => $nextDay->dayStatus, 'dayData' => $nextDay];
                            break;
                        }
                    }
                    if (
                        $firstWorkingDayBefore && $firstWorkingDayAfter &&
                        (in_array($firstWorkingDayBefore->status, ['A', '0']) && ($firstWorkingDayBefore->dayData->AGFStatus ?? 0) == 0) &&
                        (in_array($firstWorkingDayAfter->status, ['A', '0']) && ($firstWorkingDayAfter->dayData->AGFStatus ?? 0) == 0)
                    ) {
                        $finalStatus = 'A';
                        $sandwitchDayDed++;
                    }
                }
                $processedDailyStatus[$d] = (object)['status' => $finalStatus, 'dayData' => $dayData];
            }

            // =========================================================================
            // >>> NEW LOGIC START: START-OF-MONTH HOLIDAY RULE <<<
            // =========================================================================
            if ($daysInMonth >= 4) {
                $day1Info = $processedDailyStatus[1] ?? null;
                $day2Info = $processedDailyStatus[2] ?? null;
                $day3Info = $processedDailyStatus[3] ?? null;
                $day4Info = $processedDailyStatus[4] ?? null;
                $day5Info = $processedDailyStatus[5] ?? null;
                $day6Info = $processedDailyStatus[6] ?? null;

                $isDay1Holiday = $day1Info && in_array($day1Info->status, ['WO', 'LH', 'H']);

                $areNext5DaysAbsent = $day2Info && in_array($day2Info->status, ['A', '0']) && ($day2Info->dayData->AGFStatus ?? 0) == 0 &&
                                      $day3Info && in_array($day3Info->status, ['A', '0']) && ($day3Info->dayData->AGFStatus ?? 0) == 0 &&
                                      $day4Info && in_array($day4Info->status, ['A', '0']) && ($day4Info->dayData->AGFStatus ?? 0) == 0 &&
                                      $day5Info && in_array($day5Info->status, ['A', '0']) && ($day5Info->dayData->AGFStatus ?? 0) == 0 &&
                                      $day6Info && in_array($day6Info->status, ['A', '0']) && ($day6Info->dayData->AGFStatus ?? 0) == 0;

                if ($isDay1Holiday && $areNext5DaysAbsent) {
                    if (($processedDailyStatus[1]->status ?? '') !== 'NE') {
                        $processedDailyStatus[1]->status = 'A';
                        $sandwitchDayDed++;
                    }
                }
            }
            // >>> NEW LOGIC END <<<

            // =========================================================================
            // >>> NEW LOGIC START: Mark weekend as absent if Mon-Fri was absent <<<
            // =========================================================================
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $currentDate = $carbonDate->copy()->day($d);

                if ($currentDate->dayOfWeek == Carbon::SATURDAY && ($processedDailyStatus[$d]->status ?? '') !== 'NE') {
                    $mondayIndex = $d - 5;
                    $fridayIndex = $d - 1;

                    if ($mondayIndex < 1) {
                        continue;
                    }

                    $isFullWeekAbsent = true;
                    for ($i = $mondayIndex; $i <= $fridayIndex; $i++) {
                        $dayInfo = $processedDailyStatus[$i] ?? null;

                        if (!$dayInfo || !in_array($dayInfo->status, ['A', '0']) || (($dayInfo->dayData->AGFStatus ?? 0) != 0) || ($dayInfo->status === 'NE')) {
                            $isFullWeekAbsent = false;
                            break;
                        }
                    }

                    if ($isFullWeekAbsent) {
                        $saturdayInfo = $processedDailyStatus[$d] ?? null;
                        if ($saturdayInfo && in_array($saturdayInfo->status, ['WO', 'LH', 'H']) && $saturdayInfo->status !== 'NE') {
                            $saturdayInfo->status = 'A';
                            $sandwitchDayDed++;
                        }

                        $sundayIndex = $d + 1;
                        if ($sundayIndex <= $daysInMonth) {
                            $sundayInfo = $processedDailyStatus[$sundayIndex] ?? null;
                            if ($sundayInfo && in_array($sundayInfo->status, ['WO', 'LH', 'H']) && $sundayInfo->status !== 'NE') {
                                $processedDailyStatus[$sundayIndex]->status = 'A';
                                $processedDailyStatus[$sundayIndex]->sandwitchPolicy = '1';
                                $sandwitchDayDed++;
                            }
                        }
                    }
                }
            }
            // >>> NEW LOGIC END <<<

            // =========================================================================
            // STEP 2: WEEKLY ABSENCE RULE WITH HOLIDAY DEDUCTION
            // =========================================================================
            $weeklyConfig = [
                'ABSENT' => ['A', '0'], 'HALF_DAY' => ['PH', 'PLH'], 'HOLIDAY' => ['H', 'LH'],
                'WEEKLY_OFF' => 'WO', 'STANDARD_WORK_DAYS' => 6,
                'ABSENT_THRESHOLD_RATIO' => 3.5 / 6, 'HALF_DAY_THRESHOLD_RATIO' => 3.0 / 6,
            ];

            for ($d = 1; $d <= $daysInMonth; $d++) {
                $currentDate = $carbonDate->copy()->day($d);
                if ($currentDate->dayOfWeek == Carbon::SUNDAY && ($processedDailyStatus[$d]->status ?? '') === $weeklyConfig['WEEKLY_OFF'] && ($processedDailyStatus[$d]->status ?? '') !== 'NE') {
                    $sundayIndex = $d;
                    $sundayStatusInfo = $processedDailyStatus[$sundayIndex] ?? null;
                    
                    if ($sundayStatusInfo && $sundayStatusInfo->status == $weeklyConfig['WEEKLY_OFF']) {
                        $startOfWeek = $currentDate->copy()->subDays(6);
                        $endOfWeek = $currentDate->copy()->subDay();
                        $weeklyAbsenceCount = 0.0;
                        $weeklyHolidayCount = 0;

                        for ($weekDay = $startOfWeek->copy(); $weekDay->lte($endOfWeek); $weekDay->addDay()) {
                            if (!$weekDay->isSameMonth($carbonDate) || ($processedDailyStatus[$weekDay->day]->status ?? '') === 'NE') continue;
                            
                            $statusInfo = $processedDailyStatus[$weekDay->day] ?? null;
                            if (!$statusInfo) continue;

                            if (in_array($statusInfo->status, $weeklyConfig['ABSENT']) && ($statusInfo->dayData->AGFStatus ?? 0) == 0) $weeklyAbsenceCount += 1.0;
                            elseif (in_array($statusInfo->status, $weeklyConfig['HALF_DAY']) && ($statusInfo->dayData->AGFStatus ?? 0) == 0) $weeklyAbsenceCount += 0.5;
                            elseif (in_array($statusInfo->status, $weeklyConfig['HOLIDAY'])) $weeklyHolidayCount++;
                        }

                        $actualWorkDays = $weeklyConfig['STANDARD_WORK_DAYS'] - $weeklyHolidayCount;
                        if ($actualWorkDays > 0) {
                            $absentThreshold = $weeklyConfig['ABSENT_THRESHOLD_RATIO'] * $actualWorkDays;
                            $halfDayThreshold = $weeklyConfig['HALF_DAY_THRESHOLD_RATIO'] * $actualWorkDays;

                            if ($weeklyAbsenceCount >= $absentThreshold) {
                                $processedDailyStatus[$sundayIndex]->status = 'A';
                                $weeklyRuleDeductions += 1.0;
                            } elseif ($weeklyAbsenceCount >= $halfDayThreshold) {
                                $processedDailyStatus[$sundayIndex]->status = 'PH';
                                $weeklyRuleDeductions += 0.5;
                            }
                        }
                    }
                }
            }
            // endregion
            
            // =========================================================================
            // STEP 3: FINAL STATUS DETERMINATION AND TOTALS CALCULATION
            // =========================================================================
            $totals = ['present' => 0.0, 'absent' => 0.0, 'weekly_leave' => 0.0, 'extra_work' => 0.0];
            $lateMarkCount = 0; // Reset for each employee
            $finalDailyObjects = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $statusInfo = $processedDailyStatus[$d] ?? null;
                if (!$statusInfo) {
                    $finalDailyObjects[$d] = null;
                    continue;
                }

                // Handle 'Not Employed' days for final output.
                if ($statusInfo->status == 'NE') {
                    $finalDailyObjects[$d] = (object)[
                        'status' => '0', // Display as '0' as requested
                        'class' => 'attend-0',
                        'forDate' => $carbonDate->copy()->day($d)->format('Y-m-d'),
                        'officeInTime' => null, 'officeOutTime' => null, 'inTime' => null, 'outTime' => null,
                        'workingHr' => null, 'AGFStatus' => null, 'repAuthStatus' => null, 'HRStatus' => null,
                        'startTime' => null, 'endTime' => null, 'AGFDayStatus' => null
                    ];
                    // Do NOT add to any totals, and skip to the next day.
                    continue;
                }

                $finalStatus = $statusInfo->status;
                $dayData = $statusInfo->dayData;
                $isLate = false;

                // Ensure $dayData is not null before accessing its properties
                if ($dayData) {
                    if (in_array($finalStatus, ['P', 'PL', 'PLH', 'PH']) && $dayData->inTime && $dayData->outTime && $dayData->officeInTime && $dayData->officeOutTime) {
                        $officeStartTime = Carbon::parse($dayData->forDate . ' ' . $dayData->officeInTime);
                        $officeEndTime = Carbon::parse($dayData->forDate . ' ' . $dayData->officeOutTime);
                        $actualInTime = Carbon::parse($dayData->inTime);
                        $actualOutTime = Carbon::parse($dayData->outTime);
                        $requiredMinutes = $officeEndTime->diffInMinutes($officeStartTime);
                        $requiredHalfDayMinutes = $requiredMinutes / 2;
                        $actualMinutesWorked = $actualOutTime->diffInMinutes($actualInTime);

                        if ($actualMinutesWorked < $requiredHalfDayMinutes && ($dayData->AGFStatus ?? 0) == 0) {
                            $finalStatus = 'A';
                        } else {
                            $leftEarly = $actualOutTime->isBefore($officeEndTime->copy()->subMinutes(15));
                            
                            $shiftMidpoint = $officeStartTime->copy()->addMinutes($requiredHalfDayMinutes);
                            $workedInFirstHalf = $actualInTime->lt($shiftMidpoint);
                            $workedInSecondHalf = $actualOutTime->gt($shiftMidpoint);
                            $isHalfDayDueToShiftSpan = !($workedInFirstHalf && $workedInSecondHalf);
                            
                            if ($isHalfDayDueToShiftSpan && $leftEarly && ($dayData->AGFStatus ?? 0) == 0) {
                                $finalStatus = 'A';
                            } elseif (($isHalfDayDueToShiftSpan || $leftEarly) && ($dayData->AGFStatus ?? 0) == 0) {
                                $finalStatus = 'PH';
                            } else {
                                if ($actualInTime->isAfter($officeStartTime->copy()->addMinutes(7)) && ($dayData->AGFStatus ?? 0) == 0) {
                                    if ($finalStatus == 'P') $finalStatus = 'PL'; 

                                    if($finalStatus != 'PH')
                                        $isLate = true;
                                        
                                } else {
                                    if (($dayData->AGFDayStatus ?? '') == 'Full Day')
                                        $finalStatus = 'P';
                                    else
                                        $finalStatus = 'PH';
                                }
                            }
                        }
                    } else {
                        if (($finalStatus == 'A' || $finalStatus == '0') && ($dayData->AGFStatus ?? 0) != 0) {
                            if (($dayData->AGFDayStatus ?? '') == 'Full Day')
                                $finalStatus = 'P';
                            else
                                $finalStatus = 'PH';
                        } else {
                            // This block ensures that if AGFStatus is 0 (not approved) and holiday is 0, it's 'A'
                            // or if it's a holiday, it remains its holiday status.
                            if (($dayData->AGFDayStatus ?? '') == 'Full Day' && ($dayData->AGFStatus ?? 0) != 0 && ($dayData->holiday ?? 0) == 0)
                                $finalStatus = 'P';
                            else if (($dayData->AGFDayStatus ?? '') == 'Half Day' && ($dayData->AGFStatus ?? 0) != 0 && ($dayData->holiday ?? 0) == 0)
                                $finalStatus = 'PH';
                            else if (($dayData->holiday ?? 0) == 0) // If not a holiday and not AGF approved, it's Absent
                                $finalStatus = 'A';
                        }
                    }

                    // This block handles cases where an original PH/PLH status gets AGF approved
                    if ((($dayData->dayStatus ?? '') == 'PH' || ($dayData->dayStatus ?? '') == 'PLH') && ($dayData->AGFStatus ?? 0) != 0) {
                        if (($dayData->AGFDayStatus ?? '') == 'Full Day')
                            $finalStatus = 'P';
                        else
                            $finalStatus = 'PH';
                    }
                }
                
                // Moved late mark counting to the switch statement to ensure accuracy.
                // The $isLate flag ensures lateMarkCount is incremented only once per late day.
                if ($isLate) $lateMarkCount++;

                // Extra work on holiday calculation
                if (in_array($finalStatus, ['WO', 'LH']) && ($dayData->AGFStatus ?? 0) != 0) {
                    $application = EmpApplication::where('id', $dayData->AGFStatus)->where('reason', 'Extra Working on Holiday')->count();
                    if($application)
                        $totals['extra_work'] += (($dayData->AGFDayStatus ?? '') == 'Full Day') ? 1.0 : 0.5;
                }

                switch ($finalStatus) {
                    case 'P':
                        $totals['present'] += 1.0;
                        break;
                    case 'PL':
                        $totals['present'] += 1.0;
                        // lateMarkCount is handled by $isLate flag above
                        break;
                    case 'A':
                    case '0':
                        $totals['absent'] += 1.0;
                        break;
                    case 'PH':
                    case 'PLH':
                        $totals['present'] += 0.5;
                        $totals['absent'] += 0.5;
                        // lateMarkCount is handled by $isLate flag above
                        break;
                    case 'WO':
                    case 'LH':
                        $totals['weekly_leave'] += 1.0;
                        // Extra work already handled above
                        break;
                }
                $finalDailyObjects[$d] = (object)[
                    'officeInTime' => $dayData->officeInTime ?? null, 'officeOutTime' => $dayData->officeOutTime ?? null, 'status' => $finalStatus, 
                    'class' => 'attend-'.$finalStatus, 'forDate' => $dayData->forDate ?? null, 'inTime' => $dayData->inTime ?? null,
                    'outTime' => $dayData->outTime ?? null, 'workingHr' => $dayData->workingHr ?? null,
                    'deviceInTime' => $dayData->deviceInTime ?? null, 'deviceOutTime' => $dayData->deviceOutTime ?? null,
                    'AGFStatus' => $dayData->AGFStatus ?? null, 'repAuthStatus' => $dayData->repAuthStatus ?? null, 'HRStatus' => $dayData->HRStatus ?? null,
                    'startTime'=>$dayData->startTime ?? null, 'endTime'=>$dayData->endTime ?? null, 'AGFDayStatus' => $dayData->AGFDayStatus ?? null,
                    'sandwitchPolicy' => $processedDailyStatus[$d]->sandwitchPolicy ?? null // Added for potential future use/tracking
                ];
            }

            $lateMarkDeduction = floor($lateMarkCount / 3);
            if ($lateMarkDeduction > 0) {
                $totals['present'] -= $lateMarkDeduction;
            }
            
            $totals['late_mark_deductions'] = $lateMarkDeduction;
            $totals['sandwitch_deductions'] = $sandwitchDayDed;
            $totals['weekly_rule_deductions'] = $weeklyRuleDeductions;
            $totals['total_deductions'] = $lateMarkDeduction + $sandwitchDayDed + $weeklyRuleDeductions;
            $totals['present'] = $totals['present'] + $totals['weekly_leave'];
            $totals['final_total'] = $totals['present'] + $totals['extra_work'];
            $totals['absent'] = $totals['absent'] - ($sandwitchDayDed + $weeklyRuleDeductions);

            $changeData = $dayChanges->get($employeeInfo->attendEmpId);
            $totals['is_edited'] = false;
            if ($changeData) {
                $totals['is_edited'] = true;
                $totals['remark'] = $changeData->remark;
                $totals['new_present'] = $changeData->newPresentDays;
                $totals['new_absent'] = $changeData->newAbsentDays;
                $totals['new_wl'] = $changeData->newWLDays;
                $totals['new_extra_work'] = $changeData->newExtraDays;
                $totals['new_final_total'] = $changeData->newDays;
            }

            // Fetch and assign additional employee-specific data
            // $retentionData = Retention::where('month', $finalMonth)->where('empId', $empId)->sum('retentionAmount');
            $retentionData = $retentions->get($empId);
            $employeeAdvanceData = $employeeAdvances->get($empId);
            $empDebitData = $empDebits->get($empId);

            $totals['empCode'] = $employeeInfo->empCode;
            $totals['organisation'] = $employeeInfo->organisation;
            $totals['retention'] = ($retentionData->retentionAmount) ?? 0;
            // Dynamic previous month for gross salary
            $totals['prevGrossSalary'] = (EmpMr::where('empId', $employeeInfo->attendEmpId)->where('forDate', $previousMonth)->value('grossSalary')) ?? 0;
            $totals['grossSalary'] = $employeeInfo->salaryScale;
            $totals['advanceAgainstSalary'] = ($employeeAdvanceData->deduction) ?? 0;
            $totals['otherDeduction'] = ($empDebitData->amount) ?? 0;
            $totals['bankAccountNo'] = $employeeInfo->bankAccountNo;
            $totals['bankIFSCCode'] = $employeeInfo->bankIFSCCode;
            $totals['bankName'] = $employeeInfo->bankName;
            $totals['salaryStatus'] = 0; // Defaulting to 0 as per your previous code

            $employeeInfo->finalSalaryStatus = $employeeDays->last()->salaryHoldRelease ?? 0;
            $processedEmployees->push(['info' => $employeeInfo, 'days' => $finalDailyObjects, 'totals' => $totals]);
        }

        // Apply search query filter if section is provided and a search query exists
        // This search query is for the overall list, not for individual employee processing
        // It's better to apply this filter in the calling function (`search` or `finalAttendanceSheet`)
        // if it's meant for filtering the displayed list. If it's meant to filter the *data processed*,
        // then it should be passed as a parameter to this function.
        // For now, removing it from here to keep this helper focused on core calculation.
        // If ($searchQuery = request()->input('search_query')) {
        //     $processedEmployees = $processedEmployees->filter(function ($employee) use ($searchQuery) {
        //         return str_contains(strtolower($employee['info']->name), strtolower($searchQuery)) ||
        //             str_contains(strtolower($employee['info']->empCode), strtolower($searchQuery));
        //     });
        // }
        
        return [
            'processedEmployees' => $processedEmployees,
            'carbonDate' => $carbonDate,
            'daysInMonth' => $daysInMonth
        ];
    }

    //------------------------------------------------------------------------------------------------------------------
    // Public Methods (Controller Actions)
    //------------------------------------------------------------------------------------------------------------------

    /**
     * Displays the attendance search form and results.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function search(Request $request)
    {
        // --- 1. SETUP AND VALIDATION ---
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $finalMonth = $request->input('month');
        $userType = Auth::user()->userType;
        $empId = Auth::user()->empId;
        $section = $request->section; // Section is optional
        $branchId = $request->branchId; // Branch ID is optional initially

        $empCode = $request->empCode; // Get empCode from request for filtering

        if ($userType == '11' || $userType == '21' || $userType == '31') {
            $empCode = EmpDet::where('id', $empId)->value('empCode');
            // For these user types, branchId might also be tied to the user's branch
            $branchId = EmpDet::where('id', $empId)->value('branchId'); // Assuming user's branch is needed
        }

        // If month is not filled, just show the form
        if (!$request->filled('month')) {
            return view('admin.attendance.finalAttendanceSheet')->with([ // Assuming 'finalAttendanceSheet' is the view for search too
                'attendances' => null, 'branches' => $branches, 'userType' => $userType,
                'section' => $section,
                'branchId' => $branchId, 'finalMonth' => $finalMonth,
                'empCode' => $empCode,
            ]);
        }

        // Validate branchId and month are present for calculation
        if (!$request->filled('branchId') && !($userType == '11' || $userType == '21' || $userType == '31')) {
             return redirect()->back()->withInput()->with("error", "Branch is required to search attendance.");
        }
        if (!$request->filled('month')) {
            return redirect()->back()->withInput()->with("error", "Month is required to search attendance.");
        }

        // Conditionally apply 'where('section', ...)' for AttendanceJob queries
        if ($userType == '501') {
            $attendanceConfStatusQuery = AttendanceJob::where('userType', '51')
                ->where('fBranchId', $branchId)
                ->where('fMonth', $finalMonth);
            
            if ($request->filled('section')) {
                $attendanceConfStatusQuery->where('section', $section);
            }
            $attendanceConfStatus = $attendanceConfStatusQuery->count();

            if (!$attendanceConfStatus)
                return redirect()->back()->withInput()->with("error", "HR Department Still not confirmed Selected Branch...");
        }

        if ($userType == '61') {
            $attendanceConfStatusQuery = AttendanceJob::where('userType', '501')
                ->where('fBranchId', $branchId)
                ->where('fMonth', $finalMonth);
            
            if ($request->filled('section')) {
                $attendanceConfStatusQuery->where('section', $section);
            }
            $attendanceConfStatus = $attendanceConfStatusQuery->count();

            if (!$attendanceConfStatus)
                return redirect()->back()->withInput()->with("error", "Higher Authority Still not confirmed Selected Branch...");
        }
        
        // Call the new private helper method to get processed data
        // Pass empCode to the helper for initial filtering if applicable
        $processedResult = $this->_calculateAttendanceData($branchId, $finalMonth, $section, $empCode);

        if ($processedResult === false) {
            return redirect()->back()->withInput()->with("error", "Invalid month format or no records found for the selected criteria.");
        }

        $processedEmployees = $processedResult['processedEmployees'];
        $daysInMonth = $processedResult['daysInMonth'];
        $carbonDate = $processedResult['carbonDate'];

        // Apply search query filter (if any, separate from empCode filter)
        // The original `search` method had `empCode` as a direct filter.
        // If `searchQuery` is for general name/code search, apply it here.
        $searchQuery = $request->search_query; // Assuming search_query is for general search
        if ($searchQuery) {
            $processedEmployees = $processedEmployees->filter(function ($employee) use ($searchQuery) {
                return str_contains(strtolower($employee['info']->name), strtolower($searchQuery)) ||
                       str_contains(strtolower($employee['info']->empCode), strtolower($searchQuery));
            });
        }
        
        // --- 5. SUMMARIZE AND PAGINATE ---
        $summaryStats = [
            'total_employees' => $processedEmployees->count(),
            'total_present' => $processedEmployees->sum(function ($emp) {
                return ($emp['totals']['present']);
            }),
            'total_absent' => $processedEmployees->sum(function ($emp) {
                return $emp['totals']['absent'];
            }),
            'total_deductions' => $processedEmployees->sum(function ($emp) {
                return $emp['totals']['total_deductions'];
            }),
            'total_extra_work' => $processedEmployees->sum(function ($emp) {
                return $emp['totals']['extra_work'];
            }),
        ];

        $perPage = 25; // Original search method used 25
        $paginatedResult = new LengthAwarePaginator(
            $processedEmployees->forPage(LengthAwarePaginator::resolveCurrentPage('page'), $perPage)->values(),
            $processedEmployees->count(),
            $perPage > 0 ? $perPage : 1,
            LengthAwarePaginator::resolveCurrentPage('page'),
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Attendance confirmation status for view
        $attendanceConfStatusQuery = AttendanceJob::where('userType', $userType)
            ->where('fBranchId', $branchId)
            ->where('fMonth', $finalMonth);
        
        if ($request->filled('section')) {
            $attendanceConfStatusQuery->where('section', $section);
        }
        $attendanceConfStatus = $attendanceConfStatusQuery->count();

        return view('admin.attendance.list')->with([ // Original search method returned 'admin.attendance.list'
            'attendances' => $paginatedResult, 'daysInMonth' => $daysInMonth, 'carbonDate' => $carbonDate,
            'attendanceConfStatus' => $attendanceConfStatus, 'branches' => $branches, 'userType' => $userType,
            'section' => $section, 'branchId' => $branchId,
            'finalMonth' => $finalMonth, 'summaryStats' => $summaryStats, 'empCode' => $empCode
        ]);
    }

    /**
     * Displays the final attendance sheet.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function finalAttendanceSheet(Request $request)
    {
        // --- 1. SETUP AND VALIDATION ---
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $finalMonth = $request->input('month');
        $userType = Auth::user()->userType;
        $searchQuery = $request->input('search_query');
        $section = $request->section; // Section is now optional
        $branchId = $request->branchId; // Branch ID

        if (!$request->filled('branchId') || !$request->filled('month')) {
            return view('admin.attendance.finalAttendanceSheet')->with([
                'attendances' => null, 'branches' => $branches, 'userType' => $userType,
                'section' => $section,
                'branchId' => $branchId, 'finalMonth' => $finalMonth,
                'searchQuery' => $searchQuery,
            ]);
        }

        // Conditionally apply 'where('section', ...)' for AttendanceJob queries
        if ($userType == '501') {
            $attendanceConfStatusQuery = AttendanceJob::where('userType', '51')
                ->where('fBranchId', $branchId)
                ->where('fMonth', $finalMonth);
            
            if ($request->filled('section')) {
                $attendanceConfStatusQuery->where('section', $section);
            }
            $attendanceConfStatus = $attendanceConfStatusQuery->count();

            if (!$attendanceConfStatus)
                return redirect()->back()->withInput()->with("error", "HR Department Still not confirmed Selected Branch...");
        }

        if ($userType == '61') {
            $attendanceConfStatusQuery = AttendanceJob::where('userType', '501')
                ->where('fBranchId', $branchId)
                ->where('fMonth', $finalMonth);
            
            if ($request->filled('section')) {
                $attendanceConfStatusQuery->where('section', $section);
            }
            $attendanceConfStatus = $attendanceConfStatusQuery->count();

            if (!$attendanceConfStatus)
                return redirect()->back()->withInput()->with("error", "Higher Authority Still not confirmed Selected Branch...");
        }

        // Call the new private helper method to get processed data
        $processedResult = $this->_calculateAttendanceData($branchId, $finalMonth, $section);

        if ($processedResult === false) {
            return redirect()->back()->withInput()->with("error", "Invalid month format or no records found for the selected criteria.");
        }

        $processedEmployees = $processedResult['processedEmployees'];
        $daysInMonth = $processedResult['daysInMonth'];
        $carbonDate = $processedResult['carbonDate'];

        // Apply search query filter (if any)
        if ($searchQuery) {
            $processedEmployees = $processedEmployees->filter(function ($employee) use ($searchQuery) {
                return str_contains(strtolower($employee['info']->name), strtolower($searchQuery)) ||
                    str_contains(strtolower($employee['info']->empCode), strtolower($searchQuery));
            });
        }

        // --- EXPORT TO EXCEL LOGIC ---
        if ($request->has('export') && $request->input('export') === 'excel') {
            $fileName = 'Attendance_Sheet_' . $finalMonth . '_' . ($branches[$branchId] ?? 'All') . '.xlsx';
            return Excel::download(new FinalAttendanceExport($processedEmployees, $daysInMonth, $carbonDate), $fileName);
        }

        // --- 5. SUMMARIZE AND PAGINATE (only if not exporting) ---
        $summaryStats = [
            'total_employees' => $processedEmployees->count(),
            'total_present' => $processedEmployees->sum(function ($emp) {
                return ($emp['totals']['present']);
            }),
            'total_absent' => $processedEmployees->sum(function ($emp) {
                return $emp['totals']['absent'];
            }),
            'total_deductions' => $processedEmployees->sum(function ($emp) {
                return $emp['totals']['total_deductions'];
            }),
            'total_extra_work' => $processedEmployees->sum(function ($emp) {
                return $emp['totals']['extra_work'];
            }),
        ];

        $perPage = 50; // Original finalAttendanceSheet used 50
        $paginatedResult = new LengthAwarePaginator(
            $processedEmployees->forPage(LengthAwarePaginator::resolveCurrentPage('page'), $perPage)->values(),
            $processedEmployees->count(),
            $perPage > 0 ? $perPage : 1,
            LengthAwarePaginator::resolveCurrentPage('page'),
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Attendance confirmation status for view
        $attendanceConfStatusQuery = AttendanceJob::where('userType', $userType)
            ->where('fBranchId', $branchId)
            ->where('fMonth', $finalMonth);
        
        if ($request->filled('section')) {
            $attendanceConfStatusQuery->where('section', $section);
        }
        $attendanceConfStatus = $attendanceConfStatusQuery->count();

        return view('admin.attendance.finalAttendanceSheet')->with([
            'attendances' => $paginatedResult, 'daysInMonth' => $daysInMonth, 'carbonDate' => $carbonDate,
            'attendanceConfStatus' => $attendanceConfStatus, 'branches' => $branches, 'userType' => $userType,
            'section' => $section, 'branchId' => $branchId, 'finalMonth' => $finalMonth,
            'summaryStats' => $summaryStats, 'searchQuery' => $searchQuery
        ]);
    }

    /**
     * Handles the confirmation of attendance by different user roles.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmation(Request $request)
    {
        // 1. Common Validation & Date Setup
        $request->validate([
            'fMonth' => 'required|string',
            'fBranchId' => 'required|integer',
            'fSection' => 'nullable|string', // Changed to nullable
        ]);

        try {
            $carbonDate = Carbon::createFromFormat('Y-m', $request->fMonth);
            $startDate = $carbonDate->copy()->startOfMonth()->format('Y-m-d');
            $endDate = $carbonDate->copy()->endOfMonth()->format('Y-m-d');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid month format.');
        }

        // 2. Common Query Building
        $employeeIdsQuery = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->where('emp_dets.branchId', $request->fBranchId)
            ->whereBetween('attendance_details.forDate', [$startDate, $endDate])
            ->when($request->filled('fSection'), function ($q) use ($request) {
                return $q->where('departments.section', $request->fSection);
            })
            ->when($request->filled('fOrganisation'), function ($q) use ($request) {
                return $q->where('emp_dets.organisation', $request->fOrganisation);
            })
            ->when($request->filled('fSearch_query'), function ($q) use ($request) {
                $searchQuery = $request->fSearch_query;
                return $q->where(function ($subQuery) use ($searchQuery) {
                    $subQuery->where('emp_dets.name', 'like', "%{$searchQuery}%")
                             ->orWhere('emp_dets.empCode', 'like', "%{$searchQuery}%");
                });
            });

        $employeeIds = $employeeIdsQuery->distinct()->pluck('emp_dets.id');

        if ($employeeIds->isEmpty()) {
            return redirect()->back()->with('error', 'No employees found to confirm.');
        }

        // 3. Determine Update Data based on User Role
        $userType = Auth::user()->userType;
        $updateData = [];

        switch ($userType) {
            case '51': // HR Role
                $updateData = [
                    'HRConfirmStatus' => 1,
                    'HRUpdatedAt' => now(),
                    'HRUpdatedBy' => Auth::user()->username
                ];
                break;
            
            case '501': // Higher Authority Role
                $updateData = [
                    'highAuthConfirmStatus' => 1,
                    'highAuthUpdatedAt' => now(),
                    'highAuthUpdatedBy' => Auth::user()->username
                ];
                break;
            
            case '61': // Accounts Role
                $updateData = [
                    'accountConfirmStatus' => 1,
                    'accountUpdatedAt' => now(),
                    'accountUpdatedBy' => Auth::user()->username
                ];
                break;

            default:
                return redirect()->back()->with('error', 'You are not authorized to perform this action.');
        }

        // 4. Perform the Mass Update
        AttendanceDetail::whereIn('empId', $employeeIds)
            ->whereBetween('forDate', [$startDate, $endDate])
            ->update($updateData);
        
        // 5. Create the Confirmation Log
        $attendanceJobWhere = [
            'fBranchId' => $request->fBranchId,
            'fMonth' => $request->fMonth,
            'userType' => $userType
        ];

        if ($request->filled('fSection')) {
            $attendanceJobWhere['section'] = $request->fSection;
        }

        $attendanceJobData = [
            'status' => '2',
            'updated_by' => Auth::user()->username
        ];

        AttendanceJob::updateOrCreate($attendanceJobWhere, $attendanceJobData);
        
        return redirect()->back()->with('success', "Attendance confirmed successfully for {$employeeIds->count()} employees.");
    }

    /**
     * Updates final attendance days based on manual input.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateFinalDays(Request $request)
    {
        return $request->all();
        // return $request->all(); // Keep for debugging if needed
        $validator = Validator::make($request->all(), [
            'empId' => 'required|integer|exists:emp_dets,id',
            'month' => 'required|date_format:Y-m',
            'newPresentDays' => 'required|numeric',
            'newAbsentDays' => 'required|numeric',
            'newWLDays' => 'required|numeric',
            'newExtraDays' => 'required|numeric',
            'newDays' => 'required|numeric',
            'remark' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            EmpChangeDay::updateOrCreate(
                [
                    'empId' => $request->empId,
                    'month' => $request->month,
                ],
                [
                    'newPresentDays' => $request->newPresentDays,
                    'newAbsentDays' => $request->newAbsentDays,
                    'newWLDays' => $request->newWLDays,
                    'newExtraDays' => $request->newExtraDays,
                    'newDays' => $request->newDays,
                    'remark' => $request->remark,
                    'updatedBy' => Auth::id(),
                ]
            );

            return redirect()->back()->with('success', 'Final attendance days updated successfully!');

        } catch (\Exception $e) {
            \Log::error("Error updating final days: " . $e->getMessage()); // Log the error
            return redirect()->back()->with('error', 'Failed to update attendance. Please try again.');
        }
    }

    /**
     * Processes and saves the final attendance summary to MonthlyAttendanceSummary.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processAndSaveAttendance(Request $request)
    {
        $request->validate([
            'fMonth' => 'required|string',
            'fBranchId' => 'required|integer',
            'fSection' => 'nullable|string', // Made nullable
        ]);

        $branchId = $request->fBranchId;
        $month = $request->fMonth;
        $section = $request->fSection; // Will be null if not provided

        // Call the private helper method to get processed data
        $processedResult = $this->_calculateAttendanceData($branchId, $month, $section);
        
        if ($processedResult === false || $processedResult['processedEmployees']->isEmpty()) {
            return redirect()->back()->with("error", "No employees found or invalid month format to process for the selected criteria.");
        }
        
        $processedEmployees = $processedResult['processedEmployees'];

        foreach ($processedEmployees as $employeeData) {
            $info = $employeeData['info'];
            $totals = $employeeData['totals'];
            
            $query1=[
                'empId' => $info->attendEmpId,
                'month' => $month
            ];
            $query2=[
                'empCode'            => $info->empCode,
                'branchId'           => $branchId,
                'organisation'       => $info->organisation ?? null,
                'presentDays'        => ($totals['is_edited'] == true) ? ($totals['new_present'] ?? 0) : ($totals['present'] ?? 0),
                'absentDays'         => ($totals['is_edited'] == true) ? ($totals['new_absent'] ?? 0) : ($totals['absent'] ?? 0),
                'WLeaveDays'         => ($totals['is_edited'] == true) ? ($totals['new_wl'] ?? 0) : ($totals['weekly_leave'] ?? 0),
                'extraWorkDays'      => ($totals['is_edited'] == true) ? ($totals['new_extra_work'] ?? 0) : ($totals['extra_work'] ?? 0),
                'totalDeductions'    => ($totals['is_edited'] == true) ? ($totals['new_wl'] ?? 0) : ($totals['total_deductions'] ?? 0),
                'retention'          => $totals['retention'] ?? 0,
                'payableDays'        => ($totals['is_edited'] == true) ? ($totals['new_final_total'] ?? 0) : ($totals['final_total'] ?? 0),
                'grossSalary'        => $totals['grossSalary'] ?? 0,
                'prevGrossSalary'    => $totals['prevGrossSalary'] ?? 0,
                'advanceAgainstSalary'=> $totals['advanceAgainstSalary'] ?? 0,
                'otherDeduction'     => $totals['otherDeduction'] ?? 0,
                'bankAccountNo'      => $totals['bankAccountNo'] ?? null,
                'bankIFSCCode'       => $totals['bankIFSCCode'] ?? null,
                'bankName'           => $totals['bankName'] ?? null,
                'salaryStatus'       => $info->finalSalaryStatus ?? 0,
                'isManuallyEdited'   => $totals['is_edited'],
            ];

            MonthlyAttendanceSummary::updateOrCreate($query1, $query2); 
        }

        // Update AttendanceJob status
        $attendanceJobData = [
            'status' => '2',
            'updated_by' => Auth::user()->username
        ];

        $attendanceJobWhere = [
            'fBranchId' => $branchId,
            'fMonth' => $month,
            'userType' => Auth::user()->userType
        ];

        if ($request->filled('fSection')) {
            $attendanceJobWhere['section'] = $request->fSection;
        }

        AttendanceJob::updateOrCreate($attendanceJobWhere, $attendanceJobData);

        return redirect()->back()->with("success", "Attendance for " . Carbon::parse($month)->format('F Y') . " has been successfully processed and saved.");
    }

    /**
     * Displays the form for editing a single employee's final attendance sheet.
     *
     * @param Request $request
     * @param int $empId
     * @param string $month
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function editFinalAttendanceSheet(Request $request, $empId, $month)
    {
        // --- 1. SETUP AND VALIDATION ---
        $finalMonth = $month;
        $finalEmpId = $empId;
        $userType = Auth::user()->userType;

        // For this method, we are fetching a specific employee's attendance
        // so we need their branch and section to pass to the helper.
        $employeeDetail = EmpDet::where('id', $finalEmpId)->first();

        if (!$employeeDetail) {
            return redirect()->back()->withInput()->with("error", "Employee not found.");
        }

        $branchId = $employeeDetail->branchId;
        $section = Department::where('id', $employeeDetail->departmentId)->value('section'); // Get section from department

        // Call the private helper method to get processed data for this specific employee
        $processedResult = $this->_calculateAttendanceData($branchId, $finalMonth, $section, $employeeDetail->empCode);

        if ($processedResult === false || $processedResult['processedEmployees']->isEmpty()) {
            return redirect()->back()->withInput()->with("error", "Invalid month format or no records found for the selected employee and month.");
        }

        // Since we filtered by empCode in _calculateAttendanceData, there should be only one employee
        $employeeData = $processedResult['processedEmployees']->first();
        $daysInMonth = $processedResult['daysInMonth'];
        $carbonDate = $processedResult['carbonDate'];

        // Pass the data to the view.
        return view('admin.attendance.editAuthorityAttendanceSheet')->with([
            'employeeData' => $employeeData,
            'daysInMonth' => $daysInMonth,
            'carbonDate' => $carbonDate,
            'userType' => $userType,
            'finalEmpId' => $finalEmpId,
            'finalMonth' => $finalMonth
        ]);
    }

    /**
     * Updates manually changed final attendance days.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateFinalAttendance(Request $request)
    {
        // return $request->all();
        $userType = Auth::user()->userType;

        // Validate input for the update
        $validator = Validator::make($request->all(), [
            'empId' => 'required|integer|exists:emp_dets,id',
            'finalMonth' => 'required|date_format:Y-m',
            'oldPresentDays' => 'required|numeric',
            'presentDays' => 'required|numeric',
            'oldAbsentDays' => 'required|numeric',
            'absentDays' => 'required|numeric',
            'oldWLDays' => 'required|numeric',
            'WLDays' => 'required|numeric',
            'oldExtraWorkDays' => 'required|numeric',
            'extraWorkDays' => 'required|numeric',
            'oldPayableDays' => 'required|numeric',
            'payableDays' => 'required|numeric',
            'remark' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($userType == '501') {
            $empDetail = EmpDet::where('id', $request->empId)->first();
            if (!$empDetail) {
                return redirect()->back()->withInput()->with("error", "Employee not found.");
            }
            $section = Department::where('id', $empDetail->departmentId)->value('section');

            $change = EmpChangeDay::where('empId', $empDetail->id)->where('month', $request->finalMonth)->first();
            if (!$change) {
                $change = new EmpChangeDay;
            }

            $change->empId = $empDetail->id;
            $change->oldPresentDays = $request->oldPresentDays;
            $change->newPresentDays = $request->presentDays; // Corrected to newPresentDays
            $change->oldAbsentDays = $request->oldAbsentDays;
            $change->newAbsentDays = $request->absentDays; // Corrected to newAbsentDays
            $change->oldWLDays = $request->oldWLDays;
            $change->newWLDays = $request->WLDays; // Corrected to newWLDays
            $change->oldExtraDays = $request->oldExtraWorkDays;
            $change->newExtraDays = $request->extraDays;
            $change->oldDays = $request->oldPayableDays;
            $change->newDays = $request->payableDays;
            $change->remark = $request->remark;
            $change->month = $request->finalMonth;
            $change->updated_by = Auth::user()->username;
            $change->save();

            $path = '/empAttendances/finalAttendanceSheet?section=' . ($section ?? '') . '&branchId=' . $empDetail->branchId . '&month=' . $request->finalMonth . '&flag=2';

            return redirect($path)->with("success", "Employee Days Updated successfully..");
        } else {
            return redirect()->back()->withInput()->with("error", "You are not authorized to access this function.");
        }
    }
   
    public function confirmSheetList(Request $request)
    {
        $forMonth = $request->forMonth;
        if ($forMonth == '')
            $forMonth = date('Y-m');

        $jobs = AttendanceJob::join('contactus_land_pages', 'attendance_jobs.fBranchId', 'contactus_land_pages.id')
            ->select('contactus_land_pages.branchName', 'attendance_jobs.*')
            ->where('attendance_jobs.fMonth', $forMonth)
            ->get();
        return view('admin.attendance.jobHistory', compact('jobs', 'forMonth'));
    }

    // public function search(Request $request)
    // {
    //     // --- 1. SETUP AND VALIDATION ---
    //     $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
    //     $finalMonth = $request->input('month');
    //     $userType = Auth::user()->userType;
    //     $empId = Auth::user()->empId;
    //     if($userType == '11' || $userType == '21' || $userType == '31')
    //     {
    //         $empCode = EmpDet::where('id', $empId)->value('empCode');
    //     }
    //     else
    //     {
    //         $empCode = $request->empCode;
    //     }

    //     if (!$request->filled('month')) {
    //         return view('admin.attendance.finalAttendanceSheet')->with([
    //             'attendances' => null, 'branches' => $branches, 'userType' => $userType,
    //             'section' => $request->section,
    //             'branchId' => $request->branchId, 'finalMonth' => $finalMonth,
    //             'empCode' => $empCode,
    //         ]);
    //     }

    //     try {
    //         // Create a Carbon instance from the 'YYYY-MM' format and set it to the 1st day.
    //         $carbonDate = Carbon::createFromFormat('Y-m', $finalMonth)->startOfMonth();
    //     } catch (\Exception $e) {
    //         // If the month format is invalid, redirect back with an error.
    //         return redirect()->back()->withInput()->with("error", "Invalid month format provided.");
    //     }

    //     // These variables can be defined once, as their logic is consistent.
    //     $startDate = $carbonDate->copy()->format('Y-m-d'); // This is always 'YYYY-MM-01'
    //     $daysInMonth = $carbonDate->daysInMonth; // Total days in the selected month

    //     // Conditionally set the end date based on the new requirement.
    //     if ($finalMonth == date('Y-m')) {
    //         // If it's the current month, the end date is today.
    //         $endDate = Carbon::now()->format('Y-m-d');
    //     } else {
    //         // For any other month, the end date is the last day of that month.
    //         $endDate = $carbonDate->copy()->endOfMonth()->format('Y-m-d');
    //     }

    //     if($userType == '501')
    //     {
    //         $attendanceConfStatus = AttendanceJob::where('userType', '51')
    //         ->where('fBranchId', $request->branchId)
    //         ->where('fMonth', $finalMonth)
    //         ->where('section', $request->section)
    //         ->count();
    //         if(!$attendanceConfStatus)
    //             return redirect()->back()->withInput()->with("error", "HR Department Still not confirmed Selected Branch...");
    //     }

    //     if($userType == '61')
    //     {
    //         $attendanceConfStatus = AttendanceJob::where('userType', '501')
    //         ->where('fBranchId', $request->branchId)
    //         ->where('fMonth', $finalMonth)
    //         ->where('section', $request->section)
    //         ->count();
    //         if(!$attendanceConfStatus)
    //             return redirect()->back()->withInput()->with("error", "Higher Authority Still not confirmed Selected Branch...");
    //     }
        
    //     // --- 3. EFFICIENT DATABASE QUERY ---
    //     $allAttendances = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
    //     ->join('designations', 'emp_dets.designationId', 'designations.id')
    //     ->join('departments', 'emp_dets.departmentId', 'departments.id')
    //     ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
    //     ->select(
    //         'attendance_details.*', 'emp_dets.name', 'emp_dets.empCode', 'emp_dets.jobJoingDate', 'emp_dets.lastDate',
    //         'emp_dets.startTime', 'emp_dets.endTime','emp_dets.id as attendEmpId',
    //         'designations.name as designationName', 'contactus_land_pages.branchName'
    //     )
    //     ->whereBetween('attendance_details.forDate', [$startDate, $endDate]);

    //     if($empCode != '')
    //         $allAttendances = $allAttendances->where('emp_dets.empCode', $empCode);
 
    //     if($request->branchId != '')
    //         $allAttendances = $allAttendances->where('emp_dets.branchId', $request->branchId);
 
    //     if($request->section != '')
    //        $allAttendances = $allAttendances->when($request->section, function ($q, $section) { return $q->where('departments.section', $section); });

    //     $allAttendances = $allAttendances->orderBy('emp_dets.empCode')->orderBy('attendance_details.forDate')
    //     ->get();

    //     if ($allAttendances->isEmpty()) {
    //         return redirect()->back()->withInput()->with("error", "Record Not Found.");
    //     }

    //     $employeeIds = $allAttendances->pluck('attendEmpId')->unique();
    //     $dayChanges = EmpChangeDay::where('month', $finalMonth)
    //         ->whereIn('empId', $employeeIds)
    //         ->get()
    //         ->keyBy('empId');
        
    //     // --- 4. PROCESS DATA WITH ALL BUSINESS RULES ---
    //     $attendancesByEmployee = $allAttendances->groupBy('empId');
    //     $processedEmployees = collect();

    //     foreach ($attendancesByEmployee as $empId => $employeeDays) {
    //         $employeeInfo = $employeeDays->first();
            
    //         // Parse joining and last dates to handle pre-joining/post-leaving periods.
    //         $joiningDate = $employeeInfo->jobJoingDate ? Carbon::parse($employeeInfo->jobJoingDate)->startOfDay() : null;
    //         $lastWorkingDate = $employeeInfo->lastDate ? Carbon::parse($employeeInfo->lastDate)->startOfDay() : null;

    //         $dailyDataMap = $employeeDays->keyBy(function($day) {
    //             return Carbon::parse($day->forDate)->format('Y-m-d');
    //         });

    //         $processedDailyStatus = [];
    //         $sandwitchDayDed = 0;
    //         $weeklyRuleDeductions = 0.0;

    //         // region Business Rule Processing
    //         // =========================================================================
    //         // STEP 1: PRELIMINARY AND SANDWICH RULE PROCESSING
    //         // =========================================================================
    //         for ($d = 1; $d <= $daysInMonth; $d++) {
    //             $currentDate = $carbonDate->copy()->day($d)->startOfDay();

    //             // Check if the day is outside the employment period.
    //             if (($joiningDate && $currentDate->lt($joiningDate)) || ($lastWorkingDate && $currentDate->gt($lastWorkingDate))) {
    //                 // Mark with a special status 'NE' (Not Employed). This will be handled in the final loop.
    //                 // This prevents these days from affecting any business rule calculations.
    //                 $processedDailyStatus[$d] = (object)['status' => 'NE', 'dayData' => null];
    //                 continue; // Skip all rule processing for this day.
    //             }

    //             $dayData = $dailyDataMap->get($currentDate->format('Y-m-d'));
    //             if (!$dayData) { $processedDailyStatus[$d] = null; continue; }
    //             $finalStatus = $dayData->dayStatus;

    //             if ($d == 1 && in_array($finalStatus, ['WO', 'LH'])) {
    //                 $otherDays = $employeeDays->filter(function($day) use ($currentDate) {
    //                     return $day->forDate != $currentDate->format('Y-m-d');
    //                 });
    //                 if ($otherDays->isNotEmpty()) {
    //                     $presentDaysCount = $otherDays->filter(function($day) {
    //                         return in_array($day->dayStatus, ['P', 'PL', 'PH', 'PLH']);
    //                     })->count();
    //                     if ($presentDaysCount <= 2) {
    //                         $finalStatus = 'A';
    //                         $sandwitchDayDed++;
    //                     }
    //                 }
    //             }

    //             if (in_array($finalStatus, ['WO', 'LH'])) {
    //                 $firstWorkingDayBefore = null; $firstWorkingDayAfter = null;
    //                 for ($i = $d - 1; $i >= 1; $i--) {
    //                     $prevDayStatus = $processedDailyStatus[$i] ?? null;
    //                     if ($prevDayStatus && !in_array($prevDayStatus->status, ['WO', 'LH', 'NE'])) { $firstWorkingDayBefore = $prevDayStatus; break; }
    //                 }
    //                 for ($i = $d + 1; $i <= $daysInMonth; $i++) {
    //                     $nextDay = $dailyDataMap->get($carbonDate->copy()->day($i)->format('Y-m-d'));
    //                     if ($nextDay && !in_array($nextDay->dayStatus, ['WO', 'LH'])) { $firstWorkingDayAfter = (object)['status' => $nextDay->dayStatus, 'dayData' => $nextDay]; break; }
    //                 }
    //                 if ($firstWorkingDayBefore && $firstWorkingDayAfter &&
    //                     (in_array($firstWorkingDayBefore->status, ['A', '0']) && $firstWorkingDayBefore->dayData->AGFStatus == 0) &&
    //                     (in_array($firstWorkingDayAfter->status, ['A', '0']) && $firstWorkingDayAfter->dayData->AGFStatus == 0)) {
    //                     $finalStatus = 'A';
    //                     $sandwitchDayDed++;
    //                 }
    //             }
    //             $processedDailyStatus[$d] = (object)['status' => $finalStatus, 'dayData' => $dayData];
    //         }


    //         // =========================================================================
    //         // >>> NEW LOGIC START: START-OF-MONTH HOLIDAY RULE <<<
    //         // =========================================================================
    //         if ($daysInMonth >= 4) {
    //             $day1Info = $processedDailyStatus[1] ?? null;
    //             $day2Info = $processedDailyStatus[2] ?? null;
    //             $day3Info = $processedDailyStatus[3] ?? null;
    //             $day4Info = $processedDailyStatus[4] ?? null;
    //             $day5Info = $processedDailyStatus[5] ?? null;
    //             $day6Info = $processedDailyStatus[6] ?? null;

    //             $isDay1Holiday = $day1Info && in_array($day1Info->status, ['WO', 'LH', 'H']);

    //             $areNext5DaysAbsent = $day2Info && in_array($day2Info->status, ['A', '0']) && $day2Info->dayData->AGFStatus == 0 &&
    //                                     $day3Info && in_array($day3Info->status, ['A', '0']) && $day3Info->dayData->AGFStatus == 0 &&
    //                                     $day4Info && in_array($day4Info->status, ['A', '0']) && $day4Info->dayData->AGFStatus == 0 &&
    //                                     $day5Info && in_array($day5Info->status, ['A', '0']) && $day5Info->dayData->AGFStatus == 0 &&
    //                                     $day6Info && in_array($day6Info->status, ['A', '0']) && $day6Info->dayData->AGFStatus == 0;

    //             if ($isDay1Holiday && $areNext5DaysAbsent) {
    //                 $processedDailyStatus[1]->status = 'A'; 
    //                 $sandwitchDayDed++;
    //             }
    //         }
    //         // >>> NEW LOGIC END <<<


    //         // =========================================================================
    //         // >>> NEW LOGIC START: Mark weekend as absent if Mon-Fri was absent <<<
    //         // =========================================================================
    //         for ($d = 1; $d <= $daysInMonth; $d++) {
    //             $currentDate = $carbonDate->copy()->day($d);

    //             if ($currentDate->dayOfWeek == Carbon::SATURDAY) {
    //                 $mondayIndex = $d - 5;
    //                 $fridayIndex = $d - 1;

    //                 if ($mondayIndex < 1) {
    //                     continue; 
    //                 }

    //                 $isFullWeekAbsent = true;
    //                 for ($i = $mondayIndex; $i <= $fridayIndex; $i++) {
    //                     $dayInfo = $processedDailyStatus[$i] ?? null;

    //                     if (!$dayInfo || !in_array($dayInfo->status, ['A', '0']) || $dayInfo->dayData->AGFStatus != 0) {
    //                         $isFullWeekAbsent = false;
    //                         break;
    //                     }
    //                 }

    //                 if ($isFullWeekAbsent) {
    //                     $saturdayInfo = $processedDailyStatus[$d] ?? null;
    //                     if ($saturdayInfo && in_array($saturdayInfo->status, ['WO', 'LH', 'H'])) {
    //                         $saturdayInfo->status = 'A';
    //                         $sandwitchDayDed++;
    //                     }

    //                     $sundayIndex = $d + 1;
    //                     if ($sundayIndex <= $daysInMonth) {
    //                         $sundayInfo = $processedDailyStatus[$sundayIndex] ?? null;
    //                         if ($sundayInfo && in_array($sundayInfo->status, ['WO', 'LH', 'H'])) {
    //                             $processedDailyStatus[$sundayIndex]->status = 'A';
    //                             $sandwitchDayDed++;
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //         // >>> NEW LOGIC END <<<


    //         // =========================================================================
    //         // STEP 2: WEEKLY ABSENCE RULE WITH HOLIDAY DEDUCTION
    //         // =========================================================================
    //         $weeklyConfig = [
    //             'ABSENT' => ['A', '0'], 'HALF_DAY' => ['PH', 'PLH'], 'HOLIDAY' => ['H', 'LH'],
    //             'WEEKLY_OFF' => 'WO', 'STANDARD_WORK_DAYS' => 6,
    //             'ABSENT_THRESHOLD_RATIO' => 3.5 / 6, 'HALF_DAY_THRESHOLD_RATIO' => 3.0 / 6,
    //         ];

    //         for ($d = 1; $d <= $daysInMonth; $d++) {
    //             $currentDate = $carbonDate->copy()->day($d);
    //             if ($currentDate->dayOfWeek == Carbon::SUNDAY) {
    //                 $sundayIndex = $d;
    //                 $sundayStatusInfo = $processedDailyStatus[$sundayIndex] ?? null;
    //                 if ($sundayStatusInfo && $sundayStatusInfo->status == $weeklyConfig['WEEKLY_OFF']) {
    //                     $startOfWeek = $currentDate->copy()->subDays(6); $endOfWeek = $currentDate->copy()->subDay();
    //                     $weeklyAbsenceCount = 0.0; $weeklyHolidayCount = 0;

    //                     for ($weekDay = $startOfWeek->copy(); $weekDay->lte($endOfWeek); $weekDay->addDay()) {
    //                         if (!$weekDay->isSameMonth($carbonDate)) continue;
    //                         $statusInfo = $processedDailyStatus[$weekDay->day] ?? null;
    //                         if (!$statusInfo) continue;
    //                         if (in_array($statusInfo->status, $weeklyConfig['ABSENT']) && $statusInfo->dayData->AGFStatus == 0) $weeklyAbsenceCount += 1.0;
    //                         elseif (in_array($statusInfo->status, $weeklyConfig['HALF_DAY']) && $statusInfo->dayData->AGFStatus == 0) $weeklyAbsenceCount += 0.5;
    //                         elseif (in_array($statusInfo->status, $weeklyConfig['HOLIDAY'])) $weeklyHolidayCount++;
    //                     }
                        
    //                     $actualWorkDays = $weeklyConfig['STANDARD_WORK_DAYS'] - $weeklyHolidayCount;
    //                     if ($actualWorkDays > 0) {
    //                         $absentThreshold = $weeklyConfig['ABSENT_THRESHOLD_RATIO'] * $actualWorkDays;
    //                         $halfDayThreshold = $weeklyConfig['HALF_DAY_THRESHOLD_RATIO'] * $actualWorkDays;
                            
    //                         if ($weeklyAbsenceCount >= $absentThreshold) {
    //                             $processedDailyStatus[$sundayIndex]->status = 'A';
    //                             $weeklyRuleDeductions += 1.0;
    //                         } elseif ($weeklyAbsenceCount >= $halfDayThreshold) {
    //                             $processedDailyStatus[$sundayIndex]->status = 'PH';
    //                             $weeklyRuleDeductions += 0.5;
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //         // endregion
            
    //         // =========================================================================
    //         // STEP 3: FINAL STATUS DETERMINATION AND TOTALS CALCULATION
    //         // =========================================================================
    //         $totals = ['present' => 0.0, 'absent' => 0.0, 'weekly_leave' => 0.0, 'extra_work' => 0.0];
    //         $lateMarkCount = 0;
    //         $finalDailyObjects = [];
    //         for ($d = 1; $d <= $daysInMonth; $d++) {
    //             $statusInfo = $processedDailyStatus[$d] ?? null;
    //             if (!$statusInfo) { $finalDailyObjects[$d] = null; continue; }

    //             // Handle 'Not Employed' days for final output.
    //             if ($statusInfo->status == 'NE') {
    //                 $finalDailyObjects[$d] = (object)[
    //                     'status' => '0', // Display as '0' as requested
    //                     'class' => 'attend-0',
    //                     'forDate' => $carbonDate->copy()->day($d)->format('Y-m-d'),
    //                     'officeInTime' => null, 'officeOutTime' => null, 'inTime' => null, 'outTime' => null,
    //                     'workingHr' => null, 'AGFStatus' => null, 'repAuthStatus' => null, 'HRStatus' => null,
    //                     'startTime' => null, 'endTime' => null, 'AGFDayStatus' => null
    //                 ];
    //                 // Do NOT add to any totals, and skip to the next day.
    //                 continue;
    //             }
                
    //             $finalStatus = $statusInfo->status;
    //             $dayData = $statusInfo->dayData;
    //             $isLate = false;

    //             if (in_array($finalStatus, ['P', 'PL', 'PLH', 'PH']) && $dayData->inTime && $dayData->outTime && $dayData->officeInTime && $dayData->officeOutTime) {
    //                 $officeStartTime = Carbon::parse($dayData->forDate . ' ' . $dayData->officeInTime);
    //                 $officeEndTime = Carbon::parse($dayData->forDate . ' ' . $dayData->officeOutTime);
    //                 $actualInTime = Carbon::parse($dayData->inTime);
    //                 $actualOutTime = Carbon::parse($dayData->outTime);
    //                 $requiredMinutes = $officeEndTime->diffInMinutes($officeStartTime);
    //                 $requiredHalfDayMinutes = $requiredMinutes / 2;
    //                 $actualMinutesWorked = $actualOutTime->diffInMinutes($actualInTime);

    //                 if ($actualMinutesWorked < $requiredHalfDayMinutes && $dayData->AGFStatus == 0) {
    //                     $finalStatus = 'A';
    //                 } else {
    //                     $leftEarly = $actualOutTime->isBefore($officeEndTime->copy()->subMinutes(15));
                        
    //                     $shiftMidpoint = $officeStartTime->copy()->addMinutes($requiredHalfDayMinutes);
    //                     $workedInFirstHalf = $actualInTime->lt($shiftMidpoint);
    //                     $workedInSecondHalf = $actualOutTime->gt($shiftMidpoint);
    //                     $isHalfDayDueToShiftSpan = !($workedInFirstHalf && $workedInSecondHalf);
                        
    //                     if ($isHalfDayDueToShiftSpan && $leftEarly && $dayData->AGFStatus == 0) {
    //                         $finalStatus = 'A';
    //                     } elseif (($isHalfDayDueToShiftSpan || $leftEarly) && $dayData->AGFStatus == 0) {
    //                         $finalStatus = 'PH';
    //                     } else {
    //                         if ($actualInTime->isAfter($officeStartTime->copy()->addMinutes(7)) && $dayData->AGFStatus == 0) {
    //                            $isLate = true;
    //                            if ($finalStatus == 'P') $finalStatus = 'PL';
    //                         }
    //                         else
    //                         {
    //                             if($dayData->AGFDayStatus == 'Full Day')
    //                                 $finalStatus = 'P';
    //                             else
    //                                 $finalStatus = 'PH';
    //                         }
    //                     }
    //                 }
    //             }
    //             else
    //             {
    //                 if($finalStatus == 'A' && $dayData->AGFStatus != 0)
    //                 {
    //                     if($dayData->AGFDayStatus == 'Full Day')
    //                         $finalStatus = 'P';
    //                     else
    //                         $finalStatus = 'PH';
    //                 }
    //                 else
    //                 {
    //                     if($dayData->AGFDayStatus == 'Full Day' && $dayData->AGFStatus != 0 && $dayData->holiday == 0)
    //                         $finalStatus = 'P';
    //                     else if($dayData->AGFDayStatus == 'Half Day' && $dayData->AGFStatus != 0 && $dayData->holiday == 0)
    //                         $finalStatus = 'PH';
    //                     else if($dayData->holiday == 0)
    //                         $finalStatus = 'A';
                        
    //                 }
    //             }

    //             if ($isLate) $lateMarkCount++;
    //             if (in_array($finalStatus, ['WO', 'LH']) && $dayData->AGFStatus != 0) {
    //                 $totals['extra_work'] += ($dayData->AGFDayStatus == 'Full Day') ? 1.0 : 0.5;
    //             }

    //             if(($dayData->dayStatus == 'PH' || $dayData->dayStatus == 'PLH')  && $dayData->AGFStatus != 0)
    //             {
    //                 if($dayData->AGFDayStatus == 'Full Day')
    //                     $finalStatus = 'P';
    //                 else
    //                     $finalStatus = 'PH';
    //             }

    //             switch ($finalStatus) {
    //                 case 'P': case 'PL': $totals['present'] += 1.0; break;
    //                 case 'A': case '0': $totals['absent'] += 1.0; break;
    //                 case 'PH':case 'PLH': $totals['present'] += 0.5; $totals['absent'] += 0.5; break;
    //                 case 'WO': case 'LH': $totals['weekly_leave'] += 1.0; break;
    //             }
    //             $finalDailyObjects[$d] = (object)[
    //                 'officeInTime' => $dayData->officeInTime,'officeOutTime' => $dayData->officeOutTime,'status' => $finalStatus, 
    //                 'class' => 'attend-'.$finalStatus, 'forDate' => $dayData->forDate, 'inTime' => $dayData->inTime,
    //                 'outTime' => $dayData->outTime, 'workingHr' => $dayData->workingHr,'deviceInTime' => $dayData->deviceInTime,'deviceOutTime' => $dayData->deviceOutTime,
    //                  'AGFStatus' => $dayData->AGFStatus,'repAuthStatus' => $dayData->repAuthStatus, 'HRStatus' => $dayData->HRStatus, 
    //                  'startTime'=>$dayData->startTime,'endTime'=>$dayData->endTime, 'AGFDayStatus' => $dayData->AGFDayStatus
    //             ];
    //         }
    //         $lateMarkDeduction = floor($lateMarkCount / 3);
    //         if ($lateMarkDeduction > 0) {
    //             $totals['present'] -= $lateMarkDeduction;
    //             $totals['absent'] += $lateMarkDeduction;
    //         }
            
    //         $totals['late_mark_deductions'] = $lateMarkDeduction;
    //         $totals['sandwitch_deductions'] = $sandwitchDayDed;
    //         $totals['weekly_rule_deductions'] = $weeklyRuleDeductions;
    //         $totals['total_deductions'] = $lateMarkDeduction + $sandwitchDayDed + $weeklyRuleDeductions;
    //         $totals['present'] = $totals['present'] + $totals['weekly_leave'];
    //         $totals['final_total'] = $totals['present'] + $totals['extra_work'];
    //         $totals['absent'] = $totals['absent'] - $totals['total_deductions'];
            
    //         $changeData = $dayChanges->get($empId);
    //         $totals['is_edited'] = false;
    //         if ($changeData) {
    //             $totals['is_edited'] = true;
    //             $totals['remark'] = $changeData->remark;
    //             $totals['new_present'] = $changeData->newPresentDays;
    //             $totals['new_absent'] = $changeData->newAbsentDays;
    //             $totals['new_wl'] = $changeData->newWLDays;
    //             $totals['new_extra_work'] = $changeData->newExtraDays;
    //             $totals['new_final_total'] = $changeData->newDays;
    //         }
        
    //         $employeeInfo->finalSalaryStatus = $employeeDays->last()->salaryHoldRelease ?? 0;
    //         $processedEmployees->push(['info' => $employeeInfo, 'days' => $finalDailyObjects, 'totals' => $totals]);

    //     }
        
    //     if ($empCode) {
    //         $processedEmployees = $processedEmployees->filter(function ($employee) use ($empCode) {
    //             return str_contains(strtolower($employee['info']->name), strtolower($empCode)) ||
    //                    str_contains(strtolower($employee['info']->empCode), strtolower($empCode));
    //         });
    //     }
        
    //     // --- 5. SUMMARIZE AND PAGINATE ---
    //    $summaryStats = [
    //         'total_employees' => $processedEmployees->count(),
    //         'total_present' => $processedEmployees->sum(function($emp) { return ($emp['totals']['present']); }), // weekly_leave already added to present
    //         'total_absent' => $processedEmployees->sum(function($emp) { return $emp['totals']['absent']; }),
    //         'total_deductions' => $processedEmployees->sum(function($emp) { return $emp['totals']['total_deductions']; }),
    //         'total_extra_work' => $processedEmployees->sum(function($emp) { return $emp['totals']['extra_work']; }),
    //     ];
        
    //     $perPage = 25;
    //     $paginatedResult = new LengthAwarePaginator(
    //         $processedEmployees->forPage(LengthAwarePaginator::resolveCurrentPage('page'), $perPage)->values(), 
    //         $processedEmployees->count(), $perPage > 0 ? $perPage : 1, 
    //         LengthAwarePaginator::resolveCurrentPage('page'), 
    //         ['path' => $request->url(), 'query' => $request->query()]
    //     );

    //     $attendanceConfStatus = AttendanceJob::where('userType', $userType)->where('section', $request->section)->where('fBranchId', $request->branchId)->where('fMonth', $finalMonth)->count();
        
    //     return view('admin.attendance.list')->with([
    //         'attendances' => $paginatedResult, 'daysInMonth' => $daysInMonth, 'carbonDate' => $carbonDate,
    //         'attendanceConfStatus' => $attendanceConfStatus, 'branches' => $branches, 'userType' => $userType,
    //         'section' => $request->section, 'branchId' => $request->branchId,
    //         'finalMonth' => $finalMonth, 'summaryStats' => $summaryStats, 'empCode' => $empCode
    //     ]);
  
    // }

    // public function finalAttendanceSheet(Request $request) // latest
    // {
    //     // --- 1. SETUP AND VALIDATION ---
    //     $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
    //     $finalMonth = $request->input('month');
    //     $userType = Auth::user()->userType;
    //     $searchQuery = $request->input('search_query');

    //     if (!$request->filled('branchId') || !$request->filled('month')) {
    //         return view('admin.attendance.finalAttendanceSheet')->with([
    //             'attendances' => null, 'branches' => $branches, 'userType' => $userType,
    //             'section' => $request->section,
    //             'branchId' => $request->branchId, 'finalMonth' => $finalMonth,
    //             'searchQuery' => $searchQuery,
    //         ]);
    //     }

    //     try {
    //         // Create a Carbon instance from the 'YYYY-MM' format and set it to the 1st day.
    //         $carbonDate = Carbon::createFromFormat('Y-m', $finalMonth)->startOfMonth();
    //     } catch (\Exception $e) {
    //         // If the month format is invalid, redirect back with an error.
    //         return redirect()->back()->withInput()->with("error", "Invalid month format provided.");
    //     }

    //     // These variables can be defined once, as their logic is consistent.
    //     $startDate = $carbonDate->copy()->format('Y-m-d'); // This is always 'YYYY-MM-01'
    //     $daysInMonth = $carbonDate->daysInMonth; // Total days in the selected month

    //     // Conditionally set the end date based on the new requirement.
    //     if ($finalMonth == date('Y-m')) {
    //         // If it's the current month, the end date is today.
    //         $endDate = Carbon::now()->format('Y-m-d');
    //     } else {
    //         // For any other month, the end date is the last day of that month.
    //         $endDate = $carbonDate->copy()->endOfMonth()->format('Y-m-d');
    //     }


    //     if ($userType == '501') {
    //         $attendanceConfStatus = AttendanceJob::where('userType', '51')
    //             ->where('fBranchId', $request->branchId)
    //             ->where('fMonth', $finalMonth)
    //             ->where('section', $request->section)
    //             ->count();
    //         if (!$attendanceConfStatus)
    //             return redirect()->back()->withInput()->with("error", "HR Department Still not confirmed Selected Branch...");
    //     }

    //     if ($userType == '61') {
    //         $attendanceConfStatus = AttendanceJob::where('userType', '501')
    //             ->where('fBranchId', $request->branchId)
    //             ->where('fMonth', $finalMonth)
    //             ->where('section', $request->section)
    //             ->count();
    //         if (!$attendanceConfStatus)
    //             return redirect()->back()->withInput()->with("error", "Higher Authority Still not confirmed Selected Branch...");
    //     }

    //     // --- 3. EFFICIENT DATABASE QUERY ---
    //     $allAttendances = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
    //         ->join('designations', 'emp_dets.designationId', 'designations.id')
    //         ->join('departments', 'emp_dets.departmentId', 'departments.id')
    //         ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
    //         ->select(
    //             'attendance_details.*',
    //             'emp_dets.name',
    //             'emp_dets.empCode',
    //             'emp_dets.jobJoingDate',
    //             'emp_dets.lastDate',
    //             'emp_dets.startTime',
    //             'emp_dets.endTime',
    //             'emp_dets.id as attendEmpId',
    //             'designations.name as designationName',
    //             'contactus_land_pages.branchName'
    //         )
    //         ->whereBetween('attendance_details.forDate', [$startDate, $endDate])
    //         ->where('emp_dets.branchId', $request->branchId)
    //         ->when($request->section, function ($q, $section) {
    //             return $q->where('departments.section', $section);
    //         })
    //         ->orderBy('emp_dets.empCode')->orderBy('attendance_details.forDate')
    //         ->get();

    //     if ($allAttendances->isEmpty()) {
    //         return redirect()->back()->withInput()->with("error", "Record Not Found.");
    //     }

    //     $employeeIds = $allAttendances->pluck('attendEmpId')->unique();
    //     $dayChanges = EmpChangeDay::where('month', $finalMonth)
    //         ->whereIn('empId', $employeeIds)
    //         ->get()
    //         ->keyBy('empId');

    //     // --- 4. PROCESS DATA WITH ALL BUSINESS RULES ---
    //     $attendancesByEmployee = $allAttendances->groupBy('empId');
    //     $processedEmployees = collect();

    //     foreach ($attendancesByEmployee as $empId => $employeeDays) {
    //         $employeeInfo = $employeeDays->first();

    //         // Parse joining and last dates to handle pre-joining/post-leaving periods.
    //         $joiningDate = $employeeInfo->jobJoingDate ? Carbon::parse($employeeInfo->jobJoingDate)->startOfDay() : null;
    //         $lastWorkingDate = $employeeInfo->lastDate ? Carbon::parse($employeeInfo->lastDate)->startOfDay() : null;

    //         $dailyDataMap = $employeeDays->keyBy(function ($day) {
    //             return Carbon::parse($day->forDate)->format('Y-m-d');
    //         });

    //         $processedDailyStatus = [];
    //         $sandwitchDayDed = 0;
    //         $weeklyRuleDeductions = 0.0;
    //         $lateMarkCount = 0;

    //         // region Business Rule Processing
    //         // =========================================================================
    //         // STEP 1: PRELIMINARY AND SANDWICH RULE PROCESSING
    //         // =========================================================================
    //         for ($d = 1; $d <= $daysInMonth; $d++) {
    //             $currentDate = $carbonDate->copy()->day($d)->startOfDay();

    //             // Check if the day is outside the employment period.
    //             if (($joiningDate && $currentDate->lt($joiningDate)) || ($lastWorkingDate && $currentDate->gt($lastWorkingDate))) {
    //                 // Mark with a special status 'NE' (Not Employed). This will be handled in the final loop.
    //                 // This prevents these days from affecting any business rule calculations.
    //                 $processedDailyStatus[$d] = (object)['status' => 'NE', 'dayData' => null];
    //                 continue; // Skip all rule processing for this day.
    //             }

    //             $dayData = $dailyDataMap->get($currentDate->format('Y-m-d'));
    //             if (!$dayData) {
    //                 $processedDailyStatus[$d] = null;
    //                 continue;
    //             }
    //             $finalStatus = $dayData->dayStatus;

    //             if ($d == 1 && in_array($finalStatus, ['WO', 'LH'])) {
    //                 $otherDays = $employeeDays->filter(function ($day) use ($currentDate) {
    //                     return $day->forDate != $currentDate->format('Y-m-d');
    //                 });
    //                 if ($otherDays->isNotEmpty()) {
    //                     $presentDaysCount = $otherDays->filter(function ($day) {
    //                         return in_array($day->dayStatus, ['P', 'PL', 'PH', 'PLH']);
    //                     })->count();
    //                     if ($presentDaysCount <= 2) {
    //                         $finalStatus = 'A';
    //                         $sandwitchDayDed++;
    //                     }
    //                 }
    //             }

    //             if (in_array($finalStatus, ['WO', 'LH'])) {
    //                 $firstWorkingDayBefore = null;
    //                 $firstWorkingDayAfter = null;
    //                 for ($i = $d - 1; $i >= 1; $i--) {
    //                     $prevDayStatus = $processedDailyStatus[$i] ?? null;
    //                     if ($prevDayStatus && !in_array($prevDayStatus->status, ['WO', 'LH', 'NE'])) {
    //                         $firstWorkingDayBefore = $prevDayStatus;
    //                         break;
    //                     }
    //                 }
    //                 for ($i = $d + 1; $i <= $daysInMonth; $i++) {
    //                     $nextDay = $dailyDataMap->get($carbonDate->copy()->day($i)->format('Y-m-d'));
    //                     if ($nextDay && !in_array($nextDay->dayStatus, ['WO', 'LH'])) {
    //                         $firstWorkingDayAfter = (object)['status' => $nextDay->dayStatus, 'dayData' => $nextDay];
    //                         break;
    //                     }
    //                 }
    //                 if (
    //                     $firstWorkingDayBefore && $firstWorkingDayAfter &&
    //                     (in_array($firstWorkingDayBefore->status, ['A', '0']) && $firstWorkingDayBefore->dayData->AGFStatus == 0) &&
    //                     (in_array($firstWorkingDayAfter->status, ['A', '0']) && $firstWorkingDayAfter->dayData->AGFStatus == 0)
    //                 ) {
    //                     $finalStatus = 'A';
    //                     $sandwitchDayDed++;
    //                 }
    //             }
    //             $processedDailyStatus[$d] = (object)['status' => $finalStatus, 'dayData' => $dayData];
    //         }


    //         // =========================================================================
    //         // >>> NEW LOGIC START: START-OF-MONTH HOLIDAY RULE <<<
    //         // =========================================================================
    //         if ($daysInMonth >= 4) {
    //             $day1Info = $processedDailyStatus[1] ?? null;
    //             $day2Info = $processedDailyStatus[2] ?? null;
    //             $day3Info = $processedDailyStatus[3] ?? null;
    //             $day4Info = $processedDailyStatus[4] ?? null;
    //             $day5Info = $processedDailyStatus[5] ?? null;
    //             $day6Info = $processedDailyStatus[6] ?? null;

    //             $isDay1Holiday = $day1Info && in_array($day1Info->status, ['WO', 'LH', 'H']);

    //             $areNext5DaysAbsent = $day2Info && in_array($day2Info->status, ['A', '0']) && $day2Info->dayData->AGFStatus == 0 &&
    //                 $day3Info && in_array($day3Info->status, ['A', '0']) && $day3Info->dayData->AGFStatus == 0 &&
    //                 $day4Info && in_array($day4Info->status, ['A', '0']) && $day4Info->dayData->AGFStatus == 0 &&
    //                 $day5Info && in_array($day5Info->status, ['A', '0']) && $day5Info->dayData->AGFStatus == 0 &&
    //                 $day6Info && in_array($day6Info->status, ['A', '0']) && $day6Info->dayData->AGFStatus == 0;

    //             if ($isDay1Holiday && $areNext5DaysAbsent) {
    //                 $processedDailyStatus[1]->status = 'A';
    //                 $sandwitchDayDed++;
    //             }
    //         }
    //         // >>> NEW LOGIC END <<<


    //         // =========================================================================
    //         // >>> NEW LOGIC START: Mark weekend as absent if Mon-Fri was absent <<<
    //         // =========================================================================
    //         for ($d = 1; $d <= $daysInMonth; $d++) {
    //             $currentDate = $carbonDate->copy()->day($d);

    //             if ($currentDate->dayOfWeek == Carbon::SATURDAY) {
    //                 $mondayIndex = $d - 5;
    //                 $fridayIndex = $d - 1;

    //                 if ($mondayIndex < 1) {
    //                     continue;
    //                 }

    //                 $isFullWeekAbsent = true;
    //                 for ($i = $mondayIndex; $i <= $fridayIndex; $i++) {
    //                     $dayInfo = $processedDailyStatus[$i] ?? null;

    //                     if (!$dayInfo || !in_array($dayInfo->status, ['A', '0']) || $dayInfo->dayData->AGFStatus != 0) {
    //                         $isFullWeekAbsent = false;
    //                         break;
    //                     }
    //                 }

    //                 if ($isFullWeekAbsent) {
    //                     $saturdayInfo = $processedDailyStatus[$d] ?? null;
    //                     if ($saturdayInfo && in_array($saturdayInfo->status, ['WO', 'LH', 'H'])) {
    //                         $saturdayInfo->status = 'A';
    //                         $sandwitchDayDed++;
    //                     }

    //                     $sundayIndex = $d + 1;
    //                     if ($sundayIndex <= $daysInMonth) {
    //                         $sundayInfo = $processedDailyStatus[$sundayIndex] ?? null;
    //                         if ($sundayInfo && in_array($sundayInfo->status, ['WO', 'LH', 'H'])) {
    //                             $processedDailyStatus[$sundayIndex]->status = 'A';
    //                             $sandwitchDayDed++;
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //         // >>> NEW LOGIC END <<<


    //         // =========================================================================
    //         // STEP 2: WEEKLY ABSENCE RULE WITH HOLIDAY DEDUCTION
    //         // =========================================================================
    //         $weeklyConfig = [
    //             'ABSENT' => ['A', '0'], 'HALF_DAY' => ['PH', 'PLH'], 'HOLIDAY' => ['H', 'LH'],
    //             'WEEKLY_OFF' => 'WO', 'STANDARD_WORK_DAYS' => 6,
    //             'ABSENT_THRESHOLD_RATIO' => 3.5 / 6, 'HALF_DAY_THRESHOLD_RATIO' => 3.0 / 6,
    //         ];

    //         for ($d = 1; $d <= $daysInMonth; $d++) {
    //             $currentDate = $carbonDate->copy()->day($d);
    //             if ($currentDate->dayOfWeek == Carbon::SUNDAY) {
    //                 $sundayIndex = $d;
    //                 $sundayStatusInfo = $processedDailyStatus[$sundayIndex] ?? null;
    //                 if ($sundayStatusInfo && $sundayStatusInfo->status == $weeklyConfig['WEEKLY_OFF']) {
    //                     $startOfWeek = $currentDate->copy()->subDays(6);
    //                     $endOfWeek = $currentDate->copy()->subDay();
    //                     $weeklyAbsenceCount = 0.0;
    //                     $weeklyHolidayCount = 0;

    //                     for ($weekDay = $startOfWeek->copy(); $weekDay->lte($endOfWeek); $weekDay->addDay()) {
    //                         if (!$weekDay->isSameMonth($carbonDate)) continue;
    //                         $statusInfo = $processedDailyStatus[$weekDay->day] ?? null;
    //                         if (!$statusInfo) continue;
    //                         if (in_array($statusInfo->status, $weeklyConfig['ABSENT']) && $statusInfo->dayData->AGFStatus == 0) $weeklyAbsenceCount += 1.0;
    //                         elseif (in_array($statusInfo->status, $weeklyConfig['HALF_DAY']) && $statusInfo->dayData->AGFStatus == 0) $weeklyAbsenceCount += 0.5;
    //                         elseif (in_array($statusInfo->status, $weeklyConfig['HOLIDAY'])) $weeklyHolidayCount++;
    //                     }

    //                     $actualWorkDays = $weeklyConfig['STANDARD_WORK_DAYS'] - $weeklyHolidayCount;
    //                     if ($actualWorkDays > 0) {
    //                         $absentThreshold = $weeklyConfig['ABSENT_THRESHOLD_RATIO'] * $actualWorkDays;
    //                         $halfDayThreshold = $weeklyConfig['HALF_DAY_THRESHOLD_RATIO'] * $actualWorkDays;

    //                         if ($weeklyAbsenceCount >= $absentThreshold) {
    //                             $processedDailyStatus[$sundayIndex]->status = 'A';
    //                             $weeklyRuleDeductions += 1.0;
    //                         } elseif ($weeklyAbsenceCount >= $halfDayThreshold) {
    //                             $processedDailyStatus[$sundayIndex]->status = 'PH';
    //                             $weeklyRuleDeductions += 0.5;
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //         // endregion

    //         // =========================================================================
    //         // STEP 3: FINAL STATUS DETERMINATION AND TOTALS CALCULATION
    //         // =========================================================================
    //         $totals = ['present' => 0.0, 'absent' => 0.0, 'weekly_leave' => 0.0, 'extra_work' => 0.0];
    //         $finalDailyObjects = [];
    //         for ($d = 1; $d <= $daysInMonth; $d++) {
    //             $statusInfo = $processedDailyStatus[$d] ?? null;
    //             if (!$statusInfo) {
    //                 $finalDailyObjects[$d] = null;
    //                 continue;
    //             }

    //             // Handle 'Not Employed' days for final output.
    //             if ($statusInfo->status == 'NE') {
    //                 $finalDailyObjects[$d] = (object)[
    //                     'status' => '0', // Display as '0' as requested
    //                     'class' => 'attend-A',
    //                     'forDate' => $carbonDate->copy()->day($d)->format('Y-m-d'),
    //                     'officeInTime' => null, 'officeOutTime' => null, 'inTime' => null, 'outTime' => null,
    //                     'workingHr' => null, 'AGFStatus' => null, 'repAuthStatus' => null, 'HRStatus' => null,
    //                     'startTime' => null, 'endTime' => null, 'AGFDayStatus' => null
    //                 ];
    //                 // Do NOT add to any totals, and skip to the next day.
    //                 continue;
    //             }

    //             $finalStatus = $statusInfo->status;
    //             $dayData = $statusInfo->dayData;

    //             if (in_array($finalStatus, ['P', 'PL', 'PLH', 'PH']) && $dayData->inTime && $dayData->outTime && $dayData->officeInTime && $dayData->officeOutTime) {
    //                 $officeStartTime = Carbon::parse($dayData->forDate . ' ' . $dayData->officeInTime);
    //                 $officeEndTime = Carbon::parse($dayData->forDate . ' ' . $dayData->officeOutTime);
    //                 $actualInTime = Carbon::parse($dayData->inTime);
    //                 $actualOutTime = Carbon::parse($dayData->outTime);
    //                 $requiredMinutes = $officeEndTime->diffInMinutes($officeStartTime);
    //                 $requiredHalfDayMinutes = $requiredMinutes / 2;
    //                 $actualMinutesWorked = $actualOutTime->diffInMinutes($actualInTime);

    //                 if ($actualMinutesWorked < $requiredHalfDayMinutes && $dayData->AGFStatus == 0) {
    //                     $finalStatus = 'A';
    //                 } else {
    //                     $leftEarly = $actualOutTime->isBefore($officeEndTime->copy()->subMinutes(15));

    //                     $shiftMidpoint = $officeStartTime->copy()->addMinutes($requiredHalfDayMinutes);
    //                     $workedInFirstHalf = $actualInTime->lt($shiftMidpoint);
    //                     $workedInSecondHalf = $actualOutTime->gt($shiftMidpoint);
    //                     $isHalfDayDueToShiftSpan = !($workedInFirstHalf && $workedInSecondHalf);

    //                     if ($isHalfDayDueToShiftSpan && $leftEarly && $dayData->AGFStatus == 0) {
    //                         $finalStatus = 'A';
    //                     } elseif (($isHalfDayDueToShiftSpan || $leftEarly) && $dayData->AGFStatus == 0) {
    //                         $finalStatus = 'PH';
    //                     } else {
    //                         if ($actualInTime->isAfter($officeStartTime->copy()->addMinutes(7)) && $dayData->AGFStatus == 0) {
    //                             if ($finalStatus == 'P') $finalStatus = 'PL';
    //                         } else {
    //                             if ($dayData->AGFDayStatus == 'Full Day')
    //                                 $finalStatus = 'P';
    //                             else
    //                                 $finalStatus = 'PH';
    //                         }
    //                     }
    //                 }
    //             } else {
    //                 if ($finalStatus == 'A' && $dayData->AGFStatus != 0) {
    //                     if ($dayData->AGFDayStatus == 'Full Day')
    //                         $finalStatus = 'P';
    //                     else
    //                         $finalStatus = 'PH';
    //                 } else {
    //                     if ($dayData->AGFDayStatus == 'Full Day' && $dayData->AGFStatus != 0 && $dayData->holiday == 0)
    //                         $finalStatus = 'P';
    //                     else if ($dayData->AGFDayStatus == 'Half Day' && $dayData->AGFStatus != 0 && $dayData->holiday == 0)
    //                         $finalStatus = 'PH';
    //                     else if ($dayData->holiday == 0)
    //                         $finalStatus = 'A';
    //                 }
    //             }

    //             if (($dayData->dayStatus == 'PH' || $dayData->dayStatus == 'PLH') && $dayData->AGFStatus != 0) {
    //                 if ($dayData->AGFDayStatus == 'Full Day')
    //                     $finalStatus = 'P';
    //                 else
    //                     $finalStatus = 'PH';
    //             }
                
    //             // Moved late mark counting to the switch statement to ensure accuracy.
    //             switch ($finalStatus) {
    //                 case 'P':
    //                     $totals['present'] += 1.0;
    //                     break;
    //                 case 'PL':
    //                     $totals['present'] += 1.0;
    //                     $lateMarkCount++;
    //                     break;
    //                 case 'A':
    //                 case '0':
    //                     $totals['absent'] += 1.0;
    //                     break;
    //                 case 'PH':
    //                 case 'PLH':
    //                     $totals['present'] += 0.5;
    //                     $totals['absent'] += 0.5;
    //                     if ($finalStatus == 'PLH') {
    //                         $lateMarkCount++;
    //                     }
    //                     break;
    //                 case 'WO':
    //                 case 'LH':
    //                     $totals['weekly_leave'] += 1.0;
    //                     if ($dayData->AGFStatus != 0) {
    //                         $application = EmpApplication::where('id', $dayData->AGFStatus)->where('reason', 'Extra Working on Holiday')->count();
    //                         if($application)
    //                             $totals['extra_work'] += ($dayData->AGFDayStatus == 'Full Day') ? 1.0 : 0.5;
    //                     }
    //                     break;
    //             }
    //             $finalDailyObjects[$d] = (object)[
    //                 'officeInTime' => $dayData->officeInTime, 'officeOutTime' => $dayData->officeOutTime, 'status' => $finalStatus,
    //                 'class' => 'attend-' . $finalStatus, 'forDate' => $dayData->forDate, 'inTime' => $dayData->inTime,
    //                 'outTime' => $dayData->outTime, 'workingHr' => $dayData->workingHr, 'deviceInTime' => $dayData->deviceInTime, 'deviceOutTime' => $dayData->deviceOutTime,
    //                 'AGFStatus' => $dayData->AGFStatus, 'repAuthStatus' => $dayData->repAuthStatus, 'HRStatus' => $dayData->HRStatus,
    //                 'startTime' => $dayData->startTime, 'endTime' => $dayData->endTime, 'AGFDayStatus' => $dayData->AGFDayStatus
    //             ];
    //         }

    //         $lateMarkDeduction = floor($lateMarkCount / 3);
    //         if ($lateMarkDeduction > 0) {
    //             $totals['present'] -= $lateMarkDeduction;
    //             // $totals['absent'] += $lateMarkDeduction;
    //         }

    //         $totals['late_mark_deductions'] = $lateMarkDeduction;
    //         $totals['sandwitch_deductions'] = $sandwitchDayDed;
    //         $totals['weekly_rule_deductions'] = $weeklyRuleDeductions;
    //         $totals['total_deductions'] = $lateMarkDeduction + $sandwitchDayDed + $weeklyRuleDeductions;
    //         $totals['present'] = $totals['present'] + $totals['weekly_leave'];
    //         $totals['final_total'] = $totals['present'] + $totals['extra_work'];
    //         $totals['absent'] = $totals['absent'] - ($sandwitchDayDed + $weeklyRuleDeductions);

    //         $changeData = $dayChanges->get($empId);
    //         $totals['is_edited'] = false;
    //         if ($changeData) {
    //             $totals['is_edited'] = true;
    //             $totals['remark'] = $changeData->remark;
    //             $totals['new_present'] = $changeData->newPresentDays;
    //             $totals['new_absent'] = $changeData->newAbsentDays;
    //             $totals['new_wl'] = $changeData->newWLDays;
    //             $totals['new_extra_work'] = $changeData->newExtraDays;
    //             $totals['new_final_total'] = $changeData->newDays;
    //         }
    //         $employeeInfo->finalSalaryStatus = $employeeDays->last()->salaryHoldRelease ?? 0;
    //         $processedEmployees->push(['info' => $employeeInfo, 'days' => $finalDailyObjects, 'totals' => $totals]);
    //     }
    //     if ($searchQuery) {
    //         $processedEmployees = $processedEmployees->filter(function ($employee) use ($searchQuery) {
    //             return str_contains(strtolower($employee['info']->name), strtolower($searchQuery)) ||
    //                 str_contains(strtolower($employee['info']->empCode), strtolower($searchQuery));
    //         });
    //     }

    //     // return $processedEmployees;
    //     // --- 5. SUMMARIZE AND PAGINATE ---
    //     $summaryStats = [
    //         'total_employees' => $processedEmployees->count(),
    //         'total_present' => $processedEmployees->sum(function ($emp) {
    //             return ($emp['totals']['present']);
    //         }), // weekly_leave already added to present
    //         'total_absent' => $processedEmployees->sum(function ($emp) {
    //             return $emp['totals']['absent'];
    //         }),
    //         'total_deductions' => $processedEmployees->sum(function ($emp) {
    //             return $emp['totals']['total_deductions'];
    //         }),
    //         'total_extra_work' => $processedEmployees->sum(function ($emp) {
    //             return $emp['totals']['extra_work'];
    //         }),
    //     ];

    //     $perPage = 50;
    //     $paginatedResult = new LengthAwarePaginator(
    //         $processedEmployees->forPage(LengthAwarePaginator::resolveCurrentPage('page'), $perPage)->values(),
    //         $processedEmployees->count(),
    //         $perPage > 0 ? $perPage : 1,
    //         LengthAwarePaginator::resolveCurrentPage('page'),
    //         ['path' => $request->url(), 'query' => $request->query()]
    //     );

    //     $attendanceConfStatus = AttendanceJob::where('userType', $userType)->where('section', $request->section)->where('fBranchId', $request->branchId)->where('fMonth', $finalMonth)->count();

    //     return view('admin.attendance.finalAttendanceSheet')->with([
    //         'attendances' => $paginatedResult, 'daysInMonth' => $daysInMonth, 'carbonDate' => $carbonDate,
    //         'attendanceConfStatus' => $attendanceConfStatus, 'branches' => $branches, 'userType' => $userType,
    //         'section' => $request->section, 'branchId' => $request->branchId, 'finalMonth' => $finalMonth, 
    //         'summaryStats' => $summaryStats, 'searchQuery' => $searchQuery
    //     ]);
    // }
    

    // public function confirmation(Request $request)  // latest
    // {
    //     // 1. Common Validation & Date Setup (Using new field names)
    //     $request->validate([
    //         'fMonth' => 'required',
    //         'fBranchId' => 'required',
    //         'fSection' => 'required',
    //     ]);

    //     try {
    //         $carbonDate = Carbon::createFromFormat('Y-m', $request->fMonth);
    //         $startDate = $carbonDate->copy()->startOfMonth()->format('Y-m-d');
    //         $endDate = $carbonDate->copy()->endOfMonth()->format('Y-m-d');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Invalid month format.');
    //     }

    //     // 2. Common Query Building (Using new field names)
    //     $employeeIdsQuery = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
    //         ->join('departments', 'emp_dets.departmentId', 'departments.id')
    //         ->where('emp_dets.branchId', $request->fBranchId)
    //         ->whereBetween('attendance_details.forDate', [$startDate, $endDate])
    //         ->when($request->filled('fSection'), function ($q) use ($request) {
    //             return $q->where('departments.section', $request->fSection);
    //         })
    //         ->when($request->filled('fOrganisation'), function ($q) use ($request) {
    //             return $q->where('emp_dets.organisation', $request->fOrganisation);
    //         })
    //         ->when($request->filled('fSearch_query'), function ($q) use ($request) {
    //             $searchQuery = $request->fSearch_query;
    //             return $q->where(function ($subQuery) use ($searchQuery) {
    //                 $subQuery->where('emp_dets.name', 'like', "%{$searchQuery}%")
    //                          ->orWhere('emp_dets.empCode', 'like', "%{$searchQuery}%");
    //             });
    //         });

    //     $employeeIds = $employeeIdsQuery->distinct()->pluck('emp_dets.id');

    //     if ($employeeIds->isEmpty()) {
    //         return redirect()->back()->with('error', 'No employees found to confirm.');
    //     }

    //     // 3. Determine Update Data based on User Role
    //     $userType = Auth::user()->userType;
    //     $updateData = [];

    //     switch ($userType) {
    //         case '51': // HR Role
    //             $updateData = [
    //                 'HRConfirmStatus' => 1,
    //                 'HRUpdatedAt' => now(),
    //                 'HRUpdatedBy' => Auth::user()->username
    //             ];
    //             break;
            
    //         case '501': // Higher Authority Role
    //             $updateData = [
    //                 'highAuthConfirmStatus' => 1,
    //                 'highAuthUpdatedAt' => now(),
    //                 'highAuthUpdatedBy' => Auth::user()->username
    //             ];
    //             break;
            
    //         case '61': // Accounts Role
    //             $updateData = [
    //                 'accountConfirmStatus' => 1,
    //                 'accountUpdatedAt' => now(),
    //                 'accountUpdatedBy' => Auth::user()->username
    //             ];
    //             break;

    //         default:
    //             return redirect()->back()->with('error', 'You are not authorized to perform this action.');
    //     }

    //     // 4. Perform the Mass Update
    //     AttendanceDetail::whereIn('empId', $employeeIds)
    //         ->whereBetween('forDate', [$startDate, $endDate])
    //         ->update($updateData);
        
    //     // 5. Create the Confirmation Log (Using new field names)
    //     $attendanceConf = new AttendanceJob;
    //     $attendanceConf->fBranchId = $request->fBranchId;
    //     $attendanceConf->fMonth = $request->fMonth;
    //     $attendanceConf->section = $request->fSection;
    //     $attendanceConf->userType = $userType;
    //     $attendanceConf->updated_by = Auth::user()->username;
        
    //     if ($attendanceConf->save()) {
    //         return redirect()->back()->with('success', "Attendance confirmed successfully for {$employeeIds->count()} employees.");
    //     } else {
    //         return redirect()->back()->with('error', 'Attendance was updated, but failed to create confirmation log. Please contact support.');
    //     }
    // }

    // public function updateFinalDays(Request $request)
    // {
    //     return $request->all();
    //     $validator = Validator::make($request->all(), [
    //         'empId' => 'required|integer|exists:emp_dets,id',
    //         'month' => 'required|date_format:Y-m',
    //         'newPresentDays' => 'required|numeric',
    //         'newAbsentDays' => 'required|numeric',
    //         'newWLDays' => 'required|numeric',
    //         'newExtraDays' => 'required|numeric',
    //         'newDays' => 'required|numeric',
    //         'remark' => 'nullable|string|max:500',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     try {
    //         EmpChangeDay::updateOrCreate(
    //             [
    //                 'empId' => $request->empId,
    //                 'month' => $request->month,
    //             ],
    //             [
    //                 'newPresentDays' => $request->newPresentDays,
    //                 'newAbsentDays' => $request->newAbsentDays,
    //                 'newWLDays' => $request->newWLDays,
    //                 'newExtraDays' => $request->newExtraDays,
    //                 'newDays' => $request->newDays,
    //                 'remark' => $request->remark,
    //                 'updatedBy' => Auth::id(),
    //             ]
    //         );

    //         return redirect()->back()->with('success', 'Final attendance days updated successfully!');

    //     } catch (\Exception $e) {
    //         // You can log the error if needed: \Log::error($e->getMessage());
    //         return redirect()->back()->with('error', 'Failed to update attendance. Please try again.');
    //     }
    // }

    // public function processAndSaveAttendance(Request $request)
    // {
    //     $request->validate([
    //         'fMonth' => 'required',
    //         'fBranchId' => 'required',
    //         'fSection' => 'required',
    //     ]);

    //     $branchId = $request->fBranchId;
    //     $month = $request->fMonth;
    //     $section = $request->fSection;

    //     $processedResult = $this->_calculateAttendanceForBranch($branchId, $month, $section);
        
    //     if ($processedResult === false || $processedResult['processedEmployees']->isEmpty()) {
    //         return redirect()->back()->with("error", "No employees found to process for the selected branch and month.");
    //     }
        
    //        $processedEmployees = $processedResult['processedEmployees'];

    //     foreach ($processedEmployees as $employeeData) {
    //         $info = $employeeData['info'];
    //         $totals = $employeeData['totals'];
    //         $query1=['empId' => $employeeData['info']->attendEmpId, 'month' => $month];
    //         $query2=[
    //                 'empCode'            => $totals['empCode'],
    //                 'branchId'            => $branchId,
    //                 'organisation'        => $totals['organisation'],
    //                 'presentDays'        => ($totals['is_edited'] == true) ? $totals['new_present'] : $totals['present'],
    //                 'absentDays'         => ($totals['is_edited'] == true) ? $totals['new_absent'] : $totals['absent'],
    //                 'WLeaveDays'        =>  ($totals['is_edited'] == true) ? $totals['new_wl'] : $totals['total_deductions'],
    //                 'extraWorkDays'     => ($totals['is_edited'] == true) ? $totals['new_extra_work'] : $totals['extra_work'],
    //                 'totalDeductions'    => ($totals['is_edited'] == true) ? $totals['new_wl'] : $totals['total_deductions'],
    //                 'retention'            => $totals['retention'],
    //                 'payableDays'        => $totals['is_edited'] ? $totals['new_final_total'] : $totals['final_total'],
    //                 'grossSalary'         => $totals['grossSalary'],
    //                 'prevGrossSalary'   => $totals['prevGrossSalary'],
    //                 'advanceAgainstSalary'=> $totals['advanceAgainstSalary'],
    //                 'otherDeduction'      => $totals['otherDeduction'],
    //                 'bankAccountNo'       => $totals['bankAccountNo'],
    //                 'bankIFSCCode'        => $totals['bankIFSCCode'],
    //                 'bankName'            => $totals['bankName'],
    //                 'salaryStatus'  =>  $totals['salaryStatus'],
    //                 'isManuallyEdited'  => $totals['is_edited'],
    //             ];

    //        MonthlyAttendanceSummary::updateOrCreate($query1,$query2);   
    //     }

    //     AttendanceJob::updateOrCreate(
    //         ['fBranchId' => $branchId, 'fMonth' => $month, 'section' => $section, 'userType'=>Auth::user()->userType],
    //         ['status' => '2', 'updated_by' => Auth::user()->username]
    //     );

    //     return redirect()->back()->with("success", "Attendance for " . Carbon::parse($month)->format('F Y') . " has been successfully processed and saved.");
    // }

    // private function _calculateAttendanceForBranch($branchId, $month, $section)
    // {
    //     // --- 1. INITIALIZATION AND DATE SETUP ---
    //     try {
    //         $carbonDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
    //     } catch (\Exception $e) { return false; }

    //     $startDate = $carbonDate->copy()->format('Y-m-d');
    //     $endDate = $carbonDate->copy()->endOfMonth()->format('Y-m-d');
    //     $daysInMonth = $carbonDate->daysInMonth;

    //     // --- 3. EFFICIENT DATABASE QUERY ---
    //     $allAttendances = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
    //     ->join('designations', 'emp_dets.designationId', 'designations.id')
    //     ->join('departments', 'emp_dets.departmentId', 'departments.id')
    //     ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
    //     ->select(
    //         'attendance_details.*', 'emp_dets.name', 'emp_dets.empCode', 'emp_dets.jobJoingDate', 'emp_dets.lastDate',
    //         'emp_dets.startTime', 'emp_dets.endTime','emp_dets.id as attendEmpId','emp_dets.organisation','emp_dets.salaryScale',
    //         'emp_dets.bankAccountNo','emp_dets.bankIFSCCode','emp_dets.bankName',
    //         'designations.name as designationName', 'contactus_land_pages.branchName'
    //     )          
    //     ->whereBetween('attendance_details.forDate', [$startDate, $endDate])
    //     ->where('emp_dets.branchId', $branchId)
    //     ->when($section, function ($q, $section) { return $q->where('departments.section', $section); })
    //     ->orderBy('emp_dets.empCode')->orderBy('attendance_details.forDate')
    //     ->get();

    //     if ($allAttendances->isEmpty()) {
    //         return redirect()->back()->withInput()->with("error", "Record Not Found.");
    //     }

    //     $employeeIds = $allAttendances->pluck('attendEmpId')->unique();
    //     $dayChanges = EmpChangeDay::where('month', $month)
    //         ->whereIn('empId', $employeeIds)
    //         ->get()
    //         ->keyBy('empId');

    //     $retentions = Retention::where('month', $month)
    //     ->whereIn('empId', $employeeIds)
    //     ->get()
    //     ->keyBy('empId');

    //     $employeeAdvances = EmpAdvRs::where('startDate', '>=', $month)
    //     ->where('endDate', '<=', $month)
    //     ->where('status', 0)
    //     ->where('accountStatus', 1)
    //     ->whereIn('empId', $employeeIds)
    //     ->get()
    //     ->keyBy('empId');

    //     $empDebits = EmpDebit::where('forMonth',$month)
    //     ->whereIn('empId', $employeeIds)
    //     ->where('status', 0)
    //     ->get()
    //     ->keyBy('empId');
        
    //     // --- 4. PROCESS DATA WITH ALL BUSINESS RULES ---
    //     $attendancesByEmployee = $allAttendances->groupBy('empId');
    //     $processedEmployees = collect();

    //     foreach ($attendancesByEmployee as $empId => $employeeDays) {
    //         $employeeInfo = $employeeDays->first();
            
    //         // Parse joining and last dates to handle pre-joining/post-leaving periods.
    //         $joiningDate = $employeeInfo->jobJoingDate ? Carbon::parse($employeeInfo->jobJoingDate)->startOfDay() : null;
    //         $lastWorkingDate = $employeeInfo->lastDate ? Carbon::parse($employeeInfo->lastDate)->startOfDay() : null;

    //         $dailyDataMap = $employeeDays->keyBy(function($day) {
    //             return Carbon::parse($day->forDate)->format('Y-m-d');
    //         });

    //         $processedDailyStatus = [];
    //         $sandwitchDayDed = 0;
    //         $weeklyRuleDeductions = 0.0;

    //         // region Business Rule Processing
    //         // =========================================================================
    //         // STEP 1: PRELIMINARY AND SANDWICH RULE PROCESSING
    //         // =========================================================================
    //         for ($d = 1; $d <= $daysInMonth; $d++) {
    //             $currentDate = $carbonDate->copy()->day($d)->startOfDay();

    //             // Check if the day is outside the employment period.
    //             if (($joiningDate && $currentDate->lt($joiningDate)) || ($lastWorkingDate && $currentDate->gt($lastWorkingDate))) {
    //                 // Mark with a special status 'NE' (Not Employed). This will be handled in the final loop.
    //                 // This prevents these days from affecting any business rule calculations.
    //                 $processedDailyStatus[$d] = (object)['status' => 'NE', 'dayData' => null];
    //                 continue; // Skip all rule processing for this day.
    //             }

    //             $dayData = $dailyDataMap->get($currentDate->format('Y-m-d'));
    //             if (!$dayData) { $processedDailyStatus[$d] = null; continue; }
    //             $finalStatus = $dayData->dayStatus;

    //             if ($d == 1 && in_array($finalStatus, ['WO', 'LH'])) {
    //                 $otherDays = $employeeDays->filter(function($day) use ($currentDate) {
    //                     return $day->forDate != $currentDate->format('Y-m-d');
    //                 });
    //                 if ($otherDays->isNotEmpty()) {
    //                     $presentDaysCount = $otherDays->filter(function($day) {
    //                         return in_array($day->dayStatus, ['P', 'PL', 'PH', 'PLH']);
    //                     })->count();
    //                     if ($presentDaysCount <= 2) {
    //                         $finalStatus = 'A';
    //                         $sandwitchDayDed++;
    //                     }
    //                 }
    //             }

    //             if (in_array($finalStatus, ['WO', 'LH'])) {
    //                 $firstWorkingDayBefore = null; $firstWorkingDayAfter = null;
    //                 for ($i = $d - 1; $i >= 1; $i--) {
    //                     $prevDayStatus = $processedDailyStatus[$i] ?? null;
    //                     if ($prevDayStatus && !in_array($prevDayStatus->status, ['WO', 'LH', 'NE'])) { $firstWorkingDayBefore = $prevDayStatus; break; }
    //                 }
    //                 for ($i = $d + 1; $i <= $daysInMonth; $i++) {
    //                     $nextDay = $dailyDataMap->get($carbonDate->copy()->day($i)->format('Y-m-d'));
    //                     if ($nextDay && !in_array($nextDay->dayStatus, ['WO', 'LH'])) { $firstWorkingDayAfter = (object)['status' => $nextDay->dayStatus, 'dayData' => $nextDay]; break; }
    //                 }
    //                 if ($firstWorkingDayBefore && $firstWorkingDayAfter &&
    //                     (in_array($firstWorkingDayBefore->status, ['A', '0']) && $firstWorkingDayBefore->dayData->AGFStatus == 0) &&
    //                     (in_array($firstWorkingDayAfter->status, ['A', '0']) && $firstWorkingDayAfter->dayData->AGFStatus == 0)) {
    //                     $finalStatus = 'A';
    //                     $sandwitchDayDed++;
    //                 }
    //             }
    //             $processedDailyStatus[$d] = (object)['status' => $finalStatus, 'dayData' => $dayData];
    //         }

    //         // =========================================================================
    //         // >>> NEW LOGIC START: START-OF-MONTH HOLIDAY RULE <<<
    //         // =========================================================================
    //         if ($daysInMonth >= 4) {
    //             $day1Info = $processedDailyStatus[1] ?? null;
    //             $day2Info = $processedDailyStatus[2] ?? null;
    //             $day3Info = $processedDailyStatus[3] ?? null;
    //             $day4Info = $processedDailyStatus[4] ?? null;
    //             $day5Info = $processedDailyStatus[5] ?? null;
    //             $day6Info = $processedDailyStatus[6] ?? null;

    //             $isDay1Holiday = $day1Info && in_array($day1Info->status, ['WO', 'LH', 'H']);

    //             $areNext5DaysAbsent = $day2Info && in_array($day2Info->status, ['A', '0']) && $day2Info->dayData->AGFStatus == 0 &&
    //                                     $day3Info && in_array($day3Info->status, ['A', '0']) && $day3Info->dayData->AGFStatus == 0 &&
    //                                     $day4Info && in_array($day4Info->status, ['A', '0']) && $day4Info->dayData->AGFStatus == 0 &&
    //                                     $day5Info && in_array($day5Info->status, ['A', '0']) && $day5Info->dayData->AGFStatus == 0 &&
    //                                     $day6Info && in_array($day6Info->status, ['A', '0']) && $day6Info->dayData->AGFStatus == 0;

    //             if ($isDay1Holiday && $areNext5DaysAbsent) {
    //                 $processedDailyStatus[1]->status = 'A'; 
    //                 $sandwitchDayDed++;
    //             }
    //         }
    //         // >>> NEW LOGIC END <<<


    //         // =========================================================================
    //         // >>> NEW LOGIC START: Mark weekend as absent if Mon-Fri was absent <<<
    //         // =========================================================================
    //         for ($d = 1; $d <= $daysInMonth; $d++) {
    //             $currentDate = $carbonDate->copy()->day($d);

    //             if ($currentDate->dayOfWeek == Carbon::SATURDAY) {
    //                 $mondayIndex = $d - 5;
    //                 $fridayIndex = $d - 1;

    //                 if ($mondayIndex < 1) {
    //                     continue; 
    //                 }

    //                 $isFullWeekAbsent = true;
    //                 for ($i = $mondayIndex; $i <= $fridayIndex; $i++) {
    //                     $dayInfo = $processedDailyStatus[$i] ?? null;

    //                     if (!$dayInfo || !in_array($dayInfo->status, ['A', '0']) || $dayInfo->dayData->AGFStatus != 0) {
    //                         $isFullWeekAbsent = false;
    //                         break;
    //                     }
    //                 }

    //                 if ($isFullWeekAbsent) {
    //                     $saturdayInfo = $processedDailyStatus[$d] ?? null;
    //                     if ($saturdayInfo && in_array($saturdayInfo->status, ['WO', 'LH', 'H'])) {
    //                         $saturdayInfo->status = 'A';
    //                         $sandwitchDayDed++;
    //                     }

    //                     $sundayIndex = $d + 1;
    //                     if ($sundayIndex <= $daysInMonth) {
    //                         $sundayInfo = $processedDailyStatus[$sundayIndex] ?? null;
    //                         if ($sundayInfo && in_array($sundayInfo->status, ['WO', 'LH', 'H'])) {
    //                             $processedDailyStatus[$sundayIndex]->status = 'A';
    //                             $processedDailyStatus[$sundayIndex]->sandwitchPolicy = '1';
    //                             $sandwitchDayDed++;
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //         // >>> NEW LOGIC END <<<


    //         // =========================================================================
    //         // STEP 2: WEEKLY ABSENCE RULE WITH HOLIDAY DEDUCTION
    //         // =========================================================================
    //         $weeklyConfig = [
    //             'ABSENT' => ['A', '0'], 'HALF_DAY' => ['PH', 'PLH'], 'HOLIDAY' => ['H', 'LH'],
    //             'WEEKLY_OFF' => 'WO', 'STANDARD_WORK_DAYS' => 6,
    //             'ABSENT_THRESHOLD_RATIO' => 3.5 / 6, 'HALF_DAY_THRESHOLD_RATIO' => 3.0 / 6,
    //         ];

    //         for ($d = 1; $d <= $daysInMonth; $d++) {
    //             $currentDate = $carbonDate->copy()->day($d);
    //             if ($currentDate->dayOfWeek == Carbon::SUNDAY) {
    //                 $sundayIndex = $d;
    //                 $sundayStatusInfo = $processedDailyStatus[$sundayIndex] ?? null;
    //                 if ($sundayStatusInfo && $sundayStatusInfo->status == $weeklyConfig['WEEKLY_OFF']) {
    //                     $startOfWeek = $currentDate->copy()->subDays(6); $endOfWeek = $currentDate->copy()->subDay();
    //                     $weeklyAbsenceCount = 0.0; $weeklyHolidayCount = 0;

    //                     for ($weekDay = $startOfWeek->copy(); $weekDay->lte($endOfWeek); $weekDay->addDay()) {
    //                         if (!$weekDay->isSameMonth($carbonDate)) continue;
    //                         $statusInfo = $processedDailyStatus[$weekDay->day] ?? null;
    //                         if (!$statusInfo) continue;
    //                         if (in_array($statusInfo->status, $weeklyConfig['ABSENT']) && $statusInfo->dayData->AGFStatus == 0) $weeklyAbsenceCount += 1.0;
    //                         elseif (in_array($statusInfo->status, $weeklyConfig['HALF_DAY']) && $statusInfo->dayData->AGFStatus == 0) $weeklyAbsenceCount += 0.5;
    //                         elseif (in_array($statusInfo->status, $weeklyConfig['HOLIDAY'])) $weeklyHolidayCount++;
    //                     }
                        
    //                     $actualWorkDays = $weeklyConfig['STANDARD_WORK_DAYS'] - $weeklyHolidayCount;
    //                     if ($actualWorkDays > 0) {
    //                         $absentThreshold = $weeklyConfig['ABSENT_THRESHOLD_RATIO'] * $actualWorkDays;
    //                         $halfDayThreshold = $weeklyConfig['HALF_DAY_THRESHOLD_RATIO'] * $actualWorkDays;
                            
    //                         if ($weeklyAbsenceCount >= $absentThreshold) {
    //                             $processedDailyStatus[$sundayIndex]->status = 'A';
    //                             $weeklyRuleDeductions += 1.0;
    //                         } elseif ($weeklyAbsenceCount >= $halfDayThreshold) {
    //                             $processedDailyStatus[$sundayIndex]->status = 'PH';
    //                             $weeklyRuleDeductions += 0.5;
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //         // endregion
            
    //         // =========================================================================
    //         // STEP 3: FINAL STATUS DETERMINATION AND TOTALS CALCULATION
    //         // =========================================================================
    //         $totals = ['present' => 0.0, 'absent' => 0.0, 'weekly_leave' => 0.0, 'extra_work' => 0.0];
    //         $lateMarkCount = 0;
    //         $finalDailyObjects = [];
    //         for ($d = 1; $d <= $daysInMonth; $d++) {
    //             $statusInfo = $processedDailyStatus[$d] ?? null;
    //             if (!$statusInfo) { $finalDailyObjects[$d] = null; continue; }

    //             // Handle 'Not Employed' days for final output.
    //             if ($statusInfo->status == 'NE') {
    //                 $finalDailyObjects[$d] = (object)[
    //                     'status' => '0', // Display as '0' as requested
    //                     'class' => 'attend-0',
    //                     'forDate' => $carbonDate->copy()->day($d)->format('Y-m-d'),
    //                     'officeInTime' => null, 'officeOutTime' => null, 'inTime' => null, 'outTime' => null,
    //                     'workingHr' => null, 'AGFStatus' => null, 'repAuthStatus' => null, 'HRStatus' => null,
    //                     'startTime' => null, 'endTime' => null, 'AGFDayStatus' => null
    //                 ];
    //                 // Do NOT add to any totals, and skip to the next day.
    //                 continue;
    //             }

                
    //             $finalStatus = $statusInfo->status;
    //             $dayData = $statusInfo->dayData;
    //             $isLate = false;

    //             if (in_array($finalStatus, ['P', 'PL', 'PLH']) && $dayData->inTime && $dayData->outTime && $dayData->officeInTime && $dayData->officeOutTime) {
    //                 $officeStartTime = Carbon::parse($dayData->forDate . ' ' . $dayData->officeInTime);
    //                 $officeEndTime = Carbon::parse($dayData->forDate . ' ' . $dayData->officeOutTime);
    //                 $actualInTime = Carbon::parse($dayData->inTime);
    //                 $actualOutTime = Carbon::parse($dayData->outTime);
    //                 $requiredMinutes = $officeEndTime->diffInMinutes($officeStartTime);
    //                 $requiredHalfDayMinutes = $requiredMinutes / 2;
    //                 $actualMinutesWorked = $actualOutTime->diffInMinutes($actualInTime);

    //                 if ($actualMinutesWorked < $requiredHalfDayMinutes && $dayData->AGFStatus == 0) {
    //                     $finalStatus = 'A';
    //                 } else {
    //                     $leftEarly = $actualOutTime->isBefore($officeEndTime->copy()->subMinutes(15));
                        
    //                     $shiftMidpoint = $officeStartTime->copy()->addMinutes($requiredHalfDayMinutes);
    //                     $workedInFirstHalf = $actualInTime->lt($shiftMidpoint);
    //                     $workedInSecondHalf = $actualOutTime->gt($shiftMidpoint);
    //                     $isHalfDayDueToShiftSpan = !($workedInFirstHalf && $workedInSecondHalf);
                        
    //                     if ($isHalfDayDueToShiftSpan && $leftEarly && $dayData->AGFStatus == 0) {
    //                         $finalStatus = 'A';
    //                     } elseif (($isHalfDayDueToShiftSpan || $leftEarly) && $dayData->AGFStatus == 0) {
    //                         $finalStatus = 'PH';
    //                     } else {
    //                         if ($actualInTime->isAfter($officeStartTime->copy()->addMinutes(7)) && $dayData->AGFStatus == 0) {
    //                            $isLate = true;
    //                            if ($finalStatus == 'P') $finalStatus = 'PL';
    //                         }
    //                         else
    //                         {
    //                             if($dayData->AGFDayStatus == 'Full Day')
    //                                 $finalStatus = 'P';
    //                             else
    //                                 $finalStatus = 'PH';
    //                         }
    //                     }
    //                 }
    //             }
    //             else
    //             {
    //                 if($finalStatus == 'A' && $dayData->AGFStatus != 0)
    //                 {
    //                     if($dayData->AGFDayStatus == 'Full Day')
    //                         $finalStatus = 'P';
    //                     else
    //                         $finalStatus = 'PH';
    //                 }
    //             }

    //             if ($isLate) $lateMarkCount++;
    //             if (in_array($finalStatus, ['WO', 'LH']) && $dayData->AGFStatus != 0) {
    //                  $application = EmpApplication::where('id', $dayData->AGFStatus)->where('reason', 'Extra Working on Holiday')->count();
    //                         if($application)
    //                             $totals['extra_work'] += ($dayData->AGFDayStatus == 'Full Day') ? 1.0 : 0.5;
    //             }

    //             if(($dayData->dayStatus == 'PH' || $dayData->dayStatus == 'PLH')  && $dayData->AGFStatus != 0)
    //             {
    //                 if($dayData->AGFDayStatus == 'Full Day')
    //                     $finalStatus = 'P';
    //                 else
    //                     $finalStatus = 'PH';
    //             }

    //             switch ($finalStatus) {
    //                 case 'P': case 'PL': $totals['present'] += 1.0; break;
    //                 case 'A': case '0': $totals['absent'] += 1.0; break;
    //                 case 'PH':case 'PLH': $totals['present'] += 0.5; $totals['absent'] += 0.5; break;
    //                 case 'WO': case 'LH': $totals['weekly_leave'] += 1.0; break;
    //             }
    //             $finalDailyObjects[$d] = (object)[
    //                 'officeInTime' => $dayData->officeInTime,'officeOutTime' => $dayData->officeOutTime,'status' => $finalStatus, 
    //                 'class' => 'attend-'.$finalStatus, 'forDate' => $dayData->forDate, 'inTime' => $dayData->inTime,
    //                 'outTime' => $dayData->outTime, 'workingHr' => $dayData->workingHr, 'AGFStatus' => $dayData->AGFStatus,
    //                 'repAuthStatus' => $dayData->repAuthStatus, 'HRStatus' => $dayData->HRStatus, 'startTime'=>$dayData->startTime,
    //                 'endTime'=>$dayData->endTime, 'AGFDayStatus' => $dayData->AGFDayStatus
    //             ];
    //         }

    //         $lateMarkDeduction = floor($lateMarkCount / 3);
    //         if ($lateMarkDeduction > 0) {
    //             $totals['present'] -= $lateMarkDeduction;
    //             $totals['absent'] += $lateMarkDeduction;
    //         }
            
    //         $totals['late_mark_deductions'] = $lateMarkDeduction;
    //         $totals['sandwitch_deductions'] = $sandwitchDayDed;
    //         $totals['weekly_rule_deductions'] = $weeklyRuleDeductions;
    //         $totals['total_deductions'] = $lateMarkDeduction + $sandwitchDayDed + $weeklyRuleDeductions;
    //         $totals['present'] = $totals['present'] + $totals['weekly_leave'];
    //         $totals['final_total'] = $totals['present'] + $totals['extra_work'];
    //         $totals['absent'] = $totals['absent'] - $totals['total_deductions'];
            
    //         $changeData = $dayChanges->get($employeeInfo->attendEmpId);
    //         $totals['is_edited'] = false;
    //         if ($changeData) {
    //             $totals['is_edited'] = true;
    //             $totals['remark'] = $changeData->remark;
    //             $totals['new_present'] = $changeData->newPresentDays;
    //             $totals['new_absent'] = $changeData->newAbsentDays;
    //             $totals['new_wl'] = $changeData->newWLDays;
    //             $totals['new_extra_work'] = $changeData->newExtraDays;
    //             $totals['new_final_total'] = $changeData->newDays;
    //         }

    //         $retentionData = $retentions->get($empId);
    //         $employeeAdvanceData = $employeeAdvances->get($empId);
    //         $empDebitData = $empDebits->get($empId);

    //         $totals['empCode'] = $employeeInfo->empCode;
    //         $totals['organisation'] = $employeeInfo->organisation;
    //         $totals['retention'] = ($retentionData->retentionAmount)??0;
    //         $totals['prevGrossSalary'] = (EmpMr::Where('empId', $employeeInfo->attendEmpId)->where('forDate', '2025-03')->value('grossSalary'))??0;
    //         $totals['grossSalary'] = $employeeInfo->salaryScale;
    //         $totals['advanceAgainstSalary'] = ($employeeAdvanceData->deduction)??0;
    //         $totals['otherDeduction'] = ($empDebitData->amount)??0;
    //         $totals['bankAccountNo'] = $employeeInfo->bankAccountNo;
    //         $totals['bankIFSCCode'] = $employeeInfo->bankIFSCCode;
    //         $totals['bankName'] = $employeeInfo->bankName;
    //         $totals['salaryStatus'] = 0;
        
    //         $employeeInfo->finalSalaryStatus = $employeeDays->last()->salaryHoldRelease ?? 0;
    //         $processedEmployees->push(['info' => $employeeInfo, 'days' => $finalDailyObjects, 'totals' => $totals]);

    //     }

    //     return [
    //         'processedEmployees' => $processedEmployees,
    //         'carbonDate' => $carbonDate,
    //         'daysInMonth' => $daysInMonth
    //     ];
    // }

    // public function editFinalAttendanceSheet(Request $request, $empId, $month)
    // {
    //     // --- 1. SETUP AND VALIDATION ---
    //     $finalMonth = $month;
    //     $finalEmpId = $empId;
    //     $userType = Auth::user()->userType;

    //     // --- 2. DATES AND CONFIGURATION ---
    //     try {
    //         // Create a Carbon instance from the 'YYYY-MM' format and set it to the 1st day.
    //         $carbonDate = Carbon::createFromFormat('Y-m', $finalMonth)->startOfMonth();
    //     } catch (\Exception $e) {
    //         return redirect()->back()->withInput()->with("error", "Invalid month format provided.");
    //     }

    //     $startDate = $carbonDate->copy()->format('Y-m-d');
    //     $daysInMonth = $carbonDate->daysInMonth;

    //     // Conditionally set the end date based on the new requirement.
    //     if ($finalMonth == date('Y-m')) {
    //         $endDate = Carbon::now()->format('Y-m-d');
    //     } else {
    //         $endDate = $carbonDate->copy()->endOfMonth()->format('Y-m-d');
    //     }

    //     // --- 3. EFFICIENT DATABASE QUERY ---
    //     $allAttendances = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
    //         ->join('designations', 'emp_dets.designationId', 'designations.id')
    //         ->join('departments', 'emp_dets.departmentId', 'departments.id')
    //         ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
    //         ->select(
    //             'attendance_details.*',
    //             'emp_dets.name',
    //             'emp_dets.empCode',
    //             'emp_dets.jobJoingDate',
    //             'emp_dets.lastDate',
    //             'emp_dets.startTime',
    //             'emp_dets.endTime',
    //             'emp_dets.id as attendEmpId',
    //             'designations.name as designationName',
    //             'contactus_land_pages.branchName'
    //         )
    //         ->whereBetween('attendance_details.forDate', [$startDate, $endDate])
    //         ->where('emp_dets.id', $finalEmpId)
    //         ->orderBy('attendance_details.forDate')
    //         ->get();

    //     if ($allAttendances->isEmpty()) {
    //         return redirect()->back()->withInput()->with("error", "Record Not Found.");
    //     }

    //     // Fetch manual override data for this specific employee
    //     $dayChanges = EmpChangeDay::where('month', $finalMonth)
    //         ->where('empId', $finalEmpId)
    //         ->get()
    //         ->keyBy('empId');

    //     // --- 4. PROCESS DATA WITH ALL BUSINESS RULES ---
    //     $attendancesByEmployee = $allAttendances->groupBy('attendEmpId');
    //     $processedEmployees = collect();

    //     foreach ($attendancesByEmployee as $empIdKey => $employeeDays) {
    //         $employeeInfo = $employeeDays->first();

    //         $joiningDate = $employeeInfo->jobJoingDate ? Carbon::parse($employeeInfo->jobJoingDate)->startOfDay() : null;
    //         $lastWorkingDate = $employeeInfo->lastDate ? Carbon::parse($employeeInfo->lastDate)->startOfDay() : null;

    //         $dailyDataMap = $employeeDays->keyBy(function ($day) {
    //             return Carbon::parse($day->forDate)->format('Y-m-d');
    //         });

    //         $processedDailyStatus = [];
    //         $sandwitchDayDed = 0;
    //         $weeklyRuleDeductions = 0.0;
    //         $lateMarkCount = 0;
            
    //         // =========================================================================
    //         // STEP 1: PRELIMINARY AND SANDWICH RULE PROCESSING
    //         // =========================================================================
    //         for ($d = 1; $d <= $daysInMonth; $d++) {
    //             $currentDate = $carbonDate->copy()->day($d)->startOfDay();

    //             if (($joiningDate && $currentDate->lt($joiningDate)) || ($lastWorkingDate && $currentDate->gt($lastWorkingDate))) {
    //                 $processedDailyStatus[$d] = (object)['status' => 'NE', 'dayData' => null];
    //                 continue;
    //             }

    //             $dayData = $dailyDataMap->get($currentDate->format('Y-m-d'));
    //             if (!$dayData) {
    //                 $processedDailyStatus[$d] = null;
    //                 continue;
    //             }
    //             $finalStatus = $dayData->dayStatus;

    //             if ($d == 1 && in_array($finalStatus, ['WO', 'LH'])) {
    //                 $otherDays = $employeeDays->filter(function ($day) use ($currentDate) {
    //                     return $day->forDate != $currentDate->format('Y-m-d');
    //                 });
    //                 if ($otherDays->isNotEmpty()) {
    //                     $presentDaysCount = $otherDays->filter(function ($day) {
    //                         return in_array($day->dayStatus, ['P', 'PL', 'PH', 'PLH']);
    //                     })->count();
    //                     if ($presentDaysCount <= 2) {
    //                         $finalStatus = 'A';
    //                         $sandwitchDayDed++;
    //                     }
    //                 }
    //             }

    //             if (in_array($finalStatus, ['WO', 'LH'])) {
    //                 $firstWorkingDayBefore = null;
    //                 $firstWorkingDayAfter = null;
    //                 for ($i = $d - 1; $i >= 1; $i--) {
    //                     $prevDayStatus = $processedDailyStatus[$i] ?? null;
    //                     if ($prevDayStatus && !in_array($prevDayStatus->status, ['WO', 'LH', 'NE'])) {
    //                         $firstWorkingDayBefore = $prevDayStatus;
    //                         break;
    //                     }
    //                 }
    //                 for ($i = $d + 1; $i <= $daysInMonth; $i++) {
    //                     $nextDay = $dailyDataMap->get($carbonDate->copy()->day($i)->format('Y-m-d'));
    //                     if ($nextDay && !in_array($nextDay->dayStatus, ['WO', 'LH'])) {
    //                         $firstWorkingDayAfter = (object)['status' => $nextDay->dayStatus, 'dayData' => $nextDay];
    //                         break;
    //                     }
    //                 }
    //                 if (
    //                     $firstWorkingDayBefore && $firstWorkingDayAfter &&
    //                     (in_array($firstWorkingDayBefore->status, ['A', '0']) && $firstWorkingDayBefore->dayData->AGFStatus == 0) &&
    //                     (in_array($firstWorkingDayAfter->status, ['A', '0']) && $firstWorkingDayAfter->dayData->AGFStatus == 0)
    //                 ) {
    //                     $finalStatus = 'A';
    //                     $sandwitchDayDed++;
    //                 }
    //             }
    //             $processedDailyStatus[$d] = (object)['status' => $finalStatus, 'dayData' => $dayData];
    //         }

    //         // =========================================================================
    //         // >>> NEW LOGIC START: START-OF-MONTH HOLIDAY RULE <<<
    //         // =========================================================================
    //         if ($daysInMonth >= 4) {
    //             $day1Info = $processedDailyStatus[1] ?? null;
    //             $day2Info = $processedDailyStatus[2] ?? null;
    //             $day3Info = $processedDailyStatus[3] ?? null;
    //             $day4Info = $processedDailyStatus[4] ?? null;
    //             $day5Info = $processedDailyStatus[5] ?? null;
    //             $day6Info = $processedDailyStatus[6] ?? null;

    //             $isDay1Holiday = $day1Info && in_array($day1Info->status, ['WO', 'LH', 'H']);

    //             $areNext5DaysAbsent = $day2Info && in_array($day2Info->status, ['A', '0']) && $day2Info->dayData->AGFStatus == 0 &&
    //                 $day3Info && in_array($day3Info->status, ['A', '0']) && $day3Info->dayData->AGFStatus == 0 &&
    //                 $day4Info && in_array($day4Info->status, ['A', '0']) && $day4Info->dayData->AGFStatus == 0 &&
    //                 $day5Info && in_array($day5Info->status, ['A', '0']) && $day5Info->dayData->AGFStatus == 0 &&
    //                 $day6Info && in_array($day6Info->status, ['A', '0']) && $day6Info->dayData->AGFStatus == 0;

    //             if ($isDay1Holiday && $areNext5DaysAbsent) {
    //                 $processedDailyStatus[1]->status = 'A';
    //                 $sandwitchDayDed++;
    //             }
    //         }
    //         // >>> NEW LOGIC END <<<

    //         // =========================================================================
    //         // >>> NEW LOGIC START: Mark weekend as absent if Mon-Fri was absent <<<
    //         // =========================================================================
    //         for ($d = 1; $d <= $daysInMonth; $d++) {
    //             $currentDate = $carbonDate->copy()->day($d);

    //             if ($currentDate->dayOfWeek == Carbon::SATURDAY) {
    //                 $mondayIndex = $d - 5;
    //                 $fridayIndex = $d - 1;

    //                 if ($mondayIndex < 1) {
    //                     continue;
    //                 }

    //                 $isFullWeekAbsent = true;
    //                 for ($i = $mondayIndex; $i <= $fridayIndex; $i++) {
    //                     $dayInfo = $processedDailyStatus[$i] ?? null;

    //                     if (!$dayInfo || !in_array($dayInfo->status, ['A', '0']) || $dayInfo->dayData->AGFStatus != 0) {
    //                         $isFullWeekAbsent = false;
    //                         break;
    //                     }
    //                 }

    //                 if ($isFullWeekAbsent) {
    //                     $saturdayInfo = $processedDailyStatus[$d] ?? null;
    //                     if ($saturdayInfo && in_array($saturdayInfo->status, ['WO', 'LH', 'H'])) {
    //                         $saturdayInfo->status = 'A';
    //                         $sandwitchDayDed++;
    //                     }

    //                     $sundayIndex = $d + 1;
    //                     if ($sundayIndex <= $daysInMonth) {
    //                         $sundayInfo = $processedDailyStatus[$sundayIndex] ?? null;
    //                         if ($sundayInfo && in_array($sundayInfo->status, ['WO', 'LH', 'H'])) {
    //                             $processedDailyStatus[$sundayIndex]->status = 'A';
    //                             $sandwitchDayDed++;
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //         // >>> NEW LOGIC END <<<


    //         // =========================================================================
    //         // STEP 2: WEEKLY ABSENCE RULE WITH HOLIDAY DEDUCTION
    //         // =========================================================================
    //         $weeklyConfig = [
    //             'ABSENT' => ['A', '0'], 'HALF_DAY' => ['PH', 'PLH'], 'HOLIDAY' => ['H', 'LH'],
    //             'WEEKLY_OFF' => 'WO', 'STANDARD_WORK_DAYS' => 6,
    //             'ABSENT_THRESHOLD_RATIO' => 3.5 / 6, 'HALF_DAY_THRESHOLD_RATIO' => 3.0 / 6,
    //         ];

    //         for ($d = 1; $d <= $daysInMonth; $d++) {
    //             $currentDate = $carbonDate->copy()->day($d);
    //             if ($currentDate->dayOfWeek == Carbon::SUNDAY) {
    //                 $sundayIndex = $d;
    //                 $sundayStatusInfo = $processedDailyStatus[$sundayIndex] ?? null;
    //                 if ($sundayStatusInfo && $sundayStatusInfo->status == $weeklyConfig['WEEKLY_OFF']) {
    //                     $startOfWeek = $currentDate->copy()->subDays(6);
    //                     $endOfWeek = $currentDate->copy()->subDay();
    //                     $weeklyAbsenceCount = 0.0;
    //                     $weeklyHolidayCount = 0;

    //                     for ($weekDay = $startOfWeek->copy(); $weekDay->lte($endOfWeek); $weekDay->addDay()) {
    //                         if (!$weekDay->isSameMonth($carbonDate)) continue;
    //                         $statusInfo = $processedDailyStatus[$weekDay->day] ?? null;
    //                         if (!$statusInfo) continue;
    //                         if (in_array($statusInfo->status, $weeklyConfig['ABSENT']) && $statusInfo->dayData->AGFStatus == 0) $weeklyAbsenceCount += 1.0;
    //                         elseif (in_array($statusInfo->status, $weeklyConfig['HALF_DAY']) && $statusInfo->dayData->AGFStatus == 0) $weeklyAbsenceCount += 0.5;
    //                         elseif (in_array($statusInfo->status, $weeklyConfig['HOLIDAY'])) $weeklyHolidayCount++;
    //                     }

    //                     $actualWorkDays = $weeklyConfig['STANDARD_WORK_DAYS'] - $weeklyHolidayCount;
    //                     if ($actualWorkDays > 0) {
    //                         $absentThreshold = $weeklyConfig['ABSENT_THRESHOLD_RATIO'] * $actualWorkDays;
    //                         $halfDayThreshold = $weeklyConfig['HALF_DAY_THRESHOLD_RATIO'] * $actualWorkDays;

    //                         if ($weeklyAbsenceCount >= $absentThreshold) {
    //                             $processedDailyStatus[$sundayIndex]->status = 'A';
    //                             $weeklyRuleDeductions += 1.0;
    //                         } elseif ($weeklyAbsenceCount >= $halfDayThreshold) {
    //                             $processedDailyStatus[$sundayIndex]->status = 'PH';
    //                             $weeklyRuleDeductions += 0.5;
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //         // endregion

    //         // =========================================================================
    //         // STEP 3: FINAL STATUS DETERMINATION AND TOTALS CALCULATION
    //         // =========================================================================
    //         $totals = ['present' => 0.0, 'absent' => 0.0, 'weekly_leave' => 0.0, 'extra_work' => 0.0];
    //         $finalDailyObjects = [];
    //         for ($d = 1; $d <= $daysInMonth; $d++) {
    //             $statusInfo = $processedDailyStatus[$d] ?? null;
    //             if (!$statusInfo) {
    //                 $finalDailyObjects[$d] = null;
    //                 continue;
    //             }

    //             if ($statusInfo->status == 'NE') {
    //                 $finalDailyObjects[$d] = (object)[
    //                     'status' => '0',
    //                     'class' => 'attend-A',
    //                     'forDate' => $carbonDate->copy()->day($d)->format('Y-m-d'),
    //                     'officeInTime' => null, 'officeOutTime' => null, 'inTime' => null, 'outTime' => null,
    //                     'workingHr' => null, 'AGFStatus' => null, 'repAuthStatus' => null, 'HRStatus' => null,
    //                     'startTime' => null, 'endTime' => null, 'AGFDayStatus' => null
    //                 ];
    //                 continue;
    //             }

    //             $finalStatus = $statusInfo->status;
    //             $dayData = $statusInfo->dayData;

    //             if (in_array($finalStatus, ['P', 'PL', 'PLH', 'PH']) && $dayData->inTime && $dayData->outTime && $dayData->officeInTime && $dayData->officeOutTime) {
    //                 $officeStartTime = Carbon::parse($dayData->forDate . ' ' . $dayData->officeInTime);
    //                 $officeEndTime = Carbon::parse($dayData->forDate . ' ' . $dayData->officeOutTime);
    //                 $actualInTime = Carbon::parse($dayData->inTime);
    //                 $actualOutTime = Carbon::parse($dayData->outTime);
    //                 $requiredMinutes = $officeEndTime->diffInMinutes($officeStartTime);
    //                 $requiredHalfDayMinutes = $requiredMinutes / 2;
    //                 $actualMinutesWorked = $actualOutTime->diffInMinutes($actualInTime);

    //                 if ($actualMinutesWorked < $requiredHalfDayMinutes && $dayData->AGFStatus == 0) {
    //                     $finalStatus = 'A';
    //                 } else {
    //                     $leftEarly = $actualOutTime->isBefore($officeEndTime->copy()->subMinutes(15));

    //                     $shiftMidpoint = $officeStartTime->copy()->addMinutes($requiredHalfDayMinutes);
    //                     $workedInFirstHalf = $actualInTime->lt($shiftMidpoint);
    //                     $workedInSecondHalf = $actualOutTime->gt($shiftMidpoint);
    //                     $isHalfDayDueToShiftSpan = !($workedInFirstHalf && $workedInSecondHalf);

    //                     if ($isHalfDayDueToShiftSpan && $leftEarly && $dayData->AGFStatus == 0) {
    //                         $finalStatus = 'A';
    //                     } elseif (($isHalfDayDueToShiftSpan || $leftEarly) && $dayData->AGFStatus == 0) {
    //                         $finalStatus = 'PH';
    //                     } else {
    //                         if ($actualInTime->isAfter($officeStartTime->copy()->addMinutes(7)) && $dayData->AGFStatus == 0) {
    //                             if ($finalStatus == 'P') $finalStatus = 'PL';
    //                         } else {
    //                             if ($dayData->AGFDayStatus == 'Full Day')
    //                                 $finalStatus = 'P';
    //                             else
    //                                 $finalStatus = 'PH';
    //                         }
    //                     }
    //                 }
    //             } else {
    //                 if ($finalStatus == 'A' && $dayData->AGFStatus != 0) {
    //                     if ($dayData->AGFDayStatus == 'Full Day')
    //                         $finalStatus = 'P';
    //                     else
    //                         $finalStatus = 'PH';
    //                 } else {
    //                     if ($dayData->AGFDayStatus == 'Full Day' && $dayData->AGFStatus != 0 && $dayData->holiday == 0)
    //                         $finalStatus = 'P';
    //                     else if ($dayData->AGFDayStatus == 'Half Day' && $dayData->AGFStatus != 0 && $dayData->holiday == 0)
    //                         $finalStatus = 'PH';
    //                     else if ($dayData->holiday == 0)
    //                         $finalStatus = 'A';
    //                 }
    //             }

    //             if (($dayData->dayStatus == 'PH' || $dayData->dayStatus == 'PLH') && $dayData->AGFStatus != 0) {
    //                 if ($dayData->AGFDayStatus == 'Full Day')
    //                     $finalStatus = 'P';
    //                 else
    //                     $finalStatus = 'PH';
    //             }

    //             // Moved late mark counting to the switch statement.
    //             switch ($finalStatus) {
    //                 case 'P':
    //                     $totals['present'] += 1.0;
    //                     break;
    //                 case 'PL':
    //                     $totals['present'] += 1.0;
    //                     $lateMarkCount++;
    //                     break;
    //                 case 'A':
    //                 case '0':
    //                     $totals['absent'] += 1.0;
    //                     break;
    //                 case 'PH':
    //                 case 'PLH':
    //                     $totals['present'] += 0.5;
    //                     $totals['absent'] += 0.5;
    //                     if ($finalStatus == 'PLH') {
    //                         $lateMarkCount++;
    //                     }
    //                     break;
    //                 case 'WO':
    //                 case 'LH':
    //                     $totals['weekly_leave'] += 1.0;
    //                     if ($dayData->AGFStatus != 0) {
    //                         $totals['extra_work'] += ($dayData->AGFDayStatus == 'Full Day') ? 1.0 : 0.5;
    //                     }
    //                     break;
    //             }
    //             $finalDailyObjects[$d] = (object)[
    //                 'officeInTime' => $dayData->officeInTime, 'officeOutTime' => $dayData->officeOutTime, 'status' => $finalStatus,
    //                 'class' => 'attend-' . $finalStatus, 'forDate' => $dayData->forDate, 'inTime' => $dayData->inTime,
    //                 'outTime' => $dayData->outTime, 'workingHr' => $dayData->workingHr, 'deviceInTime' => $dayData->deviceInTime, 'deviceOutTime' => $dayData->deviceOutTime,
    //                 'AGFStatus' => $dayData->AGFStatus, 'repAuthStatus' => $dayData->repAuthStatus, 'HRStatus' => $dayData->HRStatus,
    //                 'startTime' => $dayData->startTime, 'endTime' => $dayData->endTime, 'AGFDayStatus' => $dayData->AGFDayStatus
    //             ];
    //         }
    //         // endregion

    //         $lateMarkDeduction = floor($lateMarkCount / 3);
    //         if ($lateMarkDeduction > 0) {
    //             $totals['present'] -= $lateMarkDeduction;
    //             // $totals['absent'] += $lateMarkDeduction;
    //         }

    //         $totals['late_mark_deductions'] = $lateMarkDeduction;
    //         $totals['sandwitch_deductions'] = $sandwitchDayDed;
    //         $totals['weekly_rule_deductions'] = $weeklyRuleDeductions;
    //         $totals['total_deductions'] = $lateMarkDeduction + $sandwitchDayDed + $weeklyRuleDeductions;
    //         $totals['present'] = $totals['present'] + $totals['weekly_leave'];
    //         $totals['final_total'] = $totals['present'] + $totals['extra_work'];
    //         $totals['absent'] = $totals['absent'] - ($sandwitchDayDed + $weeklyRuleDeductions);
            
    //         $changeData = $dayChanges->get($empIdKey);
    //         $totals['is_edited'] = false;
    //         if ($changeData) {
    //             $totals['is_edited'] = true;
    //             $totals['remark'] = $changeData->remark;
    //             $totals['new_present'] = $changeData->newPresentDays;
    //             $totals['new_absent'] = $changeData->newAbsentDays;
    //             $totals['new_wl'] = $changeData->newWLDays;
    //             $totals['new_extra_work'] = $changeData->newExtraDays;
    //             $totals['new_final_total'] = $changeData->newDays;
    //         }

    //         $processedEmployees->push(['info' => $employeeInfo, 'days' => $finalDailyObjects, 'totals' => $totals]);
    //     }

    //     // --- 5. SUMMARIZE AND PAGINATE ---
    //     // Since we are only processing a single employee, the summary is straightforward.
    //     $employeeData = $processedEmployees->first();

    //     // Pass the data to the view.
    //     return view('admin.attendance.editAuthorityAttendanceSheet')->with([
    //         'employeeData' => $employeeData,
    //         'daysInMonth' => $daysInMonth,
    //         'carbonDate' => $carbonDate,
    //         'userType' => $userType,
    //         'finalEmpId' => $finalEmpId,
    //         'finalMonth' => $finalMonth
    //     ]);
    // }

    // public function updateFinalAttendance(Request $request) // latest
    // {
    //     // return $request->all();
    //     $userType = Auth::user()->userType;
    //     if($userType == '501')
    //     {
    //         $empDetail = EmpDet::where('id', $request->empId)->first();
    //         $section = Department::where('id', $empDetail->departmentId)->value('section');
    //         $change = EmpChangeDay::where('empId', $empDetail->id)->where('month', $request->finalMonth)->first();  
    //         if(!$change)
    //             $change = new EmpChangeDay;               

    //         $change->empId = $empDetail->id; 
    //         $change->oldPresentDays = $request->oldPresentDays; 
    //         $change->newPresentDays = $request->presentDays; 

    //         $change->oldAbsentDays = $request->oldAbsentDays; 
    //         $change->newAbsentDays = $request->absentDays; 

    //         $change->oldWLDays = $request->oldWLDays; 
    //         $change->newWLDays = $request->WLDays; 

    //         $change->oldExtraDays = $request->oldExtraWorkDays; 
    //         $change->newExtraDays = $request->extraWorkDays; 

    //         $change->oldDays = $request->oldPayableDays; 
    //         $change->newDays = $request->payableDays; 

    //         $change->remark = $request->remark; 
    //         $change->month = $request->finalMonth; 
    //         $change->updated_by = Auth::user()->username;
    //         $change->save();

    //         $path = '/empAttendances/finalAttendanceSheet?section='.$section.'&branchId='.$empDetail->branchId.'&month='.$request->finalMonth.'&flag=2';

    //         return redirect($path)->with("success","Employee Days Updated successfully..");
    //     }
    //     else
    //     {
    //         return redirect()->back()->withInput()->with("error","Your not authorized Access...");
    //     }
    // }

    // public function confirmSheetList(Request $request)
    // {
    //     $forMonth = $request->forMonth;
    //     if($forMonth == '')
    //         $forMonth=date('Y-m');

    //     $jobs = AttendanceJob::join('contactus_land_pages', 'attendance_jobs.fBranchId','contactus_land_pages.id')
    //     ->select('contactus_land_pages.branchName', 'attendance_jobs.*')
    //     ->where('attendance_jobs.fMonth', $forMonth)
    //     ->get();
    //     return view('admin.attendance.jobHistory', compact('jobs', 'forMonth'));
    // }

    public function exportFinalAttendance($month, $branchId)
    {
        $attendanceCt = EmpMr::where('forDate', $month)
        ->where('branchId', $branchId)
        ->count();
        if($attendanceCt == 0)
            return redirect()->back()->withInput()->with("error","Please Confirm sheet.then you can export sheet.");

        $branch = ContactusLandPage::where('id', $branchId)->value('shortName');
        $fileName = $branch.'.xlsx';
        return Excel::download(new FinalAttendanceExport($month, $branchId), $fileName);
    }

    public function create(Request $request)
    {
        $month=date('M');
        $year=date('Y');
        $util = new Utility();
        $empCode = session()->get('empCode');
        $attend = $util->getMonthlyEmpAttendance($empCode, ($month.'-'.$year));
        $totDays = $attend[0];
        $presentDays = $attend[1];
        $absentDays = $attend[2];
        return view('admin.attendance.empAttendanceList')->with(['month'=>$month, 'year'=>$year,
        'totDays'=>$totDays, 'presentDays'=>$presentDays,'absentDays'=>$absentDays]);
    }

    public function uploadAttendanceSheet(Request $request)
    {
        // $month = $request->input('month');
        $startDate =  date('Y-m', strtotime('-1 month'));
        $days =  date('t', strtotime('-1 month'));
        $attendances = AttendanceFinalDetail::where('forMonth',  $startDate)->get();

        return view('admin.tempAttendance', compact('attendances', 'startDate','days'));
    }

    private function applySandwichLogic($attendances)
    {
        $loss = 0;
        $absentCount = 0;
        foreach ($attendances as $day) {
            if ($day->dayStatus == 'A') $absentCount++;
        }
        if ($absentCount >= 3.5) {
            $loss += 1; // Full day WO
        } elseif ($absentCount >= 3) {
            $loss += 0.5; // Half day WO
        }
        return $loss;
    }

    public function uploadExcel(Request $request)
    {
        Excel::import(new SalarySheetImport, $request->file('excelFile'));
        return back()->with('success', 'Excel Data Imported Successfully & Punch Time will update shortly...');
    }

    public function exportExcel(Request $request)
    {
        $fileName = "AttendanceSheet_".date('M-Y', strtotime($request->month)).".xlsx";
        return Excel::download(new AttendanceReportExport($request->empCode, $request->organisation, $request->section, $request->branchId, $request->month), $fileName);
    }

    public function exportPDF($empCode, $branchId, $departmentId, $month)
    {
        return $reqeust->all();
    }

    public function salaryReport(Request $request)
    { 
        $empCode = session()->get('empCode');
        $year = date('Y');
        $attendances = AttendanceDetail::select('month', 'year')
        ->where('year', $year)
        ->where('empCode', $empCode)
        ->where('month', '!=',date('M'))
        ->whereActive(1)
        ->orderByRaw("FIELD(month,'Dec', 'Nov', 'Oct', 'Sep', 'Aug', 'Jul', 'Jun', 'May', 'Apr', 'Mar', 'Feb', 'Jan')")
        ->get();
        return view('admin.empPayroll.salaryReport')->with(['attendances'=>$attendances]);
    }

    public function salarySlip(Request $request)
    {
        $salarySlips = PayrollApplication::where('appType', 1)
        ->where('empId', Auth::user()->empId)
        ->paginate(10);
        return view('admin.empPayroll.salarySlip')->with(['salarySlips'=>$salarySlips]);
    }

    public function raiseReqSalarySlip(Request $request)
    {
        try
        {
            $month = $request->month;
            if($month == '')
                return view('admin.empPayroll.raiseReqSalarySlip');
            else
            {
                $empCode = session()->get('empCode');
                $empDet = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
                ->join('organisations', 'emp_dets.organisationId', 'organisations.id')
                ->join('designations', 'emp_dets.designationId', 'designations.id')
                ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
                ->select('emp_dets.id as empId','emp_dets.jobJoingDate','emp_dets.bankName'
                ,'emp_dets.bankAccountNo','emp_dets.name', 'emp_dets.firmType', 'emp_dets.empCode',
                'departments.name as deptName', 'organisations.name as organisationName','designations.name as desName', 'emp_dets.AADHARNo','emp_dets.PANNo',
                'contactus_land_pages.branchName')
                ->where('empCode', $empCode)
                ->first();
                if($month <= '2025-05')
                    $salDet = EmpMr::select('*','extraWorking as extraWorkDays','forDate as month', 'totPresent as presentDays')->where('empId', $empDet->empId)->where('forDate', $month)->first();
                else
                    $salDet = MonthlyAttendanceSummary::where('empId', $empDet->empId)->where('month', $month)->first();

                return view('admin.empPayroll.viewSalarySlip')->with(['empDet'=>$empDet,'salDet'=>$salDet, 'month'=>$month]);
            }
        }
        catch(\Exception $e){
            DB::rollback();
            return $e->getMessage();
            $data = [
                'action' => 'Bill Format Controller store() Method',
                'params' => $request->all(),
                'exception' => $e->getMessage()
            ];
            $errorHandler = $util->errorLogHandler($data);
            abort(500);
            return redirect()->back()->withInput()->with("error","There is some issue. Please try again!!!");
        }

    }

    public function exportSalarySlip($month)
    {
        // $empCode = session()->get('empCode');
        $empId = Auth::user()->empId;
        $empDet = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.id as empId','emp_dets.name', 'emp_dets.firmType', 'emp_dets.empCode',
        'departments.name as deptName', 'designations.name as desName', 
        'contactus_land_pages.branchName')
        ->where('emp_dets.id', $empId)
        ->first();
        $salDet = EmpMr::where('empId', $empDet->empId)->where('forDate', $month)->first();

        $pdf = PDF::loadView('admin.pdfs.salarySlip',compact('salDet','empDet','month'));
        $fileName = "Salary_slip_".date('M-Y', strtotime($month)).".pdf";
        return $pdf->stream($fileName);

        return $pdf = PDF::loadView('admin.pdfs.salarySlip', complact);
        $fileName = "Salary_slip_".date('M-Y', strtotime($month)).".pdf";
        return $pdf->download($fileName);

    }

    public function form16(Request $request)
    {
        $forms = PayrollApplication::where('appType', 2)
        ->where('empId', Auth::user()->empId)
        ->paginate(10);

        return view('admin.empPayroll.form16')->with(['forms'=>$forms]);
    }

    public function raiseReqForm16(Request $request)
    {
        $year = $request->year;
        if($year == '')
            return view('admin.empPayroll.raiseReqForm16');
        else
        {
            $empCode = session()->get('empCode');
            $empDet = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select('emp_dets.name', 'emp_dets.PANNo', 'emp_dets.firmType', 'emp_dets.empCode',
            'departments.name as deptName', 'designations.name as desName', 
            'contactus_land_pages.branchName')
            ->where('empCode', $empCode)
            ->first();
            return view('admin.empPayroll.raiseReqForm16')->with(['empDet'=>$empDet, 'year'=>$year]);
        }
    }

    public function updateRaiseReqForm16(Request $request)
    {
        if(PayrollApplication::where('appType', 2)->where('status', 1)->where('caption', $request->selectedYear)->count())
            return redirect()->back()->withInput()->with("error","Reqeust is already raised. Please TRY another one!!!");;

        $payApp = new PayrollApplication;
        $payApp->empId = Auth::user()->empId;
        $payApp->appType = 2;
        $payApp->forDate = date('Y-m-d');
        $payApp->caption = $request->selectedYear;
        $payApp->status = 1;
        $payApp->updated_by = Auth::user()->username;
        $payApp->save();

        return redirect('/empPayroll/form16')->with("success","Request Raised successfully..");

    }

    public function salaryCertificate(Request $request)
    {
        $salaryCerts = PayrollApplication::where('appType', 3)
        ->where('empId', Auth::user()->empId)
        ->paginate(10);
        return view('admin.empPayroll.salaryCertificate')->with(['salaryCerts'=>$salaryCerts]);
    }

    public function raiseReqSalaryCertificate(Request $request)
    {
        $month = $request->month;
        if($month == '')
            return view('admin.empPayroll.raiseReqSalaryCertificate');
        else
        {
            $empCode = session()->get('empCode');
            $empDet = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select('emp_dets.name', 'emp_dets.firmType', 'emp_dets.empCode',
            'departments.name as deptName', 'designations.name as desName', 
            'contactus_land_pages.branchName')
            ->where('empCode', $empCode)
            ->first();
            return view('admin.empPayroll.raiseReqSalaryCertificate')->with(['empDet'=>$empDet, 'month'=>$month]);
        }
    }

    public function updateRaiseReqSalaryCertificate(Request $request)
    {
        if(PayrollApplication::where('appType', 3)->where('status', 1)->where('caption', $request->selectedMonth)->count())
            return redirect()->back()->withInput()->with("error","Reqeust is already raised. Please TRY another one!!!");;

        $payApp = new PayrollApplication;
        $payApp->empId = Auth::user()->empId;
        $payApp->appType = 3;
        $payApp->forDate = date('Y-m-d');
        $payApp->caption = $request->selectedMonth;
        $payApp->status = 1;
        $payApp->updated_by = Auth::user()->username;
        $payApp->save();

        return redirect('/empPayroll/salaryCertificate')->with("success","Request Raised successfully..");
    }

    public function salaryHoldList(Request $request)
    {
        // $apprisal = Appraisal::where('month', '>=', date('2025-01'))
        // ->where('month', '<=', date('2025-08'))
        // ->get();
        // foreach($apprisal as $app)
        // {
        //     if(date('Y', strtotime($app->month)) == '2025')
        //     {
        //         $tempRS = $app->hikeRs?? $app->hikeRs/2;
        //         $retention = Retention::where('empId', $app->empId)
        //         ->where('month', '2025-07')
        //         ->where('retentionAmount', $tempRS)
        //         ->first();
        //         if(!$retention)
        //             $retention = new Retention;

        //         $retention->empId = $app->empId;
        //         $retention->retentionAmount = $tempRS;
        //         $retention->month = '2025-07';
        //         $retention->remark = 'Retention for the month of Jul 2025';
        //         $retention->updated_by = 'account department';
        //         $retention->updated_at = $app->updated_at;
        //         $retention->save();
        //     }
        // }

        $lists = SalaryHoldRelease::join('emp_dets', 'salary_hold_releases.empId', 'emp_dets.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('emp_dets.id as empId', 'emp_dets.empCode', 'emp_dets.firmType', 'emp_dets.name',
        'emp_dets.phoneNo','emp_dets.jobJoingDate','emp_dets.salaryScale',
        'contactus_land_pages.branchName','emp_dets.contractEndDate', 
        'emp_dets.contractStartDate', 'departments.name as departmentName', 
        'designations.name as designationName','salary_hold_releases.*')
        ->where('salary_hold_releases.active', 1)
        ->get();

        return view('admin.salaryHoldRelease.salaryHoldList', compact('lists'));
    }

    public function salaryHoldDList(Request $request)
    {
        $lists = SalaryHoldRelease::join('emp_dets', 'salary_hold_releases.empId', 'emp_dets.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('emp_dets.id as empId', 'emp_dets.empCode', 'emp_dets.firmType', 'emp_dets.name',
        'emp_dets.phoneNo','emp_dets.jobJoingDate','emp_dets.salaryScale',
        'contactus_land_pages.branchName','emp_dets.contractEndDate', 
        'emp_dets.contractStartDate', 'departments.name as departmentName', 
        'designations.name as designationName','salary_hold_releases.*')
        ->where('salary_hold_releases.active', 0)
        ->get();

        return view('admin.salaryHoldRelease.salaryHoldDList', compact('lists'));
    }

    public function editSalaryHoldDetail($id)
    {
        $holdStatus = SalaryHoldRelease::find($id);
        if($holdStatus)
        {
            $empDet = EmpDet::join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->select('emp_dets.id', 'emp_dets.empCode', 'emp_dets.firmType', 'emp_dets.name',
            'emp_dets.phoneNo','emp_dets.jobJoingDate','emp_dets.salaryScale',
            'contactus_land_pages.branchName','emp_dets.contractEndDate', 
            'emp_dets.contractStartDate', 'departments.name as departmentName', 
            'designations.name as designationName')
            ->where('emp_dets.empCode', $holdStatus->empCode)
            ->first();

            return view('admin.salaryHoldRelease.salaryHold', compact('empDet', 'holdStatus'));
        }
        return redirect()->back()->withInput()->with("error","Invalid Employee Code");
    }

    public function searchSalaryHold(Request $request)
    {
        $empCode = $request->empCode;
        if($empCode == '')
        {
            
            return view('admin.salaryHoldRelease.salaryHold');
        }
        else
        {
            $empDet = EmpDet::join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->select('emp_dets.id', 'emp_dets.empCode', 'emp_dets.firmType', 'emp_dets.name',
            'emp_dets.phoneNo','emp_dets.jobJoingDate','emp_dets.salaryScale',
            'contactus_land_pages.branchName','emp_dets.contractEndDate', 
            'emp_dets.contractStartDate', 'departments.name as departmentName', 
            'designations.name as designationName')
            ->where('emp_dets.active', 1)
            ->where('emp_dets.empCode', $empCode)
            ->first();

            $holdStatus = SalaryHoldRelease::where('empCode', $empCode)->first();

            if(!$empDet)
                return redirect()->back()->withInput()->with("error","Invalid Employee Code");

            return view('admin.salaryHoldRelease.salaryHold', compact('empDet', 'holdStatus'));
        }
    }

    public function updateSalaryStatus(Request $request)
    {
        DB::beginTransaction();
        try {
            $forMonth = $request->forMonth;
            if($request->lastDate)
                EmpDet::where('id', $request->empId)->update(['lastDate'=>$request->lastDate]);

            $salaryHold = SalaryHoldRelease::find($request->holdId);
            if(!$salaryHold)
                $salaryHold = new SalaryHoldRelease;

            $salaryHold->empId = $request->empId;
            $salaryHold->empCode = $request->empCode;
            $salaryHold->status = $request->status;
            $salaryHold->remark = $request->remark;
            $salaryHold->referenceBy = $request->referenceBy;
            $salaryHold->forMonth = $forMonth;
            $salaryHold->userType = Auth::user()->userType;
            $salaryHold->updated_by = Auth::user()->username;
            if($salaryHold->save())
            {
                $summary = MonthlyAttendanceSummary::where('month', $forMonth)->where('empId', $request->empId)->first();
                if($summary)
                {
                    $summary->empId = $request->empId;
                    $summary->month = $forMonth;
                    $summary->salaryStatus = $request->status;
                    $summary->save();
                }

                AttendanceDetail::where('month', date('M', strtotime($forMonth)))
                ->where('year', date('Y', strtotime($forMonth)))
                ->where('empId', $request->empId)
                ->update(['salaryHoldRelease'=>$request->status]);
            }
        
            DB::commit();
            return redirect('/empAttendances/salaryHoldList')->with("success", "Request Updated successfully.");
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Salary Hold Release Error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with("error", "There is some issue...");
        }
    }

    public function activeOrDeactiveSalaryStatus($id)
    {
        DB::beginTransaction();
        try 
        {
            $row = SalaryHoldRelease::find($id);
            if($row->active == 1)
            {
                $row->active=0;
                $row->updated_by=Auth::user()->username;
                if($row->save())
                {
                    AttendanceDetail::where('month', Carbon::parse($row->forMonth)->format('M'))
                    ->where('year', Carbon::parse($row->forMonth)->format('Y'))
                    ->where('empId', $row->empId)
                    ->update(['salaryHoldRelease' => '0']);
                }

            }
            else
            {
                $row->active=1;
                $row->updated_by=Auth::user()->username;
                if($row->save())
                {
                    AttendanceDetail::where('month', Carbon::parse($row->forMonth)->format('M'))
                    ->where('year', Carbon::parse($row->forMonth)->format('Y'))
                    ->where('empId', $row->empId)
                    ->update(['salaryHoldRelease' => '1']);
                }
            }

            DB::commit();

            return redirect('/empAttendances/salaryHoldList')->with("success", "Request Updated successfully.");
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Salary Hold Release Error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with("error", "There is some issue...");
        }
    }

       
    public function getDepartments($section)
    {
        $departments = Department::select('name', 'id')
        ->where('section', $section)
        ->where('active', 1)
        ->orderBy('name', 'asc')
        ->get();
        
        return response()->json($departments);
    }

}