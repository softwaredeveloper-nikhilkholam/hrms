<?php

namespace App\Http\Controllers\admin\masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Utility;
use App\Branch;
use App\Country;
use App\Region;
use App\City;
use Auth;

use App\Department;

class BranchesController extends Controller
{    
    public function index()
    {
        $branches = Branch::whereActive(1)->get();
        return view('admin.masters.branches.list')->with(['branches'=>$branches]);
    }

    public function dlist()
    {
        $branches = Branch::whereActive(0)->get();
        return view('admin.masters.branches.dlist')->with(['branches'=>$branches]);
    }
   
    public function create()
    {
        $countries = Country::whereActive(1)->pluck('name', 'id');
        $regions = Region::whereActive(1)->pluck('name', 'id');
        return view('admin.masters.branches.create')->with(['countries'=>$countries,'regions'=>$regions]);        
    }
  
    public function store(Request $request)
    {
        return $request->all();
        if(Branch::where('name', $request->name)->count())
            return redirect()->back()->withInput()->with("error","Branch Name already exist. Please TRY another one!!!");;

        $branch = new Branch();
        $branch->countryId = $request->countryId;
        $branch->regionId = $request->regionId;
        $branch->cityId = $request->cityId;
        $branch->name = $request->name;
        $branch->email = $request->email;
        $branch->address = $request->address;
        $branch->PINCode = $request->PINCode;
        $branch->contactNo1 = $request->contactNo1;
        $branch->contactNo2 = $request->contactNo2;
        $branch->updated_by=Auth::user()->username;
        $branch->save();

        return redirect('/branches')->with('success', 'New Branch added successfully!!!');

    }
   
    public function show($id)
    {
        $branch = Branch::join('countries', 'branches.countryId', 'countries.id')
        ->join('regions', 'branches.regionId', 'regions.id')
        ->join('cities', 'branches.cityId', 'cities.id')
        ->select('countries.name as countryName', 'regions.name as regionName', 'cities.name as cityName','branches.*')
        ->first();
        return view('admin.masters.branches.show')->with(['branch'=>$branch]);    
    }
  
    public function edit($id)
    {
        $branch = Branch::find($id);
        $countries = Country::whereActive(1)->pluck('name', 'id');
        $regions = Region::whereActive(1)->pluck('name', 'id');
        $cities = City::where('regionId', $branch->regionId)->whereActive(1)->pluck('name', 'id');
        return view('admin.masters.branches.edit')->with(['branch'=>$branch,'countries'=>$countries,'regions'=>$regions,'cities'=>$cities]);                      
    }
   
    public function update(Request $request, $id)
    {
        if($request->name && Branch::Where('name', $request->name)->where('id','<>', $id)->count())
            return redirect()->back()->withInput()->with("error","Branch Name already exist. Please TRY another one!!!");;

        $branch = Branch::find($id);
        $branch->countryId = $request->countryId;
        $branch->regionId = $request->regionId;
        $branch->cityId = $request->cityId;
        $branch->name = $request->name;
        $branch->email = $request->email;
        $branch->address = $request->address;
        $branch->PINCode = $request->PINCode;
        $branch->contactNo1 = $request->contactNo1;
        $branch->contactNo2 = $request->contactNo2;
        $branch->updated_by=Auth::user()->username;
        $branch->save();

        if($branch->active == 1)
            return redirect('/branches')->with('success', 'Branch Updated successfully!!!');
        else
            return redirect('/branches/dlist')->with('success', 'Branch Updated successfully!!!');
    }  

    public function activate($id)
    {
        Branch::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        return redirect('/branches')->with('success', 'Branch Activated successfully!!!');
    }

    public function deactivate($id)
    {
        Branch::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        return redirect('/branches/dlist')->with('success', 'Branch Deactivated successfully!!!');
    }
}
