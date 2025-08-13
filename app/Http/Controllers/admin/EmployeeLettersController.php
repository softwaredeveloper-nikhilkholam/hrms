<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EmpDet;
use App\Designation;
use App\Department;
use App\EmployeeLetter;
use App\ContactusLandPage;
use App\SignatureFile;
use App\Organisation;
use Auth;
use DB;
use PDF;

class EmployeeLettersController extends Controller
{
    public function index()
    {
    }

    public function concernList(Request $request) // Concern Letter list
    {
        $letters = EmployeeLetter::join('emp_dets','employee_letters.empId', 'emp_dets.id')
        ->select('emp_dets.empCode','emp_dets.firmType', 'emp_dets.name as empName', 'employee_letters.*')
        ->where('employee_letters.active', 1)
        ->where('employee_letters.letterType', 6) // 6 is for Concern Letter type
        ->orderBy('employee_letters.created_at', 'desc')
        ->get();
        return view('admin.employeeLetters.concernLetters.list')->with(['letters'=>$letters]);
    }

    public function concernCreate(Request $request)
    {
        $empCode = $request->empCode;
        if($empCode == '')
            return view('admin.employeeLetters.concernLetters.create');

        $empDet = EmpDet::select('id','salaryScale', 'name', 'designationId','jobJoingDate', 'departmentId','branchId')->where('empCode', $empCode)->first();
        if(!$empDet)
            return redirect()->back()->withInput()->with("error","Invalid Employee Code");;
            
        $signFiles = SignatureFile::whereActive(1)->pluck('name', 'id');
        $branches = ContactusLandPage::whereActive(1)->pluck('branchName', 'id');
        $designations = Designation::where('departmentId', $empDet->departmentId)->whereActive(1)->pluck('name', 'id');
     
        return view('admin.employeeLetters.concernLetters.create')->with(['signFiles'=>$signFiles,'empDet'=>$empDet, 'empCode'=>$empCode,
        'branches'=>$branches, 'designations'=>$designations]);
    }

    public function concernStore(Request $request)
    {
        $empDet = EmpDet::find($request->empId);
        $letter = new EmployeeLetter;
        $letter->empId=$empDet->id;
        $letter->designationId=$empDet->designationId;
        $letter->branchId=$empDet->branchId;
        $letter->DOJ=$empDet->jobJoingDate;
        $letter->lateMarkCount=$request->lateMarkCount;
        $letter->signBy=$request->signBy;
        $letter->letterForMonth=$request->letterForMonth;
        $letter->forDate=$request->forDate;
        $letter->letterType=6;
        $letter->updated_by=Auth::user()->username;
        $letter->save();
        return redirect('/employeeLetters/concernList')->with('success', 'Save Concern Letter successfully!!!');
    }

    public function concernView($id)
    { 
        $letter = EmployeeLetter::join('emp_dets','employee_letters.empId', 'emp_dets.id')
        ->join('designations','emp_dets.designationId', 'designations.id')
        ->select('emp_dets.empCode','emp_dets.firmType', 'designations.name as designationName','emp_dets.name as empName', 'employee_letters.*')
        ->where('employee_letters.active', 1)
        ->where('employee_letters.id', $id) 
        ->first();

        $signBy = SignatureFile::where('designationName', 'HR Manager')->first();
        $file = $letter->empCode.'-ConcernLetter.pdf';
        $pdf = PDF::loadView('admin.employeeLetters.concernLetters.viewLetterPDF',compact('letter','signBy'));
        return $pdf->stream($file);   
    }

    public function list($letterType)
    {
        $letters = EmployeeLetter::join('emp_dets','employee_letters.empId', 'emp_dets.id')
        ->select('emp_dets.empCode','emp_dets.firmType', 'emp_dets.name as empName', 'employee_letters.*')
        ->where('employee_letters.active', 1)
        ->where('employee_letters.letterType', $letterType)
        ->orderBy('employee_letters.created_at', 'desc')
        ->get();
        return view('admin.employeeLetters.list')->with(['letters'=>$letters,'letterType'=>$letterType]);
    }

    public function getOfferLetter(Request $request)
    {
        $empCode = $request->empCode;
        if($empCode == '')
            return view('admin.employeeLetters.offerLetter');

        $empDet = EmpDet::select('id','salaryScale', 'name', 'designationId','jobJoingDate', 'departmentId','branchId')->where('empCode', $empCode)->first();
        if(!$empDet)
            return redirect()->back()->withInput()->with("error","Invalid Employee Code");;
            
        $signFiles = SignatureFile::whereActive(1)->pluck('name', 'id');
        $branches = ContactusLandPage::whereActive(1)->pluck('branchName', 'id');
        $designations = Designation::where('departmentId', $empDet->departmentId)->whereActive(1)->pluck('name', 'id');
     
        return view('admin.employeeLetters.offerLetter')->with(['signFiles'=>$signFiles,'empDet'=>$empDet, 'empCode'=>$empCode,
        'branches'=>$branches, 'designations'=>$designations]);
    }
    
    public function generateOfferLetter(Request $request)
    {
        $empId = $request->empId;
        $empDet = EmpDet::join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.id','emp_dets.designationId','emp_dets.branchId',
        'emp_dets.empCode','emp_dets.name', 'designations.name as designationName',
        'emp_dets.salaryScale','emp_dets.jobJoingDate','emp_dets.gender', 'contactus_land_pages.branchName')
        ->where('emp_dets.id', $empId)
        ->first();

        if($request->submit == 'Download Offer Letter')
        {
            $signBy = SignatureFile::find($request->signBy);
            $file = $empDet->empCode.'Offer Letter.pdf';
            $pdf = PDF::loadView('admin.employeeLetters.offerLetterPDF',compact('empDet','signBy'));
            return $pdf->download($file);   
        }
        else
        {
            $letter = new EmployeeLetter;
            $letter->empId=$empDet->id;
            $letter->designationId=$empDet->designationId;
            $letter->branchId=$empDet->branchId;
            $letter->DOJ=$empDet->jobJoingDate;
            $letter->salary=$empDet->salaryScale;
            $letter->signBy=$request->signBy;
            $letter->forDate=date('Y-m-d');
            $letter->letterType=1;
            $letter->updated_by=Auth::user()->username;
            $letter->save();
            return redirect('/employeeLetters/list/1')->with('success', 'Save Offer Letter successfully!!!');
        }
    }

    public function viewOfferLetter($id)
    {
        $empDet = EmployeeLetter::join('emp_dets', 'employee_letters.empId', 'emp_dets.id')
        ->join('designations', 'employee_letters.designationId', 'designations.id')
        ->join('contactus_land_pages', 'employee_letters.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.empCode','emp_dets.name', 'designations.name as designationName',
        'employee_letters.salary as salaryScale','employee_letters.DOJ as jobJoingDate','emp_dets.gender',
        'contactus_land_pages.branchName','employee_letters.signBy')
        ->where('employee_letters.id', $id)
        ->first();

        $signBy = SignatureFile::find($empDet->signBy);
        $file = $empDet->empCode.'Offer Letter.pdf';
        $pdf = PDF::loadView('admin.employeeLetters.offerLetterPDF',compact('empDet','signBy'));
        return $pdf->stream($file);   
    }

    public function getAgreement(Request $request)
    {
        $empCode = $request->empCode;
        if($empCode == '')
            return view('admin.employeeLetters.agreement');

        $empDet = EmpDet::select('id','salaryScale', 'name', 'designationId','jobJoingDate', 'departmentId','branchId')->where('empCode', $empCode)->first();
        if(!$empDet)
            return redirect()->back()->withInput()->with("error","Invalid Employee Code");;
            
        $signFiles = SignatureFile::whereActive(1)->pluck('name', 'id');
        $branches = ContactusLandPage::whereActive(1)->pluck('branchName', 'id');
        $designations = Designation::where('departmentId', $empDet->departmentId)->whereActive(1)->pluck('name', 'id');
        $departments = Department::whereActive(1)->pluck('name', 'id');
        return view('admin.employeeLetters.agreement')->with(['signFiles'=>$signFiles,'empDet'=>$empDet, 'empCode'=>$empCode,
        'branches'=>$branches, 'designations'=>$designations, 'departments'=>$departments]);
    }
    
    public function generateAgreement(Request $request)
    {
        $empId = $request->empId;
        $empDet = EmpDet::join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.id','emp_dets.designationId','emp_dets.branchId',
        'emp_dets.empCode','emp_dets.name', 'designations.name as designationName',
        'emp_dets.salaryScale','emp_dets.jobJoingDate','emp_dets.gender', 'contactus_land_pages.branchName')
        ->where('emp_dets.id', $empId)
        ->first();
       
        $letter = new EmployeeLetter;
        $letter->empId=$empDet->id;
        $letter->designationId=$empDet->designationId;
        $letter->branchId=$empDet->branchId;
        $letter->DOJ=$empDet->jobJoingDate;
      
        if(!empty($request->file('uploadFile')))
        {
            $fileName = "Agreement_".date('ymdhis').'.'.$request->uploadFile->extension();  
            $request->uploadFile->move(public_path('admin/empLetters'), $fileName);
            $letter->uploadFile=$fileName;
        }

        $letter->fromDate=$request->fromDate;
        $letter->toDate=$request->toDate;
        $letter->signBy=$request->signBy;
        $letter->forDate=date('Y-m-d');
        $letter->letterType=3;
        $letter->updated_by=Auth::user()->username;
        $letter->save();
        return redirect('/employeeLetters/list/3')->with('success', 'Agreement Uploaded successfully!!!');
    }

    public function viewAgreement($id)
    {
        $empDet = EmployeeLetter::join('emp_dets', 'employee_letters.empId', 'emp_dets.id')
        ->join('designations', 'employee_letters.designationId', 'designations.id')
        ->join('contactus_land_pages', 'employee_letters.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.empCode','emp_dets.name', 'designations.name as designationName',
        'employee_letters.fromDate','employee_letters.uploadFile','employee_letters.toDate','employee_letters.DOJ as jobJoingDate','emp_dets.gender',
        'contactus_land_pages.branchName','employee_letters.signBy','employee_letters.created_at','employee_letters.updated_by')
        ->where('employee_letters.id', $id)
        ->first();

        $signFiles = SignatureFile::whereActive(1)->pluck('name', 'id');
        $branches = ContactusLandPage::whereActive(1)->pluck('branchName', 'id');
        $designations = Designation::where('departmentId', $empDet->departmentId)->whereActive(1)->pluck('name', 'id');
        $departments = Department::whereActive(1)->pluck('name', 'id');

        return view('admin.employeeLetters.viewAgreement')->with(['signFiles'=>$signFiles,'empDet'=>$empDet,
        'branches'=>$branches, 'designations'=>$designations, 'departments'=>$departments]);  
    }

    public function getAppointmentLetter(Request $request)
    {
        // $employees = EmpDet::where('signatureId', '!=', '')->where('active', 1)->get();
        // foreach($employees as $empDet)
        // {
        //     $letter = new EmployeeLetter;
        //     $letter->empId=$empDet->id;
        //     $letter->designationId=$empDet->designationId;
        //     $letter->branchId=$empDet->branchId;
        //     $letter->organisation=$request->organisation;
        //     $letter->fromDate=date('2025-04-01');
        //     $letter->toDate=date('2026-03-31');
        //     $letter->salary=$empDet->salaryScale;
        //     $letter->aPeriod="Academic Year";            
        //     $letter->forDate=$request->appointmentDate;
        //     $letter->signBy=$empDet->signatureId;
        //     $letter->letterType=2;
        //     $letter->updated_by=Auth::user()->username;
        //     $letter->save();
        // }
    
        // return 'd';

        $empCode = $request->empCode;
        if($empCode == '')
            return view('admin.employeeLetters.appointmentLetter');

        $empDet = EmpDet::select('id','salaryScale', 'name', 'designationId','jobJoingDate', 'departmentId','branchId')->where('empCode', $empCode)->first();
        if(!$empDet)
            return redirect()->back()->withInput()->with("error","Invalid Employee Code");;

        $signFiles = SignatureFile::whereActive(1)->pluck('name', 'id');
        $branches = ContactusLandPage::whereActive(1)->pluck('branchName', 'id');
        $designations = Designation::where('departmentId', $empDet->departmentId)->whereActive(1)->pluck('name', 'id');
        return view('admin.employeeLetters.appointmentLetter')->with(['signFiles'=>$signFiles, 'empDet'=>$empDet, 'empCode'=>$empCode,
        'branches'=>$branches, 'designations'=>$designations]);
    }
    
    public function generateAppointment(Request $request)
    {
        $empId = $request->empId;
        if($request->organisation == 1)
            $organisation = "Ellora Medicals and Educational Foundation's";
        if($request->organisation == 2)
            $organisation = "Snayraa Agency";
        if($request->organisation == 3)
            $organisation = "Tejasha Educational and research Foundation's";

        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $salary = $request->salary;
        $appointmentDate = $request->appointmentDate;
        $signBy = $request->signBy;
        $aPeriod = $request->aPeriod;

        $empDet = EmpDet::join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.branchId','emp_dets.designationId','emp_dets.id','departments.section','emp_dets.permanentAddress',
        'emp_dets.empCode','emp_dets.name', 'designations.name as designationName',
        'emp_dets.jobJoingDate','emp_dets.gender', 'contactus_land_pages.branchName')
        ->where('emp_dets.id', $empId)
        ->first();

        if($request->submit == 'Download Appointment Letter')
        {
            $signBy = SignatureFile::find($signBy);
            $file = $empDet->empCode.'Appointment Letter.pdf';
            $pdf = PDF::loadView('admin.employeeLetters.appointmentLetterPDF',compact('aPeriod','organisation','fromDate', 'toDate', 'salary', 'appointmentDate', 'signBy', 'empDet'));
            return $pdf->stream($file); 
        }
        else
        {
            $letter = new EmployeeLetter;
            $letter->empId=$empDet->id;
            $letter->designationId=$empDet->designationId;
            $letter->branchId=$empDet->branchId;
            $letter->organisation=$request->organisation;
            $letter->fromDate=$request->fromDate;
            $letter->toDate=$request->toDate;
            $letter->salary=$request->salary;
            $letter->aPeriod=$request->aPeriod;            
            $letter->forDate=$request->appointmentDate;
            $letter->signBy=$request->signBy;
            $letter->letterType=2;
            $letter->updated_by=Auth::user()->username;
            $letter->save();
            return redirect('/employeeLetters/list/2')->with('success', 'Save Appointment Letter successfully!!!');
        }
    }

    public function viewAppointmentLetter($id, $flag)
    {
        $empDet = EmployeeLetter::join('emp_dets', 'employee_letters.empId', 'emp_dets.id')
        ->join('designations', 'employee_letters.designationId', 'designations.id')
        ->join('contactus_land_pages', 'employee_letters.branchId', 'contactus_land_pages.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->select('emp_dets.empCode','emp_dets.name', 'emp_dets.signatureId as signId','designations.name as designationName','departments.section',
        'employee_letters.salary as salaryScale','employee_letters.aPeriod','employee_letters.DOJ as jobJoingDate','emp_dets.permanentAddress',
        'emp_dets.gender','employee_letters.organisation','employee_letters.fromDate',
        'employee_letters.toDate','employee_letters.forDate as appointmentDate',
        'contactus_land_pages.branchName','employee_letters.signBy')
        ->where('employee_letters.id', $id)
        ->first();

        if(!$empDet)
            return redirect()->back()->withInput()->with("error","Appointment Letter Not Found");;
        
        if($empDet->organisation == 1)
            $organisation = "Ellora Medicals and Educational Foundation's";
        if($empDet->organisation == 2)
            $organisation = "Snayraa Agency";
        if($empDet->organisation == 3)
            $organisation = "Tejasha Educational and research Foundation's";

        $fromDate = $empDet->fromDate;
        $toDate = $empDet->toDate;
        $salary = $empDet->salaryScale;
        $appointmentDate = $empDet->appointmentDate;
        $signBy = $empDet->signBy;
        $aPeriod = $empDet->aPeriod;
        
        $signBy = SignatureFile::find($empDet->signBy);
        $file = $empDet->empCode.'Offer Letter.pdf';
        $pdf = PDF::loadView('admin.employeeLetters.appointmentLetterPDF',compact('flag','empDet','aPeriod','organisation','fromDate', 'toDate', 'salary', 'appointmentDate', 'signBy'));
        return $pdf->stream($file);   
    }

    public function getWarningLetter(Request $request)
    {
        $empCode = $request->empCode;
        if($empCode == '')
            return view('admin.employeeLetters.warningLetter');

        $empDet = EmpDet::select('id','salaryScale', 'name', 'designationId','jobJoingDate', 'departmentId','branchId')->where('empCode', $empCode)->first();
        if(!$empDet)
            return redirect()->back()->withInput()->with("error","Invalid Employee Code");;
            
        $signFiles = SignatureFile::whereActive(1)->pluck('name', 'id');
        $branches = ContactusLandPage::whereActive(1)->pluck('branchName', 'id');
        $designations = Designation::where('departmentId', $empDet->departmentId)->whereActive(1)->pluck('name', 'id');
        return view('admin.employeeLetters.warningLetter')->with(['signFiles'=>$signFiles,'empDet'=>$empDet, 'empCode'=>$empCode,
        'branches'=>$branches, 'designations'=>$designations]);
    }

    public function generateWarningLetter(Request $request)
    {
        $empId = $request->empId;
        $empDet = EmpDet::join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.id','emp_dets.designationId','emp_dets.branchId',
        'emp_dets.empCode','emp_dets.name', 'designations.name as designationName',
        'emp_dets.salaryScale','emp_dets.jobJoingDate','emp_dets.gender', 'contactus_land_pages.branchName')
        ->where('emp_dets.id', $empId)
        ->first();

        $letter = new EmployeeLetter;
        $letter->empId=$empDet->id;
        $letter->designationId=$empDet->designationId;
        $letter->branchId=$empDet->branchId;
        $letter->DOJ=$empDet->jobJoingDate;

        if(!empty($request->file('uploadFile')))
        {
            $fileName = "WarningLetter_".$empDet->empCode."_".date('ymdhis').'.'.$request->uploadFile->extension();  
            $request->uploadFile->move(public_path('admin/empLetters'), $fileName);
            $letter->uploadFile=$fileName;
        }
        
        $letter->signBy=$request->signBy;
        $letter->forDate=$request->forDate;
        $letter->letterType=5;
        $letter->updated_by=Auth::user()->username;
        $letter->save();
        return redirect('/employeeLetters/list/5')->with('success', 'Save Warning Letter successfully!!!');
    }

    public function viewWarningLetter($id)
    {
        $letter = EmployeeLetter::join('emp_dets', 'employee_letters.empId', 'emp_dets.id')
        ->select('emp_dets.departmentId','emp_dets.empCode','emp_dets.name','employee_letters.designationId',
        'employee_letters.uploadFile','employee_letters.DOJ as jobJoingDate','employee_letters.branchId',
        'emp_dets.gender','employee_letters.organisation','employee_letters.fromDate','employee_letters.updated_at',
        'employee_letters.toDate','employee_letters.forDate as appointmentDate','employee_letters.updated_by',
        'employee_letters.signBy')
        ->where('employee_letters.id', $id)
        ->first();

        if(!$letter)
            return redirect()->back()->withInput()->with("error","Appointment Letter Not Found");;
        
        $signFiles = SignatureFile::whereActive(1)->pluck('name', 'id');
        $branches = ContactusLandPage::whereActive(1)->pluck('branchName', 'id');
        $designations = Designation::where('departmentId', $letter->departmentId)->whereActive(1)->pluck('name', 'id');
        return view('admin.employeeLetters.viewWarningLetter')->with(['signFiles'=>$signFiles,'letter'=>$letter,
        'branches'=>$branches, 'designations'=>$designations]);
    }

    public function getExperienceLetter(Request $request)
    {
        $empCode = $request->empCode;
        if($empCode == '')
            return view('admin.employeeLetters.experienceLetter');

        $empDet = EmpDet::join('organisations', 'emp_dets.organisationId', 'organisations.id')
        ->select('emp_dets.id','emp_dets.salaryScale', 'emp_dets.name', 'emp_dets.designationId','emp_dets.jobJoingDate','emp_dets.lastDate', 
        'emp_dets.departmentId','emp_dets.branchId','emp_dets.organisationId', 'emp_dets.active', 'organisations.name as organisationName')
        ->where('emp_dets.empCode', $empCode)
        ->first();

        if(!$empDet)
            return redirect()->back()->withInput()->with("error","Invalid Employee Code");;

        $signFiles = SignatureFile::whereActive(1)->pluck('name', 'id');
        $branches = ContactusLandPage::whereActive(1)->pluck('branchName', 'id');
        $designations = Designation::where('departmentId', $empDet->departmentId)->whereActive(1)->pluck('name', 'id');
        $organisation = Organisation::whereActive(1)->pluck('name', 'id');

        return view('admin.employeeLetters.experienceLetter', compact('organisation','signFiles', 'empDet', 'empCode', 'designations', 'branches'));
    }
    
    public function generateExperience(Request $request)
    {
        $empId = $request->empId;
        if($request->organisation == 1)
            $organisation = "Ellora Medicals and Educational Foundation's";
        if($request->organisation == 2)
            $organisation = "Snayraa Agency";
        if($request->organisation == 3)
            $organisation = "Tejasha Educational and research Foundation's";

        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $designationId = $request->designationId;
        $comment = $request->comment;

        $signDetials = SignatureFile::where('designationName', 'HR Manager')->first();

        $empDet = EmpDet::join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->join('organisations', 'emp_dets.organisationId', 'organisations.id')
        ->select('emp_dets.branchId','emp_dets.designationId','emp_dets.id','emp_dets.gender','emp_dets.active',
        'emp_dets.empCode','emp_dets.name', 'designations.name as designationName','organisations.name as organisationName',
        'emp_dets.jobJoingDate','emp_dets.gender', 'contactus_land_pages.branchName','emp_dets.lastDate')
        ->where('emp_dets.id', $empId)
        ->first();

        if($request->submit == 'Download Experience Letter')
        {
            $file = $empDet->empCode.'Experience Letter.pdf';
            $pdf = PDF::loadView('admin.employeeLetters.experienceLetterPDF',compact('empDet','comment','organisation','fromDate', 'toDate','empDet','signDetials'));
            return $pdf->stream($file); 
        }
        else
        {
            $letter = new EmployeeLetter;
            $letter->empId=$empDet->id;
            $letter->comment=$request->comment;
            $letter->organisation=$request->organisation;
            $letter->designationId=$request->designationId;
            $letter->fromDate=$request->fromDate;
            $letter->toDate=$request->toDate;
            $letter->letterType=4;
            $letter->updated_by=Auth::user()->username;
            $letter->save();
            return redirect('/employeeLetters/list/4')->with('success', 'Save Experience Letter successfully!!!');
        }
    }

    public function viewExperienceLetter($id)
    {
        $empDet = EmployeeLetter::join('emp_dets', 'employee_letters.empId', 'emp_dets.id')
        ->join('designations', 'employee_letters.designationId', 'designations.id')
        ->select('emp_dets.empCode','emp_dets.name', 'designations.name as designationName',
        'employee_letters.salary as salaryScale','employee_letters.aPeriod','employee_letters.DOJ as jobJoingDate',
        'emp_dets.gender','employee_letters.organisation','employee_letters.fromDate',
        'employee_letters.toDate','employee_letters.forDate as appointmentDate','emp_dets.gender','emp_dets.active',
        'employee_letters.signBy')
        ->where('employee_letters.id', $id)
        ->first();

        if(!$empDet)
            return redirect()->back()->withInput()->with("error","Experience Letter Not Found");;
        
        if($empDet->organisation == 1)
            $organisation = "Ellora Medicals and Educational Foundation's";
        if($empDet->organisation == 2)
            $organisation = "Snayraa Agency";
        if($empDet->organisation == 3)
            $organisation = "Tejasha Educational and research Foundation's";

        $fromDate = $empDet->fromDate;
        $toDate = $empDet->toDate;
        
        $file = $empDet->empCode.'Experience Letter.pdf';
        $pdf = PDF::loadView('admin.employeeLetters.experienceLetterPDF',compact('empDet','organisation','fromDate', 'toDate'));
        return $pdf->stream($file);   
    } 
    
    public function getInternalBranchTransferLetter(Request $request)
    {
        $empCode = $request->empCode;
        if($empCode == '')
            return view('admin.employeeLetters.internalBranchTransferLetter');

        $empDet = EmpDet::select('id','salaryScale', 'name', 'designationId','jobJoingDate','lastDate', 'departmentId','branchId', 'active')->where('empCode', $empCode)->first();
        if(!$empDet)
            return redirect()->back()->withInput()->with("error","Invalid Employee Code");;

        $signFiles = SignatureFile::whereActive(1)->pluck('name', 'id');
        $branches = ContactusLandPage::whereActive(1)->orderBy('branchName')->pluck('branchName', 'id');
        $designations = Designation::where('departmentId', $empDet->departmentId)->whereActive(1)->pluck('name', 'id');
        return view('admin.employeeLetters.internalBranchTransferLetter')->with(['signFiles'=>$signFiles, 'empDet'=>$empDet, 'empCode'=>$empCode,
        'branches'=>$branches, 'designations'=>$designations]);
    }
    
    public function generateInternalBranchTransferLetter(Request $request)
    {
        $empId = $request->empId;

        $forDate = $request->forDate;
        $branchId = $request->branchId;
        $newBranchId = $request->newBranchId;

        $newBranch = ContactusLandPage::where('id', $newBranchId)->value('branchName');
        $empDet = EmpDet::join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.id','branchId','emp_dets.empCode','emp_dets.name','emp_dets.firmType','contactus_land_pages.branchName','emp_dets.gender')
        ->where('emp_dets.id', $empId)
        ->first();
        $signBy = SignatureFile::find($request->signBy);
        if($request->submit == 'Download Transfer Letter')
        {
            $file = $empDet->empCode.'Transfer Letter.pdf';
            $pdf = PDF::loadView('admin.employeeLetters.internalBranchTransferLetterPDF',compact('forDate','newBranch','empDet','signBy'));
            return $pdf->stream($file); 
        }
        else
        {
            $letter = new EmployeeLetter;
            $letter->empId=$empDet->id;
            $letter->branchId=$empDet->branchId;
            $letter->newBranchId=$request->newBranchId;
            $letter->forDate=$request->forDate;
            $letter->signBy=$request->signBy;
            $letter->letterType=7;
            $letter->updated_by=Auth::user()->username;
            if($letter->save())
            {
                $empTemp = EmpDet::find($letter->empId);
                $empTemp->branchId =  $request->newBranchId;
                $empTemp->updated_by=Auth::user()->username;
                $empTemp->save();
            }
            return redirect('/employeeLetters/list/7')->with('success', 'Save Transfer Letter Successfully!!!');
        }
    }

    public function viewInternalBranchTransferLetter($id)
    {
        $letter = EmployeeLetter::find($id);
        $empDet = EmpDet::join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.id','emp_dets.empCode','emp_dets.name','emp_dets.firmType','contactus_land_pages.branchName','emp_dets.gender')
        ->where('emp_dets.id', $letter->empId)
        ->first();
        
        $forDate = $letter->forDate;
        $newBranch = ContactusLandPage::where('id', $letter->newBranchId)->value('branchName');
        $empDet->branchName = ContactusLandPage::where('id', $letter->branchId)->value('branchName');
        $signBy = SignatureFile::find($letter->signBy);
        $file = $empDet->empCode.'Transfer Letter.pdf';
        $pdf = PDF::loadView('admin.employeeLetters.internalBranchTransferLetterPDF',compact('forDate','newBranch','empDet','signBy'));
        return $pdf->stream($file); 
    }

    public function getInternalDepartmentTransferLetter(Request $request)
    {
        $empCode = $request->empCode;
        if($empCode == '')
            return view('admin.employeeLetters.internalDepartmentTransferLetter');

        $empDet = EmpDet::select('id','salaryScale', 'name', 'designationId','jobJoingDate','lastDate', 'departmentId','branchId', 'active')->where('empCode', $empCode)->first();
        if(!$empDet)
            return redirect()->back()->withInput()->with("error","Invalid Employee Code");;

        $signFiles = SignatureFile::whereActive(1)->pluck('name', 'id');
        $departments = Department::whereActive(1)->orderBy('name')->pluck('name', 'id');
        $designations = Designation::where('departmentId', $empDet->departmentId)->whereActive(1)->pluck('name', 'id');
        return view('admin.employeeLetters.internalDepartmentTransferLetter')->with(['signFiles'=>$signFiles, 'empDet'=>$empDet, 'empCode'=>$empCode,'departments'=>$departments, 'designations'=>$designations]);
    }
    
    public function generateInternalDepartmentTransferLetter(Request $request)
    {
        $empId = $request->empId;

        $forDate = $request->forDate;
        $departmentId = $request->departmentId;
        $newDepartmentId = $request->newDepartmentId;
        $newDesignationId = $request->newDesignationId;
        $revSalary = $request->revSalary;

        $newDepartment = Department::where('id', $newDepartmentId)->value('name');
        $newDesignation = Designation::where('id', $newDesignationId)->value('name');

        $empDet = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->select('emp_dets.id','branchId','emp_dets.empCode','emp_dets.name','emp_dets.firmType','emp_dets.salaryScale',
        'departments.name as departmentName','emp_dets.gender','emp_dets.gender')
        ->where('emp_dets.id', $empId)
        ->first();

        $signBy = SignatureFile::find($request->signBy);
        if($request->submit == 'Download Transfer Letter')
        {
            $file = $empDet->empCode.'Transfer Letter.pdf';
            $pdf = PDF::loadView('admin.employeeLetters.internalDepartmentTransferLetterPDF',compact('forDate','newDesignation','newDepartment','empDet','signBy','revSalary'));
            return $pdf->stream($file); 
        }
        else
        {
            $letter = new EmployeeLetter;
            $letter->empId=$empDet->id;
            $letter->departmentId=$request->departmentId;
            $letter->newDepartmentId=$request->newDepartmentId;

            $letter->designationId=$request->designationId;
            $letter->newDesignationId=$request->newDesignationId;
            $letter->forDate=$request->forDate;
            $letter->oldSalary=$empDet->salaryScale;
            $letter->salary=$request->revSalary;
            $letter->signBy=$request->signBy;
            $letter->letterType=8;
            $letter->updated_by=Auth::user()->username;
            if($letter->save())
            {
                $empTemp = EmpDet::find($letter->empId);
                $empTemp->departmentId =  $request->newDepartmentId;
                $empTemp->designationId =  $request->newDesignationId;
                $empTemp->salaryScale =  $request->revSalary;
                $empTemp->updated_by=Auth::user()->username;
                $empTemp->save();
            }
            return redirect('/employeeLetters/list/8')->with('success', 'Save Department Transfer Letter Successfully!!!');
        }
    }

    public function viewInternalDepartmentTransferLetter($id)
    {
        $letter = EmployeeLetter::find($id);

        $empDet = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->select('emp_dets.id','branchId','emp_dets.empCode','emp_dets.name','emp_dets.firmType','emp_dets.salaryScale',
        'departments.name as departmentName','emp_dets.gender','emp_dets.gender')
        ->where('emp_dets.id', $letter->empId)
        ->first();

        $forDate = $letter->forDate;
        $departmentId = $letter->departmentId;
        $newDepartmentId = $letter->newDepartmentId;
        $newDesignationId = $letter->newDesignationId;
        $revSalary = $letter->salary;

        $newDepartment = Department::where('id', $newDepartmentId)->value('name');
        $newDesignation = Designation::where('id', $newDesignationId)->value('name');

        $signBy = SignatureFile::find($letter->signBy);

        $file = $empDet->empCode.'Transfer Letter.pdf';
        $pdf = PDF::loadView('admin.employeeLetters.internalDepartmentTransferLetterPDF',compact('forDate','newDesignation','newDepartment','empDet','signBy','revSalary'));
        return $pdf->stream($file); 
    }   

    public function getPromotionLetter(Request $request)
    {
        $empCode = $request->empCode;
        if($empCode == '')
            return view('admin.employeeLetters.promotionLetter');

        $empDet = EmpDet::select('id','salaryScale', 'name', 'designationId','jobJoingDate','lastDate', 'departmentId','branchId', 'active')->where('empCode', $empCode)->first();
        if(!$empDet)
            return redirect()->back()->withInput()->with("error","Invalid Employee Code");;

        $signFiles = SignatureFile::whereActive(1)->pluck('name', 'id');
        $departments = Department::whereActive(1)->orderBy('name')->pluck('name', 'id');
        $designations = Designation::where('departmentId', $empDet->departmentId)->whereActive(1)->pluck('name', 'id');
        return view('admin.employeeLetters.promotionLetter')->with(['signFiles'=>$signFiles, 'empDet'=>$empDet, 'empCode'=>$empCode,'departments'=>$departments, 'designations'=>$designations]);
    }
    
    public function generatePromotionLetter(Request $request)
    {
        $empId = $request->empId;

        $forDate = $request->forDate;
        $departmentId = $request->departmentId;
        $newDepartmentId = $request->newDepartmentId;
        $newDesignationId = $request->newDesignationId;
        $revSalary = $request->revSalary;

        $newDepartment = Department::where('id', $newDepartmentId)->value('name');
        $newDesignation = Designation::where('id', $newDesignationId)->value('name');

        $empDet = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->select('emp_dets.id','branchId','emp_dets.empCode','emp_dets.name','emp_dets.firmType','emp_dets.salaryScale',
        'departments.name as departmentName','emp_dets.gender','emp_dets.gender')
        ->where('emp_dets.id', $empId)
        ->first();

        $signBy = SignatureFile::find($request->signBy);
        if($request->submit == 'Download Transfer Letter')
        {
            $file = $empDet->empCode.'Promotion Letter.pdf';
            $pdf = PDF::loadView('admin.employeeLetters.promotionLetterPDF',compact('forDate','newDesignation','newDepartment','empDet','signBy','revSalary'));
            return $pdf->stream($file); 
        }
        else
        {
            $letter = new EmployeeLetter;
            $letter->empId=$empDet->id;
            $letter->departmentId=$request->departmentId;
            $letter->newDepartmentId=$request->newDepartmentId;
            $letter->designationId=$request->designationId;
            $letter->newDesignationId=$request->newDesignationId;
            $letter->forDate=$request->forDate;
            $letter->oldSalary=$empDet->salaryScale;
            $letter->salary=$request->revSalary;
            $letter->signBy=$request->signBy;
            $letter->letterType=9;
            $letter->updated_by=Auth::user()->username;
            if($letter->save())
            {
                $empTemp = EmpDet::find($letter->empId);
                $empTemp->departmentId =  $request->newDepartmentId;
                $empTemp->designationId =  $request->newDesignationId;
                $empTemp->salaryScale =  $request->revSalary;
                $empTemp->updated_by=Auth::user()->username;
                $empTemp->save();
            }
            return redirect('/employeeLetters/list/9')->with('success', 'Save Promotion Letter Successfully!!!');
        }
    }

    public function viewPromotionLetter($id)
    {
        $letter = EmployeeLetter::find($id);

        $empDet = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->select('emp_dets.id','branchId','emp_dets.empCode','emp_dets.name','emp_dets.firmType','emp_dets.salaryScale',
        'departments.name as departmentName','emp_dets.gender','emp_dets.gender')
        ->where('emp_dets.id', $letter->empId)
        ->first();

        $forDate = $letter->forDate;
        $departmentId = $letter->departmentId;
        $newDepartmentId = $letter->newDepartmentId;
        $newDesignationId = $letter->newDesignationId;
        $revSalary = $letter->salary;

        $newDepartment = Department::where('id', $newDepartmentId)->value('name');
        $newDesignation = Designation::where('id', $newDesignationId)->value('name');

        $signBy = SignatureFile::find($letter->signBy);

        $file = $empDet->empCode.'Transfer Letter.pdf';
        $pdf = PDF::loadView('admin.employeeLetters.promotionLetterPDF',compact('forDate','newDesignation','newDepartment','empDet','signBy','revSalary'));
        return $pdf->stream($file); 
    }  
}
