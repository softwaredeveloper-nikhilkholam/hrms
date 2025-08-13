<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Appointment;
use App\EmpDet;
use App\Exports\AppointmentExport;
use App\User;
use Auth;
use PDF;
use App\AttendanceDetail;
use App\AttendanceConfirm;
use App\ContactusLandPage;
use Excel;

class AppointmentsController extends Controller
{
    public function index()
    {
        $empId = Auth::user()->empId;
        $appointments = Appointment::join('users', 'appointments.appointWith','users.id')
        ->select('users.name', 'appointments.*')
        ->where('appointments.active', 1)
        ->where('appointments.empId', $empId)
        ->orderBy('appointments.status')
        ->get();
        return view('admin.appointments.list')->with(['appointments'=>$appointments]);
    }

    public function requestList()
    {
        $userId = Auth::user()->id;
        $users = User::where('id', $userId)->value('appointStatus');
        $users = explode(",",$users);

        $appointments = Appointment::join('emp_dets', 'appointments.empId', 'emp_dets.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->select('emp_dets.empCode', 'emp_dets.firmType', 'emp_dets.name', 'contactus_land_pages.branchName',
        'departments.name as departmentName', 'appointments.*')
        ->where('appointments.active', 1)
        ->whereIn('appointments.appointWith', $users)
        ->orderBy('appointments.forDate', 'desc')
        ->paginate(10);
        
        return view('admin.appointments.requestList')->with(['appointments'=>$appointments]);
    }

    public function search(Request $request)
    {
        $requestTo = $request->requestTo;
        $forDate = $request->forDate;
        $status = $request->status;
        $priority = $request->priority;

        $userId = Auth::user()->id;
        $userType = Auth::user()->userType;
        if($userType != '51')
        {
            $users = User::where('id', $userId)->value('appointStatus');
            $users = explode(",",$users);
        }

        $appointments = Appointment::join('emp_dets', 'appointments.empId', 'emp_dets.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->select('emp_dets.empCode', 'emp_dets.firmType', 'emp_dets.name', 'contactus_land_pages.branchName',
        'departments.name as departmentName', 'appointments.*')
        ->where('appointments.active', 1)
        ->where('appointments.forDate', $forDate);

        if($priority != '')
            $appointments = $appointments->where('appointments.priority', $priority);

        if($status != '')
            $appointments = $appointments->where('appointments.status', $status);

        if($requestTo != '')
            $appointments = $appointments->where('appointments.appointWith', $requestTo);

        $appointments = $appointments->orderBy('appointments.status')
        ->paginate(10)  
        ->appends(['requestTo' => $requestTo,'status' => $status,'priority' => $priority,'forDate' => $forDate]);

        return view('admin.appointments.requestList')->with(['appointments'=>$appointments,
        'requestTo' => $requestTo,'status' => $status,'priority' => $priority,'forDate' => $forDate]);
    }

    public function create()
    {
         $empDet = EmpDet::join('departments', 'emp_dets.departmentId', '=', 'departments.id')
        ->join('designations', 'emp_dets.designationId', '=', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', '=', 'contactus_land_pages.id')
        ->select(
            'emp_dets.*',
            'departments.name as deptName',
            'designations.name as desName',
            'contactus_land_pages.branchName'
        )
        ->where('emp_dets.id', Auth::user()->empId)
        ->first();
      
        return view('admin.appointments.create')->with(['empDet'=>$empDet]);
    }
  
    public function store(Request $request)
    {
        $appoint = new Appointment;
        $appoint->empId = Auth::user()->empId;
        $appoint->appointWith = $request->requestTo;
        $appoint->forDate = $request->forDate;
        $appoint->originalForDate = $request->forDate;
        $appoint->reason = $request->agenda;
        $appoint->priority = $request->priority;
        $appoint->updated_by = Auth::user()->username;
        $appoint->save();
        return redirect('/appointments')->with("success","Appointment Application Send successfully...");
    }
   
    public function show($id)
    {
        $appointment = Appointment::join('users', 'appointments.appointWith', 'users.id')
        ->join('emp_dets', 'appointments.empId', 'emp_dets.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('emp_dets.empCode', 'emp_dets.firmType', 'emp_dets.name', 'contactus_land_pages.branchName',
        'designations.name as desName','departments.name as departmentName', 'appointments.*','users.name as userName')
        ->where('appointments.id', $id)
        ->first();

        $lastStatus = Appointment::select('forDate', 'status')
        ->where('appointments.id', '<', $id)
        ->where('appointments.appointWith', $appointment->appointWith)
        ->first();
        $branches=ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
         
        return view('admin.appointments.show')->with(['appointment'=>$appointment, 'branches'=>$branches, 'lastStatus'=>$lastStatus]);
    }
   
    public function edit($id)
    {
        $appointment = Appointment::find($id);
        return view('admin.appointments.edit')->with(['appointment'=>$appointment]);
    }
  
    public function update(Request $request, $id)
    {
        return $request->all();
    }

    public function changeStatus(Request $request)
    {
        $appointment = Appointment::find($request->id);
       
        if(isset($request->reject))
            $appointment->status = 4;

        if(isset($request->postponed))
            $appointment->status = 3;

        if(isset($request->approve))
            $appointment->status = 2;

        $appointment->comment = $request->comment;
        $appointment->location = $request->otherLocation;
        $appointment->forDate = $request->forDate;
        $appointment->forTime = $request->forTime;

        if(isset($request->Completed))
            $appointment->meetingStatus = 'Completed';

        $appointment->updated_by = Auth::user()->username;
        $appointment->save();
        return redirect('/appointments/requestList')->with("success","Changed Appointment Application Status successfully...");
    }

    public function exportPDF($requestTo, $priority, $status, $forDate)
    {
        $appointments = Appointment::join('emp_dets', 'appointments.empId', 'emp_dets.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->select('emp_dets.empCode', 'emp_dets.firmType', 'emp_dets.name', 'contactus_land_pages.branchName',
        'departments.name as departmentName', 'appointments.*')
        ->where('appointments.active', 1)
        ->where('appointments.forDate', $forDate);

        if($priority != 0)
            $appointments = $appointments->where('appointments.priority', $priority);

        if($status != 0)
            $appointments = $appointments->where('appointments.status', $status);

        if($requestTo != 0)
            $appointments = $appointments->where('appointments.appointWith', $requestTo);

        $appointments = $appointments->orderBy('appointments.status')
        ->get();

        // foreach($appointments as $app)
        // {
        //     $fromDest = ContactusLandPage::where('id', $app->fromDest)->value('branchName');
        //     $app['fromDest'] = ($fromDest == '')?$app->fromDest:$fromDest;

        //     $toDest = ContactusLandPage::where('id', $app->toDest)->value('branchName');
        //     $app['toDest'] = ($toDest == '')?$app->toDest:$toDest;
        // }

        $file = 'Appointment_'.$forDate.'.pdf';
        $pdf = PDF::loadView('admin.reports.appointmentPdfView',compact('appointments', 'forDate'));
        return $pdf->stream($file);  
    }

    public function exportExcel()
    {
        $fileName = 'Appointment.xlsx';
        return Excel::download(new AppointmentExport(), $fileName);
    }

    public function getLocations()
    {
        return Appointment::distinct('location')
        ->whereNotNull('location')
        ->orderBy('location')
        ->pluck('location');
    }

}
