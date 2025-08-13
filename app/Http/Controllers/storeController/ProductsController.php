<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\stores\ProductsExport;
use App\Helpers\Utility;
use App\StoreHall;
use App\StoreRack;
use App\StoreShel;
use App\StoreProduct;
use App\StoreCategory;
use App\StoreUnit;
use App\StoreSubCategory;
use App\TempStoreProduct;
use App\StoreProductOpeningStock;
use App\InwardProductList;
use App\EventRequisitionProduct;
use App\StoreRequisitionProduct;
use App\OutwardProductReturn;
use App\StoreProductLedger;
use Auth;
use DB;
use Image;
use PDF;
use Excel;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $search = $request->search;
        $activeCount = StoreProduct::whereActive(1)->count();
        $deactiveCount = StoreProduct::whereActive(0)->count();
        $products = StoreProduct::join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->join('store_halls', 'store_products.hallId', 'store_halls.id')
        ->join('store_racks', 'store_products.rackId', 'store_racks.id')
        ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
        ->select('store_categories.name as categoryName','store_halls.name as hallName','store_racks.name as rackName',
         'store_sub_categories.name as subCategoryName', 'store_shels.name as shelfName', 'store_products.*')
        ->where('store_products.active', 1)
        ->where('store_products.name', 'like', '%' . $search . '%')
        ->orderBy('store_products.name')
        ->paginate(20)->appends(['search'=>$search,'activeCount'=>$activeCount,'deactiveCount'=>$deactiveCount]);

       
        return view('storeAdmin.masters.products.list')->with(['search'=>$search,'products'=>$products,'activeCount'=>$activeCount,'deactiveCount'=>$deactiveCount]);
    }

    public function dlist(Request $request)
    {   
        //  $util = new Utility();
        //  return $util->sendDailyStockReport(date('Y-m-d'));
        // return StoreProduct::whereIn('id', [172,331,439,446,449,483,488,489,506,533,771,797,807,1031,1047,1063,1196,1212,1216,1230,1231,1232,1245,1252,1341,1343,1380,1421,1639,1742,1766,1767,1780,1783,1784,1786,1789,1790,1791,1792,1819,1820,1821,1823,1873,2117,2229,2246,2314,2339,2530,2557,2642,2726,2769,2875,2937,2940,2993,3004,3026,3369,3407,4063,4064,4067,4070,4074,4076,4077,4082,4084,4085,4401,4517,4694,4719,4738,4742,4746,4796,4811,4963,5074,5096,5125,5275,5289,5512,5635,5639,5669,5747,5868,5992,6233,6242,6262,6328,6445,6554,6613,6640,6699,6740,6792,6795,6797,6799,6801,6808,6809,6810,6811,6823,6829,6841,6852,6860,6861,6951,6952,6953,6955,6964,6979,7006,7045,7052,7089,7315,7336,7357,7454,7467,7468,7544,7579,7601,7614,7615,7631,7647,7648,7652,7656,7657,7658,7660,7661,7662,7663,7673,7674,7675,7695,7759,7760])->pluck('name');

        // $util = new Utility();
      

        // $products = StoreProduct::where('categoryId', [3,5])
        // ->where('active', 1)
        // ->where('stockStatus', 0)
        // ->whereNotNull('openingStockForDate')
        // ->take(50)
        // ->get();
    
        // $updatedBy = Auth::user()->username; // Store once to avoid multiple calls

        // foreach ($products as $product) 
        // {
        //     $ledgers = StoreProductLedger::where('productId', $product->id)->get();
        //     if($ledgers)
        //     {
        //         foreach($ledgers as $ledger)
        //         {
        //             $ledger->delete();
        //         }
        //     }
           
        //     // **Inward Entry**

        //    $productLists = InwardProductList::join('inwards', 'inward_product_lists.inwardId', '=', 'inwards.id')
        //     ->select('inwards.id as primaryTransactionId', 'inward_product_lists.id as transactionId', 
        //                 'inwards.forDate', 'inward_product_lists.qty', 'inward_product_lists.created_at', 'inward_product_lists.updated_at')
        //     ->where('inwards.created_at', '>=', $product->openingStockForDate)
        //     ->where('inward_product_lists.productId', $product->id)
        //     ->where('inward_product_lists.status', 1)
        //     ->where('inward_product_lists.qty', '!=', 0)
        //     ->orderBy('inwards.forDate')
        //     ->get();

        //     $this->updateLedger($productLists, $product, 1, 'inwardQty', $updatedBy);
        
        //     //**Event Requisitions Entry**
        //         $productLists = EventRequisitionProduct::join('event_requisitions', 'event_requisition_products.eventReqId', '=', 'event_requisitions.id')
        //         ->select('event_requisitions.id as primaryTransactionId', 'event_requisition_products.id as transactionId', 
        //                  'event_requisitions.requisitionDate as forDate', 'event_requisition_products.qty', 'event_requisition_products.created_at', 'event_requisition_products.updated_at')
        //         ->where('event_requisitions.requisitionDate', '>=', $product->openingStockForDate)
        //         ->where('event_requisition_products.productId', $product->id)
        //         ->where('event_requisition_products.status', 1)
        //         ->where('event_requisition_products.productType', 1)
        //         ->where('event_requisition_products.qty', '!=', 0)
        //         ->orderBy('event_requisitions.requisitionDate')
        //         ->get();

        //         $this->updateLedger($productLists, $product, 2, 'outwardQty', $updatedBy);

        //     // **Normal Requisitions Entry**
        //     $productLists = StoreRequisitionProduct::join('store_requisitions', 'store_requisition_products.requisitionId', '=', 'store_requisitions.id')
        //     ->select('store_requisitions.id as primaryTransactionId', 'store_requisition_products.id as transactionId', 
        //                 'store_requisitions.requisitionDate as forDate', 'store_requisition_products.receivedQty as qty', 
        //                 'store_requisition_products.created_at', 'store_requisition_products.updated_at')
        //     ->where('store_requisitions.requisitionDate', '>=', $product->openingStockForDate)
        //     ->where('store_requisition_products.productId', $product->id)
        //     ->where('store_requisition_products.status', 1)
        //     ->where('store_requisition_products.reqProductType', 1)
        //     ->where('store_requisition_products.receivedQty', '!=', 0)
        //     ->orderBy('store_requisitions.requisitionDate')
        //     ->get();

        //    $this->updateLedger($productLists, $product, 2, 'outwardQty', $updatedBy);

        //     //**Product Return Entry**

        //     $productLists = OutwardProductReturn::join('store_outwards', 'outward_product_returns.outwardId', '=', 'store_outwards.id')
        //     ->select('store_outwards.id as primaryTransactionId',  'outward_product_returns.id as transactionId',
        //      'store_outwards.forDate','outward_product_returns.returnQty as qty', 
        //      'outward_product_returns.created_at', 'outward_product_returns.updated_at')
        //     ->where('store_outwards.forDate', '>=', $product->openingStockForDate)
        //     ->where('outward_product_returns.productId', $product->id)
        //     ->where('outward_product_returns.status', 1)
        //     ->where('outward_product_returns.returnQty', '!=', 0)
        //     ->orderBy('store_outwards.forDate')
        //     ->get();

        //     $this->updateLedger($productLists, $product, 3, 'returnQty', $updatedBy);

        //     $product->stockStatus=1;
        //     $product->save();
        // }

        // return 'ddd';

        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $search = $request->search;
        $products = StoreProduct::join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->join('store_halls', 'store_products.hallId', 'store_halls.id')
        ->join('store_racks', 'store_products.rackId', 'store_racks.id')
        ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
        ->select('store_categories.name as categoryName','store_halls.name as hallName','store_racks.name as rackName',
         'store_sub_categories.name as subCategoryName', 'store_shels.name as shelfName', 'store_products.*')
        ->where('store_products.active', 0)
        ->where('store_products.name', 'like', '%' . $search . '%')
        ->orderBy('store_products.name')
        ->paginate(20);

        $activeCount = StoreProduct::whereActive(1)->count();
        $deactiveCount = StoreProduct::whereActive(0)->count();
        return view('storeAdmin.masters.products.dlist')->with(['search'=>$search,'products'=>$products,'activeCount'=>$activeCount,'deactiveCount'=>$deactiveCount]);
    }

    private function updateLedger($lists, $product, $type, $qtyField, $updatedBy)
    {
        if($lists)
        {
            foreach ($lists as $list) 
            {
                if($list->qty != 0)
                {
                    $ledger = StoreProductLedger::where('transactionId', $list->transactionId)->where('productId', $product->id)->where('forDate', $list->forDate)->where('type', $type)->first();
                    
                    if(!$ledger)
                        $ledger = new StoreProductLedger();
                    
                    $ledger->primaryTransactionId = $list->primaryTransactionId;
                    $ledger->transactionId = $list->transactionId;
                    $ledger->forDate = $list->forDate;
                    $ledger->productId = $product->id;
                    $ledger->$qtyField = $list->qty;
                    $ledger->type = $type;
                    $ledger->status = 1;
                    $ledger->created_at = $list->created_at;
                    $ledger->updated_at = $list->updated_at;
                    $ledger->updated_by = $updatedBy;
                    $ledger->save();
                }
            }
        }
    }

    public function create()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $halls = StoreHall::where('active', 1)->orderBy('name')->pluck('name', 'id');
        $categories = StoreCategory::where('active', 1)->orderBy('name')->pluck('name', 'id');
        $units = StoreUnit::where('active', 1)->orderBy('name')->pluck('name', 'id');
        $activeCount = StoreProduct::whereActive(1)->count();
        $deactiveCount = StoreProduct::whereActive(0)->count();
        return view('storeAdmin.masters.products.create')->with(['halls'=>$halls,'categories'=>$categories,'units'=>$units,
        'deactiveCount'=>$deactiveCount,'activeCount'=>$activeCount]);
    }

    public function store(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        if(StoreProduct::where('name', $request->name)->where('categoryId', $request->categoryId)->where('subCategoryId', $request->subCategoryId)->first())
        {
            return redirect()->back()->withInput()->with("error","This Product Already added, try new Product");
        }
        $tempProduct = new TempStoreProduct;

        $product = new StoreProduct;
        $tempProduct->categoryId=$product->categoryId = $request->categoryId;      
        $tempProduct->subCategoryId=$product->subCategoryId = $request->subCategoryId;        
        $tempProduct->name=$product->name = $request->name;
        $tempProduct->company=$product->company = $request->company;
        $tempProduct->openingStock=$product->openingStock = $request->openingStock;        
        $tempProduct->openingStockForDate=$product->openingStockForDate = $request->openingStockForDate;        
        $tempProduct->stock=$product->stock = $request->openingStock; 
        $tempProduct->unitId=$product->unitId = $request->unitId;
        $tempProduct->color=$product->color = $request->color;
        $tempProduct->size=$product->size = $request->size;
        $tempProduct->fuelType=$product->fuelType = $request->fuelType;
        $tempProduct->modelNumber=$product->modelNumber = $request->modelNumber;
        $tempProduct->returnStatus=$product->returnStatus = $request->returnStatus;
        $tempProduct->reorderLevel=$product->reorderLevel = $request->reorderLevel;
        $tempProduct->maximumLevel=$product->maximumLevel = $request->maximumLevel;
        $tempProduct->manuDate=$product->manuDate = ($request->manuDate == '')?'':$request->manuDate;
        $tempProduct->expiryDate=$product->expiryDate = ($request->expiryDate == '')?'':$request->expiryDate;
        $tempProduct->CGST=$product->CGST = $request->CGST;
        $tempProduct->SGST=$product->SGST = $request->SGST;
        $tempProduct->IGST=$product->IGST = $request->IGST;
        $tempProduct->productRate=$product->productRate = $request->productRate;
        $tempProduct->description=$product->description = $request->description;
        $tempProduct->hallId=$product->hallId = $request->hallId;
        $tempProduct->rackId=$product->rackId = $request->rackId;
        $tempProduct->shelfId=$product->shelfId = $request->shelfId;

        if(!empty($request->file('image')))
        {
            $originalImage= $request->file('image');
            $Image = Auth::user()->id.date('Ymdhis').'.'.$originalImage->getClientOriginalExtension();
            $image = Image::make($originalImage);
            $originalPath =  public_path()."/storeAdmin/productImages/";
            $image->resize(700,700);
            $image->save($originalPath.$Image);
            $tempProduct->image=$product->image = $Image;
        }

        $category = StoreCategory::where('id', $request->categoryId)->value('name');
        $subCategory = StoreSubCategory::where('id', $request->subCategoryId)->value('name');

        $tempProduct->updated_by=$product->updated_by=Auth::user()->username;
        $tmProductId = StoreProduct::orderBy('id', 'desc')->value('id') + 1;
        $tempProduct->productCode=$product->productCode = "EMEF/AWS/".substr(strtoupper($category), 0, 5)."/".substr(strtoupper($subCategory), 0, 5)."/".$tmProductId;
        
        try
        {
            DB::beginTransaction();
            $product->save();
            $tempProduct->productId = $product->id;
            $tempProduct->save();
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->back()->withInput()->with("error","There is some issue. Please try again!!!");
        }  
        
        return redirect('/product')->with("success","Product Store successfully..");
    }

    public function edit($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $product = StoreProduct::join('store_categories','store_products.categoryId','store_categories.id')
        ->join('store_sub_categories','store_products.subCategoryId','store_sub_categories.id')
        ->select('store_sub_categories.name as subCategory', 'store_categories.name as category', 'store_products.*')
        ->where('store_products.id',$id)
        ->first();
        $halls = StoreHall::where('active', 1)->pluck('name', 'id');
        $racks = StoreRack::where('active', 1)->where('hallId', $product->hallId)->pluck('name', 'id');
        $shelfs = StoreShel::where('active', 1)->where('rackId', $product->rackId)->pluck('name', 'id');
        $units = StoreUnit::where('active', 1)->pluck('name', 'id');
        $categories = StoreCategory::where('active', 1)->orderBy('name')->pluck('name', 'id');
        $subCategories = StoreSubCategory::where('categoryId', $product->categoryId)->where('active', 1)->orderBy('name')->pluck('name', 'id');

        $activeCount = StoreProduct::whereActive(1)->count();
        $deactiveCount = StoreProduct::whereActive(0)->count();
        return view('storeAdmin.masters.products.edit')->with(['categories'=>$categories,'subCategories'=>$subCategories,'product'=>$product, 'halls'=>$halls, 'racks'=>$racks, 'shelfs'=>$shelfs,
        'units'=>$units, 'deactiveCount'=>$deactiveCount,'activeCount'=>$activeCount]);
    }
   
    public function update(Request $request, $id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        $tempProduct = new TempStoreProduct;
        $product = StoreProduct::find($id);
        $tempProduct->productId = $id;
        $tempProduct->categoryId = $product->categoryId = $request->categoryId;      
        $tempProduct->subCategoryId = $product->subCategoryId = $request->subCategoryId;        
        $tempProduct->name = $product->name = $request->name;
        $tempProduct->company = $product->company = $request->company;
        $tempProduct->unitId = $product->unitId = $request->unitId;
        $tempProduct->color = $product->color = $request->color;
        $tempProduct->size = $product->size = $request->size;
        $tempProduct->fuelType=$product->fuelType = $request->fuelType;
        // if($product->stockUpdateStatus == 0)
        // {
        //     $tempProduct->openingStock = $product->openingStock = $request->openingStock;        
        //     $tempProduct->stock = $product->stock = $request->openingStock; 
        //     $tempProduct->stockUpdateStatus = $product->stockUpdateStatus = 1; 
        // }

        // $tempProduct->openingStock=$product->openingStock;
        // $tempProduct->stock=$product->stock;

        $tempProduct->returnStatus = $product->returnStatus = $request->returnStatus;
        $tempProduct->reorderLevel = $product->reorderLevel = $request->reorderLevel;
        $tempProduct->maximumLevel = $product->maximumLevel = $request->maximumLevel;
        $tempProduct->modelNumber = $product->modelNumber = $request->modelNumber;
        
        $tempProduct->manuDate = $product->manuDate = ($request->manuDate == '')?'':$request->manuDate;
        $tempProduct->expiryDate = $product->expiryDate = ($request->expiryDate == '')?'':$request->expiryDate;
        $tempProduct->CGST = $product->CGST = $request->CGST;
        $tempProduct->SGST = $product->SGST = $request->SGST;
        $tempProduct->IGST = $product->IGST = $request->IGST;
        $tempProduct->productRate = $product->productRate = $request->productRate;
        $tempProduct->description = $product->description = $request->description;
        $tempProduct->hallId = $product->hallId = $request->hallId;
        $tempProduct->rackId = $product->rackId = $request->rackId;
        $tempProduct->shelfId = $product->shelfId = $request->shelfId;

        if(!empty($request->file('image')))
        {
            if($product->image != '')
            {
                $image_name = $product->image;
                $image_path = public_path('storeAdmin/productImages/'.$image_name);
                if(file_exists($image_path)){
                    unlink($image_path);
                }
            }

            $originalImage= $request->file('image');
            $Image = Auth::user()->id.date('Ymdhis').'.'.$originalImage->getClientOriginalExtension();
            $image = Image::make($originalImage);
            $originalPath =  public_path()."/storeAdmin/productImages/";
            $image->resize(700,700);
            $image->save($originalPath.$Image);
            $tempProduct->image = $product->image = $Image;
        }

        $tempProduct->updated_by = $product->updated_by=Auth::user()->username;
        try
        {
            DB::beginTransaction();
            $product->save();
            $tempProduct->save();
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->back()->withInput()->with("error","There is some issue. Please try again!!!");
        }  
        return redirect('/product?search='.$request->name)->with("success","Product Updated successfully..");
    }

    public function printQRCode($productId)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $product = StoreProduct::select('productCode', 'name')->where('id', $productId)->first();
        $productCode = $product->productCode;
        $file = $product->name.'.pdf';
        $pdf = PDF::loadView('storeAdmin.masters.products.printQR',compact('productCode','productId'))->setPaper('a4');
        return $pdf->stream($file); 
    }

    public function show($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $product = StoreProduct::find($id);
        $halls = StoreHall::where('active', 1)->pluck('name', 'id');
        $racks = StoreRack::where('active', 1)->where('hallId', $product->hallId)->pluck('name', 'id');
        $shelfs = StoreShel::where('active', 1)->where('rackId', $product->rackId)->pluck('name', 'id');
        $categories = StoreCategory::where('active', 1)->pluck('name', 'id');
        $subCategories = StoreSubCategory::where('active', 1)->where('categoryId', $product->categoryId)->pluck('name', 'id');
        $units = StoreUnit::where('active', 1)->pluck('name', 'id');

        $activeCount = StoreProduct::whereActive(1)->count();
        $deactiveCount = StoreProduct::whereActive(0)->count();
        return view('storeAdmin.masters.products.show')->with(['product'=>$product, 'halls'=>$halls, 'racks'=>$racks, 'shelfs'=>$shelfs,
        'categories'=>$categories,'subCategories'=>$subCategories,'units'=>$units, 'deactiveCount'=>$deactiveCount,'activeCount'=>$activeCount]);
    }

    public function printProductQR($id)
    {
        $product = StoreProduct::find($id);
        $halls = StoreHall::where('active', 1)->pluck('name', 'id');
        $racks = StoreRack::where('active', 1)->where('hallId', $product->hallId)->pluck('name', 'id');
        $shelfs = StoreShel::where('active', 1)->where('rackId', $product->rackId)->pluck('name', 'id');
        $categories = StoreCategory::where('active', 1)->pluck('name', 'id');
        $subCategories = StoreSubCategory::where('active', 1)->where('categoryId', $product->categoryId)->pluck('name', 'id');
        $units = StoreUnit::where('active', 1)->pluck('name', 'id');

        $activeCount = StoreProduct::whereActive(1)->count();
        $deactiveCount = StoreProduct::whereActive(0)->count();
        return view('storeAdmin.showProductDetails')->with(['product'=>$product, 'halls'=>$halls, 'racks'=>$racks, 'shelfs'=>$shelfs,
        'categories'=>$categories,'subCategories'=>$subCategories,'units'=>$units, 'deactiveCount'=>$deactiveCount,'activeCount'=>$activeCount]);
    }

    

    public function activate($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $product = StoreProduct::find($id);
        $product->active = 1;
        $product->updated_by=Auth::user()->username;
        $product->save();
        return redirect('/product')->with("success","Product Activate Successfully.");
    }

    public function deactivate($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $product = StoreProduct::find($id);
        $product->active = 0;
        $product->updated_by=Auth::user()->username;
        $product->save();
        return redirect('/product/dlist')->with("success","Product Deactivated Successfully.");
    }

    public function getShelfs($rackId)
    {
        return StoreShel::where('rackId', $rackId)->where('active', 1)->orderBy('name')->get(['id', 'name']);
    }

    public function productList()
    {
        return StoreProduct::select("name")->where('active', 1)->orderBy('name')->pluck('name');
    }

    public function productDetails($name)
    {
        return StoreProduct::where(DB::raw('concat(productCode,"-",name)') , '=' , $name)->where('active', 1)->orderBy('name')->first(); 
    }

    public function getProductLists($name)
    {
        return StoreProduct::where('name', 'like', '%' . $name . '%')
        ->orWhere('productCode', 'like', '%' . $name . '%')->where('active', 1)->orderBy('name')->get();
    }

    public function getInOutProductList($categoryId, $subCategoryId)
    {
        return StoreProduct::join('store_units', 'store_products.unitId', 'store_units.id')
        ->where('store_products.categoryId', $categoryId)
        ->where('store_products.subCategoryId', $subCategoryId)
        ->where('store_products.active', 1)
        ->select('store_products.id', 'store_products.name', 'store_products.image','store_products.company', 
        'store_products.color', 'store_products.size', 'store_units.name as unitName')
        ->orderBy('store_products.name')
        ->get();
    }

    public function getProductDetails($productId)
    {
        return StoreProduct::join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->join('store_units', 'store_products.unitId', 'store_units.id')
        ->select("store_products.id", "store_categories.name as categoryName", "store_sub_categories.name as subCategoryName", 
        "store_products.size","store_products.color", "store_units.name as unitName")
        ->where('store_products.productId', $productId)
        ->where('store_categories.active', 1)
        ->where('store_sub_categories.active', 1)
        ->where('store_units.active', 1)
        ->where('store_products.active', 1)
        ->orderBy('store_products.name')
        ->get();
    }

    public function getLastProductCode()
    {
        return StoreProduct::orderBy('productCode', 'desc')->where('active', 1)->orderBy('name')->value("productCode");
    }

    public function exportExcelSheet($search,$active)
    {
        $fileName = 'ProductList_'.date('d-M-Y').'.xlsx';
        return Excel::download(new ProductsExport($search, $active), $fileName);
    }

    public function searchProduct(Request $request)
    {
        $halls = StoreHall::where('active', 1)->orderBy('name')->pluck('name', 'id');
        $racks = StoreRack::where('active', 1)->orderBy('name')->pluck('name', 'id');
        $shelfs = StoreShel::where('active', 1)->orderBy('name')->pluck('name', 'id');
        return view('storeAdmin.masters.products.searchProduct')->with(['halls'=>$halls,'racks'=>$racks,'shelfs'=>$shelfs]); 
    }

    public function generateQRCodes(Request $request)
    {
        $hallId = $request->hallId;
        $rackId = $request->rackId;
        $shelfId = $request->shelfId;

        $products = StoreProduct::select('productCode', 'name', 'id')
        ->where('hallId', $hallId);
        if($rackId != '')
            $products=$products->where('rackId', $rackId);

        if($shelfId != '')
            $products=$products->where('shelfId', $shelfId);
        
       $productList=$products->orderBy('name')->get();
   
        $file = date('YmdHi').'.pdf';
        $size = $request->size;
     
        $pdf = PDF::loadView('storeAdmin.masters.products.QRCodePDF',compact('productList','size'));
        return $pdf->stream($file);
    }

    public function changeOpeningStock($productId)
    {
        $activeCount = StoreProduct::whereActive(1)->count();
        $deactiveCount = StoreProduct::whereActive(0)->count();
        $productDetail = StoreProduct::join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->join('store_units', 'store_products.unitId', 'store_units.id')
        ->select("store_products.id","store_products.name", "store_categories.name as categoryName",
         "store_sub_categories.name as subCategoryName", "store_products.modelNumber",
        "store_products.size","store_products.color", "store_products.company", "store_units.name as unitName",
        "store_products.openingStockForDate",
        "store_products.openingStock")
        ->where('store_products.id', $productId)
        ->where('store_categories.active', 1)
        ->where('store_sub_categories.active', 1)
        ->where('store_units.active', 1)
        ->where('store_products.active', 1)
        ->orderBy('store_products.name')
        ->first();
        return view('storeAdmin.masters.products.changeOpeningStock')->with(['activeCount'=>$activeCount,'deactiveCount'=>$deactiveCount,'productDetail'=>$productDetail]); 
    }

    public function updateOpeningStock(Request $request)
    {
        $openingStock = new StoreProductOpeningStock;
        $openingStock->productId=$request->productId;
        $openingStock->openingStock=$request->openingStock;
        $openingStock->openingStockForDate=$request->openingStockForDate.' '.date('H:i:s');
        $openingStock->updated_by=$request->updated_by;
        $openingStock->updated_by=Auth::user()->username;
        // try
        // {
        //    DB::beginTransaction();
            $deleteLedger = StoreProductLedger::Where('productId', $request->productId)->get();
            if($deleteLedger)
            {
                foreach($deleteLedger as $ledger)
                {
                    $ledger->delete();
                }
            }

            if($openingStock->save())
            {
                $product = StoreProduct::find($request->productId);
                $product->openingStock=$request->openingStock;
                $product->closingStock=$request->openingStock;
                $product->stock=$request->openingStock;
                $product->openingStockForDate=$request->openingStockForDate.' '.date('H:i:s');
                $product->updated_by=Auth::user()->username;


                $util = new Utility();
                $updatedBy = Auth::user()->username; // Store once to avoid multiple calls

                    // **Inward Entry**
                $productLists = InwardProductList::join('inwards', 'inward_product_lists.inwardId', '=', 'inwards.id')
                ->select('inwards.id as primaryTransactionId', 'inward_product_lists.id as transactionId', 
                            'inwards.forDate', 'inward_product_lists.qty', 'inward_product_lists.created_at', 'inward_product_lists.updated_at')
                ->where('inwards.created_at', '>=', $product->openingStockForDate)
                ->where('inward_product_lists.productId', $product->id)
                ->where('inward_product_lists.status', 1)
                ->where('inward_product_lists.qty', '!=', 0)
                ->orderBy('inwards.forDate')
                ->get();

                $this->updateLedger($productLists, $product, 1, 'inwardQty', $updatedBy);

                //**Event Requisitions Entry**
                $productLists = EventRequisitionProduct::join('event_requisitions', 'event_requisition_products.eventReqId', '=', 'event_requisitions.id')
                ->select('event_requisitions.id as primaryTransactionId', 'event_requisition_products.id as transactionId', 
                        'event_requisitions.requisitionDate as forDate', 'event_requisition_products.qty', 'event_requisition_products.created_at', 'event_requisition_products.updated_at')
                ->where('event_requisitions.requisitionDate', '>=', $product->openingStockForDate)
                ->where('event_requisition_products.productId', $product->id)
                ->where('event_requisition_products.status', 1)
                ->where('event_requisition_products.productType', 1)
                ->where('event_requisition_products.qty', '!=', 0)
                ->orderBy('event_requisitions.requisitionDate')
                ->get();

                $this->updateLedger($productLists, $product, 2, 'outwardQty', $updatedBy);

                // **Normal Requisitions Entry**
                $productLists = StoreRequisitionProduct::join('store_requisitions', 'store_requisition_products.requisitionId', '=', 'store_requisitions.id')
                ->select('store_requisitions.id as primaryTransactionId', 'store_requisition_products.id as transactionId', 
                'store_requisitions.requisitionDate as forDate', 'store_requisition_products.receivedQty as qty', 
                'store_requisition_products.created_at', 'store_requisition_products.updated_at')
                ->where('store_requisitions.requisitionDate', '>=', $product->openingStockForDate)
                ->where('store_requisition_products.productId', $product->id)
                ->where('store_requisition_products.status', 1)
                ->where('store_requisition_products.reqProductType', 1)
                ->where('store_requisition_products.receivedQty', '!=', 0)
                ->orderBy('store_requisitions.requisitionDate')
                ->get();

                $this->updateLedger($productLists, $product, 2, 'outwardQty', $updatedBy);

                //**Product Return Entry**

                $productLists = OutwardProductReturn::join('store_outwards', 'outward_product_returns.outwardId', '=', 'store_outwards.id')
                ->select('store_outwards.id as primaryTransactionId',  'outward_product_returns.id as transactionId',
                'store_outwards.forDate','outward_product_returns.returnQty as qty', 
                'outward_product_returns.created_at', 'outward_product_returns.updated_at')
                ->where('store_outwards.forDate', '>=', $product->openingStockForDate)
                ->where('outward_product_returns.productId', $product->id)
                ->where('outward_product_returns.status', 1)
                ->where('outward_product_returns.returnQty', '!=', 0)
                ->orderBy('store_outwards.forDate')
                ->get();

                $this->updateLedger($productLists, $product, 3, 'returnQty', $updatedBy);

                $product->stockStatus=1;
                $product->save();

            //     DB::commit();
                return redirect()->back()->withInput()->with("success","Product Opening Stock Updated successfully..");
            }
        // }
        // catch(\Exception $e)
        // {
        //     DB::rollback();
        //     return redirect()->back()->withInput()->with("error","There is some issue. Please try again!!!");
        // }  
    }
}
