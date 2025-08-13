<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\EmpDet;
use App\User;

class EmployeeDetailsController extends Controller
{
    public function employeeProfile()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated.',
            ], 401);
        }

        $employee = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.*', 'designations.name as designationName', 'departments.name as departmentName','departments.section',
         'emp_dets.branchName as bankBranchName', 'contactus_land_pages.address as branchAddress', 'contactus_land_pages.branchName')
        ->where('emp_dets.id', $user->empId)
        ->first();

        if($employee->reportingType == 2)
            $reportingAuthority = User::where('id', $employee->reportingId)->value('name');
        else
            $reportingAuthority = EmpDet::where('id', $employee->reportingId)->value('name');


        $buddyName= ($employee->buddyName)?User::where('id', $employee->buddyName)->value('name'):'-';

        // Format the user profile response
        $profile = [
            'id' => $user->id,
            'name' => $employee->name,
            'empCode' => $user->username,
            'email' => $employee->email,
            'mobile' => '+91 '.$employee->phoneNo,
            'birthday' => date('d-m-Y', strtotime($employee->DOB)) ?? '',
            'gender' => $employee->gender ?? '',
            'present_address' => $employee->presentAddress ?? '',
            'permanent_address' => $employee->permanentAddress ?? '',
            'marital_status' => $employee->maritalStatus ?? '',
            'whatsapp_no' => $employee->whatsappNo ?? '',
            'job_title' => $employee->designationName ?? '',
            'department' => $employee->departmentName ?? '',
            'joining_date' => date('d-m-Y', strtotime($employee->jobJoingDate)) ?? '',
            'location' => $employee->branchAddress ?? '',
            'education' => $employee->education ?? '',
            'contract_start_date' => $employee->contractStartDate,
            'contract_end_date' => $employee->contractEndDate,
            'reporting_authority' => $reportingAuthority,
            'buddy_name' => $buddyName,
            'organisation' => $employee->organisation,
            'religion' => $employee->religion,
            'caste' => $employee->caste,
            'sub_caste' => $employee->subCaste,
            'marital_status' => $employee->maritalStatus,
            'qualification' => $employee->qualification,
            'experience_or_fresher' => $employee->workingStatus == 1 ?'Fresher':'Experience',
            'section' => optional($user->section)->name,
            'shift' => $employee->shift ?? 'Day Shift',
            'officeTime' => date('H:i', strtotime($employee->startTime)).' To '.date('H:i', strtotime($employee->endTime)),
            'transport_allowance' => $employee->transAllowed ? 'Yes' : 'No',
            'aadhaar_no' => $employee->AADHARNo,
            'pan_no' => $employee->PANNo,
            'account_number' => $employee->bankAccountNo,
            'bank_name' => $employee->bankName,
            'bank_branch' => $employee->bankBranchName,
            'ifsc_code' => $employee->bankIFSCCode,
            'pf_number' => $employee->pfNumber,
            'reference' => $employee->reference,
            'instagram_id' => $employee->instagramId,
            'facebook_id' => $employee->facebookId,
            'twitter_id' => $employee->twitterId,
            'emergency_contact_1' => [
                'name' => $employee->twitterId,
                'relation' => $employee->twitterId,
                'place' => $employee->twitterId,
                'contact' => $employee->twitterId,
            ],
            'emergency_contact_2' => [
                'name' => $employee->twitterId,
                'relation' => $employee->twitterId,
                'place' => $employee->twitterId,
                'contact' => $employee->twitterId,
            ],
            'profile_image' => $employee->profilePhoto ? url('admin/profilePhotos/' . $employee->profilePhoto) : null,
        ];

        return response()->json([
            'success' => true,
            'data' => $profile
        ]);
    }

    public function employees()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated.',
            ], 401);
        }

        $empId = Auth::user()->empId;
        $uId = Auth::user()->id;
        $userType = Auth::user()->userType;

        if($empId != '')
        {
            if($userType == '11')
            {
                $users1 = EmpDet::where('reportingId', $empId)->where('active', 1)->pluck('id');
                $users2 = EmpDet::whereIn('reportingId', $users1)->where('active', 1)->pluck('id');

                $collection = collect($users1);
                $merged = $collection->merge($users2);
                $users = $merged->all();
            }

            if($userType == '21' || $userType == '11')
                $users = EmpDet::where('reportingId', $empId)->where('active', 1)->pluck('id');
        }

        $employees = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.id','emp_dets.firmType','emp_dets.phoneNo','emp_dets.feesConcession', 'emp_dets.profilePhoto',
        'emp_dets.name', 'emp_dets.username', 'emp_dets.empCode','contactus_land_pages.branchName',
        'departments.name as departmentName','designations.name as designationName');

        if($userType == '601')
        {
            $employees = $employees->where('emp_dets.reportingId', $uId)
            ->where('emp_dets.active', 1)
            ->get();
            return response()->json($employees);
        }

        if($userType == '101')
        {
            $employees = $employees->whereIn('departments.name', ['Security Department'])
            ->where('emp_dets.active', 1)
            ->get();
            return response()->json($employees);
        }

        if($empId != '')
            $employees=$employees->whereIn('emp_dets.id', $users);
        
        $employees=$employees->where('emp_dets.active', 1);
        if($empId != '')
            $employees=$employees->orderBy('departments.name');
        else
            $employees=$employees->orderBy('emp_dets.empCode');

        $employees=$employees->orderBy('emp_dets.empCode')->get();
        
        return response()->json($employees);
    }
}
