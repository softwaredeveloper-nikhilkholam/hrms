<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\stores\PaymentExport;
use App\Helpers\Utility;
use App\TypeOfCompany;
use App\StoreVendor;
use App\StoreQuotation;
use App\StoreProduct;
use App\StoreQuotOrder;
use App\StoreCategory;
use App\StoreQuotationPayment;
use App\StorePurchaseOrder;
use App\StoreRequisitionProduct;
use App\StorePurchaseProduct;
use App\ContactusLandPage;
use App\FuelFilledEntry;
use App\User;
use DB;
use Auth;
use Excel;
use PDF;

class PurchaseTransactions extends Controller
{
    public function index()
    {
        $userType = Auth::user()->userType;
        if($userType == '701' || $userType == '501' || $userType == '801')
        {
            $vendors = StoreVendor::whereActive(1)->get();
            $categories = StoreCategory::whereActive(1)->orderBy('name')->pluck('name', 'id');

            return view('storeAdmin.quotations.vendorList')->with(['categories'=>$categories, 'vendors'=>$vendors]);
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
    }

    public function generateQuot(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType == '701' || $userType == '501' || $userType == '801')
        {
            $typeOfCompanies = TypeOfCompany::where('status', 1)->orderBy('name')->pluck('name', 'id');
            if(!isset($request->vendorId))
                return redirect()->back()->withInput()->with("error","Please select Vendors...");

            if(count($request->vendorId) > 3)
                return redirect()->back()->withInput()->with("error","Please select maximum 3 Vendors...");
            
            $vendorDetails = StoreVendor::whereIn('id', $request->vendorId)->get();
            $branches = ContactusLandPage::select(DB::raw("CONCAT(branchName,' : ',address) AS name"), 'id')->whereActive(1)->orderBy('name')->pluck('name', 'id');

            return view('storeAdmin.quotations.generateQuotation')->with(['typeOfCompanies'=>$typeOfCompanies,'shippingBranchId'=>$request->shippingBranchId,'officeAddress'=>$request->officeAddress,
            'requisitionNo'=>$request->requisitionNo,'quotationFor'=>$request->quotationFor,'productCode'=>$request->productCode,'unitPrice'=>$request->unitPrice,'typeOfCompany'=>$request->typeOfCompany,
            'productId'=>$request->productId, 'productName'=>$request->productName, 'size'=>$request->size, 'color'=>$request->color, 'subCategoryName'=>$request->subCategoryName,
            'categoryName'=>$request->categoryName, 'company'=>$request->company, 'stock'=>$request->stock,
            'vendorDetails'=>$vendorDetails,'qty'=>$request->qty,'branches'=>$branches,'vendorDetails'=>$vendorDetails,'discount'=>$request->discount,'cgst'=>$request->cgst,
            'sgst'=>$request->sgst,'amount'=>$request->amount,'category'=>$request->category]);
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
    }

    public function editGenerateQuot(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType == '701' || $userType == '501' || $userType == '801')
        {
            $typeOfCompanies = TypeOfCompany::where('status', 1)->orderBy('name')->pluck('name', 'id');
            if(!isset($request->vendorId))
                return redirect()->back()->withInput()->with("error","Please select Vendors...");

            if(count($request->vendorId) > 3)
                return redirect()->back()->withInput()->with("error","Please select maximum 3 Vendors...");
            
            $vendorDetails = StoreVendor::whereIn('id', $request->vendorId)->get();
            $branches = ContactusLandPage::select(DB::raw("CONCAT(branchName,' : ',address) AS name"), 'id')->whereActive(1)->orderBy('name')->pluck('name', 'id');

            return view('storeAdmin.quotations.generateQuotation', compact('userType', 'typeOfCompanies'));
           
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
    }

    public function quotationList()
    {
        $userType = Auth::user()->userType;
        if($userType == '61' || $userType == '701' || $userType == '501')
        {
            return $quotations = StoreQuotation::join('store_vendors', 'store_quotations.vendorId', 'store_vendors.id')
            ->select('store_vendors.name as vendorName', 'store_quotations.*')
            ->where('store_quotations.status', 1)
            ->get();
            return view('storeAdmin.quotations.quotationList')->with(['quotations'=>$quotations]);

        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
    }

    public function list(Request $request)
    {
        $user = Auth::user();
        $userType = Auth::user()->userType;
        $reqDepartmentId = Auth::user()->reqDepartmentId;
        $typeOfCompany = explode(',',$user->typeOfCompany);

        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801')
        {
            $vendors = StoreVendor::where('active', 1)->pluck('name', 'id');
            $raisedBysIds = StoreQuotation::pluck('raisedBy');
            $users = User::whereIn('id', $raisedBysIds)->orderBy('name')->pluck('name','id');

            $quotations = StoreQuotation::join('store_vendors', 'store_quotations.vendorId', 'store_vendors.id')
            ->join('type_of_companies', 'store_quotations.typeOfCompany', 'type_of_companies.id')
            ->select('store_vendors.name as vendorName', 'store_quotations.*', 'type_of_companies.shortName as typeOfCompanyName')
            ->where('store_quotations.quotStatus', 'Pending');
            if($userType == '801' && $reqDepartmentId!= 12)
                $quotations = $quotations->where('store_quotations.raisedBy', Auth::user()->id);

            if($request->myInputVendorName != '')
                $quotations = $quotations->where('store_vendors.name', $request->myInputVendorName);

            if($request->raisedBys != '')
                $quotations = $quotations->where('store_quotations.raisedBy', $request->raisedBys);

            if($userType == '61')
            {
                $quotations = $quotations->whereIn('store_quotations.typeOfCompany', $typeOfCompany);
            }

            $quotations = $quotations->orderBy('store_quotations.commQuotNo')
            ->where('store_quotations.status', 1)
            ->get();           

            return view('storeAdmin.quotations.quotationList')->with(['myInputVendorName'=>$request->myInputVendorName,
            'raisedBys'=>$request->raisedBys, 'users'=>$users, 'vendors'=>$vendors, 'quotations'=>$quotations]);
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page.");
        }
    }

    public function approvedQuotationList(Request $request)
    {
        $user = Auth::user();
        $userType = Auth::user()->userType;
        $reqDepartmentId = Auth::user()->reqDepartmentId;
        $typeOfCompany = explode(',',$user->typeOfCompany);

        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801')
        {
            $vendors = StoreVendor::where('active', 1)->pluck('name', 'id');
            $raisedBysIds = StoreQuotation::pluck('raisedBy');
            $users = User::whereIn('id', $raisedBysIds)->orderBy('name')->pluck('name','id');

            $quotations = StoreQuotation::join('store_vendors', 'store_quotations.vendorId', 'store_vendors.id')
            ->join('type_of_companies', 'store_quotations.typeOfCompany', 'type_of_companies.id')
            ->select('store_vendors.name as vendorName', 'store_quotations.*', 'type_of_companies.shortName as typeOfCompanyName')
            ->where('store_quotations.quotStatus', 'Approved');
            if($userType == '801' && $reqDepartmentId!= 12)
                $quotations = $quotations->where('store_quotations.raisedBy', Auth::user()->id);

            if($request->myInputVendorName != '')
                $quotations = $quotations->where('store_vendors.name', $request->myInputVendorName);

            if($request->raisedBys != '')
                $quotations = $quotations->where('store_quotations.raisedBy', $request->raisedBys);
                
            if($userType == '61')
            {
                $quotations = $quotations->whereIn('store_quotations.typeOfCompany', $typeOfCompany);
            }

            if($request->forMonth != '')
            {
                $startDate = $request->forMonth.'-01';
                $endDate = date('Y-m-t', strtotime($startDate));
                $quotations = $quotations->whereBetween('store_quotations.created_at', [$startDate, $endDate]);
            }
            else
            {
                $startDate = date('Y-m-01');
                $endDate = date('Y-m-t');
                $quotations = $quotations->whereBetween('store_quotations.created_at', [$startDate, $endDate]);
            }

            if($userType == '61')
            {
                $quotations = $quotations->whereIn('store_quotations.typeOfCompany', $typeOfCompany);
            }

            $quotations = $quotations->orderBy('store_quotations.commQuotNo')
            ->where('store_quotations.status', 1)
            ->get();           

            return view('storeAdmin.quotations.approvedQuotationList')->with(['myInputVendorName'=>$request->myInputVendorName,
            'raisedBys'=>$request->raisedBys,  'forMonth'=>$request->forMonth,  'users'=>$users, 'vendors'=>$vendors, 'quotations'=>$quotations]);
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page.");
        }
    }

    public function rejectedQuotationList(Request $request)
    {
        $user = Auth::user();
        $userType = Auth::user()->userType;
        $reqDepartmentId = Auth::user()->reqDepartmentId;
        $typeOfCompany = explode(',',$user->typeOfCompany);

        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801')
        {
            $vendors = StoreVendor::where('active', 1)->pluck('name', 'id');
            $raisedBysIds = StoreQuotation::pluck('raisedBy');
            $users = User::whereIn('id', $raisedBysIds)->orderBy('name')->pluck('name','id');

            $quotations = StoreQuotation::join('store_vendors', 'store_quotations.vendorId', 'store_vendors.id')
            ->join('type_of_companies', 'store_quotations.typeOfCompany', 'type_of_companies.id')
            ->select('store_vendors.name as vendorName', 'store_quotations.*', 'type_of_companies.shortName as typeOfCompanyName')
            ->whereIn('store_quotations.quotStatus', ['Cancel','Rejected']);
            if($userType == '801' && $reqDepartmentId!= 12)
                $quotations = $quotations->where('store_quotations.raisedBy', Auth::user()->id);

            if($request->myInputVendorName != '')
                $quotations = $quotations->where('store_vendors.name', $request->myInputVendorName);

            if($request->raisedBys != '')
                $quotations = $quotations->where('store_quotations.raisedBy', $request->raisedBys);

            if($request->forMonth != '')
            {
                $startDate = $request->forMonth.'-01';
                $endDate = date('Y-m-t', strtotime($startDate));
                $quotations = $quotations->whereBetween('store_quotations.created_at', [$startDate, $endDate]);
            }
            else
            {
                $startDate = date('Y-m-01');
                $endDate = date('Y-m-t');
                $quotations = $quotations->whereBetween('store_quotations.created_at', [$startDate, $endDate]);
            }

            if($userType == '61')
            {
                $quotations = $quotations->whereIn('store_quotations.typeOfCompany', $typeOfCompany);
            }

            $quotations = $quotations->orderBy('store_quotations.commQuotNo')
            ->where('store_quotations.status', 1)
            ->get();           

            return view('storeAdmin.quotations.rejectedQuotationList')->with(['myInputVendorName'=>$request->myInputVendorName,
            'raisedBys'=>$request->raisedBys, 'forMonth'=>$request->forMonth,  'users'=>$users, 'vendors'=>$vendors, 'quotations'=>$quotations]);
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page.");
        }
    }

    public function store(Request $request)
    {
        $userType = Auth::user()->userType;
        $quotationId = $request->quotationId;
        if($userType == '701' || $userType == '501' || $userType == '801')
        {
            if(empty($quotationId))
            {
                $lastComQuot = StoreQuotation::orderBy('commQuotNo', 'desc')->value('commQuotNo');
                if(!$lastComQuot)
                    $lastComQuot=100000;
            }

            $vendorsCount = count($request->vendorId);
            for($i=0; $i<$vendorsCount; $i++)
            {

                $tempQuotationId=0;
                if(!empty($quotationId))
                    $tempQuotationId =  $request->quotationId[$i];
            
                if($tempQuotationId != 0)
                    $quotation = StoreQuotation::find($tempQuotationId);
                else
                    $quotation = new StoreQuotation;

                $quotation->raisedBy = Auth::user()->id;
                if($tempQuotationId == 0)
                {
                    $quotation->vendorId = $request->vendorId[$i];
                    $vendorDet = StoreVendor::where('id', $request->vendorId[$i])->first();
                    
                    $quotation->name = $vendorDet->name;
                    if(empty($quotationId))
                    {
                        $lastQuot = StoreQuotation::orderBy('quotNo', 'desc')->value('quotNo');
                        $quotation->quotNo = $lastQuot+1;
                    }
        
                    $quotation->typeOfCompany = $request->typeOfCompany[$i];
                    $quotation->accountNo = $vendorDet->accountNo;
                    $quotation->IFSCCode = $vendorDet->IFSCCode;
                    $quotation->bankBranch = $vendorDet->bankBranch;

                    if(empty($quotationId))
                        $quotation->commQuotNo = $lastComQuot+1;
                    
                    $quotation->validDate = $request->validDate[$i];
                    $quotation->termsOfPayment = $request->termOfPayment[$i];
                    $quotation->shippingBranchId = $request->shippingAddress[$i];
                    $quotation->shippingAddress = ContactusLandPage::where('id', $request->shippingAddress[$i])->value('address');
                    $quotation->quotationFor = $request->quotationFor[$i];
                
                    $quotation->tentativeDate = $request->tentativeDeliveryDate[$i];
                    $quotation->reqNo = $request->requisitionNo[$i];
                    $quotation->alreadyPaid = $request->alreadyPaid[$i];
                    $quotation->alreadyPaidBy = $request->alreadyPaidBy[$i];
                    

                    $quotationFile = $request->quotationFile[$i];
                }

                $quotation->quotationFor = $request->quotationFor[$i];

                if(!empty($quotationFile))
                {
                    $file= $quotationFile;
                    $fileName = 'quotation_'.date('dmhis').'_'.$i.'.'.$file->extension();  
                    $file->move(public_path('storeAdmin/quotations/'), $fileName); 
                    $quotation->quotationFile = $fileName;
                }

                //  return $quotation;
                $quotation->totalRs = $request->totalRs[$i];
                $quotation->transportationRs = $request->transportationRs[$i];
                $quotation->loadingRs = $request->loadingRs[$i];
                $quotation->unloadingRs = $request->unloadingRs[$i];
                $quotation->finalRs = $request->finalRs[$i];
                $quotation->updated_by = Auth::user()->username;

                if($request->buttonStatus == 'save')
                    $quotation->status = 2;
                else
                    $quotation->status = 1;

                if($quotation->save())
                {
                    if($i == 0)
                    {
                        $quotOrderCount = count($request->productId1);  
                        for($j=0; $j<$quotOrderCount; $j++)
                        {
                            if(!isset($request->orderId1[$j]))
                                $order = new StoreQuotOrder;
                            else
                                $order = StoreQuotOrder::find($request->orderId1[$j]);

                            $order->quotationId = $quotation->id;
                            $order->productId = $request->productId1[$j];
                            $order->qty = $request->qty1[$j];
                            $order->forDate = $request->forDate1[$j];
                            $order->remark = $request->remark1[$j];
                            $order->unitPrice = $request->unitPrice1[$j];
                            $order->discount = $request->discount1[$j];
                            $order->cgst = $request->cgst1[$j];
                            $order->sgst = $request->sgst1[$j];
                            $order->amount = $request->amount1[$j];
                            if($request->buttonStatus == 'save')
                                $order->status = 2;
                            else
                                $order->status = 1;

                            $order->save();
                        }
                    }
    
                    if($i == 1)
                    {
                        $quotOrderCount = count($request->productId2);  
                        for($j=0; $j<$quotOrderCount; $j++)
                        {
                            if(!isset($request->orderId2[$j]))
                                $order = new StoreQuotOrder;
                            else
                                $order = StoreQuotOrder::find($request->orderId2[$j]);

                            $order->quotationId = $quotation->id;
                            $order->productId = $request->productId2[$j];
                            $order->qty = $request->qty2[$j];
                            $order->forDate = $request->forDate2[$j];
                            $order->remark = $request->remark2[$j];
                            $order->unitPrice = $request->unitPrice2[$j];
                            $order->discount = $request->discount2[$j];
                            $order->cgst = $request->cgst2[$j];
                            $order->sgst = $request->sgst2[$j];
                            $order->amount = $request->amount2[$j];
                            if($request->buttonStatus == 'save')
                                $order->status = 2;
                            else
                                $order->status = 1;


                            $order->save();
                        }
                    }
    
                    if($i == 2)
                    {
                        $quotOrderCount = count($request->productId3);  
                        for($j=0; $j<$quotOrderCount; $j++)
                        {
                            if(!isset($request->orderId3[$j]))
                                $order = new StoreQuotOrder;
                            else
                                $order = StoreQuotOrder::find($request->orderId3[$j]);

                            $order->quotationId = $quotation->id;
                            $order->productId = $request->productId3[$j];
                            $order->qty = $request->qty3[$j];
                            $order->forDate = $request->forDate3[$j];
                            $order->remark = $request->remark3[$j];
                            $order->unitPrice = $request->unitPrice3[$j];
                            $order->discount = $request->discount3[$j];
                            $order->cgst = $request->cgst3[$j];
                            $order->sgst = $request->sgst3[$j];
                            $order->amount = $request->amount3[$j];
                            if($request->buttonStatus == 'save')
                                $order->status = 2;
                            else
                                $order->status = 1;


                            $order->save();
                        }
                    }
                }
            }
            
            if($request->buttonStatus == 'save')
                return redirect('/quotation/saveList')->with("success","Quotation Save Successfully.");
            else
                return redirect('/quotation/list')->with("success","Quotation Generated Successfully.");
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
    }

    public function saveList(Request $request)
    {

        $user = Auth::user();
        $userType = Auth::user()->userType;
        $reqDepartmentId = Auth::user()->reqDepartmentId;
        $typeOfCompany = explode(',',$user->typeOfCompany);

        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801')
        {
            $vendors = StoreVendor::where('active', 1)->pluck('name', 'id');
            $raisedBysIds = StoreQuotation::pluck('raisedBy');
            $users = User::whereIn('id', $raisedBysIds)->orderBy('name')->pluck('name','id');

            $quotations = StoreQuotation::join('store_vendors', 'store_quotations.vendorId', 'store_vendors.id')
            ->join('type_of_companies', 'store_quotations.typeOfCompany', 'type_of_companies.id')
            ->select('store_vendors.name as vendorName', 'store_quotations.*', 'type_of_companies.shortName as typeOfCompanyName')
            ->where('store_quotations.quotStatus', 'Pending');
            if($userType == '801' && $reqDepartmentId!= 12)
                $quotations = $quotations->where('store_quotations.raisedBy', Auth::user()->id);

            if($request->myInputVendorName != '')
                $quotations = $quotations->where('store_vendors.name', $request->myInputVendorName);

            if($request->raisedBys != '')
                $quotations = $quotations->where('store_quotations.raisedBy', $request->raisedBys);

            if($request->forMonth != '')
            {
                $startDate = $request->forMonth.'-01';
                $endDate = date('Y-m-t', strtotime($startDate));
                $quotations = $quotations->whereBetween('store_quotations.created_at', [$startDate, $endDate]);
            }
            else
            {
                $startDate = date('Y-m-01');
                $endDate = date('Y-m-t');
                $quotations = $quotations->whereBetween('store_quotations.created_at', [$startDate, $endDate]);
            }

            if($userType == '61')
            {
                $quotations = $quotations->whereIn('store_quotations.typeOfCompany', $typeOfCompany);
            }

            $quotations = $quotations->orderBy('store_quotations.commQuotNo')
            ->where('store_quotations.status', 2)
            ->get();           

            return view('storeAdmin.quotations.saveList')->with(['myInputVendorName'=>$request->myInputVendorName,
            'raisedBys'=>$request->raisedBys, 'forMonth'=>$request->forMonth,  'users'=>$users, 'vendors'=>$vendors, 'quotations'=>$quotations]);
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page.");
        }
    }

    public function show($id)
    {
        $userType = Auth::user()->userType;
        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801')
        {
           $quotations = StoreQuotation::join('type_of_companies', 'store_quotations.typeOfCompany', 'type_of_companies.id')
            ->select('store_quotations.*', 'type_of_companies.name as typeOfCompany')
            ->where('store_quotations.commQuotNo', $id)
            ->get();
            $approveQuot = StoreQuotation::where('commQuotNo', $id)->where('quotStatus', 'Approved')->count();

            // FuelVehicle

            return view('storeAdmin.quotations.viewQuotation')->with(['quotations'=>$quotations,'approveQuot'=>$approveQuot]);

        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
    }

    public function approveQuotation(Request $request)
    {
        $percentArr = $request->input('percent');
        $user = Auth::user();

        // ✅ Authorization check
        if (!in_array($user->userType, ['701', '501'])) {
            return redirect()->back()->withInput()->with("error", "You do not have permission to access this page.");
        }


        DB::beginTransaction();

        try {
            $quotationId = $request->input('optradio');
            $stQuot = StoreQuotation::find($quotationId);


            if (!$stQuot) {
                return back()->with('error', 'Invalid quotation selected.');
            }
        
            // ✅ Validate required payment inputs
            if (empty($percentArr) && $stQuot->alreadyPaid == 0) {
                return back()->with('error', 'At least one payment row is required.');
            }

            // ✅ Check if PO already exists
            $existingPO = StorePurchaseOrder::where('quotationId', $stQuot->id)->first();
            if ($existingPO) {
                return redirect()->back()->with("error", "Purchase Order for this quotation has already been generated.");
            }

            // ✅ Generate unique PO number
            $util =  new Utility;
            $finYear = $util->getFinancialYear(date('Y-m-d'));
            $tOCompany = TypeOfCompany::where('id', $stQuot->typeOfCompany)->where('status', 1)->value('shortName');
            $serialNo = StorePurchaseOrder::orderBy('serialNo', 'desc')->whereNotNull('serialNo')->value('serialNo');
            if($serialNo == '')
                $serialNo = 1;
            else
                $serialNo=$serialNo+1;
        
            if($serialNo >= 1 && $serialNo <= 9)
                $poNumber = $tOCompany.'/'.$finYear.'/PO/0'.$serialNo;
            else
                $poNumber = $tOCompany.'/'.$finYear.'/PO/'.$serialNo;

            // Double-check uniqueness (very rare case)
            $existingPONumber = StorePurchaseOrder::where('poNumber', $poNumber)->first();
            if ($existingPONumber) {
                return redirect()->back()->with("error", "Duplicate PO number generated. Please try again.");
            }

            $totalPOAmount = 0;

            // ✅ Save payment schedule only if not already paid
            if ($stQuot->alreadyPaid == 0) {
                $dateArr   = $request->input('forDate');
                $amountArr = $request->input('amount');
                $remarkArr = $request->input('remark');

                $count = count($percentArr);

                for ($i = 0; $i < $count; $i++) {
                    if (!empty($percentArr[$i]) && !empty($dateArr[$i]) && !empty($amountArr[$i])) {
                        $payment = new StoreQuotationPayment;
                        $payment->poNumber = $poNumber;
                        $payment->quotationId = $stQuot->id;
                        $payment->percent = $percentArr[$i];
                        $payment->forDate = $dateArr[$i];
                        $payment->amount = floatval($amountArr[$i]);
                        $payment->remark = $remarkArr[$i] ?? null;
                        $payment->updated_by = $user->username;
                        $payment->save();

                        $totalPOAmount += floatval($amountArr[$i]);
                    }
                }
            }

            // ✅ Update quotation
            $stQuot->poAmount = $stQuot->finalRs;
            $stQuot->quotStatus = "Approved";
            $stQuot->updated_by = $user->username;
            $stQuot->save();

            // ✅ Update related fuel entry if any
            if (!empty($stQuot->fuelEntryId)) {
                $entry = FuelFilledEntry::find($stQuot->fuelEntryId);
                if ($entry) {
                    $entry->status = 2; // Consider replacing with named constant
                    $entry->updated_by = $user->username;
                    $entry->save();
                }
            }

            // ✅ Mark all quotations with same commQuotNo as checked
            StoreQuotation::where('commQuotNo', $stQuot->commQuotNo)
                ->update(['checkedByAuthority' => 1]);

            // ✅ Create purchase order
            $porder = new StorePurchaseOrder;
            $porder->quotationId = $stQuot->id;
            $porder->serialNo = $serialNo;
            $porder->poNumber = $poNumber;
            $porder->poAmount = $stQuot->poAmount;
            $porder->generatedDate = now()->format('Y-m-d');
            $porder->updated_by = $user->username;

            if ($stQuot->alreadyPaid == 1) {
                $porder->paidAmount = $stQuot->poAmount;
                $porder->alreadyPaid = $stQuot->alreadyPaid;
            }

            $porder->save();

            DB::commit();

            return redirect('/quotation/list')->with("success", "Quotation approved and PO generated successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with("error", "Error: " . $e->getMessage());
        }
    }


    // public function approveQuotation(Request $request)
    // {
    //     $percentArr = $request->input('percent');
    //     $userType = Auth::user()->userType;
    //     // Only allow authorized user types
    //     if (!in_array($userType, ['701', '501'])) {
    //         return redirect()->back()->withInput()->with("error", "You do not have permission to access this page.");
    //     }

    //     if (empty($percentArr)) {
    //         return back()->with('error', 'At least one payment row is required.');
    //     }

    //     DB::beginTransaction();
    
    //     try {
    //          $stQuot = StoreQuotation::find($request->optradio);
    
    //         // ✅ Prevent duplicate PO generation
    //         $existingPO = StorePurchaseOrder::where('quotationId', $stQuot->id)->first();
    //         if ($existingPO) {
    //             return redirect()->back()->with("error", "Purchase Order for this quotation has already been generated.");
    //         }
    
    //         $poNumber = "PO" . now()->format('YmdHis');
    //         $totalPOAmount = 0;
    
    //         $existingPONumber = StorePurchaseOrder::where('poNumber', $poNumber)->first();
    //         if ($existingPONumber) {
    //             return redirect()->back()->with("error", "Purchase Order for this quotation has already been generated.");
    //         }

    //         // ✅ Add payments only if not already paid
    //         if ($stQuot->alreadyPaid == 0) {
                    
    //                 $dateArr = $request->input('forDate');
    //                 $amountArr = $request->input('amount');
    //                 $remarkArr = $request->input('remark');

    //                 $count = count($percentArr);

    //                 for ($i = 0; $i < $count; $i++) {
    //                     if (!empty($percentArr[$i]) && !empty($dateArr[$i]) && !empty($amountArr[$i])) {
    //                         $payment = new StoreQuotationPayment;
    //                         $payment->poNumber = $poNumber;
    //                         $payment->quotationId = $stQuot->id;
    //                         $payment->percent = $percentArr[$i];
    //                         $payment->forDate = $dateArr[$i];
    //                         $payment->amount = $amountArr[$i];
    //                         $payment->remark = $remarkArr[$i] ?? null;
    //                         $payment->updated_by = Auth::user()->username;
    //                         $payment->save();

    //                         $totalPOAmount += $amountArr[$i];
    //                     }
    //                 }
    //             }

    
    //         // ✅ Update quotation
    //         $stQuot->poAmount = $stQuot->finalRs;
    //         $stQuot->quotStatus = "Approved";
    //         $stQuot->updated_by = Auth::user()->username;
    //         $stQuot->save();
    
    //         // ✅ Update related fuel entry if any
    //         if (!empty($stQuot->fuelEntryId)) {
    //             $entry = FuelFilledEntry::find($stQuot->fuelEntryId);
    //             if ($entry) {
    //                 $entry->status = 2; // Consider using a named constant for status
    //                 $entry->updated_by = Auth::user()->username;
    //                 $entry->save();
    //             }
    //         }
    
    //         // ✅ Mark all quotations with same commQuotNo as checked
    //         StoreQuotation::where('commQuotNo', $stQuot->commQuotNo)
    //             ->update(['checkedByAuthority' => 1]);
    
    //         // ✅ Create new purchase order
    //         $porder = new StorePurchaseOrder;
    //         $porder->quotationId = $stQuot->id;
    //         $porder->poNumber = $poNumber;
    //         $porder->poAmount = $stQuot->poAmount;
    //         $porder->generatedDate = now()->format('Y-m-d');
    //         $porder->updated_by = Auth::user()->username;
    
    //         if ($stQuot->alreadyPaid == 1) {
    //             $porder->paidAmount = $stQuot->poAmount;
    //             $porder->alreadyPaid = $stQuot->alreadyPaid;
    //         }
    
    //         $porder->save();
    
    //         DB::commit();
    
    //         return redirect('/quotation/list')->with("success", "Quotation approved and PO generated successfully.");
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->withInput()->with("error", "Error: " . $e->getMessage());
    //     }
    // }

    public function printQuotation($id)
    {
        $userType = Auth::user()->userType;
        if($userType == '701' || $userType == '501' || $userType == '801')
        {
            $quotation = StoreQuotation::where('id', $id)->first();
            $products = StoreQuotOrder::join('store_products', 'store_quot_orders.productId', 'store_products.id')
            ->select('store_products.productCode', 'store_products.name', 'store_quot_orders.*')
            ->where('store_quot_orders.quotationId', $id)->get();
            $pdf = PDF::loadView('storeAdmin.pdfs.quotation',compact('products','quotation'));
            return $pdf->stream('quotation-'.$quotation->quotNo);
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
    }

    // Pending PO List
    public function purchaseOrderList(Request $request)
    {
        $user = Auth::user();
        $userType = $user->userType;
        $reqDepartmentId = $user->reqDepartmentId;
        $typeOfCompany = explode(',', $user->typeOfCompany ?? '');

        if (in_array($userType, ['61', '701', '501', '801'])) {

            // Get filters from the request
            $vendorName = $request->vendorName;
            $poNumber = $request->poNumber;
            $raisedBys = $request->raisedBys;

            // Base query: Get fully paid POs from the last 3 months
            $orders = StorePurchaseOrder::join('store_quotations', 'store_purchase_orders.quotationId', '=', 'store_quotations.id')
                ->join('store_vendors', 'store_quotations.vendorId', '=', 'store_vendors.id')
                ->select(
                    'store_quotations.quotNo',
                    'store_vendors.name',
                    'store_quotations.raisedBy',
                    'store_purchase_orders.*',
                    'store_quotations.shippingAddress'
                )
                ->where('store_purchase_orders.status', 1)
                ->whereColumn('store_purchase_orders.paidAmount', '!=','store_purchase_orders.poAmount')
                ->where('store_purchase_orders.created_at', '>=', now()->subMonths(3));

            // Department restriction for userType 801
            if ($userType == '801' && $reqDepartmentId != 12) {
                $orders->where('store_quotations.raisedBy', $user->id);
            }

            // Apply filters
            if (!empty($vendorName)) {
                $orders->where('store_vendors.name', $vendorName);
            }

            if (!empty($poNumber)) {
                $orders->where('store_purchase_orders.poNumber', $poNumber);
            }

            if (!empty($raisedBys)) {
                $orders->where('store_quotations.raisedBy', $raisedBys);
            }

            // Filter by company type (only for userType 61)
            if ($userType == '61') {
                $orders->whereIn('store_quotations.typeOfCompany', $typeOfCompany);
            }

            // Finalize query
            $orders = $orders->orderByDesc('store_purchase_orders.created_at')
                ->paginate(15)
                ->appends($request->except('page'));

            // Get all users who raised quotations
            $raisedBysIds = StoreQuotation::whereNotNull('raisedBy')->pluck('raisedBy');
            $users = User::whereIn('id', $raisedBysIds)->orderBy('name')->pluck('name', 'id');

            return view('storeAdmin.purchases.list', compact('orders', 'raisedBys', 'users', 'vendorName', 'poNumber'));

        } else {
            return redirect()->back()->withInput()->with('error', 'You do not have permission to access this page.');
        }

    }

    // paid po list
    public function paidPurchaseOrderList(Request $request)
    {
        $user = Auth::user();
        $userType = $user->userType;
        $reqDepartmentId = $user->reqDepartmentId;
        $typeOfCompany = explode(',', $user->typeOfCompany ?? '');

        if (in_array($userType, ['61', '701', '501', '801'])) {

            // Get filters from the request
            $vendorName = $request->vendorName;
            $poNumber = $request->poNumber;
            $raisedBys = $request->raisedBys;

            // Base query: Get fully paid POs from the last 3 months
            $orders = StorePurchaseOrder::join('store_quotations', 'store_purchase_orders.quotationId', '=', 'store_quotations.id')
                ->join('store_vendors', 'store_quotations.vendorId', '=', 'store_vendors.id')
                ->select(
                    'store_quotations.quotNo',
                    'store_vendors.name',
                    'store_quotations.raisedBy',
                    'store_purchase_orders.*',
                    'store_quotations.shippingAddress'
                )
                ->where('store_purchase_orders.status', 1)
                ->whereColumn('store_purchase_orders.paidAmount', 'store_purchase_orders.poAmount')
                ->where('store_purchase_orders.created_at', '>=', now()->subMonths(3));

            // Department restriction for userType 801
            if ($userType == '801' && $reqDepartmentId != 12) {
                $orders->where('store_quotations.raisedBy', $user->id);
            }

            // Apply filters
            if (!empty($vendorName)) {
                $orders->where('store_vendors.name', $vendorName);
            }

            if (!empty($poNumber)) {
                $orders->where('store_purchase_orders.poNumber', $poNumber);
            }

            if (!empty($raisedBys)) {
                $orders->where('store_quotations.raisedBy', $raisedBys);
            }

            // Filter by company type (only for userType 61)
            if ($userType == '61') {
                $orders->whereIn('store_quotations.typeOfCompany', $typeOfCompany);
            }

            // Finalize query
            $orders = $orders->orderByDesc('store_purchase_orders.created_at')
                ->paginate(15)
                ->appends($request->except('page'));

            // Get all users who raised quotations
            $raisedBysIds = StoreQuotation::whereNotNull('raisedBy')->pluck('raisedBy');
            $users = User::whereIn('id', $raisedBysIds)->orderBy('name')->pluck('name', 'id');

            return view('storeAdmin.purchases.paidPO', compact('orders', 'raisedBys', 'users', 'vendorName', 'poNumber'));

        } else {
            return redirect()->back()->withInput()->with('error', 'You do not have permission to access this page.');
        }
    }

    public function viewPO($id)
    {
        $userType = Auth::user()->userType;
        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801')
        {
            $orders = StorePurchaseOrder::count();
            $pOrder = StorePurchaseOrder::join('store_quotations', 'store_purchase_orders.quotationId', 'store_quotations.id')
            ->join('store_vendors', 'store_quotations.vendorId', 'store_vendors.id')
            ->join('type_of_companies', 'store_quotations.typeOfCompany', 'type_of_companies.id')
            ->select('store_quotations.id as quotationId','store_quotations.quotNo','store_vendors.landlineNo','store_vendors.address','store_vendors.name', 'store_vendors.PANNO','store_vendors.GSTNo',
            'store_purchase_orders.*','type_of_companies.name as tempTypeOfCompany','store_quotations.transportationRs','store_quotations.loadingRs','store_quotations.unloadingRs',
            'store_quotations.finalRs','store_quotations.raisedBy', 'store_quotations.totalRs','store_quotations.shippingAddress', 'store_quotations.reqNo', 
            'store_quotations.quotationFor','store_quotations.shippingBranchId','store_quotations.typeOfCompany','store_quotations.quotationFile')
            ->where('store_purchase_orders.id', $id)
            ->first();
            if($pOrder)
            {
                $raisedBy = User::where('id', $pOrder->raisedBy)->value('name');

                $branchName = ContactusLandPage::where('id', $pOrder->shippingBranchId)->value('shortName');

                $payments = StoreQuotationPayment::join('store_quotations', 'store_quotation_payments.quotationId', 'store_quotations.id')
                ->join('store_vendors', 'store_quotations.vendorId', 'store_vendors.id')
                ->select('store_quotation_payments.*', 'store_vendors.id as vendorId','store_vendors.name as vendorName', 'store_vendors.accountNo', 'store_vendors.IFSCCode', 'store_vendors.bankBranch')
                ->where('store_quotation_payments.quotationId', $pOrder->quotationId)
                ->orderBy('store_quotation_payments.forDate')
                ->get();

                return view('storeAdmin.purchases.viewPO')->with(['branchName'=>$branchName,'raisedBy'=>$raisedBy,'pOrder'=>$pOrder, 'orders'=>$orders, 'payments'=>$payments]);
            }
            else
            {
                return redirect()->back()->withInput()->with("error","PO Not Found...");
            }
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
    }

    public function viewPONumber($poNumber)
    { 
        $userType = Auth::user()->userType;
        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801')
        {
            $poNumber = base64_decode($poNumber);
            $orders = StorePurchaseOrder::count();
            $pOrder = StorePurchaseOrder::join('store_quotations', 'store_purchase_orders.quotationId', 'store_quotations.id')
            ->join('store_vendors', 'store_quotations.vendorId', 'store_vendors.id')
            ->select('store_quotations.id as quotationId','store_quotations.quotNo','store_vendors.landlineNo','store_vendors.address','store_vendors.name', 'store_vendors.PANNO','store_vendors.GSTNo',
            'store_purchase_orders.*','store_quotations.transportationRs','store_quotations.loadingRs','store_quotations.unloadingRs',
            'store_quotations.finalRs','store_quotations.raisedBy', 'store_quotations.totalRs','store_quotations.shippingAddress', 'store_quotations.reqNo', 
            'store_quotations.quotationFor','store_quotations.shippingBranchId','store_quotations.typeOfCompany')
            ->where('store_purchase_orders.poNumber', $poNumber)
            ->first();
            if($pOrder)
            {
                $raisedBy = User::where('id', $pOrder->raisedBy)->value('name');

                $branchName = ContactusLandPage::where('id', $pOrder->shippingBranchId)->value('shortName');

                $payments = StoreQuotationPayment::join('store_quotations', 'store_quotation_payments.quotationId', 'store_quotations.id')
                ->join('store_vendors', 'store_quotations.vendorId', 'store_vendors.id')
                ->select('store_quotation_payments.*', 'store_vendors.id as vendorId','store_vendors.name as vendorName', 'store_vendors.accountNo', 'store_vendors.IFSCCode', 'store_vendors.bankBranch')
                ->where('store_quotation_payments.quotationId', $pOrder->quotationId)
                ->orderBy('store_quotation_payments.forDate')
                ->get();

                return view('storeAdmin.purchases.viewPO')->with(['branchName'=>$branchName,'raisedBy'=>$raisedBy,'pOrder'=>$pOrder, 'orders'=>$orders, 'payments'=>$payments]);
            }
            else
            {
                return redirect()->back()->withInput()->with("error","PO Not Found...");
            }
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
    }

    public function printPO($id)
    {
        $userType = Auth::user()->userType;
        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801')
        {
            $pOrder = StorePurchaseOrder::join('store_quotations', 'store_purchase_orders.quotationId', 'store_quotations.id')
            ->join('store_vendors', 'store_quotations.vendorId', 'store_vendors.id')
            ->join('type_of_companies', 'store_quotations.typeOfCompany', 'type_of_companies.id')
            ->select('store_quotations.id as quotationId','store_quotations.quotNo','store_vendors.landlineNo','store_vendors.address','store_vendors.name', 'store_vendors.PANNO','store_vendors.GSTNo',
            'store_purchase_orders.*','store_quotations.transportationRs','store_quotations.loadingRs','store_quotations.unloadingRs',
            'store_quotations.finalRs','store_quotations.totalRs','type_of_companies.name as tempTypeOfCompany','store_quotations.shippingAddress', 'store_quotations.reqNo', 'store_quotations.quotationFor')
            ->where('store_purchase_orders.id', $id)
            ->first();
            $pdf = PDF::loadView('storeAdmin.pdfs.printPO',compact('pOrder'));
            return $pdf->stream('PO-'.$pOrder->poNumber);
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
    }

    public function getProducts($productId)
    {
        
        $util=new Utility(); 
        $stock = $util->getCurrentProductStock($productId);
        $temp = StoreProduct::join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->join('store_units', 'store_products.unitId', 'store_units.id')
        ->select("store_products.id", "store_categories.name as categoryName", "store_sub_categories.name as subCategoryName", 
        "store_products.size","store_products.color", "store_products.returnStatus","store_products.company", "store_products.productRate", 
        "store_products.stock", "store_products.name", 'store_products.productCode', 'store_units.name as unitName')
        ->where('store_products.id', $productId)
        ->first();
        $temp['stock'] =$stock;
        return $temp;
    }

    public function deactivate($commQuotNo)
    {
        $userType = Auth::user()->userType;
        if($userType == '501')
        {
            StoreQuotation::where('commQuotNo', $commQuotNo)->update(['quotStatus'=>'Rejected', 'updated_by'=>Auth::user()->username]);
            StoreQuotOrder::whereIn('quotationId', StoreQuotation::where('commQuotNo', $commQuotNo)->pluck('id'))->update(['status'=>0, 'updated_by'=>Auth::user()->username]);
            return redirect()->back()->withInput()->with("success","Quotation Rejected Successfully..");
        }
        else
        {
            StoreQuotation::where('commQuotNo', $commQuotNo)->update(['quotStatus'=>'Cancel', 'updated_by'=>Auth::user()->username]);
            StoreQuotOrder::whereIn('quotationId', StoreQuotation::where('commQuotNo', $commQuotNo)->pluck('id'))->update(['status'=>0, 'updated_by'=>Auth::user()->username]); 
            return redirect()->back()->withInput()->with("success","Quotation Cancel Successfully..");
        }
        
    }

    public function productList()
    {
      
        $products = StoreRequisitionProduct::select(DB::raw('sum(requiredQty) as total'), 'productId')
        ->whereIn('status', [0,1])
        ->where('created_at', '>=','2024-11-01 00:00:00')
        ->groupBy('productId')
        ->get();

        $prodList = [];
        if(count($products))
        {
            foreach($products as $temp)
            {
                $prodTemp = StoreProduct::find($temp->productId);
                if($prodTemp)
                {
                    if($prodTemp->stock < $temp->total)
                    {
                        $product = StoreProduct::join('store_categories', 'store_products.categoryId', 'store_categories.id')
                        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
                        ->join('store_halls', 'store_products.hallId', 'store_halls.id')
                        ->join('store_racks', 'store_products.rackId', 'store_racks.id')
                        ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
                        ->join('store_units', 'store_products.unitId', 'store_units.id')
                        ->select('store_categories.name as categoryName','store_halls.name as hallName','store_racks.name as rackName',
                        'store_sub_categories.name as subCategoryName', 'store_shels.name as shelfName','store_units.name as unitName', 
                        'store_products.*')
                        ->where('store_products.id', $temp->productId)
                        ->orderBy('store_products.id', 'desc')
                        ->first();
                        $product['requiredStock']=$temp->total;

                        $purchaseProd = StorePurchaseProduct::where('productId', $temp->productId)
                        ->where('status', '!=', 3)
                        ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-1 month')))
                        ->first();
                        if($purchaseProd)
                        {
                            $product['requiredStockStatus']=1;
                            $product['purchaseQty']=$purchaseProd->purchaseQty;
                            $product['requiredStockTime']=$purchaseProd->created_at;
                            $product['purchaseStatus']=$purchaseProd->status;
                            $product['purchaseId']=$purchaseProd->id;
                        }
                        else
                        {
                            $product['requiredStockStatus']=0;
                            $product['purchaseQty']=0;
                            $product['requiredStockTime']=0;
                            $product['purchaseStatus']=0;
                            $product['purchaseId']=0;
                        }
                        array_push($prodList,  $product);
                    }
                }
            }
        }

        $products =[];
       $products = $prodList;


        return view('storeAdmin.purchases.purchaseProductList')->with(['products'=>$products]);
    }

    public function completedProductList()
    {
        $products = StorePurchaseProduct::join('store_products', 'store_purchase_products.productId', 'store_products.id')
        ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->join('store_units', 'store_products.unitId', 'store_units.id')
        ->select('store_products.name','store_products.size','store_products.color', 'store_categories.name as categoryName', 
        'store_sub_categories.name as subCategoryName', 'store_units.name as unitName','store_purchase_products.*')
        ->where('store_purchase_products.status', 3)
        ->get();


        return view('storeAdmin.purchases.completedProductList')->with(['products'=>$products]);
    }

    public function updateProducts(Request $request)
    {
        $prodCount = count($request->check);
        if($prodCount)
        {
            DB::beginTransaction();
            try 
            {
                for($i=0; $i<$prodCount; $i++)
                {
                    if($request->status[$i] != 0)
                    {
                        $product = StoreProduct::where('id', $request->check[$i])->first();
                        $purProd = new StorePurchaseProduct;
                        $purProd->productId=$product->id;
                        $purProd->currentStock=$product->stock;
                        $purProd->requiredQty=$request->requiredStock[$i];
                        $purProd->purchaseQty=$request->purchaseQty[$i];
                        $purProd->status=$request->status[$i];
                        $purProd->updated_by=Auth::user()->username;
                       
                        if($purProd->save())
                        {
                            StoreRequisitionProduct::where('productId', $purProd->productId)->update(['status'=>$request->status[$i], 'updated_by'=>Auth::user()->username]);          
                        }
                    }
                }

                DB::commit();  
                return redirect()->back()->withInput()->with("success","Approved for Purchase Product....");       
            } catch (\Exception $e) {
                DB::rollback();
            }
        }
        else
        {
            return redirect()->back()->withInput()->with("success","Not selected any Product......");
        }            
    }  

    public function purchasedSuccessfully($id)
    {
        DB::beginTransaction();
        try 
        {    
            $storePurchased = StorePurchaseProduct::find($id);
            $storePurchased->status = 3;
            $storePurchased->updated_by=Auth::user()->username;
            $storePurchased->save();
            DB::commit();
            return redirect('/purchaseOrder/productList')->with("success","Product Purchased successfully.....");
        
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with("error","There is some issue.....");
        }
    }

    // Payments
    public function POPayment(Request $request)
    {
        $userType = Auth::user()->userType;
        $typeOfCompany = Auth::user()->typeOfCompany;
        $companyTypes = explode(" ",$typeOfCompany);
        $typeOfCompany = str_replace(',', '', $companyTypes);

        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801')
        {
            $payments = StoreQuotationPayment::join('store_quotations', 'store_quotation_payments.quotationId', 'store_quotations.id')
            ->join('store_vendors', 'store_quotations.vendorId', 'store_vendors.id')
            ->select('store_quotation_payments.*', 'store_vendors.id as vendorId','store_vendors.name as vendorName', 
            'store_vendors.accountNo', 'store_vendors.IFSCCode', 'store_vendors.bankBranch')
            ->where('store_quotation_payments.status', 1);
            if($userType == '801')
                $payments = $payments->where('store_quotations.raisedBy', Auth::user()->id);

            if($userType == '61')
                $payments = $payments->whereIn('store_quotations.typeOfCompany',$typeOfCompany);

             $payments = $payments->orderBy('store_quotation_payments.forDate')
            ->get();

            $paidPayments = StoreQuotationPayment::join('store_quotations', 'store_quotation_payments.quotationId', 'store_quotations.id')
            ->whereIn('store_quotation_payments.status', [2,3]);
            if($userType == '801')
                $paidPayments = $paidPayments->where('store_quotations.raisedBy', Auth::user()->id);

            if($userType == '61')
                $paidPayments = $paidPayments->whereIn('store_quotations.typeOfCompany',$typeOfCompany);

            $paidPayments = $paidPayments->count();

            return view('storeAdmin.payments.list')->with(['paidPayments'=>$paidPayments,'payments'=>$payments]);
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
    }

    public function POPaidPayments(Request $request)
    {
        $userType = Auth::user()->userType;      
       

        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801')
        {
            $forMonth = $request->forMonth;
            if($forMonth == '')
                $forMonth = date('Y-m');

            $payments = StoreQuotationPayment::join('store_quotations', 'store_quotation_payments.quotationId', 'store_quotations.id')
            ->join('store_vendors', 'store_quotations.vendorId', 'store_vendors.id')
            ->select('store_quotation_payments.*', 'store_vendors.id as vendorId','store_vendors.name as vendorName', 'store_vendors.accountNo', 'store_vendors.IFSCCode', 'store_vendors.bankBranch')
            ->whereIn('store_quotation_payments.status', [2,3])
            ->where('store_quotation_payments.forDate', '>=', date('Y-m-01', strtotime($forMonth)))
            ->where('store_quotation_payments.forDate', '<=', date('Y-m-t', strtotime($forMonth)));

            if($userType == '61')
            {
                $typeOfCompany = Auth::user()->typeOfCompany;
                $typeOfCompany = explode(',', $user->typeOfCompany);
                if($typeOfCompany == '')
                    $typeOfCompaniesData = TypeOfCompany::where('status', 1)->orderBy('shortName')->pluck('shortName', 'id');
                else
                    $typeOfCompaniesData = TypeOfCompany::whereIn('id',$typeOfCompany)->where('status', 1)->orderBy('shortName')->pluck('shortName', 'id');

                $typeOfCompanyId = $request->typeOfCompanyId;

                $typeOfCompany = str_replace(',', '', $companyTypes);
                if($typeOfCompanyId != '')
                    $payments = $payments->where('store_work_orders.typeOfCompany', $typeOfCompanyId);
            }
            else
            {
                $typeOfCompaniesData=[];
                $typeOfCompanyId='';
            }

            
            if($userType == '801')
                $payments = $payments->where('store_quotations.raisedBy', Auth::user()->id);

            if($userType == '61')
                $payments = $payments->whereIn('store_quotations.typeOfCompany', $typeOfCompany);

            $unpaidPayments = $payments->where('store_quotations.status', 1)->count();

            $payments = $payments->orderBy('store_quotation_payments.forDate', 'desc')
            ->get();
           
            return view('storeAdmin.payments.paidList', compact('typeOfCompanyId', 'typeOfCompaniesData','forMonth','payments', 'unpaidPayments'));
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
    }

    public function exportPOPayments($forMonth, $status)
    {     
        if($status == 2)
            $fileName = 'Paid_Payment_List'.date('M-Y', strtotime($forMonth)).'.xlsx';
        else
            $fileName = 'Unpaid_Payment_List.xlsx';

        return Excel::download(new PaymentExport($forMonth, $status), $fileName);
    }

    public function editQuotation($commQuotNo)
    {
        $userType = Auth::user()->userType;
        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801')
        {
            $quotations =  StoreQuotation::join('store_vendors', 'store_quotations.vendorId', 'store_vendors.id')
            ->join('users', 'store_quotations.raisedBy', 'users.id')
            ->join('type_of_companies', 'store_quotations.typeOfCompany', 'type_of_companies.id')
            ->select('users.name as userName','type_of_companies.name as typeOfCompanyName','store_vendors.name as vendorName',
            'store_vendors.address','store_vendors.materialProvider', 'store_quotations.*')
            ->where('commQuotNo', $commQuotNo)
            ->get();
            $categories = StoreCategory::whereActive(1)->orderBy('name')->pluck('name', 'id');
            return view('storeAdmin.quotations.editQuotation', compact('quotations', 'userType','categories'));
            
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
    }

    public function edit($id)
    {
        $userType = Auth::user()->userType;
        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801')
        {
            $vendorsId = StoreQuotation::where('commQuotNo', $id)->pluck('vendorId');
            $quotation =  StoreQuotation::where('commQuotNo', $id)->first();
            $productList = StoreQuotOrder::join('store_products', 'store_quot_orders.productId', 'store_products.id')
            ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
            ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
            ->select('store_categories.name as catagoryName', 'store_sub_categories.name as subCategoryName', 
            'store_products.color', 'store_products.size', 'store_products.color', 'store_products.name as productName',
             'store_products.id as productId', 'store_products.company', 'store_products.stock', 
            'store_quot_orders.*')
            ->where('store_quot_orders.quotationId', $quotation->id)
            ->get();

            $selectedVendors = StoreVendor::whereIn('id', $vendorsId)->get();
            $categories = StoreCategory::whereActive(1)->orderBy('name')->pluck('name', 'id');

            return view('storeAdmin.quotations.editVendorList')->with(['quotation'=>$quotation, 'productList'=>$productList,
            'selectedVendors'=>$selectedVendors, 'categories'=>$categories]);
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
    }

    public function updatePayment(Request $request)
    {
        DB::beginTransaction();
        try 
        {    
            $payment = StoreQuotationPayment::find($request->paymentId);
            $payment->vendorId=$request->vendorId;
            $payment->accountNo=$request->accountNo;
            $payment->bankBranch=$request->bankBranch;
            $payment->IFSCCode=$request->IFSCCode;
            $payment->transactionId=$request->transactionNumber;
            $payment->paymentRemark=$request->paymentRemark;
            $payment->transferDate=$request->transferDate;
            $payment->updated_by=Auth::user()->username;
            $payment->status=2;
            if($payment->save())
            {
                $vendor = StoreVendor::find($request->vendorId);
                $vendor->accountNo=($request->accountNo != '')?$request->accountNo:$vendor->accountNo;
                $vendor->IFSCCode=($request->IFSCCode != '')?$request->IFSCCode:$vendor->IFSCCode;
                $vendor->bankBranch=($request->bankBranch != '')?$request->bankBranch:$vendor->bankBranch;
                $vendor->updated_by=Auth::user()->username;
                if($vendor->save())
                {
                    $poDetails = StorePurchaseOrder::where('quotationId', $payment->quotationId)->first();
                    $poDetails->paidAmount = $poDetails->paidAmount + $payment->amount;
                    $poDetails->updated_by=Auth::user()->username;
                    $poDetails->save();
                }
            }
            DB::commit();
            return redirect('/payments/POPayment')->with("success","Payment Details Updated Successfully....");
        
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with("error","There is some issue.....");
        }
    }  

    public function rejectPayment($paymentId)
    {
        DB::beginTransaction();
        try 
        {    
            $payment = StoreQuotationPayment::find($paymentId);
            $payment->updated_by=Auth::user()->username;
            $payment->status=3;
            $payment->save();
            DB::commit();
            return redirect('/payments/POPayment')->with("success","Payment Rejected Successfully....");
        
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with("error","There is some issue.....");
        }
    }
}
