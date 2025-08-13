<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Utility;

use App\StoreOutward;
use App\StoreOutwardProdList;
use App\StoreProduct;
use App\StoreCategory;
use App\StoreRequisition;
use App\StoreRequisitionProduct;
use App\Department;
use App\ContactusLandPage;
use App\BranchStock;
use App\OutwardProductReturn;
use App\TempStoreProduct;
use App\RequisitionTracking;
use App\EventRequisition;
use App\EventRequisitionProduct;
use Auth;
use Image;
use PDF;
use DB;

class OutwardsController extends Controller
{
    public function getProducts($id)
    {
        $outward = StoreOutwardProdList::join('store_products', 'store_outward_prod_lists.actualProductId', 'store_products.id')
        ->select('store_products.*')
        ->where('store_outward_prod_lists.actualProductId', $id)->first();
        return view('storePartials.outward-products', ['products' => $outward]);
    }

    public function index(Request $request)
    {
        $userType = Auth::user()->userType;
        if ($userType != '91') {
            return redirect()->back()->withInput()->with("error", "You do not have permission to access this page.");
        }
    
        $forMonth = $request->forMonth;
        if($forMonth == '')
            $forMonth = date('Y-m');

        $receiptNo = $request->receiptNo;
        $requisitionNo = $request->requisitionNo;
        $branchId = $request->branchId;
        $productName = $request->productName;
    
        $branches = ContactusLandPage::where('active', 1)
            ->orderBy('branchName')
            ->pluck('branchName', 'id');
    
        $countOutward = StoreOutward::where('active', 1)->count();
    
        // Base query
        $outwardQuery = StoreOutward::join('contactus_land_pages', 'store_outwards.branchId', '=', 'contactus_land_pages.id')
                ->select('store_outwards.*', 'contactus_land_pages.shortName as newBranchName');

        if($forMonth != '')
        {
            $outwardQuery = $outwardQuery->whereDate('store_outwards.created_at', '>=', date('Y-m-01', strtotime($forMonth)))
                ->whereDate('store_outwards.created_at', '<=', date('Y-m-t', strtotime($forMonth)));
        }
        
        // Apply product name filter if provided
        if (!empty($productName)) {
            $outwardIds = StoreOutwardProdList::join('store_products', 'store_outward_prod_lists.actualProductId', '=', 'store_products.id')
            ->where('store_products.name', 'like', '%' . $productName . '%')
            ->whereColumn('store_outward_prod_lists.created_at', '>=', 'store_products.openingStockForDate')
            ->pluck('store_outward_prod_lists.outwardId');
    
            $outwardQuery->whereIn('store_outwards.id', $outwardIds);
        }
    
        // Apply other filters if provided
        if (!empty($receiptNo)) {
            $outwardQuery->where('store_outwards.receiptNo', $receiptNo);
        }
    
        if (!empty($requisitionNo)) {
            $outwardQuery->where('store_outwards.requisitionNo', $requisitionNo);
        }
    
        if (!empty($branchId)) {
            $outwardQuery->where('store_outwards.branchId', $branchId);
        }
    
        $outwards = $outwardQuery->orderBy('store_outwards.id', 'desc')->get();
    
        return view('storeAdmin.outwards.list')->with([
            'forMonth' => $forMonth,
            'branches' => $branches,
            'branchId' => $branchId,
            'requisitionNo' => $requisitionNo,
            'receiptNo' => $receiptNo,
            'productName' => $productName,
            'outwards' => $outwards,
            'countOutward' => $countOutward
        ]);
        
    }

    public function searchProduct($productName)
    {
        return StoreOutwardProdList::join('store_products', 'store_outward_prod_lists.actualProductId', '=', 'store_products.id')
        ->where('store_products.name', 'like', '%' . $productName . '%')
        ->pluck('store_outward_prod_lists.outwardId');
    }

    public function oldList(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

        if($request->forMonth == '')
            $forMonth=date('Y-m', strtotime('-1 month'));
        else
            $forMonth=$request->forMonth;

        $receiptNo = $request->receiptNo;
        $requisitionNo = $request->requisitionNo;
        $branchId = $request->branchId;
        $branches = ContactusLandPage::whereActive(1)->orderBy('branchName')->pluck('branchName', 'id');
        $countOutward = StoreOutward::whereActive(1)->count();

        $outwards = StoreOutward::join('contactus_land_pages', 'store_outwards.branchId', 'contactus_land_pages.id')
        ->select('store_outwards.*', 'contactus_land_pages.branchName as newBranchName')
        ->whereDate('store_outwards.created_at', '>=', date('Y-m-01', strtotime($forMonth)))
        ->whereDate('store_outwards.created_at', '<=', date('Y-m-t', strtotime($forMonth)))
        ->where('store_outwards.active', 1)
        ->orderBy('store_outwards.id','desc')
        ->get();

        return view('storeAdmin.outwards.oldList')->with(['forMonth'=>$forMonth,'branches'=>$branches,'branchId'=>$branchId,'requisitionNo'=>$requisitionNo,'receiptNo'=>$receiptNo,
        'outwards'=>$outwards, 'countOutward'=>$countOutward]); 
    }

    public function create()
    {
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
        $countOutward = StoreOutward::whereActive(1)->count();
        $categories = StoreCategory::where('active', 1)->pluck('name', 'id');
        return view('storeAdmin.outwards.create')->with(['categories'=>$categories, 'countOutward'=>$countOutward]);
    }

    public function store(Request $request)
    {       
        $userType = Auth::user()->userType;
        if($userType != '91')
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
     
        $prodCount = count($request->productId);
        $outward = new StoreOutward;
        $outward->forDate = $request->forDate;
        $outward->receiptNo = "Out-".date('Ymdhis');
        $outward->requisitionNo = $request->requisitionNo;
        $outward->dateOfRequisition = $request->dateOfRequisition;
        $outward->branchName = $request->branchName;
        $outward->requisitionFor = $request->requisitionFor;
        $outward->narration = $request->narration;
        $outward->updated_by = Auth::user()->username;  
        
        DB::beginTransaction();
        try 
        {
            if($outward->save())
            {
                for($i=0; $i<$prodCount; $i++)
                { 
                    $productList = new StoreOutwardProdList;
                    $productList->outwardId = $outward->id;
                    $productList->productId = $request->productId[$i];
                    $productList->qty = $request->qty[$i];
                    $productList->returnable = $request->returnable[$i];
                    $productList->dueDate = $request->dueDate[$i];               
                    $productList->updated_by = Auth::user()->username;
                    if($productList->save())
                    {
                        $product=StoreProduct::find($productList->productId);
                        $product->stock = $product->stock - $request->qty[$i];
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
            
            DB::commit();         
        } catch (\Exception $e) {
            DB::rollback();
        }
        
        return redirect('/outwards')->with("success","Product Outward Save successfully..");
    }

    public function show($id)
    {
        DB::beginTransaction();
        try 
        {
            $userType = Auth::user()->userType;
            if($userType != '91'){
                return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
            }

            $outward = StoreOutward::find($id);
            if($outward->normalOrEventReq == 1)
            {
                $requisition = StoreRequisition::join('contactus_land_pages','store_requisitions.branchId','contactus_land_pages.id')
                ->join('departments','store_requisitions.departmentId','departments.id')
                ->select('store_requisitions.*','contactus_land_pages.branchName as newBranchName','departments.name as departmentName')
                ->where('store_requisitions.requisitionNo', $outward->requisitionNo)
                ->first();

                $prodList = StoreRequisitionProduct::join('store_products', 'store_requisition_products.productId', 'store_products.id')
                ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
                ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
                ->join('store_units', 'store_products.unitId', 'store_units.id')
                ->join('store_halls', 'store_products.hallId', 'store_halls.id')
                ->join('store_racks', 'store_products.rackId', 'store_racks.id')
                ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
                ->select('store_halls.name as hallName', 'store_racks.name as rackName', 'store_products.image as prodImage', 'store_shels.name as shelfName', 
                'store_products.stock','store_products.name as productMName', 'store_categories.name as categoryName', 
                'store_sub_categories.name as subCategoryName', 'store_products.productRate','store_units.name as unitName', 
                'store_products.company', 'store_products.size','store_requisition_products.*')
                ->where('store_requisition_products.requisitionId', $requisition->id)
                ->get();
        
                $categories = StoreCategory::whereActive(1)->orderBy('name')->pluck('name', 'id');
                $departments = Department::whereActive(1)->orderBy('name')->pluck('name', 'id');
                $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        
                $returnProductList = OutwardProductReturn::join('store_products', 'outward_product_returns.productId', 'store_products.id')
                ->select('store_products.name', 'outward_product_returns.returnQty', 'outward_product_returns.created_at', 'outward_product_returns.updated_by')
                ->where('outward_product_returns.outwardId', $id)
                ->get();
                $countOutward = StoreOutward::whereActive(1)->count();
        
                $trackingHistories = RequisitionTracking::where('requisitionId', $requisition->id)->get();
            }
            else
            {
                $requisition = EventRequisition::where('requisitionNo', $outward->requisitionNo)->first();
                $prodList = EventRequisitionProduct::join('store_products', 'event_requisition_products.productId', 'store_products.id')
                ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
                ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
                ->join('store_units', 'store_products.unitId', 'store_units.id')
                ->join('store_halls', 'store_products.hallId', 'store_halls.id')
                ->join('store_racks', 'store_products.rackId', 'store_racks.id')
                ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
                ->select('store_halls.name as hallName', 'store_racks.name as rackName', 
                'store_shels.name as shelfName', 'store_products.stock','store_products.name as productMName', 
                'store_categories.name as categoryName', 
                'store_sub_categories.name as subCategoryName', 'store_products.returnStatus as retStatus',
                'store_products.productRate', 'store_products.image as prodImage','store_units.name as unitName', 'store_products.company', 
                'store_products.size','event_requisition_products.*', 'event_requisition_products.qty as receivedQty')
                ->where('event_requisition_products.eventReqId', $requisition->id)
                ->where('event_requisition_products.productType', 1)
                ->get();
        
        
                $categories = StoreCategory::whereActive(1)->orderBy('name')->pluck('name', 'id');
                $departments = Department::whereActive(1)->orderBy('name')->pluck('name', 'id');
                $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        
                $returnProductList = OutwardProductReturn::join('store_products', 'outward_product_returns.productId', 'store_products.id')
                ->select('store_products.name', 'outward_product_returns.returnQty', 'outward_product_returns.created_at', 'outward_product_returns.updated_by')
                ->where('outward_product_returns.outwardId', $id)
                ->get();
                $countOutward = StoreOutward::whereActive(1)->count();
        
                $trackingHistories = RequisitionTracking::where('requisitionId', $requisition->id)->get();
            }

            DB::commit();         
        } catch (\Exception $e) {
               DB::rollback();
               return redirect()->back()->withInput()->with("error","There is some issue / Data not found....");
        }
        return view('storeAdmin.outwards.show')->with(['trackingHistories'=>$trackingHistories,'returnProductList'=>$returnProductList,'countOutward'=>$countOutward,'outward'=>$outward,'prodList'=>$prodList, 'requisition'=>$requisition, 'departments'=>$departments, 'categories'=>$categories, 'branches'=>$branches]);
    }

    public function showDetails($id)
    {
        DB::beginTransaction();
        try 
        {
            $outward = StoreOutward::find($id);

            $requisition = StoreRequisition::where('requisitionNo', $outward->requisitionNo)->first();
            $prodList = StoreRequisitionProduct::join('store_products', 'store_requisition_products.productId', 'store_products.id')
            ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
            ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
            ->join('store_units', 'store_products.unitId', 'store_units.id')
            ->join('store_halls', 'store_products.hallId', 'store_halls.id')
            ->join('store_racks', 'store_products.rackId', 'store_racks.id')
            ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
            ->select('store_halls.name as hallName', 'store_racks.name as rackName', 'store_products.image', 'store_shels.name as shelfName', 
            'store_products.stock','store_products.name as productMName', 'store_categories.name as categoryName', 
            'store_sub_categories.name as subCategoryName', 'store_products.productRate','store_units.name as unitName', 'store_products.company', 'store_products.size','store_requisition_products.*')
            ->where('store_requisition_products.requisitionId', $requisition->id)
            ->get();
            $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
            $trackingHistories = RequisitionTracking::where('requisitionId', $requisition->id)->get();
            return view('storeAdmin.outwards.showDetails')->with(['trackingHistories'=>$trackingHistories,'outward'=>$outward,'prodList'=>$prodList, 'requisition'=>$requisition,  'branches'=>$branches]);
            DB::commit();         
        } catch (\Exception $e) {
               DB::rollback();
               return redirect()->back()->withInput()->with("error","There is some issue / Data not found....");
        }
    }

    public function updatedDeliveryHistory(Request $request)
    {
        $outward = StoreOutward::where('id', $request->outwardId)->first();
        if(!RequisitionTracking::where('requisitionId', $request->requisitionId)->where('remark', "Out for Delivery")->first())
        {
            $requisitionTracking = new RequisitionTracking;
            $requisitionTracking->requisitionId = $request->requisitionId;
            $requisitionTracking->userId = Auth::user()->id;
            $requisitionTracking->name = Auth::user()->name;
            $requisitionTracking->remark = "Out for Delivery";
            $requisitionTracking->trackingType = 4;
        }
        else
        {

            $requisitionTracking = new RequisitionTracking;
            $requisitionTracking->requisitionId = $request->requisitionId;
            $requisitionTracking->userId = Auth::user()->id;
            $requisitionTracking->name = Auth::user()->name;
            if($request->reqStatus == 1)
            {
                $requisitionTracking->remark = "Outward Delivered";
                $requisitionTracking->trackingType = 6;
            }
            else
            {
                $requisitionTracking->remark = "On the Way";
                $requisitionTracking->trackingType = 5;
            }
           
        }

        $requisitionTracking->userComment = $request->userComment;

        if(!empty($request->file('image')))
        {
            $originalImage= $request->file('image');
            $Image = Auth::user()->id.date('Ymdhis').'.'.$originalImage->getClientOriginalExtension();
            $image = Image::make($originalImage);
            $originalPath =  public_path()."/storeAdmin/requisitionTrackingImages/";
            $image->resize(700,700);
            $image->save($originalPath.$Image);
            $requisitionTracking->image=$Image;
        }

        DB::beginTransaction();
        try 
        {

            $requisitionTracking->normalOrEventReq = $outward->normalOrEventReq;
            $requisitionTracking->updated_by = Auth::user()->username;
            if($requisitionTracking->save()) 
            {
                if($request->reqStatus == 1)
                {
                    if($outward->normalOrEventReq == 1)
                    {
                        $requisition = StoreRequisition::find($request->requisitionId);
                        $requisition->status = $request->reqStatus;
                        $requisition->updated_by = Auth::user()->username;
                        $products = StoreRequisitionProduct::where('requisitionId', $requisition->id)->where('status', 1)->get();
                        foreach($products as $product)
                        {
                            $product->status=1;
                            $product->save();
                            if(Auth::user()->reqBranchId != '')
                            {
                                $bStock = BranchStock::where('userId', Auth::user()->id)->where('productId', $product->productId)->first();
                                if(!$bStock)
                                    $bStock = new BranchStock;
    
                                $bStock->userId = Auth::user()->id;
                                $bStock->branchId = Auth::user()->reqBranchId;
                                $bStock->productId = $product->productId;
                                $bStock->stock = $bStock->stock + $product->receivedQty;
                                $bStock->updated_by = Auth::user()->username;
                                $bStock->returnToStore = StoreProduct::where('id', $product->productId)->value('returnStatus');
                                $bStock->save();
                            }
                        }
    
                        $requisition->save();
                    }
                    else
                    {
                        $requisition = EventRequisition::find($request->requisitionId);
                        $requisition->status = $request->reqStatus;
                        $requisition->updated_by = Auth::user()->username;
                        $products = EventRequisitionProduct::where('eventReqId', $requisition->id)->where('status', 1)->get();
                        foreach($products as $product)
                        {
                            if(Auth::user()->reqBranchId != '')
                            {
                                $bStock = BranchStock::where('userId', Auth::user()->id)->where('productId', $product->productId)->first();
                                if(!$bStock)
                                    $bStock = new BranchStock;
    
                                $bStock->userId = Auth::user()->id;
                                $bStock->branchId = Auth::user()->reqBranchId;
                                $bStock->productId = $product->productId;
                                $bStock->stock = $bStock->stock + $product->receivedQty;
                                $bStock->updated_by = Auth::user()->username;
                                $bStock->returnToStore = StoreProduct::where('id', $product->productId)->value('returnStatus');
                                $bStock->save();
                            }
                        }
    
                        $requisition->save();
                    }
                    
                   
                }
                DB::commit();
                return redirect()->back()->withInput()->with("success","Status Updated Successfully..");

            }
        
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with("error","There is some issue.....");
        }


        // $tracking = new StoreOutwardTracking;    
        // $tracking->outwardId = $request->outwardId;
        // $tracking->userId = $user->id;
        // $tracking->name = $user->name;
        // $tracking->remark = $request->remark;
        // $tracking->updated_by = $user->username;
        // if($tracking->save())
        // {
        //     if($request->reqStatus == 1)
        //     {
        //         $requisition = StoreRequisition::find($request->requisitionId);
        //         $requisition->status = $request->reqStatus;
        //         $requisition->updated_by = Auth::user()->username;
        //         $flag=0;
        //         $products = StoreRequisitionProduct::where('requisitionId', $requisition->id)->where('status', 1)->get();
        //         foreach($products as $product)
        //         {
        //             if(Auth::user()->reqBranchId != '')
        //             {
        //                 $bStock = BranchStock::where('userId', $user->id)->where('productId', $product->productId)->first();
        //                 if(!$bStock)
        //                     $bStock = new BranchStock;

        //                 $bStock->userId = Auth::user()->id;
        //                 $bStock->branchId = Auth::user()->reqBranchId;
        //                 $bStock->productId = $product->productId;
        //                 $bStock->stock = $bStock->stock + $product->receivedQty;
        //                 $bStock->updated_by = Auth::user()->username;
        //                 $bStock->returnToStore = StoreProduct::where('id', $product->productId)->value('returnStatus');
        //                 $bStock->save();
        //             }
        //         }

        //         $requisition->save();
        //     }
        // }

        // return redirect()->back()->withInput()->with("success","Status Updated Successfully..");


    }

    public function printOutward($id)
    {
        DB::beginTransaction();
        try 
        {

            $outward = StoreOutward::find($id);

            if($outward->normalOrEventReq == 1)
            {
                $requisition = StoreRequisition::join('departments', 'store_requisitions.departmentId', 'departments.id')
                ->select('departments.name as departmentName', 'store_requisitions.*')
                ->where('store_requisitions.requisitionNo', $outward->requisitionNo)
                ->first();
        
                $prodList = StoreRequisitionProduct::join('store_products', 'store_requisition_products.productId', 'store_products.id')
                ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
                ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
                ->join('store_units', 'store_products.unitId', 'store_units.id')
                ->join('store_halls', 'store_products.hallId', 'store_halls.id')
                ->join('store_racks', 'store_products.rackId', 'store_racks.id')
                ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
                ->select('store_halls.name as hallName', 'store_racks.name as rackName', 'store_products.image', 'store_shels.name as shelfName', 
                'store_products.stock','store_products.name as productMName', 'store_categories.name as categoryName', 
                'store_sub_categories.name as subCategoryName', 'store_products.productRate','store_units.name as unitName', 
                'store_products.company', 'store_products.size','store_requisition_products.*')
                ->where('store_requisition_products.requisitionId', $requisition->id)
                ->get();
                
                $file = $requisition->requisitionNo.'_Req.pdf';
                $pdf = PDF::loadView('storeAdmin.outwards.printOutward',compact('prodList','outward','requisition'))->setPaper('a4');
                return $pdf->stream($file); 
            }
            else
            {
                $requisition = EventRequisition::join('departments', 'event_requisitions.departmentId', 'departments.id')
                ->select('departments.name as departmentName', 'event_requisitions.*')
                ->where('event_requisitions.requisitionNo', $outward->requisitionNo)
                ->first();
        
                $prodList = EventRequisitionProduct::join('store_products', 'event_requisition_products.productId', 'store_products.id')
                ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
                ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
                ->join('store_units', 'store_products.unitId', 'store_units.id')
                ->join('store_halls', 'store_products.hallId', 'store_halls.id')
                ->join('store_racks', 'store_products.rackId', 'store_racks.id')
                ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
                ->select('store_halls.name as hallName', 'store_racks.name as rackName', 'store_products.image', 'store_shels.name as shelfName', 
                'store_products.stock','store_products.name as productMName', 'store_categories.name as categoryName', 
                'store_sub_categories.name as subCategoryName', 'store_products.productRate','store_units.name as unitName', 
                'store_products.company', 'store_products.size','event_requisition_products.*', 'event_requisition_products.qty as receivedQty')
                ->where('event_requisition_products.eventReqId', $requisition->id)
                ->get();
                
                $file = $requisition->requisitionNo.'_Req.pdf';
                $pdf = PDF::loadView('storeAdmin.outwards.printOutward',compact('prodList','outward','requisition'))->setPaper('a4');
                return $pdf->stream($file); 
            }
            DB::commit();         
        } catch (\Exception $e) {
               DB::rollback();
               return redirect()->back()->withInput()->with("error","There is some issue / Data not found....");
        }
    }

    public function productReturn($id)
    {
        DB::beginTransaction();
        try 
        {

            $userType = Auth::user()->userType;
            if($userType != '91'){
                return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
            }

            $outward = StoreOutward::find($id);
            if($outward->normalOrEventReq == 1)
            {
                $requisition = StoreRequisition::where('requisitionNo', $outward->requisitionNo)->first();
                $prodList = StoreRequisitionProduct::join('store_products', 'store_requisition_products.productId', 'store_products.id')
                ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
                ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
                ->join('store_units', 'store_products.unitId', 'store_units.id')
                ->join('store_halls', 'store_products.hallId', 'store_halls.id')
                ->join('store_racks', 'store_products.rackId', 'store_racks.id')
                ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
                ->select('store_halls.name as hallName', 'store_racks.name as rackName', 'store_products.image as prodImage', 
                'store_shels.name as shelfName', 
                'store_products.stock','store_products.name as productMName', 'store_categories.name as categoryName', 
                'store_sub_categories.name as subCategoryName', 'store_products.productRate','store_units.name as unitName', 
                'store_products.company', 'store_products.size','store_requisition_products.*')
                ->where('store_requisition_products.requisitionId', $requisition->id)
                ->get();
            }
            else
            {
                $requisition = EventRequisition::join('departments', 'event_requisitions.departmentId', 'departments.id')
                ->select('departments.name as departmentName', 'event_requisitions.*')
                ->where('event_requisitions.id', $outward->requisitionId)
                ->first();

                $prodList = EventRequisitionProduct::join('store_products', 'event_requisition_products.productId', 'store_products.id')
                ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
                ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
                ->join('store_units', 'store_products.unitId', 'store_units.id')
                ->join('store_halls', 'store_products.hallId', 'store_halls.id')
                ->join('store_racks', 'store_products.rackId', 'store_racks.id')
                ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
                ->select('store_halls.name as hallName', 'store_racks.name as rackName', 'store_products.image as prodImage', 'store_shels.name as shelfName', 
                'store_products.stock','store_products.name as productMName', 'store_categories.name as categoryName', 
                'store_sub_categories.name as subCategoryName', 'store_products.productRate','store_units.name as unitName', 
                'store_products.company', 'store_products.size','event_requisition_products.*', 'event_requisition_products.qty as requiredQty')
                ->where('event_requisition_products.eventReqId', $requisition->id)
                ->get();
                
            }
            
            $categories = StoreCategory::whereActive(1)->orderBy('name')->pluck('name', 'id');
            $departments = Department::whereActive(1)->orderBy('name')->pluck('name', 'id');
            $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');

            $countOutward = StoreOutward::whereActive(1)->count();
            return view('storeAdmin.outwards.ProductReturn')->with(['countOutward'=>$countOutward,'outward'=>$outward,'prodList'=>$prodList, 'requisition'=>$requisition, 'departments'=>$departments, 'categories'=>$categories, 'branches'=>$branches]);
            DB::commit();         
        } catch (\Exception $e) {
               DB::rollback();
               return redirect()->back()->withInput()->with("error","There is some issue / Data not found....");
        }
    }

    public function updateReturnProduct(Request $request)
    {
        DB::beginTransaction();
        try 
        {
            $productCount = count($request->productId);
            for($i=0; $i<$productCount; $i++)
            {
                if($request->returnQty[$i] != 0)
                {
                    $productReturn = StoreOutwardProdList::where('outwardId', $request->outwardId)->where('productId', $request->outwardProductId[$i])->first();
                    if($productReturn)
                    {
                        $product = StoreProduct::find($request->productId[$i]);
                        if($product)
                        {   
                            $productReturn->returnQty = $request->returnQty[$i];
                            $productReturn->updated_by = Auth::user()->username;
                            if($productReturn->save())
                            {
                                $returnProd = new OutwardProductReturn;
                                $returnProd->productId = $request->productId[$i];
                                $returnProd->outwardId = $request->outwardId;
                                $returnProd->actualQty = $request->qty[$i];
                                $returnProd->returnQty = $request->returnQty[$i];
                                $returnProd->updated_by = Auth::user()->username;
                                $returnProd->save();

                                $util = new Utility();
                                $util->updateLedger($request->outwardId, $productReturn->id, date('Y-m-d'), $request->productId[$i], $request->returnQty[$i], 3);
                            }
                        }
                    
                    }
                }
            }   

            DB::commit();
            // all good
            return redirect()->back()->withInput()->with("success","Product Return Updated sucessfully..");
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }
    }
}
