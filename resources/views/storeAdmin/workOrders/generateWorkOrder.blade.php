@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div  class="col-lg-6"><b style="color:red;">WorkOrder List</b></div>
                        <div  class="col-lg-6 text-right">
                            @if(Auth::user()->userType == '701' || Auth::user()->userType == '801')
                                <a href="/workOrder/create" class="btn mb-1 btn-danger">Generate</a>
                            @endif
                            <a href="/workOrder" class="btn mb-1 btn-primary">Pending List</a>
                            <a href="/workOrder/approvedOrderList" class="btn mb-1 btn-primary">Approved List</a>
                            <a href="/workOrder/rejectedOrderList" class="btn mb-1 btn-primary">Rejected List</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {!! Form::open(['action' => 'storeController\WorkOrdersController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                    <div class="row">  
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header" style="background-color:#0080007d;text-align:center;">
                                    <h4>Work Order 1</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">  
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Vendor</b></label>
                                                {{Form::select('vendorId1', $vendors, null, ['class'=>'form-control', 'placeholder'=>'Select a Vendor', 'required','id'=>'vendorId1'])}}
                                            </div>
                                        </div>  
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Bill No</b></label>
                                                <input type="text" value="" name="billNo1" style="font-size:12px;" placeholder="Bill No" class="form-control" required>
                                            </div>
                                        </div>          
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Date</b></label>
                                                <input type="date" value="{{date('Y-m-d')}}" id="forDate1" name="forDate1" style="font-size:12px;" placeholder="Date" class="form-control" required>
                                            </div>
                                        </div>   
                                    </div>   
                                    <div class="row">                                
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Branch</b></label>
                                                {{Form::select('branchId1', $branches, null, ['class'=>'form-control', 'placeholder'=>'Select a Branch', 'required','id'=>'branchId1'])}}
                                            </div>
                                        </div> 
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Type of Company</b></label>
                                                {{Form::select('typeOfCompany1', $typeOfCompanies, null, ['class'=>'form-control', 'placeholder'=>'Select Option', 'style'=>'font-weight: bold;font-size:18px;'])}}
                                            </div>
                                        </div>        
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Location in Branch</b></label>
                                                <input type="text" style="font-size:12px;" maxlength="500" placeholder="Location in Branch" id="locationInBranch1" name="locationInBranch1" class="form-control" required>
                                            </div>
                                        </div> 
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Upload WorkOrder Copy</b></label>
                                                <input type="file" style="font-size:12px;line-height: 20px !important;" placeholder="Upload WorkOrder Copy" name="workOrderFile1"  id="workOrderFile1" value="" class="form-control" required>
                                            </div>
                                        </div>      
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Already Paid</b></label>
                                                {{Form::select('alreadyPaid1', ['1'=>'Yes','0'=>'No'], null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'alreadyPaid1'])}}
                                            </div>
                                        </div>   
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Already Paid By(Employee Code)</b></label>
                                                <input type="text" value="" style="font-size:12px;" name="alreadyPaidBy1" placeholder="Already Paid By(Employee Code)" class="form-control">
                                            </div>
                                        </div>   
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Requisition No.</b></label>
                                                <input type="text" value="" style="font-size:12px;" name="reqNo" placeholder="Requisition No." class="form-control">
                                            </div>
                                        </div>      
                                    </div>      
                                    <div class="row">    
                                        <div class="col-md-12">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Description</b></label>
                                                <textarea class="form-control" rows="2" style="font-size:12px;height: 112px !important;"  name="description1"  id="description1" placeholder="Description" required></textarea>
                                            </div>
                                        </div>                                  
                                    </div>  
                                    <hr> 
                                    <div class="table-responsive">
                                        <table  id="workOrderTable" data-page-length='25' class="table table-bordered table-striped" style="font-size:12px;" width="auto">
                                            <thead>
                                                <tr class="ligth">
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="5%">No</th>
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="42%">Particular</th>           
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="12%">Qty</th>
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="12%">Unit</th>
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="12%">Rate</th>
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="12%">Amount</th>
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="5%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th  style="padding: 10px 17px !important;font-size:12px;"scope="row">1</th>
                                                    <td style="padding: 10px 17px !important;font-size:12px;"><textarea row="5" style="height:65px !important;" value="" name="particular1[]" placeholder="Particular" class="form-control" required></textarea></td>
                                                    <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;" value="" id="qty1" name="qty1[]" placeholder="0" class="form-control qty1" required></td>
                                                    <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" value="" name="unit1[]" placeholder="Unit" class="form-control" required></td>
                                                    <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  id="rate1" value="" name="rate1[]" placeholder="Rate" class="form-control rate1" required></td>
                                                    <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" value="0" id="amount1" name="amount1[]" style="text-align:right;"  style="line-height: 20px !important;" placeholder="Amount" class="form-control amount1" readonly required></td>
                                                    <td style="padding: 10px 17px !important;font-size:12px;"><input type="button" class="btn btn-block btn-small btn-success" id="addWorkOrder" onclick="childrenRowWorkOrder()" value="+" /><input type="button" class="btn btn-block btn-small btn-danger btnDelete" id="btnDelete" value="X" /></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive">
                                        <table  id="" data-page-length='25' class="table table-bordered table-striped" style="font-size:12px;" width="auto">
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;" width="71%"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;" width="12%">LABOUR CHARGES</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;" width="12%"><input onkeypress="return isNumberKey(event)" style="text-align:right;"  type="text" value="0" id="labourCharges1" name="labourCharges1" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control labourCharges1"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;" width="5%"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">TRANSPORTATION</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="transportation1"  name="transportation1" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">SHIFTING</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="shiftingCharges1" name="shiftingCharges1" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">SUB TOTAL</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" readonly style="text-align:right;"  value="0" id="subTotal1" name="subTotal1" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">DISCOUNT</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="woDiscount1" name="woDiscount1" style="line-height: 20px !important;" placeholder="Discount" class="form-control woDiscount1"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">CGST</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="wocgst1" name="CGST1" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control wocgst1"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;" colspan="5"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">SGST</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="wosgst1" name="SGST1" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control wosgst1"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">Advance Rs.</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="woAdv1" name="woAdv1" style="line-height: 20px !important;" placeholder="Advance Amount" class="form-control woAdv1"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">GRAND TOTAL</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="grandTotal1" name="grandTotal1" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control grandTotal1"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>  
                    <div class="row">  
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header" style="background-color:#80008085;">
                                    <h4 style="text-align:center;">Work Order 2</h4>
                                    <b style="text-align:left;font-size:25px;"><button type="button" value="1" name="addNewWorkOrder2" class="btn btn-primary addNewWorkOrder2" id="addNewWorkOrder2">Add</button>
                                        <input type="hidden" value="1" id="workOrder2" name="workOrderDiv2"></b>
                                </div>
                                <div class="card-body workOrderDiv2">
                                    <div class="row">  
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Vendor</b></label>
                                                {{Form::select('vendorId2', $vendors, null, ['class'=>'form-control', 'placeholder'=>'Select a Vendor', 'id'=>'vendorId2'])}}
                                            </div>
                                        </div>  
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Bill No</b></label>
                                                <input type="text" value="" id="billNo2" name="billNo2" style="font-size:12px;" placeholder="Bill No" class="form-control">
                                            </div>
                                        </div>          
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Date</b></label>
                                                <input type="date" value="{{date('Y-m-d')}}" name="forDate2" id="forDate2" style="font-size:12px;" placeholder="Date" class="form-control">
                                            </div>
                                        </div>   
                                    </div>   
                                    <div class="row">                                
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Branch</b></label>
                                                {{Form::select('branchId2', $branches, null, ['class'=>'form-control', 'placeholder'=>'Select a Branch', 'id'=>'branchId2'])}}
                                            </div>
                                        </div>  
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Type of Company</b></label>
                                                {{Form::select('typeOfCompany2', $typeOfCompanies, null, ['class'=>'form-control', 'placeholder'=>'Select Option',  'style'=>'font-weight: bold;font-size:18px;'])}}
                                            </div>
                                        </div>         
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Location in Branch</b></label>
                                                <input type="text" style="font-size:12px;" maxlength="500" placeholder="Location in Branch" id="locationInBranch2" name="locationInBranch2" class="form-control">
                                            </div>
                                        </div> 
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Upload WorkOrder Copy</b></label>
                                                <input type="file" style="font-size:12px;line-height: 20px !important;" placeholder="Upload WorkOrder Copy" name="workOrderFile2"  id="workOrderFile2" value="" class="form-control">
                                            </div>
                                        </div>      
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Already Paid</b></label>
                                                {{Form::select('alreadyPaid2', ['1'=>'Yes','0'=>'No'], null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'id'=>'alreadyPaid'])}}
                                            </div>
                                        </div>   
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Already Paid By(Employee Code)</b></label>
                                                <input type="text" value="" style="font-size:12px;" name="alreadyPaidBy2" placeholder="Already Paid By(Employee Code)" class="form-control">
                                            </div>
                                        </div>   
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Requisition No.</b></label>
                                                <input type="text" value="" style="font-size:12px;" name="reqNo2" placeholder="Requisition No." class="form-control">
                                            </div>
                                        </div>          
                                    </div>   
                                    <div class="row">    
                                        <div class="col-md-12">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Description</b></label>
                                                <textarea class="form-control" rows="2" style="font-size:12px;height: 112px !important;"  name="description2"  id="description2" placeholder="Description"></textarea>
                                            </div>
                                        </div>                                  
                                    </div>  
                                    <hr> 
                                    <div class="table-responsive">
                                        <table  id="workOrderTable2" data-page-length='25' class="table table-bordered table-striped" style="font-size:12px;" width="auto">
                                            <thead>
                                                <tr class="ligth">
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="5%">No</th>
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="42%">Particular</th>           
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="12%">Qty</th>
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="12%">Unit</th>
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="12%">Rate</th>
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="12%">Amount</th>
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="5%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th  style="padding: 10px 17px !important;font-size:12px;"scope="row">1</th>
                                                    <td style="padding: 10px 17px !important;font-size:12px;"><textarea row="5" style="height:65px !important;" value="" name="particular2[]" placeholder="Particular" class="form-control"></textarea></td>
                                                    <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;" value="" id="qty2" name="qty2[]" placeholder="0" class="form-control qty2"></td>
                                                    <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" value="" name="unit2[]" placeholder="Unit" class="form-control"></td>
                                                    <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  id="rate2" value="" name="rate2[]" placeholder="Rate" class="form-control rate2"></td>
                                                    <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" value="0" id="amount2" name="amount2[]" style="text-align:right;"  style="line-height: 20px !important;" placeholder="Amount" class="form-control amount2" readonly></td>
                                                    <td style="padding: 10px 17px !important;font-size:12px;"><input type="button" class="btn btn-block btn-small btn-success" id="addWorkOrder" onclick="childrenRowWorkOrder2()" value="+" /><input type="button" class="btn btn-block btn-small btn-danger btnDelete2" id="btnDelete2" value="X" /></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive">
                                        <table  id="" data-page-length='25' class="table table-bordered table-striped" style="font-size:12px;" width="auto">
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;" width="71%"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;" width="12%">LABOUR CHARGES</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;" width="12%"><input onkeypress="return isNumberKey(event)" style="text-align:right;"  type="text" value="0" id="labourCharges2" name="labourCharges2" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control labourCharges2"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;" width="5%"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">TRANSPORTATION</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="transportation2"  name="transportation2" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">SHIFTING</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="shiftingCharges2" name="shiftingCharges2" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">SUB TOTAL</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" readonly style="text-align:right;"  value="0" id="subTotal2" name="subTotal2" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">DISCOUNT</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="woDiscount2" name="woDiscount2" style="line-height: 20px !important;" placeholder="Discount" class="form-control woDiscount2"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">CGST</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="wocgst2" name="CGST2" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control wocgst2"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;" colspan="5"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">SGST</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="wosgst2" name="SGST2" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control wosgst2"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">Advance Rs.</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="woAdv2" name="woAdv2" style="line-height: 20px !important;" placeholder="Advance Amount" class="form-control woAdv2"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">GRAND TOTAL</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="grandTotal2" name="grandTotal2" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control grandTotal2"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">  
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header" style="background-color:#00118085;">
                                    <h4 style="text-align:center;">Work Order 3</h4>
                                    <b style="text-align:left;font-size:25px;"><button type="button" value="1" name="addNewWorkOrder3" class="btn btn-primary addNewWorkOrder3" id="addNewWorkOrder3">Add</button>
                                    <input type="hidden" value="1" id="workOrder3" name="workOrderDiv3"></b>
                                </div>
                                <div class="card-body workOrderDiv3">
                                    <div class="row">  
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Vendor</b></label>
                                                {{Form::select('vendorId3', $vendors, null, ['class'=>'form-control', 'placeholder'=>'Select a Vendor', 'id'=>'vendorId3'])}}
                                            </div>
                                        </div>  
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Bill No</b></label>
                                                <input type="text" value="" id="billNo3" name="billNo3" style="font-size:12px;" placeholder="Bill No" class="form-control">
                                            </div>
                                        </div>          
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Date</b></label>
                                                <input type="date" value="{{date('Y-m-d')}}" name="forDate3" id="forDate3" style="font-size:12px;" placeholder="Date" class="form-control">
                                            </div>
                                        </div>   
                                    </div>   
                                    <div class="row">                                
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Branch</b></label>
                                                {{Form::select('branchId3', $branches, null, ['class'=>'form-control', 'placeholder'=>'Select a Branch', 'id'=>'branchId3'])}}
                                            </div>
                                        </div>  
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Type of Company</b></label>
                                                {{Form::select('typeOfCompany3', $typeOfCompanies, null, ['class'=>'form-control', 'placeholder'=>'Select Option', 'style'=>'font-weight: bold;font-size:18px;'])}}
                                            </div>
                                        </div>         
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Location in Branch</b></label>
                                                <input type="text" style="font-size:12px;" maxlength="500" placeholder="Location in Branch" id="locationInBranch3" name="locationInBranch3" class="form-control">
                                            </div>
                                        </div> 
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Upload WorkOrder Copy</b></label>
                                                <input type="file" style="font-size:12px;line-height: 20px !important;" placeholder="Upload WorkOrder Copy" name="workOrderFile3"  id="workOrderFile3" value="" class="form-control">
                                            </div>
                                        </div>      
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Already Paid</b></label>
                                                {{Form::select('alreadyPaid3', ['1'=>'Yes','0'=>'No'], null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'id'=>'alreadyPaid'])}}
                                            </div>
                                        </div>   
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Already Paid By(Employee Code)</b></label>
                                                <input type="text" value="" style="font-size:12px;" name="alreadyPaidBy3" placeholder="Already Paid By(Employee Code)" class="form-control">
                                            </div>
                                        </div> 
                                        <div class="col-md-4">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Requisition No.</b></label>
                                                <input type="text" value="" style="font-size:12px;" name="reqNo3" placeholder="Requisition No." class="form-control">
                                            </div>
                                        </div>            
                                    </div>   
                                    <div class="row">    
                                        <div class="col-md-12">
                                            <div style="margin-bottom: 0rem;" class="form-group">
                                                <label style="font-size:12px;"><b>Description</b></label>
                                                <textarea class="form-control" rows="2" style="font-size:12px;height: 112px !important;"  name="description3"  id="description3" placeholder="Description"></textarea>
                                            </div>
                                        </div>                                  
                                                                                
                                    </div>  
                                    <hr> 
                                    <div class="table-responsive">
                                        <table  id="workOrderTable3" data-page-length='25' class="table table-bordered table-striped" style="font-size:12px;" width="auto">
                                            <thead>
                                                <tr class="ligth">
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="5%">No</th>
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="42%">Particular</th>           
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="12%">Qty</th>
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="12%">Unit</th>
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="12%">Rate</th>
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="12%">Amount</th>
                                                    <th style="padding: 10px 17px !important;font-size:12px;" width="5%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th  style="padding: 10px 17px !important;font-size:12px;"scope="row">1</th>
                                                    <td style="padding: 10px 17px !important;font-size:12px;"><textarea row="5" style="height:65px !important;" value="" name="particular3[]" placeholder="Particular" class="form-control"></textarea></td>
                                                    <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;" value="0" id="qty3" name="qty3[]" placeholder="0" class="form-control qty3"></td>
                                                    <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" value="" name="unit3[]" placeholder="Unit" class="form-control"></td>
                                                    <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  id="rate3" value="" name="rate3[]" placeholder="Rate" class="form-control rate3"></td>
                                                    <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" value="0" id="amount3" name="amount3[]" style="text-align:right;"  style="line-height: 20px !important;" placeholder="Amount" class="form-control amount3" readonly></td>
                                                    <td style="padding: 10px 17px !important;font-size:12px;"><input type="button" class="btn btn-block btn-small btn-success" id="addWorkOrder" onclick="childrenRowWorkOrder3()" value="+" /><input type="button" class="btn btn-block btn-small btn-danger btnDelete3" id="btnDelete3" value="X" /></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive">
                                        <table  id="" data-page-length='25' class="table table-bordered table-striped" style="font-size:12px;" width="auto">
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;" width="71%"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;" width="12%">LABOUR CHARGES</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;" width="12%"><input onkeypress="return isNumberKey(event)" style="text-align:right;"  type="text" value="0" id="labourCharges3" name="labourCharges3" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control labourCharges3"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;" width="5%"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">TRANSPORTATION</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="transportation3"  name="transportation3" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">SHIFTING</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="shiftingCharges3" name="shiftingCharges3" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">SUB TOTAL</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" readonly style="text-align:right;"  value="0" id="subTotal3" name="subTotal3" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">DISCOUNT</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="woDiscount3" name="woDiscount3" style="line-height: 20px !important;" placeholder="Discount" class="form-control woDiscount3"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">CGST</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="wocgst3" name="CGST3" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control wocgst3"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;" colspan="5"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">SGST</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="wosgst3" name="SGST3" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control wosgst3"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">Advance Rs.</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="woAdv3" name="woAdv3" style="line-height: 20px !important;" placeholder="Advance Amount" class="form-control woAdv3"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                            <tr style="padding: 10px 17px !important;font-size:12px;">
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;">GRAND TOTAL</td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"><input type="text" onkeypress="return isNumberKey(event)" style="text-align:right;"  value="0" id="grandTotal3" name="grandTotal3" style="line-height: 20px !important;" placeholder="LABOUR CHARGES" class="form-control grandTotal3"></td>
                                                <td style="padding: 10px 17px !important;font-size:12px;"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">  
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success mr-2">Generate Work Order</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection