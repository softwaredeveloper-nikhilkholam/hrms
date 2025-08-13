<?php
    $userType = Auth::user()->userType;
    use App\Helpers\Utility;
    $util=new Utility(); 
?>
@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-"><b style="color:red;">Reject WorkOrder List</b></div>
                            <div  class="col-lg-4">
                                 <a href="/WOPayments/WOPayment" class="btn mb-1 btn-primary">Unpaid List</a>
                                <a href="/WOPayments/WOPaidPayments" class="btn mb-1 btn-primary">Paid List</a>
                                <a href="/WOPayments/WOHoldPayments" class="btn mb-1 btn-primary">Hold List</a>
                                <a href="/WOPayments/WORejectedPayments" class="btn mb-1 btn-success">Rejected List</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\WorkOrdersController@WOPaidPayments', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                        <div class="row mt-2">
                            <div class="col-md-3 col-lg-3">
                                <div class="form-group">
                                    <input type="month" class="form-control" value="{{(isset($forMonth))?$forMonth:''}}" name="forMonth" required>
                                </div>
                            </div>
                            <div class="col-md-2 col-lg-2">
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
                                    <table id="datatable" data-page-length='50' width="100%" class="table table-bordered data-table table-striped">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th width="5%">No</th>
                                                <th width="10%">WO No</th>
                                                <th>Vendor Name</th>
                                                <th>Workorder For</th>
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
                                                    <td class="text-center"><a href="/workOrder/viewWorkOrder/{{base64_encode($payment->poNumber)}}" style="color:black;" target="_blank">{{$payment->poNumber}}</a></td>
                                                    <td class="text-center"><a href="/vendor/{{$payment->vendorId}}" style="color:black;" target="_blank">{{$payment->vendorName}}</a></td>
                                                    <td class="text-left">{{$payment->WOFor}}</td>
                                                    <td class="text-center">{{$payment->typeOfCompanyName}}</td>
                                                    <td class="text-center">{{date('d-m-Y', strtotime($payment->forDate))}}</td>
                                                    <td class="text-center">{{round($payment->percent, 0)}}%</td>
                                                    <td class="text-center">{{$util->numberFormatRound($payment->amount)}}</td>
                                                    <td class="text-center">{{date('d-m-Y', strtotime($payment->updated_at))}}</td>
                                                    <td class="text-center">
                                                        <div class="d-flex align-items-center justify-content-center list-action">
                                                            <a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top" title="View Quotation"
                                                                href="/WOPayments/edit/{{$payment->id}}">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                <div class="col-md-10"></div>
                                <div class="col-md-2 text-right">
                                    <a href="/WOPayments/{{$forMonth}}/3/{{($selectedTypeOfCompany == '')?0:$selectedTypeOfCompany}}/exportWOPayments" class="btn mb-1 btn-success mt-5">Export&nbsp;&nbsp;<i class="fa fa-file-excel"></i></a>
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
