<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\stores\ProductsExport;
use App\Exports\stores\PaymentExport;

use App\Helpers\Utility;
use App\StoreQuotationPayment;
use App\StoreQuotation;
use App\StoreQuotOrder;
use App\StoreVendor;
use App\StoreCategory;
use App\StoreUnit;
use App\StoreProductList;
use App\StoreSubCategory;
use App\TempStoreProduct;
use App\StoreProductOpeningStock;
use App\StorePurchaseOrder;
use App\FuelFilledEntry;
use App\TypeOfCompany;
use Auth;
use DB;
use Image;
use PDF;
use Excel;

class PaymentsController extends Controller
{
    public function index(Request $request){}

    public function POUnpaidPaymentList(Request $request)
    {
        if (!in_array(Auth::user()->userType, ['61', '701', '501', '801'])) 
            return redirect()->back()->withInput()->with("error", "You do not have permission to access this page.");

        $user = Auth::user();
        $userType = $user->userType;
      
        $paymentsQuery = StoreQuotationPayment::join('store_quotations', 'store_quotation_payments.quotationId', '=', 'store_quotations.id')
        ->join('store_vendors', 'store_quotations.vendorId', '=', 'store_vendors.id')
        ->join('type_of_companies', 'store_quotations.typeOfCompany', 'type_of_companies.id')
        ->select(
            'store_quotation_payments.*',
            'store_vendors.id as vendorId',
            'store_vendors.name as vendorName',
            'store_quotations.quotationFor',
            'store_vendors.accountNo',
            'store_vendors.IFSCCode',
            'store_vendors.bankBranch',
            'store_quotations.id as quotationId',
            'type_of_companies.shortName as typeOfCompanyName'
        )
        ->where('store_quotation_payments.status', 1); // 1 is for Pending

        if ($userType == '801') {
            $paymentsQuery=$paymentsQuery->where('store_quotations.raisedBy', $user->id);
        }
        else
        {
            if($user->typeOfCompany != '')
            {
                $typeOfCompany = explode(',', $user->typeOfCompany ?? []);
                $paymentsQuery=$paymentsQuery->whereIn('store_quotations.typeOfCompany', $typeOfCompany);
            }
        }

        $payments = $paymentsQuery->orderBy('store_quotation_payments.forDate')->get();
        return view('storeAdmin.POPayments.POUnpaidPaymentList', compact('payments'));
    }

    public function POPaidPaymentList(Request $request)
    {
        if (!in_array(Auth::user()->userType, ['61', '701', '501', '801'])) 
            return redirect()->back()->withInput()->with("error", "You do not have permission to access this page.");

        $user = Auth::user();
        $userType = $user->userType;
        $selectedTypeOfCompany = $request->input('typeOfCompanyId');
        $forMonth = $request->input('forMonth');
        if($forMonth == '')
            $forMonth = date('Y-m'); // Default to current month
        
        $startDate = date('Y-m-01', strtotime($forMonth));
        $endDate = date('Y-m-t', strtotime($forMonth));
      
        $paymentsQuery = StoreQuotationPayment::join('store_quotations', 'store_quotation_payments.quotationId', '=', 'store_quotations.id')
        ->join('store_vendors', 'store_quotations.vendorId', '=', 'store_vendors.id')
        ->join('type_of_companies', 'store_quotations.typeOfCompany', 'type_of_companies.id')
        ->select(
            'store_quotation_payments.*',
            'store_vendors.id as vendorId',
            'store_vendors.name as vendorName',
            'store_quotations.quotationFor',
            'store_vendors.accountNo',
            'store_vendors.IFSCCode',
            'store_vendors.bankBranch',
            'store_quotations.id as quotationId',
            'type_of_companies.shortName as typeOfCompanyName'
        )
        ->where('store_quotation_payments.status', 2) // 2 is for Approved
        ->whereBetween('store_quotation_payments.forDate', [$startDate, $endDate]);


        if($userType != '701')
        {
            if ($userType == '801') {
                $paymentsQuery=$paymentsQuery->where('store_quotations.raisedBy', $user->id);
                $typeOfCompanies = TypeOfCompany::where('status', 1)->orderBy('name')->pluck('shortName', 'id');
            }
            else
            {
                $typeOfCompany = explode(',', $user->typeOfCompany ?? []);
                $paymentsQuery=$paymentsQuery->whereIn('store_quotations.typeOfCompany', $typeOfCompany);
                $typeOfCompanies = TypeOfCompany::where('status', 1)->whereIn('id', $typeOfCompany)->orderBy('name')->pluck('shortName', 'id');
            }
        }
        else
        {
            $typeOfCompanies = TypeOfCompany::where('status', 1)->orderBy('name')->pluck('shortName', 'id');
        }

        if($selectedTypeOfCompany != '')
        {
           $paymentsQuery=$paymentsQuery->where('store_quotations.typeOfCompany', $selectedTypeOfCompany);
        }

        $payments = $paymentsQuery->orderBy('store_quotation_payments.forDate')->get();
        return view('storeAdmin.POPayments.POPaidPaymentList', compact('payments', 'typeOfCompanies','selectedTypeOfCompany', 'forMonth'));
    }

    public function PORejectedPaymentList(Request $request)
    {
         if (!in_array(Auth::user()->userType, ['61', '701', '501', '801'])) 
            return redirect()->back()->withInput()->with("error", "You do not have permission to access this page.");

        $user = Auth::user();
        $userType = $user->userType;
        $selectedTypeOfCompany = $request->input('typeOfCompanyId');
        $forMonth = $request->input('forMonth');
        if($forMonth == '')
            $forMonth = date('Y-m'); // Default to current month
        
        $startDate = date('Y-m-01', strtotime($forMonth));
        $endDate = date('Y-m-t', strtotime($forMonth));
      
        $paymentsQuery = StoreQuotationPayment::join('store_quotations', 'store_quotation_payments.quotationId', '=', 'store_quotations.id')
        ->join('store_vendors', 'store_quotations.vendorId', '=', 'store_vendors.id')
        ->join('type_of_companies', 'store_quotations.typeOfCompany', 'type_of_companies.id')
        ->select(
            'store_quotation_payments.*',
            'store_vendors.id as vendorId',
            'store_vendors.name as vendorName',
            'store_quotations.quotationFor',
            'store_vendors.accountNo',
            'store_vendors.IFSCCode',
            'store_vendors.bankBranch',
            'store_quotations.id as quotationId',
            'type_of_companies.shortName as typeOfCompanyName'
        )
        ->where('store_quotation_payments.status', 3) // 3 is for Rejected
        ->whereBetween('store_quotation_payments.forDate', [$startDate, $endDate]);

        if ($userType != '701') 
        {
            if ($userType == '801') {
                $paymentsQuery=$paymentsQuery->where('store_quotations.raisedBy', $user->id);
                $typeOfCompanies = TypeOfCompany::where('status', 1)->orderBy('name')->pluck('shortName', 'id');
            }
            else
            {
                $typeOfCompany = explode(',', $user->typeOfCompany ?? []);
                $paymentsQuery=$paymentsQuery->whereIn('store_quotations.typeOfCompany', $typeOfCompany);
                $typeOfCompanies = TypeOfCompany::where('status', 1)->whereIn('id', $typeOfCompany)->orderBy('name')->pluck('shortName', 'id');
            }
        }
        else
        {
            $typeOfCompanies = TypeOfCompany::where('status', 1)->orderBy('name')->pluck('shortName', 'id');
        }

        if($selectedTypeOfCompany != '')
        {
           $paymentsQuery=$paymentsQuery->where('store_quotations.typeOfCompany', $selectedTypeOfCompany);
        }

        $payments = $paymentsQuery->orderBy('store_quotation_payments.forDate')->get();
        return view('storeAdmin.POPayments.PORejectedPaymentList', compact('payments', 'typeOfCompanies','selectedTypeOfCompany', 'forMonth'));
    }

    public function POHoldPaymentList(Request $request)
    {
        if (!in_array(Auth::user()->userType, ['61', '701', '501', '801'])) 
            return redirect()->back()->withInput()->with("error", "You do not have permission to access this page.");

        $user = Auth::user();
        $userType = $user->userType;
        $selectedTypeOfCompany = $request->input('typeOfCompanyId');
        $forMonth = $request->input('forMonth');
        if($forMonth == '')
            $forMonth = date('Y-m'); // Default to current month
        
        $startDate = date('Y-m-01', strtotime($forMonth));
        $endDate = date('Y-m-t', strtotime($forMonth));
      
        $paymentsQuery = StoreQuotationPayment::join('store_quotations', 'store_quotation_payments.quotationId', '=', 'store_quotations.id')
        ->join('store_vendors', 'store_quotations.vendorId', '=', 'store_vendors.id')
        ->join('type_of_companies', 'store_quotations.typeOfCompany', 'type_of_companies.id')
        ->select(
            'store_quotation_payments.*',
            'store_vendors.id as vendorId',
            'store_vendors.name as vendorName',
            'store_quotations.quotationFor',
            'store_vendors.accountNo',
            'store_vendors.IFSCCode',
            'store_vendors.bankBranch',
            'store_quotations.id as quotationId',
            'type_of_companies.shortName as typeOfCompanyName'
        )
        ->where('store_quotation_payments.status', 4) // 4 is for Hold
        ->whereBetween('store_quotation_payments.forDate', [$startDate, $endDate]);

        if($userType != '701')
        {
            if ($userType == '801') {
                $paymentsQuery=$paymentsQuery->where('store_quotations.raisedBy', $user->id);
                $typeOfCompanies = TypeOfCompany::where('status', 1)->orderBy('name')->pluck('shortName', 'id');
            }
            else
            {
                $typeOfCompany = explode(',', $user->typeOfCompany ?? []);
                $paymentsQuery=$paymentsQuery->whereIn('store_quotations.typeOfCompany', $typeOfCompany);
                $typeOfCompanies = TypeOfCompany::where('status', 1)->whereIn('id', $typeOfCompany)->orderBy('name')->pluck('shortName', 'id');
            }
        }
        else
        {
           $typeOfCompanies = TypeOfCompany::where('status', 1)->orderBy('name')->pluck('shortName', 'id'); 
        }

        if($selectedTypeOfCompany != '')
        {
           $paymentsQuery=$paymentsQuery->where('store_quotations.typeOfCompany', $selectedTypeOfCompany);
        }

        $payments = $paymentsQuery->orderBy('store_quotation_payments.forDate')->get();
        return view('storeAdmin.POPayments.POHoldPaymentList', compact('payments', 'typeOfCompanies','selectedTypeOfCompany', 'forMonth'));
    }

    public function POPaymentEdit($id)
    {
        $allowedUserTypes = ['61', '701', '501', '801'];
    
        if (!in_array(Auth::user()->userType, $allowedUserTypes)) {
            return redirect()->back()->withInput()->with("error", "You do not have permission to access this page.");
        }
    
        $payment = StoreQuotationPayment::join('store_quotations', 'store_quotation_payments.quotationId', 'store_quotations.id')
            ->join('store_vendors', 'store_quotations.vendorId', 'store_vendors.id')
            ->select(
                'store_quotation_payments.*',
                'store_vendors.address',
                'store_vendors.contactPerson1',
                'store_vendors.contactPerNo1',
                'store_vendors.id as vendorId',
                'store_vendors.name as vendorName',
                'store_vendors.accountNo',
                'store_vendors.IFSCCode',
                'store_vendors.bankBranch'
            )
            ->where('store_quotation_payments.id', $id)
            ->first();
    
        if (!$payment) {
            return redirect()->back()->with("error", "Payment record not found.");
        }
    
        $quotation = StoreQuotation::where('id', $payment->quotationId)->where('quotStatus', 'Approved')->first();
    
        if (!$quotation) {
            return redirect()->back()->with("error", "Approved quotation not found.");
        }
    
        $paymentHistory = StoreQuotationPayment::where('poNumber', $payment->poNumber)
            ->where('status', 2)
            ->orderBy('updated_at')
            ->get();
    
        return view('storeAdmin.POPayments.POPaymentEdit', compact('payment', 'quotation', 'paymentHistory'));
    }
    

    public function POPaymentShow($id)
    {
        $userType = Auth::user()->userType;
        if($userType == '61' || $userType == '701' || $userType == '501' || $userType == '801')
        {
            $quotations = StoreQuotation::where('commQuotNo', $id)->get();
            $approveQuot = StoreQuotation::where('commQuotNo', $id)->where('quotStatus', 'Approved')->count();

            $pendingQuotationCount = StoreQuotation::where('quotStatus', 'Pending');
            if($userType == '801')
                $pendingQuotationCount = $pendingQuotationCount->where('raisedBy', Auth::user()->id);

            $pendingQuotationCount = $pendingQuotationCount->count();

            $approvedQuotationCount = StoreQuotation::where('quotStatus', 'Approved');
            if($userType == '801')
                $approvedQuotationCount = $approvedQuotationCount->where('raisedBy', Auth::user()->id);

            $approvedQuotationCount = $approvedQuotationCount->count();

            $rejectedQuotationCount = StoreQuotation::whereIn('quotStatus', ['Cancel','Rejected']);
            if($userType == '801')
                $rejectedQuotationCount = $rejectedQuotationCount->where('raisedBy', Auth::user()->id);

            $rejectedQuotationCount = $rejectedQuotationCount->count();

            return view('storeAdmin.quotations.viewQuotation')->with(['quotations'=>$quotations,'approveQuot'=>$approveQuot,
            'pendingQuotationCount'=>$pendingQuotationCount,'approvedQuotationCount'=>$approvedQuotationCount,'rejectedQuotationCount'=>$rejectedQuotationCount]);

        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        }
    }

    public function POPaymentUpdate(Request $request)
    {
        $payment = StoreQuotationPayment::find($request->paymentId);
      
        if($request->paymentStatus != '1' || $payment->paymentStatus == '4')
        {
            if($request->paymentStatus == '3')
            {
                DB::beginTransaction();
                try 
                {
                    $payment->updated_by=Auth::user()->username;
                    $payment->status=$request->paymentStatus;
                    $payment->save();
                    DB::commit();
                    return redirect('/payments/PORejectedPaymentList')->with("success", "Payment Rejected Status Updated Successfully....");
                } catch (\Exception $e) {
                    DB::rollback();
                    // Optionally log the error $e->getMessage()
                    return redirect()->back()->withInput()->with("error", "There is some issue. Please try again.");
                }
            }
            elseif($request->paymentStatus == '4')
            {
                DB::beginTransaction();
                try 
                {
                    $payment->updated_by=Auth::user()->username;
                    $payment->status=$request->paymentStatus;
                    $payment->paymentRemark  = $request->paymentRemark;
                    $payment->save();
                    DB::commit();
                    return redirect('/payments/POHoldPaymentList')->with("success", "Payment Hold status updated Successfully....");
                } catch (\Exception $e) {
                    DB::rollback();
                    // Optionally log the error $e->getMessage()
                    return redirect()->back()->withInput()->with("error", "There is some issue. Please try again.");
                }
            }
            else
            {
                // Update payment details from request
                $payment->vendorId       = $request->vendorId;
                $payment->accountNo      = $request->accountNo;
                $payment->bankBranch     = $request->bankBranch;
                $payment->IFSCCode       = $request->IFSCCode;
                $payment->transactionId  = $request->transactionNumber;
                $payment->paymentRemark  = $request->paymentRemark;
                $payment->transferDate   = $request->transferDate;
                $payment->updated_by     = Auth::user()->username;
                $payment->status         = 2;  // Mark payment as Transferred

                // Update vendor bank details if provided
                $vendor = StoreVendor::find($request->vendorId);
                if ($request->accountNo !== '') {
                    $vendor->accountNo = $request->accountNo;
                }
                if ($request->IFSCCode !== '') {
                    $vendor->IFSCCode = $request->IFSCCode;
                }
                if ($request->bankBranch !== '') {
                    $vendor->bankBranch = $request->bankBranch;
                }
                $vendor->updated_by = Auth::user()->username;

                // Update PO paid amount
                $poDetails = StorePurchaseOrder::where('quotationId', $payment->quotationId)->first();
                if ($poDetails) {
                    $poDetails->paidAmount += $payment->amount;
                    $poDetails->updated_by = Auth::user()->username;
                }

                DB::beginTransaction();
                try {
                    // Save all models within transaction
                    $payment->save();
                    $vendor->save();
                    if ($poDetails) {
                        $poDetails->save();
                    }

                    // Update fuel entry status if applicable
                    $fuelEntryId = StoreQuotation::where('id', $payment->quotationId)->value('fuelEntryId');
                    if ($fuelEntryId) {
                        $entry = FuelFilledEntry::find($fuelEntryId);
                        if ($entry) {
                            $entry->status = 3;  // Assuming 3 means some updated status
                            $entry->updated_by = Auth::user()->username;
                            $entry->save();
                        }
                    }

                    DB::commit();
                    return redirect('/payments/POUnpaidPaymentList')->with("success", "Payment Details Updated Successfully....");
                } catch (\Exception $e) {
                    DB::rollback();
                    // Optionally log the error $e->getMessage()
                    return redirect()->back()->withInput()->with("error", "There is some issue. Please try again.");
                }
            }
        }
        else
        {
            DB::beginTransaction();
            try 
            {
                $payment->updated_by=Auth::user()->username;
                $payment->status=$request->paymentStatus;
                $payment->save();
                DB::commit();
                return redirect('/payments/POUnpaidPaymentList')->with("success", "Payment Pending Status Updated Successfully....");
            } catch (\Exception $e) {
                DB::rollback();
                // Optionally log the error $e->getMessage()
                return redirect()->back()->withInput()->with("error", "There is some issue. Please try again.");
            }
        }
    }

    public function exportPOPayments($forMonth, $status, $typeOfCompany)
    {     
        if($status == 1)
            $tempName = 'Pending';

        if($status == 2)
            $tempName = 'Paid';
    
        if($status == 3)
            $tempName = 'Rejected';  

        if($status == 4)
            $tempName = 'Hold';   

            $fileName = $tempName.'-Payment-List'.date('d-m-Y').'.xlsx';

        return Excel::download(new PaymentExport($forMonth, $status, $typeOfCompany), $fileName);
    }
}