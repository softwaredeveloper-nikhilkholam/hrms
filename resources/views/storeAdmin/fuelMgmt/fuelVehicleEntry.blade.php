@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-7"><b style="color:red;">Active Fuel Entry List</b></div>
                            <div  class="col-lg-5 text-right">
                                <a href="/fuelSystems/create" class="btn mb-1 btn-primary">Add</a>
                                <a href="/fuelSystems/dlist" class="btn mb-1 btn-primary">Deactive List</a>
                                <a href="/fuelSystems" class="btn mb-1 btn-primary">Active List</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\FuelFilledSystemsController@storeFuelVehicleEntry', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="row"> 
                            <div class="col-md-4"></div> 
                            <div class="col-md-4 text-center">                      
                                <b id="" style="color:red;font-size:13px;">{{$fuelEntry->vendor}}</b>
                            </div>                    
                            <div class="col-md-4"></div> 
                        </div> 
                        <div class="row"> 
                            <div class="col-md-4"></div> 
                            <div class="col-md-4 text-center">                      
                                <b id="" style="color:red;font-size:13px;">{{$fuelEntry->branchName}}</b>
                            </div>                    
                            <div class="col-md-4"></div> 
                        </div> 
                        <div class="row"> 
                            <div class="col-md-4"></div>                    
                            <div class="col-md-4">                      
                                <div class="form-group">
                                    {{Form::select('vehicleId', $vehicles, null, ['placeholder'=>'Select Vehicle','class'=>'form-control', 'id'=>'vehicleId', 'required'])}}
                                </div>
                            </div> 
                            <div class="col-md-4"></div> 
                        </div> 
                        <div class="row"> 
                            <div class="col-md-4"></div>                    
                            <div class="col-md-4">                      
                                <div class="form-group">
                                    <input type="text" class="form-control" name="oldKM" id="oldKM" placeholder="Old KM" onkeypress="return validateInput(event)" required>
                                </div>
                            </div> 
                            <div class="col-md-4"></div> 
                        </div> 
                        <div class="row"> 
                            <div class="col-md-4"></div>                    
                            <div class="col-md-4">                      
                                <div class="form-group">
                                    <input type="text" class="form-control" name="newKM" id="newKM" placeholder="New KM" onkeypress="return validateInput(event)" required>
                                </div>
                            </div> 
                            <div class="col-md-4"></div> 
                        </div> 
                        <div class="row"> 
                            <div class="col-md-4"></div>                    
                            <div class="col-md-4">                      
                                <div class="form-group">
                                    <input type="text" class="form-control" name="ltr"  id="ltr" placeholder="Ltr." onkeypress="return validateInput(event)" required>
                                </div>
                            </div> 
                            <div class="col-md-4"></div> 
                        </div>                         
                        <div class="row"> 
                            <div class="col-md-4"></div>                    
                            <div class="col-md-4">  
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="meterPhoto" id="fuelReadingPhoto" required>
                                    <label class="custom-file-label" for="meterPhoto">Choose Meter Image</label>
                                </div>                         
                            </div> 
                            <div class="col-md-4"></div> 
                        </div> 
                        <div class="row mt-2"> 
                            <div class="col-md-4"></div>                    
                            <div class="col-md-4"> 
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="fuelReadingPhoto" id="fuelReadingPhoto" required>
                                    <label class="custom-file-label" for="fuelReadingPhoto">Choose Fuel Reading Image</label>
                                </div>                     
                            </div> 
                            <div class="col-md-4"></div> 
                        </div> 
                        <hr>
                        <div class="row"> 
                            <div class="col-md-4"></div> 
                            <div class="col-md-2 text-center">                      
                                <b id="" style="color:red;font-size:13px;">{{($fuelEntry->petrolRate != 0)?'Petrol':'Diesel'}} Rate. {{($fuelEntry->petrolRate != 0)?$fuelEntry->petrolRate:$fuelEntry->dieselRate}}</b>
                            </div> 
                            <div class="col-md-2 text-center">                      
                                <b id="average" style="color:red;font-size:13px;">Vehicle Average</b>
                            </div>                    
                            <div class="col-md-4"></div> 
                        </div> 
                        <div class="row"> 
                            <div class="col-md-4"></div> 
                            <div class="col-md-2 text-center">                      
                                <b id="amount" style="color:red;font-size:13px;">Rs.</b>
                            </div>                    
                            <div class="col-md-2 text-center">                      
                                <b id="totalKM" style="color:red;font-size:13px;">KM: </b>
                            </div> 
                            <div class="col-md-4"></div> 
                        </div> 
                        <div class="row"> 
                            <div class="col-md-4"></div>                    
                            <div class="col-md-4 text-center">
                                <input type="hidden" value="{{($fuelEntry->petrolRate != 0)?$fuelEntry->petrolRate:$fuelEntry->dieselRate}}" id="fuelRate">                      
                                <input type="hidden" value="{{$fuelEntry->id}}" name="fuelEntryId">                      
                                <button type="submit" class="btn btn-success  w-100" width="100%">Save</button>
                            </div> 
                            <div class="col-md-4"></div> 
                        </div> 
                        <div class="row mt-2"> 
                            <div class="col-md-4"></div>                    
                            <div class="col-md-4 text-center">
                                <button type="reset" class="btn btn-danger  w-100" width="100%">Reset</button>
                            </div> 
                            <div class="col-md-4"></div> 
                        </div> 
                    {!! Form::close() !!}
                    <div class="row mt-2"> 
                        <div class="col-md-12"> 
                            @if(count($vehicleListLast5) > 0)
                                @foreach($vehicleListLast5 as $vehicle)
                                    <div class="row mb-4">
                                        <div class="col-md-4 offset-md-4">
                                            <div class="card shadow rounded-3">
                                                <div class="card-body">
                                                    <h5 class="card-title text-center mb-3">Vehicle Details</h5>

                                                    <p><strong>Vehicle No:</strong> {{ $vehicle->busNo }}</p>
                                                    <p><strong>Old KM:</strong> {{ $vehicle->oldKM }}</p>
                                                    <p><strong>New KM:</strong> {{ $vehicle->newKM }}</p>
                                                    <p><strong>KM Travelled:</strong> {{ $vehicle->newKM - $vehicle->oldKM }}</p>
                                                    <p><strong>Fuel Rate:</strong> ₹{{ $vehicle->fuelRate }}</p>
                                                    <p><strong>Litres Filled:</strong> {{ $vehicle->ltr }}</p>
                                                    <p><strong>Average (KM/L):</strong> {{ $vehicle->average }}</p>
                                                    
                                                    <p>
                                                        <strong>Image 1:</strong>
                                                        @if($vehicle->image1)
                                                            <a href="/storeAdmin/images/fuelFillingimages/fuelReceipts/{{ $vehicle->image1 }}" target="_blank" class="text-success fw-bold">✅ Uploaded</a>
                                                        @else
                                                            <span class="text-danger">❌ Not Uploaded</span>
                                                        @endif
                                                    </p>
                                                    <p>
                                                        <strong>Image 2:</strong>
                                                        @if($vehicle->image2)
                                                            <a href="/storeAdmin/images/fuelFillingimages/fuelReceipts/{{ $vehicle->image2 }}" target="_blank" class="text-success fw-bold">✅ Uploaded</a>
                                                        @else
                                                            <span class="text-danger">❌ Not Uploaded</span>
                                                        @endif
                                                    </p>

                                                    <a href="/fuelSystems/{{$vehicle->id}}/deleteVehicleEntry" class="btn btn-danger w-100 mt-3">
                                                        🗑️ Delete Entry
                                                    </a>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection
