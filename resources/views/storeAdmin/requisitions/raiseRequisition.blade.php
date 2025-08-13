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
                    <div  class="col-lg-4"><b style="color:red;">Requisition List</b></div>
                    <div  class="col-lg-8 text-right">
                        <a href="/requisitions/create" class="btn mb-1 btn-success">Raise Requisition</a>
                        <a href="/requisitions" class="btn mb-1 btn-primary">Pending List</a>     
                        <a href="/requisitions/completedReqList" class="btn mb-1 btn-primary">Completed List</a>   
                        <a href="/requisitions/oldEventReqList" class="btn mb-1 btn-primary">Old Event List</a>                          
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            {!! Form::open(['action' => 'storeController\RequisitionsController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                <div class="row">  
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Branch Name<span style="color:red;">*</span></label>
                            @if($user->userType == '801')
                                {{Form::select('branchIdd', $branches, $user->reqBranchId, ['class'=>'form-control', 'placeholder'=>'Select a Branch', 'disabled', 'id'=>'branchIdd'])}}
                                <input type="hidden" value="{{$user->reqBranchId}}" name="branchId">
                            @else
                                {{Form::select('branchId', $branches, $user->branchName, ['class'=>'form-control', 'placeholder'=>'Select a Branch', 'required', 'id'=>'branchId'])}}
                            @endif
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
                            <input type="text" class="form-control" value="{{($user->userType == '801')?$user->name:''}}" name="requisitionerName" placeholder="Enter Requisition Date" {{($user->userType == '801')?'readonly':'required'}}>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>  
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Department<span style="color:red;">*</span></label>
                            @if($user->userType == '801')
                                {{Form::select('departmentIdd', $departments, $user->reqDepartmentId, ['class'=>'form-control', 'placeholder'=>'Select a Department', 'required', 'id'=>'departmentIdd', 'disabled'])}}
                                <input type="hidden" value="{{$user->reqDepartmentId}}" name="departmentId">
                            @else    
                                {{Form::select('departmentId', $departments, null, ['class'=>'form-control', 'placeholder'=>'Select a Department', 'required', 'id'=>'departmentId', 'required'])}}
                            @endif
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>  
                    <div class="col-md-2">                      
                        <div class="form-group">
                            <label>Date of Requirement<span style="color:red;">*</span></label>
                            <input type="date" class="form-control" name="dateOfRequirement" placeholder="Enter Requisition Date" required>
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
                            <label>Event Details / Requisition For<b style="color:red;">*{ Max. 2000 Character }</b></label>
                            <textarea class="form-control" maxlength="2000" style="height: 96px !important;" name="requisitionFor" required></textarea>
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
                <hr>
                <div class="row">      
                    <div class="col-md-2">
                        <label style="font-size:12px !important;"><b>Select Category<span style="color:red;">*</span></b></label>
                        {{Form::select('reqCategoryId', $categories, null, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Category','class'=>'form-control', 'id'=>'reqCategoryId', ''])}}
                    </div> 
                    <div class="col-md-2">
                        <div style="margin-bottom: 0rem;" class="form-group">
                            <label style="font-size:12px !important;"><b>Select Sub-Category<span style="color:red;">*</span></b></label>
                            {{Form::select('reqSubCategoryId', [], null, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Sub-Category','class'=>'form-control', 'id'=>'reqSubCategoryId', ''])}}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div style="margin-bottom: 0rem;" class="form-group">
                            <label style="font-size:12px !important;">Select Product<span style="color:red;">*</span></b></label>
                            {{Form::select('reqProductId', [], null, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Product','class'=>'form-control', 'id'=>'reqProductId', ''])}}
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div style="margin-bottom: 0rem;" class="form-group mt-4">
                            <label style="font-size:12px !important;"></label>
                            <button type="button" id="addRequisitionRow" class="btn btn-danger" style="font-size:15px !important;">+&nbsp;&nbsp;Add</button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <img src="/storeAdmin/images/basket.jpg" id="showProductImage" width="150px" class="responsive" height="150px">
                    </div>
                </div>                        
                <hr>
                <div class="row raiseTheRequestion">  
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered mb-0" id="myTable1" width="100%">
                            <thead class="bg-white text-uppercase">
                                <tr class="ligth ligth-data">
                                    <th style="padding: 0px 4px !important;font-size:13px;" width="3%" class="text-center">No</th>
                                    <th  style="padding: 0px 4px !important;font-size:13px;" class="text-center">Product Details</th>
                                    <th  style="padding: 0px 4px !important;font-size:13px;" width="8%"  class="text-center">Stock</th>
                                    <th  style="padding: 0px 4px !important;font-size:13px;" width="10%" class="text-center">Qty</th>                                               
                                    <th  style="padding: 0px 4px !important;font-size:13px;" width="3%" class="text-center">Action<?php $i=1; ?></th>
                                </tr>
                            </thead>
                            <tbody class="ligth-body">
                                
                            </tbody>
                        </table>
                    </div>                      
                </div>

                <input type="hidden" value="{{$flag}}" name="flag">
                <button type="submit" class="btn btn-success mr-2 RaiseRequest">Raise Request</button>
                <button type="reset" class="btn btn-danger RaiseReset ">Reset</button>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
