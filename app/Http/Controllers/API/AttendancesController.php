<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AttendanceProcessorService;
use Illuminate\Support\Facades\Validator;


class AttendancesController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceProcessorService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function showEmployeeReport(Request $request)
    {
        // --- 1. VALIDATE INPUT ---
        $validator = Validator::make($request->all(), [
            'month' => 'required',
            'employeeId' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // --- 2. CALL THE SERVICE FOR THE SPECIFIC EMPLOYEE ---
        // This is the core of the function. It calls the powerful service we already built.
        $employeeData = $this->attendanceService->processSingleEmployeeReport(
            $request->employeeId,
            $request->month
        );

        $salary = $employeeData['info']->salaryScale;
        $perDaySalary = $salary / date('t', strtotime($request->month));
        $employeeData['totals']['totalPay'] = ($perDaySalary*$employeeData['totals']->present) + ($perDaySalary*$employeeData['totals']->weekly_leave);
        $employeeData['totals']->presentPay = $perDaySalary*$employeeData['totals']->present + $perDaySalary*$employeeData['totals']->weekly_leave;
        $employeeData['totals']->absentPay = $perDaySalary*$employeeData['totals']->absent;
        $employeeData['totals']->latemarkPay = $perDaySalary*$employeeData['totals']->late_mark_deductions;
        $employeeData['totals']->wlPay = $perDaySalary*$employeeData['totals']->total_deductions;
        $employeeData['totals']->extraWork = $perDaySalary*$employeeData['totals']->extra_work;
        return response()->json(['data' => $employeeData]);
    }

}