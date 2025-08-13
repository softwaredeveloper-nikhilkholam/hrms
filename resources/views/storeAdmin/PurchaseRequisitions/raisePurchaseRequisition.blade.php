<?php
    $user = Auth::user();
?>
@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div  class="col-lg-4"><b style="color:red;">Raise Requisition</b></div>
                    <div  class="col-lg-8 text-right">
                        <a href="/requisitions/raisePurchaseRequisition" class="btn mb-1 btn-primary">Raise Requisition</a>
                        <a href="/requisitions/purchaseRequisitionList" class="btn mb-1 btn-success">Pending List</a>                           
                        <a href="/requisitions/approvedPurchaseRequisitionList" class="btn mb-1 btn-primary">Approved List</a>                           
                        <a href="/requisitions/completedPurchaseRequisitionList" class="btn mb-1 btn-primary">Completed List</a>                           
                        <a href="/requisitions/rejectedPurchaseRequisitionList" class="btn mb-1 btn-primary">Rejected List</a>    
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            {!! Form::open(['action' => 'storeController\RequisitionsController@storePurchaseRequisition', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                <div class="row">  
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Branch Name<span style="color:red;">*</span></label>
                            {{Form::select('branchId', $branches, $user->branchName, ['class'=>'form-control', 'placeholder'=>'Select a Branch', 'required', 'id'=>'branchId'])}}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>    
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Requisition Date<span style="color:red;">*</span></label>
                            <input type="date" class="form-control" value="{{date('Y-m-d')}}" name="requisitionDate" placeholder="Enter Requisition Date" required>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>  
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Requisitioner Name<span style="color:red;">*</span></label>
                            {{Form::select('requisitionerName', $requisitioners, null, ['class'=>'form-control', 'placeholder'=>'Select a Requisitioner Name', 'required', 'id'=>'requisitionerName'])}}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>  
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Department<span style="color:red;">*</span></label>
                            {{Form::select('departmentId', $departments, null, ['class'=>'form-control', 'placeholder'=>'Select a Department', 'required', 'id'=>'departmentId', 'readonly'])}}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>  
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Date of Requirement<span style="color:red;">*</span></label>
                            <input type="date" class="form-control" name="dateOfRequirement" min="<?php echo date("Y-m-d"); ?>" placeholder="Enter Requisition Date" required>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>  
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Select Sevirity<span style="color:red;">*</span></label>
                            {{Form::select('sevirity', ['1'=>'NORMAL','2'=>'URGENT','3'=>'VERY URGENT'], null, ['class'=>'form-control', 'placeholder'=>'Select a Sevirity', 'required', 'id'=>'sevirity', 'required'])}}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>    
                </div> 
                <div class="row">    
                    <div class="col-md-8">   
                        <div class="form-group">
                            <label>Remark<b style="color:red;">*{ Max. 500 Character }</b></label>
                            <textarea class="form-control" maxlength="500" style="height: 70px !important;" name="remark" required></textarea>
                        </div>
                    </div>
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Deliver To<span style="color:red;">*</span></label>
                            {{Form::select('deliveryTo', $branches, null, ['class'=>'form-control', 'placeholder'=>'Select a Branch', 'required', 'id'=>'deliveryTo'])}}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>    
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Authority Name<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" value="" name="authorityName" placeholder="Enter Authority Name" required>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>  
                </div>
                <div class="table-responsive">
                    <table  id="childTable" data-page-length='25' class="table table-bordered table-striped" style="font-size:12px;" width="auto">
                        <thead>
                            <tr class="ligth">
                                <th style="padding: 10px 17px !important;font-size:12px;" width="5%">No</th>
                                <th style="padding: 10px 17px !important;font-size:12px;" width="30%">Product Name</th>           
                                <th style="padding: 10px 17px !important;font-size:12px;" width="15%">Qty</th>
                                <th style="padding: 10px 17px !important;font-size:12px;" width="40%">Description</th>
                                <th style="padding: 10px 17px !important;font-size:12px;" width="10%">Product Image</th>
                                <th style="padding: 10px 17px !important;font-size:12px;" width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td><input type="text" value="" name="productId[]" placeholder="PRODUCT NAME" class="form-control"></td>
                                <td><input type="text" value="" name="qty[]" placeholder="QUANTITY" class="form-control"></td>
                                <td><input type="text" value="" name="description[]" placeholder="DESCRIPTION" class="form-control"></td>
                                <td><input type="file" value="" name="fileName[]" style="line-height: 20px !important;" placeholder="DESCRIPTION" class="form-control"></td>
                                <td><input type="button" class="btn btn-block btn-danger" id="addrow" onclick="childrenRow()" value="+" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <input type="text" class="form-control" value="" name="departmentIdView" id="departmentIdView">
                <button type="submit" class="btn btn-success mr-2">Raise Request</button>
                <button type="reset" class="btn btn-danger">Reset</button>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
