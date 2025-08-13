<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->userType == '51';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Basic Details
            'userRoleId' => 'required|integer',
            'organisationId' => 'required|integer',
            'firstName' => 'required|string|max:100',
            'middleName' => 'nullable|string|max:100',
            'lastName' => 'required|string|max:100',
            'profilePhoto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'gender' => 'required|string',
            'region' => 'nullable|string|max:100',
            'cast' => 'nullable|string|max:100',
            'type' => 'nullable|string|max:100',
            'DOB' => 'required|date',
            'maritalStatus' => 'required|string',
            'phoneNo' => 'required|string|digits:10',
            'whatsappNo' => 'nullable|string|digits:10',
            'email' => 'nullable|email|max:255',
            'presentAddress' => 'required|string',
            'permanentAddress' => 'nullable|string',
            'qualification' => 'required|string|max:255',
            'workingStatus' => 'required|integer',

            // Work Profile
            'branchId' => 'required|integer',
            'departmentId' => 'required|integer',
            'designationId' => 'required|integer',
            'teachingSubject' => 'nullable|string|max:255',
            'salaryScale' => 'nullable|numeric',
            'buddyName' => 'nullable|string|max:255',
            'empJobJoingDate' => 'nullable|date',
            'shift' => 'nullable|string',
            'jobStartTime' => 'nullable|date_format:H:i',
            'jobEndTime' => 'nullable|date_format:H:i',
            'contractStartDate' => 'nullable|date',
            'contractEndDate' => 'nullable|date|after_or_equal:contractStartDate',
            'reportingId' => 'nullable|integer',
            'idCardStatus' => 'nullable|string',
            'transAllowed' => 'required|boolean',
            'retentionAmountPerMonth' => 'nullable|numeric',
            'deductionFromMonth' => 'nullable|date_format:Y-m',

            // Bank & ID Details
            'aadhaarCardNo' => 'required|string|digits:12|unique:emp_dets,AADHARNo',
            'PANNo' => 'required|string|size:10',
            'bankName' => 'nullable|string',
            'bankBranch' => 'nullable|string',
            'bankAccountName' => 'nullable|string',
            'bankAccountNo' => 'nullable|string',
            'bankIFSCCode' => 'nullable|string',
            'pfNumber' => 'nullable|string',
            'uIdNumber' => 'nullable|string',

            // Other Details
            'reference' => 'nullable|string',
            'instagramId' => 'nullable|string',
            'twitterId' => 'nullable|string',
            'facebookId' => 'nullable|string',
            'attendanceType' => 'required|string',

            // Emergency Contacts
            'emergencyName1' => 'required_with:emergencyRelation1,emergencyContactNo1',
            'emergencyRelation1' => 'required_with:emergencyName1,emergencyContactNo1',
            'emergencyContactNo1' => 'required_with:emergencyName1,emergencyRelation1',
            'emergencyPlace1' => 'nullable|string',
            'emergencyName2' => 'nullable|string',
            'emergencyRelation2' => 'nullable|string',
            'emergencyContactNo2' => 'nullable|string',
            'emergencyPlace2' => 'nullable|string',

            // Experience Details (Arrays)
            'experName.*' => 'nullable|string',
            'experDesignation.*' => 'nullable|string',
            'experFromDuration.*' => 'nullable|date',
            'experToDuration.*' => 'nullable|date',
            'experLastSalary.*' => 'nullable|numeric',
            'experJobDesc.*' => 'nullable|string',
            'experReasonLeaving.*' => 'nullable|string',
            'experReportingAuth.*' => 'nullable|string',
            'experReportingDesignation.*' => 'nullable|string',
            'experCompanyCont.*' => 'nullable|string',

            // Documents
            'uploadAddharCard' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'uploadPanCard' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'uploadTestimonials10th' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'uploadTestimonials12th' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'uploadTestimonialsGrad' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'uploadTestimonialsPostGrad' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'uploadTestimonialsOtherEducation' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'uploadDrivingLicense' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'uploadRtoBatch' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'uploadElectricityBill' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'uploadBankDetails' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'uploadEmployeeContract' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'uploadTestimonialsOther' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }
}
