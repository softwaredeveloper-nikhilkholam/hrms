<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StoreScrapCategory;
use Auth;
use DB;

class ScrapCategoriesController extends Controller
{
    public function index()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $categories = StoreScrapCategory::whereActive(1)->get();
        $categoriesCt = StoreScrapCategory::whereActive(1)->count();
        $dCategoriesCt = StoreScrapCategory::whereActive(0)->count();
        return view('storeAdmin.scrapManagement.category.list')->with(['categories'=>$categories,'categoriesCt'=>$categoriesCt,'dCategoriesCt'=>$dCategoriesCt]);
    }

    public function dlist()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $categories = StoreScrapCategory::whereActive(0)->get();
        $categoriesCt = StoreScrapCategory::whereActive(1)->count();
        $dCategoriesCt = StoreScrapCategory::whereActive(0)->count();
        return view('storeAdmin.scrapManagement.category.dlist')->with(['categories'=>$categories,'categoriesCt'=>$categoriesCt,'dCategoriesCt'=>$dCategoriesCt]);
    }

    public function create()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $categoriesCt = StoreScrapCategory::whereActive(1)->count();
        $dCategoriesCt = StoreScrapCategory::whereActive(0)->count();
        return view('storeAdmin.scrapManagement.category.create')->with(['dCategoriesCt'=>$dCategoriesCt,'categoriesCt'=>$categoriesCt]);
    }
    
    public function store(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        if(StoreScrapCategory::where('name', $request->name)->count())
        {
            return redirect()->back()->withInput()->with("error","Scrap Category Name is already exist..");
        }

        $category = new StoreScrapCategory;
        $category->name = $request->name;
        $category->description = $request->description;
        $category->updated_by=Auth::user()->username;
        $category->save();
        return redirect('/scrapCategory')->with("success","Scrap Category Store successfully..");
    }

    public function edit($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $category = StoreScrapCategory::whereId($id)->first();
        $categoriesCt = StoreScrapCategory::whereActive(1)->count();
        $dCategoriesCt = StoreScrapCategory::whereActive(0)->count();
        return view('storeAdmin.scrapManagement.category.edit')->with(['categoriesCt'=>$categoriesCt,'dCategoriesCt'=>$dCategoriesCt,'category'=>$category]);
    }

    public function update(Request $request, $id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        if(StoreScrapCategory::where('name', $request->name)->where('id', '<>', $id)->count())
        {
            return redirect()->back()->withInput()->with("error","Scrap Category Name is already exist..");
        }

        $category = StoreScrapCategory::find($id);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->updated_by=Auth::user()->username;
        $category->save();
        return redirect('/scrapCategory')->with("success","Scrap Category Updated successfully..");
    }

    public function activate($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $category = StoreScrapCategory::find($id);
        $category->active = 1;
        $category->updated_by=Auth::user()->username;
        $category->save();
        return redirect('/scrapCategory')->with("success","Scrap Category Activate Successfully.");
    }

    public function deactivate($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $category = StoreScrapCategory::find($id);
        $category->active = 0;
        $category->updated_by=Auth::user()->username;
        $category->save();
        return redirect('/scrapCategory/dlist')->with("success","Scrap Category Deactivated Successfully.");
    }
}

