<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EmpDet;
use App\ResourceRequest;
use Auth;
use File;

class SignatureFilesController extends Controller
{    
    public function index()
    {
        $request = ResourceRequest::where()->where('status',)->get();
        return view('admin.signFiles.list')->with(['signFiles'=>$signFiles]);
    }

    public function dlist()
    {
    }
   
    public function create()
    {
    }
  
    public function store(Request $request)
    {
       
    }
   
    public function edit($id)
    {
        
    }
   
    public function update(Request $request, $id)
    {
      
    }  

    public function activate($id)
    {
       
    }

    public function deactivate($id)
    {
       
    }
}