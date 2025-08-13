<?php
$userType = Auth::user()->userType;
$username = Auth::user()->username;
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
                            <div  class="col-lg-"><b style="color:red;">Update Payment</b></div>
                            <div  class="col-lg-4">
                                <a href="/payments/POPaidPayments" class="btn mb-1 btn-primary">Paid List</a>
                                <a href="/payments/POPayment" class="btn mb-1 btn-primary">Unpaid List</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => ['storeController\PurchaseTransactions@updatePayment'], 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="row"> 
                            <div class="col-md-3">                      
                                <div class="form-group">
                                    <label>Vendor Name <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{$payment->vendorName}}" placeholder="Enter Vendor Name" disabled>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div> 
                            <div class="col-md-6">                      
                                <div class="form-group">
                                    <label>Vendor Address<span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" value="{{$payment->address}}" name="company" placeholder="Enter Vendor Address" disabled>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div> 
                            <div class="col-md-3">                      
                                <div class="form-group">
                                    <label>Vendor Contact Number<span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" value="{{$payment->contactPerNo1}}" name="company" placeholder="Enter Vendor Contact Number" disabled>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div> 
                        </div>
                        <div class="row"> 
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Quotation Number<span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{$payment->quotNo}}" placeholder="Enter Product Name" disabled>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div> 
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>PO Number<span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" value="{{$payment->poNumber}}" name="company" placeholder="Enter Company Name" disabled>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div> 
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>PO Amount</label>
                                    <input type="text" class="form-control" name="modelNumber" value="{{$poDetails->poAmount}}" placeholder="Enter Model Number" disabled>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>   
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Paid Amount</label>
                                    <input type="text" class="form-control" name="modelNumber" value="{{$poDetails->paidAmount}}" placeholder="Enter Model Number" disabled>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>   .
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Remaining Amount</label>
                                    <input type="text" class="form-control" name="modelNumber" value="{{$poDetails->poAmount - $poDetails->paidAmount}}" placeholder="Enter Model Number" disabled>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>      
                        </div>
                        <div class="row"> 
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Account Number <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" name="accountNo" value="{{$payment->accountNo}}" placeholder="Enter Account Number"  {{($payment->status == 1 && $userType == '61')?'required':'disabled' }}>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div> 
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Bank Name<span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" value="{{$payment->bankBranch}}" name="bankBranch" placeholder="Enter Bank Name"  {{($payment->status == 1 && $userType == '61')?'required':'disabled' }}>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div> 
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>IFSC Code<span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" value="{{$payment->IFSCCode}}" name="IFSCCode" placeholder="Enter IFSC Code"  {{($payment->status == 1 && $userType == '61')?'required':'disabled' }}>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div> 
                        </div>
                        <div class="row"> 
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Payment Percent<span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" name="percent" value="{{$payment->percent}}%" placeholder="Enter Payment Amount" disabled>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div> 
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Payment Amount<span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" name="paymentAmount" value="{{$payment->amount}}" placeholder="Enter Payment Amount" disabled>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div> 
                            @if($username == 'account department')
                                <div class="col-md-2">                      
                                    <div class="form-group">
                                        <label>Transaction Number<span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" value="{{$payment->transactionId}}" name="transactionNumber" placeholder="Enter Transaction Number"  required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
                                <div class="col-md-2">                      
                                    <div class="form-group">
                                        <label>Transfer Date<span style="color:red;">*</span></label>
                                        <input type="date" class="form-control" value="{{$payment->transferDate}}" name="transferDate" placeholder="Enter Transfer Date"  required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
                                <div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>Payment Remark</label>
                                        <input type="text" class="form-control" name="paymentRemark" value="{{$payment->paymentRemark}}" placeholder="Enter Payment Remark" >
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>   
                            @else
                                <div class="col-md-2">                      
                                    <div class="form-group">
                                        <label>Transaction Number<span style="color:red;">*</span></label>
                                        <input type="text" class="form-control" value="{{$payment->transactionId}}" name="transactionNumber" placeholder="Enter Transaction Number"  {{($payment->status == 1 && $userType == '61')?'required':'disabled' }}>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
                                <div class="col-md-2">                      
                                    <div class="form-group">
                                        <label>Transfer Date<span style="color:red;">*</span></label>
                                        <input type="date" class="form-control" value="{{$payment->transferDate}}" name="transferDate" placeholder="Enter Transfer Date"  {{($payment->status == 1 && $userType == '61')?'required':'disabled' }}>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
                                <div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>Payment Remark</label>
                                        <input type="text" class="form-control" name="paymentRemark" value="{{$payment->paymentRemark}}" placeholder="Enter Payment Remark" {{($payment->status == 1 && $userType == '61')?'':'disabled' }}>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>   
                            @endif
                           
                        </div>
                        <hr>  
                        @if($payment->status == 1 && $userType == '61') 
                            <input type="hidden" value="{{$payment->vendorId}}" name="vendorId">  
                            <input type="hidden" value="{{$payment->id}}" name="paymentId">  
                            <a class="btn btn-danger mr-2" href="/payments/{{$payment->id}}/rejectPayment">Reject Payment</a>
                            <button type="submit" class="btn btn-success mr-2">Save</button>
                        @elseif($username = 'account department')
                            <input type="hidden" value="{{$payment->vendorId}}" name="vendorId">  
                            <input type="hidden" value="{{$payment->id}}" name="paymentId">  
                            <button type="submit" class="btn btn-success mr-2">Save</button>
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
                                        <th>No</th>
                                        <th>Date</th>
                                        <th>Percent</th>
                                        <th>Amount</th>
                                        <th>transferDate</th>
                                        <th>transactionId</th>
                                        <th>Payment Remark<?php $i=1; ?></th>
                                    </tr>
                                </thead>
                                <tbody class="ligth-body">
                                    @foreach($paymentHistory as $history)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$history->forDate}}</td>
                                            <td>{{$history->percent}}</td>
                                            <td>{{$history->amount}}</td>
                                            <td>{{$history->transferDate}}</td>
                                            <td>{{$history->transactionId}}</td>
                                            <td>{{$history->paymentRemark}}</td>
                                        </tr>
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
