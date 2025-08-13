<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\EmpMr; // Adjust namespace if needed.
use App\EmpDet; // Adjust namespace if needed.
use App\Department; // Adjust namespace if needed.
use App\Designation; // Adjust namespace if needed.
use App\ContactusLandPage; // Adjust namespace if needed.


class SalariesController extends Controller
{
    public function show(Request $request)
    {
        $forDate = sprintf('%04d-%02d-01', $request->year, $request->month);
        $monthDays = date('t', strtotime($request->year.'-'.$request->month));
        $user = Auth::user();
        if (!$user || !isset($user->empId)) {
             return response()->json(['message' => 'Unauthorized or employee ID not linked to user.'], 401);
        }

        $empDet = EmpDet::where('id', $user->empId)->first();

        if (!$empDet) {
            return response()->json(['message' => 'Employee details not found for authenticated user.'], 404);
        }
        if ($request->employeeId !== (string)$empDet->empCode) { // Cast empCode to string for comparison
             return response()->json(['message' => 'Requested employee ID does not match authenticated user.'], 403);
        }

        $empMr = EmpMr::join('emp_dets', 'emp_mrs.empId', 'emp_dets.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->join('contactus_land_pages', 'emp_mrs.branchId', 'contactus_land_pages.id')
            ->select(
                'emp_dets.empCode', 'emp_dets.jobJoingDate', 'designations.name as designationName',
        'departments.name as departmentName', 'emp_mrs.totPresent', 'emp_mrs.grossSalary',
        'emp_mrs.bankName', 'emp_mrs.bankAccountNo', 'emp_mrs.bankIFSCCode','emp_dets.PANNo', 
        'emp_dets.pfNumber', 'emp_dets.AADHARNo', 'emp_dets.ESIC', 'emp_mrs.extraWorking','emp_mrs.hra', 
        'emp_mrs.transportAllowance', 'emp_mrs.otherAllowance', 'emp_mrs.PT as ptDeduction', 'emp_mrs.pf as pfDeduction', 
        'emp_mrs.ESIC as ESICDeduction', 'emp_mrs.MLWL as MLWLDeduction','emp_mrs.advanceAgainstSalary','emp_mrs.otherDeduction',
        'emp_mrs.retention','emp_mrs.forDate'
            )
            ->where('emp_mrs.empId', $user->empId)
            ->where('emp_mrs.forDate', date('Y-m', strtotime($forDate)))
            ->first();

        if (!$empMr) {
            return response()->json(['status' => 'error', 'message' => 'Salary details not found for the specified month and employee.'], 404);
        }

        // --- Data Transformation & Calculation to match React Native Interface ---

        // Basic Salary: This is a critical calculation.
        // If grossSalary is the total, and you break it down into HRA, Transport, OtherAllowance,
        // then Basic is likely: Gross - HRA - Transport - Other.
        // If 'basic' is a distinct field in your DB, use that directly.

        // Assuming your `grossSalary` already accounts for all earnings and you need to derive basic.
        $basic = (float)(($empMr->grossSalary / $monthDays) * $empMr->totPresent) - (float)$empMr->hra - (float)$empMr->transportAllowance - (float)$empMr->otherAllowance;
        if($basic < 0) $basic = 0; // Ensure basic doesn't go negative

        // Mobile Allowance: Assuming `otherAllowance` maps to this. Adjust if a dedicated field exists.
        $mobileAllowance = (float)$empMr->otherAllowance;

        // TDS: You need to define what in your data maps to TDS.
        // Based on previous discussions, it might be MLWLDeduction + retention.
        // Or if you have a specific 'incomeTax' field, use that.
        $tds = (float)$empMr->MLWLDeduction;

        // Other Deductions: Combine advanceAgainstSalary and otherDeduction
        $otherDeductionsCombined = (float)$empMr->advanceAgainstSalary + (float)$empMr->otherDeduction  + (float)$empMr->retention;


        return response()->json([
            'status' => 'success',
            'data' => [
                // Employee Information
                'empCode' => (string)$empMr->empCode, // Ensure string
                'jobJoiningDate' => (string)date('d-m-Y', strtotime($empMr->jobJoingDate)), // Ensure string, matches RN interface
                'designationName' => (string)$empMr->designationName,
                'departmentName' => (string)$empMr->departmentName,
                'panNo' => (string)$empMr->PANNo,
                'aadharNo' => (string)$empMr->AADHARNo,

                // Bank Details
                'bankName' => (string)$empMr->bankName,
                'bankAccountNo' => (string)$empMr->bankAccountNo,
                'ifscCode' => (string)$empMr->bankIFSCCode,

                // PF/ESIC Details
                'pfUANNo' => (string)$empMr->pfNumber, // Matches RN interface
                'esiNo' => (string)$empMr->ESIC,       // Matches RN interface (from ESIC column)

                // Earnings
                'grossSalary' => round($empMr->grossSalary, 2), // actual Gross Salary
                'basic' => round($basic, 2), // Calculated basic
                'hra' => (float)$empMr->hra,
                'extraWorking' => (float)$empMr->extraWorking, // Matches RN interface (from extraWorking column)
                'transportAllowance' => (float)$empMr->transportAllowance,
                'mobileAllowance' => round($mobileAllowance, 2), // Mapped from otherAllowance

                // Deductions (Nested Object)
                'deductions' => [
                    'pt' => (float)$empMr->ptDeduction,
                    'tds' => round($tds, 2), // Combined MLWL and retention
                    'pf' => (float)$empMr->pfDeduction,
                    'otherDductions' => round($otherDeductionsCombined, 2), // Combined advanceAgainstSalary and otherDeduction
                ],
            ],
        ]);
    }
}