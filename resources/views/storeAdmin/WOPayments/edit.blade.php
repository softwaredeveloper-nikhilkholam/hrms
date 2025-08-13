<?php
$userType = Auth::user()->userType;
$username = Auth::user()->username;
$reqDepartmentId = Auth::user()->reqDepartmentId;
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
                            <div  class="col-lg-"><b style="color:red;">Update Work Order Payment</b></div>
                            <div  class="col-lg-4">
                                <a href="/WOPayments/WOPayment" class="btn mb-1 btn-primary">Unpaid List</a>
                                <a href="/WOPayments/WOPaidPayments" class="btn mb-1 btn-primary">Paid List</a>
                                <a href="/WOPayments/WORejectPayments" class="btn mb-1 btn-primary">Rejected List</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => ['storeController\WorkOrdersController@updatePayment'], 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                    {{-- Vendor Info --}}
                        <div class="form-row mb-3">
                            <div class="col-md-12 text-center">
                                <h5  class="text-center">{{ $payment->vendorName }}</h5>
                                <h5  class="text-center">{{ $payment->address }}</h5>
                                <h5  class="text-center">{{ $payment->contactPerson1 }} - {{ $payment->contactPerNo1 }}</h5>
                            </div>
                        </div>
                        <hr>
                        {{-- PO Amounts --}}
                        <div class="form-row mb-3">
                            <div class="col-md-2">
                                <h6 class="text-center">WO Amount</h6>
                                <h5  class="text-center" style="color:red;">{{ ($WODetails->poAmount) }}</h5>
                            </div>
                            <div class="col-md-2">
                                <h6 class="text-center">Paid Amount</h6>
                                <h5  class="text-center" style="color:red;">{{ $WODetails->paidAmount }}</h5>
                            </div>
                            <div class="col-md-2">
                                <h6 class="text-center">Remaining</h6>
                                <h5  class="text-center" style="color:red;">{{ ($WODetails->poAmount - $WODetails->paidAmount) }}</h5>
                            </div>
                            <div class="col-md-2">
                                <h6 class="text-center">WO Number</label>
                                <h5  class="text-center" style="color:red;">{{ $payment->poNumber }}</h5>
                            </div>
                            <div class="col-md-2">
                                <h6 class="text-center">Payment Percent</label>
                                <h5  class="text-center" style="color:red;">{{ number_format($payment->percent, 2) }}%</p>
                            </div>
                            <div class="col-md-2">
                                <h6 class="text-center">Payment Amount</label>
                                <h5  class="text-center" style="color:red;">{{ number_format($payment->amount, 2) }}</h5>
                            </div>
                        </div>
                        {{-- Editable / Readonly Fields --}}
                        <div class="form-row mb-3">
                            <div class="col-md-2">
                                <h6 class="text-center">Select Status<span style="color:red;">*</span></label>
                                {{Form::select('paymentStatus', ['1'=>'Pending', '2'=>'Approved', '3'=>'Rejected', '4'=>'Hold'], old('accountNo', $payment->status), ['class'=>'form-control', 'placeholder'=>'Select Status', 'required', 'id'=>'paymentStatus',"style"=>"height: 40px !important;font-size:16px !important;"])}}
                            </div>      
                            <div class="col-md-2 statusView1">
                                <h6 class="text-center">Account Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="accountNo" value="{{ old('accountNo', $payment->accountNo) }}" style="height: 40px !important;font-size:16px !important;">
                            </div>
                            <div class="col-md-2 statusView2">
                                <h6 class="text-center">Bank Name <span class="text-danger">*</span></label>
                                <input type="text"  class="form-control text-uppercase" id="bankBranch" name="bankBranch" value="{{ old('bankBranch', $payment->bankBranch) }}" style="height: 40px !important;font-size:16px !important;">
                            </div>
                            <div class="col-md-2 statusView3">
                                <h6 class="text-center">IFSC Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" name="IFSCCode" id="IFSCCode" value="{{ old('IFSCCode', $payment->IFSCCode) }}" style="height: 40px !important;font-size:16px !important;">
                            </div>
                            <div class="col-md-2 statusView4">
                                <h6 class="text-center">Transaction Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="transactionNumber" value="{{ old('transactionNumber', $payment->transactionId) }}" style="height: 40px !important;font-size:16px !important;">
                            </div>
                            <div class="col-md-2 statusView5">
                                <h6 class="text-center">Transfer Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="transferDate" value="{{ old('transferDate', $payment->transferDate) }}" style="height: 40px !important;font-size:16px !important;">
                            </div>
                        </div>
                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <h6 class="text-center">Payment Remark</label>
                                <textarea style="height: 100px !important;" placeholder="Payment Remark" rows="5" cols="5" class="form-control" name="paymentRemark">{{ old('paymentRemark', $payment->paymentRemark) }}</textarea>
                            </div>
                        </div>

                        @if($payment->status == 1 &&  ($userType == '61' || $reqDepartmentId == 12)) 
                            <div class="form-row mb-3 justify-content-center">
                                <input type="hidden" value="{{$payment->vendorId}}" name="vendorId">  
                                <input type="hidden" value="{{$payment->id}}" name="paymentId">  
                                <button type="submit" class="btn btn-success mr-2" style="height: 45px !important;font-size:16px !important;">Save</button>
                            </div>
                        @else
                            <div class="row"> 
                                <div class="col-md-2">                      
                                    <div class="form-group">
                                        <label>Status<span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" name="paymentAmount" value="{{($payment->status == 1)?'Pending':(($payment->status == 2)?'Transfer':'Rejected')}}" placeholder="Enter Payment Amount" disabled>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
                                <div class="col-md-2">                      
                                    <div class="form-group">
                                        <label>Updated At<span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" name="paymentAmount" value="{{date('d-m-Y H:i', strtotime($payment->updated_at))}}" placeholder="Enter Payment Amount" disabled>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
                                <div class="col-md-2">                      
                                    <div class="form-group">
                                        <label>Updated By<span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" value="{{$payment->updated_by}}" name="transactionNumber" placeholder="Enter Transaction Number"  disabled>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
                            </div>
                        @endif

                        <hr> 
                        @if(count($paymentHistory))
                            <div class="table-responsive">
                                <table id="datatable" data-page-length='25' width="100%" class="table table-bordered data-table table-striped" style="">
                                    <thead class="bg-white text-uppercase">
                                        <tr>
                                            <th style="padding: 5px 12px !important;font-size:12px;">No</th>
                                            <th style="padding: 5px 12px !important;font-size:12px;">Date</th>
                                            <th style="padding: 5px 12px !important;font-size:12px;">Percent</th>
                                            <th style="padding: 5px 12px !important;font-size:12px;">Amount</th>
                                            <th style="padding: 5px 12px !important;font-size:12px;">transferDate</th>
                                            <th style="padding: 5px 12px !important;font-size:12px;">transactionId</th>
                                            <th style="padding: 5px 12px !important;font-size:12px;">Payment Remark<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody class="ligth-body">
                                        @foreach($paymentHistory as $history)
                                            @if($history->amount != 0)
                                            <tr>
                                                <td style="padding: 0px 12px !important;font-size:10px;color:black;">{{$i++}}</td>
                                                <td style="padding: 0px 12px !important;font-size:10px;color:black;">{{$history->forDate}}</td>
                                                <td style="padding: 0px 12px !important;font-size:10px;color:black;">{{$history->percent}}</td>
                                                <td style="padding: 0px 12px !important;font-size:10px;color:black;">{{$history->amount}}</td>
                                                <td style="padding: 0px 12px !important;font-size:10px;color:black;">{{$history->transferDate}}</td>
                                                <td style="padding: 0px 12px !important;font-size:10px;color:black;">{{$history->transactionId}}</td>
                                                <td style="padding: 0px 12px !important;font-size:10px;color:black;">{{$history->paymentRemark}}</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </thead>
                                </table>
                            </div>
                        @else
                            <h5 style="color:red;">Not Fount Any Payment History of this PO...</h5>
                        @endif
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
