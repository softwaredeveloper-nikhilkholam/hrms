@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Asset</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/assets" class="btn btn-primary mr-3">Active List</a>
                            <a href="/assets/dlist" class="btn btn-primary mr-3">Deactive List</a>
                            <a href="#" class="btn btn-success mr-3">Add Asset</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Page header-->
            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">Add Asset</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\masters\AssetsController@store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Asset Category<span class="text-red">*</span> :</label>
                                            {{Form::select('assetCategory', ['1'=>'Laptop & Charger','5'=>'Desktop PC', '2'=>'Mobile & Charger', '3'=>'Sim Card', '4'=>'Pendrive', '6'=>'Hard Disk'], NULL, ['placeholder'=>'Select Category','class'=>'assetCategory form-control', 'id'=>'assetCategory'])}}
                                        </div>
                                    </div>                                    
                                </div>

                                <div id="assetCategory1">
                                    <div class="row"> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">MAC Id<span class="text-red">*</span> :</label>
                                                <input type="text" class="form-control" name="MACId1" placeholder="MAC Id" >
                                            </div>
                                        </div>   
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Motherboard Id<span class="text-red">*</span> :</label>
                                                <input type="text" class="form-control" id="motherboard" name="motherboard1" placeholder="Motherboard Id">
                                            </div>
                                        </div>    
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Serial Number<span class="text-red">*</span> :</label>
                                                <input type="text" class="form-control" id="serialNo" name="serialNo1" placeholder="Serial Number">
                                            </div>
                                        </div> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Make<span class="text-red">*</span> :</label>
                                                <input type="text" class="form-control" id="make" name="make1" placeholder="Serial Number">
                                            </div>
                                        </div> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Laptop Charger<span class="text-red">*</span> :</label>
                                                {{Form::select('lapCharger1', ['Yes'=>'Yes', 'No'=>'No'], NULL, ['placeholder'=>'Select Option','class'=>' form-control', 'id'=>'lapCharger1'])}}
                                            </div>
                                        </div>     
                                    </div>  
                                    
                                    <hr>
                                    <h5 style="color:blue;">Login Details</h5>
                                    <div class="row"> 
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Username<span class="text-red"></span> :</label>
                                                <input type="text" class="form-control" placeholder="Username" name="username1">
                                            </div>
                                        </div>  
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Password<span class="text-red"></span> :</label>
                                                <input type="text" class="form-control" name="password1" placeholder="Password">
                                            </div>
                                        </div>  
                                    </div>  
                                    <div class="row">    
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Remarks :</label>
                                                <textarea class="form-control" name="remarks1" placeholder="Remarks"></textarea>
                                            </div>
                                        </div>  
                                    </div> 
                                </div>

                                <div id="assetCategory2">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Mobile Company Name<span class="text-red">*</span> :</label>
                                                <input type="text" class="form-control" name="companyName2" placeholder="Mobile Company Name" >
                                            </div>
                                        </div> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Model No<span class="text-red">*</span> :</label>
                                                <input type="text" class="form-control" name="modelNumber2" placeholder="Model No" >
                                            </div>
                                        </div> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">IMEI 1 <span class="text-red">*</span> :</label>
                                                <input type="text" class="form-control" name="IMEI12" placeholder="IMEI 1" >
                                            </div>
                                        </div> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">IMEI 2 <span class="text-red">*</span> :</label>
                                                <input type="text" class="form-control" name="IMEI22" placeholder="IMEI 2" >
                                            </div>
                                        </div> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Mobile Charger<span class="text-red">*</span> :</label>
                                                {{Form::select('mobileCharger2', ['Yes'=>'Yes', 'No'=>'No'], NULL, ['placeholder'=>'Select Option','class'=>' form-control', 'id'=>'mobileCharger'])}}
                                            </div>
                                        </div>    
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">OS Type<span class="text-red">*</span> :</label>
                                                {{Form::select('OSType2', ['JIO PHONE'=>'JIO PHONE', 'ANDROID'=>'ANDROID'], NULL, ['placeholder'=>'Select Option','class'=>' form-control', 'id'=>'OSType'])}}
                                            </div>
                                        </div>  
                                    </div>  
                                    <div class="row">    
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Remarks :</label>
                                                <textarea class="form-control" name="remarks1" placeholder="Remarks"></textarea>
                                            </div>
                                        </div>  
                                    </div> 
                                </div>
                                
                                <div id="assetCategory3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Phone No<span class="text-red">*</span> :</label>
                                                <input type="text" class="form-control" name="mobNumber3" placeholder="Phone No" >
                                            </div>
                                        </div> 
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Operator / Company Name<span class="text-red">*</span> :</label>
                                                <input type="text" class="form-control" name="operatComName3" placeholder="Company Name" >
                                            </div>
                                        </div> 
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Extra Material<span class="text-red">*</span> :</label>
                                                {{Form::select('extraMat3', ['Yes'=>'Yes', 'No'=>'No'], NULL, ['placeholder'=>'Select Option','class'=>' form-control', 'id'=>'extraMat'])}}
                                            </div>
                                        </div> 
                                    </div>  
                                </div>

                                <div id="assetCategory4">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Company Name<span class="text-red">*</span> :</label>
                                                <input type="text" class="form-control" name="operatComName4" placeholder="Company Name">
                                            </div>
                                        </div> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Storage Size<span class="text-red">*</span> :</label>
                                                <input type="text" class="form-control" name="storeageSize4" placeholder="Storage Size">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">MODEL<span class="text-red">*</span> :</label>
                                                {{Form::select('modelType4', ['OTG'=>'OTG', 'Basic'=>'Basic'], NULL, ['placeholder'=>'Select Option','class'=>' form-control', 'id'=>'modelType'])}}
                                            </div>
                                        </div> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Extra Material<span class="text-red">*</span> :</label>
                                                {{Form::select('extraMat4', ['Yes'=>'Yes', 'No'=>'No'], NULL, ['placeholder'=>'Select Option','class'=>' form-control', 'id'=>'extraMat'])}}
                                            </div>
                                        </div>  
                                    </div>  
                                </div>

                                <div id="assetCategory5">
                                    <div class="row"> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">MAC Id<span class="text-red">*</span> :</label>
                                                <input type="text" class="form-control" name="MACId5" placeholder="MAC Id" >
                                            </div>
                                        </div>   
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Motherboard Id<span class="text-red">*</span> :</label>
                                                <input type="text" class="form-control" id="motherboard5" name="motherboard5" placeholder="Motherboard Id">
                                            </div>
                                        </div>    
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Serial Number<span class="text-red">*</span> :</label>
                                                <input type="text" class="form-control" id="serialNo5" name="serialNo5" placeholder="Serial Number">
                                            </div>
                                        </div> 
                                    </div>  
                                    <hr>
                                    <h5 style="color:blue;">Login Details</h5>
                                    <div class="row"> 
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Username<span class="text-red"></span> :</label>
                                                <input type="text" class="form-control" placeholder="Username" name="username5">
                                            </div>
                                        </div>  
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Password<span class="text-red"></span> :</label>
                                                <input type="text" class="form-control" name="password5" placeholder="Password">
                                            </div>
                                        </div>  
                                    </div>  
                                    <div class="row">    
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Remarks :</label>
                                                <textarea class="form-control" name="remarks5" placeholder="Remarks"></textarea>
                                            </div>
                                        </div>  
                                    </div> 
                                    <hr>
                                </div>

                                <div id="assetCategory6">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Company Name<span class="text-red">*</span> :</label>
                                                <input type="text" class="form-control" name="operatComName6" placeholder="Company Name">
                                            </div>
                                        </div> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Storage Size<span class="text-red">*</span> :</label>
                                                <input type="text" class="form-control" name="storeageSize6" placeholder="Storage Size">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Hard Disk Type<span class="text-red">*</span> :</label>
                                                {{Form::select('modelType6', ['External'=>'External', 'Internal'=>'Internal'], NULL, ['placeholder'=>'Select Option','class'=>' form-control', 'id'=>'hardType'])}}
                                            </div>
                                        </div> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Extra Material<span class="text-red">*</span> :</label>
                                                {{Form::select('extraMat6', ['Yes'=>'Yes', 'No'=>'No'], NULL, ['placeholder'=>'Select Option','class'=>' form-control', 'id'=>'extraMat'])}}
                                            </div>
                                        </div>  
                                    </div>  
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5"></div>
                                        <div class="col-md-12 col-lg-3">
                                            <button type="submit" class="btn btn-primary btn-lg">Save</button>
                                            <a href="/assets" class="btn btn-danger btn-lg">Cancel</a>
                                        </div>
                                        <div class="col-md-12 col-lg-4"></div>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
