@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-7"><b style="color:red;">Add Fuel Entry</b></div>
                            <div  class="col-lg-5 text-right">
                                <a href="/fuelSystems/create" class="btn mb-1 btn-success">Add</a>
                                <a href="/fuelSystems/dlist" class="btn mb-1 btn-primary">Deactive List</a>
                                <a href="/fuelSystems" class="btn mb-1 btn-primary">Active List</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\FuelFilledSystemsController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="row"> 
                            <div class="col-md-4"></div>                    
                            <div class="col-md-4 text-center">   
                                <b style="color:black;">Date: {{date('d-m-Y')}}</b>                   
                            </div> 
                            <div class="col-md-4"></div> 
                        </div> 
                        <div class="row"> 
                            <div class="col-md-4"></div>                    
                            <div class="col-md-4">                      
                                <div class="form-group">
                                    {{Form::select('fuelType', ['1'=>'Diesel', '2'=>'Petrol', '3'=>'Other'], null, ['placeholder'=>'Select FuelType','class'=>'form-control', 'id'=>'fuelType', 'required'])}}
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div> 
                            <div class="col-md-4"></div> 
                        </div> 
                        <div class="row"> 
                            <div class="col-md-4"></div>                    
                            <div class="col-md-4">                      
                                <div class="form-group">
                                    <input type="text" class="form-control" name="fuelRate" placeholder="Enter Fuel Rate" onkeypress="return validateInput(event)" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div> 
                            <div class="col-md-4"></div> 
                        </div> 
                        <div class="row"> 
                            <div class="col-md-4"></div>                    
                            <div class="col-md-4">                      
                                <div class="form-group">
                                    {{Form::select('vendorId', $vendors, null, ['placeholder'=>'Select Vendor','class'=>'form-control', 'id'=>'vendorId', 'required'])}}
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div> 
                            <div class="col-md-4"></div> 
                        </div> 
                        <div class="row"> 
                            <div class="col-md-4"></div>                    
                            <div class="col-md-4">                      
                                <div class="form-group">
                                    {{Form::select('branchId', $branches, null, ['placeholder'=>'Select Branch','class'=>'form-control', 'id'=>'branchId', 'required'])}}
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div> 
                            <div class="col-md-4"></div> 
                        </div> 
                        <hr>
                        <div class="row"> 
                            <div class="col-md-4"></div>                    
                            <div class="col-md-4 text-center">  
                                <button type="submit" class="btn btn-success mr-2">Save</button>
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
