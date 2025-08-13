<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\stores\SubCategoryExport;
use App\StoreCategory;
use App\StoreSubCategory;
use Auth;
use DB;

class SubCategoriesController extends Controller
{
    public function index()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $subCategoryCt = StoreSubCategory::whereActive(1)->count();
        $dSubCategoryCt = StoreSubCategory::whereActive(0)->count();
        $subCategory = StoreSubCategory::join('store_categories', 'store_sub_categories.categoryId', 'store_categories.id')
        ->select('store_sub_categories.*', 'store_categories.name as categoryName')
        ->where('store_sub_categories.active', 1)
        ->orderBy('store_sub_categories.name')
        ->get();

        return view('storeAdmin.masters.subCategory.list')->with(['subCategoryCt'=>$subCategoryCt,'dSubCategoryCt'=>$dSubCategoryCt,'subCategory'=>$subCategory]);
    }

    public function dlist()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $subCategoryCt = StoreSubCategory::whereActive(1)->count();
        $dSubCategoryCt = StoreSubCategory::whereActive(0)->count();
        $dSubCategory = StoreSubCategory::join('store_categories', 'store_sub_categories.categoryId', 'store_categories.id')
        ->select('store_sub_categories.*', 'store_categories.name as categoryName')
        ->where('store_sub_categories.active', 0)
        ->orderBy('store_sub_categories.name')
        ->get();

        return view('storeAdmin.masters.subCategory.dlist')->with(['subCategoryCt'=>$subCategoryCt,'dSubCategoryCt'=>$dSubCategoryCt,'dSubCategory'=>$dSubCategory]);
    }

    public function create()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $subCategoryCt = StoreSubCategory::whereActive(1)->count();
        $dSubCategoryCt = StoreSubCategory::whereActive(0)->count();
        $categories = StoreCategory::whereActive(1)->orderBy('name')->pluck('name', 'id');
        return view('storeAdmin.masters.subCategory.create')->with(['subCategoryCt'=>$subCategoryCt,'dSubCategoryCt'=>$dSubCategoryCt,'categories'=>$categories]);
    }

    
    public function store(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        if(StoreSubCategory::where('name', $request->name)->where('categoryId', $request->categoryId)->first())
        {
            return redirect()->back()->withInput()->with("error","Sub Category Name is already exist..");
        }

        $subCategory = new StoreSubCategory;
        $subCategory->categoryId = $request->categoryId;
        $subCategory->name = $request->name;
        $subCategory->updated_by=Auth::user()->username;
        $subCategory->save();

        return redirect('/subCategory')->with("success","Sub Category Store successfully..");
    }

   
    public function show($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $subCategoryCt = StoreSubCategory::whereActive(1)->count();
        $dsubCategoryCt = StoreSubCategory::whereActive(0)->count();
        $subCategory = StoreSubCategory::join('store_categories', 'store_sub_categories.categoryId', 'store_categories.id')
        ->select('store_sub_categories.*', 'store_categories.name as categoryName')
        ->where('store_sub_categories.id', $id)
        ->first();
        return view('storeAdmin.masters.subCategory.show')->with(['subCategoryCt'=>$subCategoryCt,'dsubCategoryCt'=>$dsubCategoryCt,'subCategory'=>$subCategory]);
    }

   
    public function edit($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $subCategoryCt = StoreSubCategory::whereActive(1)->count();
        $dSubCategoryCt = StoreSubCategory::whereActive(0)->count();
        $subCategory = StoreSubCategory::find($id);
        $categories = StoreCategory::whereActive(1)->pluck('name', 'id');
        return view('storeAdmin.masters.subCategory.edit')->with(['subCategoryCt'=>$subCategoryCt,'dSubCategoryCt'=>$dSubCategoryCt,'subCategory'=>$subCategory, 'categories'=>$categories]);
    }

    
    public function update(Request $request, $id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        if(StoreSubCategory::where('id', '<>',$id)->where('name', $request->name)->where('categoryId', $request->categoryId)->count())
        {
            return redirect()->back()->withInput()->with("error","Sub Category Name is already exist..");
        }

        $subCategory = StoreSubCategory::find($id);
        $subCategory->categoryId = $request->categoryId;
        $subCategory->name = $request->name;
        $subCategory->updated_by=Auth::user()->username;
        $subCategory->save();
        return redirect('/subCategory')->with("success","Sub Category Updated successfully..");
    }

    public function activate($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $subCategory = StoreSubCategory::find($id);
        $subCategory->active = 1;
        $subCategory->updated_by=Auth::user()->username;
        $subCategory->save();
        return redirect('/subCategory')->with("success","Sub Category Activated successfully..");
    }

    public function deactivate($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $subCategory = StoreSubCategory::find($id);
        $subCategory->active = 0;
        $subCategory->updated_by=Auth::user()->username;
        $subCategory->save();
        return redirect('/subCategory/dlist')->with("success","Sub Category Deactivated successfully..");
    }

    public function subCategoryList($category)
    {
        return StoreSubCategory::join('store_categories', 'store_sub_categories.categoryId', 'store_categories.id')
        ->select("store_sub_categories.name")
        ->where('store_categories.name', $category)
        ->where('store_categories.active', 1)
        ->where('store_sub_categories.active', 1)
        ->orderBy('store_sub_categories.name')
        ->pluck('store_sub_categories.name');
    }
}
