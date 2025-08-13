<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\stores\CategoryExport;
use App\StoreCategory;
use App\StoreSubCategory;
use App\BranchStock;
use Auth;
use DB;
use Image;
use Excel;

class CategoriesController extends Controller
{
    public function index()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $categories = StoreCategory::whereActive(1)->orderBy('name')->get();
        $categoriesCt = StoreCategory::whereActive(1)->count();
        $dCategoriesCt = StoreCategory::whereActive(0)->count();
        return view('storeAdmin.masters.category.list')->with(['categories'=>$categories,'categoriesCt'=>$categoriesCt,'dCategoriesCt'=>$dCategoriesCt]);
    }

    public function dlist()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $categories = StoreCategory::whereActive(0)->orderBy('name')->get();
        $categoriesCt = StoreCategory::whereActive(1)->count();
        $dCategoriesCt = StoreCategory::whereActive(0)->count();
        return view('storeAdmin.masters.category.dlist')->with(['categories'=>$categories,'categoriesCt'=>$categoriesCt,'dCategoriesCt'=>$dCategoriesCt]);
    }

    public function create()
    {
        $categoriesCt = StoreCategory::whereActive(1)->count();
        $dCategoriesCt = StoreCategory::whereActive(0)->count();
        return view('storeAdmin.masters.category.create')->with(['dCategoriesCt'=>$dCategoriesCt,'categoriesCt'=>$categoriesCt]);
    }
    
    public function store(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        if(StoreCategory::where('name', $request->name)->count())
        {
            return redirect()->back()->withInput()->with("error","Product Category Name is already exist..");
        }

        $category = new StoreCategory;
        $category->name = $request->name;
        $category->description = $request->description;
        if(!empty($request->file('image')))
        {
            $originalImage= $request->file('image');
            $Image = $category->name.'_'.date('his').'.'.$originalImage->getClientOriginalExtension();
            $image = Image::make($originalImage);
            $originalPath =  public_path()."/storeAdmin/images/categories/";
            $image->save($originalPath.$Image);
            $category->image = $Image;
        }
        $category->updated_by=Auth::user()->username;
        $category->save();
        return redirect('/category')->with("success","Product Category Store successfully..");
    }

    public function edit($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $category = StoreCategory::whereId($id)->first();
        $categoriesCt = StoreCategory::whereActive(1)->count();
        $dCategoriesCt = StoreCategory::whereActive(0)->count();
        return view('storeAdmin.masters.category.edit')->with(['categoriesCt'=>$categoriesCt,'dCategoriesCt'=>$dCategoriesCt,'category'=>$category]);
    }

    public function update(Request $request, $id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        if(StoreCategory::where('name', $request->name)->where('id', '<>', $id)->count())
        {
            return redirect()->back()->withInput()->with("error","Product Category Name is already exist..");
        }

        $category = StoreCategory::find($id);
        $category->name = $request->name;
        $category->description = $request->description;
        if(!empty($request->file('image')))
        {
            $oldImage = base_path('public/storeAdmin/images/categories/').$category->photo;
            if($category->photo != '')
            {
                if (File::exists($oldImage))  // unlink or remove previous image from folder
                {
                    unlink($oldImage);
                }
            }

            $originalImage= $request->file('image');
            $Image = $category->name.'_'.date('his').'.'.$originalImage->getClientOriginalExtension();
            $image = Image::make($originalImage);
            $originalPath =  public_path()."/storeAdmin/images/categories/";
            $image->save($originalPath.$Image);
            $category->image = $Image;
        }

        $category->updated_by=Auth::user()->username;
        $category->save();
        return redirect('/category')->with("success","Product Category Updated successfully..");
    }

    public function activate($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $category = StoreCategory::find($id);
        $category->active = 1;
        $category->updated_by=Auth::user()->username;
        $category->save();
        return redirect('/category')->with("success","Product Category Activate Successfully.");
    }

    public function deactivate($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $category = StoreCategory::find($id);
        $category->active = 0;
        $category->updated_by=Auth::user()->username;
        $category->save();
        return redirect('/category/dlist')->with("success","Product Category Deactivated Successfully.");
    }

    public function getSubCategory($categoryId)
    {
        return StoreSubCategory::select('id', 'name')->where('active', 1)->where('categoryId', $categoryId)->orderBy('name')->get();
    } 

    public function getSubCategoryStore($categoryId)
    {
        $branchId = Auth::user()->reqBranchId;
        $categoryIds = BranchStock::join('store_products', 'branch_stocks.productId', 'store_products.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->where('store_products.categoryId', $categoryId)
        ->where('branch_stocks.branchId', $branchId)
        ->where('branch_stocks.status', 1)
        ->pluck('store_products.subCategoryId');
        
        return StoreSubCategory::select('id', 'name')->where('active', 1)->whereIn('id', $categoryIds)->orderBy('name')->get();
    } 

    public function getQuotationSubCategory($categoryId)
    {
        return StoreSubCategory::select('id', 'name')->where('active', 1)->where('categoryId', $categoryId)->orderBy('id')->get();
    } 
    
    public function categoryList()
    {
        return StoreCategory::where('active', 1)->orderBy('name')->pluck('name');
    }

    public function exportToExcel($active)
    {
        $fileName = 'CategoryList_'.date('d-M-Y').'.xlsx';
        return Excel::download(new CategoryExport($active), $fileName);
    }

}
