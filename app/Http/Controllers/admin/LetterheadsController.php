<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\LetterHead;
use Auth;
use File;

class LetterHeadsController extends Controller
{    
    public function index()
    {
        $letterHeads = LetterHead::whereActive(1)->get();
        return view('admin.masters.letterHeads.list')->with(['letterHeads'=>$letterHeads]);
    }

    public function dlist()
    {
        $letterHeads = LetterHead::whereActive(0)->get();
        return view('admin.masters.letterHeads.dlist')->with(['letterHeads'=>$letterHeads]);
    }
   
    public function create()
    {
        return view('admin.masters.letterHeads.create');        
    }
  
    public function store(Request $request)
    {
        if(LetterHead::where('name', $request->name)->count())
            return redirect()->back()->withInput()->with("error","Letter Head Name already exist. Please TRY another one!!!");;

        $letterHead = new LetterHead();    
        $letterHead->name = $request->name;  
        if(!empty($request->file('fileName')))
        {
            $fileName = date('Ymdhis').".".$request->fileName->extension();  
            $request->fileName->move(public_path('admin/letterHeads/'), $fileName);
            $letterHead->fileName = $fileName;
        }

        $letterHead->updated_by=Auth::user()->username;
        $letterHead->save();

        return redirect('/letterHeads')->with('success', 'New Letter Head File uploaded successfully!!!');
    }
   
    public function edit($id)
    {
        $letterHead = LetterHead::find($id);
        return view('admin.masters.letterHeads.edit')->with(['letterHead'=>$letterHead]);                      
    }
   
    public function update(Request $request, $id)
    {
        if($request->name && LetterHead::Where('name', $request->name)->where('id','<>', $id)->count())
            return redirect()->back()->withInput()->with("error","Letter Head File Name already exist. Please TRY another one!!!");;

        $letterHead = LetterHead::find($id);
        $letterHead->name = $request->name;    
        if(!empty($request->file('fileName')))
        {
            if($letterHead->fileName != '')
            {
                $oldImage = public_path('admin/letterHeads/').$letterHead->name;

                if (File::exists($oldImage))  // unlink or remove previous image from folder
                {
                    unlink($oldImage);
                }
            }

            $fileName = date('Ymdhis').".".$request->fileName->extension();  
            $request->fileName->move(public_path('admin/letterHeads/'), $fileName);
            $letterHead->fileName = $fileName;
        } 
        $letterHead->updated_by=Auth::user()->username;
        $letterHead->save();
        
        return redirect('/letterHeads')->with('success', 'Signature Updated successfully!!!');
    }  

    public function activate($id)
    {
        LetterHead::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        return redirect('/letterHeads')->with('success', 'Signature Activated successfully!!!');
    }

    public function deactivate($id)
    {
        LetterHead::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        return redirect('/letterHeads/dlist')->with('success', 'Signature Deactivated successfully!!!');
    }
}