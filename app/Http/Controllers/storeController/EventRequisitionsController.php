<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Utility;

use App\StoreProduct;
use App\ContactusLandPage;
use App\StoreCategory;
use App\Department;
use App\StoreOutward;
use App\StoreOutwardProdList;
use App\StoreUser;
use App\StoreUnit;
use App\EventRequisition;
use App\EventRequisitionProduct;
use App\RequisitionTracking;
use App\StoreProductLedger;
use DB;
use Auth;
use PDF;
use Image;

class EventRequisitionsController extends Controller
{
    public function index(Request $request)
    {
        $userType = Auth::user()->userType;
        $userId = Auth::user()->id;
        $search = $request->search;
        if($userType == '91' || $userType == '501')
        {
            $temps = EventRequisition::join('contactus_land_pages', 'event_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'event_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.shortName as branchName', 'departments.name as departmentName', 'event_requisitions.*')
            ->whereIn('event_requisitions.status', [0, 3, 5])
            ->where(function  ($query) use ($search) {
                $query->where('event_requisitions.requisitionNo', 'like', '%' . $search . '%')
                      ->orwhere('event_requisitions.requisitionerName', 'like', '%' . $search . '%')
                      ->orwhere('event_requisitions.requisitionFor', 'like', '%' . $search . '%');
            })->orderByRaw('FIELD(event_requisitions.status,0,3,5)')
            ->orderBy('event_requisitions.id', 'desc');

            $reqCount = $temps->count();
            $requisitions = $temps->paginate(20);
        }
        else
        {
            $temps = EventRequisition::join('contactus_land_pages', 'event_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'event_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.shortName as branchName', 'departments.name as departmentName', 'event_requisitions.*')
            ->where('event_requisitions.raisedById', $userId)
            ->whereIn('event_requisitions.status', [0,3,5])
            ->where(function ($query) use ($search) {
                $query->where('event_requisitions.requisitionNo', 'like', '%' . $search . '%')
                      ->orwhere('event_requisitions.requisitionerName', 'like', '%' . $search . '%')
                      ->orwhere('event_requisitions.requisitionFor', 'like', '%' . $search . '%');
            })
            ->orderByRaw('FIELD(event_requisitions.status,0,3,5)')
            ->orderBy('event_requisitions.id', 'desc');

            $reqCount = $temps->count();
            $requisitions = $temps->paginate(20);
        }

        return view('storeAdmin.eventRequisitions.list')->with(['search'=>$search,'reqCount'=>$reqCount, 'requisitions'=>$requisitions]);
    }

    public function completedReqList(Request $request)
    {
        $userType = Auth::user()->userType;
        $userId = Auth::user()->id;
        $search = $request->search;
        if($userType == '91' || $userType == '501')
        {
            $temps = EventRequisition::join('contactus_land_pages', 'event_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'event_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.branchName', 'departments.name as departmentName', 'event_requisitions.*')
            ->whereIn('event_requisitions.status', [1,2,4])
            ->where(function ($query) use ($search) {
                $query->where('event_requisitions.requisitionNo', 'like', '%' . $search . '%')
                      ->orwhere('event_requisitions.requisitionerName', 'like', '%' . $search . '%')
                      ->orwhere('event_requisitions.requisitionFor', 'like', '%' . $search . '%');
            })
            ->orderByRaw('FIELD(event_requisitions.status,1,2,4)')
            ->orderBy('event_requisitions.id', 'desc');

            $reqCount = $temps->count();
            $requisitions = $temps->paginate(20);
        }
        else
        {
            $temps = EventRequisition::join('contactus_land_pages', 'event_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'event_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.branchName', 'departments.name as departmentName', 'event_requisitions.*')
            ->where('event_requisitions.raisedById', $userId)
            ->whereIn('event_requisitions.status', [1,2,4])
            ->where(function ($query) use ($search) {
                $query->where('event_requisitions.requisitionNo', 'like', '%' . $search . '%')
                      ->orwhere('event_requisitions.requisitionerName', 'like', '%' . $search . '%')
                      ->orwhere('event_requisitions.requisitionFor', 'like', '%' . $search . '%');
            })
            ->orderByRaw('FIELD(event_requisitions.status,1,2,4)')
            ->orderBy('event_requisitions.id', 'desc');

            $reqCount = $temps->count();
            $requisitions = $temps->paginate(20);
        }

        return view('storeAdmin.eventRequisitions.completedList')->with(['search'=>$search,'reqCount'=>$reqCount, 'requisitions'=>$requisitions]);
    }
    
    public function rejectedList(Request $request)
    {
        $userType = Auth::user()->userType;
        $userId = Auth::user()->id;
        $search = $request->search;
        if($userType == '91' || $userType == '501')
        {
            $temps = EventRequisition::join('contactus_land_pages', 'event_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'event_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.branchName', 'departments.name as departmentName', 'event_requisitions.*')
            ->whereIn('event_requisitions.status', [4])
            ->where(function ($query) use ($search) {
                $query->where('event_requisitions.requisitionNo', 'like', '%' . $search . '%')
                      ->orwhere('event_requisitions.requisitionerName', 'like', '%' . $search . '%')
                      ->orwhere('event_requisitions.requisitionFor', 'like', '%' . $search . '%');
            })
            ->orderByRaw('FIELD(event_requisitions.status,4)')
            ->orderBy('event_requisitions.id', 'desc');

            $reqCount = $temps->count();
            $requisitions = $temps->paginate(20);
        }
        else
        {
            $temps = EventRequisition::join('contactus_land_pages', 'event_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'event_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.branchName', 'departments.name as departmentName', 'event_requisitions.*')
            ->where('event_requisitions.raisedById', $userId)
            ->whereIn('event_requisitions.status', [4])
            ->where(function ($query) use ($search) {
                $query->where('event_requisitions.requisitionNo', 'like', '%' . $search . '%')
                      ->orwhere('event_requisitions.requisitionerName', 'like', '%' . $search . '%')
                      ->orwhere('event_requisitions.requisitionFor', 'like', '%' . $search . '%');
            })
            ->orderByRaw('FIELD(event_requisitions.status,4)')
            ->orderBy('event_requisitions.id', 'desc');

            $reqCount = $temps->count();
            $requisitions = $temps->paginate(20);
        }

        return view('storeAdmin.eventRequisitions.rejectedList')->with(['search'=>$search,'reqCount'=>$reqCount, 'requisitions'=>$requisitions]);
    }  

    public function masterProductList()
    {
        $userId = Auth::user()->id;      
        $catIds = StoreUser::where('userId', $userId)->where('active', 1)->pluck('categoryId');
        $categories = StoreCategory::whereIn('id', $catIds)->whereActive(1)->orderBy('name')->pluck('id');
       
        $products = StoreProduct::join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->select('store_categories.name as categoryName', 'store_sub_categories.name as subCategoryName', 'store_products.*')
        ->whereIn('store_products.categoryId', $categories)
        ->where('store_products.active', 1)
        ->orderBy('store_products.id', 'desc')
        ->get();
        return view('storeAdmin.eventRequisitions.productList')->with(['products'=>$products]);
    }

    public function create()
    {
        $categories = StoreCategory::whereActive(1)->orderBy('name')->pluck('name', 'id');

        $departments = Department::whereActive(1)->orderBy('name')->pluck('name', 'id');
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $units = StoreUnit::where('active', 1)->orderBy('name')->pluck('name', 'id');
        $flag=1;
        return view('storeAdmin.eventRequisitions.raiseRequisition')->with(['units'=>$units,'flag'=>$flag,'departments'=>$departments, 'categories'=>$categories,'branches'=>$branches]);
    }

    public function store(Request $request)
    {
        if(count($request->productName) == 0)
        {
            return redirect()->back()->withInput()->with("error","Please Select At least 1 Product");
        }

        if (in_array("0", $request->qty))
        {
            return redirect()->back()->withInput()->with("error","Enter minimum 1 Qty of each Product....");
        }
        $lastRequisitionNo = EventRequisition::orderBy('reqTemp','desc')->value('reqTemp');
        if(!$lastRequisitionNo)
            $lastRequisitionNo=1;
        else
            $lastRequisitionNo=$lastRequisitionNo+1;


        $words = explode(" ", Department::where('id', $request->departmentId)->value('name'));
        $dept = "";
        foreach ($words as $w) {
            $dept .= mb_substr($w, 0, 3);
        }

        $shortCutTest = ContactusLandPage::where('id', $request->branchId)->first();('shortName');
        if($shortCutTest->shortName == '')
            $shortCut = mb_substr($shortCutTest->name, 0, 5);
        else
            $shortCut = $shortCutTest->shortName;

        $eventReq = new EventRequisition;
        $eventReq->raisedById = Auth::user()->id;
        $eventReq->reqTemp =$lastRequisitionNo;
        $eventReq->requisitionNo = 'EVENT/'.$shortCut.'/'.$dept.'/'.date('Y').'/'.date('M').'/'.$lastRequisitionNo;
        $eventReq->branchId = $request->branchId;
        $eventReq->requisitionDate = $request->requisitionDate;
        $eventReq->requisitionerName = $request->requisitionerName;
        $eventReq->departmentId = $request->departmentId;
        $eventReq->dateOfRequirement = $request->dateOfRequirement;
        $eventReq->sevirity = $request->sevirity;
        $eventReq->requisitionFor = $request->requisitionFor;
        $eventReq->deliverToBranchId = $request->deliveryTo;
        $eventReq->authorityName = $request->authorityName;
        $eventReq->active = 1;
        $eventReq->updated_by = Auth::user()->username;
        DB::beginTransaction();
        try 
        {
            if($eventReq->save())
            {
                $productCount = count($request->productId);
                $productTypeArray=[];
                for($j=0; $j<$productCount; $j++)
                {
                    $eventProduct = new EventRequisitionProduct;
                    $eventProduct->eventReqId = $eventReq->id;
                    $eventProduct->productId = $request->productId[$j];
                    $eventProduct->productName = $request->productName[$j];
                    $eventProduct->unitId = StoreUnit::where('name',$request->unitName[$j])->value('id');
                    $eventProduct->description = $request->description[$j];

                    $testImage = 'productImage_'.$request->productName[$j];
                    if(isset($request->$testImage))
                    {
                        if(!empty($request->$testImage))
                        {
                            $originalImage= $request->$testImage[0];
                            $Image = $request->productName[$j].date('Ymdhis').'.'.$originalImage->getClientOriginalExtension();
                            $image = Image::make($originalImage);
                            $originalPath =  public_path()."/storeAdmin/otherProductImages/";
                            $image->resize(700,700);
                            $image->save($originalPath.$Image);
                            $eventProduct->image = $Image;
                        }
                    }

                    $eventProduct->productType = $request->type[$j];
                    $eventProduct->reqTimeStock = $request->stock[$j];
                    $eventProduct->qty = $request->qty[$j];
                    $eventProduct->updated_by =  Auth::user()->username;
                    $eventProduct->save();

                    array_push($productTypeArray, $request->type[$j]);
                } 

               $productType = array_unique($productTypeArray);
            }

            $tempReq = EventRequisition::find($eventReq->id);

            if (in_array('1', $productType) && (in_array('2', $productType) || in_array('3', $productType))) 
                $tempReq->productListStatus=2;
            else
                $tempReq->productListStatus=1;

            $tempReq->save();

            $requisitionTracking = new RequisitionTracking;
            $requisitionTracking->requisitionId = $eventReq->id;
            $requisitionTracking->userId =  Auth::user()->id;
            $requisitionTracking->name = Auth::user()->name;
            $requisitionTracking->remark = "Requisition Raised";
            $requisitionTracking->trackingType = 1;
            $requisitionTracking->normalOrEventReq = 2;
            $requisitionTracking->updated_by = Auth::user()->username;
            $requisitionTracking->save(); 

            DB::commit();  
            return redirect('/eventRequisitions')->with("success","Event Requisition Raise Successfully...");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with("success","there is some issue...");       
        }
    } 

    public function edit($id)
    {
        $userType = Auth::user()->name;

        // $outwards = StoreOutward::where('normalOrEventReq', 2)->where('created_at', '>=', '2025-06-01 00:00:00')->get();
        // foreach($outwards as $outward)
        // {
        //     $outwardProductList = StoreOutwardProdList::where('outwardId', $outward->id)->get();
        //     if($outwardProductList)
        //     {
        //         foreach($outwardProductList as $list)
        //         {
        //             $ledger = StoreProductLedger::where('primaryTransactionId', $outward->id)
        //             ->where('transactionId', $list->id)
        //             ->where('forDate', date('Y-m-d', strtotime($list->created_at)))
        //             ->first();

        //             if(!$ledger)
        //                 $ledger = new StoreProductLedger;

        //             $ledger->primaryTransactionId = $outward->id;
        //             $ledger->transactionId = $list->id;
        //             $ledger->forDate = date('Y-m-d', strtotime($list->created_at));
        //             $ledger->productId = $list->actualProductId;
        //             $ledger->outwardQty = $list->qty;
        //             $ledger->type = 2;
        //             $ledger->status = 1;
        //             $ledger->updated_by = Auth::user()->username;
        //             $ledger->created_at =  $list->created_at;   
        //             $ledger->updated_at =  $list->created_at;  
        //             $ledger->save();
        //             // return $ledger;
        //         }
        //     }      
        // }

        // return 'done';
        $requisition = EventRequisition::join('contactus_land_pages', 'event_requisitions.branchId', 'contactus_land_pages.id')
        ->join('departments', 'event_requisitions.departmentId', 'departments.id')
        ->select('contactus_land_pages.shortName', 'departments.name as departmentName', 'event_requisitions.*')
        ->where('event_requisitions.id', $id)
        ->first();

        if($userType == '91')
        {
            $requisitionTracking = new RequisitionTracking;
            $requisitionTracking->requisitionId = $requisition->id;
            $requisitionTracking->userId =  Auth::user()->id;
            $requisitionTracking->name = Auth::user()->name;
            $requisitionTracking->remark = "Requisition Viewed";
            $requisitionTracking->trackingType = 2;
            $requisitionTracking->normalOrEventReq = 2;
            $requisitionTracking->updated_by = Auth::user()->username;
            $requisitionTracking->save(); 
        }
        $deliverToBranch = ContactusLandPage::where('id', $requisition->deliverToBranchId)->value('shortName'); 

        $prodList = EventRequisitionProduct::join('store_products', 'event_requisition_products.productId', 'store_products.id')
        ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->join('store_units', 'store_products.unitId', 'store_units.id')
        ->join('store_halls', 'store_products.hallId', 'store_halls.id')
        ->join('store_racks', 'store_products.rackId', 'store_racks.id')
        ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
        ->select('store_halls.name as hallName', 'store_racks.name as rackName', 
        'store_shels.name as shelfName', 'store_products.stock','store_products.name as productName', 'store_categories.name as categoryName', 
        'store_sub_categories.name as subCategoryName', 'store_products.returnStatus as retStatus',
        'store_products.productRate', 'store_units.name as unitName', 'store_products.company', 
        'store_products.size','event_requisition_products.*')
        ->where('event_requisition_products.eventReqId', $id)
        ->where('event_requisition_products.productType', 1)
        ->get();

        $categories = StoreCategory::whereActive(1)->orderBy('name')->pluck('name', 'id');
        $departments = Department::whereActive(1)->orderBy('name')->pluck('name', 'id');
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        return view('storeAdmin.outwards.eventEdit')->with(['deliverToBranch'=>$deliverToBranch,'prodList'=>$prodList, 'requisition'=>$requisition, 'departments'=>$departments, 'categories'=>$categories, 'branches'=>$branches]);
    }

    public function update(Request $request, $id)
    {   
        // return $request->all();
        $productCount = count($request->productId);     
     
        if (in_array("0", $request->status))
            return redirect()->back()->withInput()->with("error","Pending / Hold Status Found, Please update every product status carefully..");
        
        if($productCount)
        {
            $requisition = EventRequisition::find($id);

            $outward = new StoreOutward;
            $outward->forDate = date('Y-m-d');
            $outward->receiptNo = "Out-".date('Ymdhis');
            $outward->requisitionId =  $id;
            $outward->requisitionNo = $requisition->requisitionNo;
            $outward->dateOfRequisition = $requisition->requisitionDate;
            $outward->branchId = $requisition->branchId;
            $outward->branchName = ContactusLandPage::where('id', $requisition->branchId)->value('branchName');
            $outward->requisitionFor = $requisition->requisitionFor;
            $outward->narration = $request->storeRemark;
            $outward->normalOrEventReq = 2;
            $outward->updated_by = Auth::user()->username;        
            $flag=0;
            DB::beginTransaction();
            try 
            {
                if($outward->save())
                {
                    for($i=0; $i<$productCount; $i++)
                    { 
                        $product=StoreProduct::find($request->productId[$i]);
                        $productStock = $util->getCurrentProductStock($request->productId[$i]);
                        $reqProduct = EventRequisitionProduct::find($request->productListId[$i]);
                        $productList = new StoreOutwardProdList;
                        if($request->requiredQty[$i] <= $productStock)
                        {                            
                            $productList->outwardId = $outward->id;
                            $productList->actualProductId = $request->productId[$i];
                            $productList->productId = $request->productListId[$i];
                            $productList->qty = $request->requiredQty[$i];
                            $productList->updated_by = Auth::user()->username;
                            $productList->normalOrEventReq = 2;

                            $reqProduct->qty=$request->requiredQty[$i];
                            $reqProduct->currentRate=$product->productRate;
                            $reqProduct->returnStatus=$request->returnStatus[$i];
                            $reqProduct->dueDate=$request->dueDate[$i];
                            
                            $reqProduct->status=$request->status[$i];
                            $reqProduct->storeRejectReason=$request->productReason[$i];
                            $reqProduct->updated_by=Auth::user()->username;
                        }
                        else
                        {
                            $reqProduct->qty=0;
                            $reqProduct->currentRate=$product->productRate;
                            
                            $reqProduct->status=3;
                            $reqProduct->updated_by=Auth::user()->username; 
                            $flag=1;
                        }

                        if($request->status[$i] == '3')
                        {
                            $flag=1;
                        }
                        else
                        {
                            $reqProduct->qty=$request->requiredQty[$i];
                            $reqProduct->dueDate=$request->dueDate[$i];
                        }

                        if($productList->save())
                        {
                            $reqProduct->save();
                            if($request->requiredQty[$i] <= $productStock)
                            {
                                if($request->status[$i] == 1)
                                {
                                    $util = new Utility();
                                    $util->updateLedger($outward->id, $productList->id, date('Y-m-d'), $product->id, $request->requiredQty[$i], 2);                                 
                                }                                   

                            }
                            
                           $product->save();
                        }
                    }

                    
                    if($flag==1)
                        $requisition->status = 5;
                    else
                        $requisition->status = $request->reqStatus;
        
                    $requisition->storeRemark = $request->storeRemark;
                    $requisition->updated_by=Auth::user()->username;
                    if($requisition->save())
                    {
                        $requisitionTracking = new RequisitionTracking;
                        $requisitionTracking->requisitionId = $requisition->id;
                        $requisitionTracking->outwardId = $outward->id;
                        $requisitionTracking->userId = Auth::user()->id;
                        $requisitionTracking->name = Auth::user()->name;
                        $requisitionTracking->remark = "Outward Generated";
                        $requisitionTracking->trackingType = 3;
                        $requisitionTracking->normalOrEventReq = 2;
                        $requisitionTracking->updated_by = Auth::user()->username;
                        $requisitionTracking->save(); 

                    }
                }

                DB::commit();  
                return redirect('/outwards')->with("success","Outward Generated successfully..");
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->withInput()->with("success","there is some issue...");       
            }
        }
        else
        {
            return redirect()->back()->withInput()->with("error","Not selected any Product...");
        }
       
    }
    
    public function show($id)
    {
       $userType= Auth::user()->userType;
        $requisition = EventRequisition::join('contactus_land_pages', 'event_requisitions.branchId', 'contactus_land_pages.id')
        ->join('departments', 'event_requisitions.departmentId', 'departments.id')
        ->select('contactus_land_pages.shortName', 'departments.name as departmentName', 'event_requisitions.*')
        ->where('event_requisitions.id', $id)
        ->first();


        if($userType == '91')
        {
            $requisitionTracking = new RequisitionTracking;
            $requisitionTracking->requisitionId = $requisition->id;
            $requisitionTracking->userId =  Auth::user()->id;
            $requisitionTracking->name = Auth::user()->name;
            $requisitionTracking->remark = "Requisition Viewed";
            $requisitionTracking->trackingType = 2;
            $requisitionTracking->normalOrEventReq = 2;
            $requisitionTracking->updated_by = Auth::user()->username;
            $requisitionTracking->save(); 
        }

        $deliverToBranch = ContactusLandPage::where('id', $requisition->deliverToBranchId)->value('shortName'); 

        $userType = Auth::user()->userType;
        if($userType == '91')
        {
            $requisition->viewedStatus = 1;
            $requisition->viewedTimestamp = date('Y-m-d H:i:s');
            $requisition->userBy = Auth::user()->username;
            $requisition->save();
        }
        
        $prodList = EventRequisitionProduct::where('eventReqId', $id)->get();
       
        $departments = Department::whereActive(1)->orderBy('name')->pluck('name', 'id');
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $outward = StoreOutward::where('requisitionNo', $requisition->requisitionNo)->first();

        $requisitionTracking = RequisitionTracking::where('requisitionId', $requisition->id)->get();
        return view('storeAdmin.eventRequisitions.show')->with(['deliverToBranch'=>$deliverToBranch,'requisitionTracking'=>$requisitionTracking, 'outward'=>$outward, 
        'prodList'=>$prodList, 'requisition'=>$requisition, 'departments'=>$departments, 'branches'=>$branches]);
    }

    public function deactivate($id)
    {
        EventRequisition::where('id', $id)->update(['status'=>2,'updated_by'=>Auth::user()->username]);
        EventRequisitionProduct::where('eventReqId', $id)->update(['status'=>2,'updated_by'=>Auth::user()->username]);
        return redirect('/eventRequisitions')->with("success","Event Requisition Deactivated Successfully...");
    }

    public function printRequisition($id)
    {
        $requisition = EventRequisition::join('departments', 'event_requisitions.departmentId', 'departments.id')
        ->join('contactus_land_pages', 'event_requisitions.branchId', 'contactus_land_pages.id')
        ->select('event_requisitions.*', 'departments.name as departmentName', 'contactus_land_pages.branchName')
        ->where('event_requisitions.id', $id)
        ->first();
        $prodList = EventRequisitionProduct::where('eventReqId', $id)->get();
        $file = $requisition->requisitionNo.'_Req.pdf';
        $pdf = PDF::loadView('storeAdmin.eventRequisitions.printRequisition',compact('prodList','requisition'))->setPaper('a4');
        return $pdf->stream($file); 
    }
}
