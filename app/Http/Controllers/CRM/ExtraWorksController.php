<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ContactusLandPage;
use App\Department;
use App\Models\TaskMaster;
use App\Models\AssignTaskMaster;
use App\EmpDet;
use Auth;

class ExtraWorksController extends Controller
{
    public function raiseTask()
    {
        $departments = Department::where('active', 1)->orderBy('name')->pluck('name', 'id');
        return view('CRM.extraTask.raisedTask')->with(['departments'=>$departments]);
    }

    public function requestList()
    {
        return view('CRM.extraTask.requestList');
    }
    public function dTaskList()
    {
        return view('CRM.extraTask.dTaskList');
    }
    public function assignedTask()
    {
        return view('CRM.extraTask.assignedTask');
    }
    public function rescheduleTask()
    {
        return view('CRM.extraTask.rescheduleTask');
    }
    
}
   
