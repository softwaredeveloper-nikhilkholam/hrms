<?php
    $userType = Session()->get('userType');
?>

@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Fees Concession</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/employees/feesConcession" class="btn btn-primary mr-3">Active List</a>
                            <a href="/employees/dFeesConcession" class="btn btn-primary mr-3">Deactive List</a>
                            <a href="/employees/addFeesConcession" class="btn btn-success mr-3">Add</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">	
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="tab-menu-heading hremp-tabs p-0 ">
                        <div class="tabs-menu1">
                            <ul class="nav panel-tabs">
                                <li class="ml-4"><a href="#tab1" class="active" data-toggle="tab">Fees Concession</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <div class="card-body">
                                    <div class="card-body">
                                        <hr>
                                        <h4 class="mb-5 mt-3 font-weight-bold" style="color:Red;"><center>Fees Concession Details</center></h4>
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                @if($feesConcession) 
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered card-table table-vcenter border-top text-nowrap mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-right" width="40%">Academic Year&nbsp;<span class="text-red" style="font-size:22px;">*</span></th>
                                                                    <th class="text-center" width="60%"><input type="text" class="form-control" value="{{$feesConcession->acadmicYear}}" name="acadmicYear" disabled></th>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-right" width="40%">Student Name&nbsp;<span class="text-red" style="font-size:22px;">*</span></th>
                                                                    <th class="text-center" width="60%"><input type="text" class="form-control" value="{{$feesConcession->studentName}}" placeholder="Student Name" name="studentName" disabled></th>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-right" width="40%">Branch&nbsp;<span class="text-red" style="font-size:22px;">*</span></th>
                                                                    <th class="text-center" width="60%">{{Form::select('branchId', $branches, $feesConcession->branchId, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'branchId', 'disabled'])}}</th>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-right" width="40%">Class-Section&nbsp;<span class="text-red" style="font-size:22px;">*</span></th>
                                                                    <th class="text-center" width="60%"><input type="text" class="form-control" value="{{$feesConcession->classSection}}" placeholder="Class-Section" name="classSection" disabled></th>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-right" width="40%">Under Category&nbsp;<span class="text-red" style="font-size:22px;">*</span></th>
                                                                    <th>{{Form::select('category', ['Category 1 (A & B)'=>'Category 1 (A & B)', ' Category 2 (C & D)'=>' Category 2 (C & D)'], $feesConcession->category, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'category', 'disabled'])}}</th>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                    <br>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered card-table table-vcenter border-top text-nowrap mb-0">
                                                            <thead>
                                                                <tr  style="background-color:#b3e1b0;">
                                                                    <th class="text-center" rowspan="2" width="15%">Fee Head</th>
                                                                    <th class="text-center" colspan="6" width="85%">Installments</th>
                                                                </tr>
                                                                <tr style="background-color:#b3e1b0;">
                                                                    <th class="text-center">1st Inst.</th>
                                                                    <th class="text-center">Amt.</th>
                                                                    <th class="text-center">2nd Inst.</th>
                                                                    <th class="text-center">Amt.</th>
                                                                    <th class="text-center">3rd Inst.</th>
                                                                    <th class="text-center">Amt.</th>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-center">Tuition Fee</th>
                                                                    <th class="text-center">{{Form::select('tuitionInst1', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->tuitionInst1, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst1', 'disabled'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->tuitionFees1}}" placeholder="0" name="tuitionFees1" disabled></th>
                                                                    <th class="text-center">{{Form::select('tuitionInst2', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->tuitionInst2, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst1', 'disabled'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->tuitionFees2}}" placeholder="0" name="tuitionFees2" disabled></th>
                                                                    <th class="text-center">{{Form::select('tuitionInst3', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->tuitionInst3, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst1', 'disabled'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->tuitionFees3}}" placeholder="0" name="tuitionFees3" disabled></th>
                                                                </tr>    
                                                                <tr>
                                                                    <th class="text-center">Worksheet Fee</th>
                                                                    <th class="text-center">{{Form::select('worksheetInst1', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->worksheetInst1, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'worksheetInst1', 'disabled'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->worksheetFees1}}" placeholder="0" name="worksheetFees1" disabled></th>
                                                                    <th class="text-center">{{Form::select('worksheetInst2', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->worksheetInst2, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'worksheetInst2', 'disabled'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->worksheetFees2}}" placeholder="0" name="worksheetFees2" disabled></th>
                                                                    <th class="text-center">{{Form::select('worksheetInst3', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->worksheetInst3, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'worksheetInst3', 'disabled'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->worksheetFees3}}" placeholder="0" name="worksheetFees3" disabled></th>
                                                                </tr>    
                                                                <tr>
                                                                    <th class="text-center">Transport Fee</th>
                                                                    <th class="text-center">{{Form::select('transportInst1', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->transportInst1, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst1', 'disabled'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->transportFees1}}" placeholder="0" name="transportFees1" disabled></th>
                                                                    <th class="text-center">{{Form::select('transportInst2', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->transportInst2, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst1', 'disabled'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->transportFees2}}" placeholder="0" name="transportFees2" disabled></th>
                                                                    <th class="text-center">{{Form::select('transportInst3', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->transportInst3, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst1', 'disabled'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->transportFees3}}" placeholder="0" name="transportFees3" disabled></th>
                                                                </tr>    
                                                                <tr>
                                                                    <th class="text-center">GPS Charges</th>
                                                                    <th class="text-center">{{Form::select('gpsInst1', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->gpsInst1, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst1', 'disabled'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->gpsCharge1}}" placeholder="0" name="gpsCharge1" disabled></th>
                                                                    <th class="text-center">{{Form::select('gpsInst2', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->gpsInst2, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst1', 'disabled'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->gpsCharge2}}" placeholder="0" name="gpsCharge2" disabled></th>
                                                                    <th class="text-center">{{Form::select('gpsInst3', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], $feesConcession->gpsInst3, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst1', 'disabled'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="{{$feesConcession->gpsCharge3}}" placeholder="0" name="gpsCharge3" disabled></th>
                                                                </tr>                                                               
                                                            </thead>
                                                        </table>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Updated At &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                                <input type="text" class="form-control" value="{{date('d-m-Y H:i', strtotime($feesConcession->updated_at))}}" disabled>
                                                            </div>
                                                        </div>       
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Updated By &nbsp;<span class="text-red" style="font-size:22px;">*</span>:</label>
                                                                <input type="text" class="form-control" value="{{$feesConcession->updated_by}}" disabled>
                                                            </div>
                                                        </div>                              
                                                    </div>   
                                                    <div class="row">
                                                        <div class="col-md-5"></div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                @if($feesConcession->active == 1)
                                                                    <a href="/employees/cancelFeesConcession/{{$feesConcession->id}}" class="btn btn-danger mr-3">Cancel Fees Concession</a>
                                                                @else
                                                                    <a href="/employees/cancelFeesConcession/{{$feesConcession->id}}" class="btn btn-success mr-3">Activate Fees Concession</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5"></div>                        
                                                    </div>   
                                                @endif                                        
                                            </div>
                                        <div class="col-md-1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection

