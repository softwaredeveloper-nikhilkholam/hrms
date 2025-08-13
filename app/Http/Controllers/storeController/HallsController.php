<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StoreHall;
use Auth;
use DB;

class HallsController extends Controller
{
   
    public function index()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $halls = StoreHall::whereActive(1)->get();
        $dHalls = StoreHall::whereActive(0)->count();
        return view('storeAdmin.masters.hall.list')->with(['halls'=>$halls,'dHalls'=>$dHalls]);
    }

    public function dlist()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $dHalls = StoreHall::whereActive(0)->get();
        $halls = StoreHall::whereActive(1)->count();
        return view('storeAdmin.masters.hall.dlist')->with(['halls'=>$halls,'dHalls'=>$dHalls]);
    }

    public function create()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $halls = StoreHall::whereActive(1)->count();
        $dHalls = StoreHall::whereActive(0)->count();
        return view('storeAdmin.masters.hall.create')->with(['halls'=>$halls,'dHalls'=>$dHalls]);
    }

    public function store(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        if(StoreHall::where('name', $request->name)->count())
        {
            return redirect()->back()->withInput()->with("error","Hall Name is already exist..");
        }

        $hall = new StoreHall;
        $hall->name = $request->name;
        $hall->description = $request->description;
        $hall->updated_by=Auth::user()->username;
        $hall->save();
        return redirect('/hall')->with("success","Hall Store successfully..");
    }

    public function edit($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $hall = StoreHall::find($id);
        $halls = StoreHall::whereActive(1)->count();
        $dHalls = StoreHall::whereActive(0)->count();
        return view('storeAdmin.masters.hall.edit')->with(['hall'=>$hall,'halls'=>$halls,'dHalls'=>$dHalls]);

    }

    public function update(Request $request, $id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        if(StoreHall::where('name', $request->name)->where('id', '<>', $id)->count())
        {
            return redirect()->back()->withInput()->with("error","Hall Name is already exist..");
        }

        $hall = StoreHall::find($id);
        $hall->name = $request->name;
        $hall->description = $request->description;
        $hall->updated_by=Auth::user()->username;
        $hall->save();
        return redirect('/hall')->with("success","Hall Updated successfully..");
    }

    public function activate($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $hall = StoreHall::find($id);
        $hall->active = 1;
        $hall->updated_by=Auth::user()->username;
        $hall->save();
        return redirect('/hall')->with("success","Hall Activate Successfully.");
    }

    public function deactivate($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $hall = StoreHall::find($id);
        $hall->active = 0;
        $hall->updated_by=Auth::user()->username;
        $hall->save();
        return redirect('/hall/dlist')->with("success","Hall Deactivated Successfully.");
    }
   
}
