<?php

namespace App\Http\Controllers\StoreController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\stores\LedgersExport;
use App\StoreRequisition;
use App\StoreRequisitionProduct;
use App\StoreQuotationPayment;
use App\StoreVendor;
use App\StoreQuotation;
use App\StoreProductLedger;
use App\StoreProduct;
use App\StoreOutward;
use App\StoreOutwardProdList;
use App\StoreWorkOrder;
use App\Inward;
use App\StorePurchaseOrder;
use App\StoreQuotOrder;
use DB;
use Excel;
use DateTime;
use DateInterval;
use DatePeriod;


class ReportsController extends Controller
{
    public function index(Request $request)
    {
        return view('storeAdmin.reports.AllReports');
    }

    public function vendorReport(Request $request)
    {
        $myInputVendorName = $request->myInputVendorName;
        $myInputCategory = $request->myInputCategory;
        $myInputMaterial = $request->myInputMaterial;
        $myInputVendorAddedBy = $request->myInputVendorAddedBy;

        $reports = StoreVendor::whereIn('active', [0,1]);
        if($myInputVendorName != '')
            $reports=$reports->where('name', $myInputVendorName);

        if($myInputCategory != '')
            $reports=$reports->where('category', $myInputCategory);

        $reports=$reports->orderBy('name')->paginate(15)->appends(['myInputVendorName' => 'myInputVendorName','myInputCategory' => 'myInputCategory','myInputMaterial' => 'myInputMaterial']);

        $categories = StoreVendor::distinct('category')->where('category', '!=', '')->orderBy('category')->get(['category']);
        return view('storeAdmin.reports.VendorReport')->with(['myInputVendorName'=>$myInputVendorName,'myInputCategory'=>$myInputCategory,'myInputMaterial'=>$myInputMaterial,
        'myInputVendorAddedBy'=>$myInputVendorAddedBy,'categories'=>$categories,'reports'=>$reports]);
    }

    public function getVendors()
    {
        return StoreVendor::select('name','category')->where('active', 1)->where('category', '!=', NULL)->orderBy('name')->get();
    }

    public function branchWiseRequisitionReport(Request $request)
    {
        $month = $request->forMonth;
        $branchId = $request->branchId;
        if($month == '')
            $month = date('Y-m');
      
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $reports=[];


        if($branchId != '')
        {
            $begin = new DateTime(date('Y-m-01', strtotime($month)));
            $end = new DateTime(date('Y-m-t', strtotime($month)));

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            $reports=$data=[];
            foreach ($period as $dt) 
            {
                $forDate = $dt->format("Y-m-d");
                $data['forDate'] = date('d-m-Y', strtotime($forDate));
                $data['pending'] = StoreRequisition::where('status', 0)->where('branchId', $branchId)->where('requisitionDate', $forDate)->count();
                $data['completed'] = StoreRequisition::where('status', 1)->where('branchId', $branchId)->where('requisitionDate', $forDate)->count();
                $data['rejected'] = StoreRequisition::where('status', 2)->where('branchId', $branchId)->where('requisitionDate', $forDate)->count();
                $data['hold'] = StoreRequisition::where('status', 3)->where('branchId', $branchId)->where('requisitionDate', $forDate)->count();
                $data['cancel'] = StoreRequisition::where('status', 4)->where('branchId', $branchId)->where('requisitionDate', $forDate)->count();
                array_push($reports, $data);
            }

        }

        return view('storeAdmin.reports.branchWiseRequisitionReport')->with(['branchId'=>$branchId,'month'=>$month,'branches'=>$branches,'reports'=>$reports]);
    }

    public function branchWiseRequisitionCountReport(Request $request)
    {
        $month = $request->forMonth;
        $branchId = $request->branchId;
        if($month == '')
            $month = date('Y-m');
      
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $reports=[];


        if($branchId != '')
        {
            $begin = new DateTime(date('Y-m-01', strtotime($month)));
            $end = new DateTime(date('Y-m-t', strtotime($month)));

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            $reports=$data=[];
            foreach ($period as $dt) 
            {
                $forDate = $dt->format("Y-m-d");
                $data['forDate'] = date('d-m-Y', strtotime($forDate));
                $data['reqCount'] = StoreRequisition::where('branchId', $branchId)->where('requisitionDate', $forDate)->count();
                $requisitionIds = StoreRequisition::where('branchId', $branchId)->where('requisitionDate', $forDate)->pluck('id');
                $data['amount'] = StoreRequisitionProduct::select(DB::raw('SUM(currentRate*requiredQty) AS total'))
                ->whereIn('requisitionId', $requisitionIds)
                ->value('total');
                $data['pendingReq'] = StoreRequisition::where('status', 0)->where('branchId', $branchId)->where('requisitionDate', $forDate)->count();
                array_push($reports, $data);
            }

        }

        return view('storeAdmin.reports.branchWiseRequisionCountReport')->with(['branchId'=>$branchId,'month'=>$month,'branches'=>$branches,'reports'=>$reports]);
    }

    // store reports
    public function openingStockReport(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $productId = $request->productId;
        if($startDate == '')
            $startDate = date('Y-m-01');

        if($endDate == '')
            $endDate = date('Y-m-d');

        $products  = StoreProduct::where('active', 1)->orderBy('name')->pluck('name', 'id');
        $ledgers=$product=[];
        $openingStock =0;
        if($productId != '')
        {
            $product  = StoreProduct::where('id', $productId)->first();
          
            if($startDate >= $product->openingStockForDate || $startDate == $product->openingStockForDate)
            {
                $openingStock = $product->openingStock;
                $inwardQty = StoreProductLedger::where('productId', $productId)->where('forDate', '>', $product->openingStockForDate)->where('forDate', '<', $startDate)->where('type', 1)->sum('inwardQty');
                $outwardQty = StoreProductLedger::where('productId', $productId)->where('forDate', '>', $product->openingStockForDate)->where('forDate', '<', $startDate)->where('type', 2)->sum('outwardQty');
                $returnQty = StoreProductLedger::where('productId', $productId)->where('forDate', '>', $product->openingStockForDate)->where('forDate', '<', $startDate)->where('type', 3)->sum('returnQty');

                $openingStock =  ($openingStock + $inwardQty +  $returnQty) - $outwardQty;
            }
            else
            {
                $openingStock = $product->openingStock;
                $startDate=$product->openingStockForDate;
            }

            $ledgers = StoreProductLedger::join('store_products', 'store_product_ledgers.productId', 'store_products.id')
            ->join('store_units', 'store_products.unitId', 'store_units.id')
            ->select('store_product_ledgers.*', 'store_units.name as unitName')
            ->where('store_product_ledgers.productId', $productId)
            ->where('store_product_ledgers.forDate', '>=', $startDate)
            ->where('store_product_ledgers.forDate', '<=', $endDate)
            ->where('store_product_ledgers.status', 1)
            ->orderBy('store_product_ledgers.updated_at')
            ->get();

        }

        return view('storeAdmin.reports.store.openingStockReport')->with(['product'=>$product,'openingStock'=>$openingStock,'startDate'=>$startDate,'endDate'=>$endDate,'productId'=>$productId, 'products'=>$products, 'ledgers'=>$ledgers]);
    }

    public function openingStocExportToExcel($startDate, $endDate, $productId)
    {
        try {
            // Decrypt parameters
            $decryptedStartDate = decrypt($startDate);
            $decryptedEndDate = decrypt($endDate);
            $decryptedProductId = decrypt($productId);
        } catch (\Exception $e) {
            return back()->with('error', 'Invalid request data.');
        }

        // Fetch product details
        $product = StoreProduct::find($decryptedProductId);
        if (!$product) {
            return back()->with('error', 'Product not found.');
        }

        // Get the initial opening stock
        $openingStock = $product->openingStock;

        // If startDate is after openingStockForDate, adjust opening stock
        if ($decryptedStartDate > $product->openingStockForDate) {
            $inwardQty = StoreProductLedger::where('productId', $decryptedProductId)
                ->whereBetween('forDate', [$product->openingStockForDate, $decryptedStartDate])
                ->where('type', 1)
                ->sum('inwardQty');

            $outwardQty = StoreProductLedger::where('productId', $decryptedProductId)
                ->whereBetween('forDate', [$product->openingStockForDate, $decryptedStartDate])
                ->where('type', 2)
                ->sum('outwardQty');

            $returnQty = StoreProductLedger::where('productId', $decryptedProductId)
                ->whereBetween('forDate', [$product->openingStockForDate, $decryptedStartDate])
                ->where('type', 2)
                ->sum('returnQty');

            // Adjust opening stock
            $openingStock = ($openingStock + $inwardQty + $returnQty) - $outwardQty;
        }

        // Fetch ledgers
        $ledgers = StoreProductLedger::where('productId', $decryptedProductId)
            ->whereBetween('forDate', [$decryptedStartDate, $decryptedEndDate])
            ->where('status', 1)
            ->orderBy('created_at')
            ->get();
        $closingStock = $openingStock;
        foreach($ledgers as $ledger)
        {
            $ledger['closingStock']=$closingStock = ($openingStock + $ledger->inwardQty + $ledger->returnQty) - $ledger->outwardQty;
            $ledger['openingStock']=$openingStock = $closingStock;
        }

        $productName=$product->name;
        $openingStockDate=date('d-m-Y', strtotime($product->openingStockForDate));

        // Export data with opening stock
        return Excel::download(new LedgersExport($ledgers, $openingStock, $openingStockDate, $productName), 'ledgers.xlsx');
    }

    public function EODProductReport(Request $request) // export to excel option in directly web.php file through call.
    {
        $productList = StoreProduct::where('active', 1)->pluck('name', 'id');
        $forDate = $request->forDate ?: date('Y-m-d'); // Default to today if empty
        $productId = $request->productId;

        // Fetch Products
        $products = StoreProduct::select('id', 'name', 'productCode', 'openingStockForDate', 'openingStock', 'stock')
            ->where('openingStockForDate', '<=', $forDate);

        if ($productId) {
            $products = $products->where('id', $productId);
        }

        // Paginate Products
        $products = $products->orderBy('name')->paginate(10)->appends([
            'forDate' => $forDate,
            'productId' => $productId
        ]);

        // Get Product IDs
        $productIds = $products->pluck('id')->toArray();

        // Get Stock Data in Bulk
        $ledgerData = StoreProductLedger::whereIn('productId', $productIds)
            ->where('forDate', '<=', date('Y-m-d', strtotime('-1 day', strtotime($forDate))))
            ->groupBy('productId')
            ->selectRaw('productId, SUM((inwardQty + returnQty) - outwardQty) AS total')
            ->pluck('total', 'productId');

        $todayStockData = StoreProductLedger::whereIn('productId', $productIds)
            ->where('forDate', $forDate)
            ->groupBy('productId')
            ->selectRaw('productId, SUM((inwardQty + returnQty) - outwardQty) AS total')
            ->pluck('total', 'productId');

        // Transform Paginated Data
        $products->getCollection()->transform(function ($product) use ($ledgerData, $todayStockData) {
            $previousStock = $ledgerData[$product->id] ?? 0;
            $todayStock = $todayStockData[$product->id] ?? 0;

            $product->newOpeningStock = $previousStock + $product->openingStock;
            $product->newClosingStock = $product->newOpeningStock+$todayStock;

            return $product;
        });

        // Return View
        return view('storeAdmin.reports.store.EODProductReport')->with([
            'forDate' => $forDate,
            'productId' => $productId,
            'productList' => $productList,
            'products' => $products
        ]);
    }

    public function outwardReport(Request $request) // export to excel option in directly web.php file through call.
    {
        $forMonth = $request->forMonth;
        if($forMonth == '')
            $forMonth=date('Y-m');

        $startDate = date('Y-m-01', strtotime($forMonth)); // First day of the month
        $endDate = date('Y-m-t', strtotime($forMonth));   // Last day of the month
        
        $reports = StoreOutward::join('store_requisitions', 'store_outwards.requisitionId', '=', 'store_requisitions.id')
        ->join('users', 'store_requisitions.userId', '=', 'users.id')
        ->select(
            'store_outwards.forDate',
            'store_requisitions.status',
            'store_outwards.branchName',
            'store_outwards.receiptNo',
            'users.name'
        )
        ->whereBetween('store_outwards.forDate', [$startDate, $endDate])
        ->get();

        return view('storeAdmin.reports.store.outwardReport')->with(['reports'=>$reports,'forMonth'=>$forMonth]);
    }

    public function productWiseReport(Request $request)
    {
        // Fetch all active products
        $products = StoreProduct::where('active', 1)->orderBy('name')->pluck('name', 'id');

        // Get request parameters
        $forMonth = $request->forMonth ?? date('Y-m'); // Default to current month if empty
        $productId = $request->productId;

        // Calculate start and end date for the selected month
        $startDate = date('Y-m-01', strtotime($forMonth));
        $endDate = date('Y-m-t', strtotime($forMonth));

        if (!$productId) {
            // Query when no specific product is selected
            $reports = StoreOutward::join('store_requisitions', 'store_outwards.requisitionId', '=', 'store_requisitions.id')
                ->join('users', 'store_requisitions.userId', '=', 'users.id')
                ->select(
                    'store_outwards.forDate',
                    'store_requisitions.status',
                    'store_outwards.branchName',
                    'store_outwards.receiptNo',
                    'users.name'
                )
                ->whereBetween('store_outwards.forDate', [$startDate, $endDate])
                ->get();
        } else {
            // Query when a specific product is selected
            $reports = StoreOutwardProdList::join('store_outwards', 'store_outward_prod_lists.outwardId', '=', 'store_outwards.id')
                ->join('store_requisitions', 'store_outwards.requisitionId', '=', 'store_requisitions.id')
                ->join('users', 'store_requisitions.userId', '=', 'users.id')
                ->select(
                    'store_outwards.forDate',
                    'store_requisitions.status',
                    'store_outwards.branchName',
                    'store_outwards.receiptNo',
                    'users.name'
                )
                ->where('store_outward_prod_lists.actualProductId', $productId)
                ->whereBetween('store_outwards.forDate', [$startDate, $endDate])
                ->get();
        }

        // Return the view with the fetched data
        return view('storeAdmin.reports.store.ProductWiseReport', [
            'productId' => $productId,
            'products' => $products,
            'reports' => $reports,
            'forMonth' => $forMonth
        ]);
    }

    public function inwardReport(Request $request)
    {
        $forMonth = $request->forMonth ?? date('Y-m'); // Default to current month if empty

        $startDate = date('Y-m-01', strtotime($forMonth));
        $endDate = date('Y-m-t', strtotime($forMonth));

        $reports = Inward::join('store_purchase_orders', 'inwards.purchaseOrderId', '=', 'store_purchase_orders.id')
        ->join('store_quotations', 'store_purchase_orders.quotationId', '=', 'store_quotations.id')
        ->join('contactus_land_pages', 'store_quotations.shippingBranchId', '=', 'contactus_land_pages.id')
        ->select('inwards.forDate', 'inwards.poNumber', 'inwards.reqNo', 'contactus_land_pages.branchName', 'inwards.active')
        ->whereBetween('inwards.forDate', [$startDate, $endDate])
        ->orderBy('inwards.forDate')
        ->get();

        return view('storeAdmin.reports.store.inwardReport')->with(['forMonth'=>$forMonth, 'reports'=>$reports]);
    }

    //purchase Reports
    public function quotationReport(Request $request)
    { 
        $forMonth= $request->forMonth;
        if($forMonth == '')
            $forMonth = date('Y-m');
       
        $startDate = date('Y-m-01 00:00:00', strtotime($forMonth));
        $endDate = date('Y-m-t 23:59:59', strtotime($forMonth));

        $reports = StoreQuotation::join('contactus_land_pages', 'store_quotations.shippingBranchId', 'contactus_land_pages.id')
        ->join('store_vendors', 'store_quotations.vendorId', 'store_vendors.id')
        ->join('users', 'store_quotations.raisedBy', 'users.id')
        ->select('store_quotations.created_at', 'contactus_land_pages.branchName', 'store_vendors.name as vendorName', 'users.name as raisedBy',
        'store_quotations.quotNo', 'store_quotations.finalRs', 'store_quotations.quotStatus', 'store_quotations.commQuotNo')
        ->where('store_quotations.status', 1)
        ->whereBetween('store_quotations.created_at', [$startDate, $endDate])
        ->where('store_quotations.quotStatus', 'Approved')
        ->orderBy('store_quotations.created_at')
        ->get();

        return view('storeAdmin.reports.purchase.QuotationReport')->with(['reports'=>$reports,'forMonth'=>$forMonth]);
    }

    public function PurchaseOrderReport(Request $request)
    {
        $forMonth= $request->forMonth;
        if($forMonth == '')
            $forMonth = date('Y-m');
       
        $startDate = date('Y-m-01', strtotime($forMonth));
        $endDate = date('Y-m-t', strtotime($forMonth));

        $reports = StorePurchaseOrder::join('store_quotations', 'store_purchase_orders.quotationId', 'store_quotations.id')
        ->join('contactus_land_pages', 'store_quotations.shippingBranchId', 'contactus_land_pages.id')
        ->join('store_vendors', 'store_quotations.vendorId', 'store_vendors.id')
        ->join('users', 'store_quotations.raisedBy', 'users.id')
        ->select('store_purchase_orders.generatedDate', 'contactus_land_pages.branchName', 'users.name as raisedBy', 'store_vendors.name as vendorName', 
        'store_purchase_orders.poNumber','store_purchase_orders.poAmount','store_purchase_orders.paidAmount')
        ->where('store_quotations.status', 1)
        ->whereBetween('store_purchase_orders.generatedDate', [$startDate, $endDate])
        ->where('store_quotations.quotStatus', 'Approved')
        ->orderBy('store_quotations.created_at')
        ->get();

        return view('storeAdmin.reports.purchase.purchaseOrderReport')->with(['reports'=>$reports,'forMonth'=>$forMonth]);
    }

    public function workOrderReport(Request $request)
    {
        $forMonth= $request->forMonth;
        if($forMonth == '')
            $forMonth = date('Y-m');
       
        $startDate = date('Y-m-01', strtotime($forMonth));
        $endDate = date('Y-m-t', strtotime($forMonth));
        
        $reports = StoreWorkOrder::join('contactus_land_pages', 'store_work_orders.branchId', 'contactus_land_pages.id')
        ->join('users', 'store_work_orders.raisedBy', 'users.id')
        ->select('store_work_orders.generatedDate', 'contactus_land_pages.branchName', 'users.name as raisedBy', 
        'store_work_orders.name as vendorName', 'store_work_orders.paidAmount', 'store_work_orders.poAmount','store_work_orders.poNumber')
        ->whereBetween('generatedDate', [$startDate, $endDate])
        ->get();

        return view('storeAdmin.reports.purchase.workOrderReport')->with(['reports'=>$reports,'forMonth'=>$forMonth]);
    }

    public function vendorWiseReport(Request $request)
    {
        $vendors = StoreVendor::where('active', 1)->orderBy('name')->pluck('name', 'id');

        $startDate = $request->startDate ?? date('Y-m-01'); // Default: First day of the current month
        $endDate = $request->endDate ?? date('Y-m-d'); // Default: Today’s date

        $type = $request->type ?? null;
        $vendorId = $request->vendorId ?? null;

        // Fetch all reports
        $reports = StoreQuotationPayment::join('store_quotations', 'store_quotation_payments.quotationId', '=', 'store_quotations.id')
            ->join('users', 'store_quotations.raisedBy', '=', 'users.id')
            ->select(
                'store_quotations.id',
                'store_quotations.created_at',
                'users.name as raisedBy',
                'store_quotations.commQuotNo',
                'store_quotation_payments.amount',
                'store_quotation_payments.forDate',
                'store_quotation_payments.percent',
                'store_quotation_payments.status'
            )
            ->whereBetween('store_quotations.created_at', [$startDate, $endDate]);

        // Apply vendor filter only if provided
        if ($vendorId) {
            $reports->where('store_quotations.vendorId', $vendorId);
        }

        $reports = $reports->get();

        // Fetch all product lists for the retrieved reports in one query
        $quotationIds = $reports->pluck('id')->toArray();

        $productLists = StoreQuotOrder::join('store_products', 'store_quot_orders.productId', '=', 'store_products.id')
            ->whereIn('store_quot_orders.quotationId', $quotationIds)
            ->select('store_quot_orders.quotationId', 'store_products.name')
            ->get()
            ->groupBy('quotationId'); // Group by quotationId for efficient mapping

        // Attach product lists to reports
        $reports = $reports->map(function ($report) use ($productLists) {
            $report->productList = $productLists[$report->id] ?? collect();
            return $report;
        });

        return $reports;

       

        return view('storeAdmin.reports.purchase.vendorWiseReport')->with([
            'reports'=>$reports,
            'vendors'=>$vendors,
            'startDate'=>$startDate,
            'endDate'=>$endDate,
            'type'=>$type,
            'vendorId'=>$vendorId]);
    }
}
