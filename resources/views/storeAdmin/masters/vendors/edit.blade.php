<?php
$userType = Auth::user()->userType;

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
                            <div  class="col-lg-5"><b style="color:red;">Edit Vendor</b></div>
                            <div  class="col-lg-7">
                                <a href="/vendor/create" class="btn mb-1 btn-primary btn-lg">Add</a>
                                <a href="/vendor/dlist" class="btn mb-1 btn-primary btn-lg">
                                    Deactive List <span class="badge badge-danger ml-2">{{$dVendors}}</span>
                                </a>
                                <a href="/vendor" class="btn mb-1 btn-primary btn-lg">
                                    Active List <span class="badge badge-danger ml-2">{{$vendors}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => ['storeController\VendorsController@update', $vendor->id], 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="row">    
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Vendor Name *</label>
                                    <input type="text" class="form-control" value="{{$vendor->name}}" name="name" placeholder="Enter Vendor Name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">    
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address *</label>
                                    <input type="text" class="form-control"value="{{$vendor->address}}"  name="address" placeholder="Enter Address" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">  
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Category</label>
                                    {{Form::select('categoryId', $categories, $vendor->categoryId, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'vCategoryId'])}}
                                </div>
                            </div> 
                            <div class="col-md-3 otherCategory">
                                <div class="form-group">
                                    <label>Other Category</label>
                                    <input type="text" class="form-control" value="{{$vendor->categoryName}}" name="otherCategory" placeholder="Enter Other Category">
                                </div>
                            </div>  
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Landline No</label>
                                    <input type="text" class="form-control" value="{{$vendor->landlineNo}}"  name="landlineNo" placeholder="Enter Landline No" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Whatsapp No</label>
                                    <input type="text" class="form-control" value="{{$vendor->whatsappNo}}"  name="whatsappNo" placeholder="Enter Whatsapp No" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>PAN NO</label>
                                    <input type="text" class="form-control" value="{{$vendor->PANNO}}"  name="PANNO" placeholder="Enter PAN NO"  maxlength="10" oninput="restrictInput(this)" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>GSTNo</label>
                                    <input type="text" class="form-control" value="{{$vendor->GSTNo}}"  name="GSTNo" placeholder="Enter GSTNo" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Contact Person 1</label>
                                    <input type="text" class="form-control" value="{{$vendor->contactPerson1}}"  name="contactPerson1" placeholder="Enter Contact Person 1" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Contact Person Number 1</label>
                                    <input type="text" class="form-control" name="contactPerNo1" value="{{$vendor->contactPerNo1}}" placeholder="Enter Contact Person Number 1" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Contact Person 2</label>
                                    <input type="text" class="form-control" value="{{$vendor->contactPerson2}}"  name="contactPerson2" placeholder="Enter Contact Person 2">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Contact Person Number 2</label>
                                    <input type="text" class="form-control" value="{{$vendor->contactPerNo1}}"  name="contactPerNo2" placeholder="Enter Contact Person Number 2">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>email Id</label>
                                    <input type="email" class="form-control" value="{{$vendor->emailId}}"  name="emailId" placeholder="Enter email Id">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Account Number</label>
                                    <input type="text" class="form-control" value="{{$vendor->accountNo}}"  name="accountNo" placeholder="Enter Account Number" {{($userType == '61')?'required':'disabled'}}>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>IFSCCode</label>
                                    <input type="text" class="form-control" value="{{$vendor->IFSCCode}}"  name="IFSCCode" placeholder="Enter IFSCCode" {{($userType == '61')?'required':'disabled'}}>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Bank Branch</label>
                                    <input type="text" class="form-control" value="{{$vendor->bankBranch}}"  name="bankBranch" placeholder="Enter Bank Branch" {{($userType == '61')?'':'disabled'}}>
                                </div>
                            </div>
                        </div>
                        <div class="row">    
                            <div class="col-md-12">   
                                <div class="form-group">
                                    <label>Material Provides <b style="color:red;">*{ Max. 2000 Character }</b></label>
                                    <textarea class="form-control" maxlength="2000" name="materialProvider" required>{{$vendor->materialProvider}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Card / Vendor Image</label>
                                    <input type="file" class="form-control" name="image">
                                </div>
                            </div>    
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Outstanding Rs</label>
                                    <input type="text" class="form-control" value="{{$vendor->outstandingRs}}"  name="outstandingRs" placeholder="Enter Outstanding Rs" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Rating</label>
                                    {{Form::select('rating', ['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'], $vendor->rating, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'rating'])}}
                                </div>
                            </div>
                        </div>      
                        {{Form::hidden('_method', 'PUT')}}                     
                        <button type="submit" class="btn btn-success mr-2">Update Category</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

<script>
        function restrictInput(input) {
            input.value = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        }

        function validatePAN() {
            let pan = document.getElementById("pan").value.toUpperCase();
            let panPattern = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
            let message = document.getElementById("message");

            if (panPattern.test(pan)) {
                message.style.color = "green";
                message.textContent = "Valid PAN Number!";
                return true;
            } else {
                message.style.color = "red";
                message.textContent = "Invalid PAN! Format: ABCDE1234F";
                return false;
            }
        }
    </script>
