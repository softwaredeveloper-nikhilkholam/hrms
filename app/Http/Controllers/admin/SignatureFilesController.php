<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SignatureFile;
use Auth;
use File;

class SignatureFilesController extends Controller
{    
    public function index()
    {
        $signFiles = SignatureFile::whereActive(1)->get();
        return view('admin.signFiles.list')->with(['signFiles'=>$signFiles]);
    }

    public function dlist()
    {
        $signFiles = SignatureFile::whereActive(0)->get();
        return view('admin.signFiles.dlist')->with(['signFiles'=>$signFiles]);
    }
   
    public function create()
    {
        return view('admin.signFiles.create');        
    }
  
    public function store(Request $request)
    {
        if(SignatureFile::where('name', $request->name)->count())
            return redirect()->back()->withInput()->with("error","Signature Name already exist. Please TRY another one!!!");;

        $signFile = new SignatureFile();    
        $signFile->name = $request->name;  
        if(!empty($request->file('fileName')))
        {
            $fileName = $signFile->name.".".$request->fileName->extension();  
            $request->fileName->move(public_path('admin/signFiles/'), $fileName);
            $signFile->fileName = $fileName;
        }

        $signFile->updated_by=Auth::user()->username;
        $signFile->save();

        return redirect('/signFiles')->with('success', 'New Signature File uploaded successfully!!!');
    }
   
    public function edit($id)
    {
        $signFile = SignatureFile::find($id);
        return view('admin.signFiles.edit')->with(['signFile'=>$signFile]);                      
    }
   
    public function update(Request $request, $id)
    {
        if($request->name && SignatureFile::Where('name', $request->name)->where('id','<>', $id)->count())
            return redirect()->back()->withInput()->with("error","Signature File Name already exist. Please TRY another one!!!");;

        $signFile = SignatureFile::find($id);
        $signFile->name = $request->name;    
        if(!empty($request->file('fileName')))
        {
            if($signFile->fileName != '')
            {
                $oldImage = public_path('admin/signFiles/').$signFile->name;

                if (File::exists($oldImage))  // unlink or remove previous image from folder
                {
                    unlink($oldImage);
                }
            }

            $fileName = $signFile->name.".".$request->fileName->extension();  
            $request->fileName->move(public_path('admin/signFiles/'), $fileName);
            $signFile->fileName = $fileName;
        } 
        $signFile->updated_by=Auth::user()->username;
        $signFile->save();
        
        return redirect('/signFiles')->with('success', 'Signature Updated successfully!!!');
    }  

    public function activate($id)
    {
        SignatureFile::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        return redirect('/signFiles')->with('success', 'Signature Activated successfully!!!');
    }

    public function deactivate($id)
    {
        SignatureFile::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        return redirect('/signFiles/dlist')->with('success', 'Signature Deactivated successfully!!!');
    }
}