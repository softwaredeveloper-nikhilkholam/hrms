@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-7"><b style="color:red;">Active Users List</b></div>
                            <div  class="col-lg-5">
                                <a href="/storeUser/create" class="btn mb-1 btn-primary">Add</a>
                                <a href="/storeUser/dlist" class="btn mb-1 btn-primary">
                                    Deactive List <span class="badge badge-danger ml-2">{{$dUsers}}</span>
                                </a>
                                <a href="/storeUser" class="btn mb-1 btn-primary">
                                    Active List <span class="badge badge-danger ml-2">{{$users}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h6 style="color:red;">If you want to add outsider User, Please enter '00000' employee Code..</h6>
                    {!! Form::open(['action' => 'storeController\StoreUsersController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                    <div class="row">
                        <div class="col-md-4">                      
                            <div class="form-group">
                                <label>Employee Code<span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="empCode" onkeypress="return isNumberKey(event)" name="empCode" value="" placeholder="Enter Employee Code" required>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>    
                        <div class="col-md-4">                      
                            <div class="form-group">
                                <button type="button" class="btn btn-success mr-2 searchEmpCode" id="searchEmpCode" style="margin-top:37px;">Find</button>
                            </div>
                        </div>
                    </div>
                    <div class="row empInformation">  
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Employee Name<span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="empName" name="name" value="" placeholder="Enter Name" disabled>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>  
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Designation<span style="color:red;">*</span></label>
                                {{Form::select('designationId', $designations, null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'designationId', 'disabled'])}}
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>    
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Department<span style="color:red;">*</span></label>
                                {{Form::select('departmentId', $departments, null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'departmentId', 'required'])}}
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>  
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Branch<span style="color:red;">*</span></label>
                                {{Form::select('branchId', $branches, null, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'branchId', 'required'])}}
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-5">                      
                                <div class="form-group">
                                    <label>Type Of Company<span style="color:red;"></span></label>
                                    {{Form::select('typeOfCompany[]', $typeOfCompanies, NULL, ['class'=>'form-control', 'placeholder'=>'Select Option', 'id'=>'typeOfCompany', 'style'=>'font-weight: bold;font-size:18px;', 'multiple'=>'multiple'])}}
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>    
                       
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Username<span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="" name="username" value="" placeholder="Enter Username" required>
                            </div>
                        </div>  
                        <div class="col-md-3">                      
                            <div class="form-group">
                                <label>Password<span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="password" name="password" value="" placeholder="Enter Password" required>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>  
                        <div class="col-md-3"> 
                            <div class="form-check form-switch" style="margin-top:30px;">
                                <input class="form-check-input" type="checkbox" id="mySwitch1" name="forQuotation" value="1" >
                                <label class="form-check-label" for="mySwitch1">For Quotation</label>
                            </div>                     
                        </div>       
                        <div class="col-md-3"> 
                            <div class="form-check form-switch" style="margin-top:30px;">
                                <input class="form-check-input" type="checkbox" id="mySwitch2" name="forBranchAdmin" value="1" >
                                <label class="form-check-label" for="mySwitch2">For Branch Admin</label>
                            </div>                     
                        </div>    
                        <div class="col-md-3"> 
                            <div class="form-check form-switch" style="margin-top:30px;">
                                <input class="form-check-input" type="checkbox" id="mySwitch3" name="forAsset" value="1" >
                                <label class="form-check-label" for="mySwitch3">For Asset</label>
                            </div>                     
                        </div>       
                    </div>        
                
                    <div class="row showCategory">
                        <div class="col-md-4">                      
                            <div class="form-group"  style="width: 326px !important;">
                                <label>Select Category<span style="color:red;">*</span></label><br>
                                <select name="categoryId[]" class="form-control userCategoryId" id="field2" multiple multiselect-search="true" multiselect-select-all="true" multiselect-max-items="3" onchange="console.log(this.selectedOptions)">
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>    
                    </div> 
                    <button type="submit" class="btn btn-success mr-2 showEmpDet">Save</button>
                    <button type="reset" class="btn btn-danger showEmpDet">Reset</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
