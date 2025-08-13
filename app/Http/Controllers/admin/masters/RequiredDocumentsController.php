<?php

namespace App\Http\Controllers\admin\masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\RequiredDocument;
use App\Department;
use App\Designation;
use Auth;

class RequiredDocumentsController extends Controller
{    
    public function index()
    {
        $requiredDocuments = RequiredDocument::join('departments', 'required_documents.departmentId', 'departments.id')
        ->join('designations', 'required_documents.designationId', 'designations.id')
        ->select('required_documents.*', 'departments.name as departmentName', 'designations.name as designationName')
        ->where('required_documents.active',1)
        ->orderBy('required_documents.departmentId')
        ->orderBy('required_documents.designationId')
        ->get();
        return view('admin.masters.requiredDocuments.list')->with(['requiredDocuments'=>$requiredDocuments]);
    }

    public function dlist()
    {
        $requiredDocuments = RequiredDocument::join('departments', 'required_documents.departmentId', 'departments.id')
        ->join('designations', 'required_documents.designationId', 'designations.id')
        ->select('required_documents.*', 'departments.name as departmentName', 'designations.name as designationName')
        ->where('required_documents.active',0)
        ->orderBy('required_documents.departmentId')
        ->orderBy('required_documents.designationId')
        ->get();
        return view('admin.masters.requiredDocuments.dlist')->with(['requiredDocuments'=>$requiredDocuments]);
    }
   
    public function create()
    {
        $departments = Department::whereActive(1)->pluck('name', 'id');
        return view('admin.masters.requiredDocuments.create')->with(['departments'=>$departments]);        
    }
  
    public function store(Request $request)
    {
        if(RequiredDocument::where('departmentId', $request->departmentId)->where('designationId', $request->designationId)->where('name', $request->name)->count())
            return redirect()->back()->withInput()->with("error","Required Document Name already exist. Please TRY another one!!!");;

        $requiredDocument = new RequiredDocument();    
        $requiredDocument->departmentId = $request->departmentId;     
        $requiredDocument->designationId = $request->designationId;     
        $requiredDocument->name = $request->name;     
        $requiredDocument->remarks = $request->remarks;     
        $requiredDocument->updated_by=Auth::user()->username;
        $requiredDocument->save();

        return redirect('/requiredDocuments')->with('success', 'New Required Document added successfully!!!');
    }
   
    public function show($id)
    {
        $requiredDocument = RequiredDocument::join('departments', 'required_documents.departmentId', 'departments.id')
        ->join('designations', 'designations.designationId', 'designations.id')
        ->select('required_documents.*', 'departments.name as departmentName', 'designations.name as designationName')
        ->where('required_documents.id',$id)
        ->first();
        return view('admin.masters.requiredDocuments.show')->with(['requiredDocument'=>$requiredDocument]);    
    }
  
    public function edit($id)
    {
        $requiredDocument = RequiredDocument::find($id);
        $departments = Department::whereActive(1)->pluck('name', 'id');
        $designations = Designation::where('departmentId', $requiredDocument->departmentId)
        ->where('active',1)
        ->pluck('name', 'id');

        return view('admin.masters.requiredDocuments.edit')->with(['departments'=>$departments,'designations'=>$designations,'requiredDocument'=>$requiredDocument]);                      
    }
   
    public function update(Request $request, $id)
    {
        if($request->name && RequiredDocument::where('departmentId', $request->departmentId)->where('designationId', $request->designationId)->Where('name', $request->name)->where('id','<>', $id)->count())
            return redirect()->back()->withInput()->with("error","Required Document Name already exist. Please TRY another one!!!");;

        $requiredDocument = RequiredDocument::find($id);
        $requiredDocument->departmentId = $request->departmentId;     
        $requiredDocument->designationId = $request->designationId; 
        $requiredDocument->name = $request->name;     
        $requiredDocument->remarks = $request->remarks;     
        $requiredDocument->updated_by=Auth::user()->username;
        $requiredDocument->save();
        
        if($requiredDocument->active == 1)
            return redirect('/requiredDocuments')->with('success', 'Required Document Updated successfully!!!');
        else
            return redirect('/requiredDocuments/dlist')->with('success', 'Required Document Updated successfully!!!');
    }  

    public function activate($id)
    {
        RequiredDocument::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        return redirect('/requiredDocuments')->with('success', 'Required Document Activated successfully!!!');
    }

    public function deactivate($id)
    {
        RequiredDocument::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        return redirect('/requiredDocuments/dlist')->with('success', 'Required Document Deactivated successfully!!!');
    }
}
