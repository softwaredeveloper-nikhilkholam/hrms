<?php

namespace App\Http\Controllers\admin\employees;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AttendanceDetail;
use App\EmpDebit;
use App\EmpDet;
use App\EmpApplication;
use App\SalarySheet;
use App\MonthlyDeduction;
use Auth;

class EmpDebitsController extends Controller
{
    public function index()
    {
        // $datas = EmpApplication::where('type', 1)
        // ->where('reason', 'Extra Working on Holiday')
        // ->whereIn('startDate',['2025-07-06','2025-07-13', '2025-07-20', '2025-07-27'])
        // ->get();

        // foreach($datas as $row)
        // {
        //     AttendanceDetail::where('forDate', $row->startDate)
        //     ->where('empId', $row->empId)
        //     ->update(['AGFReason'=>$row->reason]);
        // }

        $debits = EmpDebit::join('emp_dets','emp_debits.empId', 'emp_dets.id')
        ->select('emp_dets.name as empName', 'emp_dets.empCode', 'emp_debits.*')
        ->where('emp_debits.active', 1)
        ->orderBy('emp_debits.created_at', 'desc')
        ->get();
        return view('admin.empDebits.list')->with(['debits'=>$debits]);
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
            return view('admin.empDebits.create')->with(['employee'=>$employee, 'empCode'=>$empCode]);
        }

        return view('admin.empDebits.create');
    }

    public function store(Request $request)
    {
        $debit = new EmpDebit;
        $debit->empId = $request->empId;
        $debit->amount = $request->amount;
        $debit->referenceBy = $request->referenceBy;
        $debit->forMonth = $request->forMonth;
        $debit->reason = $request->reason;
        $debit->updated_by=Auth::user()->username;
        $debit->save();
        return redirect('/empDebits')->with("success","Employee Debit amount save successfully..");
    }

    public function show($id)
    {
        $debit = EmpDebit::join('emp_dets','emp_debits.empId', 'emp_dets.id')
        ->where('emp_debits.id', $id)
        ->first();
        return view('admin.empDebits.show')->with(['debit'=>$debit]);
    }

    public function edit($id)
    {
        $debit = EmpDebit::find($id);
        $employee = EmpDet::join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('emp_dets.id','emp_dets.name', 'emp_dets.empCode','emp_dets.phoneNo', 'designations.name as designationName')
        ->where('emp_dets.id', $debit->empId)
        ->first();
        return view('admin.empDebits.edit')->with(['employee'=>$employee,'debit'=>$debit]);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $userType = $user->userType;

        $debit = EmpDebit::find($id);
        $debit->empId = $request->empId;
        $debit->amount = $request->amount;
        $debit->referenceBy = $request->referenceBy;
        $debit->forMonth = $request->forMonth;
        $debit->reason = $request->reason;
        $debit->updated_by=Auth::user()->username;
        if($userType == '501')
        {
            $debit->managementStatus = $request->managementStatus;
            $debit->managementRemark = $request->managementRemark;
        }

        if($userType == '61')
        {
            $debit->accountStatus = $request->accountStatus;
            $debit->accountRemark = $request->accountRemark;
        }

        if($debit->save())
        {
            if($debit->accountStatus == 1)
            {
                if(!$monthDeb = MonthlyDeduction::where('parentId', $debit->id)->first())
                    $monthDeb = new MonthlyDeduction;

                $monthDeb->parentId = $debit->id; 
                $monthDeb->empId = $debit->empId; 
                $monthDeb->forMonth = $request->forMonth;
                $monthDeb->amount = $request->amount;
                $monthDeb->type = 1; 
                $monthDeb->status = 1; 
                $monthDeb->active = 1; 
                $monthDeb->updated_by = Auth::user()->username;
                $monthDeb->save(); 
            }

            if($debit->accountStatus == 2 || $debit->managementStatus == 2)
            {
                $monthDebs = MonthlyDeduction::where('parentId', $debit->id)->get();
                foreach($monthDebs as $temp)
                {
                    $monthDeb = MonthlyDeduction::find($temp->id);
                    $monthDeb->delete();
                }                
            }            
        }

        return redirect('/empDebits')->with("success","Employee Debit amount updated successfully..");
    }

    public function activate($id)
    {
        EmpDebit::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        $debit = EmpDebit::find($id); 
        if($debit->save())
        {
            $salarySheet = SalarySheet::Where('empId', $request->empId)
            ->where('month', $debit->month)
            ->first();
            $debit = EmpDebit::find($debit->id); 
            if($salarySheet)
            {
                $salarySheet->otherDeduction=$debit->amount;
                $salarySheet->totalDeduction = ($salarySheet->advanceAgainstSalary + $salarySheet->otherDeduction + $salarySheet->TDS + $salarySheet->PT + $salarySheet->PF + $salarySheet->ESIC + $salarySheet->MLWF);
                $salarySheet->netSalary = $salarySheet->grossPayableSalary - $salarySheet->totalDeduction;
                $salarySheet->save();

                $debit->deductionUpdated = 1;
                $debit->save();
            }
            else
            {
                $debit->deductionUpdated = 0;
                $debit->save();
            }
        }

        return redirect('/empDebits')->with("success","Employee Debit Entry Activated successfully..");
    }

    public function deactivate($id)
    {
        EmpDebit::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        $debit = EmpDebit::find($id); 
        if($debit->save())
        {
            $salarySheet = SalarySheet::Where('empId', $request->empId)
            ->where('month', $debit->month)
            ->first();
            $debit = EmpDebit::find($debit->id); 
            if($salarySheet)
            {
                $salarySheet->otherDeduction=0;
                $salarySheet->totalDeduction = ($salarySheet->advanceAgainstSalary + $salarySheet->otherDeduction + $salarySheet->TDS + $salarySheet->PT + $salarySheet->PF + $salarySheet->ESIC + $salarySheet->MLWF);
                $salarySheet->netSalary = $salarySheet->grossPayableSalary - $salarySheet->totalDeduction;
                $salarySheet->save();

                $debit->deductionUpdated = 0;
                $debit->save();
            }
            else
            {
                $debit->deductionUpdated = 0;
                $debit->save();
            }
        }
        return redirect('/empDebits/dlist')->with("success","Employee Debit Entry Deactivated successfully..");        
    }

}
