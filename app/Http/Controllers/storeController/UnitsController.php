<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StoreUnit;
use Auth;
use DB;

class UnitsController extends Controller
{
    public function index()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $units = StoreUnit::whereActive(1)->get();
        $dUnits = StoreUnit::whereActive(0)->count();
        return view('storeAdmin.masters.unit.list')->with(['units'=>$units,'dUnits'=>$dUnits]);
    }

    public function dlist()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $dUnits = StoreUnit::whereActive(0)->get();
        $units = StoreUnit::whereActive(1)->count();
        return view('storeAdmin.masters.unit.dlist')->with(['units'=>$units,'dUnits'=>$dUnits]);
    }

    public function create()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $units = StoreUnit::whereActive(1)->count();
        $dUnits = StoreUnit::whereActive(0)->count();
        return view('storeAdmin.masters.unit.create')->with(['units'=>$units,'dUnits'=>$dUnits]);
    }

    public function store(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        if(StoreUnit::where('name', $request->name)->count())
        {
            return redirect()->back()->withInput()->with("error","Unit Name is already exist..");
        }

        $unit = new StoreUnit;
        $unit->name = $request->name;
        $unit->updated_by=Auth::user()->username;
        $unit->save();
        return redirect('/unit')->with("success","Unit Store successfully..");
    }

    public function edit($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $unit = StoreUnit::find($id);
        $units = StoreUnit::whereActive(1)->count();
        $dUnit = StoreUnit::whereActive(0)->count();
        return view('storeAdmin.masters.unit.edit')->with(['unit'=>$unit,'units'=>$units,'dUnits'=>$dUnits]);
    }

    public function update(Request $request, $id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        if(StoreUnit::where('name', $request->name)->where('id', '<>', $id)->count())
        {
            return redirect()->back()->withInput()->with("error","Unit Name is already exist..");
        }

        $unit = StoreUnit::find($id);
        $unit->name = $request->name;
        $unit->updated_by=Auth::user()->username;
        $unit->save();
        return redirect('/unit')->with("success","Unit Updated successfully..");
    }

    public function activate($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $unit = StoreUnit::find($id);
        $unit->active = 1;
        $unit->updated_by=Auth::user()->username;
        $unit->save();
        return redirect('/unit')->with("success","Unit Activate Successfully.");
    }

    public function deactivate($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $unit = StoreUnit::find($id);
        $unit->active = 0;
        $unit->updated_by=Auth::user()->username;
        $unit->save();
        return redirect('/unit/dlist')->with("success","Unit Deactivated Successfully.");
    }
}
    