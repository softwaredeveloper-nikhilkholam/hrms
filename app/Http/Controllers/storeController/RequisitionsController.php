<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Utility;

use App\StoreProduct;
use App\ContactusLandPage;
use App\StoreCategory;
use App\StoreRequisition;
use App\StoreRequisitionProduct;
use App\Department;
use App\StoreOutward;
use App\StoreOutwardProdList;
use App\StoreOutwardTracking;
use App\StorePurchaseRequisition;
use App\StorePurReqProduct;
use App\StoreUser;
use App\BranchStock;
use App\User;
use App\StoreUnit;
use App\StoreProductLedger;
use App\RequisitionTracking;
use App\EventRequisition;
use App\EventRequisitionProduct;
use DB;
use Auth;
use Image;
use PDF;
use Log;

class RequisitionsController extends Controller
{
    public function index(Request $request) // optimized code
    {
        try {
            // Ensure user is authenticated
            if (!Auth::check()) {
                abort(403, 'Unauthorized action.');
            }

            // Get authenticated user details
            $user = Auth::user();
            $userType = $user->userType;
            $userId = $user->id;

            // Request Filters
            $filters = [
                'requisitionNo' => $request->requitionNumber ?? null,
                'branchId'      => $request->branchId ?? null,
                'departmentId'  => $request->departmentId ?? null,
                'status'        => $request->status ?? null,
            ];

            // Fetch Branches & Departments
            $branches = ContactusLandPage::whereActive(1)->orderBy('branchName')->pluck('branchName', 'id');
            $departments = Department::whereActive(1)->orderBy('name')->pluck('name', 'id');

            // Base Query with Joins
           
            $temps = StoreRequisition::join('contactus_land_pages', 'store_requisitions.branchId', '=', 'contactus_land_pages.id')
                ->join('departments', 'store_requisitions.departmentId', '=', 'departments.id')
                ->select('contactus_land_pages.branchName', 'departments.name as departmentName', 'store_requisitions.*')
                ->where('store_requisitions.reqType', 1)
                ->whereIn('store_requisitions.status', [0, 3, 5]);

            // If Admin (UserType 91 or 501), show all records; else, filter by user
            if (!in_array($userType, ['91', '501'])) {
                $temps->where('store_requisitions.userId', $userId);
            }

            // Apply Dynamic Filters
            foreach ($filters as $column => $value) {
                if (!empty($value)) {
                    $temps->where("store_requisitions.$column", $value);
                }
            }

            // Order by custom status priority and latest records
            $requisitions = $temps->orderByRaw('FIELD(store_requisitions.status, 0, 3, 5)')
                ->orderByDesc('store_requisitions.id')
                ->get();

            // Count total requisitions
            $reqCount = $requisitions->count();

            // Return View with Data
            return view('storeAdmin.requisitions.list', [
                'requitionNumber' => $filters['requisitionNo'], 
                'branchId'        => $filters['branchId'], 
                'departmentId'    => $filters['departmentId'], 
                'status'          => $filters['status'], 
                'branches'        => $branches, 
                'departments'     => $departments, 
                'reqCount'        => $reqCount, 
                'requisitions'    => $requisitions
            ]);
            

        } catch (\Exception $e) {
            Log::error('Error fetching store requisitions: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function completedReqList(Request $request) // optimized code
    {
        $userType = Auth::user()->userType;
        $userId = Auth::user()->id;

        // Base Query with Joins
        $query = StoreRequisition::join('contactus_land_pages', 'store_requisitions.branchId', '=', 'contactus_land_pages.id')
            ->join('departments', 'store_requisitions.departmentId', '=', 'departments.id')
            ->select(
                'contactus_land_pages.branchName',
                'departments.name as departmentName',
                'store_requisitions.*'
            )
            ->where('store_requisitions.reqType', 1)
            ->whereIn('store_requisitions.status', [1, 2, 4]);

        // Apply User-Specific Filters
        if (!in_array($userType, ['91', '501'])) {
            $query->where('store_requisitions.userId', $userId)
                ->where('store_requisitions.created_at', '>=', now()->subMonths(3));
        }

        // Sorting and Pagination
        $requisitions = $query->orderByRaw('FIELD(store_requisitions.status, 1, 2, 4)')
                            ->orderByDesc('store_requisitions.id')
                            ->paginate(20);

        return view('storeAdmin.requisitions.completedList', [
            'reqCount' => $requisitions->total(),
            'requisitions' => $requisitions
        ]);
    }

    public function oldEventReqList(Request $request)
    {
        $userType = Auth::user()->userType;
        $userId = Auth::user()->id;
        $masterLogin = Auth::user()->masterLogin;
        $requitionNumber = $request->requitionNumber;
        $branchId = $request->branchId;
        $departmentId = $request->departmentId;
        $status = $request->status;

        $branches = ContactusLandPage::whereActive(1)->orderBy('branchName')->pluck('branchName', 'id');
        $departments = Department::whereActive(1)->orderBy('name')->pluck('name', 'id');

        if($userType == '91' || $userType == '501')
        {
            $temps = StoreRequisition::join('contactus_land_pages', 'store_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'store_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.branchName', 'departments.name as departmentName', 'store_requisitions.*')
            ->whereIn('store_requisitions.status', [0,3,5])
            ->where('store_requisitions.reqType', 2);

            if($requitionNumber != '')
                $temps =$temps->where('store_requisitions.requitionNumber',$requitionNumber);

            if($branchId != '')
                $temps =$temps->where('store_requisitions.branchId',$branchId);

            if($departmentId != '')
                $temps =$temps->where('store_requisitions.departmentId',$departmentId);

            if($status != '')
                $temps =$temps->where('store_requisitions.status',$status);

            $temps =$temps->orderByRaw('FIELD(store_requisitions.status,0,3,5)')
            ->orderBy('store_requisitions.id', 'desc');

            $reqCount = $temps->count();
            $requisitions = $temps->get();
        }
        else
        {
            $temps = StoreRequisition::join('contactus_land_pages', 'store_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'store_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.branchName', 'departments.name as departmentName', 'store_requisitions.*')
            ->where('store_requisitions.userId', $userId)
            ->where('store_requisitions.reqType', 2)
            ->whereIn('store_requisitions.status', [0,3,5]);

            if($requitionNumber != '')
                $temps =$temps->where('store_requisitions.requisitionNo',$requitionNumber);

            if($branchId != '')
                $temps =$temps->where('store_requisitions.branchId',$branchId);

            if($departmentId != '')
                $temps =$temps->where('store_requisitions.departmentId',$departmentId);

            if($status != '')
                $temps =$temps->where('store_requisitions.status',$status);

            $temps =$temps->orderByRaw('FIELD(store_requisitions.status,0,3,5)')
            ->orderBy('store_requisitions.id', 'desc');

            $reqCount = $temps->count();
            $requisitions = $temps->get();
        }
        return view('storeAdmin.requisitions.oldEventReqList')->with(['requitionNumber'=>$requitionNumber,'branchId'=>$branchId,'departmentId'=>$departmentId,
        'branches'=>$branches,'departments'=>$departments,'status'=>$status,'reqCount'=>$reqCount, 'requisitions'=>$requisitions]);
    }

    public function masterProductList()
    {
        try 
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
            return view('storeAdmin.requisitions.productList')->with(['products'=>$products]);

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with("error",$e->getMessage());
        }
    }

    public function create()
    {
        $userType = Auth::user()->userType;
        $userId = Auth::user()->id;
       
        $categories = StoreCategory::whereActive(1)->orderBy('name')->pluck('name', 'id');

        $departments = Department::whereActive(1)->orderBy('name')->pluck('name', 'id');
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $flag=1;
        return view('storeAdmin.requisitions.raiseRequisition')->with(['flag'=>$flag,'departments'=>$departments, 'categories'=>$categories,'branches'=>$branches]);
    }    

    public function store(Request $request)
    {
        if(count($request->productId) == 0)
        {
            return redirect()->back()->withInput()->with("error","Please Select At least 1 Product");
        }

        if (in_array("0", $request->qty))
        {
            return redirect()->back()->withInput()->with("error","Enter minimum 1 Qty of each Product....");
        }

        $lastRequisitionNo = StoreRequisition::where('branchId', $request->branchId)
        ->where('departmentId', $request->departmentId)
        ->orderBy('reqTemp','desc')
        ->value('reqTemp');

        if(!$lastRequisitionNo)
            $lastRequisitionNo=1;
        else
            $lastRequisitionNo++;

        $requisition = new StoreRequisition;
        $requisition->userId = Auth::user()->id;

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

        $requisition->requisitionNo = $shortCut.'/'.$dept.'/'.date('Y').'/'.date('M').'/'.$lastRequisitionNo;
        $requisition->reqTemp = $lastRequisitionNo;
        $requisition->branchId = $request->branchId;
        $requisition->requisitionDate = $request->requisitionDate;
        $requisition->requisitionerName = $request->requisitionerName;
        $requisition->departmentId = $request->departmentId;
        $requisition->dateOfRequirement = $request->dateOfRequirement;
        $requisition->sevirity = $request->sevirity;
        $requisition->requisitionFor = $request->requisitionFor;
        $requisition->deliveryTo = ContactusLandPage::where('id', $request->deliveryTo)->value('branchName');
        $requisition->authorityName = $request->authorityName;
        $requisition->updated_by = Auth::user()->username;
        $requisition->userBy = Auth::user()->username;
        $requisition->reqType = 1;

        if($requisition->save())
        {
            $products = count($request->productId);
            for($i=0; $i<$products; $i++)
            {
                $reqProduct = new StoreRequisitionProduct;
                $reqProduct->requisitionId=$requisition->id;
                $reqProduct->productId=$request->productId[$i];
                $reqProduct->requiredQty=$request->qty[$i];
                $reqProduct->updated_by=Auth::user()->username;
                $reqProduct->save();
            }

            $requisitionTracking = new RequisitionTracking;
            $requisitionTracking->requisitionId = $requisition->id;
            $requisitionTracking->userId = Auth::user()->id;
            $requisitionTracking->name = Auth::user()->name;
            $requisitionTracking->remark = "Requisition Raised";
            $requisitionTracking->trackingType = 1;
            $requisitionTracking->normalOrEventReq = 1;
            $requisitionTracking->updated_by = Auth::user()->username;
            $requisitionTracking->save(); 
        }

        return redirect('/requisitions')->with("success",$requisition->requisitionNo." Requisition Raise Successfully...");
    }
    
    public function show($id)
    {
        $requisition = StoreRequisition::find($id);
        $userType = Auth::user()->userType;
        if($userType == '91')
        {
            $requisition->viewedStatus = 1;
            $requisition->viewedTimestamp = date('Y-m-d H:i:s');
            $requisition->userBy = Auth::user()->username;
            if($requisition->save())
            {
                if(!RequisitionTracking::where('requisitionId', $requisition->id)->where('remark', "Requisition Viewed")->first())
                {
                    
                    $requisitionTracking = new RequisitionTracking;
                    $requisitionTracking->requisitionId = $requisition->id;
                    $requisitionTracking->userId = Auth::user()->id;
                    $requisitionTracking->name = Auth::user()->name;
                    $requisitionTracking->remark = "Requisition Viewed"; 
                    $requisitionTracking->trackingType = 2;
                    $requisitionTracking->normalOrEventReq = 1;
                    $requisitionTracking->updated_by = Auth::user()->username;
                    $requisitionTracking->save(); 
                }
            }
        }
        
        if($requisition->reqType == 1)
        {
            $prodList = StoreRequisitionProduct::join('store_products', 'store_requisition_products.productId', 'store_products.id')
            ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
            ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
            ->join('store_units', 'store_products.unitId', 'store_units.id')
            ->join('store_halls', 'store_products.hallId', 'store_halls.id')
            ->join('store_racks', 'store_products.rackId', 'store_racks.id')
            ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
            ->select('store_halls.name as hallName', 'store_racks.name as rackName', 'store_products.image', 'store_shels.name as shelfName', 'store_products.stock','store_products.name as productName', 'store_categories.name as categoryName', 
            'store_sub_categories.name as subCategoryName', 'store_products.returnStatus as prodReturn','store_products.productRate','store_units.name as unitName', 'store_products.company', 'store_products.size','store_requisition_products.*')
            ->where('store_requisition_products.requisitionId', $id)
            ->get();
        }
        else
        {
            $prodList = StoreRequisitionProduct::join('store_units', 'store_requisition_products.unitId', 'store_units.id')
            ->select('store_requisition_products.*', 'store_units.name as unitName')
            ->where('store_requisition_products.requisitionId', $id)
            ->get();
        }

        $categories = StoreCategory::whereActive(1)->orderBy('name')->pluck('name', 'id');
        $departments = Department::whereActive(1)->orderBy('name')->pluck('name', 'id');
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $outward = StoreOutward::where('requisitionNo', $requisition->requisitionNo)->first();
        if($outward)
            $deliveryHistory = StoreOutwardTracking::where('outwardId', $outward->id)->orderBy('id')->get();
        else
            $deliveryHistory = [];

        $trackingHistories = RequisitionTracking::where('requisitionId', $requisition->id)->get();

        return view('storeAdmin.requisitions.show')->with(['trackingHistories'=>$trackingHistories, 'deliveryHistory'=>$deliveryHistory, 'outward'=>$outward, 'prodList'=>$prodList, 'requisition'=>$requisition, 'departments'=>$departments, 'categories'=>$categories, 'branches'=>$branches]);
    }

    public function edit($id)
    {      
        $requisition = StoreRequisition::find($id);
        if($requisition)
        {
            $requisition->viewedStatus = 1;
            $requisition->viewedTimestamp = date('Y-m-d H:i:s');
            $requisition->userBy = Auth::user()->username;
            if($requisition->save())
            {
                if(!RequisitionTracking::where('requisitionId', $requisition->id)->where('remark', "Requisition Viewed")->first())
                {
                    $requisitionTracking = new RequisitionTracking;
                    $requisitionTracking->requisitionId = $requisition->id;
                    $requisitionTracking->userId = Auth::user()->id;
                    $requisitionTracking->name = Auth::user()->name;
                    $requisitionTracking->remark = "Requisition Viewed"; 
                    $requisitionTracking->trackingType = 2;
                    $requisitionTracking->normalOrEventReq = 1;
                    $requisitionTracking->updated_by = Auth::user()->username;
                    $requisitionTracking->save(); 
                }
            }
        }
        
        if($requisition->reqType == 1)
        {
            $prodList = StoreRequisitionProduct::join('store_products', 'store_requisition_products.productId', 'store_products.id')
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
            'store_products.size','store_requisition_products.*')
            ->where('store_requisition_products.requisitionId', $id)
            ->get();

        }
        else
        {
            $prodList = StoreRequisitionProduct::join('store_units', 'store_requisition_products.unitId', 'store_units.id')
            ->select('store_requisition_products.*', 'store_units.name as unitName')
            ->where('store_requisition_products.requisitionId', $id)
            ->get();
        }

        $categories = StoreCategory::whereActive(1)->orderBy('name')->pluck('name', 'id');
        $departments = Department::whereActive(1)->orderBy('name')->pluck('name', 'id');
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        return view('storeAdmin.outwards.edit')->with(['prodList'=>$prodList, 'requisition'=>$requisition, 'departments'=>$departments, 'categories'=>$categories, 'branches'=>$branches]);
    }

    public function update(Request $request, $id)
    {   
        // return $request->all();
        $productCount = count($request->productId);     
     
        if(in_array("0", $request->status))
            return redirect()->back()->withInput()->with("error","Pending / Hold Status Found, Please update every product status carefully..");
        
        if($productCount)
        {
            $outward = new StoreOutward;
            $outward->forDate = date('Y-m-d');
            $outward->receiptNo = "Out-".date('Ymdhis');
            $outward->requisitionId = $id;
            $outward->requisitionNo = $request->requisitionNo;
            $outward->dateOfRequisition = $request->dateOfRequisition;
            $outward->branchName = $request->branchName;
            $outward->branchId = ContactusLandPage::where('branchName', $request->branchName)->value('id');
            $outward->requisitionFor = $request->requisitionFor;
            $outward->narration = $request->storeRemark;
            $outward->updated_by = Auth::user()->username;        
            $flag=0;

            if($outward->save())
            {
                $util=new Utility(); 
                for($i=0; $i<$productCount; $i++)
                { 

                    $product=StoreProduct::find($request->productId[$i]);
                    $productStock = $util->getCurrentProductStock($request->productId[$i]);
                    $reqProduct = StoreRequisitionProduct::find($request->productListId[$i]);
                    $productList = new StoreOutwardProdList;
                    if($request->requiredQty[$i] <= $productStock)
                    {                            
                        $productList->outwardId = $outward->id;
                        $productList->actualProductId = $request->productId[$i];
                        $productList->productId = $request->productListId[$i];
                        $productList->qty = $request->requiredQty[$i];
                        $productList->updated_by = Auth::user()->username;

                        $reqProduct->receivedQty=$request->requiredQty[$i];
                        $reqProduct->currentRate=$product->productRate;
                        $reqProduct->returnStatus=$request->returnStatus[$i];
                        $reqProduct->dueDate=$request->dueDate[$i];
                        
                        $reqProduct->status=$request->status[$i];
                        $reqProduct->storeRejectReason=$request->productReason[$i];
                        $reqProduct->updated_by=Auth::user()->username;
                    }
                    else
                    {
                        $reqProduct->receivedQty=0;
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
                        $reqProduct->receivedQty=$request->requiredQty[$i];
                        $reqProduct->dueDate=$request->dueDate[$i];
                    }

                    if($productList->save())
                    {
                        $reqProduct->save();
                        if($request->requiredQty[$i] <= $productStock)
                        {
                            if($request->status[$i] == 1)
                            {
                                $util->updateLedger($outward->id, $productList->id, date('Y-m-d'), $product->id, $request->requiredQty[$i], 2);
                            }
                        }
                        $product->save();
                    }
                }

                $requisition = StoreRequisition::find($id);
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
                    $requisitionTracking->normalOrEventReq = 1;
                    $requisitionTracking->updated_by = Auth::user()->username;
                    $requisitionTracking->save(); 

                }
            }
            return redirect('/outwards')->with("success","Outward Generated successfully..");
        }
        else
        {
            return redirect()->back()->withInput()->with("error","Not selected any Product...");
        }
       
    }

    public function printRequisition($id)
    {
        $requisition = StoreRequisition::join('departments', 'store_requisitions.departmentId', 'departments.id')
        ->join('contactus_land_pages', 'store_requisitions.branchId', 'contactus_land_pages.id')
        ->select('store_requisitions.*', 'departments.name as departmentName', 'contactus_land_pages.branchName')
        ->where('store_requisitions.id', $id)
        ->first();
        
        $prodList = StoreRequisitionProduct::join('store_products', 'store_requisition_products.productId', 'store_products.id')
        ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->join('store_units', 'store_products.unitId', 'store_units.id')
        ->join('store_halls', 'store_products.hallId', 'store_halls.id')
        ->join('store_racks', 'store_products.rackId', 'store_racks.id')
        ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
        ->select('store_halls.name as hallName', 'store_racks.name as rackName', 'store_shels.name as shelfName', 'store_products.stock','store_products.name as storeProductName', 'store_categories.name as categoryName', 
        'store_sub_categories.name as subCategoryName', 'store_products.productRate', 'store_units.name as unitName', 'store_products.company', 'store_products.size','store_requisition_products.*')
        ->where('store_requisition_products.requisitionId', $id)
        ->get();
      
        $file = $requisition->requisitionNo.'_Req.pdf';
        $pdf = PDF::loadView('storeAdmin.outwards.printRequisition',compact('prodList','requisition'))->setPaper('a4');
        return $pdf->stream($file); 
    }

    public function getDepartment($userId)
    {
       return User::where('id', $userId)->value('reqDepartmentId');
    }

    public function getBranchStock()
    {
        $stocks = BranchStock::join('store_products', 'branch_stocks.productId', 'store_products.id')
        ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->join('store_units', 'store_products.unitId', 'store_units.id')
        ->select('branch_stocks.*', 'store_categories.name as category', 'store_sub_categories.name as subCategory',
        'store_units.name as unitName', 'store_products.name as productName','store_products.size',
        'store_products.color','store_categories.id as categoryId','store_sub_categories.id as subCategoryId','store_products.productRate')
        ->where('branch_stocks.status', 1)
        ->where('branch_stocks.stock', '!=', 0)
        ->orderBy('branch_stocks.stock')
        ->get();

        return view('storeAdmin.stocks.list')->with(['stocks'=>$stocks]);
    }
    

    public function deactivate($id)
    {
        $requisition = StoreRequisition::find($id);
        $requisition->status = 4;
        $requisition->updated_by = Auth::user()->username;
        if($requisition->save())
        {
            StoreRequisitionProduct::where('requisitionId', $id)->update(['status'=>4, 'updated_by'=>Auth::user()->username]); 
        }
        
        return redirect()->back()->withInput()->with("success","Requisition Cancel Successfully..");
    }

    
    public function productReturnView($id)
    {
        $productReturn = StoreProductReturn::find($id);
        $list = StoreProductReturnList::where('returnId', $id)->get();
       
        $list = StoreProductReturnList::join('store_products', 'store_product_return_lists.productId', 'store_products.id')
        ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->join('store_units', 'store_products.unitId', 'store_units.id')
        ->join('store_halls', 'store_products.hallId', 'store_halls.id')
        ->join('store_racks', 'store_products.rackId', 'store_racks.id')
        ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
        ->select('store_product_return_lists.*', 'store_products.name as productName', 'store_categories.name as categoryName','store_halls.name as hallName', 'store_racks.name as rackName',
        'store_sub_categories.name as subCategoryName', 'store_products.color', 'store_products.size','store_shels.name as shelfName',
        'store_products.company','store_units.name as unitName')
        ->where('store_product_return_lists.returnId', $id)
        ->where('store_product_return_lists.status', 1)
        ->get();

        $categories = StoreCategory::where('active', 1)->orderBy('name')->pluck('name', 'id');
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $productReturns = StoreProductReturn::where('status', 1)->count();
        return view('storeAdmin.productReturns.show')->with(['productReturn'=>$productReturn,'list'=>$list,'branches'=>$branches,'categories'=>$categories,'productReturns'=>$productReturns]);
    }

    public function updateReqProductReturn(Request $request)
    {
        return 'Cancelled';
        $productCount = count($request->productId);
        $productIds = $request->productId;
        $returnQtys = $request->returnQty;
        $requisitionId = $request->requisitionId;
        if($productCount)
        {
            for($i=0; $i<$productCount; $i++)
            {
                if($returnQtys[$i] != 0 || $returnQtys[$i] != '')
                {
                    $productReturn = StoreRequisitionProduct::where('requisitionId', $requisitionId)
                    ->where('productId', $productIds[$i])
                    ->first();
                    $productReturn->returnQty = $returnQtys[$i];
                    if($productReturn->returnQty == $productReturn->requiredQty)
                        $productReturn->returnToStore = 1;

                    $productReturn->returnToStoreDate = date('Y-m-d');
                    $productReturn->returnStoreStatus = 1;
                    $productReturn->returnToStoreRemark = $request->remark[$i];
                    $productReturn->takenByStore = Auth::user()->username;;
                    $productReturn->updated_by = Auth::user()->username;
                    if($productReturn->save())
                    {
                        $util = new Utility();
                        $util->updateLedger($requisitionId, $productReturn->id, date('Y-m-d'), $productIds[$i], $returnQtys[$i], 3);
                    }
                }
            }
        }
        return redirect('/requisitions/reqProductReturnList')->with("success","Requisition Updated Successfully...");
    }

    public function reqProductReturnList()
    {
        $requisitionIds = StoreRequisitionProduct::join('store_products', 'store_requisition_products.productId', 'store_products.id')
        ->where('store_requisition_products.returnStatus', 'Yes')
       // ->where('store_requisition_products.returnStoreStatus', 0)
       // ->where('store_requisition_products.status', 1)
        ->distinct('store_requisition_products.requisitionId')
        ->pluck('store_requisition_products.requisitionId');
        
        $requisitions = StoreRequisition::join('contactus_land_pages', 'store_requisitions.branchId', 'contactus_land_pages.id')
        ->join('departments', 'store_requisitions.departmentId', 'departments.id')
        ->select('contactus_land_pages.branchName', 'departments.name as departmentName', 'store_requisitions.*')
        ->whereIn('store_requisitions.id', $requisitionIds)
        ->where('store_requisitions.status', 1)
        ->orderBy('store_requisitions.updated_at', 'desc')
        ->paginate(10);

        return view('storeAdmin.productReturns.reqProductReturnList')->with(['requisitions'=>$requisitions]);
    }

    public function reqProductReturnView($id)
    {
        $requisition = StoreRequisition::join('contactus_land_pages', 'store_requisitions.branchId', 'contactus_land_pages.id')
        ->join('departments', 'store_requisitions.departmentId', 'departments.id')
        ->select('contactus_land_pages.branchName', 'departments.name as departmentName', 'store_requisitions.*')
        ->where('store_requisitions.id', $id)
        ->orderBy('store_requisitions.updated_at')
        ->first();

        $prodList = StoreRequisitionProduct::join('store_products', 'store_requisition_products.productId', 'store_products.id')
        ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->join('store_units', 'store_products.unitId', 'store_units.id')
        ->join('store_halls', 'store_products.hallId', 'store_halls.id')
        ->join('store_racks', 'store_products.rackId', 'store_racks.id')
        ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
        ->select('store_halls.name as hallName', 'store_racks.name as rackName', 'store_products.image', 'store_shels.name as shelfName', 'store_products.stock','store_products.name as productName', 'store_categories.name as categoryName', 
        'store_sub_categories.name as subCategoryName', 'store_products.returnStatus as prodReturn','store_products.productRate','store_units.name as unitName', 'store_products.company', 'store_products.size','store_requisition_products.*')
        ->where('store_requisition_products.requisitionId', $id)
        ->where('store_products.returnStatus', 'Yes')
        ->where('store_requisition_products.returnToStore', 0)
        ->get();

        $categories = StoreCategory::whereActive(1)->orderBy('name')->pluck('name', 'id');
        $departments = Department::whereActive(1)->orderBy('name')->pluck('name', 'id');
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');

        return view('storeAdmin.productReturns.reqProductReturnView')->with(['prodList'=>$prodList, 'requisition'=>$requisition, 'departments'=>$departments, 'categories'=>$categories, 'branches'=>$branches]);
    }


    // Purchase Requisitions

    public function purchaseRequisitionList()  // Pending purchase requisition
    {
        $user = Auth::user();
        $userType = $user->userType;
        $userId = $user->id;
        
        $isAdmin = in_array($userType, ['91', '501', '701']);
        
        $temp1 = StorePurchaseRequisition::join('contactus_land_pages', 'store_purchase_requisitions.branchId', '=', 'contactus_land_pages.id')
            ->join('departments', 'store_purchase_requisitions.departmentId', '=', 'departments.id')
            ->select('contactus_land_pages.branchName', 'departments.name as departmentName', 'store_purchase_requisitions.*')
            ->where('store_purchase_requisitions.created_at', '>=', now()->subMonths(3))
            ->where('store_purchase_requisitions.status', 0)
            ->when(!$isAdmin, function ($query) use ($userId) {
                return $query->where('store_purchase_requisitions.userId', $userId);
            })
            ->orderBy('store_purchase_requisitions.status')
            ->orderByDesc('store_purchase_requisitions.id')
            ->get();
        
        $temp2 = EventRequisition::join('contactus_land_pages', 'event_requisitions.branchId', '=', 'contactus_land_pages.id')
            ->join('departments', 'event_requisitions.departmentId', '=', 'departments.id')
            ->select('contactus_land_pages.shortName as branchName', 'departments.name as departmentName', 'event_requisitions.*')
            ->where('event_requisitions.status', 0)
            ->when(!$isAdmin, function ($query) use ($userId) {
                return $query->where('event_requisitions.raisedById', $userId)
                             ->where('event_requisitions.productListStatus', 2);
            })
            ->orderByDesc('event_requisitions.id')
            ->get();
        
        // Bulk update `productListStatus` for all fetched `event_requisitions`
        EventRequisition::whereIn('id', $temp2->pluck('id'))->update(['productListStatus' => 2]);
        
        // Merge & sort data
        $requisitions = collect($temp1)->merge($temp2)->sortByDesc('created_at');
        
        $countRequisition = $requisitions->count();
        
        return view('storeAdmin.PurchaseRequisitions.pendingPurchaseRequisitionList', compact('countRequisition', 'requisitions'));
        
    }

    public function approvedPurchaseRequisitionList()  // Approved purchase requisition
    {
        $userType = Auth::user()->userType;
        $userId = Auth::user()->id;
        $masterLogin = Auth::user()->masterLogin;
        if($userType == '91' || $userType == '501' || $userType == '701')
        {
            $temp1 = StorePurchaseRequisition::join('contactus_land_pages', 'store_purchase_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'store_purchase_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.branchName', 'departments.name as departmentName', 'store_purchase_requisitions.*')
            ->where('store_purchase_requisitions.created_at', '>=', date('Y-m-d H:i:s', strtotime('-3 month')))
            ->where('store_purchase_requisitions.status', 5)
            ->orderBy('store_purchase_requisitions.status')
            ->orderBy('store_purchase_requisitions.id', 'desc')
            ->get();

            $temp2 = EventRequisition::join('contactus_land_pages', 'event_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'event_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.shortName as branchName', 'departments.name as departmentName', 'event_requisitions.*')
            ->where('event_requisitions.productListStatus', 2)
            ->orderBy('event_requisitions.id', 'desc')
            ->get();

            $original = collect($temp1);
            $latest = collect($temp2);
            $requisitions = $original->merge($latest)->sortByDesc('created_at');

        }
        else
        {
            $temp1 = StorePurchaseRequisition::join('contactus_land_pages', 'store_purchase_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'store_purchase_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.branchName', 'departments.name as departmentName', 'store_purchase_requisitions.*')
            ->where('store_purchase_requisitions.created_at', '>=', date('Y-m-d H:i:s', strtotime('-3 month')))
            ->where('store_purchase_requisitions.status', 5)
            ->where('store_purchase_requisitions.userId', $userId)
            ->orderBy('store_purchase_requisitions.status')
            ->orderBy('store_purchase_requisitions.id', 'desc')
            ->get();

            $temp2 = EventRequisition::join('contactus_land_pages', 'event_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'event_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.shortName as branchName', 'departments.name as departmentName', 'event_requisitions.*')
            ->whereIn('event_requisitions.status', [2,5])
            ->where('event_requisitions.userId', $userId)
            ->where('event_requisitions.productListStatus', 2)
            ->orderBy('event_requisitions.id', 'desc')
            ->get();

            $original = collect($temp1);
            $latest = collect($temp2);
            $requisitions = $original->merge($latest)->sortByDesc('created_at');
        }

        $countRequisition = count($requisitions);
        return view('storeAdmin.PurchaseRequisitions.approvedPurchaseRequisitionList')->with(['countRequisition'=>$countRequisition, 'requisitions'=>$requisitions]);
    }

    public function completedPurchaseRequisitionList()  // Completed purchase requisition
    {
        $userType = Auth::user()->userType;
        $userId = Auth::user()->id;
        $masterLogin = Auth::user()->masterLogin;
        if($userType == '91' || $userType == '501' || $userType == '701')
        {
            $temp1 = StorePurchaseRequisition::join('contactus_land_pages', 'store_purchase_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'store_purchase_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.branchName', 'departments.name as departmentName', 'store_purchase_requisitions.*')
            ->where('store_purchase_requisitions.created_at', '>=', date('Y-m-d H:i:s', strtotime('-3 month')))
            ->where('store_purchase_requisitions.status', 2)
            ->orderBy('store_purchase_requisitions.status')
            ->orderBy('store_purchase_requisitions.id', 'desc')
            ->get();

            $temp2 = EventRequisition::join('contactus_land_pages', 'event_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'event_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.shortName as branchName', 'departments.name as departmentName', 'event_requisitions.*')
            ->whereIn('event_requisitions.status', [2,5])
            ->where('event_requisitions.productListStatus', 2)
            ->orderBy('event_requisitions.id', 'desc')
            ->get();

            $original = collect($temp1);
            $latest = collect($temp2);
            $requisitions = $original->merge($latest)->sortByDesc('created_at');
        }
        else
        {
            $temp1 = StorePurchaseRequisition::join('contactus_land_pages', 'store_purchase_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'store_purchase_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.branchName', 'departments.name as departmentName', 'store_purchase_requisitions.*')
            ->where('store_purchase_requisitions.created_at', '>=', date('Y-m-d H:i:s', strtotime('-3 month')))
            ->where('store_purchase_requisitions.status', 2)
            ->where('store_purchase_requisitions.userId', $userId)
            ->orderBy('store_purchase_requisitions.status')
            ->orderBy('store_purchase_requisitions.id', 'desc')
            ->get();

            $temp2 = EventRequisition::join('contactus_land_pages', 'event_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'event_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.shortName as branchName', 'departments.name as departmentName', 'event_requisitions.*')
            ->whereIn('event_requisitions.status', [2,5])
            ->where('event_requisitions.userId', $userId)
            ->where('event_requisitions.productListStatus', 2)
            ->orderBy('event_requisitions.id', 'desc')
            ->get();

            $original = collect($temp1);
            $latest = collect($temp2);
            $requisitions = $original->merge($latest)->sortByDesc('created_at');
        }

        $countRequisition = count($requisitions);
        return view('storeAdmin.PurchaseRequisitions.completedPurchaseRequisitionList')->with(['countRequisition'=>$countRequisition, 'requisitions'=>$requisitions]);
    }

    public function rejectedPurchaseRequisitionList()  // Rejected purchase requisition
    {
        $userType = Auth::user()->userType;
        $userId = Auth::user()->id;
        $masterLogin = Auth::user()->masterLogin;
        if($userType == '91' || $userType == '501' || $userType == '701')
        {
            $temp1 = StorePurchaseRequisition::join('contactus_land_pages', 'store_purchase_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'store_purchase_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.branchName', 'departments.name as departmentName', 'store_purchase_requisitions.*')
            ->where('store_purchase_requisitions.created_at', '>=', date('Y-m-d H:i:s', strtotime('-3 month')))
            ->where('store_purchase_requisitions.status', 3)
            ->orderBy('store_purchase_requisitions.status')
            ->orderBy('store_purchase_requisitions.id', 'desc')
            ->get();

            $temp2 = EventRequisition::join('contactus_land_pages', 'event_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'event_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.shortName as branchName', 'departments.name as departmentName', 'event_requisitions.*')
            ->where('event_requisitions.status', 0)
            ->where('event_requisitions.productListStatus', 2)
            ->orderByRaw('FIELD(event_requisitions.status,0,3,5)')
            ->orderBy('event_requisitions.id', 'desc')
            ->get();

            $original = collect($temp1);
            $latest = collect($temp2);
            $requisitions = $original->merge($latest)->sortByDesc('created_at');
        }
        else
        {
            $temp1 = StorePurchaseRequisition::join('contactus_land_pages', 'store_purchase_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'store_purchase_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.branchName', 'departments.name as departmentName', 'store_purchase_requisitions.*')
            ->where('store_purchase_requisitions.created_at', '>=', date('Y-m-d H:i:s', strtotime('-3 month')))
            ->where('store_purchase_requisitions.status', 3)
            ->where('store_purchase_requisitions.userId', $userId)
            ->orderBy('store_purchase_requisitions.status')
            ->orderBy('store_purchase_requisitions.id', 'desc')
            ->get();

            $temp2 = EventRequisition::join('contactus_land_pages', 'event_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'event_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.shortName as branchName', 'departments.name as departmentName', 'event_requisitions.*')
            ->where('event_requisitions.status', 0)
            ->where('event_requisitions.userId', $userId)
            ->where('event_requisitions.productListStatus', 2)
            ->orderBy('event_requisitions.id', 'desc')
            ->get();

            $original = collect($temp1);
            $latest = collect($temp2);
            $requisitions = $original->merge($latest)->sortByDesc('created_at');
        }

        $countRequisition = count($requisitions);
        return view('storeAdmin.PurchaseRequisitions.pendingPurchaseRequisitionList')->with(['countRequisition'=>$countRequisition, 'requisitions'=>$requisitions]);
    }

    public function raisePurchaseRequisition()// Purchase Requisitions
    {
        $categories = StoreCategory::whereActive(1)->orderBy('name')->pluck('name', 'id');
        $departments = Department::whereActive(1)->orderBy('name')->pluck('name', 'id');
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $units = StoreUnit::where('active', 1)->orderBy('name')->pluck('name', 'id');
        $requisitioners = User::where('active', 1)->where('userType', 801)->orderBy('name')->pluck('name', 'id');
        return view('storeAdmin.PurchaseRequisitions.raisePurchaseRequisition')->with(['requisitioners'=>$requisitioners,'units'=>$units,'departments'=>$departments, 'categories'=>$categories,'branches'=>$branches]);
    }  

    public function storePurchaseRequisition(Request $request)  // purchase requisition
    {
        //return $request->all();
        try 
        {
            $products = $request->productId;
            $qtys = $request->qty;
            $descriptions = $request->description;
            $prodCount = count($products);
            if($prodCount == 1 && $products[0] == null)
                    return redirect()->back()->withInput()->with("error","Please Enter at least 1 Product...");

            
            for($i=0; $i<$prodCount; $i++)
            {
                if($products[$i] != '' || $qtys[$i] != '' || $descriptions[$i] != '')
                {
                    if($products[$i] == '' && $qtys[$i] ==  '')
                        return redirect()->back()->withInput()->with("error","Please enter all details if fill any single textbox ");
                }
            }

                $lastRequisitionNo = StorePurchaseRequisition::where('branchId', $request->branchId)
                ->where('departmentId', $request->departmentId)
                ->orderBy('reqTemp','desc')
                ->value('reqTemp');

                if(!$lastRequisitionNo)
                    $lastRequisitionNo=1;
                else
                    $lastRequisitionNo++;

                $requisition = new StorePurchaseRequisition;
                $requisition->userId = Auth::user()->id;

                if($request->departmentId != '')
                    $departmentId = $request->departmentId;
                else
                    $departmentId = $request->departmentIdView;
                
                $words = explode(" ", Department::where('id', $departmentId)->value('name'));
                $dept = "";
                foreach ($words as $w) {
                    $dept .= mb_substr($w, 0, 3);
                }

                $shortCut = ContactusLandPage::where('id', $request->branchId)->value('shortName');
                
                $requisition->requisitionNo = $shortCut.'/'.$dept.'/'.date('Y').'/'.date('M').'/'.$lastRequisitionNo;
                $requisition->reqTemp = $lastRequisitionNo;
                $requisition->branchId = $request->branchId;
                $requisition->requisitionDate = $request->requisitionDate;
                $userData = User::where('id', $request->requisitionerName)->first();
                $requisition->requisitionerName = $userData->name;
                $requisition->departmentId = $userData->reqDepartmentId;
                $requisition->dateOfRequirement = $request->dateOfRequirement;
                $requisition->sevirity = $request->sevirity;
                $requisition->requisitionFor = $request->remark;
                $requisition->deliveryTo = ContactusLandPage::where('id', $request->deliveryTo)->value('branchName');
                $requisition->authorityName = $request->authorityName;
                $requisition->updated_by = Auth::user()->username;
                $requisition->userBy = Auth::user()->username;
            

                if($requisition->save())
                {
                    for($i=0; $i<$prodCount; $i++)
                    {
                    
                        if($products[$i] != '' && $qtys[$i] != '')
                        {
                            $purProd = new StorePurReqProduct;
                            $purProd->purReqId=$requisition->id;
                            $purProd->prodName=$products[$i];
                            $purProd->qty=$qtys[$i];
                            $purProd->description=$descriptions[$i];
                            $purProd->updated_by=Auth::user()->username;
                            $file = $request->file('fileName')[$i];
                            if(!empty($file))
                            {
                                $originalImage= $file;
                                $Image = Auth::user()->id.date('Ymdhis').'.'.$originalImage->getClientOriginalExtension();
                                $image = Image::make($originalImage);
                                $originalPath =  public_path()."/storeAdmin/otherProductImages/";
                                $image->resize(700,700);
                                $image->save($originalPath.$Image);
                                $purProd->image = $Image;
                            }
                            $purProd->save();
                        }
                    }
                }

                return redirect('/requisitions/purchaseRequisitionList')->with("success",$lastRequisitionNo." Requisition Raise Successfully...");
        } catch (Throwable $e) {
            report($e);
    
            return false;
        }
    }

    public function deactivatePurchaseRequisition($id)  // purchase requisition
    {
        $req = StorePurchaseRequisition::find($id);
        $req->status = 3;
        $req->updated_by = Auth::user()->username;
        if($req->save())
            StorePurReqProduct::where('purReqId', $id)->update(['status'=>'3', 'updated_by'=>Auth::user()->username]);

        return redirect('/requisitions/purchaseRequisitionList')->with("success","Deleted Purchase Requisition successfully...");
    }

    public function purchaseRequisitionView($id, $type) // purchase requisition
    {
        if($type == 2)
        {
            $requisition = StorePurchaseRequisition::join('contactus_land_pages', 'store_purchase_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'store_purchase_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.shortName', 'departments.name as departmentName', 'store_purchase_requisitions.*')
            ->where('store_purchase_requisitions.id', $id)
            ->first();
            
            $products = StorePurReqProduct::select('store_pur_req_products.*','store_pur_req_products.prodName as productName')->where('purReqId', $id)->get();
            $deliverToBranch = ContactusLandPage::where('id', $requisition->deliverToBranchId)->value('shortName'); 
        }
        else
        {
            $requisition = EventRequisition::join('contactus_land_pages', 'event_requisitions.branchId', 'contactus_land_pages.id')
            ->join('departments', 'event_requisitions.departmentId', 'departments.id')
            ->select('contactus_land_pages.shortName', 'departments.name as departmentName', 'event_requisitions.*')
            ->where('event_requisitions.id', $id)
            ->first();

            $products = EventRequisitionProduct::where('eventReqId', $requisition->id)->get();
            $deliverToBranch = ContactusLandPage::where('id', $requisition->deliverToBranchId)->value('shortName'); 
        }

        $userType = Auth::user()->userType;
        if($userType == '91')
        {
            $requisitionTracking = new RequisitionTracking;
            $requisitionTracking->requisitionId = $requisition->id;
            $requisitionTracking->userId = Auth::user()->id;
            $requisitionTracking->name = Auth::user()->name;
            $requisitionTracking->remark = "Requisition Viewed";
            $requisitionTracking->trackingType = 2;
            $requisitionTracking->normalOrEventReq = 2;
            $requisitionTracking->updated_by = Auth::user()->username;
            $requisitionTracking->save(); 
        }

        return view('storeAdmin.PurchaseRequisitions.showPurchaseRequisition')->with(['deliverToBranch'=>$deliverToBranch,
        'products'=>$products,'requisition'=>$requisition]);
    }

    public function approvePurchaseProduct(Request $request) // purchase requisition
    {
        $requisition = StorePurchaseRequisition::find($request->reqId);
        $requisition->status=$request->status;
        $requisition->updated_by = Auth::user()->username;
        $requisition->save();
        return redirect('/requisitions/purchaseRequisitionList')->with("success","Requisition Updated Successfully...");
    }
}
