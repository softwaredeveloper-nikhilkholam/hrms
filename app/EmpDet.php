<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmpDet extends Model
{
    protected $casts = [
        'bankAccountNo' => 'string',
    ];

     protected $fillable = [
        'userRoleId', // <--- ADD THIS LINE
        'empCode',
        'username',
        'name',
        'firstName',
        'middleName',
        'lastName',
        'gender',
        'region',
        'cast',
        'type',
        'DOB',
        'maritalStatus',
        'phoneNo',
        'whatsappNo',
        'email',
        'presentAddress',
        'permanentAddress',
        'qualification',
        'workingStatus',
        'organisationId',
        'branchId',
        'sectionId',
        'departmentId',
        'designationId',
        'teachingSubject',
        'salaryScale',
        'reportingId',
        'buddyName',
        'jobJoingDate',
        'shift',
        'idCardStatus',
        'startTime',
        'endTime',
        'contractStartDate',
        'contractEndDate',
        'PANNo',
        'bankName',
        'branchName',
        'bankAccountName',
        'bankAccountNo',
        'bankIFSCCode',
        'pfNumber',
        'uIdNumber',
        'reference',
        'instagramId',
        'twitterId',
        'facebookId',
        'attendanceType',
        'reportingType',
        'attendanceStatus',
        'newUser',
        'added_by',
        'updated_by',
    ];

}
