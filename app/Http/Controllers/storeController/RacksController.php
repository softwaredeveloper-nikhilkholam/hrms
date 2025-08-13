<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StoreHall;
use App\StoreRack;
use App\StoreProduct;
use App\StoreShel;
use Auth;
use DB;

class RacksController extends Controller
{
    public function index()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $racks = StoreRack::join('store_halls', 'store_racks.hallId', 'store_halls.id')
        ->select('store_halls.name as hallName', 'store_racks.*')
        ->where('store_racks.active',1)
        ->orderBy('store_racks.name')
        ->get();
        $dRacks = StoreRack::whereActive(0)->count();
        return view('storeAdmin.masters.racks.list')->with(['racks'=>$racks,'dRacks'=>$dRacks]);
    }

    public function dlist()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $dRacks = StoreRack::join('store_halls', 'store_racks.hallId', 'store_halls.id')
        ->select('store_halls.name as hallName', 'store_racks.*')
        ->where('store_racks.active',0)
        ->orderBy('store_racks.name')
        ->get();
        $racks = StoreRack::whereActive(1)->count();
        return view('storeAdmin.masters.racks.dlist')->with(['racks'=>$racks,'dRacks'=>$dRacks]);
    }

    public function create()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $halls = StoreHall::where('active', 1)->pluck('name', 'id');
        $racks = StoreRack::whereActive(1)->count();
        $dRacks = StoreRack::whereActive(0)->count();
        return view('storeAdmin.masters.racks.create')->with(['halls'=>$halls,'racks'=>$racks,'dRacks'=>$dRacks]);
    }

    public function store(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        if(StoreRack::where('name', $request->name)->where('hallId', $request->hallId)->count())
        {
            return redirect()->back()->withInput()->with("error","Rack Name is already exist..");
        }

        $rack = new StoreRack;
        $rack->hallId = $request->hallId;
        $rack->name = $request->name;
        $rack->updated_by=Auth::user()->username;
        $rack->save();
        return redirect('/rack')->with("success","Rack Added successfully with Shelf..");
    }

    public function edit($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $rack = StoreRack::find($id);
        $halls = StoreHall::where('active', 1)->pluck('name', 'id');
        $racks = StoreRack::whereActive(1)->count();
        $dRacks = StoreRack::whereActive(0)->count();
        return view('storeAdmin.masters.racks.edit')->with(['halls'=>$halls,'rack'=>$rack,'racks'=>$racks,'dRacks'=>$dRacks]);
    }

    public function update(Request $request, $id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        if(StoreRack::where('name', $request->name)->where('hallId', $request->hallId)->where('id', '<>', $id)->count())
        {
            return redirect()->back()->withInput()->with("error","Rack Name is already exist..");
        }

        $rack = StoreRack::find($id);
        $rack->hallId = $request->hallId;
        $rack->name = $request->name;
        $rack->updated_by=Auth::user()->username;
        $rack->save();
        return redirect('/rack')->with("success","Rack Updated successfully with Shelf");
    }

    public function activate($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $rack = StoreRack::find($id);
        $rack->active = 1;
        $rack->updated_by=Auth::user()->username;
        $rack->save();
        return redirect('/rack')->with("success","Rack Activate Successfully.");
    }

    public function deactivate($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $rack = StoreRack::find($id);
        $rack->active = 0;
        $rack->updated_by=Auth::user()->username;
        $rack->save();
        return redirect('/rack/dlist')->with("success","Rack Deactivated Successfully.");
    }

    public function printPDF($rackId, $active)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $products  = StoreProduct::join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->join('store_halls', 'store_products.hallId', 'store_halls.id')
        ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
        ->select('store_categories.name as category', 'store_sub_categories.name as subCategory', 'store_halls.name as hallName', 
        'store_shels.name as shelfName','store_products.*')
        ->where('store_products.active', $active)
        ->where('store_products.rackId', $rackId)
        ->get();

        $rackName = StoreRack::where('id', $rackId)->value('name');

        $file = $rackName.'.pdf';
        $pdf = PDF::loadView('storeAdmin.masters.racks.printProducts',compact('products', 'active'));
        return $pdf->stream($file);  
    }
}