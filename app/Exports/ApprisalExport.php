<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ApprisalExport implements FromQuery, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Database\Query\Builder
    */
    public function query()
    {
        // Get the initial set of employee IDs
        $empIds = DB::table('appraisals')->where('active', 1)->pluck('empId')->unique();

        // Return the Query Builder instance. The package will handle execution.
        return DB::table('emp_dets as e')
            ->select(
                'e.name',
                'e.empCode',
                'd.name as departmentName',
                'des.name as designationName',
                'd.section',
                'clp.branchName',
                'e.jobJoingDate as joiningDate',
                'a23.finalRs as salary23_24',
                'a25.oldSalary as salary24_25',
                'a25.finalRs as salary25_26'
            )
            ->join('departments as d', 'e.departmentId', '=', 'd.id')
            ->join('designations as des', 'e.designationId', '=', 'des.id')
            ->join('contactus_land_pages as clp', 'e.branchId', '=', 'clp.id')
            ->leftJoin('appraisals as a23', function ($join) {
                $join->on('e.id', '=', 'a23.empId')->whereRaw('YEAR(a23.created_at) = 2023');
            })
            ->leftJoin('appraisals as a24', function ($join) {
                $join->on('e.id', '=', 'a24.empId')->whereRaw('YEAR(a24.created_at) = 2024');
            })
            ->leftJoin('appraisals as a25', function ($join) {
                $join->on('e.id', '=', 'a25.empId')->whereRaw('YEAR(a25.created_at) = 2025');
            })
            ->whereIn('e.id', $empIds)
            ->where('e.active', 1)
            ->orderBy('e.id'); // <-- FIX: Added this line
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        // This defines the header row in the Excel file
        return [
            'Name',
            'Code',
            'Department',
            'Designation',
            'Section',
            'Branch',
            'Joining Date',
            'Salary 23-24',
            'Salary 24-25',
            'Salary 25-26',
        ];
    }

    /**
    * @param mixed $employee
    * @return array
    */
    public function map($employee): array
    {
        // This maps the data to the correct columns
        return [
            $employee->name,
            $employee->empCode,
            $employee->departmentName,
            $employee->designationName,
            $employee->section,
            $employee->branchName,
            $employee->joiningDate,
            $employee->salary23_24,
            $employee->salary24_25,
            $employee->salary25_26,
        ];
    }
}