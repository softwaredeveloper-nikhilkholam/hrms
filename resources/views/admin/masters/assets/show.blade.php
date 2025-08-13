@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Asset</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/assets" class="btn btn-primary mr-3">Active List</a>
                            <a href="/assets/dlist" class="btn btn-primary mr-3">Deactive List</a>
                            <a href="/assets/create" class="btn btn-primary mr-3">Add Asset</a>
                            
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
                            <h4 class="card-title">Show Asset Details</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($asset))
                                    @if($type == 1)
                                        <div>
                                            <h5 style="color:red;">LAPTOP</h5>
                                            <hr>
                                            <div class="row"> 
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">MAC Id<span class="text-red">*</span> :</label>
                                                        <input type="text" class="form-control" value="{{$asset->MACId}}" name="MACId1" readonly placeholder="MAC Id" >
                                                    </div>
                                                </div>   
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Motherboard Id<span class="text-red">*</span> :</label>
                                                        <input type="text" class="form-control" id="motherboard" value="{{$asset->motherboard}}" name="motherboard1" readonly placeholder="Motherboard Id">
                                                    </div>
                                                </div>    
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Serial Number<span class="text-red">*</span> :</label>
                                                        <input type="text" class="form-control" id="serialNo" value="{{$asset->serialNo}}" name="serialNo1" readonly placeholder="Serial Number">
                                                    </div>
                                                </div> 
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Make<span class="text-red">*</span> :</label>
                                                        <input type="text" class="form-control" id="make" value="{{$asset->make}}" name="make1" readonly placeholder="Serial Number">
                                                    </div>
                                                </div>  
                                            </div>  
                                            <h5 style="color:blue;">Extra Material</h5>
                                            <hr>
                                            <div class="row"> 
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Laptop Bag<span class="text-red">*</span> :</label>
                                                        {{Form::select('lapBag1', ['Yes'=>'Yes', 'No'=>'No'], $asset->lapBag, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'lapBag'])}}
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Keyboard<span class="text-red">*</span> :</label>
                                                        {{Form::select('keyboard1', ['Yes'=>'Yes', 'No'=>'No'], $asset->keyboard, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'keyboard'])}}
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Mouse<span class="text-red">*</span> :</label>
                                                        {{Form::select('mouse1', ['Yes'=>'Yes', 'No'=>'No'], $asset->mouse, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'mouse'])}}
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Mouse Pad<span class="text-red">*</span> :</label>
                                                        {{Form::select('mousePad1', ['Yes'=>'Yes', 'No'=>'No'], $asset->mousePad, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'mousePad'])}}
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Antivirus<span class="text-red">*</span> :</label>
                                                        {{Form::select('anti1', ['Yes'=>'Yes', 'No'=>'No'], $asset->anti, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'anti'])}}
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Laptop Charger<span class="text-red">*</span> :</label>
                                                        {{Form::select('lapCharger1', ['Yes'=>'Yes', 'No'=>'No'], $asset->lapCharger, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'lapCharger'])}}
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Extra Material<span class="text-red">*</span> :</label>
                                                        {{Form::select('extraMaterial1', ['Yes'=>'Yes', 'No'=>'No'], $asset->extraMaterial, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'extraMaterial'])}}
                                                    </div>
                                                </div>  
                                            </div>
                                            <hr>
                                            <h5 style="color:blue;">Login Details</h5>
                                            <div class="row"> 
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Username<span class="text-red"></span> :</label>
                                                        <input type="text" class="form-control" readonly placeholder="Username" value="{{$asset->username}}" name="username1">
                                                    </div>
                                                </div>  
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Password<span class="text-red"></span> :</label>
                                                        <input type="text" class="form-control" name="password1" value="{{$asset->password}}" readonly placeholder="Password">
                                                    </div>
                                                </div>  
                                            </div>  
                                            <div class="row">    
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Remarks :</label>
                                                        <textarea class="form-control" name="remarks1" readonly placeholder="Remarks">{{$asset->remarks}}</textarea>
                                                    </div>
                                                </div>  
                                            </div> 
                                            <hr>
                                        </div>
                                    @endif
                                    @if($type == 2)
                                        <div>
                                            <h5 style="color:red;">MOBILE</h5>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Mobile Company Name<span class="text-red">*</span> :</label>
                                                        <input type="text" class="form-control" value="{{$asset->companyName}}" name="companyName2" readonly placeholder="Mobile Company Name" >
                                                    </div>
                                                </div> 
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Model No<span class="text-red">*</span> :</label>
                                                        <input type="text" class="form-control" value="{{$asset->IMEI1}}" name="IMEI12" readonly placeholder="Model No" >
                                                    </div>
                                                </div> 
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">IMEI 1 <span class="text-red">*</span> :</label>
                                                        <input type="text" class="form-control" value="{{$asset->IMEI1}}" name="IMEI12" readonly placeholder="IMEI 1" >
                                                    </div>
                                                </div> 
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">IMEI 2 <span class="text-red">*</span> :</label>
                                                        <input type="text" class="form-control" value="{{$asset->IMEI2}}" name="IMEI22" readonly placeholder="IMEI 2" >
                                                    </div>
                                                </div> 
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Mobile Charger<span class="text-red">*</span> :</label>
                                                        {{Form::select('mobileCharger2', ['Yes'=>'Yes', 'No'=>'No'], $asset->mobileCharger, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'mobileCharger'])}}
                                                    </div>
                                                </div>    
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Headphone<span class="text-red">*</span> :</label>
                                                        {{Form::select('headphone2', ['Yes'=>'Yes', 'No'=>'No'], $asset->headphone, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'headphone'])}}
                                                    </div>
                                                </div>   
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Mobile Cover<span class="text-red">*</span> :</label>
                                                        {{Form::select('mobileCover2', ['Yes'=>'Yes', 'No'=>'No'], $asset->mobileCover, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'mobileCover'])}}
                                                    </div>
                                                </div>   
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">OS Type<span class="text-red">*</span> :</label>
                                                        {{Form::select('OSType2', ['JIO PHONE'=>'JIO PHONE', 'ANDROID'=>'ANDROID'], $asset->OSType, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'OSType'])}}
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Extra Material<span class="text-red">*</span> :</label>
                                                        {{Form::select('extraMat2', ['Yes'=>'Yes', 'No'=>'No'], $asset->extraMat, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'extraMat'])}}
                                                    </div>
                                                </div>    
                                            </div>  
                                        </div>
                                    @endif

                                    @if($type == 3)
                                        <div>
                                            <h5 style="color:red;">SIM CARD</h5>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Phone No<span class="text-red">*</span> :</label>
                                                        <input type="text" class="form-control" value="{{$asset->mobNumber}}" name="mobNumber3" readonly placeholder="Phone No" >
                                                    </div>
                                                </div> 
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Operator / Company Name<span class="text-red">*</span> :</label>
                                                        <input type="text" class="form-control" value="{{$asset->operatComName}}" name="operatComName3" readonly placeholder="Company Name" >
                                                    </div>
                                                </div> 
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Extra Material<span class="text-red">*</span> :</label>
                                                        {{Form::select('extraMat3', ['Yes'=>'Yes', 'No'=>'No'],  $asset->extraMat, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'extraMat'])}}
                                                    </div>
                                                </div> 
                                            </div>  
                                        </div>
                                    @endif

                                    @if($type == 4)
                                        <div>
                                            <h5 style="color:red;">PENDRIVE</h5>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Company Name<span class="text-red">*</span> :</label>
                                                        <input type="text" class="form-control" value="{{$asset->operatComName}}" name="operatComName4" readonly placeholder="Company Name">
                                                    </div>
                                                </div> 
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Storage Size<span class="text-red">*</span> :</label>
                                                        <input type="text" class="form-control" value="{{$asset->storageSize}}" name="storageSize4" readonly placeholder="Storage Size">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">MODEL<span class="text-red">*</span> :</label>
                                                        {{Form::select('modelType4', ['OTG'=>'OTG', 'Basic'=>'Basic'], $asset->modelType, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'modelType'])}}
                                                    </div>
                                                </div> 
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Extra Material<span class="text-red">*</span> :</label>
                                                        {{Form::select('extraMat4', ['Yes'=>'Yes', 'No'=>'No'], $asset->extraMat, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'extraMat'])}}
                                                    </div>
                                                </div>  
                                            </div>  
                                        </div>
                                    @endif
                                    @if($type == 5)
                                        <div>
                                            <h5 style="color:red;">DESKTOP PC</h5>
                                            <hr>
                                            <div class="row"> 
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">MAC Id<span class="text-red">*</span> :</label>
                                                        <input type="text" class="form-control" value="{{$asset->MACId}}" name="MACId5" readonly placeholder="MAC Id" >
                                                    </div>
                                                </div>   
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Motherboard Id<span class="text-red">*</span> :</label>
                                                        <input type="text" class="form-control" value="{{$asset->motherboard}}" id="motherboard5" name="motherboard5" readonly placeholder="Motherboard Id">
                                                    </div>
                                                </div>    
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Serial Number<span class="text-red">*</span> :</label>
                                                        <input type="text" class="form-control" value="{{$asset->serialNo}}" id="serialNo5" name="serialNo5" readonly placeholder="Serial Number">
                                                    </div>
                                                </div> 
                                            </div>  
                                            <h5 style="color:blue;">Extra Material</h5>
                                            <hr>
                                            <div class="row"> 
                                            <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Keyboard<span class="text-red">*</span> :</label>
                                                        {{Form::select('keyboard5', ['Yes'=>'Yes', 'No'=>'No'], $asset->keyboard, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'keyboard'])}}
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Mouse<span class="text-red">*</span> :</label>
                                                        {{Form::select('mouse5', ['Yes'=>'Yes', 'No'=>'No'], $asset->mouse, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'mouse'])}}
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Mouse Pad<span class="text-red">*</span> :</label>
                                                        {{Form::select('mousePad5', ['Yes'=>'Yes', 'No'=>'No'], $asset->mousePad, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'mousePad'])}}
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Antivirus<span class="text-red">*</span> :</label>
                                                        {{Form::select('anti5', ['Yes'=>'Yes', 'No'=>'No'], $asset->anti, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'anti'])}}
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">UPS<span class="text-red">*</span> :</label>
                                                        {{Form::select('ups5', ['Yes'=>'Yes', 'No'=>'No'], $asset->ups, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'ups'])}}
                                                    </div>
                                                </div>  
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Extra Material<span class="text-red">*</span> :</label>
                                                        {{Form::select('extraMaterial5', ['Yes'=>'Yes', 'No'=>'No'], $asset->extraMaterial, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'extraMaterial5'])}}
                                                    </div>
                                                </div>  
                                            </div>
                                            <hr>
                                            <h5 style="color:blue;">Login Details</h5>
                                            <div class="row"> 
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Username<span class="text-red"></span> :</label>
                                                        <input type="text" class="form-control" value="{{$asset->username}}" readonly placeholder="Username" name="username5">
                                                    </div>
                                                </div>  
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Password<span class="text-red"></span> :</label>
                                                        <input type="text" class="form-control" value="{{$asset->password}}" name="password5" readonly placeholder="Password">
                                                    </div>
                                                </div>  
                                            </div>  
                                            <div class="row">    
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Remarks :</label>
                                                        <textarea class="form-control" name="remarks5" readonly placeholder="Remarks"> {{$asset->remarks}}</textarea>
                                                    </div>
                                                </div>  
                                            </div> 
                                            <hr>
                                        </div>
                                    @endif
                                    @if($type == 6)
                                        <div>
                                            <h5 style="color:red;">HARD DISK</h5>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Company Name<span class="text-red">*</span> :</label>
                                                        <input type="text" class="form-control" name="operatComName6" readonly placeholder="Company Name">
                                                    </div>
                                                </div> 
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Storage Size<span class="text-red">*</span> :</label>
                                                        <input type="text" class="form-control" name="storeageSize6" readonly placeholder="Storage Size">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Hard Disk Type<span class="text-red">*</span> :</label>
                                                        {{Form::select('modelType6', ['External'=>'External', 'Internal'=>'Internal'], NULL, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'hardType'])}}
                                                    </div>
                                                </div> 
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Extra Material<span class="text-red">*</span> :</label>
                                                        {{Form::select('extraMat6', ['Yes'=>'Yes', 'No'=>'No'], NULL, ['placeholder'=>'Select Option','class'=>' form-control','disabled', 'id'=>'extraMat'])}}
                                                    </div>
                                                </div>  
                                            </div>  
                                        </div>
                                    @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-1 col-md-1 col-lg-1"></div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
