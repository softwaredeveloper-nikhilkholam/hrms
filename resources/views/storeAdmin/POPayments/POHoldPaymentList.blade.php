<?php
    $userType = Auth::user()->userType;
    use App\Helpers\Utility;
    $util=new Utility(); 
?>
@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content')
<style>
    /* Global box-sizing for consistent layout */
    * {
        box-sizing: border-box;
    }

    /* Style for the responsive table container to ensure the right border is not clipped */
    .table-responsive {
        padding-right: 1px; /* Small padding to prevent clipping of the rightmost border */
        padding-left: 0;
        padding-top: 0;
        padding-bottom: 0;
        /* Ensure no hidden overflow is causing issues with the top/bottom borders */
        overflow-x: auto; /* Re-confirming horizontal scroll if needed */
        overflow-y: visible; /* Ensure vertical borders are not clipped */
    }

    /* Styles for the table itself */
    #datatable {
        border-collapse: collapse; /* Essential for contiguous borders between cells */
        width: 100%; /* Ensure it fills its container */
        /* Explicitly add a border to the entire table element */
        border: 1px solid #dee2e6; /* THIS IS THE KEY FIX FOR THE OUTER BORDER */
        /* You might want to consider table-layout: fixed; for more predictable column widths */
        /* table-layout: fixed; */
        /* Optional: Add a subtle box-shadow for visual debugging and separation */
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    /* Apply consistent borders to all table cells and headers */
    /* This is the primary rule for ensuring all borders are visible */
    #datatable th,
    #datatable td {
        border: 1px solid #dee2e6; /* Standard Bootstrap border color for consistency */
        /* Ensure padding is consistent, overriding any conflicting inline/other rules */
        padding: 8px 12px !important; /* Slightly increased padding for better appearance */
        font-size: 14px;
        color: black;
        vertical-align: middle; /* Vertically align content in the middle of cells */
    }

    /* Explicitly ensure the right border of the last column cells, though the general rule should cover it */
    #datatable th:last-child,
    #datatable td:last-child {
        border-right: 1px solid #dee2e6;
    }

    /* Specific alignments to override inline styles where necessary */
    #datatable .text-center { text-align: center !important; }
    #datatable .text-left { text-align: left !important; }
    #datatable .text-right { text-align: right !important; }

    /* Header row specific styling */
    #datatable thead tr th {
        font-size: 15px; /* Ensure header font size */
        font-weight: bold; /* Make headers bold */
        text-align: center; /* Center header text */
        background-color: #4965e3a1; /* Light background for headers */
        color: #343a40; /* Darker text for headers */
    }

    /* Action button/badge styling */
    .list-action .badge {
        display: inline-flex; /* Use flex to center icon inside badge */
        align-items: center;
        justify-content: center;
        min-width: 30px; /* Ensure a minimum size for easy clicking */
        min-height: 30px; /* Ensure a minimum size for easy clicking */
        padding: 5px; /* Adjust padding as needed */
        border-radius: 8px; /* Slightly rounded corners for the badge */
        text-decoration: none; /* Remove underline from link */
        transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth transitions */
    }

    .list-action .badge:hover {
        transform: translateY(-2px); /* Slight lift on hover */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Add shadow on hover */
    }

    /* Font Awesome icon specific styling */
    .list-action .badge i.fa {
        color: white; /* Ensure the icon color is white for contrast on colored badges */
        font-size: 16px; /* Adjust icon size */
    }

    /* Specific warning badge background color */
    .list-action .badge.bg-warning {
        background-color: #ffc107 !important; /* Bootstrap warning color */
        border: 1px solid #e0a800; /* Darker border for depth */
    }

    .list-action .badge.bg-warning:hover {
        background-color: #e0a800 !important; /* Darker warning on hover */
    }
</style> 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">                   
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-"><b style="color:red;">PO Paid List</b></div>
                            <div  class="col-lg-4">
                                <a href="/payments/POUnpaidPaymentList" class="btn mb-1 btn-primary">Pending List</a>
                                <a href="/payments/POHoldPaymentList" class="btn mb-1 btn-success">Hold List</a>
                                <a href="/payments/POPaidPaymentList" class="btn mb-1 btn-primary">Paid List</a>
                                <a href="/payments/PORejectedPaymentList" class="btn mb-1 btn-primary">Rejected List</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\PaymentsController@POHoldPaymentList', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                        <div class="row ">
                            <div class="col-md-2 col-lg-2">
                                <div class="form-group">
                                    <input type="month" class="form-control" value="{{($forMonth)?$forMonth:date('Y-m')}}" name="forMonth" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    {{Form::select('typeOfCompanyId', $typeOfCompanies, $selectedTypeOfCompany, ['class'=>'form-control', 'placeholder'=>'Select Type Of Company', 'id'=>'typeOfCompanyId'])}}
                                </div>
                            </div>
                            <div class="col-md-2 col-lg-2 mt-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">Search</button>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!} 
                    <div class="row">
                    <div class="col-lg-12">
                            @if(count($payments))
                                <div class="table-responsive">
                                    <div class="table-responsive">
                                    <table id="datatable" data-page-length='50' width="100%" class="table table-bordered data-table table-striped">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th width="5%">No</th>
                                                <th width="10%">PO No</th>
                                                <th>Vendor Name</th>
                                                <th>Quotation For</th>
                                                <th width="6%">Type</th>
                                                <th width="6%">Pay Date</th>
                                                <th width="5%">Per %</th>
                                                <th width="5%">Pay</th>
                                                <th width="6%">Updated</th>
                                                <th width="5%">Action</th> {{-- Removed PHP counter from here --}}
                                            </tr>
                                        </thead>
                                        <tbody class="ligth-body">
                                            <?php $i=1; ?> {{-- Initialized $i here for the serial number --}}
                                            @foreach($payments as $payment)
                                                <tr>
                                                    <td class="text-center">{{$i++}}</td>
                                                    <td class="text-center"><a href="/purchaseOrder/viewPONumber/{{base64_encode($payment->poNumber)}}" style="color:black;" target="_blank">{{$payment->poNumber}}</a></td>
                                                    <td class="text-center"><a href="/vendor/{{$payment->vendorId}}" style="color:black;" target="_blank">{{$payment->vendorName}}</a></td>
                                                    <td class="text-left">{{$payment->quotationFor}}</td>
                                                    <td class="text-center">{{$payment->typeOfCompanyName}}</td>
                                                    <td class="text-center">{{date('d-m-Y', strtotime($payment->forDate))}}</td>
                                                    <td class="text-center">{{round($payment->percent, 0)}}%</td>
                                                    <td class="text-center">{{$util->numberFormatRound($payment->amount)}}</td>
                                                    <td class="text-center">{{date('d-m-Y', strtotime($payment->updated_at))}}</td>
                                                    <td class="text-center">
                                                        <div class="d-flex align-items-center justify-content-center list-action">
                                                            <a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top" title="View Quotation"
                                                                href="/payments/{{$payment->id}}/POPaymentEdit">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                                <div class="row">
                                <div class="col-md-10"></div>
                                <div class="col-md-2 text-right">
                                    <a href="/payments/{{$forMonth}}/4/{{($selectedTypeOfCompany == '')?'0':$selectedTypeOfCompany}}/exportPOPayments" class="btn mb-1 btn-success mt-5">Export Excel<i class="fa fa-excel-o"></i></a>
                                </div>
                            @else
                                <h4>Record not found</h4>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
