@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-5"><b style="color:red;">Add Vendor</b></div>
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
                    {!! Form::open(['action' => 'storeController\VendorsController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="row">    
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Vendor Name *</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter Vendor Name" required>
                                </div>
                            </div> 
                       
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Address *</label>
                                    <input type="text" class="form-control" name="address" placeholder="Enter Address" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">   
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Category</label>
                                    {{Form::select('categoryId', $categories, null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'vCategoryId'])}}
                                </div>
                            </div> 
                            <div class="col-md-3 otherCategory">
                                <div class="form-group">
                                    <label>Other Category</label>
                                    <input type="text" class="form-control" name="otherCategory" placeholder="Enter Other Category">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Landline No</label>
                                    <input type="text" class="form-control" name="landlineNo" placeholder="Enter Landline No" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Whatsapp No</label>
                                    <input type="text" class="form-control" name="whatsappNo" placeholder="Enter Whatsapp No" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>PAN NO</label>
                                    <input type="text" class="form-control" name="PANNO" id="PANNo" style="text-transform:uppercase" placeholder="Enter PAN NO" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>GSTNo</label>
                                    <input type="text" class="form-control" name="GSTNo" placeholder="Enter GSTNo" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Contact Person 1</label>
                                    <input type="text" class="form-control" name="contactPerson1" placeholder="Enter Contact Person 1" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Contact Person Number 1</label>
                                    <input type="text" class="form-control" name="contactPerNo1" placeholder="Enter Contact Person Number 1" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Contact Person 2</label>
                                    <input type="text" class="form-control" name="contactPerson2" placeholder="Enter Contact Person 2">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Contact Person Number 2</label>
                                    <input type="text" class="form-control" name="contactPerNo2" placeholder="Enter Contact Person Number 2">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>email Id</label>
                                    <input type="email" class="form-control" name="emailId" placeholder="Enter email Id">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Account Number</label>
                                    <input type="text" class="form-control" name="accountNo" placeholder="Enter Account Number">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>IFSCCode</label>
                                    <input type="text" class="form-control" name="IFSCCode" placeholder="Enter IFSCCode">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Bank Branch</label>
                                    <input type="text" class="form-control" name="bankBranch" placeholder="Enter Bank Branch">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Card / Vendor Image</label>
                                    <input type="file" class="form-control" name="image">
                                </div>
                            </div>    
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Outstanding Rs</label>
                                    <input type="text" class="form-control" name="outstandingRs" placeholder="Enter Outstanding Rs" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Rating</label>
                                    {{Form::select('rating', ['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'], null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'rating'])}}
                                </div>
                            </div>
                        </div>
                        <div class="row">    
                            <div class="col-md-12">   
                                <div class="form-group">
                                    <label>Material Provides <b style="color:red;">*{ Max. 2000 Character }</b></label>
                                    <textarea class="form-control" cols="50" rows="10" maxlength="2000" name="materialProvider" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                           
                        </div>
                        <button type="submit" class="btn btn-success mr-2">Save</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
