<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Utility;
use App\Notice;
use App\ContactusLandPage;
use App\Department;
use App\Designation;
use App\NoticeDetail;
use App\EmpDet;
use DB;
use Auth;

class NoticesController extends Controller
{
    public function index()
    {
        $notices = Notice::where('status', 1)->where('toDate', '>=', date('Y-m-d'))->orderBy('fromDate')->get();
        return view('admin.notices.list')->with(['notices'=>$notices]);
    }

    public function dlist()
    {
         $notices = Notice::where('status', 1)->where('toDate', '<', date('Y-m-d'))->orderBy('fromDate')->get();
        return view('admin.notices.dlist')->with(['notices'=>$notices]);
    }

    public function deletedList()
    {
        $notices = Notice::where('status', 0)->get();
        return view('admin.notices.deletedList')->with(['notices'=>$notices]);
    }

    public function create()
    {
        $branches = ContactusLandPage::whereActive(1)->orderby('branchName')->pluck('branchName', 'id');
        $departments = Department::whereActive(1)->orderby('name')->pluck('name', 'id');
        return view('admin.notices.create')->with(['branches'=>$branches,'departments'=>$departments]);
    }

    public function store(Request $request)
    {
        try 
        {
            $notice = new Notice();
            $notice->branchId=$request->branchId;
            $notice->departmentId=implode(',', $request->departmentId);
            $notice->fromDate=$request->fromDate;
            $notice->toDate=$request->toDate;
            $notice->title=$request->title;
            $notice->description=$request->description;
            $notice->updated_by=Auth::user()->username;
            $notice->added_by=Auth::user()->username;
            if($notice->save())
            {
                $empIds = EmpDet::where('active',1);
                if($request->branchId != '')
                    $empIds=$empIds->where('branchId', $request->branchId);

                if(!empty($request->departmentId))
                    $empIds=$empIds->where('departmentId', $request->departmentId);

                $empIds=$empIds->pluck('id');

                if(count($empIds))
                {
                    for($i=0;$i<count($empIds); $i++)
                    {
                        $noticeDet = new NoticeDetail;
                        $noticeDet->noticeId = $notice->id;
                        $noticeDet->empId = $empIds[$i];
                        $noticeDet->updated_by=Auth::user()->username;
                        $noticeDet->save();
                    }
                }
            }

            return redirect('/notices')->with("success","Notice Store successfully..");
        } catch(\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return redirect()->back()->withInput()->with("error","Duplicate Entry");
            }
        }
    }

    public function show()
    {
        return view('admin.notices.show');
    }

    public function edit($id)
    {
        $branches = ContactusLandPage::whereActive(1)->orderby('branchName')->pluck('branchName', 'id');
        $departments = Department::whereActive(1)->orderby('name')->get(['name', 'id']);
        $notice = Notice::find($id);
        return view('admin.notices.edit')->with(['notice'=>$notice,'branches'=>$branches,'departments'=>$departments]);
    }

    public function update(Request $request, $id)
    {
        try 
        {
            $notice = Notice::find($id);
            $notice->branchId=$request->branchId;
            $notice->departmentId=implode(',', $request->departmentId);
            $notice->fromDate=$request->fromDate;
            $notice->toDate=$request->toDate;
            $notice->title=$request->title;
            $notice->description=$request->description;
            $notice->updated_by=Auth::user()->username;
            $notice->added_by=Auth::user()->username;
            if($notice->save())
            {
                $empIds = EmpDet::where('active',1);
                if($request->branchId != '')
                    $empIds=$empIds->where('branchId', $request->branchId);

                if(!empty($request->departmentId))
                    $empIds=$empIds->where('departmentId', $request->departmentId);

                $empIds=$empIds->pluck('id');

                if(count($empIds))
                {
                    NoticeDetail::where('noticeId', $notice->id)->update(['status'=>0, 'updated_by'=>Auth::user()->username]);
                    for($i=0;$i<count($empIds); $i++)
                    {
                        $noticeDet = NoticeDetail::where('empId', $empIds[$i])->where('noticeId', $notice->id)->first();
                        if(!$noticeDet)
                            $noticeDet = new NoticeDetail;

                        $noticeDet->noticeId = $notice->id;
                        $noticeDet->empId = $empIds[$i];
                        $noticeDet->status = 1;
                        $noticeDet->updated_by=Auth::user()->username;
                        $noticeDet->save();
                    }
                }
            }

            return redirect('/notices')->with("success","Notice Store successfully..");
        } catch(\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return redirect()->back()->withInput()->with("error","Duplicate Entry");
            }
        }

        // try 
        // {
        //     $notice = Notice::find($id);
        //     $notice->branchId=$request->branchId;
        //     $notice->departmentId=implode(',', $request->departmentId);
        //     $notice->fromDate=$request->fromDate;
        //     $notice->toDate=$request->toDate;
        //     $notice->title=$request->title;
        //     $notice->description=$request->description;
        //     $notice->updated_by=Auth::user()->username;
        //     $notice->added_by=Auth::user()->username;
        //     $notice->save();

        //     return redirect('/notices')->with("success","Notice Updated successfully..");
        // } catch(\Illuminate\Database\QueryException $e){
        //     $errorCode = $e->errorInfo[1];
        //     if($errorCode == '1062'){
        //         return redirect()->back()->withInput()->with("error","Duplicate Entry");
        //     }
        // }
    }

    public function activate($id)
    {
        $branches = ContactusLandPage::whereActive(1)->orderby('branchName')->pluck('branchName', 'id');
        $departments = Department::whereActive(1)->orderby('name')->get(['name', 'id']);
        $notice = Notice::find($id);
        return view('admin.notices.edit')->with(['notice'=>$notice,'branches'=>$branches,'departments'=>$departments]);
    }
    
}