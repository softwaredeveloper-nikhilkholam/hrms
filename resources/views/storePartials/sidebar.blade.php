<?php

$userType = Auth::user()->userType;
$user = Auth::user();
$username = Auth::user()->username;
// 00 - Super admin
// 61 - Account login
// 51- HR login
// 91 - Store Department
// 701 - Purchase Department
// 

?>
<div class="iq-sidebar  sidebar-default ">
    <div class="iq-sidebar-logo d-flex align-items-center justify-content-between">
        <a href="#" class="header-logo">
            <img src="{{asset('storeAdmin/images/230921120510.png')}}" class="img-fluid rounded-normal light-logo" alt="logo">
        </a>
        <div class="iq-menu-bt-sidebar ml-0">
            <i class="fa fa-bars wrapper-menu"></i>
        </div>
    </div>
    <div class="data-scrollbar" data-scroll="1">
        <nav class="iq-sidebar-menu">
            <ul id="iq-sidebar-toggle" class="iq-menu">
                @if($userType == '91' || $userType == '00' || $userType == '007' || $userType == '501')
                    <li class=""><a href="/storeHome" class="svg-icon"><i class="fa fa-tachometer" aria-hidden="true" style="color:green;"></i><span class="ml-4">Dashboards</span></a></li>
                    <li class=" ">
                        <a href="#product" class="collapsed" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-list" aria-hidden="true" style="color:green;"></i>
                            <span class="ml-4">Master</span>
                            <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="10 15 15 20 20 15"></polyline><path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                            </svg>
                        </a>
                        <ul id="product" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            <li class=""><a href="/category"><i class="fa fa-minus"></i><span>Category</span></a></li>
                            <li class=""><a href="/subCategory"><i class="fa fa-minus"></i><span>Sub Category</span></a></li>
                            <li class=""><a href="/unit"><i class="fa fa-minus"></i><span>Unit</span></a></li>
                            <li class=""><a href="/hall"><i class="fa fa-minus"></i><span>Hall</span></a></li>
                            <li class=""><a href="/rack"><i class="fa fa-minus"></i><span>Rack</span></a></li>
                            <li class=""><a href="/shelf"><i class="fa fa-minus"></i><span>Shelf</span></a></li>
                            <li class=""><a href="/product"><i class="fa fa-minus"></i><span>Product</span></a></li>
                            <li class=""><a href="/storeUser"><i class="fa fa-minus"></i><span>User</span></a></li>
                            <li class=""><a href="/scrapCategory"><i class="fa fa-minus"></i><span>Scrap Category</span></a></li>
                        </ul>
                    </li>
                    <li class=""><a href="/inwards" class="svg-icon"><i class="fa fa-bus" aria-hidden="true" style="color:grreen;"></i><span class="ml-4">Inwards</span></a></li>
                    <li class=""><a href="/outwards" class="svg-icon"><i class="fa fa-bus" aria-hidden="true" style="color:#40ff00;"></i><span class="ml-4">Outwards</span></a></li>
                    <li class=""><a href="/scraps" class="svg-icon"><i class="fa fa-trash" aria-hidden="true" style="color:red;"></i><span class="ml-4">Scrap Material</span></a></li>
                    <li class=""><a href="/requisitions" class="svg-icon"><i class="fa fa-bullhorn" aria-hidden="true" style="color:blue;"></i><span class="ml-4">Requisition</span></a></li>
                    <li class=""><a href="/requisitions/purchaseRequisitionList" class="svg-icon"><i class="fa fa-bullhorn" aria-hidden="true" style="color:orange;"></i><span class="ml-4">Purchase Requisition</span></a></li>
                    <li class=""><a href="/eventRequisitions" class="svg-icon"><i class="fa fa-bullhorn" aria-hidden="true" style="color:purple;"></i><span class="ml-4">Event Requisition</span></a></li>
                    <li class=""><a href="/assetProducts" class="svg-icon"><i class="fa fa-building-o" aria-hidden="true" style="color:red;"></i><span class="ml-4">Aaryans Assets</span></a></li>
                    <li class=""><a href="{{ route('repaires.index') }}" class="svg-icon"><i class="fa fa-cogs" aria-hidden="true" style="color:red;"></i><span class="ml-4">Repaire / Maintenance</span></a></li>
                    
                    @if($userType == '00' || $userType == '007' || $userType == '501')
                        <li class=""><a href="/vendor" class="svg-icon"><i class="	fa fa-user-secret" aria-hidden="true" style="color:blue;"></i><span class="ml-4">Vendors</span></a></li>
                        <li class=""><a href="/quotation/list" class="svg-icon"><i class="fa fa-language" aria-hidden="true" style="color:blue;"></i><span class="ml-4">Quotations</span></a></li>
                        <li class=""><a href="/workOrder" class="svg-icon"><i class="fa fa-file-word-o" aria-hidden="true" style="color:blue;"></i><span class="ml-4">Work Order</span></a></li>
                        <li class=""><a href="/purchaseOrder/purchaseOrderList" class="svg-icon"><i class="fa fa-file-powerpoint-o" aria-hidden="true" style="color:blue;"></i><span class="ml-4">Purchase Order</span></a></li>
                        <li class=""><a href="/payments/POUnpaidPaymentList" class="svg-icon"><i class="fa fa-bullhorn" aria-hidden="true" style="color:orange;"></i><span class="ml-4">PO Payment</span></a></li>
                        <li class=""><a href="/WOPayments/WOPayment" class="svg-icon"><i class="fa fa-bullhorn" aria-hidden="true" style="color:orange;"></i><span class="ml-4">WO Payment</span></a></li>
                    @endif

                    <li class=" ">
                        <a href="#FuelMgmt" class="collapsed" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-burn" style="color:blue;"></i><span class="ml-4">Fuel Mgmt.</span>
                        </a>
                        <ul id="FuelMgmt" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            <li class=""><a href="/fuelSystems"><i class="fa fa-file"  style="color:red;"></i><span>Fuel Entry</span></a></li>    
                            <li class=""><a href="/fuelSystems/fuelReports"><i class="fa fa-file"  style="color:red;"></i><span>Fuel Report</span></a></li>    
                        </ul>
                    </li>
                     <li class=" ">
                        <a href="#auditMgmt" class="collapsed" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-burn" style="color:blue;"></i><span class="ml-4">Audit Management</span>
                        </a>
                        <ul id="auditMgmt" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            <li class=""><a href="/audits"><i class="fa fa-file"  style="color:red;"></i><span>Add</span></a></li>    
                        </ul>
                    </li>
                    
                    <li class=" ">
                        <a href="#reports" class="collapsed" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-file-excel-o" style="color:green;"></i><span class="ml-4">Reports</span>
                        </a>
                        <ul id="reports" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            <li class=""><a href="/reports/openingStockReport"><i class="fa fa-file-archive-o"  style="color:red;"></i><span>Opening Stock</span></a></li>    
                            <li class=""><a href="/reports/EODProductReport"><i class="fa fa-file-archive-o"  style="color:red;"></i><span>EOD Product</span></a></li>    
                            <li class=""><a href="/reports/outwardReport"><i class="fa fa-file-archive-o"  style="color:red;"></i><span>Outward Report</span></a></li>    
                            <li class=""><a href="/reports/productWiseReport"><i class="fa fa-file-archive-o"  style="color:red;"></i><span>Product Wise Report</span></a></li>    
                            <li class=""><a href="/reports/inwardReport"><i class="fa fa-file-archive-o"  style="color:red;"></i><span>Inward Report</span></a></li>    
                        </ul>
                    </li>
                @endif

                @if($userType == '61')
                    <li class=""><a href="/purchaseHome" class="svg-icon"><i class="fa fa-tachometer" aria-hidden="true" style="color:green;"></i><span class="ml-4">Dashboards</span></a></li>
                    <li class=""><a href="/inwards" class="svg-icon"><i class="fa fa-bus" aria-hidden="true" style="color:grreen;"></i><span class="ml-4">Inwards</span></a></li>
                    <li class=""><a href="/vendor" class="svg-icon"><i class="	fa fa-user-secret" aria-hidden="true" style="color:blue;"></i><span class="ml-4">Vendors</span></a></li>
                    <li class=""><a href="/quotation/list" class="svg-icon"><i class="fa fa-language" aria-hidden="true" style="color:blue;"></i><span class="ml-4">Quotations</span></a></li>
                    <li class=""><a href="/purchaseOrder/purchaseOrderList" class="svg-icon"><i class="fa fa-file-powerpoint-o" aria-hidden="true" style="color:blue;"></i><span class="ml-4">Purchase Order</span></a></li>
                    <li class=""><a href="/workOrder" class="svg-icon"><i class="fa fa-file-word-o" aria-hidden="true" style="color:blue;"></i><span class="ml-4">Work Order</span></a></li>
                    <li class=" ">
                        <a href="#requisitionManagement" class="collapsed" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-recycle"></i>
                            <span class="ml-4">Requisition Management</span>
                            <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="10 15 15 20 20 15"></polyline><path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                            </svg>
                        </a>
                        <ul id="requisitionManagement" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            <li class=""><a href="/requisitions/masterProductList"><i class="fa fa-recycle"></i><span>Product List</span></a></li>     
                            <li class=""><a href="/requisitions"><i class="fa fa-question-circle" aria-hidden="true"></i><span>Requisition</span></a></li>   
                            <li class=""><a href="/requisitions/purchaseRequisitionList"><i class="fa fa-question-circle" aria-hidden="true"></i><span>Purchase Requisition</span></a></li>   
                            <li class=""><a href="/eventRequisitions"><i class="fa fa-question-circle" aria-hidden="true"></i><span>Event Requisition</span></a></li>                          
                        </ul>
                    </li>
                    <li class=""><a href="/payments/POUnpaidPaymentList" class="svg-icon"><i class="fa fa-bullhorn" aria-hidden="true" style="color:orange;"></i><span class="ml-4">PO Payment</span></a></li>
                    <li class=""><a href="/WOPayments/WOPayment" class="svg-icon"><i class="fa fa-bullhorn" aria-hidden="true" style="color:orange;"></i><span class="ml-4">WO Payment</span></a></li>
                @endif

                @if($userType == '801')
                    <li class=""><a href="/purchaseHome" class="svg-icon"><i class="fa fa-tachometer" aria-hidden="true" style="color:green;"></i><span class="ml-4">Dashboards</span></a></li>
                    <li class=""><a href="/vendor" class="svg-icon"><i class="	fa fa-user-secret" aria-hidden="true" style="color:blue;"></i><span class="ml-4">Vendors</span></a></li>
                    <li class=""><a href="/quotation/list" class="svg-icon"><i class="fa fa-language" aria-hidden="true" style="color:blue;"></i><span class="ml-4">Quotations</span></a></li>
                    <li class=""><a href="/purchaseOrder/purchaseOrderList" class="svg-icon"><i class="fa fa-file-powerpoint-o" aria-hidden="true" style="color:blue;"></i><span class="ml-4">Purchase Order</span></a></li>
                    <li class=""><a href="/workOrder" class="svg-icon"><i class="fa fa-file-word-o" aria-hidden="true" style="color:blue;"></i><span class="ml-4">Work Order</span></a></li>
                    <li class="">
                        <a href="#requisitionManagement" class="collapsed" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-recycle"></i>
                            <span class="ml-4">Requisition Management</span>
                            <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="10 15 15 20 20 15"></polyline><path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                            </svg>
                        </a>
                        <ul id="requisitionManagement" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            <li class=""><a href="/requisitions/masterProductList"><i class="fa fa-recycle"></i><span>Product List</span></a></li>     
                            <li class=""><a href="/requisitions"><i class="fa fa-question-circle" aria-hidden="true"></i><span>Requisition</span></a></li>   
                            <li class=""><a href="/requisitions/purchaseRequisitionList"><i class="fa fa-question-circle" aria-hidden="true"></i><span>Purchase Requisition</span></a></li>   
                            <li class=""><a href="/eventRequisitions"><i class="fa fa-question-circle" aria-hidden="true"></i><span>Event Requisition</span></a></li>                          
                        </ul>
                    </li>
                    <li class=""><a href="/payments/POUnpaidPaymentList" class="svg-icon"><i class="fa fa-bullhorn" aria-hidden="true" style="color:orange;"></i><span class="ml-4">PO Payment</span></a></li>
                    <li class=""><a href="/WOPayments/WOPayment" class="svg-icon"><i class="fa fa-bullhorn" aria-hidden="true" style="color:orange;"></i><span class="ml-4">WO Payment</span></a></li>
                @endif

                @if($username == 'REQ 225' || $username == 'REQ 1410')
                    <li class=" ">
                        <a href="#FuelMgmt" class="collapsed" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-burn" style="color:blue;"></i><span class="ml-4">Fuel Mgmt.</span>
                        </a>
                        <ul id="FuelMgmt" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            <li class=""><a href="/fuelSystems"><i class="fa fa-file"  style="color:red;"></i><span>Fuel Entry</span></a></li>    
                            <li class=""><a href="/fuelSystems/fuelReports"><i class="fa fa-file"  style="color:red;"></i><span>Fuel Report</span></a></li>    
                        </ul>
                    </li>
                @endif
                @if($user->subStoreAccess == 1)
                    <li class=" ">
                        <a href="#subStores" class="collapsed" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-recycle"></i>
                            <span class="ml-4">Sub Store Management</span>
                            <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="10 15 15 20 20 15"></polyline><path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                            </svg>
                        </a>
                        <ul id="subStores" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            <li class=""><a href="/requisitions/masterProductList"><i class="fa fa-recycle"></i><span>Product List</span></a></li> 
                            <li class=""><a href="/subStores/inward"><i class="fa fa-inward"></i><span>Inward</span></a></li>     
                            <li class=""><a href="/subStores/create"><i class="fa fa-recycle"></i><span>Outward</span></a></li>     
                        </ul>
                    </li>
                    
                    <li class=" ">
                        <a href="#stockmanagement" class="collapsed" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-recycle"></i>
                            <span class="ml-4">Stock Management</span>
                            <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="10 15 15 20 20 15"></polyline><path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                            </svg>
                        </a>
                        <ul id="stockmanagement" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            <li class="">
                                <a href="/getBranchStock">
                                    <i class="fa fa-recycle"></i><span>Stock</span>
                                </a>
                            </li>     
                        </ul>
                    </li>
                @endif

                @if($userType == '701') 
                    <li class=""><a href="/purchaseHome" class="svg-icon"><i class="fa fa-tachometer" aria-hidden="true"></i><span class="ml-4">Dashboards</span></a></li>
                    <li class=""><a href="/vendor" class="svg-icon"><i class="	fa fa-user-secret" aria-hidden="true" style="color:blue;"></i><span class="ml-4">Vendors</span></a></li>
                    <li class=""><a href="/quotation/list" class="svg-icon"><i class="fa fa-language" aria-hidden="true" style="color:blue;"></i><span class="ml-4">Quotations</span></a></li>
                    <li class=""><a href="/workOrder" class="svg-icon"><i class="fa fa-file-word-o" aria-hidden="true" style="color:blue;"></i><span class="ml-4">Work Order</span></a></li>
                    <li class=""><a href="/purchaseOrder/purchaseOrderList" class="svg-icon"><i class="fa fa-file-powerpoint-o" aria-hidden="true" style="color:blue;"></i><span class="ml-4">Purchase Order</span></a></li>
                    <li class=""><a href="/requisitions" class="svg-icon"><i class="fa fa-bullhorn" aria-hidden="true" style="color:blue;"></i><span class="ml-4">Requisition</span></a></li>
                    <li class=""><a href="/requisitions/purchaseRequisitionList" class="svg-icon"><i class="fa fa-bullhorn" aria-hidden="true" style="color:orange;"></i><span class="ml-4">Purchase Requisition</span></a></li>
                    <li class=""><a href="/eventRequisitions" class="svg-icon"><i class="fa fa-bullhorn" aria-hidden="true" style="color:purple;"></i><span class="ml-4">Event Requisition</span></a></li>
                    <li class=""><a href="/purchaseOrder/productList" class="svg-icon"><i class="fa fa-wrench" aria-hidden="true" style="color:purple;"></i><span class="ml-4">Purchase Product</span></a></li>
                    <li class=""><a href="/payments/POUnpaidPaymentList" class="svg-icon"><i class="fa fa-money" aria-hidden="true" style="color:red;"></i><span class="ml-4">PO Payment</span></a></li>
                    <li class=""><a href="/WOPayments/WOPayment" class="svg-icon"><i class="fa fa-money" aria-hidden="true" style="color:red;"></i><span class="ml-4">WO Payment</span></a></li>
                    <li class=" ">
                        <a href="#reports" class="collapsed" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-file-excel-o" style="color:green;"></i><span class="ml-4">Reports</span>
                        </a>
                        <ul id="reports" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            <li class=""><a href="/reports/quotationReport"><i class="fa fa-file-archive-o"  style="color:red;"></i><span>Quotation </span></a></li>    
                            <li class=""><a href="/reports/purchaseOrderReport"><i class="fa fa-file-archive-o"  style="color:red;"></i><span>Purchase Order</span></a></li>    
                            <li class=""><a href="/reports/workOrderReport"><i class="fa fa-file-archive-o"  style="color:red;"></i><span>Work Order</span></a></li>    
                            <li class=""><a href="/reports/vendorWiseReport"><i class="fa fa-file-archive-o"  style="color:red;"></i><span>Vendor Wise</span></a></li>    
                        </ul>
                    </li>
                @endif

                @if($user->assetAccess == 1)
                    <li class=" ">
                        <a href="#assetManagement" class="collapsed" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-recycle"></i>
                            <span class="ml-4">Asset Management</span>
                            <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="10 15 15 20 20 15"></polyline><path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                            </svg>
                        </a>
                        <ul id="assetManagement" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            <li class="">
                                <a href="/assetProducts">
                                    <i class="fa fa-recycle"></i><span>Add Asset</span>
                                </a>
                            </li>     
                        </ul>
                    </li>
                @endif
            </ul>
        </nav>
        <div class="p-3"></div>
    </div>
</div>   