<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\StandardResignationExport;
use App\Helpers\Utility;
use App\HrPolicy;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Ticket;
use App\CommonForm;
use App\Department;
use App\EmpDet;
use App\ExitReportingAuth;
use App\ExitProcessStatus;
use App\ExitStoreDept;
use App\ExitItDept;
use App\ExitErpDept;
use App\ExitAccountDept;
use App\ExitTopAuthority;
use App\AttendanceDetail;
use App\ExitHrDept;
use App\ContactusLandPage;
use App\WorkFromHome;
use App\HolidayDept;
use App\User;
use App\EmployeeChequeDetail;
use Auth;
use PDF;
use DateTime;
use Excel;
use DB;

class HrPoliciesController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month;
        $section = $request->section;
        if ($month == '')
            $month = date('Y-m');

        if ($section == '')
            $section = 'Non Teaching';

        $policies = HrPolicy::where('active', 1)->where('section', $section)->where('month', $month)->get();
        return view('admin.hrPolicies.show')->with(['policies' => $policies, 'month' => $month, 'section' => $section]);
    }

    public function employeeResignation(Request $request)
    {
        $empId = Auth::user()->empId;
        $resins = ExitProcessStatus::join('emp_dets', 'exit_process_statuses.empId', 'emp_dets.id')
            ->select('emp_dets.name', 'exit_process_statuses.*', 'emp_dets.reportingType')
            ->where('exit_process_statuses.empId', $empId)
            ->where('exit_process_statuses.active', 0)
            ->orderBy('exit_process_statuses.created_at', 'desc')
            ->get();

        return view('admin.exitProcess.list')->with(['resins' => $resins]);
    }

    public function deleteResignation($id)
    {
        $resin = ExitProcessStatus::find($id);
        $resin->active = 0;
        $resin->save();
        return redirect('/exitProces/employeeResignation')->with('success', 'Resignation deleted successfully.');
    }

    public function apply(Request $request)
    {
        $userType = Auth::user()->userType;
        if ($userType == '51') {
            return view('admin.exitProcess.apply');
        }

        $resins = ExitProcessStatus::join('emp_dets', 'exit_process_statuses.empId', 'emp_dets.id')
            ->select('emp_dets.name', 'exit_process_statuses.*')
            ->where('exit_process_statuses.empId', Auth::user()->empId)
            ->where('exit_process_statuses.active', 1)
            ->first();

        if ($resins) {
            return $this->view($resins->id);
        }

        $employee = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
            ->select('emp_dets.empCode', 'emp_dets.reportingId', 'departments.section')
            ->where('emp_dets.id', Auth::user()->empId)
            ->first();

        if ($employee->reportingType == 1)
            $id = EmpDet::where('id', $employee->reportingId)->value('id');
        else
            $id = $employee->reportingId;

        $user = User::find($id);

        $commonF = CommonForm::where('active', 1)->first();

        if ($employee->section == 'Teaching')
            $noticeP = $commonF->teachingNoticePer;
        else
            $noticeP = $commonF->nonTeachingNoticePer;

        return view('admin.exitProcess.apply')->with(['noticeP' => $noticeP, 'user' => $user, 'employee' => $employee]);
    }

    public function create()
    {
        $policies = HrPolicy::where('active', 1)->get();
        $departmentIds = explode(',', $policies[2]->temp2);
        $departments = Department::whereActive(1)->orderBy('name')->get(['name', 'id']);
        return view('admin.hrPolicies.create')->with(['departmentIds' => $departmentIds, 'departments' => $departments, 'policies' => $policies]);
    }

    public function store(Request $request)
    {
        $month = $request->month;
        $section = $request->section;
        //Rule 1

        $hrRule1 = HrPolicy::where('name', 'Rule 1')->first();
        if (!$hrRule1) {
            $hrRule1 = new HrPolicy;
            $hrRule1->name = 'Rule 1';
        }

        $hrRule1->month = $request->month;
        $hrRule1->section = $request->section;
        $hrRule1->temp1 = $request->rule1Option;
        $hrRule1->updated_by = Auth::user()->username;
        $hrRule1->save();

        // Rule 2
        $hrRule2 = HrPolicy::where('name', 'Rule 2')->first();
        if (!$hrRule2) {
            $hrRule2 = new HrPolicy;
            $hrRule2->name = 'Rule 2';
        }

        $hrRule2->month = $request->month;
        $hrRule2->section = $request->section;
        $hrRule2->temp1 = $request->rule2Days;
        $hrRule2->temp2 = $request->rule2Option;
        $hrRule2->updated_by = Auth::user()->username;
        $hrRule2->save();

        $departments = $request->departmentId;

        // Rule 3
        $hrRule3 = HrPolicy::where('name', 'Rule 3')->first();
        $hrRule3->month = $request->month;
        $hrRule3->section = $request->section;
        if (!$hrRule3) {
            $hrRule3 = new HrPolicy;
            $hrRule3->name = 'Rule 3';
        } else {
            $departmentIds = explode(',', $hrRule3->temp2);
            $saturDay = $hrRule3->temp1;
            if ($saturDay == '1' || $saturDay == 1)
                $satName = 'first';

            if ($saturDay == '2' || $saturDay == 2)
                $satName = 'second';

            if ($saturDay == '3' || $saturDay == 3)
                $satName = 'third';

            if ($saturDay == '4' || $saturDay == 4)
                $satName = 'fourth';

            if ($saturDay == '5' || $saturDay == 5)
                $satName = 'fifth';

            $thirdSat = date('d', strtotime($satName . ' saturday of' . date('M-Y')));
            $emps = EmpDet::whereIn('departmentId', $departments)->pluck('id');
            AttendanceDetail::where('month', date('M', strtotime($month)))->where('year', date('Y', strtotime($month)))->where('day', $thirdSat)->whereIn('empId', $emps)->where('dayStatus', 'WO')->update(['dayStatus' => '0']);
            AttendanceDetail::where('month', date('M', strtotime($month)))->where('year', date('Y', strtotime($month)))->where('day', $thirdSat)->whereIn('empId', $emps)->where('dayStatus', 'WOP')->update(['dayStatus' => 'P']);
            AttendanceDetail::where('month', date('M', strtotime($month)))->where('year', date('Y', strtotime($month)))->where('day', $thirdSat)->whereIn('empId', $emps)->where('dayStatus', 'WOPH')->update(['dayStatus' => 'PH']);
        }

        $emps = [];
        $saturDay = $request->rule3Day;
        $temp = $thirdSat = $satName = '';
        if ($saturDay == '1' || $saturDay == 1)
            $satName = 'first';

        if ($saturDay == '2' || $saturDay == 2)
            $satName = 'second';

        if ($saturDay == '3' || $saturDay == 3)
            $satName = 'third';

        if ($saturDay == '4' || $saturDay == 4)
            $satName = 'fourth';

        if ($saturDay == '5' || $saturDay == 5)
            $satName = 'fifth';

        $thirdSat = date('d', strtotime($satName . ' saturday of' . date('M-Y')));
        $emps = EmpDet::whereIn('departmentId', $departments)->pluck('id');
        AttendanceDetail::where('month', date('M', strtotime($month)))->where('year', date('Y', strtotime($month)))->where('day', $thirdSat)->whereIn('empId', $emps)->where('dayStatus', '0')->update(['dayStatus' => 'WO']);
        AttendanceDetail::where('month', date('M', strtotime($month)))->where('year', date('Y', strtotime($month)))->where('day', $thirdSat)->whereIn('empId', $emps)->where('dayStatus', 'A')->update(['dayStatus' => 'WO']);
        AttendanceDetail::where('month', date('M', strtotime($month)))->where('year', date('Y', strtotime($month)))->where('day', $thirdSat)->whereIn('empId', $emps)->where('dayStatus', 'P')->update(['dayStatus' => 'WOP']);
        AttendanceDetail::where('month', date('M', strtotime($month)))->where('year', date('Y', strtotime($month)))->where('day', $thirdSat)->whereIn('empId', $emps)->where('dayStatus', 'PL')->update(['dayStatus' => 'WOP']);
        AttendanceDetail::where('month', date('M', strtotime($month)))->where('year', date('Y', strtotime($month)))->where('day', $thirdSat)->whereIn('empId', $emps)->where('dayStatus', 'PH')->update(['dayStatus' => 'WOPH']);
        AttendanceDetail::where('month', date('M', strtotime($month)))->where('year', date('Y', strtotime($month)))->where('day', $thirdSat)->whereIn('empId', $emps)->where('dayStatus', 'PLH')->update(['dayStatus' => 'WOPH']);

        $hrRule3->temp1 = $request->rule3Day;
        $hrRule3->temp2 = implode(",", $departments);
        $hrRule3->updated_by = Auth::user()->username;

        $hrRule3->save();

        // Rule 4
        $hrRule4 = HrPolicy::where('name', 'Rule 4')->first();
        if (!$hrRule4) {
            $hrRule4 = new HrPolicy;
            $hrRule4->name = 'Rule 4';
        }

        $hrRule4->month = $request->month;
        $hrRule4->section = $request->section;
        $hrRule4->temp1 = $request->rule4AMin;
        $hrRule4->temp2 = $request->rule4BMin;
        $hrRule4->temp3 = $request->rule4CNoLate;
        $hrRule4->temp4 = $request->rule4CDay1;
        $hrRule4->temp5 = $request->rule4CLate1;
        $hrRule4->temp6 = $request->rule4CDay2;
        $hrRule4->temp7 = $request->rule4DHr;
        $hrRule4->temp8 = $request->rule4DOption;
        $hrRule4->updated_by = Auth::user()->username;
        $hrRule4->save();

        // Rule 5
        $hrRule5 = HrPolicy::where('name', 'Rule 5')->first();
        if (!$hrRule5) {
            $hrRule5 = new HrPolicy;
            $hrRule5->name = 'Rule 5';
        }

        $hrRule5->month = $request->month;
        $hrRule5->section = $request->section;
        $hrRule5->temp1 = $request->rule5Day;
        $hrRule5->updated_by = Auth::user()->username;
        $hrRule5->save();

        // Rule 6
        $hrRule6 = HrPolicy::where('name', 'Rule 6')->first();
        if (!$hrRule6) {
            $hrRule6 = new HrPolicy;
            $hrRule6->name = 'Rule 6';
        }

        $hrRule6->month = $request->month;
        $hrRule6->section = $request->section;
        $hrRule6->temp1 = $request->rule6Option;
        $hrRule6->updated_by = Auth::user()->username;
        if ($hrRule6->save()) {
            if (date('Y-m') == $month) {
                $attendnaces = AttendanceDetail::where('month', date('M', strtotime($month)))
                    ->where('year', date('Y', strtotime($month)))
                    ->where('dayName', 'Sat')
                    ->get();
            }
        }

        return redirect('/hrPolicies')->with("success", "Updated HR Rules Successfully..");
    }

    public function storeResignation(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $user = Auth::user();
            $userType = Auth::user()->userType;

            $empId = $user->userType == '51'
                ? EmpDet::where('empCode', $request->empCode)->value('id')
                : $user->empId;
        
            $empDet = EmpDet::select('reportingType', 'reportingId')->find($empId);
        
            if (!$empDet) {
                return redirect()->back()->withInput()->with("error", "Employee details not found.");
            }
        
            if (ExitProcessStatus::where('empId', $empId)->where('active', 1)->exists()) {
                return redirect()->back()->withInput()->with("error", "You have already applied for resignation.");
            }
        
            $exitProcess = new ExitProcessStatus();
            $exitProcess->fill([
                'empId'            => $empId,
                'applyDate'        => $request->forDate,
                'reqExitDate'      => $request->reqExitDate,
                'empCode'          => $request->empCode,
                'description'      => $request->description, // HTML allowed
                'expectedLastDate' => $request->expectedLastDate,
                'processType'      => $request->processType,
                'updated_by'       => $user->username,
            ]);
        
            if($empDet->reportingType == 1 || $empDet->reportingType == '')
            {
                $exitProcess->reportingAuthId =  User::where('empId', $empDet->reportingId)
                        ->whereIn('userType', [11, 21, 31])
                        ->value('id');
                if($exitProcess->reportingAuthId == '')
                    $exitProcess->reportingAuthId =  User::where('id', $empDet->reportingId)->value('id');
            }
            else
            {
                $exitProcess->reportingAuthId =  User::where('id', $empDet->reportingId)->value('id');
            }

            if (!$exitProcess->reportingAuthId) {
                return redirect()->back()->withInput()->with("error", "Reporting authority not assigned. Please contact HR.");
            }
        
            if ($exitProcess->save()) {
                $data = EmpDet::join('designations', 'emp_dets.designationId', '=', 'designations.id')
                    ->join('departments', 'emp_dets.departmentId', '=', 'departments.id')
                    ->join('contactus_land_pages', 'emp_dets.branchId', '=', 'contactus_land_pages.id')
                    ->where('emp_dets.id', $empId)
                    ->select(
                        'emp_dets.empCode',
                        'emp_dets.name as employeeName',
                        'departments.name as empDepartmentName',
                        'designations.name as empDesignationName',
                        'contactus_land_pages.branchName'
                    )
                    ->first();
        
                $data['empResignationDate'] = $request->forDate;
                $data['empLastWorkingDate'] = $request->expectedLastDate;
                $data['empReportingAuthorityName'] = User::find($exitProcess->reportingAuthId)->name ?? '';
        
                $commonForm = CommonForm::latest()->first();
        
                if (!empty($user->email)) {
                    $cc = array_filter([
                        $commonForm->ccEmailId1,
                        $commonForm->ccEmailId2,
                        $commonForm->ccEmailId3
                    ]);
        
                    Mail::to($user->email)
                        ->cc($cc)
                        ->bcc('nikhilkholam2025@gmail.com')
                        ->send(new TestMail($data, 'Employee Resignation Submitted: ' . $data->employeeName));
                }
            }
              DB::commit();

            if($userType == '51')
                return redirect('/exitProces/standardProcess')->with("success", "Resignation submitted Successfully.");
            else
                return redirect('/exitProces/employeeResignation')->with("success", "Resignation submitted Successfully.");
        
        } catch (\Exception $e) 
        {
            DB::rollBack();
            Log::error('Error updating exit process: '. $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function removeResignation($id)
    {
        $userType = Auth::user()->userType;
        if ($userType != '51')
            return redirect()->back()->withInput()->with("error", "You are not authorized to delete this.");

        $resignation = ExitProcessStatus::find($id);
        $resignation->active = 0;
        $resignation->updated_by = Auth::user()->username;
        $resignation->save();
        return redirect('/exitProces/standardProcess')->with("success", "Resignation Deleted Successfully.");
    }

    public function exportExcel($type)
    {      
        return Excel::download(new StandardResignationExport($type), 'StandardResignation.xlsx');
    }

    public function calender()
    {
        return view('admin.trainings.list');
    }

    public function standardProcess()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        $empId = $user->empId;
        $userType = $user->userType;
        $userId = $user->id;

        // Base query setup
        $query = ExitProcessStatus::query()
            ->join('emp_dets', 'exit_process_statuses.empId', '=', 'emp_dets.id')
            ->join('departments', 'emp_dets.departmentId', '=', 'departments.id')
            ->join('designations', 'emp_dets.designationId', '=', 'designations.id')
            ->join('users', 'exit_process_statuses.reportingAuthId', '=', 'users.id')
            ->select(
                'emp_dets.name',
                'users.name as reportingAuthorityName',
                'exit_process_statuses.*',
                'departments.name as departmentName',
                'departments.section',
                'designations.name as designationName'
            )
            ->where('exit_process_statuses.active', 1)
            ->where('exit_process_statuses.status', 0); // Status 0 usually means 'pending'

            if($empId == '1914')
            {
                $resins = $query->get();
                return view('admin.exitProcess.pendingNDCList', ['resins' => $resins]);
            }

        $departmentApprovalColumns = [
            '51'  => 'hrDept',          // HR Department
            '61'  => 'accountDept',     // Accounts Department
            '71'  => 'itDept',          // IT Department
            '81'  => 'erpDept',         // ERP Department
            '91'  => 'storeDept',       // Store Department
            '401' => 'finalPermission', // Example: Director / Principal
            '501' => 'finalPermission', // Example: HOD for Non-Teaching
            '201' => 'finalPermission', // Example: HOD for Teaching
        ];

        if (array_key_exists($userType, $departmentApprovalColumns)) {
            $column = $departmentApprovalColumns[$userType];

            if (in_array($userType, ['401', '501', '201'])) {
                // Clone base query to create separate conditions
                $testQuery1 = clone $query;
                $testQuery2 = clone $query;

                // Direct reporting employees
                $testQuery1->where('emp_dets.reportingId', $userId)
                    ->orderBy('exit_process_statuses.reportingAuth')
                    ->orderBy('exit_process_statuses.created_at', 'desc');

                // Section-specific filtering for HOD roles
                if ($userType === '501') {
                    $testQuery2->where('departments.section', 'Non Teaching');
                } elseif ($userType === '201') {
                    $testQuery2->where('departments.section', 'Teaching');
                }

                $testQuery2->where("exit_process_statuses.$column", 1)
                    ->orderBy('exit_process_statuses.reportingAuth')
                    ->orderBy('exit_process_statuses.created_at', 'desc');

                // Merge both result sets
                $resins = $testQuery1->get()->merge($testQuery2->get())->unique('id');

            } elseif ($userType == '51') {
                // HR Dept — show all statuses 0 (pending), 1 (approved), 2 (rejected)
                $query->whereIn("exit_process_statuses.$column", [0, 1, 2])
                    ->orderByRaw("FIELD(exit_process_statuses.$column, 1, 0, 2)");
                $resins = $query->get();

            } else {
                // Other dept heads — show only records where status is 1 (forwarded to them)
                $query->where("exit_process_statuses.$column", 1)
                    ->orderBy('exit_process_statuses.applyDate');
                $resins = $query->get();
            }

        } else {
            // For regular users — show only their own department's exits
            if (!empty($empId)) {
                $query->where('emp_dets.reportingId', $empId);
            }

            $query->orderBy('exit_process_statuses.reportingAuth')
                ->orderBy('exit_process_statuses.created_at', 'desc');

            $resins = $query->get();
        }

        return view('admin.exitProcess.pendingNDCList', ['resins' => $resins]);
    }


    public function archieveStandardProcess()
    {
        $empId = Auth::user()->empId;
        $userType = Auth::user()->userType;
        if ($userType == '51' || $userType == '61' || $userType == '71' || $userType == '81' || $userType == '91' || $userType == '401' || $userType == '501' || $userType == '201') {
            $resins = ExitProcessStatus::join('emp_dets', 'exit_process_statuses.empId', 'emp_dets.id')
                ->join('departments', 'emp_dets.departmentId', 'departments.id')
                ->join('users', 'exit_process_statuses.reportingAuthId', 'users.id')
                ->select('emp_dets.name', 'users.name as reportingAuthorityName', 'exit_process_statuses.*','departments.section')
                ->whereIn('exit_process_statuses.status', [1,2,3]);

            $departmentColumns = [
                '51' => 'hrDept',
                '61' => 'accountDept',
                '71' => 'itDept',
                '81' => 'erpDept',
                '91' => 'storeDept',
                '401' => 'finalPermission',
                '501' => 'finalPermission',
                '201' => 'finalPermission',
            ];

            if (array_key_exists($userType, $departmentColumns)) {
                $column = $departmentColumns[$userType];
                // $resins = $resins->where("exit_process_statuses.$column", 1);

                if($userType == 61)
                    $resins = $resins->whereIn('exit_process_statuses.accountDept', [2,3]);

                if($userType == 71)
                    $resins = $resins->whereIn('exit_process_statuses.itDept', [2,3]);

                if($userType == 81)
                    $resins = $resins->whereIn('exit_process_statuses.erpDept', [2,3]);

                if($userType == 91)
                    $resins = $resins->whereIn('exit_process_statuses.storeDept', [2,3]);

                if($userType == 401 || $userType == 501 || $userType == 201)
                {
                    if($userType == 501)
                        $resins = $resins->where('departments.section', 'Non Teaching');
                    else
                        $resins = $resins->where('departments.section', 'Teaching');
                }

                $resins = $resins->orderBy('exit_process_statuses.hrDept')
                ->orderBy('exit_process_statuses.created_at', 'desc')
                ->get();
            }
        } else {
            $resins = ExitProcessStatus::join('emp_dets', 'exit_process_statuses.empId', 'emp_dets.id')
                ->join('departments', 'emp_dets.departmentId', 'departments.id')
                ->join('users', 'exit_process_statuses.reportingAuthId', 'users.id')
                ->select('emp_dets.name', 'users.name as reportingAuthorityName', 'exit_process_statuses.*','departments.section');
            if ($empId != '')
                $resins = $resins->where('emp_dets.reportingId', $empId);

            $resins = $resins->orderBy('exit_process_statuses.reportingAuth')
                ->orderBy('exit_process_statuses.created_at', 'desc')
                ->get();
        }

        return view('admin.exitProcess.archieveList')->with(['resins' => $resins]);
    }

      
    public function storeExitProcess(Request $request)
    {
        $userType = Auth::user()->userType;
        DB::beginTransaction();

        try 
        {
            $processStep = $request->processStep;
            $fromType = $request->processType;
            $parentId = $request->parentId;
            
            $userId = Auth::user()->id;
            $exitStatus = ExitProcessStatus::find($parentId);
            $action = $request->input('action');
            if($action == '0')
            {
                
                if($request->forcefullyRemark != '' || $request->forcefullyRemark != '.')
                {
                    $exitStatus->forcefullyRemark = $request->forcefullyRemark;
                    $exitStatus->status = 2;
                    $exitStatus->updated_by = Auth::user()->username;
                    $exitStatus->save();
                    return redirect('/exitProces/archieveStandardProcess')->with("success", "NDC Closed forcefully Successfully.");
                }
                else
                {
                    return redirect()->back()->withInput()->with("error", "If you want close NDC forcefully, Remark mandatory");
                }
            }
            else
            {
        
                if (($userId == $exitStatus->reportingAuthId && $processStep == 1)  || $userType == '51') // Reporting Authority
                {
                    $exitProcess = ExitReportingAuth::where('parentId', $parentId)->first();
                    if (!$exitProcess)
                        $exitProcess = new ExitReportingAuth;

                    $exitProcess->parentId = $parentId;
                    $exitProcess->empId = $request->empId;
                    $exitProcess->reportingId = Auth::user()->id;
                    $exitProcess->details = $request->details;
                    $exitProcess->bookSet = $request->bookSet;
                    $exitProcess->officeKeys = $request->officeKeys;
                    $exitProcess->muster = $request->muster;
                    $exitProcess->reportCard = $request->reportCard;
                    $exitProcess->planner = $request->planner;
                    $exitProcess->libraryBooks     = $request->libraryBooks;
                    $exitProcess->teachersKit = $request->teachersKit;
                    $exitProcess->logBook = $request->logBook;
                    $exitProcess->originalDocs = $request->originalDocs;
                    $exitProcess->experienceCertificate = $request->experienceCertificate;
                    $exitProcess->retentionAmt = $request->retentionAmt;
                    $exitProcess->salary = $request->salary;
                    $exitProcess->comment = $request->comment1;
                    $exitProcess->processType = $request->processType;
                    $exitProcess->status = 1;
                    $exitProcess->updated_by = Auth::user()->username;

                    if ($exitProcess->save()) {
                        $exitStatus = ExitProcessStatus::find($parentId);
                        $exitStatus->reportingAuth = 2;
                        $exitStatus->storeDept = 1;
                        if($userType != '51')
                            $exitStatus->reportingAuthDate = date('Y-m-d H:i:s');

                        $exitStatus->updated_by = Auth::user()->username;
                        $exitStatus->save();
                    }
                }

                if (($userType == '91' && $processStep == 2)  || $userType == '51') // Store Department
                {

                    $exitProcess = ExitStoreDept::where('parentId', $parentId)->first();
                    if (!$exitProcess)
                        $exitProcess = new ExitStoreDept;

                    $exitProcess->parentId = $parentId;
                    $exitProcess->empId = $request->empId;
                    $exitProcess->apron = $request->apron;
                    $exitProcess->iCard = $request->iCard;
                    $exitProcess->comment = $request->comment2;
                    $exitProcess->processType = $request->processType;
                    $exitProcess->status = 1;
                    $exitProcess->updated_by = Auth::user()->username;

                    if ($exitProcess->save()) {
                        $exitStatus = ExitProcessStatus::find($parentId);
                        $exitStatus->storeDept = 2;
                        $exitStatus->itDept = 1;
                        $exitStatus->updated_by = Auth::user()->username;
                        if($userType != '51')
                            $exitStatus->storeDeptDate = date('Y-m-d H:i:s');

                        $exitStatus->save();
                    }
                }

                if (($userType == '71' && $processStep == 3)  || $userType == '51') // IT Department
                {
                    $exitProcess = ExitItDept::where('parentId', $parentId)->first();
                    if (!$exitProcess)
                        $exitProcess = new ExitItDept;

                    $exitProcess->parentId = $parentId;
                    $exitProcess->empId = $request->empId;
                    $exitProcess->mailId = $request->mailId;
                    $exitProcess->removeWhatsapp = $request->removeWhatsapp;
                    $exitProcess->asset = $request->asset;
                    $exitProcess->laptop = $request->laptop;
                    $exitProcess->mouse = $request->mouse;
                    $exitProcess->bag = $request->bag;
                    $exitProcess->mobile = $request->mobile;
                    $exitProcess->sim = $request->sim;
                    $exitProcess->debitCharge = $request->debitCharge;
                    $exitProcess->debitAmount = $request->debitAmount;
                    $exitProcess->comment = $request->comment3;
                    $exitProcess->processType = $request->processType;
                    $exitProcess->status = 1;
                    $exitProcess->updated_by = Auth::user()->username;

                    if ($exitProcess->save()) {
                        $exitStatus = ExitProcessStatus::find($parentId);
                        $exitStatus->itDept = 2;
                        $exitStatus->erpDept = 1;
                        $exitStatus->updated_by = Auth::user()->username;
                        if($userType != '51')
                            $exitStatus->itDeptDate = date('Y-m-d H:i:s');

                        $exitStatus->save();
                    }
                }

                if (($userType == '81' && $processStep == 4)  || $userType == '51') // ERP Department
                {
                    $exitProcess = ExitErpDept::where('empId', $request->empId)->where('status', 1)->first();
                    if (!$exitProcess)
                        $exitProcess = new ExitErpDept;

                    $exitProcess->parentId = $request->parentId;
                    $exitProcess->empId = $request->empId;
                    $exitProcess->concession = $request->concession;
                    $exitProcess->biometric = $request->biometric;
                    $exitProcess->erp = $request->erp;
                    if (!empty($request->file('uploadERPAttachment'))) {
                        $fileName = now(). '.' . $request->uploadERPAttachment->extension();
                        $request->uploadERPAttachment->move(public_path('admin/exitProcessDocs/ERPDocs'), $fileName);
                        $exitProcess->uploadERPAttachment = $fileName;
                    }

                    $exitProcess->comment = $request->comment4;
                    $exitProcess->processType = $request->processType;
                    $exitProcess->status = 1;
                    $exitProcess->updated_by = Auth::user()->username;

                    if ($exitProcess->save()) {
                        $exitStatus = ExitProcessStatus::find($parentId);
                        $exitStatus->erpDept = 2;
                        $exitStatus->hrDept = 1;
                        if($userType != '51')
                            $exitStatus->erpDeptDate = date('Y-m-d H:i:s');

                        $exitStatus->updated_by = Auth::user()->username;
                        $exitStatus->save();
                    }
                }

                if ($userType == '51') // HR Department
                {
                    $exitProcess = ExitHrDept::where('parentId', $parentId)->first();
                    if (!$exitProcess)
                        $exitProcess = new ExitHrDept;

                    $exitProcess->parentId = $parentId;
                    $exitProcess->empId = $request->empId;
                    $exitProcess->salCalculation = $request->salCalculation;
                    $exitProcess->fullAndfinal = $request->fullAndfinal;
                    $exitProcess->originalDoc = $request->originalDoc;
                    $exitProcess->comment = $request->comment5;
                    $exitProcess->processType = $request->processType;
                    $exitProcess->status = 1;

                    if ($exitProcess->save()) {
                        $exitStatus = ExitProcessStatus::find($parentId);
                        $exitStatus->hrDept = 2;
                        $exitStatus->finalPermission = 1;
                        if($exitStatus->expectedLastDate != $request->expectedLastDate)
                            $exitStatus->expectedLastDate = $request->expectedLastDate;

                        if($exitStatus->reqExitDate != $request->reqExitDate)
                            $exitStatus->reqExitDate = $request->reqExitDate;

                        $exitStatus->hrDeptDate = date('Y-m-d H:i:s');
                        $exitStatus->updated_by = Auth::user()->username;
                        $exitStatus->save();
                    }
                }

                if (($userType == '401' || $userType == '201' || $userType == '501') && $processStep == 6)  // MD, CEO, COO
                {
                    $exitProcess = ExitTopAuthority::where('parentId', $parentId)->first();
                    if (!$exitProcess)
                        $exitProcess = new ExitTopAuthority;

                    $exitProcess->parentId = $parentId;
                    $exitProcess->empId = $request->empId;

                    $exitProcess->originalDocs = $request->originalDocs;
                    $exitProcess->experienceCertificate = $request->experienceCertificate;
                    $exitProcess->retentionAmt = $request->retentionAmt;
                    $exitProcess->salary = $request->salary;

                    $exitProcess->concession = $request->concession;

                    $exitProcess->comment = $request->comment6;
                    $exitProcess->processType = $request->processType;
                    $exitProcess->status = 1;
                    $exitProcess->updated_by = Auth::user()->username;
                    if ($exitProcess->save()) {
                        $exitAuthProcess = ExitReportingAuth::where('parentId', $parentId)->first();
                        if ($exitAuthProcess) {
                            if ($exitAuthProcess->originalDocs != $request->originalDocs)
                                $exitAuthProcess->originalDocs = $request->originalDocs;

                            if ($exitAuthProcess->experienceCertificate != $request->experienceCertificate)
                                $exitAuthProcess->experienceCertificate = $request->experienceCertificate;

                            if ($exitAuthProcess->retentionAmt != $request->retentionAmt)
                                $exitAuthProcess->retentionAmt = $request->retentionAmt;

                            if ($exitAuthProcess->salary != $request->salary)
                                $exitAuthProcess->salary = $request->salary;

                            $exitAuthProcess->save();
                        }

                        $exitErpProcess = ExitErpDept::where('parentId', $parentId)->first();
                        if ($exitErpProcess) {
                            if ($exitErpProcess->concession != $request->concession)
                                $exitErpProcess->concession = $request->concession;

                            $exitErpProcess->save();
                        }

                        $exitStatus = ExitProcessStatus::find($parentId);
                        $exitStatus->finalPermission = 2;
                        $exitStatus->accountDept = 1;
                        $exitStatus->updated_by = Auth::user()->username;
                        if($userType != '51')
                            $exitStatus->authorityUpdatedAt = date('Y-m-d H:i:s');

                        $exitStatus->save();
                    }
                }

                if ($userType == '61' && $processStep == 8)  // Accounts Department
                {
                    $exitProcess = ExitAccountDept::where('parentId', $parentId)->first();
                    if (!$exitProcess)
                        $exitProcess = new ExitAccountDept;

                    $exitProcess->parentId = $request->parentId;
                    $exitProcess->empId = $request->empId;
                    $exitProcess->retention = $request->retention;
                    $exitProcess->retentionAmt = $request->retentionAmt;
                    $exitProcess->salary = $request->salary;
                    $exitProcess->salaryAmt = $request->salaryAmt;
                    $exitProcess->salaryAdvance = $request->salaryAdvance;
                    $exitProcess->salaryAdvanceAmt = $request->salaryAdvanceAmt;
                    $exitProcess->anyDebit = $request->anyDebit;
                    $exitProcess->anyDebitAmt = $request->anyDebitAmt;
                    $exitProcess->fullAndSet = $request->fullAndSet;
                    $exitProcess->fullAndSetAmt = $request->fullAndSetAmt;

                    $cheques = $request->input('cheques');

                    if ($cheques && is_array($cheques)) {
                        foreach ($cheques as $entry) {
                            // Skip if no payee name
                            if (empty($entry['payeeName'])) continue;

                            // Save to DB (adjust your model and table as needed)
                            EmployeeChequeDetail::create([
                                'empId'     => $exitStatus->empId,
                                'exitId'     => $exitStatus->id,
                                'payeeName'     => $entry['payeeName'],
                                'chequeDate'    => $entry['payeeDate'],
                                'payeeAmount'         => $entry['payeeAmount'],
                                'payeeBank'   => $entry['payeeBank'],
                                'payeeChequeNo'  => $entry['payeeChequeNo'],
                                'updated_by'     => auth()->id(), // optional
                            ]);
                        }
                    }

                    $exitProcess->comment = $request->comment7;
                    $exitProcess->processType = $request->processType;
                    $exitProcess->status = 1;
                    $exitProcess->updated_by = Auth::user()->username;
                    if ($exitProcess->save()) {
                        $exitStatus = ExitProcessStatus::find($parentId);
                        $exitStatus->accountDept = 2;
                        $exitStatus->status = 1;
                        if($userType != '51')
                            $exitStatus->accountDeptDate = date('Y-m-d H:i:s');

                        $exitStatus->updated_by = Auth::user()->username;
                        $exitStatus->save();
                    }
                }

                if ($processStep == 9) {
                    $exitStatus->status = 2;
                    $exitStatus->status = 2;
                    $exitStatus->updated_by = Auth::user()->username;
                    if ($exitStatus->save()) {
                        User::where('empId', $exitStatus->empId)->update(['active' => 0]);
                        EmpDet::where('id', $exitStatus->empId)->update(['active' => 0]);
                        $request->session()->flush();
                        $request->session()->regenerate();

                        return redirect('/login')->with('success', 'Thank You & All the Best');
                    }
                }

            }
            DB::commit();
            return redirect('/exitProces/standardProcess')->with("success", "Information Updated Successfully..");
            
        } catch (\Exception $e) 
        {
            DB::rollBack();
            Log::error('Error updating exit process: '. $e->getMessage());
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    
    public function view($id)
    {
        $resins = ExitProcessStatus::join('emp_dets', 'exit_process_statuses.empId', 'emp_dets.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->join('users', 'exit_process_statuses.reportingAuthId', 'users.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select(
                'departments.name as departmentName',
                'designations.name as designationName',
                'emp_dets.jobJoingDate',
                'contactus_land_pages.branchName',
                'emp_dets.id as empId',
                'emp_dets.name',
                'emp_dets.name',
                'departments.section',
                'users.name as reportingAuthorityName',
                'emp_dets.empCode',
                'exit_process_statuses.*'
            )
            ->where('exit_process_statuses.id', $id)
            ->first();

        $util = new Utility();
        $commonF = CommonForm::where('active', 1)->first();
      
        
        if($resins)
        {
            if ($resins->section == 'Teaching')
                $noticeP = $commonF->teachingNoticePer;
            else
                $noticeP = $commonF->nonTeachingNoticePer;

            $datetime1 = new DateTime(date('Y-m-d', strtotime($resins->jobJoingDate)));
            $datetime2 = new DateTime(date('Y-m-d', strtotime($resins->expectedLastDate)));
            $interval = $datetime1->diff($datetime2);
            $experience = $interval->format('%y.%m Years');

            $exitProcess1 = ExitReportingAuth::Where('parentId', $id)->first();
            $exitProcess2 = ExitStoreDept::Where('parentId', $id)->first();
            $exitProcess3 = ExitItDept::Where('parentId', $id)->first();
            $exitProcess4 = ExitErpDept::Where('parentId', $id)->first();
            $exitProcess5 = ExitHrDept::Where('parentId', $id)->first();
            $exitProcess6 = ExitTopAuthority::Where('parentId', $id)->first();
            $exitProcess7 = ExitAccountDept::Where('parentId', $id)->first();
        }
        else
        {
            $exitProcess1 = '';
            $exitProcess2 = '';
            $exitProcess3 = '';
            $exitProcess4 = '';
            $exitProcess5 = '';
            $exitProcess6 = '';
            $exitProcess7 = '';
            $experience = '';
            $noticeP = '';
        }

        return view('admin.exitProcess.standardProcess')->with([
            'exitProcess1' => $exitProcess1,
            'exitProcess2' => $exitProcess2,
            'exitProcess3' => $exitProcess3,
            'exitProcess4' => $exitProcess4,
            'exitProcess5' => $exitProcess5,
            'exitProcess6' => $exitProcess6,
            'exitProcess7' => $exitProcess7,
            'experience' => $experience,
            'resins' => $resins,
            'noticeP' => $noticeP
        ]);
    }

    public function printNDC($id)
    {
        $resins = ExitProcessStatus::join('emp_dets', 'exit_process_statuses.empId', 'emp_dets.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select(
            'departments.name as departmentName',
            'designations.name as designationName',
            'emp_dets.jobJoingDate',
            'emp_dets.whatsappNo',
            'emp_dets.phoneNo',
            'contactus_land_pages.branchName',
            'emp_dets.id as empId',
            'emp_dets.name',
            'departments.section',
            'emp_dets.reportingId',
            'emp_dets.reportingType',
            'emp_dets.empCode',
            'emp_dets.lastDate',
            'exit_process_statuses.*'
        )
        ->where('exit_process_statuses.id', $id)
        ->first();

        $datetime1 = new DateTime(date('Y-m-d', strtotime($resins->jobJoingDate)));
        $datetime2 = new DateTime(date('Y-m-d', strtotime($resins->expectedLastDate)));
        $interval = $datetime1->diff($datetime2);
        $experience = $interval->format('%y.%m Years');

        $userDet = User::find($resins->reportingAuthId);

        $exitProcess1 = ExitReportingAuth::Where('parentId', $id)->first();
        $exitProcess2 = ExitStoreDept::Where('parentId', $id)->first();
        $exitProcess3 = ExitItDept::Where('parentId', $id)->first();
        $exitProcess4 = ExitErpDept::Where('parentId', $id)->first();
        $exitProcess5 = ExitHrDept::Where('parentId', $id)->first();
        $exitProcess6 = ExitTopAuthority::Where('parentId', $id)->first();
        $exitProcess7 = ExitAccountDept::Where('parentId', $id)->first();

        $file = $resins->name . '_NDC_' . date('d-M-Y') . '.pdf';
        $pdf = PDF::loadView('admin.exitProcess.ndc', compact('exitProcess1', 'exitProcess2', 'exitProcess3', 'exitProcess4', 'exitProcess5', 'exitProcess6', 'exitProcess7', 'experience', 'userDet', 'resins'));
        return $pdf->stream($file);
    }

    public function terminationProcess()
    {
        return view('admin.exitProcess.terminationProcess');
    }

    public function sabiticalProcess()
    {
        return view('admin.exitProcess.sabiticalProcess');
    }

    public function abscondingProcess()
    {
        return view('admin.exitProcess.abscondingProcess');
    }

    public function search(Request $request)
    {
        $empCode = $request->empCode;
        $fromType = $request->processType;
        $userType = Auth::user()->userType;
        $logEmpId = Auth::user()->empId;
        $resiDet = [];

        if ($empCode != '') {
            $empStatus = ExitProcessStatus::where('empCode', $empCode)->where('status', 0)->first();
            if ($fromType == 1) {
                $resiDet = Ticket::join('emp_dets', 'tickets.empId', 'emp_dets.id')
                    ->join('departments', 'emp_dets.departmentId', 'departments.id')
                    ->join('designations', 'emp_dets.designationId', 'designations.id')
                    ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
                    ->select(
                        'departments.section',
                        'designations.name as designationName',
                        'emp_dets.jobJoingDate',
                        'contactus_land_pages.branchName',
                        'emp_dets.id',
                        'emp_dets.name',
                        'tickets.created_at',
                        'emp_dets.empCode',
                        'emp_dets.reportingId',
                        'emp_dets.firmType',
                        'emp_dets.bankName'
                    )
                    ->where('emp_dets.empCode', $empCode)
                    ->where('tickets.issue', 'RESIGNATION OF EMPLOYEE')
                    ->where('tickets.status', 2)
                    ->first();

                if (!$resiDet)
                    return redirect()->back()->withInput()->with("error", "Resignation Application not found your side");

                if ($resiDet->reportingId != $logEmpId) {
                    $sRepId = EmpDet::where('id', $logEmpId)->value('reportingId');
                    if ($sRepId != $logEmpId)
                        return redirect()->back()->withInput()->with("error", "You have not Authority.....");
                }
            } else {
                if ($logEmpId != "")
                    return redirect()->back()->withInput()->with("error", "You have not Authority.....");

                $resiDet = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
                    ->join('designations', 'emp_dets.designationId', 'designations.id')
                    ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
                    ->select(
                        'departments.section',
                        'designations.name as designationName',
                        'contactus_land_pages.branchName',
                        'emp_dets.*'
                    )
                    ->where('emp_dets.empCode', $empCode)
                    ->first();
            }

            $exitProcess1 = ExitReportingAuth::where('empId', $resiDet->id)->where('status', 1)->first();
            $exitProcess2 = ExitStoreDept::where('empId', $resiDet->id)->where('status', 1)->first();
            $exitProcess3 = ExitItDept::where('empId', $resiDet->id)->where('status', 1)->first();
            $exitProcess4 = ExitErpDept::where('empId', $resiDet->id)->where('status', 1)->first();
            $exitProcess5 = ExitAccountDept::where('empId', $resiDet->id)->where('status', 1)->first();
            $exitProcess6 = ExitHrDept::where('empId', $resiDet->id)->where('status', 1)->first();

            if ($userType == '91') {
                if (!$exitProcess1)
                    return redirect()->back()->withInput()->with("error", "Reporting Authority / HR Department Still in-progress...");
            } elseif ($userType == '71') {
                if (!$exitProcess1) // Reporting Auth
                    return redirect()->back()->withInput()->with("error", "Reporting Authority / HR Department Still in-progress...");
                elseif (!$exitProcess2) // Store Depart 
                    return redirect()->back()->withInput()->with("error", "Store Department Still in-progress...");
            } elseif ($userType == '81') {
                if (!$exitProcess1) // Reporting Auth
                    return redirect()->back()->withInput()->with("error", "Reporting Authority / HR Department Still in-progress...");
                elseif (!$exitProcess2) // Store Depart 
                    return redirect()->back()->withInput()->with("error", "Store Department Still in-progress...");
                elseif (!$exitProcess3) // IT Department
                    return redirect()->back()->withInput()->with("error", "IT Department Still in-progress...");
            } elseif ($userType == '61') {
                if (!$exitProcess1) // Reporting Auth
                    return redirect()->back()->withInput()->with("error", "Reporting Authority / HR Department Still in-progress...");
                elseif (!$exitProcess2) // Store Depart 
                    return redirect()->back()->withInput()->with("error", "Store Department Still in-progress...");
                elseif (!$exitProcess3) // IT Department
                    return redirect()->back()->withInput()->with("error", "IT Department Still in-progress...");
                elseif (!$exitProcess4) // ERP Department
                    return redirect()->back()->withInput()->with("error", "ERP Department Still in-progress...");
            }
        }

        if ($fromType == 1)
            return view('admin.exitProcess.standardProcess')->with([
                'resiDet' => $resiDet,
                'empStatus' => $empStatus,
                'exitProcess1' => $exitProcess1,
                'exitProcess2' => $exitProcess2,
                'exitProcess3' => $exitProcess3,
                'exitProcess4' => $exitProcess4,
                'exitProcess5' => $exitProcess5,
                'exitProcess6' => $exitProcess6
            ]);

        if ($fromType == 2)
            return view('admin.exitProcess.terminationProcess')->with([
                'resiDet' => $resiDet,
                'empStatus' => $empStatus,
                'exitProcess1' => $exitProcess1,
                'exitProcess2' => $exitProcess2,
                'exitProcess3' => $exitProcess3,
                'exitProcess4' => $exitProcess4,
                'exitProcess5' => $exitProcess5,
                'exitProcess6' => $exitProcess6
            ]);

        if ($fromType == 3)
            return view('admin.exitProcess.sabiticalProcess')->with([
                'resiDet' => $resiDet,
                'empStatus' => $empStatus,
                'exitProcess1' => $exitProcess1,
                'exitProcess2' => $exitProcess2,
                'exitProcess3' => $exitProcess3,
                'exitProcess4' => $exitProcess4,
                'exitProcess5' => $exitProcess5,
                'exitProcess6' => $exitProcess6
            ]);

        if ($fromType == 4)
            return view('admin.exitProcess.abscondingProcess')->with([
                'resiDet' => $resiDet,
                'empStatus' => $empStatus,
                'exitProcess1' => $exitProcess1,
                'exitProcess2' => $exitProcess2,
                'exitProcess3' => $exitProcess3,
                'exitProcess4' => $exitProcess4,
                'exitProcess5' => $exitProcess5,
                'exitProcess6' => $exitProcess6
            ]);
    }

    // Work From Home Functions
    public function listWFH(Request $request)
    {
        return view('admin.wfh.list');
    }

    public function createWFH(Request $request)
    {
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        return view('admin.wfh.create')->with(['branches' => $branches]);
    }

    public function displayDates($date1, $date2, $gap, $format = 'Y-m-d')
    {
        $dates = array();
        $current = strtotime($date1);
        $date2 = strtotime($date2);
        $stepVal = '+' . $gap . ' day';
        while ($current <= $date2) {
            $dates[] = date($format, $current);
            $current = strtotime($stepVal, $current);
        }
        return $dates;
    }

    public function storeWFH(Request $request)
    {
        $wfh = new WorkFromHome;
        $wfh->fromDate = $request->fromDate;
        $wfh->toDate = $request->toDate;
        $wfh->wfhType = $request->wfhType;
        $wfh->branchId = $request->branchId;
        $wfh->staffType = $request->staffType;
        $wfh->percent = $request->percent;
        $wfh->remark = $request->remark;
        $wfh->updated_by =  Auth::user()->username;
        if ($wfh->save()) {
            $temps = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id');
            if ($request->branchId != '')
                $temps = $temps->where('emp_dets.branchId', $request->branchId);

            if ($request->staffType != '')
                $temps = $temps->where('departments.section', $request->staffType);

            $empIds = $temps->where('emp_dets.active', 1)->pluck('emp_dets.id');
            $employees = $temps->where('emp_dets.active', 1)->get(['emp_dets.id', 'emp_dets.departmentId']);
            foreach ($employees as $emp) {
                $start_date = $request->fromDate;
                $end_date = $request->toDate;

                while (strtotime($start_date) <= strtotime($end_date)) {
                    $Hday1 = HolidayDept::join('holidays', 'holiday_depts.holidayId', 'holidays.id')
                        ->where('holidays.forDate', $start_date)
                        ->where('holiday_depts.departmentId', $emp->departmentId)
                        ->where('holidays.active', 1)
                        ->count();

                    $Hday2 = HolidayDept::join('holidays', 'holiday_depts.holidayId', 'holidays.id')
                        ->where('holidays.forDate', $start_date)
                        ->where('holiday_depts.branchId', $emp->branchId)
                        ->where('holidays.active', 1)
                        ->count();

                    if ($Hday1 || $Hday2) {
                        $start_date = date("Y-m-d", strtotime("+1 days", strtotime($start_date)));
                    } else {
                        AttendanceDetail::where('forDate', $start_date)
                            ->where('empId', $emp->id)
                            ->update(['percent' => 1]);

                        $start_date = date("Y-m-d", strtotime("+2 days", strtotime($start_date)));
                    }
                }
            }
        }

        return redirect('/hrPolicies/listWFH')->with("success", "Work From Home dates updated successfully..");
    }

    public function editWFH($id, Request $request)
    {
        return view('admin.wfh.list');
    }

    public function updateWFH(Request $request)
    {
        return view('admin.wfh.list');
    }

    public function showWFH($id)
    {
        return view('admin.wfh.list');
    }
}
