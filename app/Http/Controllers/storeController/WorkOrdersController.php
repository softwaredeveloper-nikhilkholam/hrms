<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Utility;
use App\Exports\stores\PaymentWOExport;
use App\Exports\stores\WorkOrderExport;
use App\StoreWorkOrder;
use App\StoreVendor;
use App\ContactusLandPage;
use App\StoreWorkOrderProduct;
use App\StoreWorkOrderPayment;
use App\StoreRequisition;
use App\TypeOfCompany;
use App\User;
use Auth;
use Excel;
use PDF;
use DB;


class WorkOrdersController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $userType = $user->userType;
        $reqDepartmentId = $user->reqDepartmentId;
        $typeOfCompany = explode(',', $user->typeOfCompany ?? '');

        // ✅ Permission check
        if (!in_array($userType, ['61', '701', '501', '801'])) {
            return redirect()->back()->withInput()->with("error", "You do not have permission to access this page.");
        }

        // 🔽 Filters & dropdown values
        $vendors = StoreVendor::where('active', 1)->pluck('name', 'id');
        $raisedBysIds = StoreWorkOrder::pluck('raisedBy');
        $users = User::whereIn('id', $raisedBysIds)->orderBy('name')->pluck('name', 'id');

        // 🔽 Base query
        $pendingOrders = StoreWorkOrder::join('store_vendors', 'store_work_orders.vendorId', '=', 'store_vendors.id')
            ->join('type_of_companies', 'store_work_orders.typeOfCompany', '=', 'type_of_companies.id')
            ->select(
                'store_vendors.name as vendorName',
                'store_work_orders.*',
                'type_of_companies.shortName as typeOfCompanyName'
            )
            ->where('store_work_orders.WOStatus', 'Pending');

        // 🔽 Additional restrictions
        if ($userType == '801' && $reqDepartmentId != 12) {
            $pendingOrders->where('store_work_orders.raisedBy', $userId);
        }

        if ($userType == '61') {
            $pendingOrders->whereIn('store_work_orders.typeOfCompany', $typeOfCompany);
        }

        // 🔽 Apply filters from request
        if (!empty($request->myInputVendorName)) {
            $pendingOrders->where('store_vendors.name', $request->myInputVendorName);
        }

        if (!empty($request->raisedBys)) {
            $pendingOrders->where('store_work_orders.raisedBy', $request->raisedBys);
        }

        // 🔽 Get results
        $orders = $pendingOrders->get();

        // 🔽 Return view
        return view('storeAdmin.workOrders.pendingOrderList', [
            'myInputVendorName' => $request->myInputVendorName,
            'raisedBys' => $request->raisedBys,
            'users' => $users,
            'vendors' => $vendors,
            'orders' => $orders
        ]);
    }

    public function approvedOrderList(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $userType = $user->userType;
        $reqDepartmentId = $user->reqDepartmentId;
        $typeOfCompany = explode(',', $user->typeOfCompany ?? '');

        // ✅ Permission check
        if (!in_array($userType, ['61', '701', '501', '801'])) {
            return redirect()->back()->withInput()->with("error", "You do not have permission to access this page.");
        }

        // 🔽 Filters & dropdown values
        $vendors = StoreVendor::where('active', 1)->pluck('name', 'id');
        $raisedBysIds = StoreWorkOrder::pluck('raisedBy');
        $users = User::whereIn('id', $raisedBysIds)->orderBy('name')->pluck('name', 'id');

        // 🔽 Base query
        $pendingOrders = StoreWorkOrder::join('store_vendors', 'store_work_orders.vendorId', '=', 'store_vendors.id')
            ->join('type_of_companies', 'store_work_orders.typeOfCompany', '=', 'type_of_companies.id')
            ->select(
                'store_vendors.name as vendorName',
                'store_work_orders.*',
                'type_of_companies.shortName as typeOfCompanyName'
            )
            ->where('store_work_orders.WOStatus', 'Approved');

        // 🔽 Additional restrictions
        if ($userType == '801' && $reqDepartmentId != 12) {
            $pendingOrders->where('store_work_orders.raisedBy', $userId);
        }

        if($request->forMonth != '')
        {
            $startDate = $request->forMonth.'-01';
            $endDate = date('Y-m-t', strtotime($startDate));
            $pendingOrders = $pendingOrders->whereBetween('store_work_orders.created_at', [$startDate, $endDate]);
        }
        else
        {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
            $pendingOrders = $pendingOrders->whereBetween('store_work_orders.created_at', [$startDate, $endDate]);
        }

        if ($userType == '61') {
            $pendingOrders->whereIn('store_work_orders.typeOfCompany', $typeOfCompany);
        }

        // 🔽 Apply filters from request
        if (!empty($request->myInputVendorName)) {
            $pendingOrders->where('store_vendors.name', $request->myInputVendorName);
        }

        if (!empty($request->raisedBys) && $userType != '61') {
            $pendingOrders->where('store_work_orders.raisedBy', $request->raisedBys);
        }

        // 🔽 Get results
        $orders = $pendingOrders->get();

        // 🔽 Return view
        return view('storeAdmin.workOrders.approvedOrderList', [
            'myInputVendorName' => $request->myInputVendorName,
            'raisedBys' => $request->raisedBys,
            'users' => $users,
            'vendors' => $vendors,
            'orders' => $orders,
            'forMonth'=>$request->forMonth
        ]);
    }

    public function rejectedOrderList(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $userType = $user->userType;
        $reqDepartmentId = $user->reqDepartmentId;
        $typeOfCompany = explode(',', $user->typeOfCompany ?? '');

        // ✅ Permission check
        if (!in_array($userType, ['61', '701', '501', '801'])) {
            return redirect()->back()->withInput()->with("error", "You do not have permission to access this page.");
        }

        // 🔽 Filters & dropdown values
        $vendors = StoreVendor::where('active', 1)->pluck('name', 'id');
        $raisedBysIds = StoreWorkOrder::pluck('raisedBy');
        $users = User::whereIn('id', $raisedBysIds)->orderBy('name')->pluck('name', 'id');

        // 🔽 Base query
        $pendingOrders = StoreWorkOrder::join('store_vendors', 'store_work_orders.vendorId', '=', 'store_vendors.id')
            ->join('type_of_companies', 'store_work_orders.typeOfCompany', '=', 'type_of_companies.id')
            ->select(
                'store_vendors.name as vendorName',
                'store_work_orders.*',
                'type_of_companies.shortName as typeOfCompanyName'
            )
            ->whereIn('store_work_orders.WOStatus', ['rejected', 'Cancel']);

        // 🔽 Additional restrictions
        if ($userType == '801' && $reqDepartmentId != 12) {
            $pendingOrders->where('store_work_orders.raisedBy', $userId);
        }

        if($request->forMonth != '')
        {
            $startDate = $request->forMonth.'-01';
            $endDate = date('Y-m-t', strtotime($startDate));
            $pendingOrders = $pendingOrders->whereBetween('store_work_orders.created_at', [$startDate, $endDate]);
        }
        else
        {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
            $pendingOrders = $pendingOrders->whereBetween('store_work_orders.created_at', [$startDate, $endDate]);
        }

        if ($userType == '61') {
            $pendingOrders->whereIn('store_work_orders.typeOfCompany', $typeOfCompany);
        }

        // 🔽 Apply filters from request
        if (!empty($request->myInputVendorName)) {
            $pendingOrders->where('store_vendors.name', $request->myInputVendorName);
        }

        if (!empty($request->raisedBys)) {
            $pendingOrders->where('store_work_orders.raisedBy', $request->raisedBys);
        }

        // 🔽 Get results
        $orders = $pendingOrders->get();

        // 🔽 Return view
        return view('storeAdmin.workOrders.rejectedOrderList', [
            'myInputVendorName' => $request->myInputVendorName,
            'raisedBys' => $request->raisedBys,
            'users' => $users,
            'vendors' => $vendors,
            'orders' => $orders,
            'forMonth'=>$request->forMonth
        ]);
    }
    
    public function create(Request $request)
    {
        try
        {
            $userType = Auth::user()->userType;
            if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801')
            {
                $typeOfCompanies = TypeOfCompany::where('status', 1)->orderBy('name')->pluck('name', 'id');
                $vendors = StoreVendor::where('active',1)->orderBy('name')->pluck('name', 'id');
                $branches = ContactusLandPage::whereActive(1)->orderBy('branchName')->pluck('branchName', 'id');
                $pendingOrdersCount = StoreWorkOrder::where('WOStatus', 'Pending')->distinct('commWONo')->count();
                $approvedOrdersCount = StoreWorkOrder::where('WOStatus', 'Approved')->count();
                $rejectedOrdersCount = StoreWorkOrder::whereIn('WOStatus', ['rejected', 'Cancel'])->distinct('commWONo')->count();
                return view('storeAdmin.workOrders.generateWorkOrder')->with(['typeOfCompanies'=>$typeOfCompanies,'vendors'=>$vendors,'branches'=>$branches,'pendingOrdersCount'=>$pendingOrdersCount,
                'approvedOrdersCount'=>$approvedOrdersCount,'rejectedOrdersCount'=>$rejectedOrdersCount]);
            }
            else
            {
                return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
            }
        }
        catch(Exception $e)
        {
            return redirect()->back()->withInput()->with("error","There is some issue....");
        }
    }

    public function store(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801')
        {
            $particular1Count = count($request->particular1);  
            for($j=0; $j<$particular1Count; $j++)
            {
                if($request->particular1[$j] == '' || $request->qty1[$j] == 0 || $request->unit1[$j] == '' || $request->rate1[$j] == 0)
                {
                    return redirect()->back()->withInput()->with("error","Please enter valid details in Service List..."); 
                }
            }

            if($request->workOrderDiv2 == 2)
            {
                if($request->vendorId2 == null || $request->billNo2 == null || $request->forDate2 == null || $request->branchId2 == null || $request->locationInBranch2 == null || $request->alreadyPaid2 == null)
                    return redirect()->back()->withInput()->with("error","Please fill all required details of Work Order 2....");
            }

            if($request->workOrderDiv3 == 2)
            {
                if($request->vendorId3== null || $request->billNo3 == null || $request->forDate3 == null || $request->branchId3 == null || $request->locationInBranch3 == null || $request->alreadyPaid3 == null)
                    return redirect()->back()->withInput()->with("error","Please fill all required details of Work Order 3....");
            }

            $lastComWO = StoreWorkOrder::orderBy('commWONo', 'desc')->value('commWONo');
            if(!$lastComWO)
                $lastComWO=100000;

            $workOrder1 = new StoreWorkOrder;
            $workOrder1->raisedBy = Auth::user()->id;
            $workOrder1->vendorId = $request->vendorId1;
            $vendorDet = StoreVendor::where('id', $request->vendorId1)->first();
            
            $workOrder1->name = $vendorDet->name;
            $lastWO = StoreWorkOrder::orderBy('WONo', 'desc')->value('WONo');
            $workOrder1->WONo = $lastWO+1;

            $workOrder1->address = $vendorDet->address;
            $workOrder1->billNo = $request->billNo1;
            $workOrder1->forDate = $request->forDate1;
            $workOrder1->typeOfCompany = $request->typeOfCompany1;
            $workOrder1->accountNo = $vendorDet->accountNo;
            $workOrder1->IFSCCode = $vendorDet->IFSCCode;
            $workOrder1->bankBranch = $vendorDet->bankBranch;
            $workOrder1->commWONo = $lastComWO+1;
            
            $workOrder1->advancePayment = $request->woAdv1;
            $workOrder1->branchId = $request->branchId1;
            $workOrder1->locationInBranch = $request->locationInBranch1;
            $workOrder1->shippingAddress = ContactusLandPage::where('id', $request->branchId1)->value('address');
            $workOrder1->WOFor = $request->description1;            
            $workOrder1->reqNo = $request->reqNo;            
          
            $workOrder1->totalRs = $request->subTotal1;
            $workOrder1->labourCharges = $request->labourCharges1;
            $workOrder1->transportationRs = $request->transportation1;
            $workOrder1->shiftingCharges = $request->shiftingCharges1;
            $workOrder1->cgst = $request->CGST1;
            $workOrder1->sgst = $request->SGST1;
            $workOrder1->discount = $request->woDiscount1;
            $workOrder1->finalRs = $request->grandTotal1;
            $workOrder1->alreadyPaid = $request->alreadyPaid1;
            $workOrder1->alreadyPaidBy = $request->alreadyPaidBy1;
            $workOrder1->updated_by = Auth::user()->username;

            $workOrderFile1 = $request->workOrderFile1;
            if(!empty($workOrderFile1))
            {
                $file= $workOrderFile1;
                $fileName = 'WO'.date('dmhis').'-1.'.$file->extension();  
                $file->move(public_path('storeAdmin/workOrders/'), $fileName); 
                $workOrder1->workOrderFile = $fileName;
            }            

            if($workOrder1->save())
            {
                $particular1Count = count($request->particular1);  
                for($j=0; $j<$particular1Count; $j++)
                {
                    $order = new StoreWorkOrderProduct;
                    $order->orderId = $workOrder1->id;
                    $order->particular = $request->particular1[$j];
                    $order->qty = $request->qty1[$j];
                    $order->rate = $request->rate1[$j];
                    $order->unit = $request->unit1[$j];
                    $order->amount = $request->amount1[$j];
                    $order->updated_by = Auth::user()->username;
                    $order->save();
                }
            }       
            
            if($request->workOrderDiv2 == 2)
            {
                $workOrder2 = new StoreWorkOrder;
                $workOrder2->raisedBy = Auth::user()->id;
                $workOrder2->vendorId = $request->vendorId2;
                $vendorDet = StoreVendor::where('id', $request->vendorId2)->first();
                
                $workOrder2->name = $vendorDet->name;
                $workOrder2->WONo = $workOrder1->WONo+1;

                $workOrder2->address = $vendorDet->address;
                $workOrder2->typeOfCompany = $request->typeOfCompany2;
                $workOrder2->accountNo = $vendorDet->accountNo;
                $workOrder2->IFSCCode = $vendorDet->IFSCCode;
                $workOrder2->bankBranch = $vendorDet->bankBranch;
                $workOrder2->commWONo = $workOrder1->commWONo;
                $workOrder2->billNo = $request->billNo2;
                $workOrder2->forDate = $request->forDate2;
                
                $workOrder2->advancePayment = $request->woAdv2;
                $workOrder2->branchId = $request->branchId2;
                $workOrder2->locationInBranch = $request->locationInBranch2;
                $workOrder2->shippingAddress = ContactusLandPage::where('id', $request->branchId2)->value('address').'[ '.$request->locationInBranch2.' ]';
                $workOrder2->WOFor = $request->description2;  
                $workOrder2->reqNo = $request->reqNo2;                
            
                $workOrder2->totalRs = $request->subTotal2;
                $workOrder2->labourCharges = $request->labourCharges2;
                $workOrder2->transportationRs = $request->transportation2;
                $workOrder2->shiftingCharges = $request->shiftingCharges2;
                $workOrder2->cgst = $request->CGST2;
                $workOrder2->sgst = $request->SGST2;
                $workOrder2->finalRs = $request->grandTotal2;
                $workOrder1->discount = $request->woDiscount2;
                $workOrder2->alreadyPaid = $request->alreadyPaid2;
                $workOrder2->alreadyPaidBy = $request->alreadyPaidBy2;
                $workOrder2->updated_by = Auth::user()->username;

                $workOrderFile2= $request->workOrderFile2;
                if(!empty($workOrderFile2))
                {
                    $file= $workOrderFile2;
                    $fileName = 'WO'.date('dmhis').'-2.'.$file->extension();  
                    $file->move(public_path('storeAdmin/workOrders/'), $fileName); 
                    $workOrder2->workOrderFile = $fileName;
                }            

                if($workOrder2->save())
                {
                    $particular1Count = count($request->particular2);  
                    for($j=0; $j<$particular1Count; $j++)
                    {
                        $order = new StoreWorkOrderProduct;
                        $order->orderId = $workOrder2->id;
                        $order->particular = $request->particular2[$j];
                        $order->qty = $request->qty2[$j];
                        $order->rate = $request->rate2[$j];
                        $order->unit = $request->unit2[$j];
                        $order->amount = $request->amount2[$j];
                        $order->updated_by = Auth::user()->username;
                        $order->save();
                    }
                } 
            }

            if($request->workOrderDiv3 == 2)
            {
                $workOrder3 = new StoreWorkOrder;
                $workOrder3->raisedBy = Auth::user()->id;
                $workOrder3->vendorId = $request->vendorId3;
                $vendorDet = StoreVendor::where('id', $request->vendorId3)->first();
                
                $workOrder3->name = $vendorDet->name;
                $workOrder3->WONo = $workOrder2->WONo + 1;

                $workOrder3->address = $vendorDet->address;
                $workOrder3->typeOfCompany = $request->typeOfCompany3;
                $workOrder3->accountNo = $vendorDet->accountNo;
                $workOrder3->IFSCCode = $vendorDet->IFSCCode;
                $workOrder3->bankBranch = $vendorDet->bankBranch;
                $workOrder3->commWONo = $workOrder1->commWONo;
                $workOrder3->billNo = $request->billNo3;
                $workOrder3->forDate = $request->forDate3;
                
                $workOrder3->advancePayment = $request->woAdv3;
                $workOrder3->branchId = $request->branchId3;
                $workOrder3->locationInBranch = $request->locationInBranch3;
                $workOrder3->shippingAddress = ContactusLandPage::where('id', $request->branchId3)->value('address').'[ '.$request->locationInBranch3.' ]';
                $workOrder3->WOFor = $request->description2;            
                $workOrder3->reqNo = $request->reqNo3; 

                $workOrder3->totalRs = $request->subTotal3;
                $workOrder3->labourCharges = $request->labourCharges3;
                $workOrder3->transportationRs = $request->transportation3;
                $workOrder3->shiftingCharges = $request->shiftingCharges3;
                $workOrder3->cgst = $request->CGST3;
                $workOrder3->sgst = $request->SGST3;
                $workOrder3->finalRs = $request->grandTotal3;
                $workOrder1->discount = $request->woDiscount3;
                $workOrder3->alreadyPaid = $request->alreadyPaid3;
                $workOrder3->alreadyPaidBy = $request->alreadyPaidBy3;
                $workOrder3->updated_by = Auth::user()->username;

                $workOrderFile3= $request->workOrderFile3;
                if(!empty($workOrderFile3))
                {
                    $file= $workOrderFile3;
                    $fileName = 'WO'.date('dmhis').'-3.'.$file->extension();  
                    $file->move(public_path('storeAdmin/workOrders/'), $fileName); 
                    $workOrder3->workOrderFile = $fileName;
                }            

                if($workOrder3->save())
                {
                    $particular1Count = count($request->particular3);  
                    for($j=0; $j<$particular1Count; $j++)
                    {
                        $order = new StoreWorkOrderProduct;
                        $order->orderId = $workOrder3->id;
                        $order->particular = $request->particular3[$j];
                        $order->qty = $request->qty3[$j];
                        $order->rate = $request->rate3[$j];
                        $order->unit = $request->unit3[$j];
                        $order->amount = $request->amount3[$j];
                        $order->updated_by = Auth::user()->username;
                        $order->save();
                    }
                } 
            }
            
            return redirect('/workOrder')->with("success","Work Order Generated successfully.");
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }

    }

    public function show($id)
    {
        $userType = Auth::user()->userType;
        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801')
        {
            $orders=StoreWorkOrder::join('store_vendors', 'store_work_orders.vendorId', 'store_vendors.id')
            ->join('contactus_land_pages', 'store_work_orders.branchId', 'contactus_land_pages.id')
            ->join('type_of_companies', 'store_work_orders.typeOfCompany', 'type_of_companies.id')
            ->select('store_vendors.name as vendorName', 'store_vendors.address as vendorAddress', 'type_of_companies.name as tempTypeOfCompany',
            'contactus_land_pages.branchName','store_vendors.accountNo','store_vendors.IFSCCode','store_vendors.bankBranch',
            'store_work_orders.*')
            ->where('commWONo', $id)
            ->get();

            $vendors = StoreVendor::where('active',1)->orderBy('name')->pluck('name', 'id');
            $branches = ContactusLandPage::whereActive(1)->orderBy('branchName')->pluck('branchName', 'id');
            $pendingOrdersCount = StoreWorkOrder::where('WOStatus', 'Pending')->distinct('commWONo')->count();
            $approvedOrdersCount = StoreWorkOrder::where('WOStatus', 'Approved')->count();
            $rejectedOrdersCount = StoreWorkOrder::whereIn('WOStatus', ['rejected', 'Cancel'])->distinct('commWONo')->count();
            return view('storeAdmin.workOrders.viewWorkOrder')->with(['orders'=>$orders,'vendors'=>$vendors,'branches'=>$branches,'pendingOrdersCount'=>$pendingOrdersCount,
            'approvedOrdersCount'=>$approvedOrdersCount,'rejectedOrdersCount'=>$rejectedOrdersCount]);
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
    }

    public function viewWorkOrder($id) // final Work Order
    {
        $userType = Auth::user()->userType;
        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801')
        {
            $id = base64_decode($id);
            $order=StoreWorkOrder::join('store_vendors', 'store_work_orders.vendorId', 'store_vendors.id')
            ->join('users', 'store_work_orders.raisedBy', 'users.id')
            ->join('contactus_land_pages', 'store_work_orders.branchId', 'contactus_land_pages.id')
            ->join('type_of_companies', 'store_work_orders.typeOfCompany', 'type_of_companies.id')
            ->select('store_vendors.letterHeadName','store_vendors.letterHeadAddress','store_vendors.letterHeadMobileNo',
            'store_vendors.letterHeadLandline','store_vendors.letterHeadEmail','store_vendors.name as vendorName', 'store_vendors.address as vendorAddress','store_vendors.PANNO', 'store_vendors.landlineNo',
            'store_vendors.GSTNo','contactus_land_pages.branchName','store_work_orders.*', 'users.name as raisedBy','type_of_companies.name as typeOfCompanyName','type_of_companies.address as typeOfCompanyAddress')
            ->where('poNumber', $id)
            ->first();

            $payments = StoreWorkOrderPayment::where('orderId', $order->id)
            ->orderBy('forDate')
            ->get();

            $reqDet='';
            if($order->reqNo != '')
                $reqDet = StoreRequisition::where('requisitionNo', $order->reqNo)->value('requisitionerName');

            $pendingOrdersCount = StoreWorkOrder::where('WOStatus', 'Pending')->distinct('commWONo')->count();
            $approvedOrdersCount = StoreWorkOrder::where('WOStatus', 'Approved')->count();
            $rejectedOrdersCount = StoreWorkOrder::whereIn('WOStatus', ['rejected', 'Cancel'])->distinct('commWONo')->count();
            return view('storeAdmin.workOrders.workOrderDetails')->with(['reqDet'=>$reqDet,'payments'=>$payments,'order'=>$order,'pendingOrdersCount'=>$pendingOrdersCount,
            'approvedOrdersCount'=>$approvedOrdersCount,'rejectedOrdersCount'=>$rejectedOrdersCount]);
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
    }

    public function approveWorkOrder(Request $request)
    {
        if($request->optradio == '')
            return redirect()->back()->withInput()->with("error","Please approve at least 1 Quotation..");
        
        $percentArr = $request->input('percent');
        $userType = Auth::user()->userType;
        if($userType == '701' || $userType == '501')
        {
            DB::beginTransaction();
            try 
            {
                $workOrder = StoreWorkOrder::find($request->optradio);

                if (empty($percentArr) && $workOrder->alreadyPaid == 0) {
                    return back()->with('error', 'At least one payment row is required.');
                }

                StoreWorkOrder::where('commWONo', $workOrder->commWONo)->update(['checkedByAuthority'=>1, 'WOStatus'=>'Rejected', 'updated_by'=>Auth::user()->username]);

                $util =  new Utility;
                $finYear = $util->getFinancialYear(date('Y-m-d'));
                $tOCompany = TypeOfCompany::where('id', $workOrder->typeOfCompany)->where('status', 1)->value('shortName');
                $serialNo = StoreWorkOrder::orderBy('serialNo', 'desc')->whereNotNull('serialNo')->value('serialNo');
                if($serialNo == '')
                    $serialNo = 1;
                else
                    $serialNo=$serialNo+1;
            
                if($serialNo >= 1 && $serialNo <= 9)
                    $poNumber = $tOCompany.'/'.$finYear.'/WO/0'.$serialNo;
                else
                    $poNumber = $tOCompany.'/'.$finYear.'/WO/'.$serialNo;

                $workOrder->serialNo = $serialNo;

                // $poNumber = "WO".date('Ymdhis');
                $totalPOAmount=0;
                // ✅ Add payments only if not already paid
                if ($workOrder->alreadyPaid == 0) 
                {
                
                    $dateArr = $request->input('forDate');
                    $amountArr = $request->input('amount');
                    $remarkArr = $request->input('remark');

                    $count = count($percentArr);

                    for ($i = 0; $i < $count; $i++) {
                        if (!empty($percentArr[$i]) && !empty($dateArr[$i]) && !empty($amountArr[$i])) {
                            $payment = new StoreWorkOrderPayment;
                            $payment->poNumber = $poNumber;
                            $payment->orderId = $workOrder->id;
                            $payment->percent = $percentArr[$i];
                            $payment->forDate = $dateArr[$i];
                            $payment->amount = $amountArr[$i];
                            $payment->remark = $remarkArr[$i] ?? null;
                            $payment->updated_by = Auth::user()->username;
                            $payment->save();

                            $totalPOAmount += $amountArr[$i];
                        }
                    }
                }

                if($workOrder->alreadyPaid == 1)
                    $workOrder->paidAmount=$workOrder->poAmount;

                $workOrder->poAmount = $workOrder->finalRs;
                $workOrder->WOStatus = "Approved";
                $workOrder->poNumber=$poNumber;
                $workOrder->generatedDate=date('Y-m-d');
                $workOrder->updated_by=Auth::user()->username;
                $workOrder->save();


                DB::commit();
                return redirect('/workOrder')->with("success","Work Order Approved by Authority Successfully..");

            } catch (\Exception $e) {
                DB::rollback();
                // Optionally log the error $e->getMessage()
                return redirect()->back()->withInput()->with("error", "There is some issue. Please try again.");
            }

        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
    }

    public function printWO($id)
    {
        $userType = Auth::user()->userType;
        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801')
        {
            $order=StoreWorkOrder::join('store_vendors', 'store_work_orders.vendorId', 'store_vendors.id')
            ->join('users', 'store_work_orders.raisedBy', 'users.id')
            ->join('contactus_land_pages', 'store_work_orders.branchId', 'contactus_land_pages.id')
            ->select('store_vendors.name as vendorName', 'store_vendors.address as vendorAddress','store_vendors.PANNO', 'store_vendors.landlineNo',
            'store_vendors.GSTNo','contactus_land_pages.branchName','store_work_orders.*', 'users.name as raisedBy')
            ->where('store_work_orders.id', $id)
            ->first();

            $payments = StoreWorkOrderPayment::where('orderId', $order->id)
            ->orderBy('forDate')
            ->get();

            $reqDet='';
            if($order->reqNo != '')
                $reqDet = StoreRequisition::where('requisitionNo', $order->reqNo)->value('requisitionerName');

            $pdf = PDF::loadView('storeAdmin.pdfs.printWO',compact('order','payments','reqDet'));
            return $pdf->stream($order->poNumber);
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
    }

    public function WOPayment(Request $request)
    {
        $user = Auth::user();
        $userType = $user->userType;
        $typeOfCompany = explode(',', $user->typeOfCompany);

        if (in_array($userType, ['61', '701', '501', '801'])) 
        {
            $paymentsQuery = StoreWorkOrderPayment::join('store_work_orders', 'store_work_order_payments.orderId', '=', 'store_work_orders.id')
            ->join('store_vendors', 'store_work_orders.vendorId', '=', 'store_vendors.id')
            ->join('type_of_companies', 'store_work_orders.typeOfCompany', '=', 'type_of_companies.id')
            ->select(
                'store_work_order_payments.*',
                'store_vendors.id as vendorId',
                'store_vendors.name as vendorName',
                'type_of_companies.shortName as typeOfCompanyName',
                'store_vendors.accountNo',
                'store_vendors.IFSCCode',
                'store_vendors.bankBranch',
                'store_work_orders.billNo',
                'store_work_orders.WOFor',
                'store_work_orders.finalRs'
            )->where('store_work_order_payments.status', 1)
            ->where('store_work_order_payments.active', 1);

            if ($userType == '801') {
                $paymentsQuery=$paymentsQuery->where('store_work_orders.raisedBy', $user->id);
            }
            else
            {
                if($user->typeOfCompany != '')
                {
                    $typeOfCompany = explode(',', $user->typeOfCompany ?? []);
                    $paymentsQuery=$paymentsQuery->whereIn('store_work_orders.typeOfCompany', $typeOfCompany);
                }
            }

            $payments = $paymentsQuery->orderBy('store_work_order_payments.forDate')->get();
            return view('storeAdmin.WOPayments.list')->with(['payments' => $payments]);
        } 
        else 
        {
            return redirect()->back()->withInput()->with("error", "You do not have permission to access this page.");
        }
    }

    public function WOPaidPayments(Request $request)
    {
        $selectedTypeOfCompany = $request->input('typeOfCompanyId');
        $forMonth = $request->input('forMonth');
        if($forMonth == '')
            $forMonth = date('Y-m'); // Default to current month
        
        $startDate = date('Y-m-01', strtotime($forMonth));
        $endDate = date('Y-m-t', strtotime($forMonth));
      
        $user = Auth::user();
        $userType = $user->userType;
        $typeOfCompany = explode(',', $user->typeOfCompany);

        if(in_array($userType, ['61', '701', '501', '801'])) 
        {
            $paymentsQuery = StoreWorkOrderPayment::join('store_work_orders', 'store_work_order_payments.orderId', '=', 'store_work_orders.id')
            ->join('store_vendors', 'store_work_orders.vendorId', '=', 'store_vendors.id')
            ->join('type_of_companies', 'store_work_orders.typeOfCompany', '=', 'type_of_companies.id')
            ->select(
                'store_work_order_payments.*',
                'store_vendors.id as vendorId',
                'store_vendors.name as vendorName',
                'type_of_companies.shortName as typeOfCompanyName',
                'store_vendors.accountNo',
                'store_vendors.IFSCCode',
                'store_vendors.bankBranch',
                'store_work_orders.billNo',
                'store_work_orders.WOFor',
                'store_work_orders.finalRs'
            )->where('store_work_order_payments.status', 2)
            ->where('store_work_order_payments.active', 1)
            ->whereBetween('store_work_order_payments.forDate', [$startDate, $endDate]);

            if($userType != '701')
            {
                if ($userType == '801') {
                    $paymentsQuery=$paymentsQuery->where('store_work_orders.raisedBy', $user->id);
                    $typeOfCompanies = TypeOfCompany::where('status', 1)->orderBy('name')->pluck('shortName', 'id');
                }
                else
                {
                    if($user->typeOfCompany != '')
                    {
                        $typeOfCompany = explode(',', $user->typeOfCompany ?? []);
                        $paymentsQuery=$paymentsQuery->whereIn('store_work_orders.typeOfCompany', $typeOfCompany);
                        $typeOfCompanies = TypeOfCompany::where('status', 1)->whereIn('id', $typeOfCompany)->orderBy('name')->pluck('shortName', 'id');
                    }
                    else
                    {
                        $typeOfCompanies = TypeOfCompany::where('status', 1)->orderBy('name')->pluck('shortName', 'id');
                    }
                }
            }
            else
            {
                $typeOfCompanies = TypeOfCompany::where('status', 1)->orderBy('name')->pluck('shortName', 'id');
            }

            if($selectedTypeOfCompany)
            {
                $paymentsQuery=$paymentsQuery->where('store_work_orders.typeOfCompany', $selectedTypeOfCompany);
            }

            $payments = $paymentsQuery->orderBy('store_work_order_payments.forDate')->get();
            return view('storeAdmin.WOPayments.paidList', compact('typeOfCompanies', 'payments','selectedTypeOfCompany','forMonth'));
        } 
        else 
        {
            return redirect()->back()->withInput()->with("error", "You do not have permission to access this page.");
        }
    }

    public function WORejectedPayments(Request $request)
    {
        $selectedTypeOfCompany = $request->input('typeOfCompanyId');
        $forMonth = $request->input('forMonth');
        if($forMonth == '')
            $forMonth = date('Y-m'); // Default to current month

        $startDate = date('Y-m-01', strtotime($forMonth));
        $endDate = date('Y-m-t', strtotime($forMonth));
        $user = Auth::user();
        $userType = $user->userType;
        $typeOfCompany = explode(',', $user->typeOfCompany);

        if(in_array($userType, ['61', '701', '501', '801'])) 
        {
            $paymentsQuery = StoreWorkOrderPayment::join('store_work_orders', 'store_work_order_payments.orderId', '=', 'store_work_orders.id')
            ->join('store_vendors', 'store_work_orders.vendorId', '=', 'store_vendors.id')
            ->join('type_of_companies', 'store_work_orders.typeOfCompany', '=', 'type_of_companies.id')
            ->select(
                'store_work_order_payments.*',
                'store_vendors.id as vendorId',
                'store_vendors.name as vendorName',
                'type_of_companies.shortName as typeOfCompanyName',
                'store_vendors.accountNo',
                'store_vendors.IFSCCode',
                'store_vendors.bankBranch',
                'store_work_orders.billNo',
                'store_work_orders.WOFor',
                'store_work_orders.finalRs'
            )->where('store_work_order_payments.status', 3)
            ->where('store_work_order_payments.active', 1)
            ->whereBetween('store_work_order_payments.forDate', [$startDate, $endDate]);

        if ($userType != '701') 
        {
            if ($userType == '801') {
                $paymentsQuery=$paymentsQuery->where('store_work_orders.raisedBy', $user->id);
                $typeOfCompanies = TypeOfCompany::where('status', 1)->orderBy('name')->pluck('shortName', 'id');
            }
            else
            {
                if($user->typeOfCompany != '')
                {
                    $typeOfCompany = explode(',', $user->typeOfCompany ?? []);
                    $paymentsQuery=$paymentsQuery->whereIn('store_work_orders.typeOfCompany', $typeOfCompany);
                    $typeOfCompanies = TypeOfCompany::where('status', 1)->whereIn('id', $typeOfCompany)->orderBy('name')->pluck('shortName', 'id');
                }
            }
        }
        else
        {
            $typeOfCompanies = TypeOfCompany::where('status', 1)->orderBy('name')->pluck('shortName', 'id');
        }

            if($selectedTypeOfCompany)
            {
                $paymentsQuery=$paymentsQuery->where('store_work_orders.typeOfCompany', $selectedTypeOfCompany);
            }

            $payments = $paymentsQuery->orderBy('store_work_order_payments.forDate')->get();
            return view('storeAdmin.WOPayments.rejectList', compact('typeOfCompanies', 'payments','selectedTypeOfCompany','forMonth'));
        } 
        else 
        {
            return redirect()->back()->withInput()->with("error", "You do not have permission to access this page.");
        }
    }

    public function WOHoldPayments(Request $request)
    {
        $selectedTypeOfCompany = $request->input('typeOfCompanyId');
        $forMonth = $request->input('forMonth');
        if($forMonth == '')
            $forMonth = date('Y-m'); // Default to current month

        $startDate = date('Y-m-01', strtotime($forMonth));
        $endDate = date('Y-m-t', strtotime($forMonth));
        $user = Auth::user();
        $userType = $user->userType;
        $typeOfCompany = explode(',', $user->typeOfCompany);

        if(in_array($userType, ['61', '701', '501', '801'])) 
        {
            $paymentsQuery = StoreWorkOrderPayment::join('store_work_orders', 'store_work_order_payments.orderId', '=', 'store_work_orders.id')
            ->join('store_vendors', 'store_work_orders.vendorId', '=', 'store_vendors.id')
            ->join('type_of_companies', 'store_work_orders.typeOfCompany', '=', 'type_of_companies.id')
            ->select(
                'store_work_order_payments.*',
                'store_vendors.id as vendorId',
                'store_vendors.name as vendorName',
                'type_of_companies.shortName as typeOfCompanyName',
                'store_vendors.accountNo',
                'store_vendors.IFSCCode',
                'store_vendors.bankBranch',
                'store_work_orders.billNo',
                'store_work_orders.WOFor',
                'store_work_orders.finalRs'
            )->where('store_work_order_payments.status', 4)
            ->where('store_work_order_payments.active', 1)
            ->whereBetween('store_work_order_payments.forDate', [$startDate, $endDate]);

            if ($userType != '701') 
            {
                if ($userType == '801') {
                    $paymentsQuery=$paymentsQuery->where('store_work_orders.raisedBy', $user->id);
                    $typeOfCompanies = TypeOfCompany::where('status', 1)->orderBy('name')->pluck('shortName', 'id');
                }
                else
                {
                    if($user->typeOfCompany != '')
                    {
                        $typeOfCompany = explode(',', $user->typeOfCompany ?? []);
                        $paymentsQuery=$paymentsQuery->whereIn('store_work_orders.typeOfCompany', $typeOfCompany);
                        $typeOfCompanies = TypeOfCompany::where('status', 1)->whereIn('id', $typeOfCompany)->orderBy('name')->pluck('shortName', 'id');
                    }
                }
            }
            else
            {
                 $typeOfCompanies = TypeOfCompany::where('status', 1)->orderBy('name')->pluck('shortName', 'id');
            }

            if($selectedTypeOfCompany)
            {
                $paymentsQuery=$paymentsQuery->where('store_work_orders.typeOfCompany', $selectedTypeOfCompany);
            }

            $payments = $paymentsQuery->orderBy('store_work_order_payments.forDate')->get();
            return view('storeAdmin.WOPayments.holdList', compact('typeOfCompanies', 'payments','selectedTypeOfCompany','forMonth'));
        } 
        else 
        {
            return redirect()->back()->withInput()->with("error", "You do not have permission to access this page.");
        }
    }

    public function exportWorkOrders($vendorId, $raisedBy, $status)
    {
        $fileName = 'WorkOrderList.xlsx';
        return Excel::download(new WorkOrderExport($vendorId, $raisedBy, $status), $fileName);
    }

    public function exportWOPayments($forMonth, $status, $typeOfCompany)
    {     
        if($status == 1)
            $temp = 'Unpaid';
        elseif($status == 2)
            $temp = 'Paid';
        elseif($status == 3)
            $temp = 'Rejected';
        else
            $temp = "Hold";

        $fileName = $temp.'-PaymentList-'.date('d-m-Y').'.xlsx';

        return Excel::download(new PaymentWOExport($forMonth, $status, $typeOfCompany), $fileName);
    }

    public function exportPayments($status)
    {
        $fileName = 'PaymentList.xlsx';
        return Excel::download(new PaymentExport($status), $fileName);
    }

    public function edit($id)
    {
        $userType = Auth::user()->userType;
        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801')
        {
            $payment = StoreWorkOrderPayment::join('store_work_orders', 'store_work_order_payments.orderId', 'store_work_orders.id')
            ->join('store_vendors', 'store_work_orders.vendorId', 'store_vendors.id')
            ->select('store_work_order_payments.*', 'store_vendors.id as vendorId','store_vendors.name as vendorName','store_work_orders.totalRs', 
            'store_work_orders.WONo','store_vendors.accountNo', 'store_vendors.address','store_vendors.landlineNo','store_vendors.IFSCCode', 'store_vendors.bankBranch')
            ->where('store_work_order_payments.id', $id)
            ->orderBy('store_work_order_payments.status')
            ->first();

            $WODetails = StoreWorkOrder::find($payment->orderId);

            $paymentHistory = StoreWorkOrderPayment::join('store_work_orders', 'store_work_order_payments.orderId', 'store_work_orders.id')
            ->join('store_vendors', 'store_work_orders.vendorId', 'store_vendors.id')
            ->select('store_work_order_payments.*', 'store_vendors.id as vendorId','store_vendors.name as vendorName','store_work_orders.totalRs', 
            'store_work_orders.WONo','store_work_orders.id as orderId','store_vendors.accountNo', 'store_vendors.IFSCCode', 'store_vendors.bankBranch')
            ->where('store_work_order_payments.poNumber', $payment->poNumber)
            ->where('store_work_order_payments.id', '!=', $id)
            ->orderBy('store_work_order_payments.status')
            ->get();
            
            return view('storeAdmin.WOPayments.edit')->with(['paymentHistory'=>$paymentHistory,'WODetails'=>$WODetails,'payment'=>$payment]);
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
    }

    public function updatePayment(Request $request)
    {
        if($request->paymentStatus != 1)
        {
            if($request->paymentStatus == 2)
            {
                $payment = StoreWorkOrderPayment::find($request->paymentId);
                $payment->vendorId=$request->vendorId;
                $payment->accountNo=$request->accountNo;
                $payment->bankBranch=$request->bankBranch;
                $payment->IFSCCode=$request->IFSCCode;
                $payment->transactionId=$request->transactionNumber;
                $payment->paymentRemark=$request->paymentRemark;
                $payment->transferDate=$request->transferDate;
                $payment->updated_by=Auth::user()->username;
                $payment->status=$request->paymentStatus;
                DB::beginTransaction();
                try 
                {
                    if($payment->save())
                    {
                        $vendor = StoreVendor::find($request->vendorId);
                        $vendor->accountNo=($request->accountNo != '')?$request->accountNo:$vendor->accountNo;
                        $vendor->IFSCCode=($request->IFSCCode != '')?$request->IFSCCode:$vendor->IFSCCode;
                        $vendor->bankBranch=($request->bankBranch != '')?$request->bankBranch:$vendor->bankBranch;
                        $vendor->updated_by=Auth::user()->username;
                        if($vendor->save())
                        {
                            $poDetails = StoreWorkOrder::where('id', $payment->orderId)->first();
                            $poDetails->paidAmount = $poDetails->paidAmount + $payment->amount;
                            $poDetails->updated_by=Auth::user()->username;
                            $poDetails->save();
                        }
                    }
                    DB::commit();
                    return redirect('/WOPayments/WOPayment')->with("success","Payment Transfer successfully..");
                } catch (\Exception $e) {
                    DB::rollback();
                    // Optionally log the error $e->getMessage()
                    return redirect()->back()->withInput()->with("error", "There is some issue. Please try again.");
                }
            }
            elseif($request->paymentStatus == 3)
            {
                $payment = StoreWorkOrderPayment::find($request->paymentId);
                $payment->paymentRemark=$request->paymentRemark;
                $payment->updated_by=Auth::user()->username;
                $payment->status=$request->paymentStatus;
                DB::beginTransaction();
                try 
                {
                    $payment->save();
                    DB::commit();
                    return redirect('/WOPayments/WOPayment')->with("success","Payment rejected successfully..");
                } catch (\Exception $e) {
                    DB::rollback();
                    // Optionally log the error $e->getMessage()
                    return redirect()->back()->withInput()->with("error", "There is some issue. Please try again.");
                }
            }
            else
            {
                
                $payment = StoreWorkOrderPayment::find($request->paymentId);
                $payment->paymentRemark=$request->paymentRemark;
                $payment->updated_by=Auth::user()->username;
                $payment->status=$request->paymentStatus;

                DB::beginTransaction();
                try 
                {
                    $payment->save();
                    DB::commit();
                    return redirect('/WOPayments/WOPayment')->with("success","Payment hold successfully..");
                } catch (\Exception $e) {
                    DB::rollback();
                    return redirect()->back()->withInput()->with("error", "There is some issue. Please try again.");
                }
            }
        }
        else
        {
            return redirect('/WOPayments/WOPayment')->with("warning","Please, select valid payment status.");
        }
    }  

    public function rejectPayment($paymentId)
    {
        $payment = StoreWorkOrderPayment::find($paymentId);
        $payment->updated_by=Auth::user()->username;
        $payment->status=3;
        $payment->save();
        return redirect('/WOPayments/WOPayment')->with("success","Payment Rejected Successfully....");
    }

    public function deactivate($workOrderComId)
    {
        $entry = StoreWorkOrder::where('commWONo', $workOrderComId)->where('WOStatus', 'Approved')->count();
        if($entry)
            return redirect()->back()->withInput()->with("error","You have not Permission to Deactivate this Order...");

        StoreWorkOrder::where('commWONo', $workOrderComId)->update(['WOStatus'=>'Rejected', 'updated_by'=>Auth::user()->username]);
        return redirect('/workOrder')->with("success","Work Order Rejected Successfully....");

    }
}
