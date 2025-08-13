<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Inward;
use App\InwardProductList;
use App\StoreInwardGrn;
use App\StoreInwardGrnProd;
use App\StoreCategory;
use App\StoreUnit;
use App\StoreProduct;
use App\TempStoreProduct;
use Auth;
use DB;

class InwardGrnsController extends Controller
{
    public function index()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $inwardGrns = StoreInwardGrn::whereStatus(1)->get();
        return view('storeAdmin.inwardGrn.list')->with(['inwardGrns'=>$inwardGrns]);
    }
    
    public function create()
    {
        //
    }

    public function getGRN($id)
    {     
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }   
        $inwardDetail = Inward::find($id);
        $inwardProdList = InwardProductList::join('store_products', 'inward_product_lists.productId', 'store_products.id')
        ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->join('store_units', 'store_products.unitId', 'store_units.id')
        ->select('store_products.name as productName', 'store_categories.name as categoryName', 'store_sub_categories.name as subCategoryName'
        ,'store_units.name as unitName', 'store_products.company', 'inward_product_lists.*')
        ->where('inward_product_lists.inwardId', $id)
        ->get();

        $countGRNInwards = StoreInwardGrn::whereStatus(1)->count();
        $units=StoreUnit::whereActive(1)->pluck('name', 'id');
        return view('storeAdmin.inwardGrn.create')->with(['units'=>$units,'inwardDetail'=>$inwardDetail,'inwardProdList'=>$inwardProdList,'countGRNInwards'=>$countGRNInwards]);
    }

    public function store(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $prodCount = count($request->productId);
        $inwardGrn = new StoreInwardGrn;
        $inwardGrn->forDate = $request->forDate;
        $inwardGrn->invoiceNo = $request->invoiceNo;        
        $inwardGrn->inwardId = $request->inwardId;
        $inwardGrn->discount = $request->discount;
        $inwardGrn->GST = $request->gst;
        $inwardGrn->grandTotal = $request->grandTotal;
        $inwardGrn->narration = $request->narration;
        $inwardGrn->updated_by = Auth::user()->username;
       
        if($inwardGrn->save())
        {
            for($i=0; $i<$prodCount; $i++)
            {
                $productList = new StoreInwardGrnProd;
                $productList->inwardGrnId = $inwardGrn->id;
                $productList->productId = $request->productId[$i];
                $productList->inwardQty = $request->inwardQty[$i];
                $productList->inwardUnitId = $request->inwardUnitId[$i];
                $productList->inwardTotal = $request->inwardTotal[$i];
                $productList->returnQty = $request->returnQty[$i];
                $productList->returnUnitId = $request->returnUnitId[$i];
                $productList->returnTotal = $request->returnTotal[$i];
                $productList->returnReason = $request->returnReason[$i];
                $productList->updated_by = Auth::user()->username;
                if($productList->save())
                {
                    $product=StoreProduct::find($productList->productId);
                    $product->stock = $product->stock - $request->returnQty[$i];
                    if($product->save())
                    {
                        $tempProduct = new TempStoreProduct;
                        $tempProduct->productId = $productList->productId;
                        $tempProduct->stock = $product->stock;
                        $tempProduct->updated_by =  Auth::user()->username;
                        $tempProduct->save();
                    }
                }
            }
        }
        return redirect('/inwardGRNs')->with("success","GRN Save successfully..");
    }

    
    public function show($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $inwardGRN = StoreInwardGrn::find($id);
        $inwardProdList = InwardProductList::join('store_products', 'inward_product_lists.productId', 'store_products.id')
        ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->join('store_units', 'store_products.unitId', 'store_units.id')
        ->select('store_products.name as productName', 'store_categories.name as categoryName', 'store_sub_categories.name as subCategoryName'
        ,'store_units.name as unitName', 'store_products.company', 'inward_product_lists.*')
        ->where('inward_product_lists.inwardId', $id)
        ->get();

        $countGRNInwards = StoreInwardGrn::whereStatus(1)->count();
        $units=StoreUnit::whereActive(1)->pluck('name', 'id');
        return view('storeAdmin.inwardGrn.create')->with(['units'=>$units,'inwardDetail'=>$inwardDetail,'inwardProdList'=>$inwardProdList,'countGRNInwards'=>$countGRNInwards]);
    }

    
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }

   
    
}
