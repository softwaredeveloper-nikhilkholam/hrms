<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\FormAndCircular;
use App\CircularPhoto;
use App\Notification;
use App\ContactusLandPage;
use App\Designation;
use App\EmpDet;
use App\ExitProcessStatus;
use App\NdcHistory;
use App\DocumentOffered;
use Auth;
use File;
use PDF;


class FormAndCircularsController extends Controller
{
    public function index()
    {
        $formCirculars = FormAndCircular::whereActive(1)->orderBy('id')->get();
        return view('admin.formsCirculars.list')->with(['formCirculars'=>$formCirculars]);
    }

    public function dlist()
    {
        $formCirculars = FormAndCircular::whereActive(0)->orderBy('id')->get();
        return view('admin.formsCirculars.dlist')->with(['formCirculars'=>$formCirculars]);
    }
   
    public function create()
    {
        return view('admin.formsCirculars.create');        
    }
  
    public function store(Request $request)
    {
        if(FormAndCircular::where('circularNo', $request->circularNo)->count())
            return redirect()->back()->withInput()->with("error","Circular already exist. Please TRY another one!!!");;

        $formsCircular = new FormAndCircular();  
        $formsCircular->circularNo = $request->circularNo;
        $formsCircular->name = $request->name;
        $formsCircular->status = $request->status;
        $formsCircular->updated_by=Auth::user()->username;
        if($formsCircular->save())
        {
            if(!empty($request->file('photos')))
            {
                if($request->hasfile('photos')) { 
                    foreach($request->file('photos') as $file)
                    {
                        $cirPhoto = new CircularPhoto;
                        $cirPhoto->circularId = $formsCircular->id;

                        $fileName = $request->circularNo.'_'.time().'_'.rand(0, 1000);
                        $fileName = strval($fileName);
                        $fileName = str_replace('/', '_', $fileName);
                        $fileName = $fileName.'.'.$file->getClientOriginalExtension();
                        $file->move(public_path()."/admin/images/formscirculars/",$fileName);
                        
                        $cirPhoto->photo = $fileName;
                        $cirPhoto->save();
                    }
                }
            } 

        }
        
        return redirect('/formsCirculars')->with('success', 'New Circular added successfully!!!');
    }
   
    public function show($id)
    {
        $formsCircular = FormAndCircular::find($id);
        $photos = CircularPhoto::where('circularId', $formsCircular->id)->get();
        
        return view('admin.formsCirculars.show')->with(['photos'=>$photos, 'formsCircular'=>$formsCircular]);    
    }
  
    public function edit($id)
    {
        $formsCircular = FormAndCircular::find($id);
        $photos = CircularPhoto::where('circularId', $formsCircular->id)->get();
        
        return view('admin.formsCirculars.edit')->with(['photos'=>$photos, 'formsCircular'=>$formsCircular]);                      
    }
   
    public function update(Request $request, $id)
    {
        if(FormAndCircular::where('id', '<>', $id)->where('circularNo', $request->circularNo)->count())
            return redirect()->back()->withInput()->with("error","Circular already exist. Please TRY another one!!!");;

        $formsCircular = FormAndCircular::find($id);  
        $formsCircular->circularNo = $request->circularNo;
        $formsCircular->name = $request->name;
        $formsCircular->status = $request->status;
        $formsCircular->updated_by=Auth::user()->username;
        if($formsCircular->save())
        {
            if(!empty($request->file('photos')))
            {
                $photos = CircularPhoto::where('circularId', $id)->get();
                if(count($photos))
                {
                    foreach($photos as $image)
                    {
                        if($image->photo != '')
                        {
                            $oldImage = base_path('public/admin/images/formscirculars/').$image->photo;

                            if (File::exists($oldImage))  // unlink or remove previous image from folder
                            {
                                unlink($oldImage);
                            }
                        }
                    }
                }
                CircularPhoto::where('circularId', $id)->delete();
                if($request->hasfile('photos')) { 
                    foreach($request->file('photos') as $file)
                    {
                        $cirPhoto = new CircularPhoto;
                        $cirPhoto->circularId = $formsCircular->id;

                        $fileName = $request->circularNo.'_'.time().'_'.rand(0, 1000);
                        $fileName = strval($fileName);
                        $fileName = str_replace('/', '_', $fileName);
                        $fileName = $fileName.'.'.$file->getClientOriginalExtension();
                        $file->move(public_path()."/admin/images/formscirculars/",$fileName);
                        
                        $cirPhoto->photo = $fileName;
                        $cirPhoto->save();
                    }
                }
            } 
        }

        return redirect('/formsCirculars')->with('success', 'Circular updated successfully!!!');
  
    }  

    public function activate($id)
    {
        FormAndCircular::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        return redirect('/formsCirculars')->with('success', 'Forms & Circular Activated successfully!!!');
    }

    public function deactivate($id)
    {
        FormAndCircular::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        return redirect('/formsCirculars/dlist')->with('success', 'Forms & Circular Deactivated successfully!!!');
    }

    public function employeeList()
    {
        $formCirculars = FormAndCircular::whereActive(1)
        ->where('status', "All Employees")->get();
        return view('admin.formsCirculars.employeeList')->with(['formCirculars'=>$formCirculars]);
    }
 
    public function getExperienceLetters(Request $request)
    {
        $empCode = $request->empCode;
        $lastWorkingDay = $request->lastWorkingDay;
        $authority = $request->authority;
        if($empCode == '')
            return view('admin.letterFormats.experienceLetter');
        else
        {
            $employee = EmpDet::join('designations', 'emp_dets.designationId', 'designations.id')
            ->select('emp_dets.id','emp_dets.name', 'designations.name as designation','emp_dets.jobJoingDate')
            ->where('emp_dets.empCode', $empCode)
            ->first();
            if(!$employee)
                return redirect()->back()->withInput()->with("error","Employee Not Found..");

            if(!$lastWorkingDay)
            {
                $lastWorkingDay = NdcHistory::where('empCode', $empCode)->value('exitDate');
                if($lastWorkingDay == '' || $lastWorkingDay == null)
                {
                    $lastWorkingDay = ExitProcessStatus::where('empCode', $empCode)->value('exitDate');
                }
            }
               
            $document = new DocumentOffered;
            $document->empId=$employee->id;
            $document->empCode=$empCode;
            $document->forDate=date('Y-m-d');
            $document->updated_by=Auth::user()->username;

            $file = $empCode.'-ExperienceLetter.pdf';
            $pdf = PDF::loadView('admin.letterFormats.formats.experienceLetterPdf',compact('empCode','employee','lastWorkingDay','authority'));
            $document->save();

            return $pdf->download($file);  
        }
    }

    public function getOfferLetters(Request $request)
    {
        
    }

    public function generateOfferLetter(Request $request)
    {
        
    }

    public function getAppointmentLetters(Request $request)
    {
        $empCode = $request->empCode;
        if($empCode == '')
            return view('admin.formsCirculars.appointmentLetter');

        $empDet = EmpDet::select('id','salaryScale', 'name', 'designationId','jobJoingDate', 'departmentId','branchId')->where('empCode', $empCode)->first();
        if(!$empDet)
            return redirect()->back()->withInput()->with("error","Invalid Employee Code");;

        $branches = ContactusLandPage::whereActive(1)->pluck('branchName', 'id');
        $designations = Designation::where('departmentId', $empDet->departmentId)->whereActive(1)->pluck('name', 'id');
        return view('admin.formsCirculars.appointmentLetter')->with(['empDet'=>$empDet, 'empCode'=>$empCode,
        'branches'=>$branches, 'designations'=>$designations]);
    }

    public function generateAppointment(Request $request)
    {
        // return $request->all();
        $empId = $request->empId;
        if($request->organisation == 1)
            $organisation = "Ellora Medicals and Educational foundation";
        if($request->organisation == 2)
            $organisation = "Snayraa Agency";
        if($request->organisation == 3)
            $organisation = "Tejasha Educational and research foundation";

        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $salary = $request->salary;
        $appointmentDate = $request->appointmentDate;
        $signBy = $request->signBy;

        $empDet = EmpDet::join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.empCode','emp_dets.name', 'designations.name as designationName',
        'emp_dets.jobJoingDate','emp_dets.gender', 'contactus_land_pages.branchName')
        ->where('emp_dets.id', $empId)
        ->first();

        $file = $empDet->empCode.'Appointment Letter.pdf';
        $pdf = PDF::loadView('admin.letters.appointmentLetter',compact('organisation','fromDate', 'toDate', 'salary', 'appointmentDate', 'signBy', 'empDet'));
        return $pdf->stream($file);  
    }

    public function getAgreement(Request $request)
    {
        $empCode = $request->empCode;
        if($empCode == '')
            return view('admin.formsCirculars.agreement');

        $empName = EmpDet::select('name')->where('empCode', $empCode)->value('name');
        if($empName == '')
            return redirect()->back()->withInput()->with("error","Invalid Employee Code");

        return view('admin.formsCirculars.agreement')->with(['empName'=>$empName, 'empCode'=>$empCode]);
    }

    public function generateAgreement(Request $request)
    {
        return $request->all();
    }
}
