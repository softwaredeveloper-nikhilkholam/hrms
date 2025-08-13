<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\stores\FuelExport;
use App\FuelFilledEntry;
use App\FuelVehicle;
use App\StoreProduct;
use App\StoreVendor;
use App\ContactusLandPage;
use App\StoreQuotOrder;
use App\TypeOfCompany;
use App\StoreQuotation;
use Auth;
use DB;
use Image;
use Excel;

class FuelFilledSystemsController extends Controller
{
    public function index()
    {
        $userType = Auth::user()->userType;

        $rows = FuelFilledEntry::join('contactus_land_pages', 'fuel_filled_entries.branchId','contactus_land_pages.id')
        ->join('store_vendors', 'fuel_filled_entries.vendorId','store_vendors.id')
        ->select('fuel_filled_entries.*', 'store_vendors.name as vendorName','contactus_land_pages.branchName as branchName', 'contactus_land_pages.zoneName')
        ->orderBy('fuel_filled_entries.created_at', 'desc')
        ->where('fuel_filled_entries.active', 1)
        ->get();
     
        return view('storeAdmin.fuelMgmt.list', compact('rows'));
    }

    public function dlist()
    {
        $userType = Auth::user()->userType;

        $rows = FuelFilledEntry::join('contactus_land_pages', 'fuel_filled_entries.branchId','contactus_land_pages.id')
        ->select('fuel_filled_entries.*', 'contactus_land_pages.branchName as branchName', 'contactus_land_pages.zoneName')
        ->where('fuel_filled_entries.active', 0)
        ->orderBy('fuel_filled_entries.created_at', 'desc')
        ->get();
     
        return view('storeAdmin.fuelMgmt.dlist', compact('rows'));
    }
    public function create()
    {
        $userType = Auth::user()->userType;
        $vendors = StoreVendor::whereActive(1)->where('fuelType', 1)->orderBy('name')->pluck('name','id');
        $branches =ContactusLandPage::where('zoneStatus', 1)->whereActive(1)->orderBy('zoneName')->pluck('zoneName','id');
        return view('storeAdmin.fuelMgmt.create', compact('vendors', 'branches', 'rows'));
    }

    public function store(Request $request)
    {
        $userType = Auth::user()->userType;
      
        $fuelEntry = new FuelFilledEntry;
        $fuelEntry->forDate = date('Y-m-d');
        if($request->fuelType == 1)
            $fuelEntry->dieselRate = $request->fuelRate;
        else
            $fuelEntry->petrolRate = $request->fuelRate;

        $fuelEntry->vendorId = $request->vendorId;
        $fuelEntry->branchId = $request->branchId;
        $fuelEntry->updated_by = Auth::user()->username;
        $fuelEntry->save();

        return redirect('/fuelSystems')->with('success', 'Fuel Filled Entry Added Successfully');
    }

    public function fuelVehicleEntry($id)
    {
        $fuelEntry = FuelFilledEntry::join('contactus_land_pages', 'fuel_filled_entries.branchId','contactus_land_pages.id')
        ->select('fuel_filled_entries.*', 'contactus_land_pages.branchName as branchName')
        ->where('fuel_filled_entries.id', $id)
        ->first();

        $vehicleListLast5 = FuelVehicle::join('store_products', 'fuel_vehicles.vehicleId', 'store_products.id')
        ->select('fuel_vehicles.*', 'store_products.name as busNo')
        ->where('fuel_vehicles.fuelEntryId', $id)
        ->where('fuel_vehicles.active', 1)
        ->orderBy('fuel_vehicles.created_at', 'desc')
        ->take(5)
        ->get();

        $vehicles = StoreProduct::join('store_sub_categories', 'store_products.subCategoryId', '=', 'store_sub_categories.id')
        ->whereIn('store_sub_categories.name', ['Vehicles', 'Genset'])
        ->orderBy('store_products.name')
        ->pluck('store_products.name', 'store_products.id');

        return view('storeAdmin.fuelMgmt.fuelVehicleEntry', compact('vehicles', 'fuelEntry','vehicleListLast5'));
    }

    public function storeFuelVehicleEntry(Request $request)
    {
        $fuelVehicle = new FuelVehicle;
        $fuelEntry = FuelFilledEntry::find($request->fuelEntryId);

        $fuelVehicle->fuelEntryId = $request->fuelEntryId;
        $fuelVehicle->forDate = date('Y-m-d');
        $fuelVehicle->vehicleId = $request->vehicleId;
        $fuelVehicle->oldKM = $request->oldKM;   
        $fuelVehicle->newKM = $request->newKM;   
        $fuelVehicle->ltr = $request->ltr;   
        if($fuelEntry->fuelType == 1)
        {
            $fuelVehicle->amount = $request->ltr*$fuelEntry->dieselRate;  
            $fuelVehicle->fuelRate=$fuelEntry->dieselRate;  
        }
        else
        {
            $fuelVehicle->amount = $request->ltr*$fuelEntry->petrolRate;
            $fuelVehicle->fuelRate=$fuelEntry->petrolRate;  
        }

        $fuelVehicle->average = round(($request->newKM - $request->oldKM) / $request->ltr, 2);   
        $fuelVehicle->status = $request->status;   
        $fuelVehicle->updated_by = Auth::user()->username;

        if(!empty($request->file('meterPhoto')))
        {
            $fileName = date('ymdhi').'1.'.$request->meterPhoto->extension();  
            $request->meterPhoto->move(public_path('storeAdmin/images/fuelFillingimages/fuelReceipts/'), $fileName);
            $fuelVehicle->image1 = $fileName;
        }

        if(!empty($request->file('fuelReadingPhoto')))
        {
            $fileName = date('ymdhi').'2.'.$request->fuelReadingPhoto->extension();  
            $request->fuelReadingPhoto->move(public_path('storeAdmin/images/fuelFillingimages/fuelReceipts/'), $fileName);
            $fuelVehicle->image2 = $fileName;
        }

        if($fuelVehicle->save())
        {
            
            $fuelEntry->totalVehicle = FuelVehicle::where('fuelEntryId', $request->fuelEntryId)->count();
            if($fuelEntry->fuelType == 1)
            {
                $fuelEntry->totalDiesel = FuelVehicle::where('fuelEntryId', $request->fuelEntryId)->sum('ltr');
                $fuelEntry->totalDieselRs = $fuelEntry->totalDiesel * $fuelEntry->dieselRate;
            }
            else
            {
                $fuelEntry->totalPetrol = FuelVehicle::where('fuelEntryId', $request->fuelEntryId)->sum('ltr');
                $fuelEntry->totalPetrolRs = $fuelEntry->totalPetrol * $fuelEntry->petrolRate;
            }
            
            $fuelEntry->save();
            return redirect('/fuelSystems/fuelVehicleEntry/'.$fuelEntry->id)->with('success', 'Vehicle Entry Added Successfully');
        }
    }

    public function exportExcelSheet($id)
    {
        $fileName = 'FuelList'.date('d-M-Y').'.xlsx';
        return Excel::download(new FuelExport($id), $fileName);
    }


    public function fuelVehicleDetails($id)
    {
        $fuelEntryList =  FuelFilledEntry::join('contactus_land_pages', 'fuel_filled_entries.branchId','contactus_land_pages.id')
        ->select('fuel_filled_entries.*', 'contactus_land_pages.branchName as branchName', 'contactus_land_pages.zoneName')
        ->where('fuel_filled_entries.id', $id)
        ->first();
        $vehicleList = FuelVehicle::join('store_products', 'fuel_vehicles.vehicleId', 'store_products.id')
        ->select('fuel_vehicles.*', 'store_products.name as busNo')
        ->where('fuel_vehicles.fuelEntryId', $id)
        ->where('fuel_vehicles.active', 1)
        ->get();
        return view('storeAdmin.fuelMgmt.fuelVehicleDetails', compact('fuelEntryList', 'vehicleList')); 
    }

    public function edit($id)
    {
        $fuelEntry = FuelFilledEntry::find($id);
        $vehicleCount = FuelVehicle::where('fuelEntryId', $id)->where('active', 1)->count();
        if($vehicleCount == 0)
            return redirect('/fuelSystems')->with('success', 'You have added vehicle entry, so you not edit fuel entry');

        $vendors = StoreVendor::whereActive(1)->orderBy('name')->pluck('name','id');
        $branches =ContactusLandPage::where('zoneStatus', 1)->whereActive(1)->orderBy('zoneName')->pluck('zoneName','id');
        return view('storeAdmin.fuelMgmt.edit', compact('fuelEntry', 'vendors', 'branches'));
    }

    public function update(Request $request, $id)
    {
        $fuelEntry = FuelFilledEntry::find($id);
        $fuelEntry->forDate = date('Y-m-d');
        if($request->fuelType == 1)
            $fuelEntry->dieselRate = $request->fuelRate;
        else
            $fuelEntry->petrolRate = $request->fuelRate;

        $fuelEntry->vendor = $request->vendorId;
        $fuelEntry->branchId = $request->branchId;
        $fuelEntry->updated_by = Auth::user()->username;
        $fuelEntry->save();

        return redirect('/fuelSystems')->with('success', 'Fuel Filled Entry Updated Successfully');
    }

    public function activeDeactivateFuelEntry($id)
    {
        $fuelEntry = FuelFilledEntry::findOrFail($id);
    
        $fuelEntry->active = !$fuelEntry->active;
        $fuelEntry->updated_by = Auth::user()->username;
        $fuelEntry->save();
    
        $redirectUrl = $fuelEntry->active ? '/fuelSystems' : '/fuelSystems/dlist';
    
        return redirect($redirectUrl)->with('success', 'Fuel Filled Entry updated successfully.');
    }

    public function generateQuotation($id)
    {
        $fuelEntry = FuelFilledEntry::join('contactus_land_pages', 'fuel_filled_entries.branchId','contactus_land_pages.id')
        ->join('store_vendors', 'fuel_filled_entries.vendorId','store_vendors.id')
        ->select('fuel_filled_entries.*', 'store_vendors.address as vendorAddress', 'store_vendors.materialProvider',
        'store_vendors.name as vendorName','store_vendors.bankBranch','store_vendors.IFSCCode','store_vendors.accountNo','contactus_land_pages.branchName as branchName', 'contactus_land_pages.zoneName')
        ->where('fuel_filled_entries.id', $id)
        ->first();
        $typeOfCompanies = TypeOfCompany::where('status', 1)->orderBy('name')->pluck('name', 'id');
        $vehicleList = FuelVehicle::join('store_products', 'fuel_vehicles.vehicleId', 'store_products.id')
        ->select('fuel_vehicles.*', 'store_products.name as busNo')
        ->where('fuel_vehicles.fuelEntryId', $id)
        ->where('fuel_vehicles.active', 1)
        ->orderBy('fuel_vehicles.created_at', 'desc')
        ->get();

        $vendorDet = StoreVendor::where('id', $fuelEntry->vendorId)->first();
        $branches =ContactusLandPage::where('zoneStatus', 1)->whereActive(1)->orderBy('zoneName')->pluck('zoneName','id');

        return view('storeAdmin.fuelMgmt.generateQuotation', compact('fuelEntry','vehicleList','vendorDet', 'branches','typeOfCompanies'));
    }

    public function storeQuotation(Request $request)
    {
        DB::beginTransaction();
        try 
        {
            $fuelEntry = FuelFilledEntry::findOrFail($request->fuelEntryId);
            $vehicleList = FuelVehicle::where('fuelEntryId', $request->fuelEntryId)->get();
            
            $lastComQuot = StoreQuotation::orderBy('commQuotNo', 'desc')->value('commQuotNo') ?? 100000;
            $lastQuot = StoreQuotation::orderBy('quotNo', 'desc')->value('quotNo') ?? 0;
        
            $vendorDet = StoreVendor::findOrFail($request->vendorId);
        
            $quotation = new StoreQuotation();
            $quotation->raisedBy = Auth::id();
            $quotation->vendorId = $request->vendorId;
            $quotation->quotNo = $lastQuot + 1;
            $quotation->commQuotNo = $lastComQuot + 1;
        
            $quotation->typeOfCompany = $request->typeOfCompany;
            $quotation->name = $vendorDet->name;
            $quotation->accountNo = $vendorDet->accountNo;
            $quotation->IFSCCode = $vendorDet->IFSCCode;
            $quotation->bankBranch = $vendorDet->bankBranch;
            
            $quotation->validDate = $fuelEntry->forDate;
            $quotation->termsOfPayment = $request->termOfPayment;
            $quotation->shippingBranchId = $request->shippingAddress;
            $quotation->shippingAddress = ContactusLandPage::where('id', $request->shippingAddress)->value('address');
            
            $quotation->quotationFor = $request->quotationFor;
            $quotation->tentativeDate = $request->tentativeDeliveryDate;
            $quotation->officeAddress = $request->address;
            $quotation->reqNo = $request->requisitionNo;
            $quotation->alreadyPaid = $request->alreadyPaid;
            $quotation->alreadyPaidBy = $request->alreadyPaidBy;
        
            // File upload
            if ($request->hasFile('quotationFile')) 
            {
                $file = $request->file('quotationFile');
                $fileName = 'quotation_' . date('dmHis') . '.' . $file->extension();  
                $file->move(public_path('storeAdmin/quotations/'), $fileName); 
                $quotation->quotationFile = $fileName;
            }
        
            $quotation->totalRs = $request->finalRs;
            $quotation->transportationRs = 0;
            $quotation->loadingRs = 0;
            $quotation->unloadingRs = 0;
            $quotation->finalRs = $request->finalRs;
            $quotation->fuelType = 1;
            $quotation->fuelEntryId = $fuelEntry->id;
            $quotation->updated_by = Auth::user()->username;
            $quotation->status = 1;
            if ($quotation->save()) 
            {
                foreach ($vehicleList as $vehicle) 
                {
                    $order = new StoreQuotOrder();
                    $order->quotationId = $quotation->id;
                    $order->productId = $vehicle->vehicleId;
                    $order->qty = $vehicle->ltr;
                    $order->forDate = $fuelEntry->forDate;
                    $order->remark = '';
                    $order->unitPrice = ($fuelEntry->fuelType == 1) ? $fuelEntry->dieselRate : $fuelEntry->petrolRate;
                    $order->discount = 0;
                    $order->cgst = 0;
                    $order->sgst = 0;
                    $order->amount = $fuelEntry->amount;
                    $order->status = 1;
                    $order->save();
                }

                $fuelEntry->status=1;
                $fuelEntry->updated_by=Auth::user()->username;
                $fuelEntry->save();
            }
            DB::commit();  
            return redirect('/quotation/list')->with("success", "Quotation Generated Successfully.");
        } 
        catch (\Exception $e) 
        {
            DB::rollback();
            return redirect()->back()->with("error", "Something went wrong.");
        }
    }

    public function deleteVehicleEntry($id)
    {
        $fuelVehicle = FuelVehicle::findOrFail($id);
        $fuelVehicle->active = 0;
        $fuelVehicle->updated_by = Auth::user()->username;
        $fuelVehicle->save();
    
        return redirect('/fuelSystems/fuelVehicleEntry/'.$fuelVehicle->fuelEntryId)->with('success', 'Vehicle Deleted successfully.');
    }

    public function getVehicleFuelDetails($vehicleId)
    {
       return FuelVehicle::where('vehicleId', $vehicleId)->orderBy('id','desc')->value('newKM');
    }
}
