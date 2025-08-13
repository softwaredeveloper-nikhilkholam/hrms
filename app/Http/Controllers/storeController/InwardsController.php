<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Inward;
use App\InwardProductList;
use App\StoreProduct;
use App\StoreCategory;
use App\StoreVendor;
use App\StorePurchaseOrder;
use App\StoreProductReturn;
use App\StoreProductReturnList;
use App\ContactusLandPage;
use App\Helpers\Utility;

use Auth;
use Image;
use PDF;
use DB;

class InwardsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $userType = $user->userType;
        $reqDepartmentId = $user->reqDepartmentId;
        $typeOfCompany = explode(',',$user->typeOfCompany);
        
        if (in_array($userType, ['91', '61']) || $reqDepartmentId == '12') {
            $vendorName = $request->input('vendorName');
            $poNumber = $request->input('poNumber');
            $invoiceNo = $request->input('invoiceNo');
        
            $countInwards = Inward::where('active', 1)->count();
        
            $inwards = Inward::query()
                ->join('store_purchase_orders', 'inwards.purchaseOrderId', '=', 'store_purchase_orders.id')
                ->join('store_quotations', 'store_purchase_orders.quotationId', '=', 'store_quotations.id')
                ->join('store_vendors', 'inwards.vendorId', '=', 'store_vendors.id')
                ->select('store_vendors.name as vendorName', 'inwards.*')
                ->where('inwards.active', 1)
                ->where('inwards.forDate', '>=', date('Y-m-d', strtotime('-3 months')))
                ->when($vendorName, function ($query) use ($vendorName) {
                    return $query->where('store_vendors.name', $vendorName);
                })
                ->when($poNumber, function ($query) use ($poNumber) {
                    return $query->where('inwards.poNumber', $poNumber);
                })
                ->when($invoiceNo, function ($query) use ($invoiceNo) {
                    return $query->where('inwards.invoiceNo', $invoiceNo);
                })
                ->when($userType == '61', function ($query) use ($typeOfCompany) {
                    return $query->whereIn('store_quotations.typeOfCompany', $typeOfCompany);
                })
                ->orderBy('inwards.forDate', 'desc')
                ->paginate(15);
        
            return view('storeAdmin.inwards.list', compact('invoiceNo', 'poNumber', 'vendorName', 'inwards', 'countInwards'));
        }
        
        return redirect()->back()->withInput()->with("error", "You do not have permission to access this page.");
        
       
    }

    public function create(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $poNumber = $request->poNumber;
        $pOrder=[];
        $countInwards = Inward::whereActive(1)->count();
        if($poNumber)
        {
            $pOrder = StorePurchaseOrder::join('store_quotations', 'store_purchase_orders.quotationId', 'store_quotations.id')
            ->join('store_vendors', 'store_quotations.vendorId', 'store_vendors.id')
            ->select('store_quotations.quotNo','store_quotations.vendorId','store_vendors.landlineNo','store_vendors.address','store_vendors.name', 
            'store_purchase_orders.*','store_quotations.shippingAddress', 'store_quotations.reqNo', 'store_quotations.quotationFor')
            ->where('store_purchase_orders.poNumber', $poNumber)
            ->first();
            $categories = StoreCategory::where('active', 1)->orderBy('name')->pluck('name', 'id');
            $vendors = StoreVendor::where('active', 1)->orderBy('name')->pluck('name', 'id');
            return view('storeAdmin.inwards.create')->with(['pOrder'=>$pOrder,'poNumber'=>$poNumber,'categories'=>$categories,'vendors'=>$vendors,'countInwards'=>$countInwards]);
        }
        else
        {
            return view('storeAdmin.inwards.create')->with(['pOrder'=>$pOrder,'countInwards'=>$countInwards]);
        }
    }

    public function store(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
     
        $prodCount = count($request->productId);
        $inward = new Inward;
        $inward->forDate = $request->forDate;
        $inward->securityGateNo = $request->securityGateNo;
        $inward->vendorId = $request->vendorId;
        $inward->vendorAddress = $request->vendorAddress;
        $inward->invoiceNo = "IN-".date('Ymdhis');
        $inward->purchaseOrderId = StorePurchaseOrder::where('poNumber', $request->poNumber)->value('id');
        $inward->poNumber = $request->poNumber;
        $inward->billNo = $request->billNo;
        $inward->reqNo = $request->reqNo;
        $inward->subTotal = $request->subTotal;
        $inward->discount = $request->discount;
        $inward->otherCharges = $request->otherCharges;
        $inward->labCharges = $request->labCharges;
        $inward->gstRs = $request->gstRs;
        $inward->netTotal = $request->netTotal;
        $inward->narration = $request->narration;
        $inward->updated_by = Auth::user()->username;

        DB::beginTransaction();
        try 
        {
            if(!empty($request->file('billImage')))
            {
                $originalImage= $request->file('billImage');
                $Image = $inward->invoiceNo.'.'.$originalImage->getClientOriginalExtension();
                $image = Image::make($originalImage);
                $originalPath =  public_path()."/storeAdmin/inwardImages/";
                $image->resize(800,800);
                $image->save($originalPath.$Image);
                $inward->billImage = $Image;
            }

            if($inward->save())
            {
                for($i=0; $i<$prodCount; $i++)
                { 
                    $productList = new InwardProductList;
                    $productList->inwardId = $inward->id;
                    $productList->productId = $request->productId[$i];
                    $productList->expiryDate = $request->expiryDate[$i];
                    $productList->HSNCode = $request->HSNCode[$i];
                    $productList->qty = $request->qty[$i];               
                    $productList->actualQty = $request->actualQty[$i];               
                    $productList->rate = $request->unitPrice[$i];               
                    $productList->grossAmount = $request->grossAmount[$i];               
                    $productList->CGSTPercent = $request->CGSTPercent[$i];               
                    $productList->CGSTRs = $request->CGSTRs[$i];               
                    $productList->SGSTPercent = $request->SGSTPercent[$i];               
                    $productList->SGSTRs = $request->SGSTRs[$i];               
                    $productList->IGSTPercent = $request->IGSTPercent[$i];               
                    $productList->IGSTRs = $request->IGSTRs[$i];               
                    $productList->total = $request->total[$i];               
                    $productList->total = $request->total[$i];               
                    $productList->updated_by = Auth::user()->username;
                    if($productList->save())
                    {
                        $util = new Utility();
                        $util->updateLedger($inward->id, $productList->id, $request->forDate, $productList->productId, $productList->actualQty, 1);
                    }
                }
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
        return redirect('/inwards')->with("success","Product Save successfully..");
    }
   
    public function show($id)
    {
        $userType = Auth::user()->userType;
        $reqDepartmentId = Auth::user()->reqDepartmentId;
        if($userType == '91' || $userType == '61'  || $reqDepartmentId == '12')
        {
            $inward = Inward::join('store_vendors', 'inwards.vendorId', 'store_vendors.id')
            ->select('store_vendors.name', 'inwards.*')
            ->where('inwards.id', $id)
            ->first();

            $productList = InwardProductList::join('store_products', 'inward_product_lists.productId', 'store_products.id')
            ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
            ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
            ->join('store_units', 'store_products.unitId', 'store_units.id')
            ->join('store_halls', 'store_products.hallId', 'store_halls.id')
            ->join('store_racks', 'store_products.rackId', 'store_racks.id')
            ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
            ->select('inward_product_lists.*', 'store_products.name', 'store_categories.name as categoryName','store_halls.name as hallName', 'store_racks.name as rackName',
            'store_sub_categories.name as subCategoryName', 'store_products.color', 'store_products.size','store_shels.name as shelfName',
            'store_products.company','store_units.name as unitName')
            ->where('inward_product_lists.inwardId', $id)
            ->where('inward_product_lists.status', 1)
            ->get();

            $countInwards = Inward::whereActive(1)->count();
            $vendors = StoreVendor::where('active', 1)->orderBy('name')->pluck('name', 'id');

            return view('storeAdmin.inwards.show')->with(['vendors'=>$vendors,'countInwards'=>$countInwards, 'inward'=>$inward,'productList'=>$productList]);

            
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        
    }

    public function getVAddress($id)
    {
        return StoreVendor::where('id', $id)->value('address');
    }

    public function generateGRI($id)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $inward = Inward::join('store_vendors', 'inwards.vendorId', 'store_vendors.id')
        ->select('store_vendors.name', 'inwards.*')
        ->where('inwards.id', $id)
        ->first();

        $productList = InwardProductList::join('store_products', 'inward_product_lists.productId', 'store_products.id')
        ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->join('store_units', 'store_products.unitId', 'store_units.id')
        ->select('inward_product_lists.*', 'store_products.name', 'store_categories.name as categoryName', 
        'store_sub_categories.name as subCategoryName', 'store_products.color', 'store_products.size',
        'store_products.company','store_units.name as unitName')
        ->where('inward_product_lists.inwardId', $id)
        ->where('inward_product_lists.status', 1)
        ->get();
        return view('storeAdmin.inwardGRI.generate');
    }
   
    public function printInward($id)
    {
        $userType = Auth::user()->userType;
        if($userType == '91' || $userType == '61'  || $reqDepartmentId == '12')
        {
            $inward = Inward::join('store_vendors', 'inwards.vendorId', 'store_vendors.id')
            ->select('store_vendors.name','store_vendors.address', 'inwards.*')
            ->where('inwards.id', $id)
            ->first();
    
            $productList = InwardProductList::join('store_products', 'inward_product_lists.productId', 'store_products.id')
            ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
            ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
            ->join('store_units', 'store_products.unitId', 'store_units.id')
            ->join('store_halls', 'store_products.hallId', 'store_halls.id')
            ->join('store_racks', 'store_products.rackId', 'store_racks.id')
            ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
            ->select('inward_product_lists.*', 'store_products.name', 'store_categories.name as categoryName','store_halls.name as hallName', 'store_racks.name as rackName',
            'store_sub_categories.name as subCategoryName', 'store_products.color', 'store_products.size','store_shels.name as shelfName',
            'store_products.company','store_units.name as unitName')
            ->where('inward_product_lists.inwardId', $id)
            ->where('inward_product_lists.status', 1)
            ->get();
    
            $vendors = StoreVendor::where('active', 1)->orderBy('name')->pluck('name', 'id');
            $pdf = PDF::loadView('storeAdmin.inwards.printInward',compact('vendors','inward','productList'))->setPaper('a4', 'landscape');
            return $pdf->stream($inward->invoiceNo); 
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        
    }  

    public function productReturnList()
    {
        $productReturns = StoreProductReturn::join('contactus_land_pages', 'store_product_returns.branchId','contactus_land_pages.id')
        ->select('contactus_land_pages.branchName', 'store_product_returns.*')
        ->where('store_product_returns.status', 1)
        ->orderBy('store_product_returns.created_at', 'desc')
        ->get();
        return view('storeAdmin.productReturns.list')->with(['productReturns'=>$productReturns]);
    }
    
    public function productReturn()
    {
        $categories = StoreCategory::where('active', 1)->orderBy('name')->pluck('name', 'id');
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $productReturns = StoreProductReturn::where('status', 1)->count();
        return view('storeAdmin.productReturns.create')->with(['branches'=>$branches,'categories'=>$categories,'productReturns'=>$productReturns]);
    }

    public function productReturnStore(Request $request)
    {
        $productCount = count($request->productId);

        $productReturn = new StoreProductReturn;
        $productReturn->branchId = $request->branchId;
        $productReturn->returnBy = $request->returnBy;
        $productReturn->forDate = $request->forDate;
        $productReturn->remark = $request->remark;
        $productReturn->updated_by = Auth::user()->username;
        if($productReturn->save())
        {
            for($i=0; $i<$productCount; $i++)
            {

                $tempProduct = StoreProduct::find($request->productId[$i]);

                $list = new StoreProductReturnList;
                $list->returnId = $productReturn->id;
                $list->productId = $request->productId[$i];
                $list->unitId = $tempProduct->unitId;
                $list->currentStock = $tempProduct->stock;
                $list->currentRate = $tempProduct->productRate;
                $list->qty = $request->qty[$i];
                $list->updated_by = Auth::user()->username;
                if($list->save())
                {
                    $tempP = StoreProduct::where('id', $request->productId[$i])->first();
                    $tempP->stock = $tempP->stock + $request->qty[$i];
                    $tempP->updated_by = Auth::user()->username;
                    $tempP->save();

                }

            }
        }
        return redirect('/inwards/productReturnList')->with("success","Product Return Entry Save successfully..");
    }
}
