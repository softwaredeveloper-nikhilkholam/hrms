<?php

namespace App\Helpers;

use App\EmpApplication;
use App\AttendanceDetail;
use App\BiometricMachine;
use App\Designation;
use App\EmpDet;
use App\HolidayDept;
use App\HrPolicy;
use App\SalarySheet;
use App\TempEmpDet;
use App\EmployeeLetter;
use App\LogTime;
use App\LogTimeOld;
use App\EmpMr;
use App\StoreQuotOrder;
use App\StoreQuotation;
use App\StoreQuotationPayment;
use App\StoreRequisitionProduct;
use App\StoreRequisition;
use App\StoreProduct;
use App\StoreVendor;
use App\StoreWorkOrderProduct;
use App\StoreWorkOrderPayment;
use App\EmpChangeDay;
use App\EmpDebit;
use App\EmpAdvRs;
use App\User;
use App\StoreProductLedger;
use App\SalaryHoldRelease;
use App\Holiday;
use App\FuelVehicle;
use App\EmpChangeTime;
use App\SignatureFile;
use App\Retention;
use App\InwardProductList;
use App\StoreOutwardProdList;
use App\OutwardProductReturn;
use App\StoreUnit;
use Auth;
use DateTime;
use Carbon\Carbon;

class Utility
{

    public function getAttendanceReport($empId=null, $month, $branchId=null, $section=null)  // 09-07-2025 latest code
    {
        // --- 1. INITIALIZATION AND DATE SETUP ---
        try {
            $carbonDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } catch (\Exception $e) { return false; }

        $startDate = $carbonDate->copy()->format('Y-m-d');
        $endDate = $carbonDate->copy()->endOfMonth()->format('Y-m-d');
        $daysInMonth = $carbonDate->daysInMonth;

        // --- 2. EFFICIENT DATABASE QUERY ---
        $allAttendances = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select(
            'attendance_details.*', 'emp_dets.name', 'emp_dets.empCode', 'emp_dets.jobJoingDate', 
            'emp_dets.lastDate', 'emp_dets.startTime', 'emp_dets.endTime', 'emp_dets.id as attendEmpId',
            'designations.name as designationName', 'contactus_land_pages.branchName','emp_dets.organisation',
            'emp_dets.bankAccountNo', 'emp_dets.bankIFSCCode', 'bankName','emp_dets.salaryScale'
        )
        ->whereBetween('attendance_details.forDate', [$startDate, $endDate]);

        if($branchId != null)
            $allAttendances=$allAttendances->where('emp_dets.branchId', $branchId);

        if($empId != null)
            $allAttendances=$allAttendances->where('emp_dets.id', $empId);

        if($section != null)
            $allAttendances=$allAttendances->when($section, function ($q, $section) { return $q->where('departments.section', $section); });

        $allAttendances=$allAttendances->orderBy('emp_dets.empCode')->orderBy('attendance_details.forDate')
        ->get();

        if ($allAttendances->isEmpty()) { return false; }

        // --- 3. FETCHING ANCILLARY DATA (MANUAL OVERRIDES) ---
        $employeeIds = $allAttendances->pluck('attendEmpId')->unique();
        $dayChanges = EmpChangeDay::where('month', $month)
            ->whereIn('empId', $employeeIds)
            ->get()
            ->keyBy('empId');

        $retentions = Retention::where('month', $month)
            ->whereIn('empId', $employeeIds)
            ->get()
            ->keyBy('empId');


        $deductions = EmpDebit::where('forMonth', $month)
            ->whereIn('empId', $employeeIds)
            ->get()
            ->keyBy('empId');

        
        $salaryHoldRelese = SalaryHoldRelease::where('forMonth', $month)
            ->whereIn('empId', $employeeIds)
            ->get()
            ->keyBy('empId');      

        // --- 4. PROCESS DATA WITH ALL BUSINESS RULES ---
        $attendancesByEmployee = $allAttendances->groupBy('empId');
        $processedEmployees = collect();

          foreach ($attendancesByEmployee as $empId => $employeeDays) {
            $employeeInfo = $employeeDays->first();

             // Fetch all application types for the month and key them for easy lookup.
            $AGFData = EmpApplication::select('status as accountStatus', 'status1 as reportingStatus', 'status2 as hrStatus',
            'approvedBy as accountApprovedBy', 'approvedBy1 as reportingApprovedBy', 'approvedBy2 as hrApprovedBy',
            'updatedAt1', 'updatedAt2', 'updatedAt3', 'startDate', 'reason', 'description')
            ->where('type', 1)->where('empId', $employeeInfo->attendEmpId)
            ->whereBetween('startDate', [$startDate, $endDate])
            ->where('active', 1)
            ->get()->keyBy('startDate');

            $exitPassData = EmpApplication::select('status', 'approvedBy', 'updated_at as updatedAt','startDate', 'reason', 'description')
            ->where('type', 2)->where('empId', $employeeInfo->attendEmpId)
            ->whereBetween('startDate', [$startDate, $endDate])
            ->where('active', 1)
            ->get()->keyBy('startDate');

            // CORRECTED: Leave applications should likely be a distinct type (e.g., 3)
            $leaveData = EmpApplication::select('status', 'approvedBy', 'updated_at as updatedAt', 'startDate', 'endDate', 'reason', 'description')
            ->where('type', 3) // Using type 3 for Leave Applications
            ->where('empId', $employeeInfo->attendEmpId)
            ->where('startDate', '<=', $endDate)
            ->where('endDate', '>=', $startDate)
            ->where('active', 1)
            ->get(); // Get as a collection to iterate over for date range checks
            
            // Parse joining and last dates to handle pre-joining/post-leaving periods.
            $joiningDate = $employeeInfo->jobJoingDate ? Carbon::parse($employeeInfo->jobJoingDate)->startOfDay() : null;
            $lastWorkingDate = $employeeInfo->lastDate ? Carbon::parse($employeeInfo->lastDate)->startOfDay() : null;

            $dailyDataMap = $employeeDays->keyBy(function($day) {
                return Carbon::parse($day->forDate)->format('Y-m-d');
            });           

            $processedDailyStatus = [];
            $sandwitchDayDed = 0;
            $weeklyRuleDeductions = 0.0;

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
                    for ($i = $d + 1; $i <= $daysInMonth; $i++) {
                        $nextDay = $dailyDataMap->get($carbonDate->copy()->day($i)->format('Y-m-d'));
                        if ($nextDay && !in_array($nextDay->dayStatus, ['WO', 'LH'])) { $firstWorkingDayAfter = (object)['status' => $nextDay->dayStatus, 'dayData' => $nextDay]; break; }
                    }
                    if ($firstWorkingDayBefore && $firstWorkingDayAfter &&
                        (in_array($firstWorkingDayBefore->status, ['A', '0']) && $firstWorkingDayBefore->dayData->AGFStatus == 0) &&
                        (in_array($firstWorkingDayAfter->status, ['A', '0']) && $firstWorkingDayAfter->dayData->AGFStatus == 0)) {
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

                $areNext5DaysAbsent = $day2Info && in_array($day2Info->status, ['A', '0']) && $day2Info->dayData->AGFStatus == 0 &&
                                        $day3Info && in_array($day3Info->status, ['A', '0']) && $day3Info->dayData->AGFStatus == 0 &&
                                        $day4Info && in_array($day4Info->status, ['A', '0']) && $day4Info->dayData->AGFStatus == 0 &&
                                        $day5Info && in_array($day5Info->status, ['A', '0']) && $day5Info->dayData->AGFStatus == 0 &&
                                        $day6Info && in_array($day6Info->status, ['A', '0']) && $day6Info->dayData->AGFStatus == 0;

                if ($isDay1Holiday && $areNext5DaysAbsent) {
                    $processedDailyStatus[1]->status = 'A'; 
                    $sandwitchDayDed++;
                }
            }
            // >>> NEW LOGIC END <<<


            // =========================================================================
            // >>> NEW LOGIC START: Mark weekend as absent if Mon-Fri was absent <<<
            // =========================================================================
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $currentDate = $carbonDate->copy()->day($d);

                if ($currentDate->dayOfWeek == Carbon::SATURDAY) {
                    $mondayIndex = $d - 5;
                    $fridayIndex = $d - 1;

                    if ($mondayIndex < 1) {
                        continue; 
                    }

                    $isFullWeekAbsent = true;
                    for ($i = $mondayIndex; $i <= $fridayIndex; $i++) {
                        $dayInfo = $processedDailyStatus[$i] ?? null;

                        if (!$dayInfo || !in_array($dayInfo->status, ['A', '0']) || $dayInfo->dayData->AGFStatus != 0) {
                            $isFullWeekAbsent = false;
                            break;
                        }
                    }

                    if ($isFullWeekAbsent) {
                        $saturdayInfo = $processedDailyStatus[$d] ?? null;
                        if ($saturdayInfo && in_array($saturdayInfo->status, ['WO', 'LH', 'H'])) {
                            $saturdayInfo->status = 'A';
                            $sandwitchDayDed++;
                        }

                        $sundayIndex = $d + 1;
                        if ($sundayIndex <= $daysInMonth) {
                            $sundayInfo = $processedDailyStatus[$sundayIndex] ?? null;
                            if ($sundayInfo && in_array($sundayInfo->status, ['WO', 'LH', 'H'])) {
                                $processedDailyStatus[$sundayIndex]->status = 'A';
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
            $totals = ['present' => 0.0, 'absent' => 0.0, 'weekly_leave' => 0.0, 'extra_work' => 0.0, 'exit_pass_count'=>0.0, 'leave_application_count'=>0.0];
            $lateMarkCount = 0;
            $finalDailyObjects = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                // $statusInfo = $processedDailyStatus[$d] ?? null;
                // if (!$statusInfo) { $finalDailyObjects[$d] = null; continue; }

                $currentDateStr = $carbonDate->copy()->day($d)->format('Y-m-d');
                $statusInfo = $processedDailyStatus[$d] ?? ($dailyDataMap->has($currentDateStr) ? (object)['status' => $dailyDataMap->get($currentDateStr)->dayStatus, 'dayData' => $dailyDataMap->get($currentDateStr)] : null);
         

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

                if (in_array($finalStatus, ['P', 'PL', 'PLH']) && $dayData->inTime && $dayData->outTime && $dayData->officeInTime && $dayData->officeOutTime) {
                    $officeStartTime = Carbon::parse($dayData->forDate . ' ' . $dayData->officeInTime);
                    $officeEndTime = Carbon::parse($dayData->forDate . ' ' . $dayData->officeOutTime);
                    $actualInTime = Carbon::parse($dayData->inTime);
                    $actualOutTime = Carbon::parse($dayData->outTime);
                    $requiredMinutes = $officeEndTime->diffInMinutes($officeStartTime);
                    $requiredHalfDayMinutes = $requiredMinutes / 2;
                    $actualMinutesWorked = $actualOutTime->diffInMinutes($actualInTime);

                    if ($actualMinutesWorked < $requiredHalfDayMinutes && $dayData->AGFStatus == 0) {
                        $finalStatus = 'A';
                    } else {
                        $leftEarly = $actualOutTime->isBefore($officeEndTime->copy()->subMinutes(15));
                        
                        $shiftMidpoint = $officeStartTime->copy()->addMinutes($requiredHalfDayMinutes);
                        $workedInFirstHalf = $actualInTime->lt($shiftMidpoint);
                        $workedInSecondHalf = $actualOutTime->gt($shiftMidpoint);
                        $isHalfDayDueToShiftSpan = !($workedInFirstHalf && $workedInSecondHalf);
                        
                        if ($isHalfDayDueToShiftSpan && $leftEarly && $dayData->AGFStatus == 0) {
                            $finalStatus = 'A';
                        } elseif (($isHalfDayDueToShiftSpan || $leftEarly) && $dayData->AGFStatus == 0) {
                            $finalStatus = 'PH';
                        } else {
                            if ($actualInTime->isAfter($officeStartTime->copy()->addMinutes(7)) && $dayData->AGFStatus == 0) {
                               $isLate = true;
                               if ($finalStatus == 'P') $finalStatus = 'PL';
                            }
                            else
                            {
                                if($dayData->AGFDayStatus == 'Full Day')
                                    $finalStatus = 'P';
                                else
                                    $finalStatus = 'PH';
                            }
                        }
                    }
                }
                else
                {
                    if($finalStatus == 'A' && $dayData->AGFStatus != 0)
                    {
                        if($dayData->AGFDayStatus == 'Full Day')
                            $finalStatus = 'P';
                        else
                            $finalStatus = 'PH';
                    }
                }

                if ($isLate) $lateMarkCount++;
                if (in_array($finalStatus, ['WO', 'LH']) && $dayData->AGFStatus != 0) {
                    $totals['extra_work'] += ($dayData->AGFDayStatus == 'Full Day') ? 1.0 : 0.5;
                }

                if(($dayData->dayStatus == 'PH' || $dayData->dayStatus == 'PLH')  && $dayData->AGFStatus != 0)
                {
                    if($dayData->AGFDayStatus == 'Full Day')
                        $finalStatus = 'P';
                    else
                        $finalStatus = 'PH';
                }

                switch ($finalStatus) {
                    case 'P': case 'PL': $totals['present'] += 1.0; break;
                    case 'A': case '0': $totals['absent'] += 1.0; break;
                    case 'PH':case 'PLH': $totals['present'] += 0.5; $totals['absent'] += 0.5; break;
                    case 'WO': case 'LH': $totals['weekly_leave'] += 1.0; break;
                }

                $agf = $AGFData->get($dayData->forDate);
                $exitPass = $exitPassData->get($dayData->forDate);
                
                // CORRECTED: Find the leave application that covers the current day
                $leave = $leaveData->first(function ($item) use ($currentDateStr) {
                    return $currentDateStr >= $item->startDate && $currentDateStr <= $item->endDate;
                });

                if ($exitPass) $totals['exit_pass_count']++;
                if ($leave) $totals['leave_application_count']++;

                
                $finalDailyObjects[$d] = (object)[
                    'officeInTime' => $dayData->officeInTime,'officeOutTime' => $dayData->officeOutTime,'status' => $finalStatus, 
                    'class' => 'attend-'.$finalStatus, 'forDate' => $dayData->forDate, 'inTime' => $dayData->inTime,
                    'outTime' => $dayData->outTime, 'workingHr' => $dayData->workingHr, 'AGFStatus' => $dayData->AGFStatus,
                    'AGFDetail'=>$agf,'exitPassDetail'=>$exitPass, 'leaveDetail'=>$leave,
                    'repAuthStatus' => $dayData->repAuthStatus, 'HRStatus' => $dayData->HRStatus, 'startTime'=>$dayData->startTime,
                    'endTime'=>$dayData->endTime, 'AGFDayStatus' => $dayData->AGFDayStatus
                ];
            }
            $lateMarkDeduction = floor($lateMarkCount / 3);
            if ($lateMarkDeduction > 0) {
                $totals['present'] -= $lateMarkDeduction;
                $totals['absent'] += $lateMarkDeduction;
            }

            $totals['empCode'] = $employeeInfo->empCode;
            $totals['branchId'] = $employeeInfo->branchId;
            $totals['organisation'] = $employeeInfo->organisation;
            $totals['sandwitch_deductions'] = $sandwitchDayDed;
            $totals['weekly_rule_deductions'] = $weeklyRuleDeductions;
            $totals['total_deductions'] = $lateMarkDeduction + $sandwitchDayDed + $weeklyRuleDeductions;
            $totals['present'] = $totals['present'] + $totals['weekly_leave'];
            $totals['final_total'] = $totals['present'] + $totals['extra_work'];
            $totals['absent'] = $totals['absent'] - $totals['total_deductions'];
            
            $retentionDetail =  $retentions->get($empId);
            $deductionDetail =  $deductions->get($empId);
            $salaryHoldReleaseDetail =  $salaryHoldRelese->get($empId);

            $totals['retention'] = ($retentionDetail)?$retentionDetail->retentionAmount:0;
            $totals['grossSalary']= $employeeInfo->salaryScale;
            $totals['advanceAgainstSalary']= 0;
            $totals['otherDeduction'] = ($deductionDetail)?$deductionDetail->amount:0;
            $totals['bankAccountNo']  = $employeeInfo->bankAccountNo;
            $totals['bankIFSCCode'] = $employeeInfo->bankIFSCCode;
            $totals['bankName'] =$employeeInfo->bankName;
            $totals['salaryStatus'] = ($salaryHoldReleaseDetail)?$salaryHoldReleaseDetail->status:0;
            
            $changeData = $dayChanges->get($empId);
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
        
            $employeeInfo->finalSalaryStatus = $employeeDays->last()->salaryHoldRelease ?? 0;
            $processedEmployees->push(['info' => $employeeInfo, 'days' => $finalDailyObjects, 'totals' => $totals]);

        }

        return [
            'processedEmployees' => $processedEmployees,
            'carbonDate' => $carbonDate,
            'daysInMonth' => $daysInMonth
        ];
        
    }

    public function getFinancialYear($date = null)
    {
        // If no date is provided, use the current date and time
        $carbonDate = $date ? Carbon::parse($date) : Carbon::now();

        // Define the financial year start month (e.g., April = 4)
        $financialYearStartMonth = 4; // April

        $currentMonth = $carbonDate->month;
        $currentYear = $carbonDate->year;

        $startFinancialYear = $currentYear;
        $endFinancialYear = $currentYear + 1;

        // If the current month is BEFORE the financial year start month (e.g., Jan, Feb, Mar if start is April)
        // then the financial year started in the previous calendar year.
        if ($currentMonth < $financialYearStartMonth) {
            $startFinancialYear = $currentYear - 1;
            $endFinancialYear = $currentYear;
        }

        // Format the years to two digits (YY)
        $startYearTwoDigits = substr($startFinancialYear, -2);
        $endYearTwoDigits = substr($endFinancialYear, -2);

        return $startYearTwoDigits . '-' . $endYearTwoDigits;
    }
    
    public function getSignatureDetail($signatureId)
    {
       return SignatureFile::find($signatureId);
    }

    public function setAttendanceTime() // needed
    {
        $changeTimeData = EmpChangeTime::where('status', 0)
        ->take(5)
        ->get();
        foreach($changeTimeData as $changeTime)
        {
            $empId = $changeTime->empId;
            $startDate = $changeTime->startDate;
            $endDate = $changeTime->endDate;
            $inTime = $changeTime->inTime;
            $outTime = $changeTime->outTime;
            $employee = EmpDet::where('id', $empId)->first();
            $fromDay = (int)date('d', strtotime($startDate));
            $toDay = (int)date('d', strtotime($endDate));
            if($employee)
            {
                for($i=$fromDay; $i<=$toDay; $i++)
                {
                    if($i >= 1 && $i <= 9)
                        $k='0'.$i;
                    else
                        $k=$i;

                    $forDate = date('Y-m-', strtotime($startDate)).$k;
                    
                    $attendanceDetail = AttendanceDetail::where('empCode', $employee->empCode)->where('forDate', $forDate)->first();
                    if($attendanceDetail)
                    {
                        $attendanceDetail->inTime = LogTimeOld::where('EmployeeCode', $employee->empCode)
                        ->where('LogDateTime', '>=', ($forDate.' 00:00:00'))
                        ->where('LogDateTime', '<=', ($forDate.' 23:59:59'))
                        ->orderBy('LogDateTime')
                        ->value('LogDateTime');

                        $attendanceDetail->outTime = LogTimeOld::where('EmployeeCode', $employee->empCode)
                        ->where('LogDateTime', '>=', ($forDate.' 00:00:00'))
                        ->where('LogDateTime', '<=', ($forDate.' 23:59:59'))
                        ->orderBy('LogDateTime', 'desc')
                        ->value('LogDateTime');

                        $holidayStatus = 0; // Default to not a holiday

                        $tempHoliday = Holiday::where('active', 1)
                                            ->where('forDate', $forDate)
                                            ->first();

                        if ($tempHoliday) {
                            $allowedBranches = explode(',',$tempHoliday->branchIds);
                            $allowedDesignations = explode(',', $tempHoliday->designationIds);

                            if (in_array($employee->branchId, $allowedBranches) && in_array($employee->designationId, $allowedDesignations)) {
                                $holidayStatus = 1;
                            }
                        }

                        $inTimeSource = $inTime;
                        $outTimeSource = $outTime;
                        
                        $attendanceDetail->officeInTime = date('H:i:s', strtotime($inTimeSource));
                        $attendanceDetail->officeOutTime = date('H:i:s', strtotime($outTimeSource));

                        if ($holidayStatus || $attendanceDetail->dayName == 'Sun') {
                            $attendanceDetail->dayStatus = 'WO';
                        } else {
                            $attendanceDetail->dayStatus = 'A';
                        }
                        
                        $finalLateTime = date('H:i', strtotime('+6 min', strtotime($attendanceDetail->officeInTime)));
                        $firstHalf = date('H:i', strtotime('+1 hour', strtotime($attendanceDetail->officeInTime)));
                        $secondHalf = date('H:i', strtotime('-15 min', strtotime($attendanceDetail->officeOutTime)));
                        
                        $workingHr=0;
                        if($attendanceDetail->inTime != NULL)
                        {                            
                            $logTime = date('H:i', strtotime($attendanceDetail->inTime)); 
                            if($attendanceDetail->dayStatus != "WO")
                            {
                                if(strtotime($logTime) > strtotime($finalLateTime))
                                {
                                    if(strtotime($logTime) >= strtotime($firstHalf))
                                        $attendanceDetail->dayStatus = 'PH'; 
                                    else                                        
                                        $attendanceDetail->dayStatus = 'PL';                                         
                                    
                                }
                                else
                                    $attendanceDetail->dayStatus = 'P'; 
                            } 
                        }

                        if($attendanceDetail->outTime != NULL)
                        {
                            $workingHr = $this->getDiff($attendanceDetail->outTime, $attendanceDetail->inTime);
                            $attendanceDetail->workingHr = $workingHr;
                            if($workingHr != 0)
                            {
                                if(date('H:i', strtotime($attendanceDetail->outTime)) < date('H:i', strtotime($secondHalf)))
                                {   
                                    if($attendanceDetail->dayStatus == 'PL' || $attendanceDetail->dayStatus == 'PLH')
                                        $attendanceDetail->dayStatus = 'PLH'; 
                                    else
                                        $attendanceDetail->dayStatus = 'PH'; 

                                }
                                else
                                {
                                    if($workingHr >= $attendanceDetail->halfDayTime && (strtotime(date('H:i', strtotime($attendanceDetail->outTime))) < strtotime(date('H:i', strtotime($secondHalf)))))
                                    {
                                        if($attendanceDetail->dayStatus == 'P')
                                            $attendanceDetail->dayStatus = 'PH'; 
                                        elseif($attendanceDetail->dayStatus == 'PL' || $attendanceDetail->dayStatus == 'PLH')
                                            $attendanceDetail->dayStatus = 'PLH'; 
                                        else
                                            $attendanceDetail->dayStatus = 'PH'; 

                                    }
                                    else
                                    {
                                        $logTime = date('H:i', strtotime($attendanceDetail->inTime)); 
                                        if(strtotime($logTime) < strtotime($firstHalf))
                                        {   
                                            if($attendanceDetail->dayStatus == 'PH')
                                                $attendanceDetail->dayStatus = 'P'; 
                                            elseif($attendanceDetail->dayStatus == 'PLH')
                                                $attendanceDetail->dayStatus = 'PL'; 
                                            elseif($attendanceDetail->dayStatus == 'A')
                                            {
                                                $inTime = date('H:i', strtotime($attendanceDetail->inTime));
                                                if(strtotime($inTime) >= strtotime($finalLateTime))
                                                {
                                                    if(strtotime($inTime) >= strtotime($firstHalf))
                                                        $attendanceDetail->dayStatus = 'PH'; 
                                                    else
                                                        $attendanceDetail->dayStatus = 'PL'; 
                                                }
                                                else
                                                    $attendanceDetail->dayStatus = 'P';
                                            }
                                        }
                                    }
                                }
                            }
                            else
                            {
                                if($attendanceDetail->dayStatus != 'WO')
                                    $attendanceDetail->dayStatus = 'A';

                            }
                        }
                        $attendanceDetail->save();
                        $prevLog = AttendanceDetail::where('empCode', $attendanceDetail->empCode)
                        ->where('forDate', date('Y-m-d', strtotime('-1 day', strtotime($attendanceDetail->forDate))))
                        ->first();
                        if($prevLog)
                        {
                            if($prevLog->outTime == 0)
                            {
                                if($prevLog->dayStatus == 'P' || $prevLog->dayStatus == 'PL' || $prevLog->dayStatus == 'PH' || $prevLog->dayStatus == 'PLH')
                                    $prevLog->dayStatus = "A"; 
                                
                                $prevLog->save();
                            }
                        }
                    }
                }  
            }
            
            EmpChangeTime::where('id', $changeTime->id)->update(['status' => 1]);
        }
    }

    public function getLeaveApplication($empId, $forDate)
    {
        return EmpApplication::where('empId', $empId)
        ->where('type', 3)
        ->where('active', 1)
        ->value('id');
    }

    public function getFuelEntryList($fuelEntryId)
    {
        return FuelVehicle::join('store_products', 'fuel_vehicles.vehicleId', 'store_products.id')
        ->select('fuel_vehicles.*', 'store_products.name as busNo')
        ->where('fuel_vehicles.fuelEntryId', $fuelEntryId)
        ->get();
    }
    
    public function getCurrentProductStock($productId)
    {
        $product = StoreProduct::find($productId);
        if (!$product || !$product->openingStockForDate) {
            return 0;
        }
    
        $lastDate = now()->toDateString(); // Safe and formatted
    
        $openingStock = $product->openingStock ?? 0;
    
        if ($lastDate >= $product->openingStockForDate) {
            $ledgerQuery = StoreProductLedger::where('productId', $productId)
                ->whereBetween('forDate', [$product->openingStockForDate, $lastDate]);
    
            $inwardQty = (clone $ledgerQuery)->where('type', 1)->sum('inwardQty');
            $outwardQty = (clone $ledgerQuery)->where('type', 2)->sum('outwardQty');
            $returnQty = (clone $ledgerQuery)->where('type', 3)->sum('returnQty');
    
            $closingStock = ($openingStock + $inwardQty + $returnQty) - $outwardQty;
    
            return $closingStock;
        }
    
        return 0;
    }

    public function getSalaryHoldStatus($empId, $forMonth)
    {
        return SalaryHoldRelease::where('empId', $empId)
        ->where('forMonth', date('Y-m', strtotime($forMonth)))
        ->value('status');
    }

    public function updateAGFLast7Days() // 31-03-2025
    {
        $startDate = Carbon::now()->subDays(27)->toDateString();
        $endDate = Carbon::now()->toDateString();

        // Fetch AGF applications
        $agfs = EmpApplication::whereBetween('startDate', [$startDate, $endDate])
            ->where('type', 1)
            ->where('active', 1)
            ->get();

        if ($agfs->isNotEmpty()) {
            foreach ($agfs as $application) {
                $attendanceDetail = AttendanceDetail::where('forDate', $application->startDate)->where('empId', $application->empId)->first();
              
                if($attendanceDetail)
                {
                    $attendanceDetail->repAuthStatus = $application->status1 == 1? $application->id : 0;
                    $attendanceDetail->HRStatus = $application->status2 == 1? $application->id : 0;
                    $attendanceDetail->AGFStatus = $application->status == 1? $application->id : 0;
                    $attendanceDetail->save();
                }
            }
        }
    }

    public function updateMismatchTime() // 31-03-2025
    {
        $updatedRows = AttendanceDetail::whereColumn('inTime', '>', 'outTime')
            ->whereBetween('forDate', [date('Y-m-d', strtotime('-6 days')), date('Y-m-d', strtotime('-2 days'))])
            ->update(['updateAttendStatus' => '0']);

        if ($updatedRows > 0) {
            $attendanceDetails = AttendanceDetail::where('updateAttendStatus', 0)->get();

            foreach ($attendanceDetails as $attendanceDetail) {
                $employee = EmpDet::select('empCode', 'id', 'jobJoingDate', 'departmentId','designationId', 'branchId', 'startTime', 'endTime')
                    ->where('id', $attendanceDetail->empId)
                    ->where('active', 1)
                    ->first();

                if ($employee) {
                    $forDate = $attendanceDetail->forDate;

                    // Optimize LogTimeOld Queries
                    $logTimes = LogTimeOld::where('EmployeeCode', $employee->empCode)
                        ->whereBetween('LogDateTime', [$forDate . ' 00:00:00', $forDate . ' 23:59:59'])
                        ->orderBy('LogDateTime')
                        ->pluck('LogDateTime');

                    $attendanceDetail->inTime = $logTimes->first();
                    $attendanceDetail->outTime = $logTimes->last();

                    // Check holiday
                    $holidayStatus = 0; // Default to not a holiday

                    $tempHoliday = Holiday::where('active', 1)
                                        ->where('forDate', $forDate)
                                        ->first();

                    if ($tempHoliday) {
                        $allowedBranches = explode(', ',$tempHoliday->branchIds);
                        $allowedDesignations = explode(', ', $tempHoliday->designationIds);

                        if (in_array($employee->branchId, $allowedBranches) && in_array($employee->designationId, $allowedDesignations)) {
                            $holidayStatus = 1;
                        }
                    }
                    $attendanceDetail->dayStatus = $holidayStatus ? 'WO' : ($attendanceDetail->dayName == 'Sun' ? 'WO' : 'A');

                    $utility = new Utility();
                    if ($attendanceDetail->inTime) {
                        $logTime = date('H:i', strtotime($attendanceDetail->inTime));
                        $finalLateTime = date('H:i', strtotime('+6 min', strtotime($employee->startTime)));
                        $firstHalf = date('H:i', strtotime('+1 hour', strtotime($employee->startTime)));

                        if (!$holidayStatus && strtotime($logTime) > strtotime($finalLateTime)) {
                            $attendanceDetail->dayStatus = strtotime($logTime) >= strtotime($firstHalf) ? 'PH' : 'PL';
                        } else {
                            $attendanceDetail->dayStatus = 'P';
                        }
                    }

                    if ($attendanceDetail->outTime) {
                        $workingHr = $utility->getDiff($attendanceDetail->outTime, $attendanceDetail->inTime);
                        $attendanceDetail->workingHr = $workingHr;

                        if ($workingHr > 0) {
                            $secondHalf = date('H:i', strtotime('-15 min', strtotime($employee->endTime)));

                            if (strtotime($attendanceDetail->outTime) < strtotime($secondHalf)) {
                                $attendanceDetail->dayStatus = $attendanceDetail->dayStatus == 'PL' ? 'PLH' : 'PH';
                            }
                        } else {
                            if ($attendanceDetail->dayStatus != 'WO') {
                                $attendanceDetail->dayStatus = 'A';
                            }
                        }
                    }

                    $attendanceDetail->updateAttendStatus=1;
                    $attendanceDetail->save();

                    // Check previous day's log
                    $prevLog = AttendanceDetail::where('empCode', $attendanceDetail->empCode)
                        ->where('forDate', date('Y-m-d', strtotime('-1 day', strtotime($attendanceDetail->forDate))))
                        ->first();

                    if ($prevLog && !$prevLog->outTime) {
                        if (in_array($prevLog->dayStatus, ['P', 'PL', 'PH', 'PLH'])) {
                            $prevLog->dayStatus = 'A';
                            $prevLog->save();
                        }
                    }
                }
            }
        }

    }


    public function getAttendance($empCode)
    {
        $month=date('Y-m');
        $days = date('t', strtotime($month));

        // Determine the start date, month, year, and number of days in the month
        $month = date('M', strtotime($month));
        $year = date('Y', strtotime($month));
        
        // Start building the query
        $attendances = AttendanceDetail::join('emp_dets', 'attendance_details.empId', '=', 'emp_dets.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', '=', 'contactus_land_pages.id')
        ->join('departments', 'emp_dets.departmentId', '=', 'departments.id')
        ->join('designations', 'emp_dets.designationId', '=', 'designations.id')
        ->select('attendance_details.*', 'emp_dets.name', 'emp_dets.startTime', 'emp_dets.endTime', 'emp_dets.DOB', 
                'emp_dets.firmType', 'emp_dets.jobJoingDate', 'designations.name as designationName', 'contactus_land_pages.branchName')
        ->where('attendance_details.month', $month)
        ->where('attendance_details.year', $year)
        ->where('attendance_details.day', '<=', $days)
        ->where('emp_dets.empCode', $empCode)
        ->orderBy('attendance_details.forDate')
        ->get();

        $k=0; 
        $tempA=[];
        $tempB=[];
        $tempC=[];
        $tempD=[];
        $attendanceData=[];
        $no=1;

        if(count($attendances))
        {
            foreach($attendances as $key => $attend)
            {
                if($k==0)
                {
                    $deduction=0;$wLeave=0;$sandwitchFlag=0;$tempDayStatus=0;$totDays=$lateMark=$extraW=0;
                }

                $holidayFlag=$jobJoining=0;
                if($attend->jobJoingDate <= $attend->forDate)
                    $jobJoining = 0;
                else
                    $jobJoining = 1;

                if($attend->lastDate != null && $attend->lastDate < $attend->forDate)
                    $lastDay = 1;
                else
                    $lastDay = 0;

                if($attend->inTime)
                    $attend->inTime = date('H:i', strtotime($attend->inTime));
                else
                    $attend->inTime = "";

                if($attend->outTime)
                    $attend->outTime = date('H:i', strtotime($attend->outTime));
                else
                    $attend->outTime = "";

                if($attend->workingHr)
                    $attend->workingHr = round($attend->workingHr, 2);
                else
                    $attend->workingHr = "";

                if($attend->holiday != 0)
                {
                    $prev = $attendances[$key-1];
                    if(($k+1) < $days)
                        $next = $attendances[$key+1];

                    if(isset($next) && $prev)
                    {
                        $i=0;
                        while(isset($next->dayStatus) == 0 && isset($next->holiday) == 0)
                        {
                            $next = $attendances[$key+$i];
                            $i++;
                        }
                    
                        if(isset($next) && isset($prev))
                        {
                        
                            if(($prev->dayStatus == '0' || $prev->dayStatus == 'A') && ($next->dayStatus == '0' || $next->dayStatus == 'A'))
                            {    
                                if($prev->AGFStatus == 0 && $next->AGFStatus == 0 && $deduction >= 4)
                                    $holidayFlag=1;
                                else
                                    $holidayFlag=0;
                                
                            }
                        }
                    }
                }

                if($jobJoining == 0)
                {
                    if($attend->forDate == $attend->jobJoingDate)
                    {
                        $tempA['day'.$k] = "";
                        $tempB['day'.$k] = "New Joinnee";
                        $tempC['day'.$k] = "";
                        $tempD['day'.$k] = "";
                    }

                    if($lastDay == 1)
                    {
                        $tempA['day'.$k] = "";
                        $tempB['day'.$k] = "✗";
                        $tempC['day'.$k] = "";
                        $tempD['day'.$k] = "";
                    }
                    elseif($attend->empCode == '4001' || $attend->empCode == '4002' || $attend->empCode == '4003' || $attend->empCode == '4004' || $attend->empCode == '4005' || $attend->empCode == '4006')
                    {
                        $tempA['day'.$k] = ($attend->dayStatus == 'WO')?'WO':'P';
                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                        $totDays=$totDays+1;
                    }
                    elseif($attend->dayStatus == 'WO' && (isset($attendances[$key-1]) && $attendances[$key-1]->dayStatus == 'A') && (isset($attendances[$key+3]) && $attendances[$key+3]->dayStatus == 'A'))
                    {
                        if($attendances[$key-1]->AGFStatus == 0 || $attendances[$key+3]->AGFStatus == 0)
                        {
                            $tempA['day'.$k] = 'A';
                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                            $deduction=$deduction+1;
                            $wLeave=$wLeave+1;    
                        }
                        else
                        {
                            $tempA['day'.$k] = 'WO';
                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                            $totDays=$totDays+1;
                        }
                        if($attend->repAuthStatus != 0)
                        {
                            if($attend->AGFStatus != 0)
                            {
                                if($attend->AGFDayStatus == 'Full Day')
                                {
                                    $extraW=$extraW+1;
                                }
                                else
                                {
                                    $extraW=$extraW+0.5;
                                }
                            }
                        }                                                              
                    }
                    elseif($attend->dayStatus == 'WO' && (isset($attendances[$key-2]) && $attendances[$key-2]->dayStatus == 'A') && (isset($attendances[$key+2]) && $attendances[$key+2]->dayStatus == 'A'))
                    {
                        if($attendances[$key-2]->AGFStatus == 0 || $attendances[$key+2]->AGFStatus == 0)
                        {
                            $tempA['day'.$k] = 'A';
                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                            $deduction=$deduction+1;
                            $wLeave=$wLeave+1;  
                        }
                        else
                        {
                            $tempA['day'.$k] = 'WO';
                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                            $totDays=$totDays+1;
                        }
                        if($attend->repAuthStatus != 0)
                        {
                            $tempA['day'.$k] = $tempA['day'.$k] . '[ '.($attend->repAuthStatus != 0)?'✓':'✗';
                            $tempA['day'.$k] = $tempA['day'.$k] . ' '.($attend->HRStatus != 0)?'✓':'✗';
                            $tempA['day'.$k] = $tempA['day'.$k] . ' '.($attend->AGFStatus != 0)?'✓':'✗'. ' ]';
                            if($attend->AGFStatus != 0)
                            {
                                if($attend->AGFDayStatus == 'Full Day')
                                {
                                    $extraW=$extraW+1;
                                }
                                else
                                {
                                    $extraW=$extraW+0.5;
                                }
                            }
                        }  
                    }
                    elseif($attend->dayStatus == 'WO' && (isset($attendances[$key-3]) && $attendances[$key-3]->dayStatus == 'A') && (isset($attendances[$key+1]) && $attendances[$key+1]->dayStatus == 'A'))
                    {
                        if($attendances[$key-3]->AGFStatus == 0 || $attendances[$key+1]->AGFStatus == 0)
                        {
                            $tempA['day'.$k] = 'A';
                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                            $deduction=$deduction+1;
                            $wLeave=$wLeave+1;  
                        }
                        else
                        {
                            $tempA['day'.$k] = 'WO';
                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                            $totDays=$totDays+1;
                        }
                        if($attend->repAuthStatus != 0)
                        {
                            if($attend->AGFStatus != 0)
                            {
                                if($attend->AGFDayStatus == 'Full Day')
                                {
                                    $extraW=$extraW+1;
                                }
                                else
                                {
                                    $extraW=$extraW+0.5;
                                }
                            }
                        }     
                    }
                    elseif($attend->dayStatus == 'WO' && isset($attendances[$key+1]) && isset($attendances[$key+2]) && isset($attendances[$key+2]))
                    {
                        if((($attendances[$key+1]->dayStatus == 'A' || $attendances[$key+1]->dayStatus == '0') && $attendances[$key+1]->AGFStatus == 0) && (($attendances[$key-1]->dayStatus == 'A' || $attendances[$key-1]->dayStatus == '0')  && $attendances[$key-1]->AGFStatus == 0))
                        {   
                            $tempA['day'.$k] = 'A';
                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                            $deduction=$deduction+1;
                            $wLeave=$wLeave+1;
                        }
                        else
                        {
                            $tempA['day'.$k] = 'WO';
                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                            $totDays=$totDays+1;
                            if($attend->repAuthStatus != 0)
                            {
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->repAuthStatus != 0)?'✓':'✗';
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->HRStatus != 0)?'✓':'✗';
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->AGFStatus != 0)?'✓':'✗'. ']';
                                if($attend->AGFStatus != 0)
                                {
                                    if($attend->AGFDayStatus == 'Full Day')
                                    {
                                        $extraW=$extraW+1;
                                    }
                                    else
                                    {
                                        $extraW=$extraW+0.5;
                                    }
                                }
                            }     
                        }
                    }
                    elseif($attend->paymentType == 3 && $attend->dayStatus == 'WO')
                    {                                                      
                        if($attend->halfDayTime <= $attend->workingHr)
                        {
                            $tempA['day'.$k] = 'WO';
                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                            $totDays=$totDays+1;
                            if($attend->repAuthStatus != 0)
                            {
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->repAuthStatus != 0)?'✓':'✗';
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->HRStatus != 0)?'✓':'✗';
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->AGFStatus != 0)?'✓':'✗'. ']';
                                if($attend->AGFStatus != 0)
                                {
                                    if($attend->AGFDayStatus == 'Full Day')
                                    {
                                        $extraW=$extraW+1;
                                    }
                                    else
                                    {
                                        $extraW=$extraW+0.5;
                                    }
                                }
                            }     
                        }
                        else
                        {
                            $tempA['day'.$k] = 'A';
                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                            if($attend->repAuthStatus != 0)
                            {
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->repAuthStatus != 0)?'✓':'✗';
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->HRStatus != 0)?'✓':'✗';
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->AGFStatus != 0)?'✓':'✗'. ']';
                                if($attend->AGFStatus != 0)
                                {
                                    if($attend->AGFDayStatus == 'Full Day')
                                    {
                                        $extraW=$extraW+1;
                                    }
                                    else
                                    {
                                        $extraW=$extraW+0.5;
                                    }
                                }
                            }     
                        }
                    }
                    elseif($attend->dayStatus == 'WO' && isset($attendances[$key+1]) && isset($attendances[$key-1]) && $attendances[$key+1]->dayStatus == 'A' && ($attendances[$key-1]->dayStatus == 'A' && $attendances[$key-1]->AGFStatus == 0))
                    {
                        $tempA['day'.$k] = 'A';
                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                        $deduction=$deduction+1;
                        $wLeave=$wLeave+1;
                    }
                    elseif(($attend->dayStatus != 'WO') && ($attend->outTime == NULL || $attend->inTime == $attend->outTime))
                    {
                        $tempA['day'.$k] = 'A';
                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                        if($attend->repAuthStatus != 0)
                        {
                            if($attend->AGFStatus != 0)
                            {
                                if($attend->AGFDayStatus == 'Full Day')
                                {
                                    $totDays=$totDays+1;
                                }
                                else
                                {
                                    $totDays=$totDays+0.5;
                                }
                            }
                            else
                            {
                                $deduction=$deduction+1;
                            }
                        }
                        else
                        {
                            $deduction=$deduction+1;
                        }
                    }
                    elseif($attend->dayStatus == 'WO' && $attend->dayName == 'Sun' && $holidayFlag == 1)
                    {
                        $tempA['day'.$k] = 'A';
                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                        $deduction=0;
                        $wLeave++;
                    }
                    elseif($attend->dayStatus == 'A')
                    {
                        $tempA['day'.$k] = 'A';
                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                        if($attend->repAuthStatus != 0)
                        {
                            if($attend->AGFStatus != 0)
                            {
                                if($attend->AGFDayStatus == 'Full Day')
                                {
                                    $totDays=$totDays+1;
                                }
                                else
                                {
                                    $totDays=$totDays+0.5;
                                }
                            }
                            else
                            {
                                $deduction=$deduction+1;
                            }
                        
                        }
                        else
                        {
                            $deduction=$deduction+1;
                        }
                    }
                    elseif($attend->dayStatus == 'WO' && $attend->dayName == 'Sun') 
                    {
                        if(isset($attendances[$key-1]) && isset($attendances[$key+1]))
                        {
                            if(($attendances[$key-1]->dayStatus == '0' && $attendances[$key+1]->dayStatus == '0') || ($attendances[$key-1]->outTime == NULL && ($attendances[$key+1]->dayStatus == '0' || $attendances[$key+1]->dayStatus == 'A')))
                            {
                                if($deduction == 3 || $deduction == 3.5)
                                {
                                    $tempA['day'.$k] = 'P/2';
                                    $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                    $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                    $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                    $wLeave=$wLeave+0.5;
                                    $totDays=$totDays+0.5;
                                }
                                elseif($deduction >= 4)
                                {
                                    $tempA['day'.$k] = 'A';
                                    $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                    $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                    $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                    $wLeave++; 
                                    $tempDayStatus='A';
                                    $totDays=$totDays+1;
                                }
                                else
                                {
                                    if(($attendances[$key-1]->dayStatus == '0' && $attendances[$key+1]->dayStatus == '0') || ($attendances[$key-1]->outTime == NULL && ($attendances[$key+1]->dayStatus == '0' || $attendances[$key+1]->dayStatus == 'A')))
                                    {
                                        if($attendances[$key+1]->AGFStatus != 0 || $attendances[$key-1]->AGFStatus != 0)
                                        {
                                            $tempA['day'.$k] = 'WO';
                                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                            if($attend->paymentType == 3)
                                            {
                                                $totDays=$totDays+0.5;   
                                            }
                                            elseif($attend->paymentType == 2)
                                            {
                                                $totDays=$totDays+0;  
                                            }
                                            else
                                            { 
                                                $totDays=$totDays+1; 
                                            }
                                        }
                                        else
                                        {
                                            if($attend->dayStatus == 'WO')
                                            {
                                                $tempA['day'.$k] = 'WO';
                                                $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                                $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                                $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                                if($attend->paymentType == 3)
                                                {
                                                    $totDays=$totDays+0.5;
                                                }   
                                                elseif($attend->paymentType == 2)
                                                {
                                                    $totDays=$totDays+0; 
                                                }
                                                else
                                                { 
                                                    $totDays=$totDays+1; 
                                                }
                                            }
                                            else
                                            {
                                                $tempA['day'.$k] = 'A';
                                                $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                                $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                                $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                                $wLeave++;
                                                $sandwitchFlag++;
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $tempA['day'.$k] = 'WO';
                                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                        if($attend->paymentType == 3)
                                        {
                                            $totDays=$totDays+0.5; 
                                        }
                                        elseif($attend->paymentType == 2)
                                        {
                                            $totDays=$totDays+0;  
                                        }
                                        else
                                        {
                                            $totDays=$totDays+1;
                                        }
                                    }
                                }
                                $deduction=0;
                                if($attend->repAuthStatus != 0)
                                {
                                    $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->repAuthStatus != 0)?'✓':'✗';
                                    $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->HRStatus != 0)?'✓':'✗';
                                    $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->AGFStatus != 0)?'✓':'✗'. ']';
                                    if($attend->AGFStatus != 0)
                                    {
                                        if($attend->AGFDayStatus == 'Full Day')
                                        {
                                            $extraW=$extraW+1;
                                        }
                                        else
                                        {
                                            $extraW=$extraW+0.5;
                                        }
                                    }
                                }     
                            }
                            else
                            { 
                                if($deduction == 3 || $deduction == 3.5)
                                {
                                    $tempA['day'.$k] = 'P/2';
                                    $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                    $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                    $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                    $wLeave=$wLeave+0.5;
                                    $totDays=$totDays+0.5;
                                }
                                elseif($deduction >= 4)
                                {
                                    $tempA['day'.$k] = 'A';
                                    $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                    $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                    $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                    $wLeave++; 
                                    $tempDayStatus='A';
                                }
                                else
                                { 
                                    if(($attendances[$key-1]->dayStatus == '0' && $attendances[$key+1]->dayStatus == '0') || ($attendances[$key-1]->outTime == NULL && ($attendances[$key+1]->dayStatus == '0' || $attendances[$key+1]->dayStatus == 'A')))
                                    {    
                                        $tempA['day'.$k] = 'A';
                                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                        $wLeave++;
                                        $sandwitchFlag++;
                                    }
                                    else
                                    { 
                                        $tempA['day'.$k] = 'WO';
                                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                        if($attend->paymentType == 3)
                                        {
                                            $totDays=$totDays+0.5;   
                                        }    
                                        elseif($attend->paymentType == 2)
                                        {
                                            $totDays=$totDays+0;
                                        }
                                        else
                                        { 
                                            $totDays=$totDays+1;
                                        }
                                    }
                                }
                                $deduction=0;
                                if($attend->repAuthStatus != 0)
                                {
                                    $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->repAuthStatus != 0)?'✓':'✗';
                                    $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->HRStatus != 0)?'✓':'✗';
                                    $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->AGFStatus != 0)?'✓':'✗'. ']';
                                    if($attend->AGFStatus != 0)
                                    {
                                        if($attend->AGFDayStatus == 'Full Day')
                                        {
                                            $extraW=$extraW+1;
                                        }
                                        else
                                        {
                                            $extraW=$extraW+0.5;
                                        }
                                    }
                                }     
                            }    
                        }    
                        else
                        {
                            if($deduction == 3 || $deduction == 3.5)
                            {
                                $tempA['day'.$k] = 'P/2';
                                $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                $wLeave=$wLeave+0.5;
                                $totDays=$totDays+0.5;
                            }
                            elseif($deduction >= 4)
                            {
                                $tempA['day'.$k] = 'A';
                                $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                $wLeave++; 
                                $tempDayStatus='A';
                            }
                            else
                            {
                                if(isset($attendances[$key-1]) && isset($attendances[$key+1]))
                                {
                                    if(($attendances[$key-1]->dayStatus == '0' && $attendances[$key+1]->dayStatus == '0') || ($attendances[$key-1]->outTime == NULL && ($attendances[$key+1]->dayStatus == '0' || $attendances[$key+1]->dayStatus == 'A')))
                                    {    
                                        $tempA['day'.$k] = 'WO';
                                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                        $wLeave++;
                                        $sandwitchFlag++;
                                    }
                                    else
                                    {
                                        $tempA['day'.$k] = 'WO';
                                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                        if($attend->paymentType == 3)
                                        {
                                            $totDays=$totDays+0.5;  
                                        } 
                                        elseif($attend->paymentType == 2)
                                        {
                                            $totDays=$totDays+0;  
                                        }
                                        else
                                        { 
                                            $totDays=$totDays+1;
                                        }
                                    }
                                }
                                else
                                {
                                    $tempA['day'.$k] = 'WO';
                                    $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                    $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                    $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;

                                    if($attend->paymentType == 3)
                                    {
                                        $totDays=$totDays+0.5;   
                                    }
                                    elseif($attend->paymentType == 2)
                                    {
                                        $totDays=$totDays+0;
                                    }
                                    else
                                    {
                                        $totDays=$totDays+1;
                                    }
                                }
                            }
                            $deduction=0;
                            if($attend->repAuthStatus != 0)
                            {
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->repAuthStatus != 0)?'✓':'✗';
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->HRStatus != 0)?'✓':'✗';
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->AGFStatus != 0)?'✓':'✗'. ']';
                                if($attend->AGFStatus != 0)
                                {
                                    if($attend->AGFDayStatus == 'Full Day')
                                    {
                                        $extraW=$extraW+1;
                                    }
                                    else
                                    {
                                        $extraW=$extraW+0.5;
                                    }
                                }
                            }     
                        }  
                    }
                    elseif($attend->dayStatus == 'WO')
                    {
                        if($deduction == 5)
                        {
                            if($attend->repAuthStatus != 0)
                            {
                                if($attend->AGFStatus != 0)
                                {
                                    $tempA['day'.$k] = 'WO';
                                    $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                    $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                    $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                }
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->repAuthStatus != 0)?'✓':'✗';
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->HRStatus != 0)?'✓':'✗';
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->AGFStatus != 0)?'✓':'✗'. ']';
                                if($attend->AGFStatus != 0)
                                {
                                    if($attend->AGFDayStatus == 'Full Day')
                                    {
                                        $extraW=$extraW+1;
                                    }
                                    else
                                    {
                                        $extraW=$extraW+0.5;
                                    }
                                }
                            }
                            else
                            {
                                $tempA['day'.$k] = 'A';
                                $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                $wLeave++;
                                $sandwitchFlag++;
                            }
                        }
                        else
                        {
                            if(isset($attendances[$key+1]) && isset($attendances[$key-1]))
                            {
                                if((($attendances[$key+1]->dayStatus == 'A' || $attendances[$key+1]->dayStatus == '0') && $attendances[$key+1]->AGFStatus == 0) && (($attendances[$key-1]->dayStatus == 'A' || $attendances[$key-1]->dayStatus == '0') && $attendances[$key-1]->AGFStatus == 0)){
                                    $tempA['day'.$k] = 'A';
                                    $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                    $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                    $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                    $wLeave++;
                                
                                    if($tempDayStatus == 'A' && $attendances[$key+1]->dayStatus == '0'  && $attend->dayStatus != 'WO'){
                                        $tempA['day'.$k] = 'A';
                                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                        $wLeave++;
                                        $sandwitchFlag++; $tempDayStatus = 0;
                                    }else{
                                        $tempA['day'.$k] = 'WO';
                                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                        if($attend->repAuthStatus != 0)
                                        {
                                            $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->repAuthStatus != 0)?'✓':'✗';
                                            $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->HRStatus != 0)?'✓':'✗';
                                            $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->AGFStatus != 0)?'✓':'✗'. ']';
                                            if($attend->AGFStatus != 0)
                                            {
                                                if($attend->AGFDayStatus == 'Full Day'){
                                                    $extraW=$extraW+1;
                                                }
                                                else{
                                                    $extraW=$extraW+0.5;
                                                }
                                            }
                                        }     
                                        if($attend->paymentType == 3){
                                            $totDays=$totDays+0.5;   
                                        }elseif($attend->paymentType == 2){
                                            $totDays=$totDays+0; 
                                        }else{ 
                                            $totDays=$totDays+1; 
                                        }
                                    }
                                }                                                              
                            }
                            else
                            {
                                $tempA['day'.$k] = 'WO';
                                $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                if($attend->repAuthStatus != 0)
                                {
                                    $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->repAuthStatus != 0)?'✓':'✗';
                                    $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->HRStatus != 0)?'✓':'✗';
                                    $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->AGFStatus != 0)?'✓':'✗'. ']';
                                    if($attend->AGFStatus != 0)
                                    {
                                        if($attend->AGFDayStatus == 'Full Day')
                                        {
                                            $extraW=$extraW+1;
                                        }
                                        else
                                        {
                                            $extraW=$extraW+0.5;
                                        }
                                    }
                                }
                                if($attend->paymentType == 3)
                                {
                                    $totDays=$totDays+0.5;   
                                }
                                elseif($attend->paymentType == 2)
                                {
                                    $totDays=$totDays+0;  
                                }
                                else
                                {
                                    $totDays=$totDays+1;
                                }
                            } 
                        }
                    }
                    elseif($attend->dayStatus == 'P')
                    {
                        $tempA['day'.$k] = 'P';
                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                        $totDays=$totDays+1;
                    }
                    elseif($attend->dayStatus == 'PL')
                    {
                        $tempA['day'.$k] = 'PBL';
                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                        if($attend->repAuthStatus != 0)
                        {
                            if($attend->AGFStatus == 0)
                            {
                                ++$lateMark;
                            }
                        }
                        else
                        {
                            ++$lateMark;
                        }
                        $totDays=$totDays+1;
                    }
                    elseif($attend->dayStatus == 'PLH')
                    {
                        $tempA['day'.$k] = 'P/2';
                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                        $lateMark++;
                        if($attend->repAuthStatus != 0)
                        {
                            if($attend->AGFStatus != 0)
                            {
                                if($attend->AGFDayStatus == 'Full Day')
                                {
                                    $totDays=$totDays+1;
                                }
                                else
                                {
                                    $totDays=$totDays+0.5;
                                }
                            }
                        }
                        else
                        {
                            $totDays=$totDays+0.5;                                                            
                        }
                    }
                    elseif($attend->dayStatus == 'PH')   
                    { 
                        $tempA['day'.$k] = 'P/2';
                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                        if($attend->repAuthStatus != 0)
                        {
                            if($attend->AGFStatus != 0)
                            {
                                if($attend->AGFDayStatus == 'Full Day')
                                {
                                    $totDays=$totDays+1;
                                }
                                else
                                {
                                    $totDays=$totDays+0.5;
                                }
                            }
                            else
                            {
                                $deduction=$deduction+0.5;
                                $totDays=$totDays+0.5;
                            }
                        }
                        else
                        {
                            $totDays=$totDays+0.5;
                            $deduction=$deduction+0.5;
                        }
                    }
                    else
                    {
                        $tempA['day'.$k] = 'A';
                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                        if($attend->repAuthStatus != 0)
                        {
                            if($attend->AGFStatus != 0)
                            {
                                if($attend->AGFDayStatus == 'Full Day')
                                {
                                    $totDays=$totDays+1;
                                }
                                else
                                {
                                    $totDays=$totDays+0.5;
                                }
                            }
                            else
                            {
                                $deduction=$deduction+1;
                            }
                        }
                        else
                        {
                            $deduction=$deduction+1;
                        }
                    }
                    
                    if($attend->dayName == 'Sun')
                    {
                        $deduction = 0;
                    }                                                             
                }
                else
                {
                    $tempA['day'.$k] = 'NA';
                }
                
                $k++;
                if($k == $days)
                {
                    $lateMark=((int)($lateMark/3)); 
                    $tempA['totPresent'] = $totDays-$lateMark;
                    $tempA['totAbsent'] = $days-$totDays-$wLeave;
                    $tempA['totWLeave'] = $wLeave+$lateMark;
                    $tempA['extraWork'] = $extraW;
                    $tempA['total'] = ($totDays-$lateMark)+($extraW);
                    $tempA['status'] = ($attend->salaryHoldRelease == 1)?'Hold':'Release';
                    $k=0;
                }
            }

        }

        return $tempA;
    }

    public function checkHalfDayOrAbsentDay() // 31-03-2025
    {

        $updatedRows = AttendanceDetail::whereBetween('forDate', [
            now()->subDays(10)->format('Y-m-d'),
            now()->subDays(1)->format('Y-m-d')
        ])
        ->whereIn('dayStatus', ['A', '0', 'PH', 'PL', 'PLH'])
        ->update(['updateAttendStatus' => '0']);

        if ($updatedRows > 0) {
            $attendanceDetails = AttendanceDetail::where('updateAttendStatus', 0)->get();

            foreach ($attendanceDetails as $attendanceDetail) {
                $employee = EmpDet::select('empCode', 'id', 'jobJoingDate', 'departmentId', 'designationId', 'branchId', 'startTime', 'endTime')
                    ->where('id', $attendanceDetail->empId)
                    ->where('active', 1)
                    ->first();

                if ($employee) {
                    $forDate = $attendanceDetail->forDate;
                    $employee->startTime = $attendanceDetail->officeInTime;
                    $employee->endTime = $attendanceDetail->officeOutTime;
                    // Optimize LogTimeOld Queries
                    $logTimes = LogTimeOld::where('EmployeeCode', $employee->empCode)
                        ->whereBetween('LogDateTime', [$forDate . ' 00:00:00', $forDate . ' 23:59:59'])
                        ->orderBy('LogDateTime')
                        ->pluck('LogDateTime');

                    $attendanceDetail->inTime = $logTimes->first();
                    $attendanceDetail->outTime = $logTimes->last();

                    // Check holiday
                    $holidayStatus = 0; // Default to not a holiday

                    $tempHoliday = Holiday::where('active', 1)
                                        ->where('forDate', $forDate)
                                        ->first();

                    if ($tempHoliday) {
                        $allowedBranches = explode(', ',$tempHoliday->branchIds);
                        $allowedDesignations = explode(', ', $tempHoliday->designationIds);
                        if (in_array($employee->branchId, $allowedBranches) && in_array($employee->designationId, $allowedDesignations)) {
                            $holidayStatus = 1;
                        }
                    }

                    $attendanceDetail->dayStatus = $holidayStatus ? 'WO' : ($attendanceDetail->dayName == 'Sun' ? 'WO' : 'A');

                    $utility = new Utility();
                    if ($attendanceDetail->inTime) {
                        $logTime = date('H:i', strtotime($attendanceDetail->inTime));
                        $finalLateTime = date('H:i', strtotime('+6 min', strtotime($employee->startTime)));
                        $firstHalf = date('H:i', strtotime('+1 hour', strtotime($employee->startTime)));

                        if (!$holidayStatus && strtotime($logTime) > strtotime($finalLateTime)) {
                            $attendanceDetail->dayStatus = strtotime($logTime) >= strtotime($firstHalf) ? 'PH' : 'PL';
                        } else {
                            $attendanceDetail->dayStatus = 'P';
                        }
                    }

                    if ($attendanceDetail->outTime) {
                        $workingHr = $utility->getDiff($attendanceDetail->outTime, $attendanceDetail->inTime);
                        $attendanceDetail->workingHr = $workingHr;

                        if ($workingHr > 0) {
                            $secondHalf = date('H:i', strtotime('-15 min', strtotime($employee->endTime)));

                            if (strtotime(date('H:i', strtotime($attendanceDetail->outTime))) < strtotime($secondHalf)) {
                                $attendanceDetail->dayStatus = $attendanceDetail->dayStatus == 'PL' ? 'PLH' : 'PH';
                            }
                        } else {
                            if ($attendanceDetail->dayStatus != 'WO') {
                                $attendanceDetail->dayStatus = 'A';
                            }
                        }
                    }

                    $attendanceDetail->updateAttendStatus=1;
                    $attendanceDetail->save();

                    // Check previous day's log
                    $prevLog = AttendanceDetail::where('empCode', $attendanceDetail->empCode)
                        ->where('forDate', date('Y-m-d', strtotime('-1 day', strtotime($attendanceDetail->forDate))))
                        ->first();

                    if ($prevLog && !$prevLog->outTime) {
                        if (in_array($prevLog->dayStatus, ['P', 'PL', 'PH', 'PLH'])) {
                            $prevLog->dayStatus = 'A';
                            $prevLog->save();
                        }
                    }
                }
            }
        }
    }

    public function updateLedger($primaryTransactionId, $transactionId, $forDate, $productId, $qty, $type)
    {
        $ledger = new StoreProductLedger();
        $ledger->primaryTransactionId = $primaryTransactionId;
        $ledger->transactionId = $transactionId;
        $ledger->forDate = $forDate;
        $ledger->productId = $productId;
        if($type == 1)
            $ledger->inwardQty = $qty;
        elseif($type == 2)
            $ledger->outwardQty = $qty;
        else
            $ledger->returnQty = $qty;

        $ledger->type = $type;
        $ledger->status = 1;
        $ledger->updated_by = Auth::user()->username;
        $ledger->save();
    }

    public function getHoldRelease($empId, $forMonth)
    {
        return SalaryHoldRelease::where('empId', $empId)
        ->where('forMonth', '>=', date('Y-m', strtotime($forMonth)))
        ->where('toMonth', '<=', date('Y-m', strtotime($forMonth)))
        ->first();
    }

    public function reportingAuthorityName($reportingId, $reportingType)
    {
        if($reportingType == 2)
            $userDet = User::where('id', $reportingId)->first();
        else
            $userDet = EmpDet::where('id', $reportingId)->first();

       if($userDet)
            return $userDet->name;
        else
            return '-';
    }

    public function getRequisitionRaisedBy($reqNumber)
    {
        return StoreRequisition::join('users', 'store_requisitions.userId', 'users.id')
        ->select('users.name', 'users.username')
        ->where('store_requisitions.requisitionNo', $reqNumber)
        ->first();
    }

    public function getDuty($empId)
    {
        $designationId = EmpDet::where('id', $empId)->value('designationId');
        return Designation::where('id', $designationId)->value('profile');
    }

    public function getVendorDetails($vendorId)
    {
        return StoreVendor::find($vendorId);
    }

    public function getProductDetail($productId)
    {
        $temp = StoreProduct::join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->join('store_units', 'store_products.unitId', 'store_units.id')
        ->join('store_halls', 'store_products.hallId', 'store_halls.id')
        ->join('store_racks', 'store_products.rackId', 'store_racks.id')
        ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
        ->select('store_products.id as productId','store_halls.name as hallName', 'store_racks.name as rackName', 
        'store_products.image', 'store_shels.name as shelfName', 'store_products.stock','store_products.name as productName', 
        'store_categories.name as categoryName', 'store_sub_categories.name as subCategoryName', 'store_products.returnStatus as prodReturn',
        'store_products.productRate','store_units.name as unitName', 'store_products.company', 'store_products.size')
        ->where('store_products.id', $productId)
        ->first();
        $temp['stock'] = $this->getCurrentProductStock($productId);
        return $temp;
    }

    public function getQuotationProductDetail($quotId)
    {
        return StoreQuotOrder::join('store_products', 'store_quot_orders.productId', 'store_products.id')
        ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->join('store_units', 'store_products.unitId', 'store_units.id')
        ->join('store_halls', 'store_products.hallId', 'store_halls.id')
        ->join('store_racks', 'store_products.rackId', 'store_racks.id')
        ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
        ->select('store_quot_orders.*','store_products.id as productId','store_halls.name as hallName', 'store_racks.name as rackName', 
        'store_products.image', 'store_shels.name as shelfName', 'store_products.stock','store_products.name as productName', 
        'store_categories.name as categoryName', 'store_sub_categories.name as subCategoryName', 'store_products.returnStatus as prodReturn',
        'store_products.productRate','store_units.name as unitName', 'store_products.company', 'store_products.size')
        ->where('store_quot_orders.quotationId', $quotId)
        ->get();
        
    }

    public function getWorkOrderList($id)
    {
        return StoreWorkOrderProduct::where('orderId', $id)->get();
    }

    public function getEmpChangeDays($empId, $month)
    {
        return EmpChangeDay::where('empId', $empId)->where('month', $month)->orderBy('id', 'desc')->first();
    }

    public function getQuotProdList($id)
    {
        return StoreQuotOrder::join('store_products', 'store_quot_orders.productId', 'store_products.id')
        ->join('store_units', 'store_products.unitId', 'store_units.id')
        ->select('store_products.company','store_products.size','store_products.color','store_products.productCode', 'store_products.name', 'store_units.name as unitName','store_quot_orders.*')
        ->where('store_quot_orders.quotationId', $id)
        ->get();
    }

    public function getQuotationStatus($commQuotNo)
    {
        return StoreQuotation::where('commQuotNo', $commQuotNo)->where('quotStatus', 'Approved')->count(); 
    }

    public function getQuotationPayment($id)
    {
        return StoreQuotationPayment::where('quotationId', $id)->get();
    }

    public function getWorkOrderPayment($id)
    {
        return StoreWorkOrderPayment::where('orderId', $id)->get();
    }
    
    public function getLastSalary()
    {
        $emps = SalarySheet::whereActive(1)->where('month',  date('Y-m', strtotime('-1 month')))->get();
        $days = date('t', strtotime('-1 month'));
        $totSalary=0;
        if(count($emps))
        {
            foreach($emps as $emp)
            {
                if($emp->grossSalary != 0)
                {
                    $perDay = $emp->grossSalary / $days;
                    $totSalary += $emp->totalDays*$perDay;
                }
            }
        }
                
        return $totSalary;
    }

    public function sendDailyStockReport($forDate)
    {
        $startDate = $forDate . ' 00:00:00';
        $endDate = $forDate . ' 23:59:59';

        // Get all product IDs used on this date from different sources
        $inwardProductIds = InwardProductList::whereBetween('created_at', [$startDate, $endDate])
            ->pluck('productId')->toArray();

        $outwardProductIds = StoreOutwardProdList::whereBetween('created_at', [$startDate, $endDate])
            ->pluck('actualProductId')->toArray();

        $returnProductIds = OutwardProductReturn::whereBetween('created_at', [$startDate, $endDate])
            ->pluck('productId')->toArray();

        // Merge and get unique product IDs
        $uniqueProductIds = array_unique(array_merge($inwardProductIds, $outwardProductIds, $returnProductIds));

        // Get product details
        $products = StoreProduct::whereIn('id', $uniqueProductIds)
            ->orderBy('name')
            ->get();

        $productData = [];
        $lastDate = date('Y-m-d', strtotime('-1 day', strtotime($forDate)));

        foreach ($products as $product) {
            $productId = $product->id;
            $openingStock = 0;
            $currentOpeningStock = 0;
            $closingStock = 0;

            $data = [
                'productName' => $product->name,
                'openingStock' => 0,
                'inwardQty' => 0,
                'outwardQty' => 0,
                'returnQty' => 0,
                'closingStock' => 0,
            ];

            // Calculate opening stock up to the day before $forDate
            if ($lastDate >= $product->openingStockForDate) {
                $openingStock = $product->openingStock;

                $inwardBefore = StoreProductLedger::where('productId', $productId)
                    ->where('created_at', '>=', $product->openingStockForDate)
                    ->where('created_at', '<=', $lastDate)
                    ->where('type', 1)
                    ->sum('inwardQty');

                $outwardBefore = StoreProductLedger::where('productId', $productId)
                    ->where('created_at', '>=', $product->openingStockForDate)
                    ->where('created_at', '<=', $lastDate)
                    ->where('type', 2)
                    ->sum('outwardQty');

                $returnBefore = StoreProductLedger::where('productId', $productId)
                    ->where('created_at', '>=', $product->openingStockForDate)
                    ->where('created_at', '<=', $lastDate)
                    ->where('type', 3)
                    ->sum('returnQty');

                $openingStock = ($openingStock + $inwardBefore + $returnBefore) - $outwardBefore;
            }

            $data['openingStock'] = $openingStock;

            // Transactions on current date
            $inwardQty = StoreProductLedger::where('productId', $productId)
                ->where('forDate', $forDate)
                ->where('type', 1)
                ->sum('inwardQty');

            $outwardQty = StoreProductLedger::where('productId', $productId)
                ->where('forDate', $forDate)
                ->where('type', 2)
                ->sum('outwardQty');

            $returnQty = StoreProductLedger::where('productId', $productId)
                ->where('forDate', $forDate)
                ->where('type', 3)
                ->sum('returnQty');

            $closingStock = ($openingStock + $inwardQty + $returnQty) - $outwardQty;

            $data['inwardQty'] = $inwardQty;
            $data['outwardQty'] = $outwardQty;
            $data['returnQty'] = $returnQty;
            $data['closingStock'] = $closingStock;
            $data['unit'] = StoreUnit::where('id', $product->unitId)->value('name');;

            $productData[] = $data;
        }

        return $productData;
    }

    public function getSalaryAmount($empId)
    {
        return EmpDet::where('id', $empId)->value('salaryScale');
    }

    public function getRetentionAmount($empId)
    {
        return EmpDet::where('id', $empId)->value('retentionAmount');
    }

    public function getRetention($empId, $forMonth)
    {
        return Retention::where('empId', $empId)->where('month', $forMonth)->value('retentionAmount');
    }

    public function getQuotRaisedBy($userId)
    {
        return User::where('id', $userId)->value('name');
    }

    public function getRequisitionProducts($reqId)
    {
        $products = StoreRequisitionProduct::join('store_products', 'store_requisition_products.productId', 'store_products.id')
        ->where('store_requisition_products.requisitionId', $reqId)
        ->get(['store_products.name']);
        $temp='';
        foreach($products as $prod)
        {
            $temp = $temp . $prod->name.', ';
        }
        return $temp;
    }

    public function getExpectedSalary()
    {
        $attendances = AttendanceDetail::join('emp_dets', 'attendance_details.empId','emp_dets.id')
        ->select('attendance_details.dayStatus', 'emp_dets.salaryScale')
        ->where('attendance_details.active',1)
        ->where('attendance_details.forDate',  '>=', date('Y-m-01'))
        ->where('attendance_details.forDate',  '<=', date('Y-m-d'))
        ->whereIn('attendance_details.dayStatus', ['P','PH','WO','WOP','WOPH'])
        ->orderBy('attendance_details.empId')
        ->orderBy('attendance_details.forDate')
        ->get();

        $totExp = 0;
        $days = date('t');
        foreach($attendances as $attend)
        {
            if($attend->salaryScale != 0)
            {
                $perDay = $attend->salaryScale / $days;
                if($attend->dayStatus == 'P')
                    $totExp += $perDay;
                elseif($attend->dayStatus == 'PH')
                    $totExp += $perDay/2;
                elseif($attend->dayStatus == 'WO')
                    $totExp += $perDay;
                elseif($attend->dayStatus == 'WOP')
                    $totExp += $perDay*2;
                elseif($attend->dayStatus == 'WOPH')
                    $totExp += $perDay + $perDay/2;
            }
        }

        return $totExp;
    }

    public function setOfficeTime($empId, $fromTime, $toTime)
    {
        AttendanceDetail::where('empId', $empId)
        ->where('forDate', '>=', $fromTime)
        ->where('forDate', '<=', $toTime)
        ->update(['dayStatus'=>'0', 'inTime'=>'0', 'outTime'=>'0', 'workingHr'=>'0','AGFStatus'=>'0']);

        $temps = AttendanceDetail::where('empId', $empId)
        ->where('month', 'Jan')
        ->where('dayStatus', '!=', 'WO')
        ->orderBy('day')
        ->get();
        foreach($temps as $tp)
        {
            $temp = AttendanceDetail::find($tp->id);
        
            $rule4 = HrPolicy::where('name', 'Rule 4')->where('active', 1)->first();
            if($rule4)
            {    
                $finalLateTime = date('H:i', strtotime('+'.$rule4->temp1.' min', strtotime($fromTime)));
                $firstHalf = date('H:i', strtotime('+'.$rule4->temp7.' hour', strtotime($fromTime)));
                $secondHalf = date('H:i', strtotime('-'.$rule4->temp7.' hour', strtotime($toTime)));
            }

            $temp->dayStatus=0;
    
            if($temp->dayStatus == "WO")
            {
                $temp->inTime = $log->logDateTime; 
                if(strtotime($log->logTime) > strtotime($finalLateTime))
                {  
                    if(strtotime($log->logTime) > strtotime($firstHalf))
                    { 
                        $temp->dayStatus = 'WOPH'; 
                        $temp->extraWorkingDay = 0.5;
                    }
                    else
                    {
                        $temp->dayStatus = 'WOPL'; 
                        $temp->extraWorkingDay = 1;
                        $temp->lateMarkDay = 1;
                    }
                }
                else
                {
                    $temp->dayStatus = 'WOP'; 
                    $temp->extraWorkingDay = 1;
                }
                
            }
            elseif($temp->dayStatus == "0")
            {
                if(strtotime($log->logTime) > strtotime($finalLateTime))
                {
                    if(strtotime($log->logTime) > strtotime($firstHalf))
                    { 
                        $temp->dayStatus = 'PH'; 
                    }
                    else
                    {
                        $temp->dayStatus = 'PL'; 
                        $temp->lateMarkDay = 1;
                    }
                }
                else
                    $temp->dayStatus = 'P'; 
    
                $temp->inTime = $log->logDateTime; 
            }
            elseif($temp->dayStatus == 'WOPH' || $temp->dayStatus == 'WOPLH' || $temp->dayStatus == 'PLH' || $temp->dayStatus == 'WOPH' || $temp->dayStatus == 'PH' || $temp->dayStatus == 'P' || $temp->dayStatus == 'WOP' || $temp->dayStatus == 'PL' || $temp->dayStatus == 'WOPL')
            {
                //$this= new Utility;
                $temp->outTime = $log->logDateTime; 
                if($temp->outTime != "0" && $temp->inTime != "0")
                {
                    $temp->workingHr = $this->timeDiff($temp->outTime, $temp->inTime);
                    if(strtotime($log->logTime) < strtotime($secondHalf))
                    { 
                        if($temp->dayStatus == 'P')
                            $temp->dayStatus = 'PH'; 
                        elseif($temp->dayStatus == 'PL')
                        {
                            $temp->dayStatus = 'PLH'; 
                            $temp->lateMarkDay = 1;
                        }
                        elseif($temp->dayStatus == 'WOP')
                        {
                            $temp->dayStatus = 'WOPH'; 
                            $temp->extraWorkingDay = 0.5;
                        }
                        elseif($temp->dayStatus == 'WOPL')
                        {
                            $temp->dayStatus = 'WOPLH'; 
                            $temp->extraWorkingDay = 1;
                            $temp->lateMarkDay = 1;
                        }
                    }
                    else
                    {
                        if($temp->dayStatus == 'PH')
                            $temp->dayStatus = 'P'; 
                        elseif($temp->dayStatus == 'PLH')
                        {
                            $temp->dayStatus = 'PL'; 
                            $temp->lateMarkDay = 1;
                        }
                        elseif($temp->dayStatus == 'WOPH')
                        {
                            $temp->dayStatus = 'WOP'; 
                            $temp->extraWorkingDay = 1;
                        }
                        elseif($temp->dayStatus == 'WOPLH')
                        {
                            $temp->dayStatus = 'WOPL'; 
                            $temp->extraWorkingDay = 1;     
                            $temp->lateMarkDay = 1;                               
                        }
                    }
                }
            }
    
            $temp->save();
        }
    }

    public function getSalaryStatus($month, $empId)
    {
        return EmpMr::where('forDate', $month)->where('empId', $empId)->first();
    }

    public function updateAttendanceLogs()
    {  
        if(count($tempsDel = TempEmpDet::where('active', 1)->take(60)->get()))
        {
            $this->bioMetricSheet();
        }
        else
        {
            $employees = EmpDet::select('empCode', 'id', 'jobJoingDate', 'departmentId', 'branchId', 'startTime','endTime')
            ->where('active', 1)
            ->where('attendanceStatus', 0)
            ->where('lastDate', NULL)
            ->take(4)
            ->orderBy('empCode')        
            ->get();    

            if(count($employees))
            {
                foreach($employees as $emp)
                {
                    $forDate = date('Y-m-01');
            
                    $days= date('t', strtotime($forDate));
                    $tempDay=0;
                    $to_time = strtotime($emp->startTime);
                    $from_time = strtotime($emp->endTime);
                    $workingHr = round(abs($to_time - $from_time) / 60,2) / 60;
                    
                    $actualTime=$halfDayMinitus=0;
                    $test = (($workingHr*60)/2) / 60;
                    $totalTime = explode('.', $test);
                    if(isset($totalTime[1]))
                    {
                        if($totalTime[1] >= 0 && $totalTime[1] <= 30)
                            $actualTime = $totalTime[0].'.30'; 
                        else
                            $actualTime = ($totalTime[0]+1).'.00';
                    }
                    else
                    {
                        $actualTime = $totalTime[0].'.00';
                    }
        
                    for($i=1; $i<=$days; $i++)
                    {
                        if($i >= 1 && $i <= 9)
                            $tempDay = '0'.$i;
                        else
                            $tempDay = $i;
        
                        $tempDate = date('Y-m', strtotime($forDate)).'-'.$tempDay;

                        $temp = AttendanceDetail::where('empId', $emp->id)
                        ->where('month', date('M', strtotime($forDate)))
                        ->where('year', date('Y', strtotime($forDate)))
                        ->where('day', $tempDay)
                        ->first();

                        if(!$temp)
                            $temp = new AttendanceDetail;

                        $temp->empId = $emp->id;
                        $temp->empCode = $emp->empCode;
                        $temp->day = $tempDay;
                        $temp->month = date('M', strtotime($forDate));
                        $temp->year = date('Y', strtotime($forDate));
                        $temp->forDate = $tempDate;
                        $temp->officeInTime = $emp->startTime;
                        $temp->officeOutTime = $emp->endTime;
                        $temp->halfDayTime = $actualTime;
                        $temp->dayName = date('D', strtotime($temp->forDate));
                        $holidayStatus = HolidayDept::where('empCode', $emp->empCode)->where('active', 1)->where('forDate', $forDate)->count();
                        if($temp->dayName == 'Sun' || $holidayStatus != 0)
                        {
                            $temp->dayStatus = 'WO';
                            $temp->holiday=1;
                        }
        
                        if($temp->save())
                        {
                            $emp->attendanceStatus=1;
                            $emp->save();
                        }
                    }
                }
            }
            else
            {
                $attLogs = LogTime::take(100)
                ->orderBy('EmployeeCode')
                ->orderBy('LogDateTime')
                ->get();

                if(count($attLogs))
                {
                    foreach($attLogs as $log)
                    {    
                        $forDate=date('Y-m-d', strtotime($log->LogDateTime));
                        
                        $temp = AttendanceDetail::where('empCode', $log->EmployeeCode)
                        ->where('forDate', $forDate)
                        ->first();
                        if($temp)
                        {
                            $days= date('t', strtotime($forDate));                      
                        
                            $emp = EmpDet::join('designations', 'emp_dets.designationId', 'designations.id')
                            ->select('emp_dets.empCode', 'emp_dets.id','designations.extraWorkPayment')
                            ->where('emp_dets.empCode', $log->EmployeeCode)
                            ->first();
                            if($emp)
                            {    
                                $finalLateTime = date('H:i', strtotime('+6 min', strtotime($temp->officeInTime)));
                                $firstHalf = date('H:i', strtotime('+1 hour', strtotime($temp->officeInTime)));
                                $secondHalf = date('H:i', strtotime('-15 min', strtotime($temp->officeOutTime)));
                                
                                $workingHr=0;
                                if($temp->inTime == NULL)
                                {      
                                    $logIn = date('Y-m-d H:i:s', strtotime($log->LogDateTime));
                                    $temp->inTime = $logIn; 
                                    $temp->deviceInTime = BiometricMachine::where('serialNo', $log->DeviceSerialNumber)->value('deviceShortName'); 
                                    $logTime = date('H:i', strtotime($logIn)); 
                                    if($temp->dayStatus != "WO")
                                    {
                                        if(strtotime($logTime) >= strtotime($finalLateTime))
                                        {
                                            if(strtotime($logTime) >= strtotime($firstHalf))
                                                $temp->dayStatus = 'PH'; 
                                            else                                        
                                                $temp->dayStatus = 'PL';                                         
                                            
                                        }
                                        else
                                            $temp->dayStatus = 'P'; 
                                    }                            
                                }
                                else
                                {
                                    if(strtotime($temp->inTime) != strtotime($log->LogDateTime))
                                    {
                                        $temp->outTime = date('Y-m-d H:i:s', strtotime($log->LogDateTime));
                                        $temp->deviceOutTime = BiometricMachine::where('serialNo', $log->DeviceSerialNumber)
                                        ->value('deviceShortName'); 
                                        $workingHr = $this->getDiff($temp->outTime, $temp->inTime);
                                        $temp->workingHr = $workingHr;

                                        if($workingHr != 0)
                                        {
                                            if(strtotime(date('H:i', strtotime($temp->outTime))) < strtotime(date('H:i', strtotime($secondHalf))))
                                            {   
                                                if($temp->dayStatus == 'PL' || $temp->dayStatus == 'PLH')
                                                    $temp->dayStatus = 'PLH'; 
                                                else
                                                    $temp->dayStatus = 'PH'; 
                                                
                                            }
                                            else
                                            {
                                                if($workingHr >= $temp->halfDayTime && (strtotime(date('H:i', strtotime($temp->outTime))) < strtotime(date('H:i', strtotime($secondHalf)))))
                                                {
                                                    if($temp->dayStatus == 'P')
                                                        $temp->dayStatus = 'PH'; 
                                                    elseif($temp->dayStatus == 'PL' || $temp->dayStatus == 'PLH')
                                                        $temp->dayStatus = 'PLH'; 
                                                    else
                                                        $temp->dayStatus = 'PH'; 

                                                }
                                                else
                                                {
                                                    $logTime = date('H:i', strtotime($temp->inTime)); 
                                                    if(strtotime($logTime) < strtotime($firstHalf))
                                                    {   
                                                        if($temp->dayStatus == 'PH')
                                                            $temp->dayStatus = 'P'; 
                                                        elseif($temp->dayStatus == 'PLH')
                                                            $temp->dayStatus = 'PL'; 
                                                        elseif($temp->dayStatus == 'A')
                                                        {
                                                            $inTime = date('H:i', strtotime($temp->inTime));
                                                            if(strtotime($inTime) >= strtotime($finalLateTime))
                                                            {
                                                                if(strtotime($inTime) >= strtotime($firstHalf))
                                                                    $temp->dayStatus = 'PH'; 
                                                                else
                                                                    $temp->dayStatus = 'PL'; 
                                                            }
                                                            else
                                                                $temp->dayStatus = 'P';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        else
                                        {
                                            if($temp->dayStatus != 'WO')
                                                $temp->dayStatus = 'A';
                                        }
                                    }

                                }
                                $temp->save();

                                $prevLog = AttendanceDetail::where('empCode', $temp->empCode)
                                ->where('forDate', date('Y-m-d', strtotime('-1 day', strtotime($temp->forDate))))
                                ->first();
                                if($prevLog)
                                {
                                    if($prevLog->outTime == 0)
                                    {
                                        if($prevLog->dayStatus == 'P' || $prevLog->dayStatus == 'PL' || $prevLog->dayStatus == 'PH' || $prevLog->dayStatus == 'PLH')
                                            $prevLog->dayStatus = "A"; 
                                        
                                        $prevLog->save();
                                    }
                                }
                            }
                        }  

                        if(!LogTimeOld::where('EmployeeCode', $log->EmployeeCode)->where('LogDateTime', $log->LogDateTime)->first())
                            LogTimeOld::insert(['EmployeeCode'=>$log->EmployeeCode,'LogDateTime'=>$log->LogDateTime,'DeviceSerialNumber'=>$log->DeviceSerialNumber,'status'=>$log->status,'updated_at'=>date('Y-m-d H:i:s')]);
                        
                        LogTime::where('DeviceLogId', $log->DeviceLogId)->delete();
                    }
                }
            }
        }
    }

    public function employee_calculateAttendanceForBranch($employeeId, $month)
    {
        try {
            $carbonDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } catch (\Exception $e) { return false; }

        $startDate = $carbonDate->copy()->format('Y-m-d');
       // Check if selected month is current month
        if ($carbonDate->isSameMonth(Carbon::now())) {
            $endDate = Carbon::now()->format('Y-m-d'); // Use today's date
        } else {
            $endDate = $carbonDate->copy()->endOfMonth()->format('Y-m-d'); // Use end of month
        }
        
        $daysInMonth = $carbonDate->daysInMonth;

        $allAttendances = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select(
                'attendance_details.*', 'emp_dets.name', 'emp_dets.empCode', 'emp_dets.jobJoingDate', 
                'emp_dets.lastDate', 'emp_dets.startTime', 'emp_dets.endTime', 'emp_dets.id as attendEmpId',
                'emp_dets.branchId', 'emp_dets.bankAccountNo', 'emp_dets.bankIFSCCode', 'emp_dets.bankName', 'emp_dets.salaryScale',
                'designations.name as designationName', 'contactus_land_pages.branchName','emp_dets.organisation'
            )
            ->whereBetween('attendance_details.forDate', [$startDate, $endDate])
            ->where('emp_dets.id', $employeeId)
            ->orderBy('emp_dets.empCode')->orderBy('attendance_details.forDate')
            ->get();

        if ($allAttendances->isEmpty()) { return false; }

        $employeeIds = $allAttendances->pluck('attendEmpId')->unique();
        $dayChanges = EmpChangeDay::where('month', $month)->whereIn('empId', $employeeIds)->get()->keyBy('empId');
        $advances = EmpAdvRs::where('status', 0)->whereIn('empId', $employeeIds)
            ->where('startDate', '<=', $endDate)->where('endDate', '>=', $startDate)
            ->get()->keyBy('empId');

        $debits = EmpDebit::where('status', 0)->whereIn('empId', $employeeIds)
            ->where('forMonth', $month)
            ->get()->keyBy('empId');

        $retentions = Retention::where('status', 0)->whereIn('empId', $employeeIds)
            ->where('month', $month)
            ->get()->keyBy('empId');

        $salaryHoldReleases = SalaryHoldRelease::where('status', 1)->whereIn('empId', $employeeIds)
            ->where('forMonth', $month)
            ->get()->keyBy('empId');

        $attendancesByEmployee = $allAttendances->groupBy('empId');
        $processedEmployees = collect();

        foreach ($attendancesByEmployee as $empId => $employeeDays) {
            $employeeInfo = $employeeDays->first();
            $dailyDataMap = $employeeDays->keyBy(function($day) { return Carbon::parse($day->forDate)->format('Y-m-d'); });
            $processedDailyStatus = [];
            $sandwitchDayDed = 0;
            $weeklyRuleDeductions = 0.0;

            // region Business Rule Processing
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $currentDate = $carbonDate->copy()->day($d);
                $dayData = $dailyDataMap->get($currentDate->format('Y-m-d'));
                if (!$dayData) { $processedDailyStatus[$d] = null; continue; }
                $finalStatus = $dayData->dayStatus;

                if ($d == 1 && in_array($finalStatus, ['WO', 'LH'])) {
                    $presentDaysCount = $employeeDays->filter(function($day) { return in_array($day->dayStatus, ['P', 'PL', 'PH', 'PLH']); })->count();
                    if ($presentDaysCount <= 2) {
                        $finalStatus = 'A';
                        $sandwitchDayDed++;
                    }
                }

                if (in_array($finalStatus, ['WO', 'LH'])) {
                    $firstWorkingDayBefore = null; $firstWorkingDayAfter = null;
                    for ($i = $d - 1; $i >= 1; $i--) {
                        $prevDayStatus = $processedDailyStatus[$i] ?? null;
                        if ($prevDayStatus && !in_array($prevDayStatus->status, ['WO', 'LH'])) { $firstWorkingDayBefore = $prevDayStatus; break; }
                    }
                    for ($i = $d + 1; $i <= $daysInMonth; $i++) {
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

            $weeklyConfig = ['ABSENT' => ['A', '0'], 'HALF_DAY' => 'PH', 'HOLIDAY' => ['H', 'LH'], 'WEEKLY_OFF' => 'WO', 'STANDARD_WORK_DAYS' => 6, 'ABSENT_THRESHOLD_RATIO' => 3.5 / 6, 'HALF_DAY_THRESHOLD_RATIO' => 3.0 / 6];
            for ($d = 1; $d <= $daysInMonth; $d++) {
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
                            elseif ($statusInfo->status == $weeklyConfig['HALF_DAY'] && $statusInfo->dayData->AGFStatus == 0) $weeklyAbsenceCount += 0.5;
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

            $totals = ['present' => 0.0, 'absent' => 0.0, 'weekly_leave' => 0.0, 'extra_work' => 0.0];
            $lateMarkCount = 0;
            $finalDailyObjects = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $statusInfo = $processedDailyStatus[$d] ?? null;
                if (!$statusInfo) { $finalDailyObjects[$d] = null; continue; }
                $finalStatus = $statusInfo->status;
                $dayData = $statusInfo->dayData;
                $isLate = false;
                if (in_array($finalStatus, ['P', 'PL']) && $dayData->inTime && $dayData->outTime && $employeeInfo->startTime && $employeeInfo->endTime && $dayData->AGFStatus == 0) {
                    $officeStartTime = Carbon::parse($dayData->forDate . ' ' . $employeeInfo->startTime);
                    $officeEndTime = Carbon::parse($dayData->forDate . ' ' . $employeeInfo->endTime);
                    $actualInTime = Carbon::parse($dayData->inTime);
                    $actualOutTime = Carbon::parse($dayData->outTime);
                    $requiredMinutes = $officeEndTime->diffInMinutes($officeStartTime);
                    $actualMinutesWorked = $actualOutTime->diffInMinutes($actualInTime);
                    if ($actualMinutesWorked < ($requiredMinutes / 2)) { $finalStatus = 'A';
                    } else {
                        $shiftMidpoint = $officeStartTime->copy()->addMinutes($requiredMinutes / 2);
                        if ($actualInTime->lt($shiftMidpoint) && $actualOutTime->gt($shiftMidpoint)) {
                            if ($actualInTime->isAfter($officeStartTime->copy()->addMinutes(7))) { $isLate = true; if ($finalStatus == 'P') $finalStatus = 'PL'; }
                        } else { $finalStatus = 'PH'; }
                    }
                }
                if ($isLate) $lateMarkCount++;
                if (in_array($finalStatus, ['WO', 'LH']) && $dayData->AGFStatus != 0) { $totals['extra_work'] += ($dayData->AGFDayStatus == 'Full Day') ? 1.0 : 0.5; }
                switch ($finalStatus) {
                    case 'P': case 'PL': $totals['present'] += 1.0; break;
                    case 'A': case '0': $totals['absent'] += 1.0; break;
                    case 'PH': $totals['present'] += 0.5; $totals['absent'] += 0.5; break;
                    case 'WO': case 'LH': $totals['weekly_leave'] += 1.0; break;
                }
                
                $finalDailyObjects[$d] = (object)[ 'status' => $finalStatus, 'inTime' => $dayData->inTime, 'outTime' => $dayData->outTime, 'workingHr' => $dayData->workingHr, /* ... */ ];
            }

            $lateMarkDeduction = floor($lateMarkCount / 3);
            if ($lateMarkDeduction > 0) { $totals['present'] -= $lateMarkDeduction; $totals['absent'] += $lateMarkDeduction; }
            $totals['late_mark_deductions'] = $lateMarkDeduction;
            $totals['sandwitch_deductions'] = $sandwitchDayDed;
            $totals['weekly_rule_deductions'] = $weeklyRuleDeductions;
            $totals['total_deductions'] = $lateMarkDeduction + $sandwitchDayDed + $weeklyRuleDeductions;
            $totals['welfare_leave'] = $lateMarkDeduction + $sandwitchDayDed + $weeklyRuleDeductions;
            $totals['final_total'] = $totals['present'] + $totals['extra_work'] + $totals['weekly_leave'];

            $advance = $advances->get($empId);
            $debit = $debits->get($empId);
            $retention = $retentions->get($empId);
            $salaryHoldRelease = $salaryHoldReleases->get($empId);

            $totals['branchId'] = $employeeInfo->branchId;
            $totals['organisation'] = $employeeInfo->organisation;
            $totals['grossSalary'] = $employeeInfo->salaryScale;
            $totals['advanceAgainstSalary'] = $advance ? $advance->deduction : 0;
            $totals['otherDeduction'] = $debit ? $debit->amount : 0;
            $totals['bankAccountNo'] = $employeeInfo->bankAccountNo;
            $totals['bankIFSCCode'] = $employeeInfo->bankIFSCCode;
            $totals['bankName'] = $employeeInfo->bankName;
            $totals['empCode'] = $employeeInfo->empCode;
            $totals['retention'] = $retention ? $retention->retentionAmount : 0;
            $totals['salaryStatus'] = $salaryHoldRelease ? $salaryHoldRelease->status : 0;

            $changeData = $dayChanges->get($empId);
            $totals['is_edited'] = false;
            if ($changeData) {
                $totals['is_edited'] = true;
                $totals['remark'] = $changeData->remark;
                $totals['new_present'] = $changeData->newPresentDays;
                $totals['new_absent'] = $changeData->newAbsentDays;
                $totals['new_welfare_leave'] = $changeData->newWLDays;
                $totals['new_extra_work'] = $changeData->newExtraDays;
                $totals['new_final_total'] = $changeData->newDays;
            }

            $employeeInfo->finalSalaryStatus = $employeeDays->last()->salaryHoldRelease ?? 0;
            $processedEmployees->push(['info' => $employeeInfo, 'days' => $finalDailyObjects, 'totals' => $totals]);
        }

        return [
            'processedEmployees' => $processedEmployees,
            'carbonDate' => $carbonDate,
            'daysInMonth' => $daysInMonth
        ];
    }


    public function bioMetricSheet()
    {
        $tempsDel = TempEmpDet::where('active', 1)->take(60)->get();
        foreach($tempsDel as $key => $attend)
        {
            if($attend->forMonth != NULL)
            {
                $employee = EmpDet::join('departments', 'emp_dets.departmentId','departments.id')
                ->select('departments.section', 'emp_dets.*')
                ->where('emp_dets.empCode', $attend->employee)
                ->first();

                if($employee)
                {
                    for($i=1; $i<=30; $i++)
                    {
                        if($i >= 1 && $i <= 9)
                            $k='0'.$i;
                        else
                            $k=$i;

                        $day = 'day'.$i;
                        
                        $forMonth = $attend->forMonth;
                        $forDate=date('Y-m-d', strtotime($forMonth.$k));
                        
                        $attendanceDetail = AttendanceDetail::where('empCode', $employee->empCode)->where('forDate', $forDate)->first();
                        if($attendanceDetail)
                        {
                            if($attend->$day != 0 || $attend->$day != '' || $attend->$day != null)
                                $attendanceDetail->inTime = $forDate.' '.$attend->$day.':00';
                            else
                                $attendanceDetail->inTime = NULL;

                            if($tempsDel[$key+1]->$day != 0 || $tempsDel[$key+1]->$day != '' || $tempsDel[$key+1]->$day != null)
                                $attendanceDetail->outTime = $forDate.' '.$tempsDel[$key+1]->$day.':00';
                            else
                                $attendanceDetail->outTime = NULL;
                            
                            $holidayStatus = HolidayDept::where('empCode', $employee->empCode)->where('active', 1)->where('forDate', $forDate)->count();

                            if($holidayStatus)
                                $attendanceDetail->dayStatus = 'WO';
                            else
                                $attendanceDetail->dayStatus = 'A';
                        
                            $finalLateTime = date('H:i', strtotime('+6 min', strtotime($attendanceDetail->officeInTime)));
                            $firstHalf = date('H:i', strtotime('+1 hour', strtotime($attendanceDetail->officeInTime)));
                            $secondHalf = date('H:i', strtotime('-15 min', strtotime($attendanceDetail->officeOutTime)));
                            
                            $workingHr=0;
                            if($attendanceDetail->inTime != NULL)
                            {                            
                                $logTime = date('H:i', strtotime($attendanceDetail->inTime)); 
                                if($attendanceDetail->dayStatus == "WO")
                                    $attendanceDetail->dayStatus = 'WOP';                                     
                                else
                                {
                                    if(strtotime($logTime) >= strtotime($finalLateTime))
                                    {
                                        if(strtotime($logTime) >= strtotime($firstHalf))
                                            $attendanceDetail->dayStatus = 'PH'; 
                                        else                                        
                                            $attendanceDetail->dayStatus = 'PL';                                         
                                        
                                    }
                                    else
                                        $attendanceDetail->dayStatus = 'P'; 
                                }      
                            }

                            if($attendanceDetail->outTime != NULL)
                            {
                                $workingHr = $this->getDiff($attendanceDetail->outTime, $attendanceDetail->inTime);
                                $attendanceDetail->workingHr = $workingHr;

                                if($workingHr != 0)
                                {
                                    if($attendanceDetail->dayStatus == 'WOP')
                                    {
                                        if($workingHr >= 4.30 && $workingHr <= 6)
                                            $attendanceDetail->dayStatus = "WOPH";
                                        elseif($workingHr > 6)
                                            $attendanceDetail->dayStatus = "WOP";
                                        else
                                            $attendanceDetail->dayStatus = "WO";

                                    }
                                    else
                                    {
                                        if(strtotime(date('H:i', strtotime($attendanceDetail->outTime))) < strtotime(date('H:i', strtotime($secondHalf))))
                                        {   
                                            if($attendanceDetail->dayStatus == 'PL' || $attendanceDetail->dayStatus == 'PLH')
                                                $attendanceDetail->dayStatus = 'PLH'; 
                                            else
                                                $attendanceDetail->dayStatus = 'PH'; 
                                            
                                        }
                                        else
                                        {
                                            if($workingHr >= $attendanceDetail->halfDayTime && (strtotime(date('H:i', strtotime($attendanceDetail->outTime))) < strtotime(date('H:i', strtotime($secondHalf)))))
                                            {
                                                if($attendanceDetail->dayStatus == 'P')
                                                    $attendanceDetail->dayStatus = 'PH'; 
                                                elseif($attendanceDetail->dayStatus == 'PL' || $attendanceDetail->dayStatus == 'PLH')
                                                    $attendanceDetail->dayStatus = 'PLH'; 
                                                else
                                                    $attendanceDetail->dayStatus = 'PH'; 

                                            }
                                            else
                                            {
                                                $logTime = date('H:i', strtotime($attendanceDetail->inTime)); 
                                                if(strtotime($logTime) < strtotime($firstHalf))
                                                {   
                                                    if($attendanceDetail->dayStatus == 'PH')
                                                        $attendanceDetail->dayStatus = 'P'; 
                                                    elseif($attendanceDetail->dayStatus == 'PLH')
                                                        $attendanceDetail->dayStatus = 'PL'; 
                                                    elseif($attendanceDetail->dayStatus == 'A')
                                                    {
                                                        $inTime = date('H:i', strtotime($attendanceDetail->inTime));
                                                        if(strtotime($inTime) >= strtotime($finalLateTime))
                                                        {
                                                            if(strtotime($inTime) >= strtotime($firstHalf))
                                                                $attendanceDetail->dayStatus = 'PH'; 
                                                            else
                                                                $attendanceDetail->dayStatus = 'PL'; 
                                                        }
                                                        else
                                                            $attendanceDetail->dayStatus = 'P';
                                                    }
                                                }
                                            }
                                        }

                                    }

                                }
                                else
                                {
                                    if($attendanceDetail->dayStatus == 'WOP')
                                        $attendanceDetail->dayStatus = 'WO';
                                    else
                                        $attendanceDetail->dayStatus = 'A';

                                }
                            }

                            $attendanceDetail->save();

                            $prevLog = AttendanceDetail::where('empCode', $attendanceDetail->empCode)
                            ->where('forDate', date('Y-m-d', strtotime('-1 day', strtotime($attendanceDetail->forDate))))
                            ->first();
                            if($prevLog)
                            {
                                if($prevLog->outTime == 0)
                                {
                                    if($prevLog->dayStatus == 'P' || $prevLog->dayStatus == 'PL' || $prevLog->dayStatus == 'PH' || $prevLog->dayStatus == 'PLH')
                                        $prevLog->dayStatus = "A"; 
                                    
                                    if($prevLog->dayStatus == 'WOP' || $prevLog->dayStatus == 'WOPH')
                                        $prevLog->dayStatus = "WO"; 
                                    
                                    $prevLog->save();
                                }
                            }
                        }

                        if($attend->$day != 0 || $attend->$day != '' || $attend->$day != null)
                        {
                            $forInTime = date('Y-m-d H:i:s', strtotime($forDate.' '.$attend->$day));
                            if(!LogTimeOld::where('EmployeeCode', $employee->empCode)->where('LogDateTime', $forInTime)->first())
                            {
                                $logTimeIn = new LogTimeOld;  
                                $logTimeIn->EmployeeCode = $employee->empCode;
                                $logTimeIn->LogDateTime = $forInTime;
                                $logTimeIn->DeviceSerialNumber = 'Bio-metric';
                                $logTimeIn->save();
                            }
                        }

                        if($tempsDel[$key+1]->$day != 0 || $tempsDel[$key+1]->$day != '' || $tempsDel[$key+1]->$day != null)
                        {
                            $forOutTime = date('Y-m-d H:i:s', strtotime($forDate.' '.$tempsDel[$key+1]->$day));
                            if(!LogTimeOld::where('EmployeeCode', $employee->empCode)->where('LogDateTime', $forOutTime)->first())
                            {    
                                $logTimeOut = new LogTimeOld;   
                                $logTimeOut->EmployeeCode = $employee->empCode;
                                $logTimeOut->LogDateTime = $forOutTime;
                                $logTimeOut->DeviceSerialNumber = 'Bio-metric';
                                $logTimeOut->save();
                            }
                        }
                    }                
                }

                TempEmpDet::where('id', $attend->id)->update(['active'=>0]);
            }
        }
    }

    public function getAGFDetail($agfStatus)
    {
        return EmpApplication::find($agfStatus);
    }

    public function getLastDayStatus($empId, $forDate)
    {
        return AttendanceDetail::where('empId', $empId)
        ->where('forDate', date('Y-m-d', strtotime('-1 day', strtotime($forDate))))
        ->first();
    }

    public function getMonthlyEmpAttendance($empCode, $month)
    {
        if($month == date('M-Y'))
        {
            $totD = date('d', strtotime('-1 day'));
            $firstDate = date('Y-m-01');
            $lastDate = date('Y-m-d', strtotime('-1 day'));
        }
        else
        {
            $totD = date('t', strtotime($month));
            $firstDate = date('Y-m-01', strtotime($month));
            $lastDate = date('Y-m-t', strtotime($month));
        }

        $attendances = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->select('attendance_details.*', 'emp_dets.name','emp_dets.startTime','emp_dets.endTime', 
        'emp_dets.firmType','emp_dets.jobJoingDate')
        ->where('attendance_details.month', date('M', strtotime($month)))
        ->where('attendance_details.year', date('Y', strtotime($month)))
        ->where('emp_dets.empCode', $empCode)
        ->where('emp_dets.active', 1)
        ->take($totD)
        ->get();

        $k=0;$no=1;
        $totDays=$weekoff=$lateMark=$extraW=$sandTp=$wfh=$sandwichPol=0;
        foreach($attendances as $attend)
        {
            if(($attend->dayStatus == 'PL' || $attend->dayStatus == 'PLH') && $attend->dayStatus == '0')
            {
                if($attend->AGFStatus == 0)
                {
                    $lateMark=$lateMark+1; 
                    if($lateMark == 3)
                    {
                        $totDays=$totDays-1;
                        $lateMark=0;
                    }
                }
            }

            if($attend->jobJoingDate <= $attend->forDate)
            {
                if($attend->dayName == 'Mon' || $attend->dayName == 'Tue' || $attend->dayName == 'Wed' || $attend->dayName == 'Thu'  || $attend->dayName == 'Fri'  || $attend->dayName == 'Sat')
                {
                    if($attend->AGFStatus == 0 && ($attend->dayStatus == '0' || $attend->dayStatus == 'A'))
                    {
                        $sandwichPol++;
                    }
                }
            }
            
            if($attend->dayStatus == 'A')
            {
                if($attend->holiday == 1 && $attend->AGFStatus != 0)
                {
                        if($attend->paymentType == 1)
                        {
                                $totDays=$totDays+1;
                                if($attend->extraWorkAllowed == 0)
                                    $extraW=$extraW+1; 
                        }
                        elseif($attend->paymentType == 2)
                        {
                                $totDays=$totDays+1;
                        }
                        else
                        {
                            $totDays=$totDays+1;
                        }
                }
                else
                {
                    if($attend->AGFStatus != '0')
                    {
                        $totDays=$totDays+1;
                    }
                    else
                    {
                        $sandTp=1; 
                        if(($attend->month == 'Aug' && $attend->month == '15') && $attend->indDayStatus == 0)
                            $totDays=$totDays-3;

                        if(($attend->month == 'Jan' && $attend->month == '26') && $attend->repubDayStatus == 0)
                            $totDays=$totDays-3;
                    }
                }
            }
            elseif($attend->dayStatus == 'WO')
            {
                
                if($attend->AGFStatus != 0)
                {
                    if($attend->paymentType == 1)
                    {
                        $totDays=$totDays+1;
                        if($attend->extraWorkAllowed == 0)
                            $extraW=$extraW+1; 
                    }
                    elseif($attend->paymentType == 2)
                    {
                            $totDays=$totDays+1;
                    }
                    else
                    {
                        $totDays=$totDays+1; 
                    }
                }  
                else
                {
                    if($attend->dayName == 'Sun')
                    {
                        if($sandTp >= 1)
                        {
                            $sandTp++;
                        }
                    
                        if($sandwichPol == 3)
                        {
                            $totDays=$totDays+0.5;
                        }
                        elseif($sandwichPol > 3)
                        {
                            
                        }
                        elseif($sandwichPol <= 2)
                        {
                            if($attend->paymentType == 1)
                            {
                                $weekoff=$weekoff+1;
                            }
                            elseif($attend->paymentType == 2)
                                $weekoff=$weekoff+0;
                            else
                                $weekoff=$weekoff+0.5; 
                            
                        }
                        else
                        {
                            $weekoff=$weekoff+1;
                        }
                    }
                    else
                    { 
                        if($attend->paymentType == 1)
                            $weekoff=$weekoff+1;
                        elseif($attend->paymentType == 2)
                            $weekoff=$weekoff+0;
                        else
                            $weekoff=$weekoff+0.5;
                        
                    }
                }
            }
            elseif($attend->dayStatus == 'WOP')
            {
                
                if($attend->paymentType == 1)
                {
                    $totDays=$totDays+1;
                    if($attend->extraWorkAllowed == 0)
                        $extraW=$extraW+1; 
                }
                elseif($attend->paymentType == 2)
                    $weekoff=$weekoff+1;
                else
                    $weekoff=$weekoff+1;
                    
            }
            elseif($attend->dayStatus == 'WOPH')
            {
                
                if($attend->paymentType == 1)
                {
                    if($attend->extraWorkAllowed == 0)
                    {
                        if($attend->AGFStatus != '0' || $attend->AGFStatus != 0)
                            $extraW=$extraW+1; 
                        else
                            $extraW=$extraW+0.5;
                        
                    }
                    $weekoff=$weekoff+1;
                }
                elseif($attend->paymentType == 2)
                {
                    if($attend->extraWorkAllowed == 0)
                    {
                        if($attend->AGFStatus != '0' || $attend->AGFStatus != 0)
                            $extraW=$extraW+1;
                        else
                            $extraW=$extraW+0.5;
                        
                    }
                }
                else
                {
                    if($attend->AGFStatus != '0' || $attend->AGFStatus != 0)
                        $weekoff=$weekoff+1;
                    else
                        $weekoff=$weekoff+0.5;
                    
                }
            }
            elseif($attend->dayStatus == 'P')
            {
                if($attend->outTime == 0 && $attend->AGFStatus == '0')
                {
                    if($attend->AGFStatus != '0')
                       $totDays=$totDays+1;
                    else
                        $sandTp=1; 
                    
                }
                else
                {
                    $totDays=$totDays+1;
                }
            }
            elseif($attend->dayStatus == 'PL')
            {
                if($attend->lateMarkDay == 0)
                {
                    if($attend->outTime == 0 && $attend->AGFStatus == '0')
                    {
                        if($attend->AGFStatus != '0')
                            $totDays=$totDays+1;
                        else
                            $sandTp=1;

                    }
                    else
                    {
                        $totDays=$totDays+1;
                    }
                }
                else
                    $totDays=$totDays+1; 
            }
            elseif($attend->dayStatus == 'PLH')
            {
                if($attend->lateMarkDay == 0)
                {
                    if($attend->outTime == 0 && $attend->AGFStatus == '0')
                    {
                        if($attend->AGFStatus != '0')
                             $totDays=$totDays+1; 
                        else
                            $sandTp=1; 
                        
                    }
                    else
                    {
                        if($attend->AGFStatus != '0' || $attend->AGFStatus != 0)
                            $totDays=$totDays+1;
                        else
                            $totDays=$totDays+0.5;
                            
                    }
                }
                else
                {
                    if($attend->AGFStatus != '0' || $attend->AGFStatus != 0)
                        $totDays=$totDays+1;
                    else
                    {
                        $totDays=$totDays+0.5; 
                        $sandwichPol=$sandwichPol+0.5;
                    }
                }
            }
            elseif($attend->dayStatus == 'PH')
            {
                if($attend->outTime == 0 && $attend->AGFStatus == '0')
                {
                    if($attend->AGFStatus != '0')
                        $totDays=$totDays+1;
                    else
                        $sandTp=1; $sandwichPol++;
                }
                else
                {
                    if($attend->AGFStatus != '0' || $attend->AGFStatus != 0)
                        $totDays=$totDays+1;
                    else
                        $totDays=$totDays+0.5; $sandwichPol=$sandwichPol+0.5;
                }
            }
            else
            {
                if($attend->AGFStatus != '0')
                    $totDays=$totDays+1;
                else
                    $sandTp=1;
            }
            if($attend->dayName == 'Sun')
            {
                $sandwichPol=0;
            }
        }
        
        $presentDays = $totDays;
        $absentDays = $totD - $totDays - $weekoff;
        $extraWo = $extraW;
        return [$totD, $presentDays, $absentDays, $extraWo, $weekoff];
    }

    public function calculateExperience($startDate)
    {
        $datetime1=new DateTime($startDate);
        $datetime2=new DateTime(date('Y-m-d'));
        $interval=$datetime1->diff($datetime2);
        return $interval->format('%y.%m Years');
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

    public function getLastDate($empCode, $forDate, $days)
    {
        $forDate = date('Y-m-d', strtotime('-'.$days.' day', strtotime($forDate)));
        return AttendanceDetail::where('empCode', $empCode)->where('forDate', $forDate)
        ->where(function($query)
        {
            $query->Where('dayStatus', '0')
            ->orWhere('dayStatus', 'A');

        })->value('dayStatus');
    }

    public function updateAGF()
    {
        $application = EmpApplication::where('type', 1)
        ->where('status', 1)
        ->where('startDate', '>=', date('Y-m-01'))
        ->where('startDate', '<=', date('Y-m-t'))
        ->get();
        foreach($application as $app)
        {
            $temp = AttendanceDetail::where('empId', $app->empId)
            ->where('forDate', $app->startDate)
            ->first();
            if($temp)
            {
                $temp->AGFStatus=$app->id;
                $temp->save();
            }
        }
    }

    public function getVerion()
    {
        return '1.0';
    }

    public function numberFormatRound($num)
    {
        $explrestunits = "" ;
        $num=preg_replace('/,+/', '', $num);
        $words = explode(".", $num);
        $dec="00";
        if(count($words)<=2){
            $num=$words[0];
            if(count($words)>=2){$dec=$words[1];}
            if(strlen($dec)<2){$dec="$dec"."0";}else{$dec=substr($dec, 0, 2);}
        }
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){
                // creates each of the 2's group and adds a comma to the end
                if($i==0)
                {
                    if($expunit[$i]=='0-'){
                        $explrestunits .= '-';
                    }else{
                        $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
                    }
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }
        return "$thecash"; 
    }

    public function numberFormatDec($num) 
    {
        $explrestunits = "" ;
        $num=preg_replace('/,+/', '', $num);
        $words = explode(".", $num);
        $dec="000";
        if(count($words)<=3){
            $num=$words[0];
            if(count($words)>=2){$dec=$words[1];}
            if(strlen($dec)<3){$dec="$dec"."0";}else{$dec=substr($dec, 0, 3);}
        }
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){
                // creates each of the 2's group and adds a comma to the end
                if($i==0) 
                {
                    if($expunit[$i]=='0-'){
                        $explrestunits .= '-';
                    }else{
                        $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
                    }
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }
        return "$thecash.$dec"; 
    }

    public function numberFormat($num) 
    {
        $explrestunits = "" ;
        $num=preg_replace('/,+/', '', $num);
        $words = explode(".", $num);
        $dec="00";
        if(count($words)<=2){
            $num=$words[0];
            if(count($words)>=2){$dec=$words[1];}
            if(strlen($dec)<2){$dec="$dec"."0";}else{$dec=substr($dec, 0, 2);}
        }
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){
                // creates each of the 2's group and adds a comma to the end
                if($i==0) 
                {
                    if($expunit[$i]=='0-'){
                        $explrestunits .= '-';
                    }else{
                        $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
                    }
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }
        return "$thecash.$dec"; 
    }

    public function numberToWord($num)
    {
        $number = $num;
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'One', '2' => 'Two',
            '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
            '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
            '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
            '13' => 'Thirteen', '14' => 'Fourteen',
            '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
            '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
            '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
            '60' => 'Sixty', '70' => 'Seventy',
            '80' => 'Eighty', '90' => 'Ninety');
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? '' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred
                    :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
            "." . $words[$point / 10] . " " . 
                $words[$point = $point % 10] : '';
         return $result;
    }

    public function dateFormat($date,$time)
    {
        switch($time){
            case '1':
                return date('d M Y',strtotime($date));
            break;
            case '2':
                return date('d M Y, h:i A',strtotime($date));
            break;
        } 
    } 

    public function getLastAppointmentLetter($empId)
    {
        return EmployeeLetter::where('letterType', 2)
        ->where('empId', $empId)
        ->where('active', 1)
        ->orderBy('id', 'desc')
        ->value('id');
    }
    
    public function getNotifications()
    {
        $userType = Auth::user()->userType;
        $empId = Auth::user()->empId;
        if($empId != 0)
        {
            if($userType == '11')
            {
                $repIds1 = EmpDet::where('reportingId', $empId)->where('active', 1)->pluck('id');
                $repIds2 = EmpDet::whereIn('reportingId', $repIds1)->where('active', 1)->pluck('id');

                $collection = collect($repIds1);
                $merged = $collection->merge($repIds2);
                $reportyId = $merged->all();

                return $applications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
                ->select('emp_applications.id','emp_dets.name','emp_dets.id as empId', 'emp_dets.empCode',
                'emp_applications.type', 'emp_applications.startDate','emp_applications.created_at', 'emp_dets.firmType')
                ->where('emp_applications.active', 1)
                ->where('emp_dets.active', 1)
                ->whereIn('emp_dets.reportingId', $reportyId)
                ->where('emp_applications.type', 1)
                ->where('emp_applications.status1', 0)
                ->where('emp_applications.created_at', '>=', date('2023-06-01 00:00:00'))
                ->orderBy('emp_applications.created_at', 'desc')
                ->get();

                // $applications2 = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
                // ->select('emp_applications.id','emp_dets.name','emp_dets.id as empId', 'emp_dets.empCode',
                // 'emp_applications.type', 'emp_applications.startDate','emp_applications.created_at', 'emp_dets.firmType')
                // ->where('emp_dets.active', 1)
                // ->where('emp_applications.status', 0)
                // ->whereIn('emp_applications.type', [2, 3])
                // ->whereIn('emp_dets.reportingId', $reportyId)
                // ->where('emp_applications.created_at', '>=', date('2022-06-01 00:00:00'))
                // ->orderBy('emp_applications.created_at', 'desc')
                // ->get();

                // $temp1 = collect($applications1);
                // $temp2 = collect($applications2);
                // return $applications =$temp1->merge($temp2);
   
            }

            if($userType == '21')
            {
               return $applications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
                ->select('emp_applications.id','emp_dets.name','emp_dets.id as empId', 'emp_dets.empCode',
                'emp_applications.type', 'emp_applications.startDate','emp_applications.created_at', 'emp_dets.firmType')
                ->where('emp_applications.active', 1)
                ->where('emp_dets.active', 1)
                ->where('emp_dets.reportingId', $empId)
                ->where('emp_applications.type', 1)
                ->where('emp_applications.status1', 0)
                ->where('emp_applications.created_at', '>=', date('2023-06-01 00:00:00'))
                ->orderBy('emp_applications.created_at', 'desc')
                ->get();

                // $applications2 = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
                // ->select('emp_applications.id','emp_dets.name','emp_dets.id as empId', 'emp_dets.empCode',
                // 'emp_applications.type', 'emp_applications.startDate','emp_applications.created_at', 'emp_dets.firmType')
                // ->where('emp_dets.active', 1)
                // ->where('emp_applications.status', 0)
                // ->whereIn('emp_applications.type', [2, 3])
                // ->where('emp_dets.reportingId', $empId)
                // ->where('emp_applications.created_at', '>=', date('2022-06-01 00:00:00'))
                // ->orderBy('emp_applications.created_at', 'desc')
                // ->get();

                // $temp1 = collect($applications1);
                // $temp2 = collect($applications2);
                // return $applications =$temp1->merge($temp2);
            }

            if($userType == '31')
            {
                return [];
            }
        }
        elseif($userType == '51')
        {
           return $applications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
            ->select('emp_applications.id','emp_dets.name','emp_dets.id as empId', 'emp_dets.empCode',
            'emp_applications.type', 'emp_applications.startDate','emp_applications.created_at', 'emp_dets.firmType')
            ->where('emp_applications.active', 1)
            ->where('emp_dets.active', 1)
            ->where('emp_applications.type', 1)
            ->where('emp_applications.status1', 1)
            ->where('emp_applications.status2', 0)
            ->where('emp_applications.startDate', '>=', date('2023-06-01'))
            ->orderBy('emp_applications.created_at', 'desc')
            ->get();

            // $applications2 = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
            // ->select('emp_applications.id','emp_dets.name','emp_dets.id as empId', 'emp_dets.empCode',
            // 'emp_applications.type', 'emp_applications.startDate','emp_applications.created_at', 'emp_dets.firmType')
            // ->where('emp_applications.active', 1)
            // ->where('emp_dets.active', 1)
            // ->where('emp_applications.status', 0)
            // ->whereIn('emp_applications.type', [2, 3])
            // ->where('emp_applications.startDate', '>=', date('2023-06-01'))
            // ->orderBy('emp_applications.created_at', 'desc')
            // ->get();

            // $temp1 = collect($applications1);
            // $temp2 = collect($applications2);
            // return $applications =$temp1->merge($temp2);
        }
        elseif($userType == '61')
        {
            return $applications1 = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
            ->select('emp_applications.id','emp_dets.name','emp_dets.id as empId', 'emp_dets.empCode',
            'emp_applications.type', 'emp_applications.startDate','emp_applications.created_at', 'emp_dets.firmType')
            ->where('emp_applications.active', 1)
            ->where('emp_dets.active', 1)
            ->where('emp_applications.type', 1)
            ->where('emp_applications.status1', 1)
            ->where('emp_applications.status2', 1)
            ->where('emp_applications.status', 0)
            ->whereDate('emp_applications.created_at', '>=', date('2023-06-01'))
            ->orderBy('emp_applications.created_at', 'desc')
            ->get();
        }
        else
        {
            return [];
        }
    }
    
    public function getNotificationsMinAge()
    {
        $user = Auth::user();
        $userType = $user->userType;
        $empId = $user->empId;
        
        if($empId != 0)
        {
            if($userType == '11')
            {
                $repIds1 = EmpDet::where('reportingId', $empId)->pluck('id');
                $repIds2 = EmpDet::whereIn('reportingId', $repIds1)->pluck('id');

                $collection = collect($repIds1);
                $merged = $collection->merge($repIds2);
                $reportyId = $merged->all();

                $applications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
                ->select('emp_applications.id','emp_dets.name', 'emp_dets.empCode',
                'emp_applications.type', 'emp_applications.created_at', 'emp_dets.firmType')
                ->where('emp_applications.status', 0)
                ->where('emp_applications.active', 1)
                ->where('emp_applications.created_at', '>=', date('Y-m-d H:i:s', strtotime('-2 min')))
                ->whereIn('emp_dets.reportingId', $reportyId)
                ->orderBy('emp_applications.created_at', 'desc')
                ->get();
            }

            if($userType == '21')
            {
                $applications =  EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
                ->select('emp_applications.id','emp_dets.name', 'emp_dets.empCode',
                'emp_applications.type', 'emp_applications.created_at', 'emp_dets.firmType')
                ->where('emp_applications.status', 0)
                ->where('emp_applications.active', 1)
                ->where('emp_dets.reportingId', $empId)
                ->where('emp_applications.created_at', '>=', date('Y-m-d H:i:s', strtotime('-2 min')))
                ->orderBy('emp_applications.created_at', 'desc')
                ->get();
            }

            if($userType == '31')
            {
                $applications =  EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
                ->select('emp_applications.id','emp_dets.name', 'emp_dets.empCode',
                'emp_applications.type', 'emp_applications.created_at', 'emp_dets.firmType')
                ->where('emp_applications.status', 0)
                ->where('emp_applications.active', 1)
                ->where('emp_applications.created_at', '>=', date('Y-m-d H:i:s', strtotime('-2 min')))
                ->where('emp_dets.id', $empId)
                ->orderBy('emp_applications.created_at', 'desc')
                ->get();
            }
        }
        elseif($userType == '51'  || $userType == '401' || $userType == '201' || $userType == '501')
        {
            $applications =  $applications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
            ->select('emp_applications.id','emp_dets.name', 'emp_dets.empCode',
            'emp_applications.type', 'emp_applications.created_at', 'emp_dets.firmType')
            ->where('emp_applications.status', 0)
            ->where('emp_applications.active', 1)
            ->where('emp_applications.created_at', '>=', date('Y-m-d H:i:s', strtotime('-2 min')))
            ->orderBy('emp_applications.created_at', 'desc')
            ->get();

            // $exitProcess = ExitProcessStatus::
            // ->where('accountDept', 1)
            // ->where('hrDept', 0)
            // ->get();

            // return [$applications, $exitProcess];
        }
        elseif($userType == '91'  || $userType == '401' || $userType == '201' || $userType == '501')
        {

        }

        foreach($applications as $app)
        {
            if($app->type == 1)
                $app['type'] = "AGF";
            elseif($app->type == 2)
                $app['type'] = "Exit Pass";
            elseif($app->type == 3)
                $app['type'] = "Leave";
            else
                $app['type'] = "Travelling Allowance";

        }

        return $applications;
    }

    public function getPersonalNotifications()
    {
        $empId = Auth::user()->empId;
        
        return EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->select('emp_applications.id','emp_dets.name', 'emp_dets.empCode',
        'emp_applications.type', 'emp_applications.created_at', 'emp_dets.firmType')
        ->where('emp_applications.status', 0)
        ->where('emp_applications.type', '!=', 4)
        ->where('emp_applications.active', 1)
        ->where('emp_dets.id', $empId)
        ->whereDate('emp_dets.updated_at', '<=', date('Y-m-d', strtotime('-1 day')))
        ->orderBy('emp_applications.created_at', 'desc')
        ->get();
        
    }

    public function getEmployeeNotifications()
    {
        $empId = Auth::user()->empId;
        return EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->select('emp_applications.id','emp_dets.name', 'emp_dets.empCode', 'emp_applications.type', 'emp_dets.profilePhoto', 'emp_dets.gender')
        ->where('emp_applications.active', 1)
        ->where('emp_applications.status', '!=', 0)
        ->where('emp_dets.id', $empId)
        ->where('emp_applications.updated_at', '<=', date('Y-m-d h:i:s', strtotime('-3')))
        ->get();
    }

    public function getTimeDiff($forDate)
    {
        // Formulate the Difference between two dates
        $date1 = strtotime($forDate);
        $date2 = strtotime(date('Y-m-d H:i:s'));
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

        $temp = '';
        if($days != 0)
        {
            $temp = $days.' day ';
        }

        if($hours != 0)
        {
            $temp = $temp.$hours.' hour ';
        }

        if($minutes != 0)
        {
            $temp = $temp.$minutes.' Min. ';
        }
        
        if($minutes == 0 && $hours == 0 && $days == 0)
        {
            $temp= 'just ago';
        }
        return $temp;
    }

    public function getDateDiff($forDate)
    {
        // Formulate the Difference between two dates
        $date1 = strtotime($forDate);
        $date2 = strtotime(date('Y-m-d'));
      
        $diff = abs($date2 - $date1);

        $years = floor($diff / (365*60*60*24));

        $months = floor(($diff - $years * 365*60*60*24)
                                    / (30*60*60*24));

        $days = floor(($diff - $years * 365*60*60*24 -
                    $months*30*60*60*24)/ (60*60*24));
   

        if($days != 0)
        { 
            return $months.' Months & '.$days.' Days left';
        }
        else
        {
            return 'Today';
        }
    }

    public function get_next_birthday($birthday) {
        $date = new DateTime($birthday);
        $date->modify(date('Y') - $date->format('Y') . ' years');
        if($date < new DateTime()) {
            $date->modify('+1 year');
        }
    
        return $date->format('d M Y');
    }

    public function getWorkingTime($time1, $time2)
    {
        $d1 = new DateTime($time1);
        $d2 = new DateTime($time2);
        $interval = $d1->diff($d2);
        // $diffInSeconds = $interval->s; //45
        $diffInMinutes = $interval->i; //23
        $diffInHours   = $interval->h; //8

        return $diffInHours.':'.$diffInMinutes;
    }

    public function timeDiff($t1, $t2)
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
       
        return $hours.':'.$minutes;
    }

    public function getNotification($empId)
    {
        $empId = Auth::user()->empId;
        $userType = Auth::user()->userType;

        if($empId != '')
        {
            if($userType == '11')
            {
                $users1 = EmpDet::where('reportingId', $empId)->pluck('id');
                $users2 = EmpDet::whereIn('reportingId', $users1)->pluck('id');

                $collection = collect($users1);
                $merged = $collection->merge($users2);
                $users = $merged->all();
            }

            if($userType == '21')
            {
                $users = EmpDet::where('reportingId', $empId)->pluck('id');
            }
        }

        if($userType != '31')
        {
            $reportee =  EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
            ->select('emp_applications.id','emp_dets.name', 'emp_dets.empCode', 'emp_applications.type', 'emp_dets.firmType')
            ->where('emp_applications.active', 1)
            ->where('emp_applications.status', 0);
            if($empId != '')
                $reportee =$reportee->whereIn('emp_dets.id', $users);

            $reportee =$reportee->where('emp_applications.updated_at', '<=', date('Y-m-d h:i:s', strtotime('-3')))
            ->get();
        }
        else
        {
            $reportee=[];
        }

        $personal =  EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->select('emp_applications.id','emp_dets.name', 'emp_dets.empCode', 'emp_applications.type', 'emp_dets.firmType')
        ->where('emp_applications.active', 1)
        ->where('emp_applications.status', [0,1])
        ->where('emp_dets.id', $empId)
        ->where('emp_applications.updated_at', '<=', date('Y-m-d h:i:s', strtotime('-3')))
        ->get();

        return [$personal, $reportee];

    }

    public function SalarySheetGenerateJob()
    {
        $eMrs = EmpMr::where('status', 1)->where('accountStatus', 0)->take(100)->get();
        if($eMrs)
        {
            foreach($eMrs as $row)
            {
                $empDet = EmpDet::find($row->empId);
                if($empDet)
                {
                    $sheet = SalarySheet::where('month', $row->month)->where('empId', $row->empId)->first();
                    if(!$sheet)
                        $sheet = new SalarySheet;

                    $sheet->month = $row->month;
                    $sheet->empId = $row->empId;
                    $sheet->name = $empDet->name;
                    $sheet->organisation = $empDet->organisation;
                    $sheet->empCode = $empDet->empCode;
                    $sheet->designationId = $empDet->designationId;
                    $sheet->departmentId = $empDet->departmentId;
                    $sheet->category = $empDet->section;
                    $sheet->branchId = $empDet->branchId;
                    $sheet->joiningDate = $empDet->joiningDate;
                    $sheet->prevBasicSalary = $empDet->prevBasicSalary;
                    $sheet->salaryHike = 0;
                    $sheet->grossSalary = $empDet->salaryScale;
                    $sheet->perDay = $empDet->salaryScale / date('m', strtotime($row->month));
                    $sheet->totalDaysInMonth = date('m', strtotime($row->month));
                    $sheet->presentDays = $row->totPresent;
                    $sheet->absent = $row->totAbsent;
                    $sheet->extraWorking = $row->extraWorking;
                    $sheet->grossPayableSalary = $row->totPresent*$sheet->perDay;
                    $sheet->advanceAgainstSalary = 0;
                    $sheet->otherDeduction = 0;
                    $sheet->retention = 0;                    
                    $sheet->TDS = $row->TDS;
                    $sheet->MLWF = $row->MLWF;
                    $sheet->ESIC = $row->ESIC;
                    $sheet->PT = $row->PT;
                    $sheet->PF = $row->PF;
                    $sheet->totalDeduction = ($sheet->advanceAgainstSalary + $sheet->otherDeduction + $sheet->TDS + $sheet->retention);
                    $sheet->extraWorkingSalary = $sheet->extraWorking*$sheet->perDay;
                    $sheet->netSalary = ($sheet->grossPayableSalary+$sheet->extraWorkingSalary)-$sheet->totalDeduction;
                    $sheet->accountNo = $empDet->accountNo;
                    $sheet->IFSCCode = $empDet->IFSCCode;
                    $sheet->bankName = $empDet->bankName;
                    $sheet->bankBranch = $empDet->bankBranch;
                    $sheet->remark = 'Salary for the month of '.date('M-Y', strtotime($row->month));
                    $sheet->status = 1;
                    $sheet->type = 1;
                    $sheet->updated_by = 'Automated';
                    $sheet->save();
                }

            }
        }
       ; 
    }

    public function salarySheetConfirmByHr()
    {
        $attendanceConf = AttendanceJob::where('status', 0)->first();
        if($attendanceConf)
        {
            $getEmpCodes = EmpDet::where('branchId', $attendanceConf->fBranchId)->pluck('id');

            if(count($getEmpCodes))
            {
                $records = AttendanceDetailHr::where('month', date('M', strtotime($attendanceConf->fMonth)))
                ->where('year', date('Y', strtotime($attendanceConf->fMonth)))
                ->whereIn('empId', $getEmpCodes)
                ->where('sheetType', $attendanceConf->sheetType)
                ->get(); 
                
                if(count($records))
                {
                    $flag=0;
                    foreach($records->chunk(100) as $chunk)
                    {
                        foreach($chunk as $row)
                        {
                            $accountDetail = AttendanceDetailAccount::where('empId', $row->empId)
                            ->where('day', $row->day)
                            ->where('month', $row->month)
                            ->where('year', $row->year)
                            ->where('sheetType', $attendanceConf->sheetType)
                            ->first();
                            if(!$accountDetail)
                            {                              
                                $accountDetail = new AttendanceDetailAccount;
                                $flag=1;
                            }

                            $accountDetail->empId=$row->empId;
                            $accountDetail->empCode=$row->empCode;
                            $accountDetail->day=$row->day;
                            $accountDetail->month=$row->month;
                            $accountDetail->year=$row->year;
                            $accountDetail->forDate=$row->forDate;
                            $accountDetail->dayName=$row->dayName;
                            $accountDetail->dayStatus=$row->dayStatus;
                            $accountDetail->inTime=$row->inTime;
                            $accountDetail->outTime=$row->outTime;
                            $accountDetail->workingHr=$row->workingHr;
                            $accountDetail->AGFStatus=$row->AGFStatus;
                            $accountDetail->AGFDayStatus=$row->AGFDayStatus;
                            $accountDetail->holiday=$row->holiday;
                            $accountDetail->paymentType=$row->paymentType;
                            $accountDetail->percent=$row->percent;
                            $accountDetail->lateMarkDay=$row->lateMarkDay;
                            $accountDetail->extraWorkingDay=$row->extraWorkingDay;
                            $accountDetail->presentDay=$row->presentDay;
                            $accountDetail->officeInTime=$row->officeInTime;
                            $accountDetail->officeOutTime=$row->officeOutTime;
                            $accountDetail->deviceInTime=$row->deviceInTime;
                            $accountDetail->deviceOutTime=$row->deviceOutTime;
                            $accountDetail->sheetDate=date('Y-m-d');
                            if($attendanceConf->userType == '51')
                            {
                                $accountDetail->sheetType=$attendanceConf->sheetType;
                                $accountDetail->updated_by='HR Manager';
                            }
                            else
                            {
                                $accountDetail->updated_by='Account Manager';
                                $accountDetail->sheetType=$row->sheetType;
                            }
                            
                            $accountDetail->save();
                        }
                    }

                    if($flag == 0)
                    {
                        $attendanceConf->status=2; 
                        $attendanceConf->save(); 
                    }
                }                
            }     
        }
    }
}