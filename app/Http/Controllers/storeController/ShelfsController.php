<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StoreRack;
use App\StoreShel;
use App\StoreHall;
use Auth;
use DB;

class ShelfsController extends Controller
{
    public function index()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $shelfs = StoreShel::join('store_halls', 'store_shels.hallId', 'store_halls.id')
        ->join('store_racks', 'store_shels.rackId', 'store_racks.id')
        ->select('store_halls.name as hallName', 'store_racks.name as rackName', 'store_shels.*')
        ->where('store_shels.active',1)
        ->orderBy('store_shels.name')
        ->get();
        $dShelfs = StoreShel::whereActive(0)->count();
        return view('storeAdmin.masters.shelfs.list')->with(['shelfs'=>$shelfs,'dShelfs'=>$dShelfs]);
    }

    public function dlist()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $dShelfs = StoreShel::join('store_halls', 'store_shels.hallId', 'store_halls.id')
        ->join('store_racks', 'store_shels.rackId', 'store_racks.id')
        ->select('store_halls.name as hallName', 'store_racks.name as rackName', 'store_shels.*')
        ->where('store_shels.active',0)
        ->orderBy('store_shels.name')
        ->get();
        $shelfs = StoreShel::whereActive(1)->count();
        return view('storeAdmin.masters.shelfs.dlist')->with(['shelfs'=>$shelfs,'dShelfs'=>$dShelfs]);
    }

    public function create()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $halls = StoreHall::where('active', 1)->pluck('name', 'id');
        $shelfs = StoreShel::whereActive(1)->count();
        $dShelfs = StoreShel::whereActive(0)->count();
        return view('storeAdmin.masters.shelfs.create')->with(['halls'=>$halls,'shelfs'=>$shelfs,'dShelfs'=>$dShelfs]);
    }

    public function store(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        if(StoreShel::where('name', $request->name)->where('hallId', $request->hallId)->where('rackId', $request->rackId)->count())
        {
            return redirect()->back()->withInput()->with("error","Shelf Name is already exist..");
        }

        $shelf = new StoreShel;
        $shelf->hallId = $request->hallId;
        $shelf->rackId = $request->rackId;
        $shelf->name = $request->name;
        $shelf->updated_by=Auth::user()->username;
        $shelf->save();
        return redirect('/shelf')->with("success","Shelf Store successfully..");
    }

    public function edit($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $shelf = StoreShel::find($id);
        $halls = StoreHall::where('active', 1)->pluck('name', 'id');
        $racks = StoreRack::where('active', 1)->pluck('name', 'id');
        $shelfs = StoreShel::whereActive(1)->count();
        $dShelfs = StoreShel::whereActive(0)->count();
        return view('storeAdmin.masters.shelfs.edit')->with(['racks'=>$racks,'halls'=>$halls,'shelf'=>$shelf,'shelfs'=>$shelfs,'dShelfs'=>$dShelfs]);
    }

    public function update(Request $request, $id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        if(StoreShel::where('name', $request->name)->where('hallId', $request->hallId)->where('rackId', $request->rackId)->where('id', '<>', $id)->count())
        {
            return redirect()->back()->withInput()->with("error","Shelf Name is already exist..");
        }

        $shelf = StoreShel::find($id);
        $shelf->hallId = $request->hallId;
        $shelf->rackId = $request->rackId;
        $shelf->name = $request->name;
        $shelf->updated_by=Auth::user()->username;
        $shelf->save();
        return redirect('/shelf')->with("success","Shelf Updated successfully..");
    }

    public function activate($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $shelf = StoreShel::find($id);
        $shelf->active = 1;
        $shelf->updated_by=Auth::user()->username;
        $shelf->save();
        return redirect('/shelf')->with("success","Shelf Activate Successfully.");
    }

    public function deactivate($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $shelf = StoreShel::find($id);
        $shelf->active = 0;
        $shelf->updated_by=Auth::user()->username;
        $shelf->save();
        return redirect('/shelf/dlist')->with("success","Shelf Deactivated Successfully.");
    }

    public function getRacks($hallId)
    {
        return StoreRack::where('hallId', $hallId)->where('active', 1)->get(['id', 'name']);
    }
}
