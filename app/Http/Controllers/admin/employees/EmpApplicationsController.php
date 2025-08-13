<?php

namespace App\Http\Controllers\admin\employees;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EmpApplication;
use App\ContactusLandPage;
use App\Helpers\Utility;
use App\Exports\AGFApplicationsExport;
use App\Exports\ExitPassApplicationsExport;
use App\Exports\LeaveApplicationsExport;
use App\Exports\TravellingAllowApplicationsExport;

use App\EmpDet;
use App\AttendanceDetail;
use App\HolidayDept;
use App\HrPolicy;
use App\AttendanceLog;
use App\Department;
use App\EmpApplicationHistory;
use App\Notification;
use App\Designation;
use App\TempEmpDet;
use App\TravelAllowancePayment;
use App\User;
use App\BiometricMachine;
use App\CommonForm;
use Auth;
use DB;
use DateTime;
use PDF;
use Excel;

class EmpApplicationsController extends Controller
{
    public function empAGFList(Request $request)
    {
        $empId = Auth::user()->empId;

        if($request->month == '')
            $month = date('Y-m-d');
        else
            $month = date('Y-m-d', strtotime($request->month));
        
        $fromDate = date('Y-m-01', strtotime($month));
        $toDate = date('Y-m-t', strtotime($month));

        $applications = EmpApplication::where('type', 1)
        ->where('empId', $empId)
        ->where('startDate', '>=', $fromDate)
        ->where('startDate', '<=', $toDate)
        ->where('active', 1)
        ->get();

        // counts 
        $appCount = EmpApplication::where('type', 1)
        ->where('empId', $empId)
        ->where('startDate', '>=', $fromDate)
        ->where('startDate', '<=', $toDate)
        ->where('status', 0)
        ->count();

        return view('admin.applications.EmpAGFList')->with(['month'=>$month,'applications'=>$applications,'appCount'=>$appCount]);
    }

    public function empLeaveList(Request $request)
    {
        $empId = Auth::user()->empId;

        if($request->month == '')
            $month = date('Y-m-d');
        else
            $month = date('Y-m-d', strtotime($request->month));
        
        $fromDate = date('Y-m-01', strtotime($month));
        $toDate = date('Y-m-t', strtotime($month));

        $applications = EmpApplication::where('type', 3)
        ->where('empId', $empId)
        ->where('startDate', '>=', $fromDate)
        ->where('startDate', '<=', $toDate)
        ->where('active', 1)
        ->get();

        // counts 
        $appCount = EmpApplication::where('type', 3)
        ->where('empId', $empId)
        ->where('startDate', '>=', $fromDate)
        ->where('startDate', '<=', $toDate)
        ->where('status', 0)
        ->count();

        return view('admin.applications.EmpLeaveList')->with(['month'=>$month,'applications'=>$applications,'appCount'=>$appCount]);
    }

    public function empExitPassList(Request $request)
    {
        $empId = Auth::user()->empId;

        if($request->month == '')
            $month = date('Y-m-d');
        else
            $month = date('Y-m-d', strtotime($request->month));
        
        $fromDate = date('Y-m-01', strtotime($month));
        $toDate = date('Y-m-t', strtotime($month));

        $applications = EmpApplication::where('type', 2)
        ->where('empId', $empId)
        ->where('startDate', '>=', $fromDate)
        ->where('startDate', '<=', $toDate)
        ->where('active', 1)
        ->get();

        // counts 
        $appCount = EmpApplication::where('type', 2)
        ->where('empId', $empId)
        ->where('startDate', '>=', $fromDate)
        ->where('startDate', '<=', $toDate)
        ->where('status', 0)
        ->count();

        return view('admin.applications.EmpExitPassList')->with(['month'=>$month,'applications'=>$applications,'appCount'=>$appCount]);
    }

    public function empTravellingAllownaceList(Request $request)
    {
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'branchName');
        
        $empId = Auth::user()->empId;

        if($request->month == '')
            $month = date('Y-m-d');
        else
            $month = date('Y-m-d', strtotime($request->month));
        
        $fromDate = date('Y-m-01', strtotime($month));
        $toDate = date('Y-m-t', strtotime($month));

        $applications = EmpApplication::select(DB::raw('YEAR(startDate) year, MONTH(startDate) month'))
        ->where('type', 4)
        ->where('active', 1)
        ->where('empId', $empId)
        ->groupBy('year','month')
        ->get();

        foreach($applications as $application)
        {
            if($application->month >= 1 && $application->month <= 9)
                $month = '0'.$application->month;
            else
                $month = $application->month;

            $application['forDate'] = $month = $application->year.'-'.$month;

            $fromDate = date('Y-m-01', strtotime($month));
            $toDate = date('Y-m-t', strtotime($month));

            $application['pendingCt'] = EmpApplication::where('type', 4)
            ->where('empId', $empId)
            ->where('active', 1)
            ->where('status1', 0)
            ->where('startDate', '>=', $fromDate)
            ->where('startDate', '<=', $toDate)
            ->count();

            $application['acPendingCt'] = EmpApplication::where('type', 4)
            ->where('empId', $empId)
            ->where('active', 1)
            ->where('status', 0)
            ->where('startDate', '>=', $fromDate)
            ->where('startDate', '<=', $toDate)
            ->count();
            
            $application['totalCt'] = EmpApplication::where('type', 4)
            ->where('empId', $empId)
            ->where('active', 1)
            ->where('startDate', '>=', $fromDate)
            ->where('startDate', '<=', $toDate)
            ->count();

            $month='';
        }

        $applications = collect($applications);
        $applications = $applications->sortBy('forDate')->values();
        return view('admin.applications.EmpTravellingAllownanceList')->with(['empId'=>$empId,'applications'=>$applications,'branches'=>$branches]);
    }

    public function dlist()
    {
        $empId = Auth::user()->empId;
        $applications = EmpApplication::where('empId', $empId)->whereActive(0)->get();
        return view('admin.applications.dlist')->with(['applications'=>$applications]);
    }
 
    public function applyApplication($type)
    {
        $common = CommonForm::where('active', 1)->first();
        $empCode = EmpDet::where('id', Auth::user()->empId)->value('empCode');
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'branchName');
        return view('admin.applications.create')->with(['common'=>$common,'branches'=>$branches,'type'=>$type,'empCode'=>$empCode]);
    }

    public function applyTAllow(Request $request)
    {
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'branchName');
        $startDate = date('Y-m', strtotime($request->month)).'-01';
        $endDate = date('Y-m', strtotime($request->month)).'-'.date('t', strtotime($request->month));
        $empId = Auth::user()->empId;
        $travells = EmpApplication::where('type', 4)
        ->where('empId', $empId)
        ->where('startDate', '>=', $startDate)
        ->where('startDate', '<=', $endDate)
        ->where('active', 1)
        ->orderBy('created_at')
        ->get();

        $empDet = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('emp_dets.name as empName', 'emp_dets.empCode','emp_dets.reportingId', 'emp_dets.firmType', 
        'departments.name as departmentName', 'designations.name as designationName')
        ->where('emp_dets.id', $empId)
        ->first();

        $appType=4;
        $month= $request->month;
      
        return view('admin.applications.applyTravAllow')->with(['empId'=>$empId,'empDet'=>$empDet,'travells'=>$travells, 
        'startDate'=>$startDate, 'endDate'=>$endDate, 'appType'=>$appType, 'branches'=>$branches, 'month'=>$month]);
    }

    public function store(Request $request)
    {
        if($request->appType == 1) // AGF Application
        {
            $forDate = date('Y-m-d', strtotime($request->AGFForDate));

            $application = new EmpApplication;
            $application->empId = Auth::user()->empId;
            $application->type = $request->appType;
            $application->inTime = $request->inTime;
            $application->outTime = $request->outTime;
            $application->dayStatus = $request->dayStatus;
            $application->startDate = $forDate;
            $application->reason = $request->AGFIssue;
            $application->description = $request->AGFDescription;
            $application->status = 0; // pending
            $application->updated_by=Auth::user()->username;
            if($application->save())
            {
                $approv = new EmpApplicationHistory;
                $approv->applicationId = $application->id;
                $approv->approvedBy = Auth::user()->username;
                $approv->approvedAt = date('Y-m-d H:i:s');
                $approv->save();
            }

            return redirect()->back()->with("success","Employee AGF applied successfully..");

        }
        elseif($request->appType == 2) // Exit Pass Application
        {
            if($request->exitPassForDate == '' || $request->exitPassIssue == '' || $request->exitPassTimeout == '' || $request->exitPassDescription == '')
            {
                return redirect()->back()->withInput()->with("error","Please Fill all required(*) fields...");
            }
           
            $forDate = date('Y-m-d', strtotime($request->exitPassForDate));
            if(EmpApplication::where('empId', Auth::user()->empId)->where('type', $request->appType)->where('status', 0)->where('startDate', $forDate)->whereActive(1)->first())
            {
                return redirect()->back()->withInput()->with("error","This selected Date of Exit Pass already filled....");;
            }

            $application = new EmpApplication;
            $application->empId = Auth::user()->empId;
            $application->type = $request->appType;
            $application->startDate = $forDate;
            $application->timeout = $request->exitPassTimeout;
            $application->reason = $request->exitPassIssue;
            $application->description = $request->exitPassDescription;
            $application->status = 0; // pending
            $application->updated_by=Auth::user()->username;
            if($application->save())
            {
                $approv = new EmpApplicationHistory;
                $approv->applicationId = $application->id;
                $approv->approvedBy = Auth::user()->username;
                $approv->approvedAt = date('Y-m-d H:i:s');
                $approv->save();
            }
            return redirect()->back()->with("success","Employee Exit Pass applied successfully..");

        }
        elseif($request->appType == 3) // Leave Application
        {
            if($request->leaveType == 1)
            {
                if($request->leaveStartDate == '' || $request->leaveEndDate == '' || $request->leaveReportingDate == '' || $request->leaveIssue == '' || $request->leaveDescription == '')
                {
                    return redirect()->back()->withInput()->with("error","Please Fill all required(*) fields...");;
                }

                $startDate = date('Y-m-d', strtotime($request->leaveStartDate));
                $endDate = date('Y-m-d', strtotime($request->leaveEndDate));
                if(EmpApplication::where('empId', Auth::user()->empId)->where('type', $request->appType)->where('startDate', '<=', $startDate)->where('endDate', '>=',$endDate)->where('status', 0)->whereActive(1)->first())
                {
                    return redirect()->back()->withInput()->with("error","This selected Leave Application already filled....");;
                }
                
                if($startDate > $endDate)
                    return redirect()->back()->withInput()->with("error","Please select valid Dates....");;
            }
            else
            {
                if($request->leaveStartDate == '' || $request->leaveReportingDate == '' || $request->leaveIssue == '' || $request->leaveDescription == '')
                {
                    return redirect()->back()->withInput()->with("error","Please Fill all required(*) fields...");;
                }

                $startDate = date('Y-m-d', strtotime($request->leaveStartDate));
                if(EmpApplication::where('empId', Auth::user()->empId)->where('type', $request->appType)->where('startDate', $startDate)->whereActive(1)->first())
                {
                    return redirect()->back()->withInput()->with("error","This selected Leave Application already filled....");;
                }
            }

            $application = new EmpApplication;
            $application->empId = Auth::user()->empId;
            $application->type = $request->appType;
            $application->startDate = $startDate;
            if($request->leaveType == 1)
                $application->endDate = $endDate;
            else
                $application->endDate = $startDate;

            $application->reportingDate = date('Y-m-d', strtotime($request->leaveReportingDate));
            $application->reason = $request->leaveIssue;
            $application->description = $request->leaveDescription;
            $application->leaveType = $request->leaveType;
            $application->status = 0; // pending
            $application->updated_by=Auth::user()->username;
            if($application->save())
            {
                $approv = new EmpApplicationHistory;
                $approv->applicationId = $application->id;
                $approv->approvedBy = Auth::user()->username;
                $approv->approvedAt = date('Y-m-d H:i:s');
                if($approv->save())
                {
                    AttendanceDetail::where('empId', $application->empId)
                    ->where('forDate', '>=', $application->startDate)
                    ->where('forDate', '<=', $application->endDate)
                    ->update(['leaveStatus'=>$application->id]);
                }
            }

            return redirect()->back()->with("success","Your Leave applied successfully..");

        }
        elseif($request->appType == 4) // Travelling Application
        {
            $application = new EmpApplication;
            $application->empId = Auth::user()->empId;
            $application->type = $request->appType;
            $application->startDate = $request->forDate;
            $application->endDate = $request->forDate;

            if($request->otherFromDest == '')
                $application->fromDest = $request->fromDest;
            else
                $application->fromDest = $request->otherFromDest;

            if($request->otherToDest == '')
                $application->toDest = $request->toDest;
            else
                $application->toDest = $request->otherToDest;

            $application->kms = $request->km;
            $application->reason = $request->reason;
            $application->status = 0; // pending
            $application->updated_by=Auth::user()->username;
            if($application->save())
            {
                $approv = new EmpApplicationHistory;
                $approv->applicationId = $application->id;
                $approv->approvedBy = Auth::user()->username;
                $approv->approvedAt = date('Y-m-d H:i:s');
                $approv->save();
            }
            // 
            $month = $request->month;
            return redirect('/empApplications/applyTAllow?month='.$month)->with("success","Your Travelling Allowance applied successfully..");

        }
        else
        {
            return redirect()->back()->withInput()->with("error","Invalid Application....");;
        }
    }

    public function deleteApplication($id, $month)
    {
        EmpApplication::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        return redirect('/empApplications/applyTAllow?month='.$month)->with('success', 'Application Deleted successfully!!!');
    }

    public function show($id)
    {
        $application = EmpApplication::find($id);
        $appHistory = EmpApplicationHistory::where('applicationId', $id)->get();
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        return view('admin.applications.show')->with(['appHistory'=>$appHistory,'branches'=>$branches,'application'=>$application]);
    }

    public function edit($id)
    {
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $application = EmpApplication::find($id);
        return view('admin.applications.edit')->with(['branches'=>$branches,'application'=>$application]);
    }
    
    public function update(Request $request, $id)
    {
        if($request->appType == 1) // AGF Application
        {
            if($request->AGFForDate == '' || $request->AGFDescription == '' || $request->AGFIssue == '')
            {
                return redirect()->back()->withInput()->with("error","Please Fill all required(*) fields...");;
            }

            $forDate = date('Y-m-d', strtotime($request->AGFForDate));
            $oldApp = EmpApplication::where('id', '<>', $id)->where('empId',Auth::user()->empId)->where('type', $request->appType)->where('startDate', $forDate)->whereActive(1)->first();
            if($forDate && $oldApp)
            {
                return redirect()->back()->withInput()->with("error","This selected Date of AGF already filled....");;
            }

            $application = EmpApplication::find($id);
            $application->startDate = $forDate;
            $application->reason = $request->AGFIssue;
            $application->description = $request->AGFDescription;
            $application->updated_by=Auth::user()->username;
            if($application->save())
            {
                $approv = new EmpApplicationHistory;
                $approv->applicationId = $application->id;
                $approv->approvedBy = Auth::user()->username;
                $approv->approvedAt = date('Y-m-d H:i:s');
                $approv->save();
                $branchId = EmpDet::where('id', $application->empId)->value('branchId');
            }

            return redirect('/empApplications/AGFList?month='.date("Y-m", strtotime($forDate)).'&branchId=$branchId')->back()->with("success","Employee AGF Application Updated successfully..");

        }
        elseif($request->appType == 2) // Exit Pass Application
        {
            if($request->exitPassForDate == '' || $request->exitPassIssue == '' || $request->exitPassTimeout == '' || $request->exitPassDescription == '')
            {
                return redirect()->back()->withInput()->with("error","Please Fill all required(*) fields...");;
            }
           
            $forDate = date('Y-m-d', strtotime($request->exitPassForDate));
            if($forDate && EmpApplication::where('id', '<>',$id)->where('empId', Auth::user()->empId)->where('type', $request->appType)->where('startDate', $forDate)->whereActive(1)->first())
            {
                return redirect()->back()->withInput()->with("error","This selected Date of Exit Pass already filled....");;
            }

            $application = EmpApplication::find($id);
            $application->startDate = $forDate;
            $application->timeout = $request->exitPassTimeout;
            $application->reason = $request->exitPassIssue;
            $application->description = $request->exitPassDescription;
            $application->updated_by=Auth::user()->username;
            if($application->save())
            {
                $approv = new EmpApplicationHistory;
                $approv->applicationId = $application->id;
                $approv->approvedBy = Auth::user()->username;
                $approv->approvedAt = date('Y-m-d H:i:s');
                $approv->save();
            }
            return redirect()->back()->with("success","Employee Exit Pass Updated successfully..");

        }
        elseif($request->appType == 3) // Leave Application
        {
            if($request->leaveType == 1)
            {
                if($request->leaveStartDate == '' || $request->leaveEndDate == '' || $request->leaveReportingDate == '' || $request->leaveIssue == '' || $request->leaveDescription == '')
                {
                    return redirect()->back()->withInput()->with("error","Please Fill all required(*) fields...");;
                }

                $startDate = date('Y-m-d', strtotime($request->leaveStartDate));
                $endDate = date('Y-m-d', strtotime($request->leaveEndDate));
                if(EmpApplication::where('id', '<>',$id)->where('empId', Auth::user()->empId)->where('type', $request->appType)->where('startDate', '<=', $startDate)->where('endDate', '>=',$endDate)->whereActive(1)->first())
                {
                    return redirect()->back()->withInput()->with("error","This selected Leave Application already filled....");;
                }
                
                if($startDate < date('Y-m-d') || $endDate < date('Y-m-d') || $startDate > $endDate)
                    return redirect()->back()->withInput()->with("error","Please select valid Dates....");;

            }
            else
            {
                if($request->leaveStartDate == '' || $request->leaveReportingDate == '' || $request->leaveIssue == '' || $request->leaveDescription == '')
                {
                    return redirect()->back()->withInput()->with("error","Please Fill all required(*) fields...");;
                }

                $startDate = date('Y-m-d', strtotime($request->leaveStartDate));
                if(EmpApplication::where('id', '<>',$id)->where('empId', Auth::user()->empId)->where('type', $request->appType)->where('startDate', $startDate)->whereActive(1)->first())
                {
                    return redirect()->back()->withInput()->with("error","This selected Leave Application already filled....");;
                }
            }

            $application = EmpApplication::find($id);
            $application->startDate = $startDate;
            if($request->leaveType == 1)
                $application->endDate = $endDate;
            else
                $application->endDate = $startDate;
                
            $application->reportingDate = date('Y-m-d', strtotime($request->leaveReportingDate));
            $application->reason = $request->leaveIssue;
            $application->description = $request->leaveDescription;
            $application->leaveType = $request->leaveType;
            $application->updated_by=Auth::user()->username;
            if($application->save())
            {
                $approv = new EmpApplicationHistory;
                $approv->applicationId = $application->id;
                $approv->approvedBy = Auth::user()->username;
                $approv->approvedAt = date('Y-m-d H:i:s');
                $approv->save();
            }

            return redirect()->back()->with("success","Your Leave Application Updated successfully..");

        }
        elseif($request->appType == 4)
        {
            $application = EmpApplication::find($id);
            $application->empId = Auth::user()->empId;
            $application->type = $request->appType;
            $application->startDate = $request->forDate;
            $application->endDate = $request->forDate;

            if($request->otherFromDest == '')
                $application->fromDest = $request->fromDest;
            else
                $application->fromDest = $request->otherFromDest;

            if($request->otherToDest == '')
                $application->toDest = $request->toDest;
            else
                $application->toDest = $request->otherToDest;

            $application->kms = $request->kms;
            $application->reason = $request->reason;
            $application->status = 0; // pending
            $application->updated_by=Auth::user()->username;
            if($application->save())
            {
                $approv = new EmpApplicationHistory;
                $approv->applicationId = $application->id;
                $approv->approvedBy = Auth::user()->username;
                $approv->approvedAt = date('Y-m-d H:i:s');
                $approv->save();
            }
            return redirect()->back()->with("success","Your Travelling allowance Application Updated successfully..");
        }
        else
        {
            return redirect()->back()->withInput()->with("error","Invalid Application....");;
        }
    }

    public function activate($id)
    {
        EmpApplication::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        return redirect()->back()->with('success', 'Application Activated successfully!!!');
    }

    public function deactivate($id)
    {
        EmpApplication::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        return redirect()->back()->with('success', 'Application Deleted successfully!!!');
    }

    public function applicationList(Request $request)
    {
        $userType = Auth::user()->userType;

        if($userType == '51' || $userType == '00' || $userType == '501' || $userType == '401' || $userType == '301' || $userType == '201')
        {
            $AGFApplications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->select('departments.name as departmentName', 'designations.name as designationName', 
            'emp_dets.name as empName', 'emp_dets.empCode','emp_dets.firmType', 'emp_applications.*')
            ->where('emp_applications.startDate', '>=', date('Y-m-01', strtotime('-1 month')))
            ->where('emp_applications.startDate', '<=', date('Y-m-t'))
            ->where('emp_applications.type', 1)
            ->orderBy('emp_applications.status')
            ->orderBy('emp_applications.updated_at', 'desc')
            ->get();

            $exitPassApplications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->select('departments.name as departmentName', 'designations.name as designationName', 
            'emp_dets.name as empName', 'emp_dets.empCode','emp_dets.firmType', 'emp_applications.*')
            ->where('emp_applications.startDate', '>=', date('Y-m-01', strtotime('-1 month')))
            ->where('emp_applications.startDate', '<=', date('Y-m-t'))
            ->where('emp_applications.type', 2)
            ->where('emp_applications.active',1)
            ->orderBy('emp_applications.status')
            ->orderBy('emp_applications.updated_at', 'desc')
            ->get();

            $leaveApplications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->select('departments.name as departmentName', 'designations.name as designationName', 
            'emp_dets.name as empName','emp_dets.empCode','emp_dets.firmType', 'emp_applications.*')
            ->where('emp_applications.startDate', '>=', date('Y-m-01', strtotime('-1 month')))
            ->where('emp_applications.startDate', '<=', date('Y-m-t'))
            ->where('emp_applications.type', 3)
            ->where('emp_applications.active',1)
            ->orderBy('emp_applications.status')
            ->orderBy('emp_applications.updated_at', 'desc')
            ->get(); 

            $travelApplications = EmpApplication::select(DB::raw('count(id)  as totApp'), 'empId', DB::raw('MONTH(startDate) as forDateMonth'),
            DB::raw('YEAR(startDate) as forDateYear'))
            ->where('type', 4)
            ->where('active', 1)
            ->where('startDate', '>=', date('Y-m-01', strtotime('-1 month')))
            ->where('startDate', '<=', date('Y-m-t'))
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
                ->where('startDate', '>=', date('Y-m-01'))
                ->where('startDate', '<=', date('Y-m-t'))->count();
            }

            $travelApplications = collect($travelApplications);
            $travelApplications = $travelApplications->sortByDesc('pendingCt')->values();
                
            // counts 
            $agfsCt = EmpApplication::where('type', 1)
            ->where('startDate', '>=', date('Y-m-01', strtotime('-1 month')))
            ->where('startDate', '<=', date('Y-m-t'))
            ->where('status', 0)
            ->count();

            $leavesCt = EmpApplication::where('type', 3)
            ->where('startDate', '>=', date('Y-m-01', strtotime('-1 month')))
            ->where('startDate', '<=', date('Y-m-t'))
            ->where('active', 1)
            ->where('status', 0)
            ->count();

            $exitPassesCt = EmpApplication::where('type', 2)
            ->where('startDate', '>=', date('Y-m-01', strtotime('-1 month')))
            ->where('startDate', '<=', date('Y-m-t'))
            ->where('active',1)
            ->where('status', 0)
            ->count();

            $travellsCt = EmpApplication::where('type', 4)
            ->where('startDate', '>=', date('Y-m-01', strtotime('-1 month')))
            ->where('startDate', '<=', date('Y-m-t'))
            ->where('active', 1)
            ->where('status', 0)
            ->count();

            return view('admin.applications.applicationList')->with(['AGFApplications'=>$AGFApplications,
            'exitPassApplications'=>$exitPassApplications,'leaveApplications'=>$leaveApplications
            ,'leavesCt'=>$leavesCt,'agfsCt'=>$agfsCt,'travelApplications'=>$travelApplications,
            'exitPassesCt'=>$exitPassesCt,'travellsCt'=>$travellsCt]);
        }

        if($userType == '31') // employee
        {
            $temp = $this->getEmployeeApp();
        }

        if($userType == '21') // Department Head
        {
           $temp = $this->getEmployeeDepHeadApp();
        }

        if($userType == '11' || $userType == '601') // General manager
        {
           $temp = $this->getEmployeeGenMgrApp();
        }

        if($userType == '00') // Super Admin
        {
            $temp = $this->getEmployeeSuperAdminApp();
        }

        return view('admin.applications.applicationList')->with($temp);
    }

    public function getEmployeeApp()
    {
        $empId = Auth::user()->empId;
        $AGFApplications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('departments.name as departmentName', 'designations.name as designationName', 
        'emp_dets.name as empName', 'emp_dets.empCode','emp_dets.firmType', 'emp_applications.*')
        ->where('emp_applications.type', 1)
        ->where('emp_applications.active',1)
        ->where('emp_dets.id', $empId)
        ->orderBy('emp_applications.status')
        ->get();
        // counts 
        $temp = collect($AGFApplications);
        $temp=$temp->where('status', 0);
        $agfsCt= $temp->count();

        $exitPassApplications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('departments.name as departmentName', 'designations.name as designationName', 
        'emp_dets.name as empName', 'emp_dets.empCode','emp_dets.firmType', 'emp_applications.*')
        ->where('emp_applications.type', 2)
        ->where('emp_applications.active',1)
        ->where('emp_dets.id', $empId)
        ->orderBy('emp_applications.status')
        ->get();
        $tempExitPass = collect($exitPassApplications);
        $tempExitPass=$tempExitPass->where('status', 0);
        $exitPassesCt= $tempExitPass->count();
        

        $leaveApplications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('departments.name as departmentName', 'designations.name as designationName', 
        'emp_dets.name as empName','emp_dets.empCode','emp_dets.firmType', 'emp_applications.*')
        ->where('emp_applications.type', 3)
        ->where('emp_applications.active',1)
        ->where('emp_dets.id', $empId)
        ->orderBy('emp_applications.status')
        ->get();
        $tempLeave = collect($leaveApplications);
        $tempLeave=$tempLeave->where('status', 0);
        $leavesCt= $tempLeave->count();

        
        $travelApplications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('departments.name as departmentName', 'designations.name as designationName', 
        'emp_dets.name as empName','emp_dets.empCode','emp_dets.firmType', 'emp_applications.*')
        ->where('emp_applications.type', 4)
        ->where('emp_applications.active',1)
        ->where('emp_dets.id', $empId)
        ->orderBy('emp_applications.status')
        ->get();
        $tempTravel = collect($travelApplications);
        $tempTravel=$tempTravel->where('status', 0);
        $travellsCt= $tempTravel->count();

        return ['AGFApplications'=>$AGFApplications,'exitPassApplications'=>$exitPassApplications,
        'leaveApplications'=>$leaveApplications,'leavesCt'=>$leavesCt,'agfsCt'=>$agfsCt,
        'exitPassesCt'=>$exitPassesCt,'travellsCt'=>$travellsCt];
    }

    public function getEmployeeDepHeadApp()
    {
        $empId = Auth::user()->empId;

        $AGFApplications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('departments.name as departmentName', 'designations.name as designationName', 
        'emp_dets.name as empName', 'emp_dets.empCode','emp_dets.firmType', 'emp_applications.*')
        ->where('emp_applications.type', 1)
        ->where('emp_applications.active',1)
        ->where('emp_applications.startDate', '>=', date('Y-m-01', strtotime('-1 month')))
        ->where('emp_applications.startDate', '<=', date('Y-m-t'))
        ->where('emp_dets.reportingId', $empId)
        ->orderBy('emp_applications.status')
        ->orderBy('emp_applications.updated_at')
        ->get();
        $temp = collect($AGFApplications);
        $temp=$temp->where('status', 0);
        $agfsCt= $temp->count();

        $exitPassApplications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('departments.name as departmentName', 'designations.name as designationName', 
        'emp_dets.name as empName', 'emp_dets.empCode','emp_dets.firmType', 'emp_applications.*')
        ->where('emp_applications.type', 2)
        ->where('emp_applications.active',1)
        ->where('emp_applications.startDate', '>=', date('Y-m-01', strtotime('-1 month')))
        ->where('emp_applications.startDate', '<=', date('Y-m-t'))
        ->where('emp_dets.reportingId', $empId)
        ->orderBy('emp_applications.status')
        ->orderBy('emp_applications.updated_at')
        ->get();
        $tempExitPass = collect($exitPassApplications);
        $tempExitPass=$tempExitPass->where('status', 0);
        $exitPassesCt= $tempExitPass->count();

        $leavesCt=0;
        $leaveApplications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('departments.name as departmentName', 'designations.name as designationName', 
        'emp_dets.name as empName','emp_dets.empCode','emp_dets.firmType', 'emp_applications.*')
        ->where('emp_applications.type', 3)
        ->where('emp_applications.active',1)
        ->where('emp_applications.startDate', '>=', date('Y-m-01', strtotime('-1 month')))
        ->where('emp_applications.startDate', '<=', date('Y-m-t'))
        ->where('emp_dets.reportingId', $empId)
        ->orderBy('emp_applications.status')
        ->orderBy('emp_applications.updated_at')
        ->get();
        $tempLeaves = collect($leaveApplications);
        $tempLeaves=$tempLeaves->where('status', 0);
        $leavesCt= $tempLeaves->count();
      

        $travelApplications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->select(DB::raw('count(emp_applications.id)  as totApp'), 'emp_applications.empId', DB::raw('MONTH(emp_applications.startDate) as forDateMonth'),
        DB::raw('YEAR(emp_applications.startDate) as forDateYear'))
        ->where('emp_applications.type', 4)
        ->where('emp_applications.active', 1)
        ->where('emp_dets.reportingId', $empId)
        ->where('emp_applications.startDate', '>=', date('Y-m-01', strtotime('-1 month')))
        ->where('emp_applications.startDate', '<=', date('Y-m-t'))
        ->groupBy('emp_applications.empId', DB::raw('MONTH(emp_applications.startDate)'), DB::raw('YEAR(emp_applications.startDate)'))
        ->orderBy('forDateYear','desc')
        ->orderBy('forDateMonth','desc')
        ->orderBy('emp_applications.empId')
        ->get();
        $travellsCt=0;
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
            ->where('startDate', '>=', date('Y-m-01'))
            ->where('startDate', '<=', date('Y-m-t'))->count();
            $travellsCt=$travellsCt+$application['pendingCt'];
        }

        $travelApplications = collect($travelApplications);
        $travelApplications = $travelApplications->sortByDesc('pendingCt')->values();

        return ['travelApplications'=>$travelApplications, 'AGFApplications'=>$AGFApplications,'exitPassApplications'=>$exitPassApplications,
        'leaveApplications'=>$leaveApplications,'leavesCt'=>$leavesCt,'agfsCt'=>$agfsCt,
        'exitPassesCt'=>$exitPassesCt,'travellsCt'=>$travellsCt];
    }

    public function getEmployeeGenMgrApp()
    {
        $empId = Auth::user()->empId;
        $userType = Auth::user()->userType;
        if($empId == '')
        {
            $empId = Auth::user()->id;
        }

        $users1 = EmpDet::where('reportingId', $empId)->pluck('id');
        $users2 = EmpDet::whereIn('reportingId', $users1)->pluck('id');

        $collection = collect($users1);
        $merged = $collection->merge($users2);
        $users = $merged->all(); 

        $AGFApplications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('departments.name as departmentName', 'designations.name as designationName', 
        'emp_dets.name as empName', 'emp_dets.empCode','emp_dets.firmType', 'emp_applications.*')
        ->where('emp_applications.type', 1)
        ->where('emp_applications.active',1)
        ->where('emp_applications.startDate', '>=', date('Y-m-01', strtotime('-1 month')))
        ->where('emp_applications.startDate', '<=', date('Y-m-t'))
        ->whereIn('emp_dets.id', $users);
        if($userType != '601')
            $AGFApplications=$AGFApplications->where('emp_dets.id', '!=', $empId);

        $AGFApplications=$AGFApplications->orderBy('emp_applications.status')
        ->get();
        $temp = collect($AGFApplications);
        $temp=$temp->where('status', 0);
        $agfsCt= $temp->count();

        $exitPassApplications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('departments.name as departmentName', 'designations.name as designationName', 
        'emp_dets.name as empName', 'emp_dets.empCode','emp_dets.firmType', 'emp_applications.*')
        ->where('emp_applications.type', 2)
        ->where('emp_applications.active',1)
        ->where('emp_applications.startDate', '>=', date('Y-m-01', strtotime('-1 month')))
        ->where('emp_applications.startDate', '<=', date('Y-m-t'))
        ->whereIn('emp_dets.id', $users);
        if($userType != '601')
            $exitPassApplications=$exitPassApplications->where('emp_dets.id', '!=', $empId);
            
        $exitPassApplications=$exitPassApplications->orderBy('emp_applications.status')
        ->get();
        $tempExitPass = collect($exitPassApplications);
        $tempExitPass=$tempExitPass->where('status', 0);
        $exitPassesCt= $tempExitPass->count();

        $leaveApplications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('departments.name as departmentName', 'designations.name as designationName', 
        'emp_dets.name as empName','emp_dets.empCode','emp_dets.firmType', 'emp_applications.*')
        ->where('emp_applications.type', 3)
        ->where('emp_applications.active',1)
        ->where('emp_applications.startDate', '>=', date('Y-m-01', strtotime('-1 month')))
        ->where('emp_applications.startDate', '<=', date('Y-m-t'))
        ->whereIn('emp_dets.id', $users);
        if($userType != '601')
            $leaveApplications=$leaveApplications->where('emp_dets.id', '!=', $empId);
            
        $leaveApplications=$leaveApplications->orderBy('emp_applications.status')
        ->get();
        $tempLeave = collect($leaveApplications);
        $tempLeave=$tempLeave->where('status', 0);
        $leavesCt= $tempLeave->count();

        $travelApplications = EmpApplication::select(DB::raw('count(id)  as totApp'),
         'empId', DB::raw('MONTH(startDate) as forDateMonth'),
        DB::raw('YEAR(startDate) as forDateYear'))
        ->where('type', 4)
        ->where('active', 1)
        ->whereIn('empId', $users);
        if($userType != '601')
            $travelApplications=$travelApplications->where('empId', '!=', $empId);
            
        $travelApplications=$travelApplications->where('startDate', '>=', date('Y-m-01', strtotime('-1 month')))
        ->where('startDate', '<=', date('Y-m-t'))
        ->groupBy('empId', DB::raw('MONTH(startDate)'), DB::raw('YEAR(startDate)'))
        ->orderBy('forDateYear','desc')
        ->orderBy('forDateMonth','desc')
        ->orderBy('empId')
        ->get();
        $travellsCt=0;
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
            ->where('startDate', '>=', date('Y-m-01'))
            ->where('startDate', '<=', date('Y-m-t'))->count();
            $travellsCt=$travellsCt+$application['pendingCt'];
        }

        $travelApplications = collect($travelApplications);
        $travelApplications = $travelApplications->sortByDesc('pendingCt')->values();

        return ['travelApplications'=>$travelApplications,'AGFApplications'=>$AGFApplications,'exitPassApplications'=>$exitPassApplications,'leaveApplications'=>$leaveApplications
        ,'leavesCt'=>$leavesCt, 'agfsCt'=>$agfsCt, 'exitPassesCt'=>$exitPassesCt, 'travellsCt'=>$travellsCt];
    }

    public function changeStatus(Request $request)
    {
        $options = $request->option;
        $applicationCt = count($options);
        if($applicationCt)
        {
            for($i=0; $i<$applicationCt; $i++)
            {
                $temp = "status".$options[$i];
                $application = EmpApplication::find($options[$i]);
                $application->approvedBy = Auth::user()->name;
                $application->status = $request->$temp;
                $application->updated_by = Auth::user()->username;
                $application->save();
                $temp="";
            }
        }
        return redirect()->back()->with("success","Application Updated Successfully...");

        // return redirect('/empApplications/applicationList')->with("success", $msg);



        // $application = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        // ->join('departments', 'emp_dets.departmentId', 'departments.id')
        // ->join('designations', 'emp_dets.designationId', 'designations.id')
        // ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        // ->select('departments.name as departmentName', 'designations.name as designationName', 
        // 'emp_dets.name as empName', 'emp_dets.reportingId','emp_dets.empCode','emp_dets.firmType', 'contactus_land_pages.branchName', 'emp_applications.*')
        // ->where('emp_applications.id', $id)
        // ->where('emp_dets.active', 1)
        // ->first();
        // if(!$application)
        //     return redirect()->back()->withInput()->with("error","Record Not Found");

        // $fromDest = ContactusLandPage::where('id', $application->fromDest)->value('branchName');
        // $toDest = ContactusLandPage::where('id', $application->toDest)->value('branchName');

        // $application['reportingName'] = EmpDet::where('id', $application->reportingId)->value('name');
        // $appHistory = EmpApplicationHistory::where('applicationId', $id)->get();
        // return view('admin.applications.applicationStatus')->with(['application'=>$application,
        //  'type'=>$type, 'fromDest'=>$fromDest, 'toDest'=>$toDest, 'appHistory'=>$appHistory]);
    }

    public function updateApplicatinStatus(Request $request)
    {
        if($request->appType == 4)
        {
            $empId = $request->empId;
            $month = $request->month;
            $userType = Auth::user()->userType;
            $appIds = $request->appId;
            $appStatus = $request->appStatus;
            $appAccount = $request->appAccount;
            $applicationCt = count($appIds);
            if($applicationCt)
            {
                for($i=0; $i<$applicationCt; $i++)
                {
                    $application = EmpApplication::find($appIds[$i]);
                    if($userType == '11' || $userType == '21')
                        $application->appRep = $request->appRep[$i];

                    if($userType == '51')
                        $application->appHr = $request->appHr[$i];
                    
                    if($userType == '61')
                        $application->status = $appAccount[$i];
                    
                    $application->updated_by = Auth::user()->username;
                    $application->save();
                   
                    $approv = EmpApplicationHistory::where('applicationId', $appIds[$i])
                    ->where('approvedBy', Auth::user()->username)
                    ->first();
                    if(!$approv)
                    {
                        $approv = new EmpApplicationHistory;
                        $approv->applicationId = $appIds[$i];
                        $approv->approvedBy = Auth::user()->username;
                        $approv->approvedAt = date('Y-m-d H:i:s');
                        $approv->save();
                    }
                }

                if($userType == '61')
                {
                    $payment = TravelAllowancePayment::where('empId', $empId)->where('month', $month)->first();
                    if(!$payment)
                    {
                        $payment = new TravelAllowancePayment;
                        $payment->empId= $empId;
                        $payment->month=$month;
                        $payment->totKm=$request->totKm;
                        $payment->totRs=$request->totRs;
                        $payment->remark=$request->remark;
                        $payment->paymentStatus=$request->paymentStatus;
                        $payment->active=1;
                        $payment->updated_by=Auth::user()->username;
                        $payment->save();
                    }
                }
            }

            if($userType == '61')
                return redirect('/reports/travellingAllowReport')->with("success", "Your Travelling Allowance Application Updated successfully..");
            else
                return redirect('/empApplications/applicationList')->with("success", "Your Travelling Allowance Application Updated successfully..");
        }
        else
        {
            $application = EmpApplication::find($request->id);
            $application->approvedBy = Auth::user()->name;
            $application->status = $request->permission;
            $application->wfh = $request->wfh;
            $application->updated_by = Auth::user()->username;
            
            if($application->save())
            {
                if($application->type == 1 && $request->permission == 1)
                {
                    $temp = AttendanceDetail::where('empId', $application->empId)
                    ->where('forDate', $application->startDate)
                    ->first();
                    if($temp)
                    {
                        $temp->AGFStatus=$application->id;
                        if($request->wfh == 'Yes')
                            $temp->percent='50';
                        
                        $temp->updated_by = Auth::user()->username;
                        $temp->save();
                    }
                }
                
                $approv = new EmpApplicationHistory;
                $approv->applicationId = $application->id;
                $approv->approvedBy = Auth::user()->username;
                $approv->approvedAt = date('Y-m-d H:i:s');
                $approv->save();
            }

            if($application->type == 1)
            {
                $msg = "Your AGF Application Updated successfully.."; 
            }
            if($application->type == 2)
                $msg = "Your Exit Pass Application Updated successfully.."; 
            if($application->type == 3)
                $msg = "Your Leave Application Updated successfully.."; 
            if($application->type == 3)
                $msg = "Your Leave Application Updated successfully.."; 

            return redirect('/empApplications/applicationList')->with("success", $msg);
        }
    }

    public function viewMore($empId, $forDate, $appType)
    {
        $startDate = date('Y-m', strtotime($forDate)).'-01';
        $endDate = date('Y-m', strtotime($forDate)).'-'.date('t', strtotime($forDate));
        $travells = EmpApplication::where('type', 4)
        ->where('empId', $empId)
        ->where('startDate', '>=', $startDate)
        ->where('startDate', '<=', $endDate)
        ->where('active', 1)
        ->orderBy('created_at')
        ->get();

        $empDet = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('emp_dets.id','emp_dets.name as empName', 'emp_dets.empCode','emp_dets.reportingId', 'emp_dets.firmType', 
        'departments.name as departmentName', 'designations.name as designationName')
        ->where('emp_dets.id', $empId)
        ->first();

        $travelAllows = TravelAllowancePayment::where('month', date('Y-m', strtotime($startDate)))->where('empId', $empId)->first();
      
        return view('admin.applications.applicationViewList')->with(['empId'=>$empId,'empDet'=>$empDet,'travells'=>$travells, 
        'startDate'=>$startDate, 'endDate'=>$endDate, 'appType'=>$appType, 'travelAllows'=>$travelAllows]);
    }

    public function exportPdf($empId, $forDate)
    {
        $startDate = date('Y-m', strtotime($forDate)).'-01';
        $endDate = date('Y-m', strtotime($forDate)).'-'.date('t', strtotime($forDate));
        $travells = EmpApplication::where('type', 4)
        ->where('empId', $empId)
        ->where('startDate', '>=', $startDate)
        ->where('startDate', '<=', $endDate)
        ->where('active', 1)
        ->orderBy('created_at')
        ->get();

        $empDet = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('emp_dets.id','emp_dets.name as empName', 'emp_dets.empCode','emp_dets.reportingId', 'emp_dets.firmType', 
        'departments.name as departmentName', 'designations.name as designationName')
        ->where('emp_dets.id', $empId)
        ->first();

        $payment = TravelAllowancePayment::where('month', date('Y-m', strtotime($startDate)))
        ->where('empId', $empId)
        ->first();

        $file = 'TAReport_'.$empDet->empCode.'_'.date('M-Y').'.pdf';
        $pdf = PDF::loadView('admin.reports.applications.travellingAllowReportPdfView',compact('travells', 'startDate', 'endDate', 'empDet','payment'));
        $canvas = $pdf->getDomPDF()->getCanvas();
        $height = $canvas->get_height();
        $width = $canvas->get_width();
        $canvas->set_opacity(.2,"Multiply");
        if($payment)
        {
            if($payment->paymentStatus == 'UnPaid' || $payment->paymentStatus == '' )
                $canvas->page_text($width/10, $height/12, 'UnPaid', null, 20, array(255,0,0),2,2,0);
            else
                $canvas->page_text($width/10, $height/12, 'Paid', null, 20, array(0, 0, 255),2,2,0);
        }
        else
        {
            $canvas->page_text($width/10, $height/12, 'UnPaid', null, 20, array(255,0,0),2,2,0);
        }
         
        return $pdf->stream($file);
    }

    // currently used for AGF
    public function AGFList(Request $request)
    { 
        $user = Auth::user(); 
        $id = $user->id; 
        $empId = $user->empId; 
        $userType = $user->userType; 
        $departmentId = $request->departmentId; 
        $designationId = $request->designationId; 

        $branchId = $request->branchId;
        if($id == 4158)
            $branches = ContactusLandPage::where('active', 1)->where('id', '!=', 11)->orderBy('branchName')->pluck('branchName', 'id');
        else
            $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');

        $departments = Department::where('active', 1)->pluck('name', 'id');

        if($request->month == '')
            $month = date('Y-m-d');
        else
            $month = date('Y-m-d', strtotime($request->month));
          
        $fromDate = date('Y-m-01', strtotime($month));
        $toDate = date('Y-m-t', strtotime($month));

        if($userType == '61' || $userType == '51' || $userType == '00')
        {
            if($branchId != '')
            {
                $empIds = EmpDet::where('branchId', $branchId)->pluck('id');
            }
            else
            {
                $empIds = EmpDet::pluck('id');
            }
        }
        elseif($userType == '101')
        {
            $empIds = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
            ->where('departments.name', 'Security Department')
            ->pluck('emp_dets.id');
        }
        else
        {
            if($userType == '501' || $userType == '401' || $userType == '301' || $userType == '201')
            {
                $empIds = EmpDet::where('reportingId', $id)->pluck('id');
            }
            elseif($empId == ''){
                $empIds = EmpDet::where('reportingId', $empId)->pluck('id');
            }
            else
            {
                if($userType == '21')
                {
                    $empIds = EmpDet::where('reportingId', $empId)->pluck('id');
                }
                elseif($userType == '11')
                {
                    $empIds1 = EmpDet::where('reportingId', $empId)->pluck('id');
                    $empIds2 = EmpDet::whereIn('reportingId', $empIds1)->pluck('id');
                    $collection = collect($empIds1);
                    $merged = $collection->merge($empIds2);
                    $empIds = $merged->all(); 
                }               
                else
                {
                    $applications = EmpApplication::select(DB::raw('count(id)  as totApp'), 'empId')
                    ->where('type', 1)
                    ->where('active', 1)
                    ->where('startDate', '>=', $fromDate)
                    ->where('startDate', '<=', $toDate)
                    ->where('empId', $empId)
                    ->groupBy('empId')
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
                        $application['pendingCt'] = EmpApplication::where('type', 1)
                        ->where('empId', $application->empId)
                        ->where('active', 1)
                        ->where('status', 0)
                        ->where('startDate', '>=', $fromDate)
                        ->where('startDate', '<=', $toDate)->count();
                        
                        $application['totalCt'] = EmpApplication::where('type', 1)
                        ->where('empId', $application->empId)
                        ->where('active', 1)
                        ->where('startDate', '>=', $fromDate)
                        ->where('startDate', '<=', $toDate)->count();
                    }

                    $applications = collect($applications);
                    $applications = $applications->sortByDesc('pendingCt')->values();

                    return view('admin.applications.AGFList')->with(['fromDate'=>$fromDate,'toDate'=>$toDate,
                    'applications'=>$applications, 'month'=>$month, 'branches'=>$branches]);
                }
            }
        }
 
        $applications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->select(DB::raw('count(emp_applications.id)  as totApp'), 'emp_applications.empId')
        ->where('emp_applications.type', 1)
        ->where('emp_applications.active', 1);

        if($branchId != '')
        {
            $applications = $applications->where('emp_dets.branchId', $branchId);
        }
        
        if($userType == '51' || $userType == "61")
        {
            if($departmentId != '')
            {
                $applications = $applications->where('emp_dets.departmentId', $departmentId);
            }

            if($designationId != '')
            {
                $applications = $applications->where('emp_dets.designationId', $designationId);
            }
        }

        if($userType == '61')
        {
            if($branchId != '')
                $applications = $applications->where('emp_dets.branchId', $branchId)->where('emp_applications.status2', 1);
            else
            {
                if($id == 4158)
                    $applications = $applications->where('emp_applications.status2', 1)->where('branchId', '!=', 11);
                else
                    $applications = $applications->where('emp_applications.status2', 1);
            }
        }
        elseif($userType == '51')
        {
            if($branchId != '')
                $applications = $applications->where('emp_dets.branchId', $branchId)->where('emp_applications.status1', 1);
            else
                $applications = $applications->where('emp_applications.status1', 1);
        }
        else
        {
            $applications = $applications->whereIn('emp_applications.empId', $empIds);
        }
       


        $applications = $applications->where('emp_applications.startDate', '>=', $fromDate)
        ->where('emp_applications.startDate', '<=', $toDate)
        ->groupBy('emp_applications.empId')
        ->orderBy('emp_applications.empId')
        ->get();
        
        foreach($applications as $application)
        {
            $empDet = EmpDet::select('departmentId', 'reportingType','designationId', 'name', 'firmType', 'empCode')->where('id', $application->empId)->first();
            $application['empName'] = $empDet->name;
            $application['empCode'] = $empDet->empCode;
            $application['firmType'] = $empDet->firmType;
            $application['departmentName'] = Department::where('id', $empDet->departmentId)->value('name');
            $application['designationName'] = Designation::where('id', $empDet->designationId)->value('name');
           
            if($empDet->reportingType == 1)
                $application['repoName'] = $reportingAuth = EmpDet::where('id', $empDet->reportingId)->value('name');
            else
                $application['repoName'] = $reportingAuth = User::where('id', $empDet->reportingId)->value('name');

            $application['pendingCt'] = EmpApplication::where('type', 1)
            ->where('empId', $application->empId)
            ->where('active', 1)
            ->where('status1', 0)
            ->where('startDate', '>=', $fromDate)
            ->where('startDate', '<=', $toDate)
            ->count();

            $application['pendingHrCt'] = EmpApplication::where('type', 1)
            ->where('empId', $application->empId)
            ->where('active', 1)
            ->where('status1', 1)
            ->where('status2', 0)
            ->where('startDate', '>=', $fromDate)
            ->where('startDate', '<=', $toDate)
            ->count();
            
            $application['acPendingCt'] = EmpApplication::where('type', 1)
            ->where('empId', $application->empId)
            ->where('active', 1)
            ->where('status1', 1)
            ->where('status2', 1)
            ->where('status', 0)
            ->where('startDate', '>=', $fromDate)
            ->where('startDate', '<=', $toDate)
            ->count();
            
            $application['totalCt'] = EmpApplication::where('type', 1)
            ->where('empId', $application->empId)
            ->where('active', 1)
            ->where('startDate', '>=', $fromDate)
            ->where('startDate', '<=', $toDate)
            ->count();
        }

        $applications = collect($applications);
        if($userType == '61')
            $applications = $applications->sortByDesc('acPendingCt')->values();
        elseif($userType == '51')
            $applications = $applications->sortByDesc('pendingHrCt')->values();
        else
            $applications = $applications->sortByDesc('acPendingCt')->values();

        return view('admin.applications.AGFList')->with(['fromDate'=>$fromDate,'toDate'=>$toDate,'departments'=>$departments,
        'applications'=>$applications, 'month'=>$month, 'branches'=>$branches, 'branchId'=>$branchId, 'departmentId'=>$departmentId,
        'designationId'=>$designationId]);
    }

    public function AGFShow($empId, $forDate, $appType)
    {
        $user = Auth::user(); 
        $userType = $user->userType; 

        $startDate = date('Y-m', strtotime($forDate)).'-01';
        $endDate = date('Y-m', strtotime($forDate)).'-'.date('t', strtotime($forDate));
        $month = date('Y-m', strtotime($forDate));
        $applications = EmpApplication::where('empId', $empId)
        ->where('startDate', '>=', $startDate)
        ->where('startDate', '<=', $endDate)
        ->where('type', $appType)
        ->where('active', 1);

        if($appType == '1' || $appType == '4')
        {
            if($userType == '61')
            {
                $applications = $applications->where('status2', 1);
            }

            if($userType == '51')
            {
                $applications = $applications->where('status1', 1);
            }
        }

        $common = CommonForm::where('active', 1)->first();

        $applications = $applications->orderBy('created_at')
        ->orderBy('status1')
        ->get();        

        $empDet = EmpDet::select('emp_dets.id','emp_dets.name as empName', 'emp_dets.empCode',
        'emp_dets.reportingId', 'emp_dets.firmType','emp_dets.departmentId', 'emp_dets.designationId')
        ->where('emp_dets.id', $empId)
        ->first();
        $reportingAuth = EmpDet::where('id', $empDet->reportingId)->value('name');
        if($reportingAuth == '')
            $reportingAuth = User::where('id', $empDet->reportingId)->value('name');

        $empDet['departmentName'] = Department::where('id', $empDet->departmentId)->value('name');
        $empDet['designationName'] = Designation::where('id', $empDet->designationId)->value('name');
      
        return view('admin.applications.AGFShow')->with(['common'=>$common,'startDate'=>$startDate,
        'endDate'=>$endDate,'applications'=>$applications,'month'=>$month, 
        'empDet'=>$empDet, 'appType'=>$appType,'forDate'=>$forDate,'reportingAuth'=>$reportingAuth]);
    }

    public function exportAGFExcel($month, $type, $branchId, $departmentId, $designationId)
    {
        $rowCount=0;
        if($type == 1)
            $fileName = 'AGF Summary Report '.date('M-Y', strtotime($month)).'.xlsx';
        else
            $fileName = 'AGF Detail Report '.date('M-Y', strtotime($month)).'.xlsx';

        return Excel::download(new AGFApplicationsExport($month, $type, $rowCount, $branchId, $departmentId, $designationId), $fileName);
    }

    public function exportAGFPdf($month, $type)
    {
        $user = Auth::user();
        $userType = Auth::user()->userType;
        $fromDate = date('Y-m-01', strtotime($month));
        $toDate = date('Y-m-t', strtotime($month));

        if($type == 1)
        {
            if($userType == '61' || $userType == '51' || $userType == '00' || $userType == '501' || $userType == '401' || $userType == '301' || $userType == '201')
            {
                $empIds = EmpDet::pluck('id');
            }
            elseif($userType == '101')
            {
                $empIds = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
                ->where('departments.name', 'Security Department')
                ->pluck('emp_dets.id');
            }
            else
            {
                if($userType == '21')
                {
                    $empIds = EmpDet::where('reportingId', $empId)->pluck('id');
                }
                elseif($userType == '11')
                {
                    $empIds1 = EmpDet::where('reportingId', $empId)->pluck('id');
                    $empIds2 = EmpDet::whereIn('reportingId', $empIds1)->pluck('id');
                    $collection = collect($empIds1);
                    $merged = $collection->merge($empIds2);
                    $empIds = $merged->all(); 
                }
                else
                {
                    $empIds = EmpDet::where('id', $empId)->pluck('id');
                }
            }

            $applications = EmpApplication::select(DB::raw('count(id)  as totApp'), 'empId')
            ->where('type', 1)
            ->where('active', 1);
            if($userType == '61')
            {
                $applications = $applications->where('status2', 1);
            }
            elseif($userType == '51')
            {
                $applications = $applications->where('status1', 1);
            }
            else
            {
                $applications = $applications->whereIn('empId', $empIds);
            }

            $applications = $applications->where('startDate', '>=', $fromDate)
            ->where('startDate', '<=', $toDate)
            ->whereIn('empId', $empIds)
            ->groupBy('empId')
            ->orderBy('empId')
            ->get();

            $temp=$apps=[];
            $i=1;
            foreach($applications as $application)
            {
                $empDet = EmpDet::select('departmentId', 'designationId', 'name', 'firmType', 'empCode')->where('id', $application->empId)->first();
                $temp['sNo'] = $i++;
                $temp['empCode'] = $empDet->empCode;
                $temp['empName'] = $empDet->name;
                $temp['departmentName'] = Department::where('id', $empDet->departmentId)->value('name');
                $temp['designationName'] = Designation::where('id', $empDet->designationId)->value('name');
                
                $temp['totalCt'] = EmpApplication::where('type', 1)
                ->where('empId', $application->empId)
                ->where('active', 1)
                ->where('startDate', '>=', $fromDate)
                ->where('startDate', '<=', $toDate)
                ->count();

                $temp['totalCt'] = ($temp['totalCt'] == 0)?'0':$temp['totalCt'];

                $temp['reportAuthCt'] = EmpApplication::where('type', 1)
                ->where('empId', $application->empId)
                ->where('active', 1)
                ->where('status1', 0)
                ->where('startDate', '>=', $fromDate)
                ->where('startDate', '<=', $toDate)
                ->count();

                $temp['reportAuthCt'] = ($temp['reportAuthCt'] == 0)?'0':$temp['reportAuthCt'];

                $temp['hrPendingCt'] = EmpApplication::where('type', 1)
                ->where('empId', $application->empId)
                ->where('active', 1)
                ->where('status1', 1)
                ->where('status2', 0)
                ->where('startDate', '>=', $fromDate)
                ->where('startDate', '<=', $toDate)
                ->count();

                $temp['hrPendingCt'] = ($temp['hrPendingCt'] == 0)?'0':$temp['hrPendingCt'];

                $temp['accountPendingCt'] = EmpApplication::where('type', 1)
                ->where('empId', $application->empId)
                ->where('active', 1)
                ->where('status1', 1)
                ->where('status2', 1)
                ->where('startDate', '>=', $fromDate)
                ->where('startDate', '<=', $toDate)->count();

                $temp['accountPendingCt'] = ($temp['accountPendingCt'] == 0)?'0':$temp['accountPendingCt'];

                array_push($apps, $temp);
                
            }

            $apps = collect($apps);

            $this->rowCount = count($apps);

            if($userType == '51')
                $applications = $apps->sortByDesc('hrPendingCt')->values();
            elseif($userType == '21' || $userType == '11')
                $applications = $apps->sortByDesc('reportAuthCt')->values();
            else
                $applications = $apps->sortByDesc('accountPendingCt')->values();
        }
        else
        {
            $userType = $user->userType; 
            if($userType == '61' || $userType == '51' || $userType == '00' || $userType == '501' || $userType == '401' || $userType == '301' || $userType == '201')
            {
                $empIds = EmpDet::pluck('id');
            }
            elseif($userType == '101')
            {
                $empIds = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
                ->where('departments.name', 'Security Department')
                ->pluck('emp_dets.id');
            }
            else
            {
                if($userType == '21')
                {
                    $empIds = EmpDet::where('reportingId', $empId)->pluck('id');
                }
                elseif($userType == '11')
                {
                    $empIds1 = EmpDet::where('reportingId', $empId)->pluck('id');
                    $empIds2 = EmpDet::whereIn('reportingId', $empIds1)->pluck('id');
                    $collection = collect($empIds1);
                    $merged = $collection->merge($empIds2);
                    $empIds = $merged->all(); 
                }
                else
                {
                    $empIds = EmpDet::where('id', $empId)->pluck('id');
                }
            }

            $applications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->Select('emp_dets.name as empName', 'emp_dets.empCode','departments.name as departmentName',
            'designations.name as designationName', 'contactus_land_pages.branchName','emp_applications.*')
            ->where('emp_applications.startDate', '>=', $fromDate)
            ->where('emp_applications.startDate', '<=', $toDate)
            ->where('emp_applications.type', 1)
            ->whereIn('emp_applications.empId', $empIds)
            ->where('emp_applications.active', 1);
            if($userType == '61')
            {
                $applications = $applications->where('emp_applications.status2', 1);
            }

            if($userType == '51')
            {
                $applications = $applications->where('emp_applications.status1', 1);
            }

            $applications = $applications->orderBy('emp_applications.created_at')
            ->orderBy('emp_applications.status1')
            ->get();        
        }

        $file = 'AGF List '.date('M-Y',strtotime($month)).'.pdf';
        
        $pdf = PDF::loadView('admin.pdfs.AGFSummary',compact('month', 'type', 'applications'));
        if($type == 2)
            $pdf->setPaper('A4', 'landscape');
            
        return $pdf->stream($file);
    }

    public function changeAGFStatus(Request $request)
    {
        $options = $request->appId;
        $reason = $request->rejectReason;
        if(!$options)
            return redirect()->back()->withInput()->with("error","Please Check at least 1 checkbox...");

        $applicationCt = count($options);
        if($applicationCt)
        {
            for($i=0; $i<$applicationCt; $i++)
            {
                $temp = "status".$options[$i];
                $application = EmpApplication::find($options[$i]);
                $application->rejectReason = $reason[$i];
                if(Auth::user()->userType != '61' && Auth::user()->userType != '51') //Reporting Auth
                {
                    if($application->status1 != $request->$temp)
                    {
                        $application->approvedBy1 = Auth::user()->name;
                        $application->status1 = $request->$temp;
                        $application->updatedAt1 = date('Y-m-d H:i:s');
                     
                        $tp = AttendanceDetail::where('empId', $application->empId)
                        ->where('forDate', $application->startDate)
                        ->first();

                        if($tp)
                        {
                            if($request->$temp == 1)
                            {
                                $tp->AGFDayStatus=$application->dayStatus;
                                $tp->repAuthStatus=$application->id;
                                $tp->updated_by = Auth::user()->username;
                            }
                            else
                            {
                                $tp->AGFStatus=0;
                                $tp->repAuthStatus=0;
                                $tp->HRStatus=0;
                            }

                            $tp->save();
                        }
                    }
                }
                elseif(Auth::user()->userType == '61') //Account department
                {
                    if($application->status != $request->$temp)
                    {
                        $application->approvedBy = Auth::user()->name;
                        $application->status = $request->$temp;
                        $application->rejectReason = $reason[$i];
                        $application->updatedAt3 = date('Y-m-d H:i:s');
                        
                        $tp = AttendanceDetail::where('empId', $application->empId)
                        ->where('forDate', $application->startDate)
                        ->first();

                        if($tp)
                        {
                            if($request->$temp == 1)
                            {
                                $tp->AGFDayStatus=$application->dayStatus;
                                $tp->repAuthStatus=$application->id;
                                $tp->HRStatus=$application->id;
                                $tp->AGFStatus=$application->id;
                            }
                            else
                            {
                                $tp->AGFStatus=0;
                                $tp->repAuthStatus=0;
                                $tp->HRStatus=0;
                            }

                            $tp->updated_by = Auth::user()->username;
                            $tp->save();
                        }
                    }
                }
                else //HR Department
                {
                    if($application->status2 != $request->$temp)
                    {
                        $application->approvedBy2 = Auth::user()->name;
                        $application->status2 = $request->$temp;
                        $application->rejectReason = $reason[$i];
                        $application->updatedAt2 = date('Y-m-d H:i:s');
                        
                        $tp = AttendanceDetail::where('empId', $application->empId)
                        ->where('forDate', $application->startDate)
                        ->first();

                        if($tp)
                        {
                            if($request->$temp == 1)
                            {
                                $tp->AGFDayStatus=$application->dayStatus;
                                $tp->repAuthStatus=$application->id;
                                $tp->HRStatus=$application->id;
                            }
                            else
                            {
                                $tp->AGFStatus=0;
                                $tp->repAuthStatus=0;
                                $tp->HRStatus=0;
                            }

                            $tp->updated_by = Auth::user()->username;
                            $tp->save();
                        }
                    }
                }

                $application->updated_by = Auth::user()->username;
                $application->save();
                $temp="";
                $forDate = $application->startDate;
                $branchId = EmpDet::where('id', $application->empId)->value('branchId');
            }
        }
        return redirect('/empApplications/AGFList?month='.date('Y-m', strtotime($forDate)).'&branchId='.$branchId)->with("success","Application Updated Successfully...");
    }

    public function updateAttendAGF(Request $request)
    {
        $application = EmpApplication::find($request->applicationId);
        $userType = Auth::user()->userType;
        $empName = EmpDet::where('id', $application->empId)->value('name');
        if($userType == '51')
        {
            $application->status2=$request->status;
            $application->rejectReason=$request->rejectReason;
            $application->updatedAt2=date('Y-m-d h:i:s');
            $application->approvedBy2=Auth::user()->username;

            if($request->status == 1)
            {
                $attendance = AttendanceDetail::where('forDate', $application->startDate)->where('empId', $application->empId)->first();
                if($attendance)
                {
                    $attendance->HRStatus=$application->id; 
                    $attendance->updated_by=Auth::user()->username; 
                    $attendance->save();
                }
            }          
        }

        if($userType == '61')
        {
            $application->status=$request->status;
            $application->rejectReason=$request->rejectReason;
            $application->updatedAt3=date('Y-m-d h:i:s');
            $application->approvedBy=Auth::user()->username;
            
            if($application->status == 1)
            {
                $attendance = AttendanceDetail::where('forDate', $application->startDate)->where('empId', $application->empId)->first();
                if($attendance)
                {
                    $attendance->AGFStatus=$application->id; 
                    $attendance->AccountStatus=$application->id;     
                    $attendance->AGFDayStatus=$application->dayStatus;   
                    $attendance->updated_by=Auth::user()->username; 
                    $attendance->save();
                }
            }
        }   

        $application->save();

        return redirect()->back()->withInput()->with("success",date('d-M', strtotime($application->startDate))." ".$empName."'s "." AGF have been updated successfully.");
    }

    public function exitPassList(Request $request)
    {
        $user = Auth::user(); 
        $id = $user->id; 
        $empId = $user->empId; 
        $userType = $user->userType; 

        if($request->month == '')
            $month = date('Y-m-d');
        else
            $month = date('Y-m-d', strtotime($request->month));
          
        $fromDate = date('Y-m-01', strtotime($month));
        $toDate = date('Y-m-t', strtotime($month));
        if($userType == '51'  || $userType == '00' || $userType == '501' || $userType == '401' || $userType == '301' || $userType == '201')
        {
            $empIds = EmpDet::where('active', 1)->pluck('id');
        }
        elseif($userType == '101')
        {
            $empIds = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
            ->where('emp_dets.active', 1)
            ->where('departments.name', 'Security Department')
            ->pluck('emp_dets.id');
        }
        else
        {
            if($empId == '')
                $empIds = EmpDet::where('reportingId', $empId)->where('reportingType', 2)->where('active', 1)->pluck('id');
            else
            {
                if($userType == '21')
                {
                    $empIds = EmpDet::where('reportingId', $empId)->where('active', 1)->pluck('id');
                }
                elseif($userType == '11')
                {
                    $empIds1 = EmpDet::where('reportingId', $empId)->where('active', 1)->pluck('id');
                    $empIds2 = EmpDet::whereIn('reportingId', $empIds1)->where('active', 1)->pluck('id');
                    $collection = collect($empIds1);
                    $merged = $collection->merge($empIds2);
                    $empIds = $merged->all(); 
                }
            }
        }

        $applications = EmpApplication::select(DB::raw('count(id)  as totApp'), 'empId')
        ->where('type', 2)
        ->where('active', 1)
        ->where('startDate', '>=', $fromDate)
        ->where('startDate', '<=', $toDate)
        ->whereIn('empId', $empIds)
        ->groupBy('empId')
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

        $applications = collect($applications);
        $applications = $applications->sortByDesc('pendingCt')->values();

        return view('admin.applications.exitPassList')->with(['fromDate'=>$fromDate,'toDate'=>$toDate,
        'applications'=>$applications, 'month'=>$month]);
    }

    public function exitPassShow($empId, $forDate, $appType)
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
      
        return view('admin.applications.exitPassShow')->with(['startDate'=>$startDate,
        'endDate'=>$endDate,'applications'=>$applications, 
        'empDet'=>$empDet, 'appType'=>$appType,'forDate'=>$forDate]);
    }

    public function changeExitPassStatus(Request $request)
    {
        $appId = $request->appId;
        $applicationCt = count($appId);
        if($applicationCt)
        {
            for($i=0; $i<$applicationCt; $i++)
            {
                $temp = "status". $appId[$i];
                $application = EmpApplication::find($appId[$i]);
                $application->approvedBy = Auth::user()->name;
                $application->status = $request->$temp;
                $application->rejectReason = $request->rejectReason[$i];
                $application->updated_by = Auth::user()->username;
                $application->save();
                $temp="";
                $forDate = $application->startDate;
            }
        }
        return redirect('/empApplications/exitPassList?month='.date('Y-m', strtotime($forDate)))->with("success","Application Updated Successfully...");
    }

    public function exportExitPassExcel($month, $type)
    {
        $rowCount=0;
        if($type == 1)
            $fileName = 'Exit Pass Summary Report '.date('M-Y', strtotime($month)).'.xlsx';
        else
            $fileName = 'Exit Pass Detail Report '.date('M-Y', strtotime($month)).'.xlsx';

            return Excel::download(new ExitPassApplicationsExport($month, $type, $rowCount), $fileName);
    } 

    public function travellingTranspList(Request $request)
    {
        $user = Auth::user(); 
        $id = $user->id; 
        $empId = $user->empId; 
        $userType = $user->userType; 

        if($request->month == '')
            $month = date('Y-m-d');
        else
            $month = date('Y-m-d', strtotime($request->month));
          
        $fromDate = date('Y-m-01', strtotime($month));
        $toDate = date('Y-m-t', strtotime($month));

        if($userType == '61' || $userType == '51'  || $userType == '00' || $userType == '501' || $userType == '401' || $userType == '301' || $userType == '201')
        {
            $empIds = EmpDet::where('active', 1)->pluck('id');
        }
        elseif($userType == '101')
        {
            $empIds = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
            ->where('emp_dets.active', 1)
            ->where('departments.name', 'Security Department')
            ->pluck('emp_dets.id');
        }
        else
        {
            if($empId == '')
                $empIds = EmpDet::where('reportingId', $empId)->where('reportingType', 2)->where('active', 1)->pluck('id');
            else
            {
                if($userType == '21')
                {
                    $empIds = EmpDet::where('reportingId', $empId)->where('active', 1)->pluck('id');
                }
                elseif($userType == '11')
                {
                    $empIds1 = EmpDet::where('reportingId', $empId)->where('active', 1)->pluck('id');
                    $empIds2 = EmpDet::whereIn('reportingId', $empIds1)->where('active', 1)->pluck('id');
                    $collection = collect($empIds1);
                    $merged = $collection->merge($empIds2);
                    $empIds = $merged->all(); 
                }
            }
        }

        $applications = EmpApplication::select(DB::raw('count(id)  as totApp'), 'empId')
        ->where('type', 4);
        if($userType == '61')
        {
            $applications =$applications->where('status2', 1);
        }

        if($userType == '51')
        {
            $applications =$applications->where('status1', 1);
        }

        if($userType != '61' && $userType != '51')
            $applications =$applications->where('status1', 0);

        $applications =$applications->where('active', 1)
        ->where('startDate', '>=', $fromDate)
        ->where('startDate', '<=', $toDate)
        ->whereIn('empId', $empIds)
        ->groupBy('empId')
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
            
            $application['pendingCt'] = EmpApplication::where('type', 4)
            ->where('empId', $application->empId)
            ->where('active', 1)
            ->where('status1', 0)
            ->where('startDate', '>=', $fromDate)
            ->where('startDate', '<=', $toDate)
            ->count();

            $application['pendingHrCt'] = EmpApplication::where('type', 4)
            ->where('empId', $application->empId)
            ->where('active', 1)
            ->where('status1', 1)
            ->where('startDate', '>=', $fromDate)
            ->where('startDate', '<=', $toDate)
            ->count();

            $application['acPendingCt'] = EmpApplication::where('type', 4)
            ->where('empId', $application->empId)
            ->where('active', 1)
            ->where('status', 0)
            ->where('startDate', '>=', $fromDate)
            ->where('startDate', '<=', $toDate)
            ->count();
            
            $application['totalCt'] = EmpApplication::where('type', 4)
            ->where('empId', $application->empId)
            ->where('active', 1)
            ->where('startDate', '>=', $fromDate)
            ->where('startDate', '<=', $toDate)
            ->count();
        }

        $applications = collect($applications);
        $applications = $applications->sortByDesc('pendingCt')->values();

        return view('admin.applications.travellingTranspList')->with(['fromDate'=>$fromDate,'toDate'=>$toDate,
        'applications'=>$applications, 'month'=>$month]);
    }

    public function travellingTranspShow($empId, $forDate, $appType)
    {
        $user = Auth::user(); 
        $userType = $user->userType; 

        $startDate = date('Y-m', strtotime($forDate)).'-01';
        $endDate = date('Y-m', strtotime($forDate)).'-'.date('t', strtotime($forDate));
       
        $applications = EmpApplication::where('empId', $empId)
        ->where('startDate', '>=', $startDate)
        ->where('startDate', '<=', $endDate)
        ->where('type', $appType)
        ->where('active', 1)
        ->orderBy('created_at')
        ->get();    
        
        $pendingApplications = EmpApplication::where('empId', $empId)
        ->where('startDate', '>=', $startDate)
        ->where('startDate', '<=', $endDate)
        ->where('type', $appType);
        if($userType != '51' && $userType != '61')
        {
            $pendingApplications = $pendingApplications->where('status1', 0);
        }

        if($userType == '51')
        {
            $pendingApplications = $pendingApplications->where('status1', 1)
            ->where('status2', 0);
        }

        if($userType == '61')
        {
            $pendingApplications = $pendingApplications->where('status2', 1)
            ->where('status', 0);
        }
        
        $pendingApplications = $pendingApplications->where('active', 1)
        ->orderBy('created_at')
        ->get();    

        $empDet = EmpDet::select('emp_dets.id','emp_dets.name as empName', 'emp_dets.empCode',
        'emp_dets.reportingId', 'emp_dets.firmType','emp_dets.departmentId', 'emp_dets.designationId')
        ->where('emp_dets.id', $empId)
        ->first();
        $empDet['departmentName'] = Department::where('id', $empDet->departmentId)->value('name');
        $empDet['designationName'] = Designation::where('id', $empDet->designationId)->value('name');
      
        return view('admin.applications.travellingTranspShow')->with(['startDate'=>$startDate,
        'endDate'=>$endDate,'applications'=>$applications,'pendingApplications'=>$pendingApplications 
        ,'empDet'=>$empDet, 'appType'=>$appType,'empId'=>$empId,'forDate'=>$forDate]);
    }

    public function exportPdfTA($empId, $month)
    {
        $user = Auth::user(); 
        $userType = $user->userType; 

        $startDate = date('Y-m', strtotime($month)).'-01';
        $endDate = date('Y-m', strtotime($month)).'-'.date('t', strtotime($month));
       
        $applications = EmpApplication::where('empId', $empId)
        ->where('startDate', '>=', $startDate)
        ->where('startDate', '<=', $endDate)
        ->where('type', 4)
        ->where('active', 1)
        ->orderBy('created_at')
        ->get();    
        
        $empDet = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.id','emp_dets.name as empName', 'emp_dets.empCode',
        'emp_dets.reportingId', 'emp_dets.firmType','emp_dets.departmentId', 
        'emp_dets.designationId','contactus_land_pages.branchName', 'departments.name as departmentName',
        'designations.name as designationName')
        ->where('emp_dets.id', $empId)
        ->first();
        
        $file = 'TA_'.$empDet->empCode.'_'.date('M-Y').'.pdf';
        $pdf = PDF::loadView('admin.applications.travellingAllowReportPDFNew',compact('applications', 'startDate', 'endDate', 'empDet'));
        return $pdf->stream($file);
    }

    public function changeTravellingTranspStatus(Request $request)
    {
        $flagType = $request->flagType;
        if($flagType == 1)
        {
            $options = $request->appId;
            $applicationCt = count($options);
            
            if($applicationCt)
            {
                for($i=0; $i<$applicationCt; $i++)
                {
                    $temp = "status".$options[$i];
                    $application = EmpApplication::find($options[$i]);

                    if(Auth::user()->userType == '61')
                    {
                        $application->approvedBy = Auth::user()->name;
                        $application->updatedAt1 = date('Y-m-d H:i:s');
                        $application->status = $request->$temp;
                    }
                    elseif(Auth::user()->userType == '51')
                    {
                        $application->approvedBy2 = Auth::user()->name;
                        $application->updatedAt2 = date('Y-m-d H:i:s');
                        $application->status2 = $request->$temp;
                    }
                    else
                    {
                        $application->approvedBy1 = Auth::user()->name;
                        $application->updatedAt3 = date('Y-m-d H:i:s');
                        $application->status1 = $request->$temp;
                    }

                    $application->updated_by = Auth::user()->username;
                    $application->save();
                    $temp="";
                    $forDate = $application->startDate;
                }
            }
        }
        else
        {
            $options = $request->option;
            if(!$options)
                return redirect()->back()->withInput()->with("error","Please Check at least 1 checkbox...");

            $applicationCt = count($options);
            
            if($applicationCt)
            {
                for($i=0; $i<$applicationCt; $i++)
                {
                    $temp = "paymentStatus".$options[$i];
                    $application = EmpApplication::find($options[$i]);
                    $application->approvedBy = Auth::user()->name;
                    $application->paymentStatus = $request->$temp;
                    $application->updated_by = Auth::user()->username;
                    $application->save();
                    $temp="";
                    $forDate = $application->startDate;
                }
            }
        }

        return redirect('/empApplications/travellingTranspList?month='.date('Y-m', strtotime($forDate)))->with("success","Application Updated Successfully...");
    }

    public function exportTravellingAllowExcel($month, $type)
    {
        $rowCount=0;
        if($type == 1)
            $fileName = 'Travelling Allowance Summary Report '.date('M-Y', strtotime($month)).'.xlsx';
        else
            $fileName = 'Travelling Allowance Detail Report '.date('M-Y', strtotime($month)).'.xlsx';

        return Excel::download(new TravellingAllowApplicationsExport($month, $type, $rowCount), $fileName);
    }


    public function printTravellingAllow($flag, $empId, $month)
    {
        $startDate = date('Y-m-d', strtotime($month));
        $endDate = date('Y-m-t', strtotime($month));
        $travells = EmpApplication::where('type', 4)
        ->where('empId', $empId)
        ->where('status', $flag)
        ->where('startDate', '>=', $startDate)
        ->where('startDate', '<=', $endDate)
        ->where('active', 1)
        ->orderBy('created_at')
        ->get();

        $empDet = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('emp_dets.id','emp_dets.name as empName', 'emp_dets.empCode','emp_dets.reportingId', 'emp_dets.firmType', 
        'departments.name as departmentName', 'designations.name as designationName')
        ->where('emp_dets.id', $empId)
        ->first();

        $payment = TravelAllowancePayment::where('month', date('Y-m', strtotime($startDate)))
        ->where('empId', $empId)
        ->first();

        $file = 'TAReport_'.$empDet->empCode.'_'.date('M-Y').'.pdf';
        $pdf = PDF::loadView('admin.reports.applications.travellingAllowReportPdfView',compact('travells', 'startDate', 'endDate', 'empDet','payment'));
        // $canvas = $pdf->getDomPDF()->getCanvas();
        // $height = $canvas->get_height();
        // $width = $canvas->get_width();
        // $canvas->set_opacity(.2,"Multiply");
        // if($payment)
        // {
        //     if($payment->paymentStatus == 'UnPaid' || $payment->paymentStatus == '' )
        //         $canvas->page_text($width/10, $height/12, 'UnPaid', null, 20, array(255,0,0),2,2,0);
        //     else
        //         $canvas->page_text($width/10, $height/12, 'Paid', null, 20, array(0, 0, 255),2,2,0);
        // }
        // else
        // {
        //     $canvas->page_text($width/10, $height/12, 'UnPaid', null, 20, array(255,0,0),2,2,0);
        // }
         
        return $pdf->stream($file);
    }

    public function leaveList(Request $request)
    {
        $user = Auth::user(); 
        $id = $user->id; 
        $empId = $user->empId; 
        $userType = $user->userType; 

        if($request->month == '')
            $month = date('Y-m-d');
        else
            $month = date('Y-m-d', strtotime($request->month));
          
        $fromDate = date('Y-m-01', strtotime($month));
        $toDate = date('Y-m-t', strtotime($month));

        if($userType == '51'  || $userType == '00' || $userType == '501' || $userType == '401' || $userType == '301' || $userType == '201')
        {
            $empIds = EmpDet::where('active', 1)->pluck('id');
        }
        elseif($userType == '101')
        {
            $empIds = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
            ->where('emp_dets.active', 1)
            ->where('departments.name', 'Security Department')
            ->pluck('emp_dets.id');
        }
        else
        {
            if($empId == '')
                $empIds = EmpDet::where('reportingId', $empId)->where('reportingType', 2)->where('active', 1)->pluck('id');
            else
            {
                if($userType == '21')
                {
                    $empIds = EmpDet::where('reportingId', $empId)->where('active', 1)->pluck('id');
                }
                elseif($userType == '11')
                {
                    $empIds1 = EmpDet::where('reportingId', $empId)->where('active', 1)->pluck('id');
                    $empIds2 = EmpDet::whereIn('reportingId', $empIds1)->where('active', 1)->pluck('id');
                    $collection = collect($empIds1);
                    $merged = $collection->merge($empIds2);
                    $empIds = $merged->all(); 
                }
            }
        }

        $applications = EmpApplication::select(DB::raw('count(id)  as totApp'), 'empId')
        ->where('type', 3)
        ->where('active', 1)
        ->where('startDate', '>=', $fromDate)
        ->where('startDate', '<=', $toDate)
        ->whereIn('empId', $empIds)
        ->groupBy('empId')
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

        return view('admin.applications.leaveList')->with(['fromDate'=>$fromDate,'toDate'=>$toDate,
        'applications'=>$applications, 'month'=>$month]);
    }

    public function leaveShow($empId, $forDate, $appType)
    {
        $startDate = date('Y-m', strtotime($forDate)).'-01';
        $endDate = date('Y-m', strtotime($forDate)).'-'.date('t', strtotime($forDate));
       
        $applications = EmpApplication::where('empId', $empId)
        ->where('startDate', '>=', $startDate)
        ->where('startDate', '<=', $endDate)
        ->where('type', 3)
        ->where('active', 1)
        ->orderBy('created_at')
        ->get();        

        $empDet = EmpDet::select('emp_dets.id','emp_dets.name as empName', 'emp_dets.empCode',
        'emp_dets.reportingId', 'emp_dets.firmType','emp_dets.departmentId', 'emp_dets.designationId')
        ->where('emp_dets.id', $empId)
        ->first();
        $empDet['departmentName'] = Department::where('id', $empDet->departmentId)->value('name');
        $empDet['designationName'] = Designation::where('id', $empDet->designationId)->value('name');
      
        return view('admin.applications.leaveShow')->with(['startDate'=>$startDate,
        'endDate'=>$endDate,'applications'=>$applications, 
        'empDet'=>$empDet, 'appType'=>$appType,'forDate'=>$forDate]);
    }

    public function changeLeaveStatus(Request $request)
    {
        $appId = $request->appId;
        $applicationCt = count($appId);
       
        if($applicationCt)
        {
            for($i=0; $i<$applicationCt; $i++)
            {
                $temp = "status".$appId[$i];
                $application = EmpApplication::find($appId[$i]);
                $application->approvedBy = Auth::user()->name;
                $application->status = $request->$temp;
                $application->rejectReason = $request->rejectReason[$i];
                $application->updated_by = Auth::user()->username;
                $application->save();
                // if($application->save())
                // {
                //     AttendanceDetail::where('forDate', $application->startDate)->where('', 3)->
                // }
                $temp="";
                $forDate = $application->startDate;
            }
        }
        return redirect('/empApplications/leaveList?month='.date('Y-m', strtotime($forDate)))->with("success","Application Updated Successfully...");
    }

    public function exportLeaveExcel($month, $type)
    {
        $rowCount=0;
        if($type == 1)
            $fileName = 'Leave Application Summary Report '.date('M-Y', strtotime($month)).'.xlsx';
        else
            $fileName = 'Leave Application Detail Report '.date('M-Y', strtotime($month)).'.xlsx';

            return Excel::download(new LeaveApplicationsExport($month, $type, $rowCount), $fileName);
    }
    
    public function compOffApplication()
    {
        return view('admin.applications.compOffApplication');
    }
        
    public function applyCompoff()
    {
        return view('admin.applications.applyCompoff');
    }
    public function compdList()
    {
        return view('admin.applications.compdList');
    }


    public function concessionList()
    {
        return view('admin.applications.concessionList');
    }
    public function applyConcession()
    {
        return view('admin.applications.applyConcession');
    }
    public function dConcessionList()
    {
        return view('admin.applications.dConcessionList');
    }

}
