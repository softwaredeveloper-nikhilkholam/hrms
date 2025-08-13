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
                                    Deactive List <span class="badge badge-danger ml-2">{{$dUser}}</span>
                                </a>
                                <a href="/storeUser" class="btn mb-1 btn-primary">
                                    Active List <span class="badge badge-danger ml-2">{{$users}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => ['storeController\StoreUsersController@update', $user->id], 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="row ">  
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Employee Name<span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="empName" name="name" value="{{$user->name}}" placeholder="Enter Sub Category Name" disabled>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>  
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Designation<span style="color:red;">*</span></label>
                                    {{Form::select('designationId', $designations,$user->designationId, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'', 'required'])}}
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>    
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Department<span style="color:red;">*</span></label>
                                    {{Form::select('departmentId', $departments, $user->reqDepartmentId, ['class'=>'form-control departmentIdData', 'placeholder'=>'Select a Option', 'required', 'id'=>'', 'required'])}}
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>  
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Branch<span style="color:red;">*</span></label>
                                    {{Form::select('branchId', $branches, $user->reqBranchId, ['class'=>'form-control', 'placeholder'=>'Select a Option', 'required', 'id'=>'', 'required'])}}
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div> 
                            <div class="col-md-3">                      
                                <div class="form-group">
                                    <label>Type Of Company<span style="color:red;"></span></label>
                                    {{Form::select('typeOfCompany[]', ['1'=>'Ellora Medical And Educational Foundation','2'=>'Snayraa Educational AID And Research Foundation','3'=>'Tejasha Educational and Research Foundation','4'=>'Tejasha Agricultural Farmining and Educating the students society','5'=>'Aaryans Animal, Birds, Fish, Reptiles Rescue, Rehabilitation and Educational Society','6'=>'Akshara Food Court','7'=>'YO Bhajiwala','8'=>'Aaryans Farm Fresh','9'=>'Aaryans Dairy Farm','10'=>'YO Bhajiwala','11'=>'Aaryans farm','12'=>'Aaryans farming Society','13'=>'Aaryans River Wood Resort','14'=>'Aaryans Edutainment','15'=>'Aaryans Hathway Farm', '16'=>'Milind Ladge', '17'=>'Pratik Ladge', '18'=>'Pranav Ladge'], $user->typeOfCompany, ['class'=>'form-control', 'placeholder'=>'Select Option', 'id'=>'typeOfCompany', 'style'=>'font-weight: bold;font-size:18px;', 'multiple'=>'multiple'])}}
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>     
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Username<span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="storeUsername" value="{{$user->username}}" name="username" value="" placeholder="Enter Username" required>
                                </div>
                            </div>  
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Password<span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="password" name="password" value="{{$user->viewPassword}}" placeholder="Enter Password" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>  
                        </div>  
                        <div class="row ">  
                            <div class="col-md-2"> 
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="mySwitch1" name="forQuotation" value="1" {{($user->purchaseAccess == '1')?'checked':''}}>
                                    <label class="form-check-label" for="mySwitch1">For Quotation</label>
                                </div>                     
                            </div>   
                            
                            <div class="col-md-2"> 
                                <div class="form-check form-switch" style="margin-top:30px;">
                                    <input class="form-check-input" type="checkbox" id="mySwitch3" name="forAsset" value="1" {{($user->assetAccess == '1')?'checked':''}}>
                                    <label class="form-check-label" for="mySwitch3">For Asset</label>
                                </div>                     
                            </div>   

                            <div class="col-md-2"> 
                                <div class="form-check form-switch" style="margin-top:30px;">
                                    <input class="form-check-input" type="checkbox" id="mySwitch4" name="forSubStore" value="1" {{($user->subStoreAccess == '1')?'checked':''}}>
                                    <label class="form-check-label" for="mySwitch4">For Sub Store</label>
                                </div>                     
                            </div>        
                        </div>        
                    
                        <div class="row">
                            <div class="col-md-2">                      
                                <div class="form-group"  style="width: 326px !important;">
                                    <label>Select Category<span style="color:red;">*</span></label><br>
                                    <select name="categoryId[]" class="form-control userCategoryId" id="field2" multiple multiselect-search="true" multiselect-select-all="true" multiselect-max-items="3" onchange="console.log(this.selectedOptions)" required>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}" {{($category->selected)?'selected':''}}>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>    
                        </div>    
                        <div class="col-md-3"> 
                            <button type="submit" class="btn btn-success mr-2">Update User</button>
                            <button type="reset" class="btn btn-danger">Reset</button>            
                        </div>                    
                        </div>       
                        {{Form::hidden('_method', 'PUT')}}                     
                        
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
