<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StoreScrapCategory;
use App\StoreCategory;
use App\StoreScrap;
use App\StoreUnit;
use App\StoreProduct;
use App\StoreSubCategory;
use App\StoreProductOpeningStock;
use Auth;
use DB;
use Image;

class ScrapsController extends Controller
{
    public function index()
    {
        
        // $products = StoreProductOpeningStock::where('created_at', '>=', date('2025-01-05 00:00:00'))
        // ->where('created_at', '<=', date('2025-01-05 23:59:59'))
        // ->where('openingStockForDate', '>=', date('2025-01-05'))
        // ->orderBy('created_at')
        // ->get();
        // foreach($products as $product)
        // {
        //     $temp = StoreProduct::find($product->productId);
        //     $temp->stock = $product->openingStock;
        //     $temp->closingStock = $product->openingStock;
        //     $temp->save();
        // }

        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $countScraps = StoreScrap::whereStatus(0)->count();
        $scraps = StoreScrap::join('store_scrap_categories', 'store_scraps.scrapCategoryId', 'store_scrap_categories.id')
        ->join('store_units', 'store_scraps.unitId', 'store_units.id')
        ->join('store_categories', 'store_scraps.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_scraps.subCategoryId', 'store_sub_categories.id')
        ->select('store_scrap_categories.name as scrapCategoryName','store_categories.name as productCategoryName',
        'store_sub_categories.name as productSubCategoryName',
        'store_scraps.*', 'store_units.name as unitName')
        ->where('store_scraps.status', 1)
        ->get();
        return view('storeAdmin.scrapManagement.scraps.list')->with(['scraps'=>$scraps,'countScraps'=>$countScraps]);
    }

    public function dlist()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $scraps = StoreScrap::join('store_scrap_categories', 'store_scraps.scrapCategoryId', 'store_scrap_categories.id')
        ->join('store_units', 'store_scraps.unitId', 'store_units.id')
        ->join('store_categories', 'store_scraps.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_scraps.subCategoryId', 'store_sub_categories.id')
        ->select('store_scrap_categories.name as scrapCategoryName','store_categories.name as productCategoryName',
        'store_sub_categories.name as productSubCategoryName',
        'store_scraps.*', 'store_units.name as unitName')
        ->where('store_scraps.status', 0)
        ->get();
        $categoriesCt = StoreScrapCategory::whereActive(1)->count();
        $dCategoriesCt = StoreScrapCategory::whereActive(0)->count();
        return view('storeAdmin.scrapManagement.scraps.dlist')->with(['scraps'=>$scraps,'categoriesCt'=>$categoriesCt,'dCategoriesCt'=>$dCategoriesCt]);
    }

    public function create()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $scrapCategories =   StoreScrapCategory::whereActive(1)->pluck('name', 'id');
        $units = StoreUnit::whereActive(1)->pluck('name', 'id');
        $categories = StoreCategory::whereActive(1)->pluck('name', 'id');
        $countScraps = StoreScrap::whereStatus(1)->count();
        return view('storeAdmin.scrapManagement.scraps.create')->with(['units'=>$units,'countScraps'=>$countScraps,'scrapCategories'=>$scrapCategories,'categories'=>$categories]);
    }
    
    public function store(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $scrap = new StoreScrap;
        $scrap->scrapCategoryId = $request->scrapCategoryId;
        $scrap->categoryId = $request->categoryId;
        $scrap->subCategoryId = $request->subCategoryId;
        $scrap->productName = $request->productName;
        $scrap->qty = $request->qty;
        $scrap->unitId = $request->unitId;
        $scrap->total = $request->total;
        $scrap->description = $request->description;
        $scrap->dateOfScrap = $request->dateOfScrap;
        $scrap->estimatedPrice = $request->estimatedPrice;
        $scrap->actualPrice = $request->actualPrice;
        $scrap->amount = $request->amount;
        if(!empty($request->file('image')))
        {
            $originalImage= $request->file('image');
            $Image = Auth::user()->id.date('Ymdhis').'.'.$originalImage->getClientOriginalExtension();
            $image = Image::make($originalImage);
            $originalPath =  public_path()."/storeAdmin/scrapImages/";
            $image->resize(700,700);
            $image->save($originalPath.$Image);
            $scrap->image = $Image;
        }

        $scrap->updated_by=Auth::user()->username;
        $scrap->save();
        if($request->addScrap == 1)
            return redirect('/scraps')->with("success","Scrap Store successfully..");
        else
            return redirect('/scraps/create')->with("success","Scrap Store successfully..");
    }

    public function edit($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $scrap = StoreScrap::find($id);
        $scrapCategories = StoreScrapCategory::whereActive(1)->pluck('name', 'id');
        $units = StoreUnit::whereActive(1)->pluck('name', 'id');
        $categories = StoreCategory::whereActive(1)->pluck('name', 'id');
        $subCategories = StoreSubCategory::where('categoryId', $scrap->categoryId)->whereActive(1)->pluck('name', 'id');
        $countScraps = StoreScrap::whereStatus(1)->count();
        return view('storeAdmin.scrapManagement.scraps.edit')->with(['subCategories'=>$subCategories,'scrap'=>$scrap,'units'=>$units,'countScraps'=>$countScraps,'scrapCategories'=>$scrapCategories,'categories'=>$categories]);
    }

    public function update(Request $request, $id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        if(StoreScrap::where('productName', $request->productName)->where('id', '<>', $id)->count())
        {
            return redirect()->back()->withInput()->with("error","Scrap Name is already exist..");
        }

        $scrap = StoreScrap::find($id);
        $scrap->scrapCategoryId = $request->scrapCategoryId;
        $scrap->categoryId = $request->categoryId;
        $scrap->subCategoryId = $request->subCategoryId;
        $scrap->productName = $request->productName;
        $scrap->qty = $request->qty;
        $scrap->unitId = $request->unitId;
        $scrap->total = $request->total;
        $scrap->description = $request->description;
        $scrap->dateOfScrap = $request->dateOfScrap;
        $scrap->estimatedPrice = $request->estimatedPrice;
        $scrap->actualPrice = $request->actualPrice;
        $scrap->amount = $request->amount;
        if(!empty($request->file('image')))
        {
            $originalImage= $request->file('image');
            $Image = Auth::user()->id.date('Ymdhis').'.'.$originalImage->getClientOriginalExtension();
            $image = Image::make($originalImage);
            $originalPath =  public_path()."/storeAdmin/scrapImages/";
            $image->resize(700,700);
            $image->save($originalPath.$Image);
            $scrap->image = $Image;
        }

        $scrap->updated_by=Auth::user()->username;
        $scrap->save();
        return redirect('/scraps')->with("success","Scrap Updated successfully..");
    }

    public function activate($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $category = StoreScrap::find($id);
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

    public function show($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $scrap = StoreScrap::join('store_scrap_categories', 'store_scraps.scrapCategoryId', 'store_scrap_categories.id')
        ->join('store_units', 'store_scraps.unitId', 'store_units.id')
        ->join('store_categories', 'store_scraps.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_scraps.subCategoryId', 'store_sub_categories.id')
        ->select('store_scrap_categories.name as scrapCategoryName','store_categories.name as productCategoryName',
        'store_sub_categories.name as productSubCategoryName',
        'store_scraps.*', 'store_units.name as unitName')
        ->where('store_scraps.id', $id)
        ->first();
        
       
        $countScraps = StoreScrap::whereStatus(1)->count();
        return view('storeAdmin.scrapManagement.scraps.show')->with(['scrap'=>$scrap,'countScraps'=>$countScraps]);
    }
}

