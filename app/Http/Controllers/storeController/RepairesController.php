<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Utility;

use App\StoreProduct;
use App\StoreProductLedger;
use App\StoreCategory;
use App\StoreRepaire;
use Auth;
use DB;

class RepairesController extends Controller
{
    public function index(Request $request) // pending List
    {
        $repaireProducts = StoreRepaire::join('store_products', 'store_repaires.productId', 'store_products.id')
        ->select('store_products.name as productName', 'store_repaires.*')
        ->where('store_repaires.status', 0)
        ->get(); // status 0- pending, 1-completed, 2- rejected / Cancelled
        return view('storeAdmin.repaires.pendingList', compact('repaireProducts'));
    }

    public function completedList(Request $request) // pending List
    {
        $repaireProducts = StoreRepaire::join('store_products', 'store_repaires.productId', 'store_products.id')
        ->select('store_products.name as productName', 'store_repaires.*')
        ->where('store_repaires.status', 1)
        ->get(); // status 0- pending, 1-completed, 2- rejected / Cancelled
        return view('storeAdmin.repaires.completedList', compact('repaireProducts'));
    }

    public function store(Request $request)
    {
        $updatedBy = Auth::user()->username;
        $repaire = new StoreRepaire;
        $repaire->productId=$request->productId;      
        $repaire->description=$request->description;        
        $repaire->reasonForRepaire=$request->reasonForRepaire;
        $repaire->count=$request->count;
        $repaire->forDate=$request->forDate;
        $repaire->updated_by=$updatedBy;
        if($repaire->save())
        {
            $product = StoreProduct::find($request->productId);
            $product->stock = $product->stock - $request->count;
            $product->closingStock = $product->closingStock - $request->count;
            if($product->save())
            {
                $ledger = new StoreProductLedger();
                $ledger->primaryTransactionId = $repaire->id;
                $ledger->transactionId = $repaire->id;
                $ledger->forDate = $request->forDate;
                $ledger->productId = $request->productId;      
                $ledger->outwardQty = $request->count;
                $ledger->type = 2;
                $ledger->status = 1;
                $ledger->remark = 'Repaire';
                $ledger->updated_by = $updatedBy;
                $ledger->save();
            }
        }

        return redirect('/repaires')->with("success","Repaire Product Store successfully..");
    }

    public function create()
    {
        $categories = StoreCategory::where('active', 1)->orderBy('name')->pluck('name', 'id');
        return view('storeAdmin.repaires.create', compact('categories'));
    }

    public function edit($id)
    {
        $repaireProduct = StoreRepaire::join('store_products', 'store_repaires.productId', 'store_products.id')
        ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->select('store_categories.name as categoryName', 'store_sub_categories.name as subCategoryName', 'store_products.name as productName', 'store_repaires.*')
        ->where('store_repaires.id', $id)
        ->first();
       
        return view('storeAdmin.repaires.edit', compact('repaireProduct'));
    }


    public function update(Request $request, $id)
    {
        $updatedBy = Auth::user()->username;
        $repaire = StoreRepaire::find($id);
        $repaire->returnCount=$request->returnCount;
        $repaire->returnDate=$request->returnDate;
        $repaire->status=1;
        $repaire->updated_by=$updatedBy;
        if($repaire->save())
        {
            $product = StoreProduct::find($repaire->productId);
            $product->stock = $product->stock + $request->returnCount;
            $product->closingStock = $product->closingStock + $request->returnCount;
            if($product->save())
            {
                $ledger = new StoreProductLedger();
                $ledger->primaryTransactionId = $repaire->id;
                $ledger->transactionId = $repaire->id;
                $ledger->forDate = $request->forDate;
                $ledger->productId = $request->productId;      
                $ledger->outwardQty = $request->count;
                $ledger->type = 3;
                $ledger->status = 1;
                $ledger->remark = 'returnQty';
                $ledger->updated_by = $updatedBy;
                $ledger->save();
            }
        }
        return redirect('/repaires/completedList')->with("success","Repaire Product return successfully..");
    }
}