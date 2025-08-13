<?php

namespace App\Http\Controllers\admin\masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\DesignationExport;
use App\Department;
use App\Designation;
use Auth;
use Excel;
use File;

class DesignationsController extends Controller
{    
    public function index()
    {
        $designations = Designation::join('departments', 'designations.departmentId', 'departments.id')
        ->select('departments.name as departmentName', 'designations.*')
        ->where('designations.active', 1)
        ->get();
        return view('admin.masters.designations.list')->with(['designations'=>$designations]);
    }

    public function dlist()
    {
        $designations = Designation::join('departments', 'designations.departmentId', 'departments.id')
        ->select('departments.name as departmentName', 'designations.*')
        ->where('designations.active', 0)
        ->get();
        return view('admin.masters.designations.dlist')->with(['designations'=>$designations]);
    }
   
    public function create()
    {
        $departments = Department::whereActive(1)->pluck('name', 'id');
        return view('admin.masters.designations.create')->with(['departments'=>$departments]);        
    }
  
    public function store(Request $request)
    {
        if(Designation::where('departmentId', $request->departmentId)->where('name', $request->name)->count())
            return redirect()->back()->withInput()->with("error","Branch Name already exist. Please TRY another one!!!");;

        $designation = new Designation();
        $designation->departmentId = $request->departmentId;
        $designation->name = $request->name;
        $designation->category = $request->category;
        $designation->profile = $request->profile;
        $designation->updated_by=Auth::user()->username;     
        $designation->save();

        return redirect('/designations')->with('success', 'New Designation added successfully!!!');

    }
   
    public function show($id)
    {
        $designation = Designation::join('departments', 'designations.departmentId', 'departments.id')       
        ->select('departments.name as departmentName', 'designations.*')
        ->where('designations.id', $id)
        ->first();
        return view('admin.masters.designations.show')->with(['designation'=>$designation]);    
    }
  
    public function edit($id)
    {
        $designation = Designation::find($id);
        $departments = Department::whereActive(1)->pluck('name', 'id');
        return view('admin.masters.designations.edit')->with(['designation'=>$designation,'departments'=>$departments]);                      
    }
   
    public function update(Request $request, $id)
    {
        $designation = Designation::find($id);
        $designation->departmentId = $request->departmentId;
        $designation->name = $request->name;
        $designation->category = $request->category;
        $designation->interviewStatus = $request->interviewStatus;
        $designation->updated_by=Auth::user()->username;
        $designation->profile = $request->profile;
        $designation->save();

        if($designation->active == 1)
            return redirect('/designations')->with('success', 'Designation Updated successfully!!!');
        else
            return redirect('/designations/dlist')->with('success', 'Designation Updated successfully!!!');
    }  

    public function activate($id)
    {
        Designation::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        return redirect('/designations')->with('success', 'Designation Activated successfully!!!');
    }

    public function deactivate($id)
    {
        Designation::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        return redirect('/designations/dlist')->with('success', 'Designation Deactivated successfully!!!');
    }

    public function excel($active)
    {
        $fileName = 'DesignationExcel.xlsx';
        return Excel::download(new DesignationExport($active, 0), $fileName);
    }
}

