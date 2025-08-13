<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\MRReportImport;
use App\Imports\SalarySheetImport; 
use App\Imports\RetentionImport; 
use App\Exports\SalarySheetExport;
use App\EmpMr;
use App\EmpDet;
use App\TempMrUpload;
use App\MrReportUpload;
use App\ContactusLandPage;
use App\TempSalarySheet;
use App\SalarySheet;
use App\TempRetention;
use App\Retention;
use App\MonthlyAttendanceSummary;
use App\Organisation;
use Auth;
use Excel;

class AccountsController extends Controller
{
    public function index()
    {
        $mrs = MrReportUpload::where('status', 1)->paginate(10);
        return view('admin.accounts.payroll.mr.list')->with(['mrs'=>$mrs]);
    }

    public function uploadMR()
    {
        return view('admin.accounts.payroll.mr.upload');
    }

    public function updateUploadMR(Request $request)
    {
        Excel::import(new MRReportImport, $request->file('excelFile'));
        $mrRep = MrReportUpload::where('month', $request->month)->first();
        if(!$mrRep)
            $mrRep = new MrReportUpload;

        $mrRep->month = $request->month;
        $mrRep->status = 1;
        $mrRep->updated_by = Auth::user()->username;
        if($mrRep->save())
        {
            $empMrs = TempMrUpload::get();
            if(count($empMrs))
            {
                foreach($empMrs as $mr)
                {
                    $empId = EmpDet::where('empCode', $mr->empCode)->value('id');
                    if($empId)
                    {
                        $salarySheet = SalarySheet::where('month', $request->month)->where('empId', $empId)->first();
                        if($salarySheet)
                        {
                            $salarySheet->PT = $mr->PT;
                            $salarySheet->PF = $mr->PF; 
                            $salarySheet->ESIC = $mr->ESIC;
                            $salarySheet->MLWF = $mr->MLWL;
                            $salarySheet->totalDeduction = ($salarySheet->advanceAgainstSalary + $salarySheet->otherDeduction + $salarySheet->TDS + $salarySheet->PT + $salarySheet->PF + $salarySheet->ESIC + $salarySheet->MLWF);
                            $salarySheet->save();
                        }
                    }
                }
            }
        }

        return redirect('/accounts')->with("success","MR Report Uploaded successfully..");
    }

    public function salarySheet(Request $request)
    {
        $branches=ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $organisations = Organisation::where('active', 1)->pluck('shortName', 'id');
        if($request->month == '' && ($request->branchId == '' || $request->organisation == ''))
        {
            return view('admin.accounts.payroll.salarySheet.create')->with(['branches'=>$branches,'organisations'=>$organisations]);
        }
        else
        {
            if($request->month <= '2025-05')
            {
                $salarySheet = EmpMr::join('emp_dets', 'emp_mrs.empId', 'emp_dets.id')
                ->join('designations', 'emp_dets.designationId', 'designations.id')
                ->join('departments', 'emp_dets.departmentId', 'departments.id')
                ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
                ->select('emp_dets.bankAccountNo','emp_dets.bankIFSCCode','emp_dets.branchName','emp_dets.bankName',
                'emp_dets.name','emp_dets.empCode','emp_dets.organisation as empOrganisation','designations.name as designationName',
                'emp_dets.jobJoingDate', 'departments.section','departments.name as departmentName',
                'emp_mrs.grossSalary as tempSalary', 'contactus_land_pages.shortName','emp_mrs.*')
                ->where('emp_mrs.forDate', $request->month)
                ->where('emp_dets.jobJoingDate', '<=', date('Y-m-t', strtotime($request->month)))
                ->where('emp_dets.active', 1);
                if($request->branchId != '')
                    $salarySheet = $salarySheet->where('emp_dets.branchId', $request->branchId);

                if($request->organisation != '')
                    $salarySheet = $salarySheet->where('emp_dets.organisation', $request->organisation);

                if($request->section != '')
                    $salarySheet = $salarySheet->where('departments.section', $request->section);

                if($request->salaryType != '')
                    $salarySheet = $salarySheet->where('emp_mrs.type', $request->salaryType);

                 $salarySheet = $salarySheet->orderBy('emp_dets.empCode')
                ->get();
            }
            else
            {
                $salarySheet = MonthlyAttendanceSummary::join('emp_dets', 'monthly_attendance_summaries.empId', 'emp_dets.id')
                ->join('designations', 'emp_dets.designationId', 'designations.id')
                ->join('departments', 'emp_dets.departmentId', 'departments.id')
                ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
                ->join('organisations', 'emp_dets.organisationId', 'organisations.id')
                ->select('emp_dets.bankAccountNo','emp_dets.bankIFSCCode','emp_dets.branchName','emp_dets.bankName',
                'emp_dets.name','emp_dets.empCode','organisations.shortName as empOrganisation','designations.name as designationName',
                'emp_dets.jobJoingDate', 'departments.section','departments.name as departmentName',
                'emp_dets.salaryScale as tempSalary', 'contactus_land_pages.shortName','monthly_attendance_summaries.*')
                ->where('monthly_attendance_summaries.month', $request->month)
                ->where('emp_dets.jobJoingDate', '<=', date('Y-m-t', strtotime($request->month)))
                ->where('emp_dets.active', 1);
                if($request->branchId != '')
                    $salarySheet = $salarySheet->where('emp_dets.branchId', $request->branchId);

                if($request->organisation != '')
                    $salarySheet = $salarySheet->where('emp_dets.organisationId', $request->organisation);

                if($request->section != '')
                    $salarySheet = $salarySheet->where('departments.section', $request->section);

                if($request->salaryType != '')
                    $salarySheet = $salarySheet->where('monthly_attendance_summaries.salaryType', $request->salaryType);

                $salarySheet = $salarySheet->orderBy('emp_dets.empCode')
                ->get();
            }

            return view('admin.accounts.payroll.salarySheet.create')->with(['organisations'=>$organisations, 'salaryType'=>$request->salaryType,'salarySheet'=>$salarySheet, 'month'=>$request->month, 'section'=>$request->section, 'organisation'=>$request->organisation, 'branchId'=>$request->branchId, 'branches'=>$branches]);

        }
    }

    public function exportSalarySheet($organisation, $branch, $section, $salaryType, $month)
    {
        if($section == 'Teaching')
            $section = 1;

        if($section == 'Non Teaching')
            $section = 2;
            
        // if($organisation == 'Ellora')
        //     $organisation=1;
        // elseif($organisation == 'Snayraa')
        //     $organisation=2;
        // elseif($organisation == 'Tejasha')
        //     $organisation=3;
        // elseif($organisation == 'Akshara')
        //     $organisation=4;
        // elseif($organisation == 'Aaryans Edutainment')
        //     $organisation=5;
        // elseif($organisation == 'ADF')
        //     $organisation=6;
        // elseif($organisation == 'AFF')
        //     $organisation=7;
        // elseif($organisation == 'YB')
        //     $organisation=8;
        // elseif($organisation == 'Aaryans Animal Bird Fish Reptiles Rescue Rehabilitation And Educational Society')
        //     $organisation=9;
        // else
        //     $organisation=0;

        $branchName = ContactusLandPage::where('id', $branch)->value('shortName');
        $fileName = $branchName.'_SalarySheet'.date('M-Y', strtotime($month)).'.xlsx';
        return Excel::download(new SalarySheetExport($organisation, $branch, $section, $salaryType, $month, 1, 0), $fileName);
    }

    public function exportBankDetails($organisation, $branch, $section, $salaryType, $month)
    {
        $branchName = ContactusLandPage::where('id', $branch)->value('shortName');
        $fileName = $branchName.'_SalaryBankDetails'.date('M-Y', strtotime($month)).'.xlsx';
        return Excel::download(new SalarySheetExport($organisation, $branch, $section, $salaryType, $month, 2, 0), $fileName);
    }

    public function viewMRReport($id)
    {
        return $id;
    }

    public function getAdvancePeriod($month)
    {
        $start = date('Y-m',strtotime('first day of +1 month'));
        $mon = '+'.$month.' month';
        $end = date('Y-m',strtotime($mon));

        return [$start, $end];
    }

    public function uploadSalarySheet(Request $request)
    {
        return view('admin.accounts.payroll.salarySheet.uploadSalarySheet');
    }

    public function updateSalarySheet(Request $request)
    {
        $month=$request->month;
        Excel::import(new SalarySheetImport, $request->file('excelFile'));
        $temps = TempSalarySheet::all();
        foreach($temps as $tp)
        {
            $emp=EmpDet::where('empCode', $tp->empCode)->first();
            if($emp)
            {
                $sheet = SalarySheet::where('empCode', $tp->empCode)->where('month', $month)->where('active', 1)->first();
                if(!$sheet)
                    $sheet = new SalarySheet;

                $sheet->code = $emp->id;
                $sheet->month = $month;
                $sheet->name = $emp->name;
                $sheet->branchId = $emp->branchId;
                $sheet->designationId = $emp->designationId;
                $sheet->departmentId = $emp->departmentId;
                $sheet->empCode = $emp->empCode;
                $sheet->organisation = $tp->organisation;
                $sheet->section = $tp->section;
                $sheet->joingDate = $tp->joingDate;
                $sheet->grossSalary = $tp->grossSalary;
                $sheet->perDayRs = $tp->perDayRs;
                $sheet->totalDays = $tp->totalDays;
                $sheet->presentWithWO = $tp->presentWithWO;
                $sheet->attendanceGriveanceForm = $tp->attendanceGriveanceForm;
                $sheet->additionalDutyAvailed = $tp->additionalDutyAvailed;
                $sheet->extraWorkingHours = $tp->extraWorkingHours;
                $sheet->additionalDutyPendingPayable = $tp->additionalDutyPendingPayable;
                $sheet->totalPresent = $tp->totalPresent;
                $sheet->absent = $tp->absent;
                $sheet->percentageDays = ($tp->percentageDays == "-" || $tp->percentageDays == " -  ")?'0':$tp->percentageDays;
                $sheet->basicPayableSalary = $tp->basicPayableSalary;
                $sheet->basicNotPayableSalary = $tp->basicNotPayableSalary;
                $sheet->applicableRateOf = $tp->applicableRateOf;
                $sheet->salaryPayable = $tp->salaryPayable;
                $sheet->EWH = $tp->EWH;
                $sheet->ADP = $tp->ADP;
                $sheet->due = $tp->due;
                $sheet->adv = $tp->adv;
                $sheet->debit = $tp->debit;
                $sheet->retention = $tp->retention;
                $sheet->TDS = $tp->TDS;
                $sheet->MLWF = $tp->MLWF;
                $sheet->ESIC = $tp->ESIC;
                $sheet->PT = $tp->PT;
                $sheet->SNAYRAAPF = $tp->SNAYRAAPF;
                $sheet->TEJASHAPF = $tp->TEJASHAPF;
                $sheet->netPayable = $tp->netPayable;
                $sheet->accountNo = $tp->accountNo;
                $sheet->IFSC = $tp->IFSC;
                $sheet->bankName = $tp->bankName;
                $sheet->branchName = $tp->branchName;
                $sheet->remarks = $tp->remarks;
                $sheet->updated_by = Auth::user()->username;
                $sheet->save();

            }
        }
        return redirect('/accounts/uploadSalarySheet')->with("success","Salary sheet Uploaded successfully..");
    }

    public function retention()
    {
        return view('admin.accounts.retentions.create');  
    }

    public function uploadRetention(Request $request)
    {
        $month=$request->month;
        Excel::import(new RetentionImport, $request->file('excelFile'));
        $temps = TempRetention::all();
        foreach($temps as $temp)
        {
            $empId = EmpDet::where('empCode', $temp->code)->value('id');
            $ret = Retention::where('empId', $empId)->where('month', $month)->first();
            if(!$ret)
                $ret = new Retention;

            $ret->empId = $empId;
            $ret->retentionAmount = $temp->amount;
            $ret->month = $month;
            $ret->remark = $temp->remark;
            $ret->updated_by = Auth::user()->username;
            $ret->save();
            // if($ret->save())
            // {
            //     $deleteTemp = TempRetention::find($temp->id); 
            //     $deleteTemp->delete();
            // }
        }

        return redirect('/reports/retentionReport')->with("success","Retention sheet Uploaded successfully..");
    }
}
