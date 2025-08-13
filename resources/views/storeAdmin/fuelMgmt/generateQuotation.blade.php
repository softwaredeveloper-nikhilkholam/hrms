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
                            <div  class="col-lg-7"><b style="color:red;">Generate Fuel Quotation</b></div>
                            <div  class="col-lg-5 text-right">
                                <a href="/fuelSystems/create" class="btn mb-1 btn-success">Add</a>
                                <a href="/fuelSystems/dlist" class="btn mb-1 btn-primary">Deactive List</a>
                                <a href="/fuelSystems" class="btn mb-1 btn-primary">Active List</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\FuelFilledSystemsController@storeQuotation', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="row">  
                            <div class="col-md-4">
                                <div style="margin-bottom: 0rem;" class="form-group">
                                    <label style="font-size:12px;"><b>Name & Address of Company</b></label>
                                    <textarea class="form-control" style="font-size:12px;height: 112px !important;" placeholder="Name & Address of Company" disabled>{{$fuelEntry->vendorName}}&#10;Address: {{$fuelEntry->vendorAddress}}&#10;Material Provider: {{$fuelEntry->materialProvider}}</textarea>
                                    <input type="hidden" value="{{$fuelEntry->vendorId}}" name="vendorId">
                                </div>
                            </div>    
                            <div class="col-md-3">
                                <div style="margin-bottom: 0rem;" class="form-group">
                                    <label style="font-size:12px;"><b>Bank Details</b></label>
                                    <textarea class="form-control" rows="3" style="font-size:12px;height: 112px !important;" placeholder="Bank Details" disabled>A/C No : {{$fuelEntry->accountNo}}&#10;IFSC Code : {{$fuelEntry->IFSCCode}}&#10;Bank Branch : {{$fuelEntry->bankBranch}}</textarea>
                                </div>
                            </div>   
                            <div class="col-md-3">
                                <div style="margin-bottom: 0rem;" class="form-group">
                                    <label style="font-size:12px;"><b>Address</b></label>
                                    <textarea class="form-control" style="font-size:12px;height: 112px !important;" name="address" placeholder="Address" readonly>Above hotel Shree Kateel, next to Amit Bloofield, near to Navle Bridge, Narhe, Pune, Maharashtra 411046</textarea>
                                </div>
                            </div> 
                            <div class="col-md-2">
                                <div style="margin-bottom: 0rem;" class="form-group">
                                    <label style="font-size:12px;"><b>Date</b></label>
                                    <input type="date" value="{{date('Y-m-d')}}" name="validDate" style="font-size:12px;" placeholder="Date" class="form-control" readonly>
                                </div>
                            </div>        
                            <div class="col-md-4">
                                <div style="margin-bottom: 0rem;" class="form-group">
                                    <label style="font-size:12px;"><b>Shipping Address</b></label>
                                    {{Form::select('shipping', $branches, $fuelEntry->branchId, ['class'=>'form-control', 'placeholder'=>'Select a Branch', 'required', 'id'=>'', 'style'=>'font-weight: bold;font-size:18px;', 'disabled'])}}
                                    <input type="hidden"   value="{{$fuelEntry->branchId}}" name="shippingAddress"  required>
                                </div>
                            </div> 
                            <div class="col-md-4">
                                <div style="margin-bottom: 0rem;" class="form-group">
                                    <label style="font-size:12px;"><b>Type of Company</b></label>
                                    {{Form::select('typeOfCompany', $typeOfCompanies,  NULL, ['class'=>'form-control', 'placeholder'=>'Select Option', 'required', 'id'=>'typeOfCompany', 'style'=>'font-weight: bold;font-size:18px;'])}}
                                </div>
                            </div>                                        
                                    
                            <div class="col-md-4">
                                <div style="margin-bottom: 0rem;" class="form-group">
                                    <label style="font-size:12px;"><b>Advance payment (%)</b></label>
                                    <input type="text" style="font-size:12px;" maxlength="100" placeholder="Terms of payment" value="" onkeypress="return isNumberKey(event)" name="termOfPayment" class="form-control" required>
                                </div>
                            </div>                                 
                            <div class="col-md-4">
                                <div style="margin-bottom: 0rem;" class="form-group">
                                    <label style="font-size:12px;"><b>Tentative delivery date</b></label>
                                    <input type="date" value="{{date('Y-m-d')}}" style="font-size:12px;" value="" name="tentativeDeliveryDate" placeholder="Tentative delivery date" class="form-control" required>
                                </div>
                            </div>   
                            <div class="col-md-4">
                                <div style="margin-bottom: 0rem;" class="form-group">
                                    <label style="font-size:12px;"><b>Already Paid</b></label>
                                    {{Form::select('alreadyPaid', ['1'=>'Yes','0'=>'No'], NULL, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'alreadyPaid'])}}
                                </div>
                            </div>   
                            <div class="col-md-4">
                                <div style="margin-bottom: 0rem;" class="form-group">
                                    <label style="font-size:12px;"><b>Already Paid By(Employee Code)</b></label>
                                    <input type="text" value="" style="font-size:12px;" name="alreadyPaidBy" placeholder="Already Paid By(Employee Code)" class="form-control">
                                </div>
                            </div>        
                            <div class="col-md-8">
                                <div style="margin-bottom: 0rem;" class="form-group">
                                    <label style="font-size:12px;"><b>Quotation For</b></label>
                                    <input type="text" style="font-size:12px;" placeholder="Quotation For" name="quotationFor" id="quotationFor1"  class="form-control" required>
                                </div>
                            </div>         
                            <div class="col-md-4">
                                <div style="margin-bottom: 0rem;" class="form-group">
                                    <label style="font-size:12px;"><b>Requisition No</b></label>
                                    <input type="text" style="font-size:12px;" placeholder="Requisition No" name="requisitionNo"  id="requisitionNo1" value="" class="form-control" required>
                                </div>
                            </div>  
                            <div class="col-md-4">
                                <div style="margin-bottom: 0rem;" class="form-group">
                                    <label style="font-size:12px;"><b>Upload Quotation</b></label>
                                    <input type="file" style="font-size:12px;line-height: 18px !important;" placeholder="Requisition No" name="quotationFile"  id="quotationFile" value="" class="form-control" required>
                                </div>
                            </div>                                              
                        </div>  
                        <hr>
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered mb-0" id="quotationProductTable1">
                                <thead class="bg-white text-uppercase">
                                    <tr class="ligth ligth-data">
                                        <th style="padding: 0px 4px !important;font-size:13px;" width="3%" class="text-center">No</th>
                                        <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Vehicle No </th>
                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Start Reading</th>
                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">New Reading</th>
                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">KM</th>
                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Average KM</th></th>
                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">No of Ltr.</th>
                                        <th  style="padding: 0px 4px !important;font-size:13px;"  class="text-center">Fuel Rate</th>
                                        <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Amount<?php $k=1; ?></th>
                                    </tr>
                                </thead>
                                <tbody class="ligth-body">
                                        @php $totAmount=0; @endphp
                                        @foreach($vehicleList as $vehicle)
                                            <tr>
                                                <td style="padding: 0px 4px !important;font-size:12px;">{{$k++}}</td>
                                                <td style="padding: 0px 4px !important;font-size:12px;" class="text-center">{{$vehicle->busNo}}</td>
                                                <td style="padding: 0px 4px !important;font-size:12px;" class="text-center">{{$vehicle->oldKM}}</td>
                                                <td style="padding: 0px 4px !important;font-size:12px;" class="text-center">{{$vehicle->newKM}}</td>
                                                <td style="padding: 0px 4px !important;font-size:12px;" class="text-center">{{$vehicle->newKM - $vehicle->oldKM}}</td>
                                                <td style="padding: 0px 4px !important;font-size:12px;" class="text-center">{{$util->numberFormat(($vehicle->newKM - $vehicle->oldKM)/$vehicle->ltr)}}</td>
                                                <td style="padding: 0px 4px !important;font-size:12px;" class="text-center">{{$vehicle->ltr}}</td>
                                                <td style="padding: 0px 4px !important;font-size:12px;" class="text-center">{{($fuelEntry->fuelType == 1)?$fuelEntry->dieselRate:$fuelEntry->petrolRate}}</td>
                                                <td style="padding: 0px 4px !important;font-size:12px;" class="text-center">{{$vehicle->amount}}</td>
                                                @php $totAmount = $totAmount + $vehicle->amount; @endphp
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td  width="80%" colspan="8" style="font-size:12px;color:black;" class="text-right"><b>Final Rs.</b></td>
                                            <td style="padding: 0px 4px !important;font-size:12px;"><input type="text" placeholder="0" onkeypress="return isNumberKey(event)" style="height: 29px;font-size:12px;text-align:right;color:black;" value="{{$totAmount}}" name="finalRs" class="form-control" id="finalRs1" readonly></td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row"> 
                            <div class="col-md-4"></div>                    
                            <div class="col-md-4 text-center">  
                                <input type="hidden" value="{{$fuelEntry->id}}" name="fuelEntryId">
                                <button type="submit" class="btn btn-success mr-2">Generate Quotation</button>
                                <button type="reset" class="btn btn-danger">Reset</button>
                            </div> 
                            <div class="col-md-4"></div> 
                        </div> 
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
<script>
 $(document).ready(function () {
      $('select').selectize({
          sortField: 'text'
      });
      
  });

  function validateInput(event) {
    var key = event.which || event.keyCode; // Get the keycode of the pressed key
    var value = event.target.value; // Get the current value of the input field

    // Allow only numbers (0-9), the decimal point (.), and backspace (8)
    if ((key >= 48 && key <= 57) || key === 46 || key === 8) {
      // Prevent more than one decimal point
      if (key === 46 && value.indexOf('.') !== -1) {
        return false; // Prevent entering more than one decimal point
      }
      return true; // Allow the key press
    } else {
      return false; // Prevent all other characters
    }
  }
  
    </script>
@endsection
