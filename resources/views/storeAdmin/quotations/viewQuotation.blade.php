<?php
$userType = Auth::user()->userType;
use App\Helpers\Utility;
$util=new Utility(); 
?>

@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

@section('content') 
<style>
    .form-group {
        border: 1px solid #dcdcdc;
        border-radius: 10px;
        padding: 10px 15px;
        background-color: #f9f9f9;
        margin-bottom: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .form-group label {
        font-size: 12px;
        font-weight: bold;
        color: #444;
    }

    .form-group h5, .form-group h6, .form-group b {
        margin-bottom: 4px;
    }

    .form-group i {
        margin-right: 6px;
    }
</style>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div  class="col-lg-6"><b style="color:red;">Quotation Details</b></div>
                        <div  class="col-lg-6 text-right">
                            @if(Auth::user()->userType == '701' || $userType == '801')
                                <a href="/quotation" class="btn mb-1 btn-primary">Generate</a>
                            @endif
                            <a href="/quotation/quotationList" class="btn mb-1 btn-primary">Pending List</a>
                            <a href="/quotation/approvedQuotationList" class="btn mb-1 btn-primary">Approved List</a>
                            <a href="/quotation/rejectedQuotationList" class="btn mb-1 btn-primary">Rejected List</a>
                            <a href="/quotation/saveList" class="btn mb-1 btn-primary">Save List</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">  
                    <div class="col-lg-12">
                        @if($quotations)
                            {!! Form::open(['action' => 'storeController\PurchaseTransactions@approveQuotation', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                <?php $flag = 0; $xi=1; ?>
                                @foreach($quotations as $quotation)
                                    <div class="card">
                                        <div class="card-header" style="background-color:#0080007d;text-align:center;">
                                            <h4>Quotation {{$quotation->quotNo}}&nbsp;&nbsp;&nbsp;[<b style="color:red;">{{$quotation->quotStatus}}]</b></h4>
                                            <?php $vDetail = $util->getVendorDetails($quotation->vendorId); ?>
                                        </div>
                                        <div class="card-body">
                                            <div  id="qutRadioDiv{{$xi}}">
                                                <div class="row">  
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label><i class="fas fa-building text-primary"></i>Name & Address of Company</label>
                                                            <h5 class="text-dark">{{ $vDetail->name }}</h5>
                                                            <h6><i class="fas fa-map-marker-alt text-info"></i>Address: {{ $vDetail->address }}</h6>
                                                            <b><i class="fas fa-box text-warning"></i>Material Provider: {{ $vDetail->materialProvider }}</b>
                                                            <input type="hidden" value="{{ $quotation->id }}" name="vendorId[]">
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label><i class="fas fa-university text-success"></i>Bank Details</label>
                                                            <h5><i class="fas fa-wallet text-dark"></i> A/C No: {{ $quotation->accountNo }}</h5>
                                                            <h6><i class="fas fa-code-branch text-info"></i> IFSC Code: {{ $quotation->IFSCCode }}</h6>
                                                            <b>{{ $quotation->bankBranch }}</b>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2"></div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label><i class="fas fa-calendar-day text-danger"></i>Date <span style="color:red;">*</span></label>
                                                            <h6>{{ date('d-m-Y', strtotime($quotation->validDate)) }}</h6>
                                                        </div>
                                                    </div>                                                                                    
                                                </div>   

                                                <div class="row">  
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label><i class="fas fa-industry text-secondary"></i>Type of Company</label>
                                                            <h6>{{ $quotation->typeOfCompany }}</h6>
                                                        </div>
                                                    </div>  

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label><i class="fas fa-shipping-fast text-primary"></i>Shipping Address</label>
                                                            <h6>{{ $quotation->shippingAddress }}</h6>
                                                        </div>
                                                    </div>          

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label><i class="fas fa-file-invoice-dollar text-success"></i>Terms of payment</label>
                                                            <h6>{{ $quotation->termsOfPayment }}</h6>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label><i class="fas fa-file-alt text-info"></i>Requisition No</label>
                                                            <h6>{{ $quotation->reqNo }}</h6>
                                                        </div>
                                                    </div>                                    
                                                </div>   

                                                <div class="row">                                                     
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label><i class="fas fa-calendar-check text-warning"></i>Tentative delivery date</label>
                                                            <h6>{{ date('d-m-Y', strtotime($quotation->tentativeDate)) }}</h6>
                                                        </div>
                                                    </div>  

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label><i class="fas fa-user-edit text-primary"></i>Quotation Raised By</label>
                                                            <h6>{{ $quotation->raisedBy ? $util->getQuotRaisedBy($quotation->raisedBy) : '-' }}</h6>
                                                        </div>
                                                    </div>      

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label><i class="fas fa-check-circle text-success"></i>Already Paid</label>
                                                            <h6 style="color: {{ $quotation->alreadyPaid == '0' ? 'red' : 'green' }};">
                                                                {{ $quotation->alreadyPaid == '0' ? 'No' : 'Yes' }}
                                                            </h6>
                                                        </div>
                                                    </div>  

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label><i class="fas fa-user-tag text-dark"></i>Already Paid By</label>
                                                            <h6>Emp Code: {{ $quotation->alreadyPaidBy }}</h6>
                                                        </div>
                                                    </div>  

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label><i class="fas fa-file-upload text-danger"></i>Uploaded Bill</label><br>
                                                            @if ($quotation->quotationFile != '')
                                                                <a href="/storeAdmin/quotations/{{ $quotation->quotationFile }}" target="_blank" class="btn btn-danger btn-sm">
                                                                    <i class="fa fa-download"></i> Bill Copy
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>                          
                                                </div>  

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label><i class="fas fa-clipboard-list text-secondary"></i>Quotation For</label>
                                                            <h6>{{ $quotation->quotationFor }}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                    
                                                <hr>
                                                <h5 style="color:yellow;background-color:#524b4b85;"><center>Product List</center></h5>
                                                <div class="table-responsive mb-3 mt-4">
                                                    <table id="datatable" data-page-length='25' class="table table-bordered data-table table-striped" style="font-size:12px;">
                                                        <thead class="bg-white text-uppercase">
                                                            @if($quotation->fuelType == 1)
                                                                <tr class="ligth ligth-data">
                                                                    <th style="padding: 0px 4px !important;font-size:13px;" width="3%" class="text-center">No</th>
                                                                    <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Vehicle No </th>
                                                                    <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Start Reading</th>
                                                                    <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">New Reading</th>
                                                                    <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">KM</th>
                                                                    <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Average KM</th></th>
                                                                    <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">No of Ltr.</th>
                                                                    <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Fuel Rate</th>
                                                                    <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center" width="12%">Amount<?php $k=1; ?></th>
                                                                </tr>
                                                            @else
                                                                <tr class="ligth ligth-data">
                                                                    <th  style="padding: 0px 17px !important;" width="5%"><b>No.</b></th>
                                                                    <th  style="padding: 0px 17px !important;"><b>Product</b></th>
                                                                    <th  style="padding: 0px 17px !important;" width="8%"><b>Quantity</b></th>
                                                                    <th  style="padding: 0px 17px !important;" width="8%"><b>Date</b></th>
                                                                    <th  style="padding: 0px 17px !important;" width="8%"><b>Unit Price</b></th>
                                                                    <th  style="padding: 0px 17px !important;" width="8%"><b>Discount</b></th>
                                                                    <th  style="padding: 0px 17px !important;" width="8%"><b>CGST</b></th>
                                                                    <th  style="padding: 0px 17px !important;" width="8%"><b>SGST</b></th>
                                                                    <th  style="padding: 0px 17px !important;" width="8%"><b>Amount<?php $i=1; ?></b></th>
                                                                </tr>
                                                            @endif
                                                        </thead>
                                                        @if($quotation->fuelType == 0)
                                                            <tbody>
                                                                <?php $products = $util->getQuotProdList($quotation->id); ?>
                                                                @if(count($products))
                                                                    @foreach($products as $product)
                                                                        <tr  class="success">
                                                                            <td  style="padding: 0px 17px !important;">{{$i+1}}</td>
                                                                            <td  style="padding: 0px 17px !important;text-align:left;">{{$product->productCode}} - <b style="color:blue;">{{$product->name}}</b><br>
                                                                            <b style="color:red;">{{$product->remark}}</b></td>
                                                                            <td  style="padding: 0px 17px !important;">{{$product->qty}}</td>
                                                                            <td  style="padding: 0px 17px !important;">{{date('d-m-Y', strtotime($product->forDate))}}</td>
                                                                            <td  style="padding: 0px 17px !important;">{{$util->numberFormat($product->unitPrice)}}</td>
                                                                            <td  style="padding: 0px 17px !important;">{{$util->numberFormat($product->discount)}}</td>
                                                                            <td  style="padding: 0px 17px !important;">{{$product->cgst}}</td>
                                                                            <td  style="padding: 0px 17px !important;">{{$product->sgst}}</td>
                                                                            <td  style="padding: 0px 17px !important;">{{$util->numberFormat($product->amount)}}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                        <tr>
                                                                            <td colspan="8"   style="padding: 0px 17px !important;" class="text-right"><b>Total Rs.</b></td>
                                                                            <td style="padding: 0px 17px !important;">{{$quotation->totalRs}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="8"   style="padding: 0px 17px !important;" class="text-right"><b>Transportation Rs.</b></td>
                                                                            <td style="padding: 0px 17px !important;">{{$quotation->transportationRs}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="8"   style="padding: 0px 17px !important;" class="text-right"><b>Loading Rs.</b></td>
                                                                            <td style="padding: 0px 17px !important;">{{$quotation->loadingRs}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="8"   style="padding: 0px 17px !important;" class="text-right"><b>Unloading Rs.</b></td>
                                                                            <td style="padding: 0px 17px !important;">{{$quotation->unloadingRs}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="8"   style="padding: 0px 17px !important;" class="text-right"><b>Final Rs.</b></td>
                                                                            <td style="padding: 0px 17px !important;">{{$quotation->finalRs}}</td>
                                                                            <input type="hidden" value="{{$quotation->finalRs}}" id="finalRs{{$xi}}">
                                                                        </tr>
                                                                @endif
                                                            </tbody>
                                                        @else
                                                            <tbody class="ligth-body">
                                                                @php $totAmount=$k=0; @endphp
                                                                <?php $vehicles = $util->getFuelEntryList($quotation->fuelEntryId); ?>
                                                                @foreach($vehicles as $vehicle)
                                                                    <tr>
                                                                        <td style="padding: 0px 4px !important;font-size:12px;">{{$k++}}</td>
                                                                        <td style="padding: 0px 4px !important;font-size:12px;" class="text-center">{{$vehicle->busNo}}</td>
                                                                        <td style="padding: 0px 4px !important;font-size:12px;" class="text-center">{{$vehicle->oldKM}}</td>
                                                                        <td style="padding: 0px 4px !important;font-size:12px;" class="text-center">{{$vehicle->newKM}}</td>
                                                                        <td style="padding: 0px 4px !important;font-size:12px;" class="text-center">{{$vehicle->newKM - $vehicle->oldKM}}</td>
                                                                        <td style="padding: 0px 4px !important;font-size:12px;" class="text-center">{{($vehicle->newKM - $vehicle->oldKM != 0)?$util->numberFormat(($vehicle->newKM - $vehicle->oldKM)/$vehicle->ltr):0}}</td>
                                                                        <td style="padding: 0px 4px !important;font-size:12px;" class="text-center">{{$vehicle->ltr}}</td>
                                                                        <td style="padding: 0px 4px !important;font-size:12px;" class="text-center">{{$vehicle->fuelRate}}</td>
                                                                        <td style="padding: 0px 4px !important;font-size:12px;" class="text-center">{{$vehicle->amount}}</td>
                                                                        @php $totAmount = $totAmount + $vehicle->amount; @endphp
                                                                    </tr>
                                                                @endforeach
                                                                <tr>
                                                                    <td  width="80%" colspan="8" style="font-size:12px;color:black;" class="text-right"><b>Final Rs.</b></td>
                                                                    <td style="padding: 0px 4px !important;font-size:12px;"><input type="text" placeholder="0" onkeypress="return isNumberKey(event)" style="height: 29px;font-size:12px;text-align:right;color:black;" value="{{$totAmount}}" name="finalRs" class="form-control" id="finalRs1" readonly></td>
                                                                </tr>
                                                            </tbody>
                                                        @endif
                                                    </table>
                                                </div>
                                            </div>
                                            @if($userType == '501')
                                                <div class="form-check">
                                                    <input type="radio" class="form-check-input" id="qutRadio{{$xi}}" name="optradio" value="{{$quotation->id}}" {{($quotation->quotStatus == 'Approved')?'checked':''}}><h5>Approve {{($quotation->alreadyPaid == 1)?'[Payment Already Paid]':''}}</h5>
                                                    <label class="form-check-label" for="radio{{$quotation->id}}"></label>
                                                </div>
                                            @else
                                                    <h4 style="color:red;">Status : {{$quotation->quotStatus}}</h4>
                                            @endif
                                            <?php 
                                                if($quotation->quotStatus == 'Approved')
                                                {
                                                    $flag = $quotation->id;
                                                    $util=new Utility(); 
                                                }    
                                            ?>
                                        </div>
                                    </div>
                                    <?php $xi++;?>
                                @endforeach
                               
                                @if($userType == '501' && $quotation->alreadyPaid == '0')
                                    <div class="card mt-3">
                                        <div class="card-header" style="background-color:purple;text-align:center;">
                                            <h4 style="color:white;">Payment Mode (Rs. <b id="finalAmountPayment"></b>)</h4>
                                        </div>
                                        <div class="card-body">
                                            @if($flag != 0)
                                                <?php $payments = $util->getQuotationPayment($flag); $xi=1; ?>
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



