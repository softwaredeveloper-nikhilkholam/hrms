<?php

namespace App\Http\Controllers\admin\masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use BranchDistance;
use Auth;

class BranchDistancesController extends Controller
{    
    public function index()
    {
        $branchDists = BranchDistance::join('departments', 'designations.departmentId', 'departments.id')
        ->select('departments.name as departmentName', 'designations.*')
        ->where('designations.active', 1)
        ->get();
        return view('admin.masters.designations.list')->with(['branchDists'=>$branchDists]);
    }

    public function dlist()
    {
        $branchDists = BranchDistance::join('departments', 'designations.departmentId', 'departments.id')
        ->select('departments.name as departmentName', 'designations.*')
        ->where('designations.active', 0)
        ->get();
        return view('admin.masters.designations.dlist')->with(['designations'=>$branchDists]);
    }
   
    public function create()
    {
        $departments = Department::whereActive(1)->pluck('name', 'id');
        return view('admin.masters.designations.create')->with(['departments'=>$departments]);        
    }
  
    public function store(Request $request)
    {
        if(BranchDistance::where('departmentId', $request->departmentId)->where('name', $request->name)->count())
            return redirect()->back()->withInput()->with("error","Branch Name already exist. Please TRY another one!!!");;

        $designation = new Designation();
        $designation->departmentId = $request->departmentId;
        $designation->name = $request->name;
        $designation->updated_by=Auth::user()->username;
        $designation->save();

        return redirect('/designations')->with('success', 'New Designation added successfully!!!');

    }
   
    public function show($id)
    {
        $designation = BranchDistance::join('departments', 'designations.departmentId', 'departments.id')       
        ->select('departments.name as departmentName', 'designations.*')
        ->first();
        return view('admin.masters.designations.show')->with(['designation'=>$designation]);    
    }
  
    public function edit($id)
    {
        $designation = BranchDistance::find($id);
        $departments = Department::whereActive(1)->pluck('name', 'id');
        return view('admin.masters.designations.edit')->with(['designation'=>$designation,'departments'=>$departments]);                      
    }
   
    public function update(Request $request, $id)
    {
        if($request->name && BranchDistance::Where('name', $request->name)->where('id','<>', $id)->count())
            return redirect()->back()->withInput()->with("error","Branch Name already exist. Please TRY another one!!!");;

        $designation = BranchDistance::find($id);
        $designation->departmentId = $request->departmentId;
        $designation->name = $request->name;
        $designation->updated_by=Auth::user()->username;
        $designation->save();

        if($designation->active == 1)
            return redirect('/designations')->with('success', 'Designation Updated successfully!!!');
        else
            return redirect('/designations/dlist')->with('success', 'Designation Updated successfully!!!');
    }  

    public function activate($id)
    {
        BranchDistance::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        return redirect('/designations')->with('success', 'Designation Activated successfully!!!');
    }

    public function deactivate($id)
    {
        BranchDistance::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        return redirect('/designations/dlist')->with('success', 'Designation Deactivated successfully!!!');
    }
}