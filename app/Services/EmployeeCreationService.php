<?php

namespace App\Services;

use App\{EmpDet, User, UserRole, EmployeeLetter, EmpFamilyDet, EmployeeExperience, Retention};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class EmployeeCreationService
{
    /**
     * Create a new employee and all related records from validated data.
     *
     * @param array $data The validated data from the request.
     * @return EmpDet The newly created employee record.
     * @throws \Exception If the lock file cannot be opened.
     */
    public function createEmployee(array $data): EmpDet
    {
        // Define a path for our lock file inside the storage directory
        $lockFilePath = storage_path('locks/employee_creation.lock');
        
        // Ensure the directory for the lock file exists
        if (!is_dir(dirname($lockFilePath))) {
            mkdir(dirname($lockFilePath), 0775, true);
        }

        // Open the lock file for writing
        $lockFileHandle = fopen($lockFilePath, 'w');

        if ($lockFileHandle === false) {
            throw new \Exception('Could not create lock file to ensure unique employee creation.');
        }

        try {
            // Acquire an exclusive lock. This will wait until the lock is available.
            if (flock($lockFileHandle, LOCK_EX)) {
                
                // Wrap the entire operation in a database transaction for data integrity
                return DB::transaction(function () use ($data) {
                    
                    // This critical section is now protected from race conditions
                    $empCode = $this->generateEmpCode($data['organisationId']);
                    $username = $this->generateUsername($data['organisationId'], $empCode);

                    $employeeData = array_merge($data, [
                        'empCode' => $empCode,
                        'username' => $username,
                        'name' => trim(ucwords($data['firstName']).' '.ucwords($data['middleName'] ?? '').' '.ucwords($data['lastName'] ?? '')),
                        'DOB' => date('Y-m-d', strtotime($data['DOB'])),
                        'jobJoingDate' => !empty($data['empJobJoingDate']) ? date('Y-m-d', strtotime($data['empJobJoingDate'])) : null,
                        'startTime' => date('H:i:s', strtotime($data['jobStartTime'])),
                        'endTime' => date('H:i:s', strtotime($data['jobEndTime'])),
                        'reportingType' => isset($data['reportingId']) && EmpDet::where('id', $data['reportingId'])->exists() ? 1 : 2,
                        'attendanceStatus' => 0,
                        'newUser' => 1,
                        'added_by' => Auth::user()->username,
                        'updated_by' => Auth::user()->username,
                    ]);
    
                    $employee = EmpDet::create($employeeData);
    
                    // Create all related records
                    $this->createUserAccount($employee, $data);
                    $this->createLetter($employee);
                    $this->createRetentionRecords($employee, $data);
                    $this->createFamilyDetails($employee, $data);
                    $this->createExperienceRecords($employee, $data);
    
                    return $employee;
                });
            }
        } finally {
            // Always release the lock and close the file
            if ($lockFileHandle) {
                flock($lockFileHandle, LOCK_UN); // Release lock
                fclose($lockFileHandle); // Close file
            }
        }
    }

    private function createUserAccount(EmpDet $employee, array $data): void
    {
        User::create([
            'name' => $employee->name,
            'username' => $employee->username,
            'email' => $employee->email,
            'password' => Hash::make('Welcome@1'),
            'empId' => $employee->id,
            'newUser' => 1,
            'transAllowed' => Arr::get($data, 'transAllowed'),
            'userRoleId' => Arr::get($data, 'userRoleId'),
            'userType' => UserRole::where('id', Arr::get($data, 'userRoleId'))->value('userType'),
            'updated_by' => Auth::user()->username,
        ]);
    }

    private function generateEmpCode(int $organisationId): int
    {
        // Define the list of organizations that share a single empCode series
        $sharedOrgIds = [1, 2, 3, 4, 5];
        
        // Start building the base query
        $query = EmpDet::query();

        if (in_array($organisationId, $sharedOrgIds)) {
            // For shared organizations, look for the max empCode across all of them
            $excludedCodes = [4414, 4413, 4412, 4006, 4005, 4004, 4003, 4002, 4001];
            $query->whereIn('organisationId', $sharedOrgIds)
                ->whereNotIn('empCode', $excludedCodes);
        } else {
            // For all other organizations (6, 7, 8, etc.),
            // find the max empCode only within that specific organization.
            $query->where('organisationId', $organisationId);
        }

        // Execute the query to get the highest existing code for the relevant scope
        $lastEmpCode = $query->max('empCode');

        // If a code exists, increment it. Otherwise, start from a default value (e.g., 1001).
        return $lastEmpCode ? $lastEmpCode + 1 : 1001;
    }

    private function generateUsername(int $organisationId, int $empCode): string
    {
        switch ($organisationId) {
            case 6: $prefix = 'AE'; break;
            case 7: $prefix = 'ARW'; break;
            case 8: $prefix = 'ADF'; break;
            default: $prefix = 'AWS'; break;
        }
        return $prefix . $empCode;
    }

    private function createLetter(EmpDet $employee): void
    {
        EmployeeLetter::create([
            'empId' => $employee->id,
            'designationId' => $employee->designationId,
            'branchId' => $employee->branchId,
            'organisation' => $employee->organisationId,
            'fromDate' => $employee->contractStartDate,
            'toDate' => $employee->contractEndDate,
            'salary' => $employee->salaryScale,
            'aPeriod' => 'Probation Period',
            'forDate' => now()->toDateString(),
            'letterType' => 2,
            'updated_by' => Auth::user()->username,
        ]);
    }

    private function createRetentionRecords(EmpDet $employee, array $data): void
    {
        if (empty($data['retentionAmountPerMonth']) || empty($data['deductionFromMonth'])) {
            return;
        }

        for ($monthOffset = 0; $monthOffset < 6; $monthOffset++) {
            $currentMonth = date('Y-m', strtotime("+$monthOffset months", strtotime($data['deductionFromMonth'])));
            Retention::updateOrCreate(
                ['empId' => $employee->id, 'month' => $currentMonth],
                [
                    'retentionAmount' => $data['retentionAmountPerMonth'],
                    'remark' => 'Retention for ' . date('M-Y', strtotime($currentMonth)),
                    'updated_by' => Auth::user()->username,
                ]
            );
        }
    }

    private function createFamilyDetails(EmpDet $employee, array $data): void
    {
        if (!empty($data['emergencyName1']) && !empty($data['emergencyRelation1'])) {
            EmpFamilyDet::create([
                'empId' => $employee->id,
                'name' => $data['emergencyName1'],
                'relation' => $data['emergencyRelation1'],
                'occupation' => $data['emergencyPlace1'] ?? null,
                'contactNo' => $data['emergencyContactNo1'],
                'updated_by' => Auth::user()->username,
            ]);
        }

        if (!empty($data['emergencyName2']) && !empty($data['emergencyRelation2'])) {
            EmpFamilyDet::create([
                'empId' => $employee->id,
                'name' => $data['emergencyName2'],
                'relation' => $data['emergencyRelation2'],
                'occupation' => $data['emergencyPlace2'] ?? null,
                'contactNo' => $data['emergencyContactNo2'],
                'updated_by' => Auth::user()->username,
            ]);
        }
    }

    private function createExperienceRecords(EmpDet $employee, array $data): void
    {
        if (($data['workingStatus'] ?? 1) != 2 || empty($data['experName'])) {
            return;
        }
        
        foreach ($data['experName'] as $index => $name) {
            if (!empty($name) && !empty($data['experDesignation'][$index])) {
                EmployeeExperience::create([
                    'empId' => $employee->id,
                    'experName' => $name,
                    'experDesignation'  => $data['experDesignation'][$index],
                    'experFromDuration' => $data['experFromDuration'][$index] ?? null,
                    'experToDuration' => $data['experToDuration'][$index] ?? null,
                    'experLastSalary' => $data['experLastSalary'][$index] ?? null,
                    'experJobDesc' => $data['experJobDesc'][$index] ?? null,
                    'experReasonLeaving' => $data['experReasonLeaving'][$index] ?? null,
                    'experReportingAuth' => $data['experReportingAuth'][$index] ?? null,
                    'experReportingDesignation' => $data['experReportingDesignation'][$index] ?? null,
                    'experCompanyCont' => $data['experCompanyCont'][$index] ?? null,
                    'updated_by' => Auth::user()->username,
                ]);
            }
        }
    }
}