<?php

namespace App\Exports;

use App\EmpDet;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class EmployeesExport implements FromCollection, WithHeadings, WithEvents, WithColumnFormatting, WithMapping
{
    private $search;
    private $active;
    private $section;
    private $userType;
    private $rowCount;

    /**
     * Constructor to initialize properties.
     *
     * @param string|int $search
     * @param mixed $active
     * @param mixed $section
     */
    public function __construct($search, $active, $section)
    {
        $this->search = $search;
        $this->active = $active;
        $this->section = $section;
        $this->userType = Auth::user()->userType;
    }

    /**
     * Define column formats for specific columns.
     * Note: Corrected column letters based on headings.
     *
     * @return array
     */
    public function columnFormats(): array
    {
        // Use TEXT format for IDs like Account No, PAN, and AADHAR to preserve leading zeros and formatting.
        if ($this->userType == '61') {
            // For userType 61: M=Salary, O=Account No, S=PAN, T=AADHAR
            return [
                'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                'O' => NumberFormat::FORMAT_TEXT,
                'S' => NumberFormat::FORMAT_TEXT,
                'T' => NumberFormat::FORMAT_TEXT,
            ];
        } else {
            // For other users: N=Account No, R=PAN, S=AADHAR
            return [
                'N' => NumberFormat::FORMAT_TEXT,
                'R' => NumberFormat::FORMAT_TEXT,
                'S' => NumberFormat::FORMAT_TEXT,
            ];
        }
    }

    /**
     * Fetch the data for the export from the database.
     * This version uses LEFT JOINs to fetch reporting manager data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Base query
        $employeesQuery = EmpDet::query();

        // Join related tables
        $employeesQuery->join('departments', 'emp_dets.departmentId', '=', 'departments.id')
            ->join('designations', 'emp_dets.designationId', '=', 'designations.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', '=', 'contactus_land_pages.id')
            // LEFT JOIN for reporting manager from 'users' table
            ->leftJoin('users as reporting_user', function ($join) {
                $join->on('reporting_user.id', '=', 'emp_dets.reportingId')
                     ->where('emp_dets.reportingType', '=', 2);
            })
            // LEFT JOIN for reporting manager from 'emp_dets' table (self-join)
            ->leftJoin('emp_dets as reporting_employee', function ($join) {
                $join->on('reporting_employee.id', '=', 'emp_dets.reportingId')
                     ->where('emp_dets.reportingType', '!=', 2);
            });

        // Define a comprehensive list of columns to select.
        $selectColumns = [
            DB::raw('COALESCE(reporting_user.name, reporting_employee.name) as repoAuthName'),
            'contactus_land_pages.branchName',
            'emp_dets.idCardStatus', // Added idCardStatus
            'emp_dets.empCode', 'emp_dets.name', 'emp_dets.qualification', 'emp_dets.startTime', 'emp_dets.endTime',
            'emp_dets.DOB', 'emp_dets.jobJoingDate', 'departments.name as departmentName',
            'designations.name as designationName', 'emp_dets.phoneNo', 'emp_dets.organisation',
            'emp_dets.presentAddress', 'emp_dets.bankAccountNo', 'emp_dets.bankName', 'emp_dets.maritalStatus',
            'emp_dets.gender', 'emp_dets.branchName as bankBranchName', 'emp_dets.bankIFSCCode',
            'emp_dets.PANNo', 'emp_dets.AADHARNo', 'emp_dets.verifyStatus', 'emp_dets.email',
        ];

        if ($this->userType == '61') {
            $selectColumns[] = 'emp_dets.salaryScale';
        }

        $employeesQuery->select($selectColumns);

        // Apply primary filters
        $employeesQuery->where('emp_dets.active', $this->active)
            ->where('departments.section', $this->section)
            ->where('emp_dets.lastDate', NULL);


        $employees = $employeesQuery->orderBy('emp_dets.empCode')->get();

        // Set the row count for styling purposes in the AfterSheet event
        $this->rowCount = $employees->count();

        return $employees;
    }

    /**
     * Maps the data for each row. This is where we format the final output.
     *
     * @param \App\EmpDet $employee
     * @return array
     */
    public function map($employee): array
    {
        // The reporting authority's name is now directly available from the query result.
        $repoAuth = $employee->repoAuthName ?? 'N/A';
        
        // Base data array common to all user types
        $data = [
            $repoAuth,
            $employee->branchName,
            $employee->empCode,
            $employee->name,
            $employee->qualification,
            $employee->DOB,
            $employee->jobJoingDate,
            $employee->gender,
            $employee->maritalStatus,
            $employee->departmentName,
            $employee->designationName,
            $employee->phoneNo,
        ];

        // Add columns conditionally based on user type
        if ($this->userType == '61') {
            $data = array_merge($data, [
                $employee->salaryScale,
                $employee->presentAddress,
                $employee->bankAccountNo,
                $employee->bankName,
                $employee->bankBranchName,
                $employee->bankIFSCCode,
                $employee->PANNo,
                $employee->AADHARNo,
                $employee->idCardStatus, // Using the ENUM value directly
                $employee->organisation,
                $employee->startTime,
                $employee->endTime,
                $employee->verifyStatus == 0 ? 'No' : 'Yes',
            ]);
        } else {
            $data = array_merge($data, [
                $employee->presentAddress,
                $employee->bankAccountNo,
                $employee->bankName,
                $employee->bankBranchName,
                $employee->bankIFSCCode,
                $employee->PANNo,
                $employee->AADHARNo,
                $employee->email,
                $employee->idCardStatus, // Using the ENUM value directly
                $employee->organisation,
                $employee->startTime,
                $employee->endTime,
                $employee->verifyStatus == 0 ? 'No' : 'Yes',
            ]);
        }

        return $data;
    }

    /**
     * Defines the headings for the Excel file.
     *
     * @return array
     */
    public function headings(): array
    {
        if ($this->userType == '61') {
            return ["Reporting Auth", "Branch Name", "Emp Code", "Employee Name", "Qualification", "DOB", "DOJ", "Gender", "Marital Status", 'Department', 'Designation', 'Phone No', 'Salary', 'Address', 'Account No', 'Bank Name', 'Branch Name', 'IFSC code', 'PAN No', 'AADHAR No', 'IdCard Status', 'Organisation', 'In Time', 'Out Time', 'Verify'];
        }
        return ["Reporting Auth", "Branch Name", "Emp Code", "Employee Name", "Qualification", "DOB", "DOJ", "Gender", "Marital Status", 'Department', 'Designation', 'Phone No', 'Address', 'Account No', 'Bank Name', 'Branch Name', 'IFSC code', 'PAN No', 'AADHAR No', 'Email', 'IdCard Status', 'Organisation', 'In Time', 'Out Time', 'Verify'];
    }

    /**
     * Registers events to style the sheet after it's created.
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = $sheet->getHighestColumn();
                $headerRange = 'A1:' . $lastColumn . '1';
                $totalRange = 'A1:' . $lastColumn . ($this->rowCount + 1);

                // --- Style Header Row ---
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['argb' => 'FFFFFFFF'], // White text for better contrast
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['argb' => 'FFDD4B39'], // Corrected ARGB for opaque red/orange background
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // --- Freeze Header Row ---
                $sheet->freezePane('A2');

                // --- Set Row Height for Header ---
                $sheet->getRowDimension('1')->setRowHeight(30);

                // --- Apply Borders to All Data ---
                $sheet->getStyle($totalRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                // --- Set Font for the Entire Sheet ---
                $sheet->getStyle($totalRange)->getFont()->setName('Times New Roman')->setSize(11);
                // Make header font size slightly larger again after global change
                $sheet->getStyle($headerRange)->getFont()->setSize(12);


                // --- Auto-size all columns for better readability ---
                foreach (range('A', $lastColumn) as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}
