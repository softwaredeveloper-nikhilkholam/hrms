<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\ApprisalExport;

use App\Appraisal;
use App\EmpDet;
use App\SignatureFile;
use App\EmployeeLetter;
use App\ContactusLandPage;
use App\Department;
use App\Retention;
use App\MonthlyAttendanceSummary;
use DB;
use Excel;
use Auth;

class AppraisalsController extends Controller
{
    public function index(Request $request)
    {
    //     $employees = EmpDet::where('jobJoingDate', '>=', '2025-03-01')->where('active', 1)->get();
    //     foreach($employees as $emp)
    //     {
    //         if($emp->salaryScale != 0)
    //         {
    //             // return $emp->salaryScale;
    //             $cutAmount = ($emp->salaryScale / 2) / 5; 
    //             $retention = new Retention;
    //             $retention->empId = $emp->id;
    //             $retention->retentionAmount = $cutAmount;
    //             $retention->month = '2025-07';
    //             $retention->remark = 'Retention for the month of Jul 2025';
    //             $retention->updated_by = 'HR Manager 01';
    //             $retention->save();
    //         }
    //     } 
    //     return 'asdf';
    //    $apprs = Appraisal::where('hikeRs', '!=', 0)->where('month', '>=', '2025-03')->where('month', '<=', '2025-07')->get();
    //     foreach($apprs as $row)
    //     {
    //         if($row->hikeRs != 0)
    //         {
    //              $cutAmount = $row->hikeRs / 2;
    //             $retention = new Retention;
    //             $retention->empId = $row->empId;
    //             $retention->retentionAmount = $cutAmount;
    //             $retention->month = '2025-07';
    //             $retention->remark = 'Retention for the month of Jul 2025';
    //             $retention->updated_by = 'HR Manager 01';
    //             $retention->save();

    //             // {
    //             //     $summary = MonthlyAttendanceSummary::where('month', '2025-07')->where('empId', $row->empId)->first();
    //             //     if($summary)
    //             //     {
    //             //         $summary->retention = $retention->retentionAmount;
    //             //         $summary->save();
    //             //     }
    //             // }
    //         }
    //     }

    //     return 'adsf';
        // $summary = MonthlyAttendanceSummary::where('month', '2025-07')->get();
        // foreach($summary as $row)
        // {
        //     $temp = Retention::where('month', '2025-07')->where('empId', $row->empId)->first();
        //     if($temp)
        //     {
        //         $row->retention = $temp->retentionAmount; 
        //         $row->save(); 
        //     }
        // }

        // return 'dd';

        $year = $request->year ?? date('Y');

       $lists = Appraisal::join('emp_dets', 'appraisals.empId', '=', 'emp_dets.id')
        ->join('designations', 'appraisals.designationId', '=', 'designations.id')
        ->select(
            'emp_dets.name',
            'emp_dets.jobJoingDate',
            'designations.name as designationName',
            'appraisals.*'
        )
        ->where('appraisals.active', 1)
        ->orderBy('appraisals.created_at', 'desc')
        ->where('appraisals.month', '>=', $year . '-01')
        ->where('appraisals.month', '<=', $year . '-12')
        ->get();

        
        return view('admin.appraisals.list', compact('year', 'lists'));
    }

    public function create(Request $request)
    {
        $empCode = $request->empCode;
        if($empCode == '')
        {
            return view('admin.appraisals.create');
        }
        else
        {
            $signFiles = SignatureFile::whereActive(1)->pluck('name', 'id');
            $empDet = EmpDet::join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->select('emp_dets.id', 'emp_dets.empCode', 'emp_dets.firmType', 'emp_dets.name',
            'emp_dets.phoneNo','emp_dets.jobJoingDate','emp_dets.salaryScale',
            'contactus_land_pages.branchName','emp_dets.contractEndDate', 'emp_dets.designationId',
            'emp_dets.contractStartDate', 'departments.name as departmentName', 
            'designations.name as designationName')
            ->where('emp_dets.active', 1)
            ->where('emp_dets.empCode', $empCode)
            ->first();

            if(!$empDet)
                return redirect()->back()->withInput()->with("error","Invalid Employee Code");

            $offerLetters = EmployeeLetter::where('empId', $empDet->id)->where('letterType', 1)->count();
            $appointment = EmployeeLetter::where('empId', $empDet->id)->where('letterType', 2)->count();
            $agreement = EmployeeLetter::where('empId', $empDet->id)->where('letterType', 3)->count();
            $warning = EmployeeLetter::where('empId', $empDet->id)->where('letterType', 5)->count();
            
            $empApp=Appraisal::where('empCode', $empCode)->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-1 Month')))->where('active', 1)->first();
            $appHistories=Appraisal::where('empCode', $empCode)->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-1 Month')))->orderBy('created_at')->get();

            return view('admin.appraisals.create')->with(['offerLetters'=>$offerLetters, 'appointment'=>$appointment, 'agreement'=>$agreement,'warning'=>$warning,'empApp'=>$empApp,'appHistories'=>$appHistories,'empDet'=>$empDet,'signFiles'=>$signFiles]);
        }
    }

    public function store(Request $request)
    {
        // Appraisal::Where('empId', $request->empId)->update(['active'=>0]);

        $apprs = new Appraisal;
        $apprs->empId=$request->empId;
        $apprs->designationId=$request->designationId;
        $apprs->empCode=$request->empCode;
        $apprs->oldSalary=$request->oldSalary;
        $apprs->hikeRs=$request->hikeRs;
        $apprs->percentage=$request->percentage;
        $apprs->finalRs=$request->finalSalary;
        $apprs->contractFrom=$request->contractFromDate;
        $apprs->contractTo=$request->contractToDate;
        $apprs->month=$request->fromMonth;
        $apprs->signBy=$request->signBy;
        $apprs->userId=Auth::user()->id;
        $apprs->changeAt=date('Y-m-d h:i:s');
        $apprs->remarks=$request->remarks;
        $apprs->updated_by=Auth::user()->username;
        if($apprs->save())
        {
            if($request->fromMonth == date('Y-m', strtotime('-1 month')))
            {
                $employee = EmpDet::find($request->empId);
                $employee->salaryScale = $request->finalSalary;
                $employee->oldSalary = $request->oldSalary;
                $employee->save();
            }
        }

        return redirect('/apprisal/create')->with('success', 'Appraisal Updated successfully!!!');
    }

    public function show($id)
    {
        $empApp=Appraisal::find($id);
        $signFiles = SignatureFile::whereActive(1)->pluck('name', 'id');
        $empDet = EmpDet::join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('emp_dets.id', 'emp_dets.empCode', 'emp_dets.firmType', 'emp_dets.name',
        'emp_dets.phoneNo','emp_dets.jobJoingDate','emp_dets.salaryScale',
        'contactus_land_pages.branchName','emp_dets.contractEndDate', 
        'emp_dets.contractStartDate', 'departments.name as departmentName', 
        'designations.name as designationName')
        ->where('emp_dets.id', $empApp->empId)
        ->where('emp_dets.active', 1)
        ->first();

        if(!$empDet)
            return redirect()->back()->withInput()->with("error","Invalid Employee Code");

        
        $appHistories=Appraisal::where('empCode', $empApp->empCode)->orderBy('created_at')->get();

        return view('admin.appraisals.show')->with(['empApp'=>$empApp,'appHistories'=>$appHistories,'empDet'=>$empDet,'signFiles'=>$signFiles]);
    }

    public function exportExcel($year)
    {     
        $fileName = 'Apprisal'.$year.'.xlsx';
        $year = date('Y', strtotime($year));
        return Excel::download(new ApprisalExport($year), $fileName);
    }

    public function update(Request $request, $id)
    {
        //
    }

}
