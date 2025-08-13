<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\CandidateBankExport;
use App\Exports\ExitPassApplicationsExport;
use App\Exports\LeaveApplicationsExport;
use App\Exports\AGFApplicationsExport;
use App\Exports\TravellingAllowApplicationsExport;
use App\Exports\ContractReportExport;
use App\Exports\RetentionExport;
use App\Exports\PaidLeaveApplicationsExport;

use App\Organisation;
use App\Appraisal;
use App\LogTimeOld;
use App\EmpApplication;
use App\ContactusLandPage;
use App\Retention;
use App\Department;
use App\Designation;
use App\NdcHistory;
use App\Cif3Application;
use App\EmpDet;
use App\AttendanceDetail;
use App\LeavePaymentPolicy;
use App\User;
use App\Ticket;
use Auth;
use DB;
use Excel;
use PDF;
use Carbon\Carbon;
use App\EmpChangeDay;
use App\AttendanceJob;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportsController extends Controller
{
    public function paidLeaveReport(Request $request)
    {
        $paymentDays = $request->paymentDays;
        $policies = LeavePaymentPolicy::where('active', 1)
        ->select('id', DB::raw("CONCAT(section, ' Days - ', paymentDays) as name"))
        ->pluck('name', 'id');

        if($paymentDays != '')
        {
            $policy = LeavePaymentPolicy::find($paymentDays);
            $section = $policy->section;
            $branchId = $policy->branchId;
            $joiningMonth = date('Y-m', strtotime(($policy->yearStatus == 0)?date('Y-', strtotime('-1 year')).$policy->startMonth:(($policy->yearStatus == 1)?date('Y-').$policy->startMonth:date('Y-', strtotime('-1 year')).$policy->startMonth)));
            $endMonth = date('Y-m', strtotime(($policy->yearStatus == 0)?date('Y-', strtotime('-1 year')).$policy->endMonth:(($policy->yearStatus == 1)?date('Y-').$policy->endMonth:date('Y-', strtotime('-1 year')).$policy->endMonth)));
            
            $employees = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select(
                'emp_dets.id', 'emp_dets.jobJoingDate', 'emp_dets.name', 'emp_dets.empCode', 'contactus_land_pages.branchName',
                'departments.name as departmentName', 'designations.name as designationName'
            )
            ->where('emp_dets.active', 1);

            // Optional: Filter only if there's a real joining date
            $employees = $employees->whereNotNull('emp_dets.jobJoingDate')
            ->where('departments.section', $section);

            if (filled($branchId)) {
                $employees->where('emp_dets.branchId', $branchId);
            }

            $employees = $employees->whereBetween(
                DB::raw("DATE_FORMAT(emp_dets.jobJoingDate, '%Y-%m')"),
                [$joiningMonth, $endMonth]
            );
        

            $employees = $employees->orderBy('emp_dets.empCode')
                ->get();

            $branches = ContactusLandPage::where('active', 1)
                ->orderBy('branchName')
                ->pluck('branchName', 'id');

            return view('admin.reports.paidLeaveReport', compact('employees', 'branches', 'joiningMonth', 'section','endMonth', 'branchId','policies','paymentDays'));
        }
        else
        {
            return view('admin.reports.paidLeaveReport', compact('policies','paymentDays'));
        }
    }

    public function exportPaidLeaveReport(Request $request)
    {
        $joiningMonth = $request->joiningMonth;
        return Excel::download(new PaidLeaveApplicationsExport($joiningMonth), 'Paid_Leave_Report.xlsx');
    }

    public function AGFReport(Request $request)
    {
        $user = Auth::user(); 
        $userType = $user->userType; 
        if($user->empId != '')
        {
            if($userType == '11')
            {
                $users1 = EmpDet::where('reportingId', $user->empId)->where('active', 1)->pluck('id');
                $users2 = EmpDet::whereIn('reportingId', $users1)->where('active', 1)->pluck('id');

                $collection = collect($users1);
                $merged = $collection->merge($users2);
                $users = $merged->all();
            }

            if($userType == '21' || $userType == '11')
            {
                $users = EmpDet::where('reportingId', $user->empId)->where('active', 1)->pluck('id');
            }
        }
      
        $branches=ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $departments=Department::where('active', 1)->orderBy('name')->pluck('name', 'id');

        if($request->month == '')
            $month = date('Y-m');
        else
            $month = date('Y-m-d', strtotime($request->month));
          
        $fromDate = date('Y-m-01', strtotime($month));
        $toDate = date('Y-m-t', strtotime($month));

        $applications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.empCode', 'emp_dets.name', 'departments.name as departmentName',
        'contactus_land_pages.branchName','emp_applications.*');
        if($request->branchId != '')
            $applications =$applications->where('emp_dets.branchId', $request->branchId);

        if($request->departmentId != '')
            $applications =$applications->where('emp_dets.departmentId', $request->departmentId);

        if($user->empId != '')
        {
            $applications =$applications->whereIn('emp_dets.id', $users);
        }

        $applications = $applications->where('emp_applications.startDate', '>=', $fromDate)
        ->where('emp_applications.startDate', '<=', $toDate)
        ->where('emp_applications.type', 1)
        ->get(); 
        
        $branchId=$request->branchId;
        $departmentId=$request->departmentId;

        return view('admin.reports.applications.AGFReport')->with(['branches'=>$branches,'departments'=>$departments,
        'month'=>$month, 'applications'=>$applications,'departmentId'=>$departmentId,'branchId'=>$branchId]);
    }

    public function PDFAGFReport($branchId, $departmentId, $fromDate, $toDate)
    {
        $applications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('departments.name as departmentName', 'designations.name as designationName', 
        'emp_dets.name as empName', 'emp_dets.empCode','emp_dets.firmType', 'emp_applications.*')
        ->where('emp_applications.type', 1)
        ->where('emp_applications.active', 1);

        if($departmentId != '0')
        {
            $applications=$applications->where('emp_dets.departmentId', $departmentId);
            $departmentId= Department::where('id', $departmentId)->value('name');
        }
        else
        {
            $departmentId = "All Department";
        }
        
        if($branchId != '0')
        {
            $applications=$applications->where('emp_dets.branchId', $branchId);
            $branchId = ContactusLandPage::where('id', $branchId)->value('branchName');
        }
        else
        {
            $branchId = "All Branch";
        }

        $applications=$applications->where('emp_applications.startDate', '>=', $fromDate)
        ->where('emp_applications.startDate', '<=', $toDate)
        ->orderBy('emp_applications.status')
        ->get();
        
        $file = 'AGFApplications '.date('d-M-Y').'.pdf';
        $pdf = PDF::loadView('admin.reports.applications.AGFApplicationPdfView',compact('applications', 'fromDate', 'toDate', 'departmentId', 'branchId'));
        return $pdf->stream($file);  
    }

    public function excelAGFReport($branchId, $departmentId, $month, $type)
    {
        $fileName = 'AGFApplications '.date('M-Y').'.xlsx';
        $rowCount=0;        

        return Excel::download(new AGFApplicationsExport($branchId, $departmentId, $month, $type, $rowCount), $fileName);
    }

    public function exitPassReport(Request $request)
    { 
        $branches=ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $departments=Department::where('active', 1)->orderBy('name')->pluck('name', 'id');
        if($request->month == '')
            $month = date('Y-m-d');
        else
            $month = date('Y-m-d', strtotime($request->month));
          
        $fromDate = date('Y-m-01', strtotime($month));
        $toDate = date('Y-m-t', strtotime($month));

        $empIds = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id');
        if($request->departmentId != '')
            $empIds=$empIds->where('emp_dets.departmentId', $request->departmentId);

        if($request->branchId != '')
            $empIds=$empIds->where('emp_dets.branchId', $request->branchId);

        $empIds=$empIds->where('emp_applications.type', 2)
        ->where('emp_applications.active', 1)
        ->where('emp_applications.startDate', '>=', $fromDate)
        ->where('emp_applications.startDate', '<=', $toDate)
        ->distinct('emp_applications.empId')
        ->pluck('emp_applications.empId');

        $exitPassApplications = EmpApplication::select(DB::raw('count(id)  as totApp'), 'empId', DB::raw('MONTH(startDate) as forDateMonth'),
        DB::raw('YEAR(startDate) as forDateYear'))
        ->where('type', 2)
        ->where('active', 1)
        ->where('startDate', '>=', $fromDate)
        ->where('startDate', '<=', $toDate)
        ->whereIn('empId', $empIds)
        ->groupBy('empId', DB::raw('MONTH(startDate)'), DB::raw('YEAR(startDate)'))
        ->orderBy('forDateYear','desc')
        ->orderBy('forDateMonth','desc')
        ->orderBy('empId')
        ->get();

        foreach($exitPassApplications as $application)
        {
            $empDet = EmpDet::select('departmentId', 'designationId', 'name', 'firmType', 'empCode')->where('id', $application->empId)->first();
            $application['empName'] = $empDet->name;
            $application['empCode'] = $empDet->empCode;
            $application['firmType'] = $empDet->firmType;
            $application['departmentName'] = Department::where('id', $empDet->departmentId)->value('name');
            $application['designationName'] = Designation::where('id', $empDet->designationId)->value('name');
            $application['pendingCt'] = EmpApplication::where('type', 2)
            ->where('empId', $application->empId)
            ->where('active', 1)
            ->where('status', 0)
            ->where('startDate', '>=', $fromDate)
            ->where('startDate', '<=', $toDate)->count();
            
            $application['totalCt'] = EmpApplication::where('type', 2)
            ->where('empId', $application->empId)
            ->where('active', 1)
            ->where('startDate', '>=', $fromDate)
            ->where('startDate', '<=', $toDate)->count();
        }

        $exitPassApplications = collect($exitPassApplications);
        $exitPassApplications = $exitPassApplications->sortByDesc('pendingCt')->values();

        return view('admin.reports.applications.exitPassReport')->with(['fromDate'=>$fromDate,'toDate'=>$toDate,
        'exitPassApplications'=>$exitPassApplications,
        'branches'=>$branches,'departments'=>$departments, 'month'=>$month]);
    }

    public function viewMore($empId, $forDate, $appType)
    {
        $startDate = date('Y-m', strtotime($forDate)).'-01';
        $endDate = date('Y-m', strtotime($forDate)).'-'.date('t', strtotime($forDate));
       
        $applications = EmpApplication::where('empId', $empId)
        ->where('startDate', '>=', $startDate)
        ->where('startDate', '<=', $endDate)
        ->where('type', $appType)
        ->where('active', 1)
        ->orderBy('created_at')
        ->get();        

        $empDet = EmpDet::select('emp_dets.id','emp_dets.name as empName', 'emp_dets.empCode',
        'emp_dets.reportingId', 'emp_dets.firmType','emp_dets.departmentId', 'emp_dets.designationId')
        ->where('emp_dets.id', $empId)
        ->first();
        $empDet['departmentName'] = Department::where('id', $empDet->departmentId)->value('name');
        $empDet['designationName'] = Designation::where('id', $empDet->designationId)->value('name');
      
        return view('admin.applications.applicationViewList')->with(['startDate'=>$startDate,
        'endDate'=>$endDate,'applications'=>$applications, 
        'empDet'=>$empDet, 'appType'=>$appType]);
    }

    public function PDFExitPassReport($branchId, $departmentId, $fromDate, $toDate)
    {
        $applications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('departments.name as departmentName', 'designations.name as designationName', 
        'emp_dets.name as empName', 'emp_dets.empCode','emp_dets.firmType', 'emp_applications.*')
        ->where('emp_applications.type', 2)
        ->where('emp_applications.active', 1);

        if($departmentId != '0')
        {
            $applications=$applications->where('emp_dets.departmentId', $departmentId);
            $departmentId= Department::where('id', $departmentId)->value('name');
        }
        else
        {
            $departmentId = "All Department";
        }
        
        if($branchId != '0')
        {
            $applications=$applications->where('emp_dets.branchId', $branchId);
            $branchId = ContactusLandPage::where('id', $branchId)->value('name');
        }
        else
        {
            $branchId = "All Branch";
        }

        $applications=$applications->where('emp_applications.startDate', '>=', $fromDate)
        ->where('emp_applications.startDate', '<=', $toDate)
        ->orderBy('emp_applications.status')
        ->get();

        $file = 'ExitApplications '.date('d-M-Y').'.pdf';
        $pdf = PDF::loadView('admin.reports.applications.exitApplicationPdfView',compact('applications', 'fromDate', 'toDate', 'departmentId', 'branchId'));
        return $pdf->stream($file);  
    }

    public function excelExitPassReport($branchId, $departmentId, $fromDate, $toDate)
    {
        $fileName = 'ExitPassApplications '.date('d-M-Y').'.xlsx';
        return Excel::download(new ExitPassApplicationsExport($branchId, $departmentId, $fromDate, $toDate), $fileName);
    }

    public function leaveReport(Request $request)
    {
        $branches=ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $departments=Department::where('active', 1)->orderBy('name')->pluck('name', 'id');
        if($request->month == '')
            $month = date('Y-m-d');
        else
            $month = date('Y-m-d', strtotime($request->month));
          
        $fromDate = date('Y-m-01', strtotime($month));
        $toDate = date('Y-m-t', strtotime($month));

        $empIds = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id');
        if($request->departmentId != '')
            $empIds=$empIds->where('emp_dets.departmentId', $request->departmentId);

        if($request->branchId != '')
            $empIds=$empIds->where('emp_dets.branchId', $request->branchId);

        $empIds=$empIds->where('emp_applications.type', 3)
        ->where('emp_applications.active', 1)
        ->where('emp_applications.startDate', '>=', $fromDate)
        ->where('emp_applications.endDate', '<=', $toDate)
        ->distinct('emp_applications.empId')
        ->pluck('emp_applications.empId');

        $applications = EmpApplication::select(DB::raw('count(id)  as totApp'), 'empId', DB::raw('MONTH(startDate) as forDateMonth'),
        DB::raw('YEAR(startDate) as forDateYear'))
        ->where('type', 3)
        ->where('active', 1)
        ->where('startDate', '>=', $fromDate)
        ->where('endDate', '<=', $toDate)
        ->whereIn('empId', $empIds)
        ->groupBy('empId', DB::raw('MONTH(startDate)'), DB::raw('YEAR(startDate)'))
        ->orderBy('forDateYear','desc')
        ->orderBy('forDateMonth','desc')
        ->orderBy('empId')
        ->get();

        foreach($applications as $application)
        {
            $empDet = EmpDet::select('departmentId', 'designationId', 'name', 'firmType', 'empCode')->where('id', $application->empId)->first();
            $application['empName'] = $empDet->name;
            $application['empCode'] = $empDet->empCode;
            $application['firmType'] = $empDet->firmType;
            $application['departmentName'] = Department::where('id', $empDet->departmentId)->value('name');
            $application['designationName'] = Designation::where('id', $empDet->designationId)->value('name');
            $application['pendingCt'] = EmpApplication::where('type', 3)
            ->where('empId', $application->empId)
            ->where('active', 1)
            ->where('status', 0)
            ->where('startDate', '>=', $fromDate)
            ->where('startDate', '<=', $toDate)->count();
            
            $application['totalCt'] = EmpApplication::where('type', 3)
            ->where('empId', $application->empId)
            ->where('active', 1)
            ->where('startDate', '>=', $fromDate)
            ->where('startDate', '<=', $toDate)->count();
        }

        $applications = collect($applications);
        $applications = $applications->sortByDesc('pendingCt')->values();

        return view('admin.reports.applications.leaveReport')->with(['fromDate'=>$fromDate,'toDate'=>$toDate,
        'applications'=>$applications, 'branches'=>$branches,'departments'=>$departments, 'month'=>$month]);
    }

    public function PDFLeaveReport($branchId, $departmentId, $fromDate, $toDate)
    {
        $applications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('departments.name as departmentName', 'designations.name as designationName', 
        'emp_dets.name as empName', 'emp_dets.empCode','emp_dets.firmType', 'emp_applications.*')
        ->where('emp_applications.type', 3)
        ->where('emp_applications.active', 1);

        if($departmentId != '0')
        {
            $applications=$applications->where('emp_dets.departmentId', $departmentId);
            $departmentId= Department::where('id', $departmentId)->value('name');
        }
        else
        {
            $departmentId = "All Department";
        }
        
        if($branchId != '0')
        {
            $applications=$applications->where('emp_dets.branchId', $branchId);
            $branchId = ContactusLandPage::where('id', $branchId)->value('name');
        }
        else
        {
            $branchId = "All Branch";
        }

        $applications=$applications->where('emp_applications.startDate', '>=', $fromDate)
        ->where('emp_applications.startDate', '<=', $toDate)
        ->orderBy('emp_applications.status')
        ->get();

        $file = 'LeaveApplications '.date('d-M-Y').'.pdf';
        $pdf = PDF::loadView('admin.reports.applications.leaveApplicationPdfView',compact('applications', 'fromDate', 'toDate', 'departmentId', 'branchId'));
        return $pdf->stream($file);  
    }

    public function excelLeaveReport($branchId, $departmentId, $fromDate, $toDate)
    {
        $fileName = 'LeaveApplications '.date('d-M-Y').'.xlsx';
        return Excel::download(new LeaveApplicationsExport($branchId, $departmentId, $fromDate, $toDate), $fileName);
    }

    public function travellingAllowReport(Request $request)
    {
        $branches=ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $departments=Department::where('active', 1)->orderBy('name')->pluck('name', 'id');
        if($request->month == '')
            $month = date('Y-m-d');
        else
            $month = date('Y-m-d', strtotime($request->month));
          
        $fromDate = date('Y-m-01', strtotime($month));
        $toDate = date('Y-m-t', strtotime($month));

        $empIds = EmpDet::where('active', 1);
        if($request->departmentId != '')
            $empIds=$empIds->where('departmentId', $request->departmentId);
        
        if($request->branchId != '')
            $empIds=$empIds->where('branchId', $request->branchId);

        $empIds=$empIds->pluck('id');

        $empIds = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->where('emp_applications.type', 4)
        ->where('emp_applications.active', 1)
        ->where('emp_applications.startDate', '>=', $fromDate)
        ->where('emp_applications.startDate', '<=', $toDate)
        ->distinct('emp_applications.empId')
        ->pluck('emp_applications.empId');

        $travelApplications = EmpApplication::select(DB::raw('count(id)  as totApp'), 'empId', DB::raw('MONTH(startDate) as forDateMonth'),
        DB::raw('YEAR(startDate) as forDateYear'))
        ->where('type', 4)
        ->where('active', 1)
        ->where('startDate', '>=', $fromDate)
        ->where('startDate', '<=', $toDate)
        ->whereIn('empId', $empIds)
        ->groupBy('empId', DB::raw('MONTH(startDate)'), DB::raw('YEAR(startDate)'))
        ->orderBy('forDateYear','desc')
        ->orderBy('forDateMonth','desc')
        ->orderBy('empId')
        ->get();

        foreach($travelApplications as $application)
        {
            $empDet = EmpDet::select('departmentId', 'designationId', 'name', 'firmType', 'empCode')->where('id', $application->empId)->first();
            $application['empName'] = $empDet->name;
            $application['empCode'] = $empDet->empCode;
            $application['firmType'] = $empDet->firmType;
            $application['departmentName'] = Department::where('id', $empDet->departmentId)->value('name');
            $application['designationName'] = Designation::where('id', $empDet->designationId)->value('name');
            $application['pendingCt'] = EmpApplication::where('type', 4)
            ->where('empId', $application->empId)
            ->where('active', 1)
            ->where('status', 0)
            ->where('startDate', '>=', $fromDate)
            ->where('startDate', '<=', $toDate)->count();
            
            $application['totalCt'] = EmpApplication::where('type', 4)
            ->where('empId', $application->empId)
            ->where('active', 1)
            ->where('startDate', '>=', $fromDate)
            ->where('startDate', '<=', $toDate)->count();
        }

        $travelApplications = collect($travelApplications);
        $travellingApplications = $travelApplications->sortByDesc('pendingCt')->values();

        return view('admin.reports.applications.travellingAllowReport')->with(['fromDate'=>$fromDate,'toDate'=>$toDate,'travellingApplications'=>$travellingApplications,
        'branches'=>$branches,'departments'=>$departments, 'month'=>$month]);
        
    }

    public function PDFTravellingAllowReport($branchId, $departmentId, $fromDate, $toDate)
    {
        $applications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('departments.name as departmentName', 'designations.name as designationName', 
        'emp_dets.name as empName', 'emp_dets.empCode','emp_dets.firmType', 'emp_applications.*')
        ->where('emp_applications.type', 4)
        ->where('emp_applications.active', 1);

        if($departmentId != '0')
        {
            $applications=$applications->where('emp_dets.departmentId', $departmentId);
            $departmentId= Department::where('id', $departmentId)->value('name');
        }
        else
        {
            $departmentId = "All Department";
        }
        
        if($branchId != '0')
        {
            $applications=$applications->where('emp_dets.branchId', $branchId);
            $branchId = ContactusLandPage::where('id', $branchId)->value('branchName');
        }
        else
        {
            $branchId = "All Branch";
        }

        $applications=$applications->where('emp_applications.startDate', '>=', $fromDate)
        ->where('emp_applications.startDate', '<=', $toDate)
        ->orderBy('emp_applications.status')
        ->get();

        foreach($applications as $app)
        {
            $fromDest = ContactusLandPage::where('id', $app->fromDest)->value('branchName');
            $app['fromDest'] = ($fromDest == '')?$app->fromDest:$fromDest;

            $toDest = ContactusLandPage::where('id', $app->toDest)->value('branchName');
            $app['toDest'] = ($toDest == '')?$app->toDest:$toDest;
        }

        $file = 'LeaveApplications '.date('d-M-Y').'.pdf';
        $pdf = PDF::loadView('admin.reports.applications.travellingAllowReportPdfView',compact('applications', 'fromDate', 'toDate', 'departmentId', 'branchId'));
        return $pdf->stream($file);  
    }

    public function excelTravellingAllowReport($branchId, $departmentId, $fromDate, $toDate)
    {
        $fileName = 'TravellingAllowApplications '.date('d-M-Y').'.xlsx';
        return Excel::download(new TravellingAllowApplicationsExport($branchId, $departmentId, $fromDate, $toDate), $fileName);
    }

    public function extraWorkingReport(Request $request)
    {
        $branches=ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $departments=Department::where('active', 1)->orderBy('name')->pluck('name', 'id');
        if($request->fromDate == '' || $request->toDate == '')
        {
            $fromDate = date('Y-m-d', strtotime('-6 days'));
            $toDate = date('Y-m-d');
        }
        else
        {
            $fromDate = date('Y-m-d', strtotime($request->fromDate));
            $toDate = date('Y-m-d', strtotime($request->toDate));
        }


        $extraWorks = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
        ->where('attendance_details.extraWorkingDay', 1);
      
        if($request->departmentId != '')
            $extraWorks=$extraWorks->where('emp_dets.departmentId', $request->departmentId);
        
        if($request->branchId != '')
            $extraWorks=$extraWorks->where('emp_dets.branchId', $request->branchId);

        if($request->organisation != '')
            $extraWorks=$extraWorks->where('emp_dets.organisation', $request->organisation);

        $extraWorks=$extraWorks->where('attendance_details.forDate', '>=', $fromDate)
        ->where('attendance_details.forDate', '<=', $toDate)
        ->pluck('emp_dets.id');

        
       
        $emps = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('departments.name as departmentName', 'designations.name as designationName',
        'emp_dets.id', 'emp_dets.empCode','emp_dets.organisation', 'emp_dets.name as empName', 'contactus_land_pages.branchName','emp_dets.firmType')
        ->whereIn('emp_dets.id', $extraWorks)
        ->orderBy('emp_dets.empCode')
        ->get();

        foreach($emps as $emp)
        {
            $extraWorks = AttendanceDetail::select(DB::raw('count(extraWorkingDay) as totalDays'))
            ->where('extraWorkingDay', 1)
            ->where('empId', $emp->id)
            ->where('forDate', '>=', $fromDate)
            ->where('forDate', '<=', $toDate)
            ->value('totalDays');
            $emp['totalDays'] = $extraWorks;
        }

        return view('admin.reports.extraWorkingReport')->with(['fromDate'=>$fromDate,'toDate'=>$toDate,
        'emps'=>$emps,'branchId'=>$request->branchId,'departmentId'=>$request->departmentId,'branches'=>$branches,'departments'=>$departments]);
    }

    public function extraWorkingReportDet($empId, $fromDate, $endDate)
    {
        $extraWorks = AttendanceDetail::where('extraWorkingDay', 1)
        ->where('empId', $empId)
        ->where('forDate', '>=', $fromDate)
        ->where('forDate', '<=', $endDate)
        ->get();

        $emp = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('departments.name as departmentName', 'designations.name as designationName',
        'emp_dets.empCode','emp_dets.organisation', 'emp_dets.name', 'contactus_land_pages.branchName',
        'emp_dets.firmType')->where('emp_dets.id', $empId)->first();

        return view('admin.reports.extraWorkingDetail')->with(['emp'=>$emp,'fromDate'=>$fromDate, 'endDate'=>$endDate, 'extraWorks'=>$extraWorks]);
    }

    public function showStatusReport($id, $type)
    {
        $application = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('departments.name as departmentName', 'designations.name as designationName', 
        'emp_dets.name as empName', 'emp_dets.reportingId','emp_dets.empCode','emp_dets.firmType',
        'contactus_land_pages.branchName', 'emp_applications.*')
        ->where('emp_applications.id', $id)
        ->first();

        $fromDest = ContactusLandPage::where('id', $application->fromDest)->value('branchName');
        $toDest = ContactusLandPage::where('id', $application->toDest)->value('branchName');

        $repoName = EmpDet::where('id', $application->reportingId)->value('name');
        if($repoName == '')
            $repoName = User::where('id', $application->reportingId)->value('name');

        $application['reportingName']=$repoName;
        
        return view('admin.applications.showStatusReport')->with(['application'=>$application, 'type'=>$type, 'toDest'=>$toDest, 'fromDest'=>$fromDest]);
    }

    public function pendingInfo(Request $request)
    {
        $option = $request->options;

        // $emps = EmpDet::where('active', 1)->get();
        // foreach($emps as $emp)
        // {
        //     if($emp->branchId != NULL)
        //     {
        //         $branchCt = ContactusLandPage::where('id', $emp->branchId)->count();
        //         if($branchCt == 0)
        //         {
        //             $temp = EmpDet::find($emp->id);
        //             $temp->branchId = NULL;
        //             $temp->save();
        //         }
        //     }

        //     if($emp->departmentId != NULL)
        //     {
        //         $deptCt = Department::where('id', $emp->departmentId)->count();
        //         if($deptCt==0)
        //         {
        //             $temp = EmpDet::find($emp->id);
        //             $temp->departmentId = NULL;
        //             $temp->save();
        //         }
        //     }
            
        //     if($emp->designationId != NULL)
        //     {
        //         $desCt = Designation::where('id', $emp->designationId)->count();
        //         if($desCt==0)
        //         {
        //             $temp = EmpDet::find($emp->id);
        //             $temp->designationId = NULL;
        //             $temp->save();
        //         }
        //     }
        // }


        if($option == '')
            return view('admin.reports.pendingInfo');
        else
        {
            $pendingInfos = EmpDet::where('active', 1);
            if($option == 1)
                $pendingInfos=$pendingInfos->where('DOB', NULL);   
            elseif($option == 2)
                $pendingInfos=$pendingInfos->where('gender', NULL);   
            elseif($option == 3)
                $pendingInfos=$pendingInfos->where('branchId', NULL);   
            elseif($option == 5)
                $pendingInfos=$pendingInfos->where('departmentId', NULL);   
            elseif($option == 6)
                $pendingInfos=$pendingInfos->where('designationId', NULL);   
            elseif($option == 7)
                $pendingInfos=$pendingInfos->where('reportingId', NULL);   
            elseif($option == 8)
                $pendingInfos=$pendingInfos->where('jobJoingDate', NULL);   
            else
                $pendingInfos=$pendingInfos->where('contractStartDate', NULL);   

            $pendingInfos=$pendingInfos->orderBy('empCode')->paginate(10);
            return view('admin.reports.pendingInfo')->with(['pendingInfos'=>$pendingInfos,'option'=>$option]);
        }

    }

    public function recruitementReport(Request $request)
    {
        $applicationType = $request->applicationType;
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $status = $request->status;
        if($request->fromDate == '' || $request->toDate == '')
        {
            $fromDate = date('Y-m-d', strtotime('-6 days'));
            $toDate = date('Y-m-d');
        }
        else
        {
            $fromDate = date('Y-m-d', strtotime($request->fromDate));
            $toDate = date('Y-m-d', strtotime($request->toDate));
        }

        if($applicationType != '')
        {
            if($applicationType == 0)
            {
                $applications = Cif3Application::join('departments', 'cif3_applications.departmentId', 'departments.id')
                ->join('designations', 'cif3_applications.designationId', 'designations.id')
                ->select('departments.name as departmentName','designations.name as designationName',
                    'cif3_applications.*')
                ->where('cif3_applications.appType', $applicationType)
                ->where('cif3_applications.status', 1)
                ->whereDate('cif3_applications.created_at', '>=', $fromDate)
                ->whereDate('cif3_applications.created_at', '<=', $toDate);
            }
            else
            {
                $applications = Cif3Application::join('departments', 'cif3_applications.departmentId', 'departments.id')
                ->join('designations', 'cif3_applications.designationId', 'designations.id')
                ->select('departments.name as departmentName','designations.name as designationName','cif3_applications.*')
                ->where('cif3_applications.appType', $applicationType)
                ->where('cif3_applications.status', 1)
                ->where('cif3_applications.forDate', '>=', $fromDate)
                ->where('cif3_applications.forDate', '<=', $toDate);
            }

            if($status != '')
                $applications =$applications->where('cif3_applications.appStatus', $status);
            
            $applications =$applications->paginate(50)
            ->appends(['toDate' => $toDate, 'fromDate' => $fromDate, 'applicationType'=>$applicationType, 'status'=>$status]);

            return view('admin.reports.recruitementReport')->with(['applications'=>$applications,'fromDate'=>$fromDate,'toDate'=>$toDate,'applicationType'=>$applicationType,'status'=>$status]);
        }
        else
        {
            return view('admin.reports.recruitementReport')->with(['fromDate'=>$fromDate,'toDate'=>$toDate]);
        }
    }

    public function pdfRecruitementReport($page, $applicationType, $status, $fromDate, $toDate)
    {
        if($applicationType == 0)
        {
            $applications = JobApplication::join('departments', 'job_applications.departmentId', 'departments.id')
            ->join('designations', 'job_applications.designationId', 'designations.id')
            ->select('departments.name as departmentName','designations.name as designationName',
                'job_applications.*')
            ->where('job_applications.appType', $applicationType)
            ->where('job_applications.status', 1)
            ->whereDate('job_applications.created_at', '>=', $fromDate)
            ->whereDate('job_applications.created_at', '<=', $toDate);
        }
        else
        {
            $applications = JobApplication::join('departments', 'job_applications.departmentId', 'departments.id')
            ->join('designations', 'job_applications.designationId', 'designations.id')
            ->select('departments.name as departmentName','designations.name as designationName','job_applications.*')
            ->where('job_applications.appType', $applicationType)
            ->where('job_applications.status', 1)
            ->where('job_applications.forDate', '>=', $fromDate)
            ->where('job_applications.forDate', '<=', $toDate);
        }
        

        if($status == 'CBC' || $status == 'Rejected' || $status == 'Selected')
            $applications =$applications->where('job_applications.appStatus', $status);
        else
            $status=0;
        
        $applications = $applications->get(); 
        
        $file = 'CandidateBank '.date('d-M-Y').'.pdf';
        $pdf = PDF::loadView('admin.reports.candidateBankPdfView',compact('applications', 'fromDate', 'toDate', 'status', 'applicationType'));
        return $pdf->stream($file);  
    }

    public function excelRecruitementReport($page, $applicationType, $status, $fromDate, $toDate)
    {
        $fileName = 'CandidateBank '.date('d-M-Y').'.xlsx';
        $rowCount=0;
        return Excel::download(new CandidateBankExport($page, $applicationType, $status, $fromDate, $toDate, $rowCount), $fileName);
    }

    public function attendanceReport(Request $request)
    {
        // $leaveApps = EmpApplication::where('type', 3)
        // ->where('startDate', '>=', date('2025-07-01'))
        // ->where('startDate', '<=', date('2025-07-31'))
        // ->where('active', 1)
        // ->where('tempStatus', 0)
        // ->take(100)
        // ->get();
        // foreach($leaveApps as $row)
        // {
        //     for($i=$row->startDate; $i<=$row->endDate; $i++)
        //     {
        //         AttendanceDetail::where('forDate', $i)->where('empId', $row->empId)->update(['leaveStatus'=>$row->id]);
        //     }

        //     $row->tempStatus=1;
        //     $row->save();
        // }

        // return '';
        // leaveStatus
        // --- 1. SETUP AND VALIDATION ---
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $finalMonth = $request->input('start_month'); // Changed from 'month' to 'start_month' to match view
        $userType = Auth::user()->userType;
        $searchQuery = $request->input('search_query');
        $reportType = $request->input('report_type'); // New: Report Type dropdown value
        $policy = $request->input('policy'); // New: Policy (for WL Report) dropdown value
        $issueType = $request->input('issue_type'); // New: Issue Type (for AGF Report) dropdown value
        $section = $request->input('section'); // Ensure section is captured from the request

        // If essential parameters are missing, return to the view without data
        if (!$request->filled('start_month')) {
            return view('admin.reports.attendanceReport')->with([
                'attendances' => null,
                'branches' => $branches,
                'userType' => $userType,
                'section' => $section, // Pass section back for form persistence
                'branchId' => $request->branchId,
                'finalMonth' => $finalMonth,
                'searchQuery' => $searchQuery,
                'report_type' => $reportType, // Pass back for old() value in view
                'policy' => $policy, // Pass back for old() value in view
                'issue_type' => $issueType, // Pass back for old() value in view
                'start_month' => $finalMonth, // Pass back start_month for old() value in view
            ]);
        }

        // Validate month format
        try {
            $carbonDate = Carbon::createFromFormat('Y-m', $finalMonth)->startOfMonth();
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with("error", "Invalid month format provided.");
        }

        $startDate = $carbonDate->copy()->format('Y-m-d');
        $daysInMonth = $carbonDate->daysInMonth;

        // Determine the end date based on current month or full month
        if ($finalMonth == date('Y-m')) {
            $endDate = Carbon::now()->format('Y-m-d');
        } else {
            $endDate = $carbonDate->copy()->endOfMonth()->format('Y-m-d');
        }

        // --- 2. AUTHORIZATION CHECKS ---
        // Check confirmation status based on user type
        if ($userType == '501') {
            $attendanceConfStatus = AttendanceJob::where('userType', '51')
                ->where('fBranchId', $request->branchId)
                ->where('fMonth', $finalMonth)
                ->where('section', $section)
                ->count();
            if (!$attendanceConfStatus) {
                return redirect()->back()->withInput()->with("error", "HR Department Still not confirmed Selected Branch...");
            }
        }

        if ($userType == '61') {
            $attendanceConfStatus = AttendanceJob::where('userType', '501')
                ->where('fBranchId', $request->branchId)
                ->where('fMonth', $finalMonth)
                ->where('section', $section)
                ->count();
            if (!$attendanceConfStatus) {
                return redirect()->back()->withInput()->with("error", "Higher Authority Still not confirmed Selected Branch...");
            }
        }

        // --- 3. EFFICIENT DATABASE QUERY ---
        // Join necessary tables to retrieve comprehensive attendance and employee data
        $allAttendances = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select(
            'attendance_details.*',
            'emp_dets.name',
            'emp_dets.empCode',
            'emp_dets.jobJoingDate',
            'emp_dets.lastDate',
            'emp_dets.startTime',
            'emp_dets.endTime',
            'emp_dets.id as attendEmpId',
            'designations.name as designationName',
            'contactus_land_pages.branchName'
        )
        ->whereBetween('attendance_details.forDate', [$startDate, $endDate]);
        if($request->branchId != '')
            $allAttendances = $allAttendances->where('emp_dets.branchId', $request->branchId);

        $allAttendances = $allAttendances->when($section, function ($q, $section) {
            return $q->where('departments.section', $section);
        })
        ->orderBy('emp_dets.empCode')->orderBy('attendance_details.forDate')
        ->get();

        // If no attendance records are found, return an error
        if ($allAttendances->isEmpty()) {
            return redirect()->back()->withInput()->with("error", "Record Not Found.");
        }

        // Get unique employee IDs and their day change data
        $employeeIds = $allAttendances->pluck('attendEmpId')->unique();
        $dayChanges = EmpChangeDay::where('month', $finalMonth)
            ->whereIn('empId', $employeeIds)
            ->get()
            ->keyBy('empId');

        // --- 4. PROCESS DATA WITH ALL BUSINESS RULES ---
        $attendancesByEmployee = $allAttendances->groupBy('empId');
        $processedEmployees = collect();

        foreach ($attendancesByEmployee as $empId => $employeeDays) {
            $employeeInfo = $employeeDays->first();

            // Parse joining and last dates for employment period checks
            $joiningDate = $employeeInfo->jobJoingDate ? Carbon::parse($employeeInfo->jobJoingDate)->startOfDay() : null;
            $lastWorkingDate = $employeeInfo->lastDate ? Carbon::parse($employeeInfo->lastDate)->startOfDay() : null;

            $dailyDataMap = $employeeDays->keyBy(function ($day) {
                return Carbon::parse($day->forDate)->format('Y-m-d');
            });

            $processedDailyStatus = [];
            $sandwitchDayDed = 0;
            $weeklyRuleDeductions = 0.0;
            $lateMarkCount = 0;

            // =========================================================================
            // STEP 1: PRELIMINARY AND SANDWICH RULE PROCESSING
            // =========================================================================
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $currentDate = $carbonDate->copy()->day($d)->startOfDay();

                // Check if the day is outside the employment period.
                if (($joiningDate && $currentDate->lt($joiningDate)) || ($lastWorkingDate && $currentDate->gt($lastWorkingDate))) {
                    $processedDailyStatus[$d] = (object)['status' => 'NE', 'dayData' => null];
                    continue; // Skip all rule processing for this day.
                }

                $dayData = $dailyDataMap->get($currentDate->format('Y-m-d'));
                if (!$dayData) {
                    $processedDailyStatus[$d] = null;
                    continue;
                }
                $finalStatus = $dayData->dayStatus;

                // Sandwich Rule A: If 1st day of month is WO/LH and only few days present in month
                if ($d == 1 && in_array($finalStatus, ['WO', 'LH'])) {
                    $otherDays = $employeeDays->filter(function ($day) use ($currentDate) {
                        return $day->forDate != $currentDate->format('Y-m-d');
                    });
                    if ($otherDays->isNotEmpty()) {
                        $presentDaysCount = $otherDays->filter(function ($day) {
                            return in_array($day->dayStatus, ['P', 'PL', 'PH', 'PLH']);
                        })->count();
                        if ($presentDaysCount <= 2) { // Adjusted logic as per original
                            $finalStatus = 'A';
                            $sandwitchDayDed++;
                        }
                    }
                }

                // Sandwich Rule B: If WO/LH is sandwiched between two absent days
                if (in_array($finalStatus, ['WO', 'LH'])) {
                    $firstWorkingDayBefore = null;
                    $firstWorkingDayAfter = null;
                    // Look backward for the first non-WO/LH day
                    for ($i = $d - 1; $i >= 1; $i--) {
                        $prevDayStatus = $processedDailyStatus[$i] ?? null;
                        if ($prevDayStatus && !in_array($prevDayStatus->status, ['WO', 'LH', 'NE'])) {
                            $firstWorkingDayBefore = $prevDayStatus;
                            break;
                        }
                    }
                    // Look forward for the first non-WO/LH day
                    for ($i = $d + 1; $i <= $daysInMonth; $i++) {
                        $nextDay = $dailyDataMap->get($carbonDate->copy()->day($i)->format('Y-m-d'));
                        if ($nextDay && !in_array($nextDay->dayStatus, ['WO', 'LH'])) {
                            $firstWorkingDayAfter = (object)['status' => $nextDay->dayStatus, 'dayData' => $nextDay];
                            break;
                        }
                    }
                    // Apply sandwich deduction if both surrounding days are unapproved absent
                    if (
                        $firstWorkingDayBefore && $firstWorkingDayAfter &&
                        (in_array($firstWorkingDayBefore->status, ['A', '0']) && ($firstWorkingDayBefore->dayData ? $firstWorkingDayBefore->dayData->AGFStatus == 0 : true)) &&
                        (in_array($firstWorkingDayAfter->status, ['A', '0']) && ($firstWorkingDayAfter->dayData ? $firstWorkingDayAfter->dayData->AGFStatus == 0 : true))
                    ) {
                        $finalStatus = 'A';
                        $sandwitchDayDed++;
                    }
                }
                $processedDailyStatus[$d] = (object)['status' => $finalStatus, 'dayData' => $dayData];
            }

            // =========================================================================
            // >>> NEW LOGIC START: START-OF-MONTH HOLIDAY RULE <<<
            // If Day 1 is a holiday and next 5 days are absent, Day 1 becomes absent
            // =========================================================================
            if ($daysInMonth >= 6) { // Ensure enough days to check next 5
                $day1Info = $processedDailyStatus[1] ?? null;
                $isDay1Holiday = $day1Info && in_array($day1Info->status, ['WO', 'LH', 'H']);

                $areNext5DaysAbsent = true;
                for ($i = 2; $i <= 6; $i++) {
                    $dayInfo = $processedDailyStatus[$i] ?? null;
                    if (!$dayInfo || !in_array($dayInfo->status, ['A', '0']) || ($dayInfo->dayData && $dayInfo->dayData->AGFStatus != 0)) {
                        $areNext5DaysAbsent = false;
                        break;
                    }
                }

                if ($isDay1Holiday && $areNext5DaysAbsent) {
                    if ($processedDailyStatus[1]) {
                        $processedDailyStatus[1]->status = 'A';
                        $sandwitchDayDed++;
                    }
                }
            }
            // >>> NEW LOGIC END <<<

            // =========================================================================
            // >>> NEW LOGIC START: Mark weekend as absent if Mon-Fri was absent <<<
            // If Monday to Friday are all unapproved absent, then Saturday and Sunday (if WO/LH/H) become absent
            // =========================================================================
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $currentDate = $carbonDate->copy()->day($d);

                if ($currentDate->dayOfWeek == Carbon::SATURDAY) {
                    $mondayIndex = $d - 5;
                    $fridayIndex = $d - 1;

                    // Ensure the week is within the month bounds
                    if ($mondayIndex < 1) {
                        continue;
                    }

                    $isFullWeekAbsent = true;
                    for ($i = $mondayIndex; $i <= $fridayIndex; $i++) {
                        $dayInfo = $processedDailyStatus[$i] ?? null;
                        // Check if the day is absent and not approved (AGFStatus == 0)
                        if (!$dayInfo || !in_array($dayInfo->status, ['A', '0']) || ($dayInfo->dayData && $dayInfo->dayData->AGFStatus != 0)) {
                            $isFullWeekAbsent = false;
                            break;
                        }
                    }

                    if ($isFullWeekAbsent) {
                        // Mark Saturday as absent if it's a holiday/WO
                        $saturdayInfo = $processedDailyStatus[$d] ?? null;
                        if ($saturdayInfo && in_array($saturdayInfo->status, ['WO', 'LH', 'H'])) {
                            $saturdayInfo->status = 'A';
                            $sandwitchDayDed++;
                        }

                        // Mark Sunday as absent if it's a holiday/WO
                        $sundayIndex = $d + 1;
                        if ($sundayIndex <= $daysInMonth) {
                            $sundayInfo = $processedDailyStatus[$sundayIndex] ?? null;
                            if ($sundayInfo && in_array($sundayInfo->status, ['WO', 'LH', 'H'])) {
                                if ($processedDailyStatus[$sundayIndex]) {
                                    $processedDailyStatus[$sundayIndex]->status = 'A';
                                    $sandwitchDayDed++;
                                }
                            }
                        }
                    }
                }
            }
            // >>> NEW LOGIC END <<<


            // =========================================================================
            // STEP 2: WEEKLY ABSENCE RULE WITH HOLIDAY DEDUCTION
            // This rule converts WO to A or PH if too many absences in the preceding 6 days.
            // =========================================================================
            $weeklyConfig = [
                'ABSENT' => ['A', '0'],
                'HALF_DAY' => ['PH', 'PLH'],
                'HOLIDAY' => ['H', 'LH'],
                'WEEKLY_OFF' => 'WO',
                'STANDARD_WORK_DAYS' => 6, // Standard working days in a week (Mon-Sat)
                'ABSENT_THRESHOLD_RATIO' => 3.5 / 6, // More than 3.5 absent days in 6 working days
                'HALF_DAY_THRESHOLD_RATIO' => 3.0 / 6, // More than 3.0 absent days in 6 working days
            ];

            for ($d = 1; $d <= $daysInMonth; $d++) {
                $currentDate = $carbonDate->copy()->day($d);
                // Apply rule only on Sundays (Weekly Offs)
                if ($currentDate->dayOfWeek == Carbon::SUNDAY) {
                    $sundayIndex = $d;
                    $sundayStatusInfo = $processedDailyStatus[$sundayIndex] ?? null;

                    // Only process if the Sunday is a Weekly Off
                    if ($sundayStatusInfo && $sundayStatusInfo->status == $weeklyConfig['WEEKLY_OFF']) {
                        $startOfWeek = $currentDate->copy()->subDays(6); // Monday of the current week
                        $endOfWeek = $currentDate->copy()->subDay(); // Saturday of the current week
                        $weeklyAbsenceCount = 0.0;
                        $weeklyHolidayCount = 0;

                        // Iterate through the 6 preceding days (Monday to Saturday)
                        for ($weekDay = $startOfWeek->copy(); $weekDay->lte($endOfWeek); $weekDay->addDay()) {
                            // Skip days outside the current month
                            if (!$weekDay->isSameMonth($carbonDate)) continue;

                            $statusInfo = $processedDailyStatus[$weekDay->day] ?? null;
                            if (!$statusInfo) continue;

                            // Count full absences (A, 0) and half-absences (PH, PLH) if not approved by AGF
                            if (in_array($statusInfo->status, $weeklyConfig['ABSENT']) && ($statusInfo->dayData && $statusInfo->dayData->AGFStatus == 0)) {
                                $weeklyAbsenceCount += 1.0;
                            } elseif (in_array($statusInfo->status, $weeklyConfig['HALF_DAY']) && ($statusInfo->dayData && $statusInfo->dayData->AGFStatus == 0)) {
                                $weeklyAbsenceCount += 0.5;
                            } elseif (in_array($statusInfo->status, $weeklyConfig['HOLIDAY'])) {
                                $weeklyHolidayCount++;
                            }
                        }

                        // Calculate actual working days in the week
                        $actualWorkDays = $weeklyConfig['STANDARD_WORK_DAYS'] - $weeklyHolidayCount;
                        if ($actualWorkDays > 0) {
                            $absentThreshold = $weeklyConfig['ABSENT_THRESHOLD_RATIO'] * $actualWorkDays;
                            $halfDayThreshold = $weeklyConfig['HALF_DAY_THRESHOLD_RATIO'] * $actualWorkDays;

                            // Apply deductions based on thresholds
                            if ($weeklyAbsenceCount >= $absentThreshold) {
                                if ($processedDailyStatus[$sundayIndex]) {
                                    $processedDailyStatus[$sundayIndex]->status = 'A'; // Convert WO to Absent
                                    $weeklyRuleDeductions += 1.0;
                                }
                            } elseif ($weeklyAbsenceCount >= $halfDayThreshold) {
                                if ($processedDailyStatus[$sundayIndex]) {
                                    $processedDailyStatus[$sundayIndex]->status = 'PH'; // Convert WO to Half Present
                                    $weeklyRuleDeductions += 0.5;
                                }
                            }
                        }
                    }
                }
            }
            // endregion

            // =========================================================================
            // STEP 3: FINAL STATUS DETERMINATION AND TOTALS CALCULATION
            // This loop finalizes daily statuses and calculates overall totals for the employee.
            // =========================================================================
            $totals = ['present' => 0.0, 'absent' => 0.0, 'weekly_leave' => 0.0, 'extra_work' => 0.0];
            $finalDailyObjects = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $statusInfo = $processedDailyStatus[$d] ?? null;
                if (!$statusInfo) {
                    $finalDailyObjects[$d] = null;
                    continue;
                }

                // Handle 'Not Employed' days for final output. Display as '0' and do not count in totals.
                if ($statusInfo->status == 'NE') {
                    $finalDailyObjects[$d] = (object)[
                        'status' => '0', // Display as '0' as requested
                        'class' => 'attend-A', // Class for visual styling
                        'forDate' => $carbonDate->copy()->day($d)->format('Y-m-d'),
                        'officeInTime' => null, 'officeOutTime' => null, 'inTime' => null, 'outTime' => null,
                        'workingHr' => null, 'AGFStatus' => null, 'repAuthStatus' => null, 'HRStatus' => null,
                        'startTime' => null, 'endTime' => null, 'AGFDayStatus' => null
                    ];
                    continue; // Do NOT add to any totals, and skip to the next day.
                }

                $finalStatus = $statusInfo->status;
                $dayData = $statusInfo->dayData; // Original day data from DB

                // Apply rules for Present/Half-Present based on actual punch times vs. office times
                if ($dayData && in_array($finalStatus, ['P', 'PL', 'PLH', 'PH']) && $dayData->inTime && $dayData->outTime && $dayData->officeInTime && $dayData->officeOutTime) {
                    $officeStartTime = Carbon::parse($dayData->forDate . ' ' . $dayData->officeInTime);
                    $officeEndTime = Carbon::parse($dayData->forDate . ' ' . $dayData->officeOutTime);
                    $actualInTime = Carbon::parse($dayData->inTime);
                    $actualOutTime = Carbon::parse($dayData->outTime);
                    $requiredMinutes = $officeEndTime->diffInMinutes($officeStartTime);
                    $requiredHalfDayMinutes = $requiredMinutes / 2;
                    $actualMinutesWorked = $actualOutTime->diffInMinutes($actualInTime);

                    // If actual worked minutes are less than half day required and not approved
                    if ($actualMinutesWorked < $requiredHalfDayMinutes && $dayData->AGFStatus == 0) {
                        $finalStatus = 'A'; // Convert to Absent
                    } else {
                        $leftEarly = $actualOutTime->isBefore($officeEndTime->copy()->subMinutes(15)); // Left more than 15 mins early

                        $shiftMidpoint = $officeStartTime->copy()->addMinutes($requiredHalfDayMinutes);
                        $workedInFirstHalf = $actualInTime->lt($shiftMidpoint);
                        $workedInSecondHalf = $actualOutTime->gt($shiftMidpoint);
                        $isHalfDayDueToShiftSpan = !($workedInFirstHalf && $workedInSecondHalf); // Did not cover both halves of the shift

                        // If half day due to shift span AND left early, and not approved
                        if ($isHalfDayDueToShiftSpan && $leftEarly && $dayData->AGFStatus == 0) {
                            $finalStatus = 'A'; // Convert to Absent
                        }
                        // If half day due to shift span OR left early, and not approved
                        elseif (($isHalfDayDueToShiftSpan || $leftEarly) && $dayData->AGFStatus == 0) {
                            $finalStatus = 'PH'; // Convert to Half Present
                        } else {
                            // If arrived late (more than 7 minutes) and not approved
                            if ($actualInTime->isAfter($officeStartTime->copy()->addMinutes(7)) && $dayData->AGFStatus == 0) {
                                if ($finalStatus == 'P') $finalStatus = 'PL'; // Mark as Present Late
                            } else {
                                // Default to Present or Half Present based on AGFDayStatus if approved
                                if ($dayData->AGFDayStatus == 'Full Day')
                                    $finalStatus = 'P';
                                else
                                    $finalStatus = 'PH';
                            }
                        }
                    }
                }
                // Handle cases where original status was 'A' but AGFStatus is not 0 (i.e., approved)
                else if ($dayData && $finalStatus == 'A' && $dayData->AGFStatus != 0) {
                    if ($dayData->AGFDayStatus == 'Full Day')
                        $finalStatus = 'P';
                    else
                        $finalStatus = 'PH';
                }
                // Handle cases where original status was not A, but AGFStatus is not 0 and holiday is 0
                else if ($dayData) { // Ensure $dayData is not null for this else block
                    if ($dayData->AGFDayStatus == 'Full Day' && $dayData->AGFStatus != 0 && $dayData->holiday == 0)
                        $finalStatus = 'P';
                    else if ($dayData->AGFDayStatus == 'Half Day' && $dayData->AGFStatus != 0 && $dayData->holiday == 0)
                        $finalStatus = 'PH';
                    else if ($dayData->holiday == 0) // If not a holiday and not approved, it's Absent
                        $finalStatus = 'A';
                }

                // If original status was PH/PLH and AGFStatus is not 0 (approved)
                if ($dayData && ($dayData->dayStatus == 'PH' || $dayData->dayStatus == 'PLH') && $dayData->AGFStatus != 0) {
                    if ($dayData->AGFDayStatus == 'Full Day')
                        $finalStatus = 'P';
                    else
                        $finalStatus = 'PH';
                }

                // Update totals based on the determined final status
                switch ($finalStatus) {
                    case 'P':
                        $totals['present'] += 1.0;
                        break;
                    case 'PL':
                        $totals['present'] += 1.0;
                        $lateMarkCount++;
                        break;
                    case 'A':
                    case '0':
                        $totals['absent'] += 1.0;
                        break;
                    case 'PH':
                    case 'PLH':
                        $totals['present'] += 0.5;
                        $totals['absent'] += 0.5;
                        if ($finalStatus == 'PLH') {
                            $lateMarkCount++;
                        }
                        break;
                    case 'WO':
                    case 'LH':
                        $totals['weekly_leave'] += 1.0;
                        // Check for 'Extra Working on Holiday' application
                        if ($dayData && $dayData->AGFStatus != 0) {
                            $application = EmpApplication::where('id', $dayData->AGFStatus)->where('reason', 'Extra Working on Holiday')->count();
                            if ($application)
                                $totals['extra_work'] += ($dayData->AGFDayStatus == 'Full Day') ? 1.0 : 0.5;
                        }
                        break;
                }
                // Store final daily object for display
                $finalDailyObjects[$d] = (object)[
                    'officeInTime' => $dayData ? $dayData->officeInTime : null,
                    'officeOutTime' => $dayData ? $dayData->officeOutTime : null,
                    'status' => $finalStatus,
                    'class' => 'attend-' . $finalStatus, // For CSS styling
                    'forDate' => $dayData ? $dayData->forDate : null,
                    'inTime' => $dayData ? $dayData->inTime : null,
                    'outTime' => $dayData ? $dayData->outTime : null,
                    'workingHr' => $dayData ? $dayData->workingHr : null,
                    'deviceInTime' => $dayData ? $dayData->deviceInTime : null,
                    'deviceOutTime' => $dayData ? $dayData->deviceOutTime : null,
                    'AGFStatus' => $dayData ? $dayData->AGFStatus : null,
                    'repAuthStatus' => $dayData ? $dayData->repAuthStatus : null,
                    'HRStatus' => $dayData ? $dayData->HRStatus : null,
                    'startTime' => $dayData ? $dayData->startTime : null,
                    'endTime' => $dayData ? $dayData->endTime : null,
                    'AGFDayStatus' => $dayData ? $dayData->AGFDayStatus : null
                ];
            }

            // Apply late mark deductions (1 day off for every 3 late marks)
            $lateMarkDeduction = floor($lateMarkCount / 3);
            if ($lateMarkDeduction > 0) {
                $totals['present'] -= $lateMarkDeduction;
            }

            // Finalize totals after all deductions
            $totals['late_mark_deductions'] = $lateMarkDeduction;
            $totals['sandwitch_deductions'] = $sandwitchDayDed;
            $totals['weekly_rule_deductions'] = $weeklyRuleDeductions;
            $totals['total_deductions'] = $lateMarkDeduction + $sandwitchDayDed + $weeklyRuleDeductions;
            $totals['present'] = $totals['present'] + $totals['weekly_leave']; // Add weekly leaves back to present for final total
            $totals['final_total'] = $totals['present'] + $totals['extra_work']; // Overall final total days
            $totals['absent'] = $totals['absent'] - ($sandwitchDayDed + $weeklyRuleDeductions); // Adjust absent count

            // Check for manual edits from EmpChangeDay
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

        // --- NEW: FILTERING BASED ON REPORT TYPE AND POLICY SELECTIONS ---
        if ($reportType) {
            $processedEmployees = $processedEmployees->filter(function ($employee) use ($reportType, $policy, $issueType) {
                $totals = $employee['totals'];
                $days = $employee['days']; // Access daily data if needed for specific day checks

                switch ($reportType) {
                    case 'Extra working Report':
                        return $totals['extra_work'] > 0;
                    case 'Absent Report':
                        return $totals['absent'] > 0;
                    case 'Single Punch Report':
                        // This requires checking if a day has only one punch (in or out)
                        // This logic is not directly available in the provided `dayData` structure.
                        // Assuming '0' status or specific flags in `AttendanceDetail` would indicate this.
                        // For demonstration, let's check for days where inTime XOR outTime is present but not both.
                        foreach ($days as $day) {
                            if ($day && (($day->inTime && !$day->outTime) || (!$day->inTime && $day->outTime))) {
                                return true;
                            }
                        }
                        return false;
                    case 'AGF Report':
                        // Filter by AGFStatus (not 0) and optionally by specific issueType
                        foreach ($days as $day) {
                            if ($day && $day->AGFStatus != 0) {
                                // Assuming 'reason' is a property of the original AttendanceDetail record or EmpApplication
                                // You might need to adjust how 'reason' is accessed here based on your DB schema.
                                // If EmpApplication is joined, its reason might be directly available in $day->dayData.
                                // For now, let's assume dayData->reason exists or fetch it if not.
                                if ($issueType) {
                                    $application = EmpApplication::find($day->AGFStatus);
                                    if ($application && $application->reason == $issueType) {
                                        return true;
                                    }
                                } else {
                                    return true; // If no specific issue type, show all AGF
                                }
                            }
                        }
                        return false;
                    case 'WL Report': // Weekly Leave Report (now includes Latemarks and Sandwich Policy)
                        if ($policy == 'Latemarks Policy') {
                            return $totals['late_mark_deductions'] > 0;
                        } elseif ($policy == 'Sandwitch Policy') {
                            return $totals['sandwitch_deductions'] > 0;
                        }
                        // Default for WL Report if no specific policy is selected, show those with weekly leave
                        return $totals['weekly_leave'] > 0;
                    case 'Leave Report':
                        // This would typically involve checking specific leave types from EmpApplication.
                        // Assuming 'A' days where AGFStatus points to an approved leave application.
                        foreach ($days as $day) {
                            if ($day && $day->status === 'A' && $day->AGFStatus != 0) {
                                $application = EmpApplication::find($day->leaveStatus);
                                // Example leave reasons, adjust as per your EmpApplication reasons
                                if ($application && in_array($application->reason, ['Sick Leave', 'Casual Leave', 'Other Leave'])) {
                                    return true;
                                }
                            }
                        }
                        return false;
                    default:
                        return true; // No specific report type selected, show all employees
                }
            });
        }

        // Apply search query filter if present
        if ($searchQuery) {
            $processedEmployees = $processedEmployees->filter(function ($employee) use ($searchQuery) {
                return str_contains(strtolower($employee['info']->name), strtolower($searchQuery)) ||
                    str_contains(strtolower($employee['info']->empCode), strtolower($searchQuery));
            });
        }

        // --- 5. SUMMARIZE AND PAGINATE ---
        // Calculate summary statistics for the filtered employees
        $summaryStats = [
            'total_employees' => $processedEmployees->count(),
            'total_present' => $processedEmployees->sum(function ($emp) {
                return ($emp['totals']['present']);
            }),
            'total_absent' => $processedEmployees->sum(function ($emp) {
                return $emp['totals']['absent'];
            }),
            'total_deductions' => $processedEmployees->sum(function ($emp) {
                return $emp['totals']['total_deductions'];
            }),
            'total_extra_work' => $processedEmployees->sum(function ($emp) {
                return $emp['totals']['extra_work'];
            }),
            'total_late_mark_deductions' => $processedEmployees->sum(function ($emp) {
                return $emp['totals']['late_mark_deductions'];
            }),
            'total_sandwitch_deductions' => $processedEmployees->sum(function ($emp) {
                return $emp['totals']['sandwitch_deductions'];
            }),
            'total_weekly_rule_deductions' => $processedEmployees->sum(function ($emp) {
                return $emp['totals']['weekly_rule_deductions'];
            }),
        ];

        // Paginate the results
        $perPage = 50;
        $paginatedResult = new LengthAwarePaginator(
            $processedEmployees->forPage(LengthAwarePaginator::resolveCurrentPage('page'), $perPage)->values(),
            $processedEmployees->count(),
            $perPage > 0 ? $perPage : 1,
            LengthAwarePaginator::resolveCurrentPage('page'),
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Get the final attendance confirmation status for the view
        $attendanceConfStatus = AttendanceJob::where('userType', $userType)->where('section', $section)->where('fBranchId', $request->branchId)->where('fMonth', $finalMonth)->count();

        // Return the view with all necessary data
        return view('admin.reports.attendanceReport')->with([
            'attendances' => $paginatedResult,
            'daysInMonth' => $daysInMonth,
            'carbonDate' => $carbonDate,
            'attendanceConfStatus' => $attendanceConfStatus,
            'branches' => $branches,
            'userType' => $userType,
            'section' => $section,
            'branchId' => $request->branchId,
            'finalMonth' => $finalMonth,
            'summaryStats' => $summaryStats,
            'searchQuery' => $searchQuery,
            'report_type' => $reportType, // Pass back for old() value in view
            'policy' => $policy, // Pass back for old() value in view
            'issue_type' => $issueType, // Pass back for old() value in view
            'start_month' => $finalMonth, // Pass back start_month for old() value in view
        ]);
    }

    public function exportAttendanceToExcel(Request $request)
    {
        // --- 1. SETUP AND VALIDATION (Similar to searchAttendance) ---
        $finalMonth = $request->input('start_month');
        $userType = Auth::user()->userType;
        $searchQuery = $request->input('search_query');
        $reportType = $request->input('report_type');
        $policy = $request->input('policy');
        $issueType = $request->input('issue_type');
        $section = $request->input('section');

        // Basic validation for required fields for export
        if (!$request->filled('branchId') || !$request->filled('start_month')) {
            return redirect()->back()->withInput()->with("error", "Please select a Branch and Month to export the report.");
        }

        try {
            $carbonDate = Carbon::createFromFormat('Y-m', $finalMonth)->startOfMonth();
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with("error", "Invalid month format provided for export.");
        }

        $startDate = $carbonDate->copy()->format('Y-m-d');
        $daysInMonth = $carbonDate->daysInMonth;

        if ($finalMonth == date('Y-m')) {
            $endDate = Carbon::now()->format('Y-m-d');
        } else {
            $endDate = $carbonDate->copy()->endOfMonth()->format('Y-m-d');
        }

        // --- 2. AUTHORIZATION CHECKS (Similar to searchAttendance) ---
        if ($userType == '501') {
            $attendanceConfStatus = AttendanceJob::where('userType', '51')
                ->where('fBranchId', $request->branchId)
                ->where('fMonth', $finalMonth)
                ->where('section', $section)
                ->count();
            if (!$attendanceConfStatus) {
                return redirect()->back()->withInput()->with("error", "HR Department Still not confirmed Selected Branch. Cannot export.");
            }
        }

        if ($userType == '61') {
            $attendanceConfStatus = AttendanceJob::where('userType', '501')
                ->where('fBranchId', $request->branchId)
                ->where('fMonth', $finalMonth)
                ->where('section', $section)
                ->count();
            if (!$attendanceConfStatus) {
                return redirect()->back()->withInput()->with("error", "Higher Authority Still not confirmed Selected Branch. Cannot export.");
            }
        }

        // --- 3. EFFICIENT DATABASE QUERY (Copied from searchAttendance) ---
        $allAttendances = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select(
                'attendance_details.*',
                'emp_dets.name',
                'emp_dets.empCode',
                'emp_dets.jobJoingDate',
                'emp_dets.lastDate',
                'emp_dets.startTime',
                'emp_dets.endTime',
                'emp_dets.id as attendEmpId',
                'designations.name as designationName',
                'contactus_land_pages.branchName'
            )
            ->whereBetween('attendance_details.forDate', [$startDate, $endDate])
            ->where('emp_dets.branchId', $request->branchId)
            ->when($section, function ($q, $section) {
                return $q->where('departments.section', $section);
            })
            ->orderBy('emp_dets.empCode')->orderBy('attendance_details.forDate')
            ->get();

        if ($allAttendances->isEmpty()) {
            return redirect()->back()->withInput()->with("error", "No records found to export.");
        }

        $employeeIds = $allAttendances->pluck('attendEmpId')->unique();
        $dayChanges = EmpChangeDay::where('month', $finalMonth)
            ->whereIn('empId', $employeeIds)
            ->get()
            ->keyBy('empId');

        // --- 4. PROCESS DATA WITH ALL BUSINESS RULES (Copied from searchAttendance) ---
        $attendancesByEmployee = $allAttendances->groupBy('empId');
        $processedEmployees = collect();

        foreach ($attendancesByEmployee as $empId => $employeeDays) {
            $employeeInfo = $employeeDays->first();

            $joiningDate = $employeeInfo->jobJoingDate ? Carbon::parse($employeeInfo->jobJoingDate)->startOfDay() : null;
            $lastWorkingDate = $employeeInfo->lastDate ? Carbon::parse($employeeInfo->lastDate)->startOfDay() : null;

            $dailyDataMap = $employeeDays->keyBy(function ($day) {
                return Carbon::parse($day->forDate)->format('Y-m-d');
            });

            $processedDailyStatus = [];
            $sandwitchDayDed = 0;
            $weeklyRuleDeductions = 0.0;
            $lateMarkCount = 0;

            // =========================================================================
            // STEP 1: PRELIMINARY AND SANDWICH RULE PROCESSING
            // (Copy the full logic from searchAttendance method here)
            // =========================================================================
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $currentDate = $carbonDate->copy()->day($d)->startOfDay();

                if (($joiningDate && $currentDate->lt($joiningDate)) || ($lastWorkingDate && $currentDate->gt($lastWorkingDate))) {
                    $processedDailyStatus[$d] = (object)['status' => 'NE', 'dayData' => null];
                    continue;
                }

                $dayData = $dailyDataMap->get($currentDate->format('Y-m-d'));
                if (!$dayData) {
                    $processedDailyStatus[$d] = null;
                    continue;
                }
                $finalStatus = $dayData->dayStatus;

                if ($d == 1 && in_array($finalStatus, ['WO', 'LH'])) {
                    $otherDays = $employeeDays->filter(function ($day) use ($currentDate) {
                        return $day->forDate != $currentDate->format('Y-m-d');
                    });
                    if ($otherDays->isNotEmpty()) {
                        $presentDaysCount = $otherDays->filter(function ($day) {
                            return in_array($day->dayStatus, ['P', 'PL', 'PH', 'PLH']);
                        })->count();
                        if ($presentDaysCount <= 2) {
                            $finalStatus = 'A';
                            $sandwitchDayDed++;
                        }
                    }
                }

                if (in_array($finalStatus, ['WO', 'LH'])) {
                    $firstWorkingDayBefore = null;
                    $firstWorkingDayAfter = null;
                    for ($i = $d - 1; $i >= 1; $i--) {
                        $prevDayStatus = $processedDailyStatus[$i] ?? null;
                        if ($prevDayStatus && !in_array($prevDayStatus->status, ['WO', 'LH', 'NE'])) {
                            $firstWorkingDayBefore = $prevDayStatus;
                            break;
                        }
                    }
                    for ($i = $d + 1; $i <= $daysInMonth; $i++) {
                        $nextDay = $dailyDataMap->get($carbonDate->copy()->day($i)->format('Y-m-d'));
                        if ($nextDay && !in_array($nextDay->dayStatus, ['WO', 'LH'])) {
                            $firstWorkingDayAfter = (object)['status' => $nextDay->dayStatus, 'dayData' => $nextDay];
                            break;
                        }
                    }
                    if (
                        $firstWorkingDayBefore && $firstWorkingDayAfter &&
                        (in_array($firstWorkingDayBefore->status, ['A', '0']) && ($firstWorkingDayBefore->dayData ? $firstWorkingDayBefore->dayData->AGFStatus == 0 : true)) &&
                        (in_array($firstWorkingDayAfter->status, ['A', '0']) && ($firstWorkingDayAfter->dayData ? $firstWorkingDayAfter->dayData->AGFStatus == 0 : true))
                    ) {
                        $finalStatus = 'A';
                        $sandwitchDayDed++;
                    }
                }
                $processedDailyStatus[$d] = (object)['status' => $finalStatus, 'dayData' => $dayData];
            }

            // START-OF-MONTH HOLIDAY RULE
            if ($daysInMonth >= 6) {
                $day1Info = $processedDailyStatus[1] ?? null;
                $isDay1Holiday = $day1Info && in_array($day1Info->status, ['WO', 'LH', 'H']);

                $areNext5DaysAbsent = true;
                for ($i = 2; $i <= 6; $i++) {
                    $dayInfo = $processedDailyStatus[$i] ?? null;
                    if (!$dayInfo || !in_array($dayInfo->status, ['A', '0']) || ($dayInfo->dayData && $dayInfo->dayData->AGFStatus != 0)) {
                        $areNext5DaysAbsent = false;
                        break;
                    }
                }

                if ($isDay1Holiday && $areNext5DaysAbsent) {
                    if ($processedDailyStatus[1]) {
                        $processedDailyStatus[1]->status = 'A';
                        $sandwitchDayDed++;
                    }
                }
            }

            // Mark weekend as absent if Mon-Fri was absent
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
                        if (!$dayInfo || !in_array($dayInfo->status, ['A', '0']) || ($dayInfo->dayData && $dayInfo->dayData->AGFStatus != 0)) {
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
                                if ($processedDailyStatus[$sundayIndex]) {
                                    $processedDailyStatus[$sundayIndex]->status = 'A';
                                    $sandwitchDayDed++;
                                }
                            }
                        }
                    }
                }
            }

            // =========================================================================
            // STEP 2: WEEKLY ABSENCE RULE WITH HOLIDAY DEDUCTION
            // (Copy the full logic from searchAttendance method here)
            // =========================================================================
            $weeklyConfig = [
                'ABSENT' => ['A', '0'],
                'HALF_DAY' => ['PH', 'PLH'],
                'HOLIDAY' => ['H', 'LH'],
                'WEEKLY_OFF' => 'WO',
                'STANDARD_WORK_DAYS' => 6,
                'ABSENT_THRESHOLD_RATIO' => 3.5 / 6,
                'HALF_DAY_THRESHOLD_RATIO' => 3.0 / 6,
            ];

            for ($d = 1; $d <= $daysInMonth; $d++) {
                $currentDate = $carbonDate->copy()->day($d);
                if ($currentDate->dayOfWeek == Carbon::SUNDAY) {
                    $sundayIndex = $d;
                    $sundayStatusInfo = $processedDailyStatus[$sundayIndex] ?? null;

                    if ($sundayStatusInfo && $sundayStatusInfo->status == $weeklyConfig['WEEKLY_OFF']) {
                        $startOfWeek = $currentDate->copy()->subDays(6);
                        $endOfWeek = $currentDate->copy()->subDay();
                        $weeklyAbsenceCount = 0.0;
                        $weeklyHolidayCount = 0;

                        for ($weekDay = $startOfWeek->copy(); $weekDay->lte($endOfWeek); $weekDay->addDay()) {
                            if (!$weekDay->isSameMonth($carbonDate)) continue;

                            $statusInfo = $processedDailyStatus[$weekDay->day] ?? null;
                            if (!$statusInfo) continue;

                            if (in_array($statusInfo->status, $weeklyConfig['ABSENT']) && ($statusInfo->dayData && $statusInfo->dayData->AGFStatus == 0)) {
                                $weeklyAbsenceCount += 1.0;
                            } elseif (in_array($statusInfo->status, $weeklyConfig['HALF_DAY']) && ($statusInfo->dayData && $statusInfo->dayData->AGFStatus == 0)) {
                                $weeklyAbsenceCount += 0.5;
                            } elseif (in_array($statusInfo->status, $weeklyConfig['HOLIDAY'])) {
                                $weeklyHolidayCount++;
                            }
                        }

                        $actualWorkDays = $weeklyConfig['STANDARD_WORK_DAYS'] - $weeklyHolidayCount;
                        if ($actualWorkDays > 0) {
                            $absentThreshold = $weeklyConfig['ABSENT_THRESHOLD_RATIO'] * $actualWorkDays;
                            $halfDayThreshold = $weeklyConfig['HALF_DAY_THRESHOLD_RATIO'] * $actualWorkDays;

                            if ($weeklyAbsenceCount >= $absentThreshold) {
                                if ($processedDailyStatus[$sundayIndex]) {
                                    $processedDailyStatus[$sundayIndex]->status = 'A';
                                    $weeklyRuleDeductions += 1.0;
                                }
                            } elseif ($weeklyAbsenceCount >= $halfDayThreshold) {
                                if ($processedDailyStatus[$sundayIndex]) {
                                    $processedDailyStatus[$sundayIndex]->status = 'PH';
                                    $weeklyRuleDeductions += 0.5;
                                }
                            }
                        }
                    }
                }
            }

            // =========================================================================
            // STEP 3: FINAL STATUS DETERMINATION AND TOTALS CALCULATION
            // (Copy the full logic from searchAttendance method here)
            // =========================================================================
            $totals = ['present' => 0.0, 'absent' => 0.0, 'weekly_leave' => 0.0, 'extra_work' => 0.0];
            $finalDailyObjects = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $statusInfo = $processedDailyStatus[$d] ?? null;
                if (!$statusInfo) {
                    $finalDailyObjects[$d] = null;
                    continue;
                }

                if ($statusInfo->status == 'NE') {
                    $finalDailyObjects[$d] = (object)[
                        'status' => '0',
                        'class' => 'attend-A',
                        'forDate' => $carbonDate->copy()->day($d)->format('Y-m-d'),
                        'officeInTime' => null, 'officeOutTime' => null, 'inTime' => null, 'outTime' => null,
                        'workingHr' => null, 'AGFStatus' => null, 'repAuthStatus' => null, 'HRStatus' => null,
                        'startTime' => null, 'endTime' => null, 'AGFDayStatus' => null
                    ];
                    continue;
                }

                $finalStatus = $statusInfo->status;
                $dayData = $statusInfo->dayData;

                if ($dayData && in_array($finalStatus, ['P', 'PL', 'PLH', 'PH']) && $dayData->inTime && $dayData->outTime && $dayData->officeInTime && $dayData->officeOutTime) {
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
                                if ($finalStatus == 'P') $finalStatus = 'PL';
                            } else {
                                if ($dayData->AGFDayStatus == 'Full Day')
                                    $finalStatus = 'P';
                                else
                                    $finalStatus = 'PH';
                            }
                        }
                    }
                } else if ($dayData && $finalStatus == 'A' && $dayData->AGFStatus != 0) {
                    if ($dayData->AGFDayStatus == 'Full Day')
                        $finalStatus = 'P';
                    else
                        $finalStatus = 'PH';
                } else if ($dayData) {
                    if ($dayData->AGFDayStatus == 'Full Day' && $dayData->AGFStatus != 0 && $dayData->holiday == 0)
                        $finalStatus = 'P';
                    else if ($dayData->AGFDayStatus == 'Half Day' && $dayData->AGFStatus != 0 && $dayData->holiday == 0)
                        $finalStatus = 'PH';
                    else if ($dayData->holiday == 0)
                        $finalStatus = 'A';
                }

                if ($dayData && ($dayData->dayStatus == 'PH' || $dayData->dayStatus == 'PLH') && $dayData->AGFStatus != 0) {
                    if ($dayData->AGFDayStatus == 'Full Day')
                        $finalStatus = 'P';
                    else
                        $finalStatus = 'PH';
                }

                switch ($finalStatus) {
                    case 'P':
                        $totals['present'] += 1.0;
                        break;
                    case 'PL':
                        $totals['present'] += 1.0;
                        $lateMarkCount++;
                        break;
                    case 'A':
                    case '0':
                        $totals['absent'] += 1.0;
                        break;
                    case 'PH':
                    case 'PLH':
                        $totals['present'] += 0.5;
                        $totals['absent'] += 0.5;
                        if ($finalStatus == 'PLH') {
                            $lateMarkCount++;
                        }
                        break;
                    case 'WO':
                    case 'LH':
                        $totals['weekly_leave'] += 1.0;
                        if ($dayData && $dayData->AGFStatus != 0) {
                            $application = EmpApplication::where('id', $dayData->AGFStatus)->where('reason', 'Extra Working on Holiday')->count();
                            if ($application)
                                $totals['extra_work'] += ($dayData->AGFDayStatus == 'Full Day') ? 1.0 : 0.5;
                        }
                        break;
                }
                $finalDailyObjects[$d] = (object)[
                    'officeInTime' => $dayData ? $dayData->officeInTime : null,
                    'officeOutTime' => $dayData ? $dayData->officeOutTime : null,
                    'status' => $finalStatus,
                    'class' => 'attend-' . $finalStatus,
                    'forDate' => $dayData ? $dayData->forDate : null,
                    'inTime' => $dayData ? $dayData->inTime : null,
                    'outTime' => $dayData ? $dayData->outTime : null,
                    'workingHr' => $dayData ? $dayData->workingHr : null,
                    'deviceInTime' => $dayData ? $dayData->deviceInTime : null,
                    'deviceOutTime' => $dayData ? $dayData->deviceOutTime : null,
                    'AGFStatus' => $dayData ? $dayData->AGFStatus : null,
                    'repAuthStatus' => $dayData ? $dayData->repAuthStatus : null,
                    'HRStatus' => $dayData ? $dayData->HRStatus : null,
                    'startTime' => $dayData ? $dayData->startTime : null,
                    'endTime' => $dayData ? $dayData->endTime : null,
                    'AGFDayStatus' => $dayData ? $dayData->AGFDayStatus : null
                ];
            }

            $lateMarkDeduction = floor($lateMarkCount / 3);
            if ($lateMarkDeduction > 0) {
                $totals['present'] -= $lateMarkDeduction;
            }

            $totals['late_mark_deductions'] = $lateMarkDeduction;
            $totals['sandwitch_deductions'] = $sandwitchDayDed;
            $totals['weekly_rule_deductions'] = $weeklyRuleDeductions;
            $totals['total_deductions'] = $lateMarkDeduction + $sandwitchDayDed + $weeklyRuleDeductions;
            $totals['present'] = $totals['present'] + $totals['weekly_leave'];
            $totals['final_total'] = $totals['present'] + $totals['extra_work'];
            $totals['absent'] = $totals['absent'] - ($sandwitchDayDed + $weeklyRuleDeductions);

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

        // --- FILTERING BASED ON REPORT TYPE AND POLICY SELECTIONS (Copied from searchAttendance) ---
        if ($reportType) {
            $processedEmployees = $processedEmployees->filter(function ($employee) use ($reportType, $policy, $issueType) {
                $totals = $employee['totals'];
                $days = $employee['days'];

                switch ($reportType) {
                    case 'Extra working Report':
                        return $totals['extra_work'] > 0;
                    case 'Absent Report':
                        return $totals['absent'] > 0;
                    case 'Single Punch Report':
                        foreach ($days as $day) {
                            if ($day && (($day->inTime && !$day->outTime) || (!$day->inTime && $day->outTime))) {
                                return true;
                            }
                        }
                        return false;
                    case 'AGF Report':
                        foreach ($days as $day) {
                            if ($day && $day->AGFStatus != 0) {
                                if ($issueType) {
                                    $application = EmpApplication::find($day->AGFStatus);
                                    if ($application && $application->reason == $issueType) {
                                        return true;
                                    }
                                } else {
                                    return true;
                                }
                            }
                        }
                        return false;
                    case 'WL Report':
                        if ($policy == 'Latemarks Policy') {
                            return $totals['late_mark_deductions'] > 0;
                        } elseif ($policy == 'Sandwitch Policy') {
                            return $totals['sandwitch_deductions'] > 0;
                        }
                        return $totals['weekly_leave'] > 0;
                    case 'Leave Report':
                        foreach ($days as $day) {
                            if ($day && $day->status === 'A' && $day->AGFStatus != 0) {
                                $application = EmpApplication::find($day->AGFStatus);
                                if ($application && in_array($application->reason, ['Sick Leave', 'Casual Leave', 'Earned Leave', 'Paid Leave', 'Unpaid Leave'])) {
                                    return true;
                                }
                            }
                        }
                        return false;
                    default:
                        return true;
                }
            });
        }

        if ($searchQuery) {
            $processedEmployees = $processedEmployees->filter(function ($employee) use ($searchQuery) {
                return str_contains(strtolower($employee['info']->name), strtolower($searchQuery)) ||
                    str_contains(strtolower($employee['info']->empCode), strtolower($searchQuery));
            });
        }

        // --- EXCEL GENERATION ---
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Attendance Report - ' . $finalMonth);

        // Define headers
        $headers = ['Emp Code', 'Name', 'Designation'];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $headers[] = $d;
        }
        $headers = array_merge($headers, [
            'Total Present', 'Total Absent', 'Total WL', 'Total Extra Work', 'Final Total',
            'Late Mark Ded.', 'Sandwich Ded.', 'Weekly Rule Ded.', 'Total Ded.', 'Remark'
        ]);

        // Add headers to the first row
        $sheet->fromArray($headers, NULL, 'A1');

        // Populate data rows
        $row = 2;
        foreach ($processedEmployees as $employee) {
            $rowData = [
                $employee['info']->empCode,
                $employee['info']->name,
                $employee['info']->designationName,
            ];

            // Add daily statuses
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $dayStatus = $employee['days'][$d]->status ?? '';
                if ($dayStatus == 'NE') $dayStatus = '0';
                $rowData[] = $dayStatus;
            }

            // Add totals
            $rowData = array_merge($rowData, [
                number_format($employee['totals']['present'], 1),
                number_format($employee['totals']['absent'], 1),
                number_format($employee['totals']['weekly_leave'], 1),
                number_format($employee['totals']['extra_work'], 1),
                number_format($employee['totals']['final_total'], 1),
                number_format($employee['totals']['late_mark_deductions'], 1),
                number_format($employee['totals']['sandwitch_deductions'], 1),
                number_format($employee['totals']['weekly_rule_deductions'], 1),
                number_format($employee['totals']['total_deductions'], 1),
                $employee['totals']['remark'] ?? '-'
            ]);

            $sheet->fromArray($rowData, NULL, 'A' . $row++);
        }

        // Set column widths for better readability
        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Prepare response for download
        $fileName = 'Attendance_Report_' . $finalMonth . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    // public function searchAttendance(Request $request)
    // {
    //     return $request->all();
    //     $start_month = $request->start_month;
    //     $end_month = $request->end_month;
    //     $branchId = $request->branchId;
    //     $endMonth = date('Y-m-01', strtotime($request->month));
    //     $month = date('M', strtotime($request->month));
    //     $year = date('Y', strtotime($request->month));

    //     if($request->month == date('Y-m'))
    //     {
    //         $endDate = date('Y-m-d');
    //         $days = date('d');
    //     }
    //     else
    //     {
    //         $endDate = date('Y-m-d', strtotime($request->month));
    //         $days = date('t', strtotime($request->month));
    //     }
      
    //     $attendances = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
    //     ->select('attendance_details.*', 'emp_dets.name','emp_dets.startTime','emp_dets.endTime', 'emp_dets.firmType')
    //     ->where('attendance_details.month', $month)
    //     ->where('attendance_details.year', $year);
    
    //     if($request->empCode != null)
    //         $attendances=$attendances->where('emp_dets.empCode', $request->empCode);

    //     if($request->branchId != null)
    //         $attendances=$attendances->where('emp_dets.branchId', $request->branchId);
    
    //     $organisation = $request->organisation;
    //     if($request->organisation != null)
    //         $attendances=$attendances->where('emp_dets.organisation', $request->organisation);
        
    //     if($request->departmentId != null)
    //         $attendances=$attendances->where('emp_dets.departmentId', $request->departmentId);

    //     $departments = Department::where('active', 1)->orderBy('name')->pluck('name', 'id');
    //     $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
    
    //     $attCt=$attendances->count();

    //     $pages = 10*$days;
    //     $attendances=$attendances->where('attendance_details.day', '<=', $days)
    //     ->orderBy('attendance_details.empCode')
    //     ->orderBy('attendance_details.day')
    //     ->paginate($pages)
    //     ->appends(['departmentId' => $request->departmentId, 'organisation' => $request->organisation, 
    //     'branchId'=>$request->branchId, 'days'=>$days, 'departments'=>$departments, 'branches'=>$branches,
    //     'month'=>$month, 'year'=>$year]);

    //     if($attCt == 0)
    //         return redirect()->back()->withInput()->with("error","Record Not Found... ");  

    //     return view('admin.reports.attendanceReport')->with(['startDate'=>$startDate,'days'=>$days, 
    //     'attendances'=>$attendances,'departments'=>$departments, 'month'=>$request->month,
    //     'branchId'=>$request->branchId,'departmentId'=>$request->departmentId,'empCode'=>$request->empCode,'branches'=>$branches]);
    // }

    public function getNDCHistory()
    {
        $ndcs = NdcHistory::where('type', 1)->get();
        $absconds = NdcHistory::where('type', 2)->get();
        $nocodes = NdcHistory::where('type', 3)->get();

        return view('admin.reports.NDCHistory')->with(['ndcs'=>$ndcs, 'absconds'=>$absconds, 'nocodes'=>$nocodes]);
    }

    public function applicationPdfView($empId, $forDate, $appType)
    {
        $user = Auth::user(); 
        $userType = $user->userType; 

        $startDate = date('Y-m', strtotime($forDate)).'-01';
        $endDate = date('Y-m', strtotime($forDate)).'-'.date('t', strtotime($forDate));
       
        $applications = EmpApplication::where('empId', $empId)
        ->where('startDate', '>=', $startDate)
        ->where('startDate', '<=', $endDate)
        ->where('type', $appType)
        ->where('active', 1);

        if($userType == '61')
        {
            $applications = $applications->where('status2', 1);
        }

        $applications =$applications->orderBy('created_at')
        ->get();        

        $empDet = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('emp_dets.id','emp_dets.name as empName', 'emp_dets.empCode','emp_dets.reportingId', 'emp_dets.firmType', 
        'departments.name as departmentName', 'designations.name as designationName')
        ->where('emp_dets.id', $empId)
        ->first();

        $reportingAuth = EmpDet::where('id', $empDet->reportingId)->value('name');
        if($reportingAuth == '')
            $reportingAuth = User::where('id', $empDet->reportingId)->value('name');

        $startDate = date('Y-m', strtotime($forDate)).'-01';
        $endDate = date('Y-m', strtotime($forDate)).'-'.date('t', strtotime($forDate));
       
        $file = 'AGFApplications '.date('d-M-Y').'.pdf';
        $pdf = PDF::loadView('admin.reports.applications.applicationPdfView',compact('applications', 'appType','empDet','startDate', 'endDate'));
        return $pdf->stream($file);  

    }

    public function getContractReport()
    {
        // $expiryDate = date('Y-m-d', strtotime('+5 days'));
        $employees1  = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('departments.name as departmentName','designations.name as designationName','emp_dets.empCode',
        'emp_dets.name','emp_dets.id', 'emp_dets.contractStartDate','emp_dets.contractEndDate')
        ->where('emp_dets.active', 1)
        ->whereNull('emp_dets.contractEndDate')
        ->orderBy('emp_dets.contractEndDate')
        ->get();


        $employees2  = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('departments.name as departmentName','designations.name as designationName','emp_dets.empCode',
        'emp_dets.name','emp_dets.id', 'emp_dets.contractStartDate','emp_dets.contractEndDate')
        // ->where('emp_dets.contractEndDate', '<=', $expiryDate)
        ->where('emp_dets.active', 1)
        ->whereNotNull('emp_dets.contractEndDate')
        ->orderBy('emp_dets.contractEndDate')
        ->get();

        $employees1 = Collect($employees1);
        $employees2 = Collect($employees2);
        $employees = $employees1->merge($employees2);

        return view('admin.reports.contractReport')->with(['employees'=>$employees]);
    }

    public function getContractReportExcel()
    {
        $fileName =  "ExpiredContracts_".date('d-M-Y').".xlsx";
        return Excel::download(new ContractReportExport(), $fileName);
    }

    public function retentionReport(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $branchId = $request->branchId;
        $departmentId = $request->departmentId;
        $designationId = $request->designationId;
        $empCode = $request->empCode;
        if($year == '')
            $year = date('Y');

        $branches=ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $departments=Department::where('active', 1)->orderBy('name')->pluck('name', 'id');

        if($branchId == '')
            return view('admin.reports.retentionReport')->with(['departments'=>$departments,'branches'=>$branches,'branchId'=>$branchId,
            'year'=>$year,'month'=>$month,'departmentId'=>$departmentId,'designationId'=>$designationId,'empCode'=>$empCode]);  
            
        $histories = Retention::join('emp_dets', 'retentions.empId', 'emp_dets.id')
        ->select('emp_dets.empCode', 'emp_dets.name', 'retentions.*');

        if($year != '')
            $histories=$histories->whereYear('retentions.month', $year);

        if($month != '')
            $histories=$histories->whereMonth('retentions.month', $month);

        if($branchId != '')
            $histories=$histories->where('emp_dets.branchId', $branchId);
        
        if($departmentId != '')
            $histories=$histories->where('emp_dets.departmentId', $departmentId);

        if($designationId != '')
            $histories=$histories->where('emp_dets.designationId', $designationId);
        
        return $histories=$histories->orderBy('retentions.empId')->orderBy('retentions.month')->get();

        return view('admin.reports.retentionReport')->with(['histories'=>$histories,'departments'=>$departments,'branches'=>$branches,'branchId'=>$branchId,
        'year'=>$year,'month'=>$month,'departmentId'=>$departmentId,'designationId'=>$designationId,'empCode'=>$empCode]);       
    }

    public function exportRetentionReport($branchId)
    {
        $branch = ContactusLandPage::where('id', $branchId)->value('branchName');
        $fileName = 'RetentionListOf_'.$branch.'_'.date('M-Y').'.xlsx';
        return Excel::download(new RetentionExport($branchId), $fileName);
    }

    public function logTimeReport(Request $request)
    {
        $branches=ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $branchId=$request->branchId;
        $empCode=$request->empCode;
        $forMonth=$request->forMonth;

        if($forMonth == '')
        {
            $forMonth = date('Y-m');
        }
       
        $logList = LogTimeOld::where('LogDateTime', '>=', date('Y-m-01 H:i:s', strtotime($forMonth)))
        ->where('LogDateTime', '<=', date('Y-m-t 23:59:59', strtotime($forMonth)));
        if($branchId != '')
        {
            $empCodes = EmpDet::where('branchId', $branchId)->where('active', 1)->pluck('empCode');
            $logList = $logList->whereIn('EmployeeCode', $empCodes);
        }

        if($empCode != '')
        {
            $logList = $logList->where('EmployeeCode', $empCode);
        }

        $logList = $logList->orderBy('EmployeeCode')->orderBy('LogDateTime')->get();

        return view('admin.reports.logTimeReport')->with(['branches'=>$branches,'branchId'=>$branchId,
        'empCode'=>$empCode, 'forMonth'=>$forMonth,'logList'=>$logList]);
    }

    // /reports/apprisalReport
    public function apprisalReport(Request $request)
    {
        $branches=ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $branchId=$request->branchId;
        $empCode=$request->empCode;
        $appraisals = Appraisal::where('active', 1)->get();

        return view('admin.reports.apprisalReport', compact('branches','branchId','empCode','appraisals'));
    } 
    
    public function arrearsReport(Request $request)
    {
        $branches=ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $organisations=Organisation::where('active', 1)->orderBy('name')->pluck('name', 'id');
        $departments=Department::where('active', 1)->orderBy('name')->pluck('name', 'id');

        $branchId = $request->branchId;
        $departmentId = $request->departmentId;
        $designationId = $request->designationId;

        if($request->month == '')
            $month=date('Y-m', strtotime('-1 month'));
        else
            $month=date('Y-m', strtotime($request->month));

        $arrears = Ticket::join('emp_dets', 'tickets.empId', 'emp_dets.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->join('organisations', 'emp_dets.organisationId', 'organisations.id')
        ->select('emp_dets.empCode', 'emp_dets.name', 'departments.name as departmentName', 
        'designations.name as designationName', 'contactus_land_pages.branchName', 'organisations.shortName','tickets.*')
        ->where('tickets.issueType', 1)
        // ->where('tickets.issue', 'Arrears')
        ->where('tickets.fromMonth', $month);
        if($branchId != '')
            $arrears = $arrears->where('emp_dets.branchId', $branchId);

        if($departmentId != '')
            $arrears = $arrears->where('emp_dets.departmentId', $departmentId);
        
        if($designationId != '')
            $arrears = $arrears->where('emp_dets.designationId', $designationId);
        

        $arrears = $arrears->orderBy('tickets.status')->get();

        return view('admin.reports.arrearsReport', compact('branchId','designationId', 'departmentId','branches','organisations','departments','month', 'arrears'));
    }

    public function arrearsExportReport()
    {

    }
}

