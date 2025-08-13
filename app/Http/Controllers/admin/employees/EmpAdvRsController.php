<?php

namespace App\Http\Controllers\admin\employees;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employee;
use App\EmpAdvRs;
use App\EmpDet;
use App\EmpEmiHistory;
use App\SalarySheet;
use App\EmpDebit;
use App\EmpAdvRsHistory;
use App\MonthlyDeduction;
use Auth;

class EmpAdvRsController extends Controller
{
    public function index()
    {
        $empAdvs = EmpAdvRs::join('emp_dets','emp_adv_rs.empId', 'emp_dets.id')
        ->select('emp_dets.name as empName', 'emp_dets.empCode', 'emp_adv_rs.*')
        ->where('emp_adv_rs.status', 0)
        ->orderBy('emp_adv_rs.created_at', 'desc')
        ->get();
        return view('admin.empAdvs.list')->with(['empAdvs'=>$empAdvs]);
    }

    public function dlist()
    {
        $empAdvs = EmpAdvRs::join('emp_dets','emp_adv_rs.empId', 'emp_dets.id')
        ->select('emp_dets.name as empName', 'emp_dets.empCode', 'emp_adv_rs.*')
        ->where('emp_adv_rs.status', '!=', 0)
        ->orderBy('emp_adv_rs.created_at', 'desc')
        ->get();
        return view('admin.empAdvs.dlist')->with(['empAdvs'=>$empAdvs]);
    }

    public function create(Request $request)
    {
        $empCode = $request->empCode;
        if($empCode != '')
        {
            $employee = EmpDet::join('designations', 'emp_dets.designationId', 'designations.id')
            ->select('emp_dets.id','emp_dets.name', 'emp_dets.empCode','emp_dets.phoneNo', 'designations.name as designationName')
            ->where('emp_dets.empCode', $empCode)
            ->first();
            return view('admin.empAdvs.create')->with(['employee'=>$employee, 'empCode'=>$empCode]);
        }

        return view('admin.empAdvs.create');
    }

    public function store(Request $request)
    {
        $empAdv = new EmpAdvRs;
        $empAdv->empId = $request->empId;
        $empAdv->advAmount = $request->advAmount;
        $empAdv->deduction = $request->deduction;
        $empAdv->purpose = $request->purpose;
        $empAdv->startDate = $request->startDate;
        $empAdv->endDate = $request->endDate;
        $empAdv->paymentMode = $request->paymentMode;      
        $empAdv->updated_by=Auth::user()->username;
        if($empAdv->save())
        {
            $advHistory = new EmpAdvRsHistory;
            $advHistory->empAdvId = $empAdv->id;
            $advHistory->empId = $empAdv->empId;
            $advHistory->advAmount = $request->advAmount;
            $advHistory->deduction = $request->deduction;
            $advHistory->purpose = $request->purpose;
            $advHistory->startDate = $request->startDate;
            $advHistory->endDate = $request->endDate;
            $advHistory->paymentMode = $request->paymentMode;      
            $advHistory->updated_by=$empAdv->updated_by=Auth::user()->username;
            $advHistory->save();
            
        }
        return redirect('/empAdvRs')->with("success","Employee Advance amount save successfully..");
    }

    public function show($id)
    {
        $empAdv = EmpAdvRs::join('emp_dets','emp_adv_rs.empId', 'emp_dets.id')
        ->where('emp_adv_rs.id', $id)
        ->first();
        return view('admin.empAdvs.create')->with(['empAdv'=>$empAdv]);
    }

    public function edit($id)
    {
        $payment = EmpAdvRs::find($id);
        $employee = EmpDet::join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('emp_dets.id','emp_dets.name', 'emp_dets.empCode','emp_dets.phoneNo', 'designations.name as designationName')
        ->where('emp_dets.id', $payment->empId)
        ->first();

        $paymentHistory = EmpAdvRsHistory::where('empAdvId', $id)->where('managementStatus', 0)->get();
        return view('admin.empAdvs.edit')->with(['employee'=>$employee,'payment'=>$payment,'paymentHistory'=>$paymentHistory]);
    }

    public function update(Request $request, $id)
    {
        // return $request->all();
        $user = Auth::user();
        $userType = $user->userType;

        $empAdv = EmpAdvRs::find($id);
        $advHistory = new EmpAdvRsHistory;
        $advHistory->empAdvId = $id;
        $advHistory->empId = $empAdv->empId;
        $advHistory->advAmount = $empAdv->advAmount;
        $advHistory->deduction = $empAdv->deduction;
        $advHistory->purpose = $empAdv->purpose;
        $advHistory->startDate = $empAdv->startDate;
        $advHistory->endDate = $empAdv->endDate;
        $advHistory->paymentMode = $empAdv->paymentMode;      
        if($userType == '501')
        {
            $advHistory->managementStatus = $empAdv->managementStatus = $request->managementStatus;
            $advHistory->managementRemark = $empAdv->managementRemark = $request->managementRemark;
        }

        if($userType == '61')
        {
            $advHistory->accountStatus = $empAdv->accountStatus = $request->accountStatus;
            $advHistory->accountRemark = $empAdv->accountRemark = $request->accountRemark;
        }

        $advHistory->updated_by=$empAdv->updated_by=Auth::user()->username;
        if($empAdv->save())
        {
            if($advHistory->save())
            {
                if($advHistory->accountStatus == 1)
                {
                    $start = $month = strtotime($advHistory->startDate);
                    $end = strtotime($advHistory->endDate);
                    while($month < $end)
                    {
                        $monthDeb = new MonthlyDeduction;
                        $monthDeb->parentId = $advHistory->id; 
                        $monthDeb->empId = $advHistory->empId; 
                        $monthDeb->forMonth = date('Y-m', $month);
                        $monthDeb->amount = $advHistory->deduction; 
                        $monthDeb->type = 2; 
                        $monthDeb->status = 1; 
                        $monthDeb->active = 1; 
                        $monthDeb->updated_by = Auth::user()->username;
                        $monthDeb->save(); 

                        $month = strtotime("+1 month", $month);
                    }
                }
            }
        }

        return redirect('/empAdvRs')->with("success","Employee Advance amount updated successfully..");
    }

    public function activate($id)
    {
        EmpAdvRs::where('id', $id)->update(['status'=>'1', 'updated_by'=>Auth::user()->username]);
        return redirect('/empAdvRs')->with("success","Employee Advanc Entry Activated successfully..");
    }

    public function deactivate($id)
    {
        EmpAdvRs::where('id', $id)->update(['status'=>'2', 'updated_by'=>Auth::user()->username]);
        return redirect('/empAdvRs/dlist')->with("success","Employee Advanc Entry Deactivated successfully..");        
    }
}
