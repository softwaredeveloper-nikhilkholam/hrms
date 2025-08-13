<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\ContactusLandPage;
use App\Department;
use App\Designation;
use Auth;


class EventsController extends Controller
{
    public function index()
    {
        $events = Event::where('active', 1)->paginate(10);
        return view('admin.events.list')->with(['events'=>$events]);
    }

    public function dlist()
    {
        $events = Event::where('active', 0)->paginate(10);
        return view('admin.events.dlist')->with(['events'=>$events]);
    }

    public function create()
    {
        $branches = ContactusLandPage::where('active', 1)->pluck('branchName', 'id');
        $departments = Department::where('active', 1)->pluck('name', 'id');
        $designations = Designation::where('active', 1)->pluck('name', 'id');
        return view('admin.events.create')->with(['branches'=>$branches,'departments'=>$departments,'designations'=>$designations]);
    }

    public function store(Request $request)
    {
        if(Event::where('forDate', $request->forDate)->where('title', $request->title)->first())
            return redirect()->back()->withInput()->with("error","This Event already Updated in the System....");

        $event = new Event();
        $event->forDate=$request->forDate;
        $event->title=$request->title;
        $event->description=$request->description;
        $event->branchId=$request->branchId;
        $event->section=$request->section;
        $event->designationId=$request->designationId;
        $event->updated_by=Auth::user()->username;
        $event->save();

        return redirect('/events')->with("success","Event Store Successfully..");
    }

    public function edit($id)
    {
        $branches = ContactusLandPage::where('active', 1)->pluck('branchName', 'id');
        $departments = Department::where('active', 1)->pluck('name', 'id');
        $designations = Designation::where('active', 1)->pluck('name', 'id');
        $event = Event::find($id);
        return view('admin.events.edit')->with(['event'=>$event, 'branches'=>$branches, 'departments'=>$departments, 'designations'=>$designations ]);
    }

    public function update(Request $request, $id)
    {        
        $event = Event::find($id);
        $event->forDate=$request->forDate;
        $event->title=$request->title;
        $event->description=$request->description;
        $event->branchId=$request->branchId;
        $event->section=$request->section;
        $event->designationId=$request->designationId;
        $event->updated_by=Auth::user()->username;
        $event->save();

        return redirect('/events')->with("success","Event Updated successfully..");
    }

    public function show($id)
    {
        $branches = ContactusLandPage::where('active', 1)->pluck('branchName', 'id');
        $departments = Department::where('active', 1)->pluck('name', 'id');
        $designations = Designation::where('active', 1)->pluck('name', 'id');
        $event = Event::find($id);
        return view('admin.events.show')->with(['event'=>$event, 'branches'=>$branches, 'departments'=>$departments, 'designations'=>$designations ]);
    }

    public function activate(Request $request)
    {
        Event::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        return redirect('events')->with("success","Event Activated successfully..");
    }

    public function deactivate(Request $request)
    {
        Event::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        return redirect('events')->with("success","Event Deactivated successfully..");  
    }
}
