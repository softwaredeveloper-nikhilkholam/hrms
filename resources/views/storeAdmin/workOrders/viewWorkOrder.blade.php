<?php
$userType = Auth::user()->userType;
use App\Helpers\Utility;
$util=new Utility(); 
?>

@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div  class="col-lg-6"><b style="color:red;">WorkOrder List</b></div>
                        <div  class="col-lg-6 text-right">
                            @if(Auth::user()->userType == '701' || $userType == '801')
                                <a href="/workOrder/create" class="btn mb-1 btn-danger">Generate</a>
                            @endif
                            <a href="/workOrder" class="btn mb-1 btn-primary">Pending List <span class="badge badge-danger ml-2">{{$pendingOrdersCount}}</span></a>
                            <a href="/workOrder/approvedOrderList" class="btn mb-1 btn-primary">Approved List <span class="badge badge-danger ml-2">{{$approvedOrdersCount}}</span></a>
                            <a href="/workOrder/rejectedOrderList" class="btn mb-1 btn-primary">Rejected List <span class="badge badge-danger ml-2">{{$rejectedOrdersCount}}</span></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">  
                    <div class="col-lg-12">
                        @if($orders)
                            {!! Form::open(['action' => 'storeController\WorkOrdersController@approveWorkOrder', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                <?php $flag = 0; $xi=1; ?>
                                @foreach($orders as $order)
                                    <div class="card">
                                        <div class="card-header" style="background-color:#0080007d;text-align:center;">
                                            <h4>Work Order {{$order->WONo}}</h4>
                                        </div>
                                        <div class="card-body">
                                            <div id="WORadioDiv{{$xi}}">
                                                <div class="row">  
                                                    <div class="table-responsive">
                                                        <table class="table" style="vertical-align: top;">
                                                            <tr style="vertical-align: top;">
                                                                <td width="40%" class="text-left" style="vertical-align: top;"><h6 style="color:#57320a;">Vendor Details: </h6><b>{{$order->vendorName}}<br>{{$order->vendorAddress}}</b></td>
                                                                <td width="40%" class="text-left" style="vertical-align: top;" colspan="2"><h6 style="color:#57320a;">Bank Details: </h6><b>Account No: {{$order->accountNo}}</b><br>
                                                                <b>IFSC Code: {{$order->IFSCCode}}</b><br>
                                                                <b>Bank Branch: {{$order->bankBranch}}</b></td>
                                                                <td width="20%" class="text-left" style="vertical-align: top;"><h6 style="color:#57320a;">Work Order Copy: </h6><b><a href="/storeAdmin/workOrders/{{$order->workOrderFile}}">{{$order->workOrderFile}}</a></b></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="40%" class="text-left" style="vertical-align: top;"><h6 style="color:#57320a;vertical-align: top;">Branch: </h6><b>{{$order->branchName}}<br>Address: {{$order->shippingAddress}}</b></td>
                                                                <td width="20%" class="text-left" style="vertical-align: top;"><h6 style="color:#57320a;vertical-align: top;">Bill No: </h6><b>{{$order->billNo}}</b></td>
                                                                <td width="20%" class="text-left" style="vertical-align: top;"><h6 style="color:#57320a;vertical-align: top;">Location in Branch: </h6><b>{{$order->locationInBranch}}</b></td>
                                                                <td width="20%" class="text-left" style="vertical-align: top;"><h6 style="color:#57320a;vertical-align: top;">Advance Payment: </h6><b>{{$order->advancePayment}}</b></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="40%" class="text-left"><h6 style="color:#57320a;vertical-align: top;">Already Paid: </h6><b style="color:{{($order->alreadyPaid == 0)?'red':'green'}};">{{($order->alreadyPaid == 0)?'No':'Yes'}}</b></td>
                                                                <td width="20%" class="text-left"><h6 style="color:#57320a;">Already Paid By: </h6><b>{{$order->alreadyPaidBy}}</b></td>
                                                                <td width="20%" class="text-left"><h6 style="color:#57320a;">Requisition No: </h6><b>{{$order->reqNo}}</b></td>
                                                                <td width="20%" class="text-left"><h6 style="color:#57320a;">Date: </h6><b>{{$order->forDate}}</b></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="100%" colspan="2" class="text-left"><h6 style="color:#57320a;">Work Order For: </h6>
                                                                <b>{{$order->WOFor}}</b>
                                                                </td>
                                                                <td width="20%" colspan="2" class="text-left"><h6 style="color:#57320a;">Type of Company: </h6><b>{{$order->tempTypeOfCompany}}</b></td>
                                                            </tr>
                                                        </table>
                                                    </div>   
                                                </div>   
                                                <hr>
                                                <div class="table-responsive">
                                                    <table style="font-size:12px; border: 1px solid;" width="100%">
                                                        <tr>
                                                            <th style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:white;font-size:12px; border: 1px solid;background-color:gray;" width="5%"><b>No</b></th>
                                                            <th style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:white;font-size:12px; border: 1px solid;background-color:gray;"><b>Particular</b></th>           
                                                            <th style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:white;font-size:12px; border: 1px solid;background-color:gray;" width="12%"><b>Qty</b></th>
                                                            <th style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:white;font-size:12px; border: 1px solid;background-color:gray;" width="12%"><b>Unit</b></th>
                                                            <th style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:white;font-size:12px; border: 1px solid;background-color:gray;" width="12%"><b>Rate</b></th>
                                                            <th style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:white;font-size:12px; border: 1px solid;background-color:gray;" width="10%"><b>Amount</b><?php $i=1; ?></th>
                                                        </tr>
                                                        <?php $products = $util->getWorkOrderList($order->id); ?>
                                                        @if(count($products))
                                                            @foreach($products as $product)
                                                                <tr style="font-size:12px; border: 1px solid;font-weight: bold;color:black;">
                                                                    <td  style="padding: 0px 17px !important;font-size:12px; border: 1px solid;font-weight: bold;color:black;text-align:left;">{{$i++}}</td>
                                                                    <td  style="padding: 0px 17px !important;font-size:12px; border: 1px solid;font-weight: bold;color:black;text-align:left;">{{$product->particular}}</td>
                                                                    <td  style="padding: 0px 17px !important;font-size:12px; border: 1px solid;font-weight: bold;color:black;">{{$util->numberFormat($product->qty)}}</td>
                                                                    <td  style="padding: 0px 17px !important;font-size:12px; border: 1px solid;font-weight: bold;color:black;">{{$util->numberFormat($product->rate)}}</td>
                                                                    <td  style="padding: 0px 17px !important;font-size:12px; border: 1px solid;font-weight: bold;color:black;">{{$product->unit}}</td>
                                                                    <td  style="padding: 0px 17px !important;font-size:12px; border: 1px solid;font-weight: bold;color:black;">{{$util->numberFormat($product->amount)}}</td>
                                                                </tr>
                                                            @endforeach
                                                            <tr style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;">
                                                                <td style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;text-align:right;" colspan="5">LABOUR CHARGES</td>
                                                                <td style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;">{{$order->labourCharges}}</td>
                                                            </tr>
                                                            <tr style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;">
                                                                <td style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;text-align:right;" colspan="5">TRANSPORTATION</td>
                                                                <td style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;">{{$order->transportationRs}}</td>
                                                            </tr>
                                                            <tr style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;">
                                                                <td style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;text-align:right;" colspan="5">SHIFTING</td>
                                                                <td style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;">{{$order->shiftingCharges}}</td>
                                                            </tr>
                                                            <tr style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;">
                                                                <td style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;text-align:right;" colspan="5">DISCOUNT</td>
                                                                <td style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;">{{$order->discount}}</td>
                                                            </tr>
                                                            <tr style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;">
                                                                <td style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;text-align:right;" colspan="5">SUB TOTAL</td>
                                                                <td style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;">{{$order->totalRs}}</td>
                                                            </tr>
                                                            <tr style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;">
                                                                <td style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;text-align:right;" colspan="5">CGST</td>
                                                                <td style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;">{{$order->cgst}}</td>
                                                            </tr>
                                                            <tr style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;">
                                                                <td style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;text-align:right;" colspan="5">SGST</td>
                                                                <td style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;">{{$order->sgst}}</td>
                                                            </tr>
                                                            <tr style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;">
                                                                <td style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;text-align:right;" colspan="5">ADVANCE RS.</td>
                                                                <td style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;">{{$order->advancePayment}}</td>
                                                            </tr>
                                                            
                                                            <tr style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;">
                                                                <td style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;text-align:right;" colspan="5">GRAND TOTAL</td>
                                                                <td style="padding: 0px 17px !important;font-size:12px;font-weight: bold;color:black;">{{$order->finalRs}}
                                                                    <input type="hidden" value="{{$order->finalRs}}" id="finalRs{{$xi}}"></td>
                                                            </tr>
                                                        </table>
                                                    @endif
                                                </div>
                                                <br>
                                                @if($userType == '501')
                                                    @if($order->checkedByAuthority == 0)
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" id="WORadio{{$xi}}" name="optradio" value="{{$order->id}}" {{($order->WOStatus == 'Approved')?'checked':''}}>
                                                            <label class="form-check-label" for="radio{{$order->id}}"><h3 style="color:#57320a;">Approve {{($order->alreadyPaid == 1)?'[Payment Already Paid]':''}}</h3></label>
                                                        </div>
                                                    @else
                                                        <h4 style="color:{{($order->WOStatus == 'Pending')?'blue':(($order->WOStatus == 'Approved')?'green':'red')}};">Status : {{$order->WOStatus}}</h4>
                                                    @endif
                                                @else
                                                    <h4 style="color:{{($order->WOStatus == 'Pending')?'blue':(($order->WOStatus == 'Approved')?'green':'red')}};">Status : {{$order->WOStatus}}</h4>
                                                @endif
                                                <?php 
                                                    if($order->WOStatus == 'Approved')
                                                    {
                                                        $flag = $order->id;
                                                        $util=new Utility(); 
                                                    }    
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $xi++; ?>
                                @endforeach
                                @if($userType == '501' && $order->alreadyPaid == '0')
                                    <div class="card mt-3">
                                        <div class="card-header" style="background-color:purple;text-align:center;">
                                            <h4 style="color:white;">Payment Mode (Rs. <b id="finalAmountPayment"></b>)</h4>
                                        </div>
                                        <div class="card-body">
                                            @if($flag != 0)
                                                <?php $payments = $util->getWorkOrderPayment($flag); $i=1; ?>
                                                @foreach($payments as $payment)
                                                    <div class="row">   
                                                        <div class="col-md-2">
                                                            <div style="margin-top: 2rem;" class="form-group">
                                                                <label style="font-size:20px;"><b>Payment {{$i++}}</b></label>
                                                            </div>
                                                        </div>        
                                                        <div class="col-md-2">
                                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                                <label style="font-size:12px;"><b>Percent of Payment</b></label>
                                                                <input type="text" style="font-size:12px;" max="100" name="percent1" placeholder="Percent of Payment" value="{{$payment->percent}}" class="form-control" disabled>
                                                            </div>
                                                        </div>        
                                                        <div class="col-md-2">
                                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                                <label style="font-size:12px;"><b>Payment Date</b></label>
                                                                <input type="date" value="{{$payment->forDate}}" name="forDate1" style="font-size:12px;" placeholder="Date" class="form-control"  disabled>
                                                            </div>
                                                        </div>  
                                                        <div class="col-md-2">
                                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                                <label style="font-size:12px;"><b>Amount</b></label>
                                                                <input type="text" style="font-size:12px;" name="amount1" placeholder="Amount" value="{{$payment->amount}}" class="form-control"  disabled>
                                                            </div>
                                                        </div> 
                                                        <div class="col-md-4">
                                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                                <label style="font-size:12px;"><b>Remark</b></label>
                                                                <input type="text" style="font-size:12px;" name="remark1" placeholder="Remark" value="{{$payment->remark}}" class="form-control"  disabled>
                                                            </div>
                                                        </div>                                
                                                    </div>  
                                                @endforeach 
                                            @else
                                                <input type="hidden" value="0" id="approvedAmount">
                                                 <div id="payment-sections">
                                                   
                                                </div>
                                                <!-- Button to Add More -->
                                                <div class="text-right mb-3">
                                                    <button type="button" class="btn btn-primary btn-sm" onclick="addPaymentSection()">+ Add Payment</button>
                                                </div>
                                            @endif
                                            
                                        </div>
                                    </div>
                                @endif
                              
                                @if($userType == '501')
                                    <div class="row">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4">
                                            <input type="submit" value="Save" class="btn btn-primary btn-lg">
                                            <input type="reset" value="Cancel" class="btn btn-danger btn-lg">
                                        </div>  
                                        <div class="col-md-4"></div>
                                    </div> 
                                @endif
                            {!! Form::close() !!} 
                        @endif
                    </div>
                </div>  
            </div>
        </div>
    </div>
@endsection

<script>
let paymentIndex = 0;

// Add new payment section
function addPaymentSection() {
    paymentIndex++;
    const today = new Date().toISOString().split('T')[0];

    const newRow = `
    <div class="payment-block">
        <div class="row align-items-end payment-row" data-index="${paymentIndex}">   
            <div class="col-md-2">
                <div class="form-group mt-4">
                    <label class="h5 payment-label"><b>Payment ${paymentIndex}</b></label>
                </div>
            </div>        

            <div class="col-md-2">
                <div class="form-group">
                    <label class="small"><b>Percent of Payment</b></label>
                    <input type="number" class="form-control form-control-sm percent-input" 
                           name="percent[]" placeholder="%" min="0" max="100" 
                           oninput="updateAmount(this)" required>
                </div>
            </div>        

            <div class="col-md-2">
                <div class="form-group">
                    <label class="small"><b>Payment Date</b></label>
                    <input type="date" class="form-control form-control-sm" 
                           name="forDate[]" value="${today}" required>
                </div>
            </div>  

            <div class="col-md-2">
                <div class="form-group">
                    <label class="small"><b>Amount</b></label>
                    <input type="text" class="form-control form-control-sm amount-input" 
                           name="amount[]" placeholder="Amount" readonly>
                </div>
            </div> 

            <div class="col-md-3">
                <div class="form-group">
                    <label class="small"><b>Remark</b></label>
                    <input type="text" class="form-control form-control-sm" name="remark[]" placeholder="Remark">
                </div>
            </div>

            <div class="col-md-1 text-right">
                <button type="button" class="btn btn-danger btn-sm mt-4" onclick="removePaymentSection(this)">X</button>
            </div>                                
        </div>
        <hr>
    </div>`;

    document.getElementById("payment-sections").insertAdjacentHTML('beforeend', newRow);
}

// Remove row and renumber
function removePaymentSection(button) {
    const block = button.closest('.payment-block');
    if (block) {
        block.remove();
        paymentIndex--;
        renumberPayments();
    }
}

// Update amount based on percentage
function updateAmount(input) {
    const finalAmount = parseFloat(document.getElementById("finalAmountPayment").innerText) || 0;
    const percent = parseFloat(input.value) || 0;
    const row = input.closest('.payment-row');
    const amountInput = row.querySelector('.amount-input');

    if (percent < 0 || percent > 100) {
        alert("Percent must be between 0 and 100");
        input.value = '';
        return;
    }

    const calculatedAmount = (finalAmount * percent / 100).toFixed(2);
    amountInput.value = calculatedAmount;

    // Validate total percent and auto-remove if over
    const totalPercent = getTotalPercent();
    if (totalPercent > 100) {
        alert("Total percent cannot exceed 100%. This row will be removed.");
        const block = input.closest('.payment-block');
        if (block) {
            block.remove();
            paymentIndex--;
            renumberPayments();
        }
    }
}

// Calculate total percent of all inputs
function getTotalPercent() {
    let total = 0;
    document.querySelectorAll('.percent-input').forEach(input => {
        total += parseFloat(input.value) || 0;
    });
    return total;
}

// Renumber payment labels
function renumberPayments() {
    const rows = document.querySelectorAll('.payment-row');
    rows.forEach((row, index) => {
        const label = row.querySelector('.payment-label');
        if (label) {
            label.innerHTML = `<b>Payment ${index + 1}</b>`;
        }
    });
}
</script>