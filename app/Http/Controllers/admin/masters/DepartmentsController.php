<?php

namespace App\Http\Controllers\admin\masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Department;
use App\Designation;
use App\Helpers\Utility;
use App\EmpDet;
use App\User;
use Auth;
use App\TempDepartment;

class DepartmentsController extends Controller
{    
    public function index()
    {
        $departments = Department::whereActive(1)->get();
        return view('admin.masters.departments.list')->with(['departments'=>$departments]);
    }

    public function dlist()
    {
        $departments = Department::whereActive(0)->get();
        return view('admin.masters.departments.dlist')->with(['departments'=>$departments]);
    }
   
    public function create()
    {
        return view('admin.masters.departments.create');        
    }
  
    public function store(Request $request)
    {
        if(Department::where('section', $request->section)->where('name', $request->name)->count())
            return redirect()->back()->withInput()->with("error","Department Name already exist. Please TRY another one!!!");;

        $department = new Department();    
        $department->name = $request->name;     
        $department->section = $request->section;     
        $department->updated_by=Auth::user()->username;
        $department->save();

        return redirect('/departments')->with('success', 'New Department added successfully!!!');

    }
   
    public function show($id)
    {
        $department = Department::find($id);
        return view('admin.masters.departments.show')->with(['department'=>$department]);    
    }
  
    public function edit($id)
    {
        $department = Department::find($id);
        return view('admin.masters.departments.edit')->with(['department'=>$department]);                      
    }
   
    public function update(Request $request, $id)
    {
        if($request->name && Department::where('section', $request->section)->Where('name', $request->name)->where('id','<>', $id)->count())
            return redirect()->back()->withInput()->with("error","Department Name already exist. Please TRY another one!!!");;

        $department = Department::find($id);
        $department->name = $request->name;  
        $department->section = $request->section;       
        $department->updated_by=Auth::user()->username;
        $department->save();
        
        if($department->active == 1)
            return redirect('/departments')->with('success', 'Department Updated successfully!!!');
        else
            return redirect('/departments/dlist')->with('success', 'Department Updated successfully!!!');
    }  

    public function activate($id)
    {
        Department::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        return redirect('/departments')->with('success', 'Department Activated successfully!!!');
    }

    public function deactivate($id)
    {
        Department::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        return redirect('/departments/dlist')->with('success', 'Department Deactivated successfully!!!');
    }

    public function getDesignations($id)
    {
       return Designation::where('departmentId', $id)->where('active', 1)->get();
    }

    public function getDepartments($sectionId)
    {
       return Department::where('section', $sectionId)->where('active', 1)->get();
    }

    public function getReporting($sectionId)
    {
        if($sectionId == 'Teaching')
        {
            return EmpDet::join('designations', 'emp_dets.designationId', 'designations.id')
            ->select('emp_dets.id', 'emp_dets.name')
            ->whereIn('designations.name', ['HOD','Principal','Academic Director','Supervisor','Academic Administator'])
            ->where('emp_dets.active', 1)
            ->get();
        }
        else
        {
            $report1 = collect(EmpDet::select('name', 'id', 'empCode')->where('userRoleId', '!=', '5')->orderBy('name')->whereActive(1)->get());
            $report2 = collect(User::select('name', 'id', 'empId as empCode')->whereIn('userType', [201,301,401,501])->orderBy('name')->whereActive(1)->get());
        
            $empReportings = $report1->merge($report2);
            return $empReportings=$empReportings->sortBy('name');  
        }
    }

    


    
}

