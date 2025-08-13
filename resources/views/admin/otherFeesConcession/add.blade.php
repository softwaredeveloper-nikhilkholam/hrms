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
                            <a href="#" class="btn btn-success mr-3">Add</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">	
                <div class="col-xl-12 col-md-12 col-lg-12">
                    {!! Form::open(['action' => 'admin\employees\EmployeesController@storeFeesConcession', 'method' => 'POST', 'class' => 'form-horizontal',  'enctype'=>'multipart/form-data']) !!}
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
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered card-table table-vcenter border-top text-nowrap mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-right" width="40%">Academic Year&nbsp;<span class="text-red" style="font-size:22px;">*</span></th>
                                                                    <th class="text-center" width="60%">
                                                                        <select class="form-control" name="acadmicYear">
                                                                            <option value="">Select Option</option>
                                                                            @for($i=2018; $i<=date('Y'); $i++)
                                                                                <?php $k=$i+1; ?>
                                                                                <option value="<?php echo $i.' - '.$k; ?>"><?php echo $i.' - '.$k; ?></option>
                                                                            @endfor 
                                                                        </select>                                                                               
                                                                    </th>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-right" width="40%">Student Name&nbsp;<span class="text-red" style="font-size:22px;">*</span></th>
                                                                    <th class="text-center" width="60%"><input type="text" class="form-control" value="" placeholder="Student Name" name="studentName" required></th>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-right" width="40%">Branch&nbsp;<span class="text-red" style="font-size:22px;">*</span></th>
                                                                    <th class="text-center" width="60%">{{Form::select('branchId', $branches, null, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'branchId', 'required'])}}</th>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-right" width="40%">Class-Section&nbsp;<span class="text-red" style="font-size:22px;">*</span></th>
                                                                    <th class="text-center" width="60%"><input type="text" class="form-control" value="" placeholder="Class-Section" name="classSection" required></th>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-right" width="40%">Under Category&nbsp;<span class="text-red" style="font-size:22px;">*</span></th>
                                                                    <th>{{Form::select('category', ['Category 1 (A & B)'=>'Category 1 (A & B)', ' Category 2 (C & D)'=>' Category 2 (C & D)'], null, ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'category', 'required'])}}</th>
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
                                                                    <th class="text-center">{{Form::select('tuitionInst1', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], 'NA', ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst1'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="" placeholder="0" readonly  id="tuitionFees1"  name="tuitionFees1"></th>
                                                                    <th class="text-center">{{Form::select('tuitionInst2', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], 'NA', ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst2'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="" placeholder="0" readonly  id="tuitionFees2"  name="tuitionFees2"></th>
                                                                    <th class="text-center">{{Form::select('tuitionInst3', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], 'NA' , ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'tuitionInst3'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="" placeholder="0" readonly  id="tuitionFees3"  name="tuitionFees3"></th>
                                                                </tr>    
                                                                <tr>
                                                                    <th class="text-center">Worksheet Fee</th>
                                                                    <th class="text-center">{{Form::select('worksheetInst1', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], 'NA', ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'worksheetInst1'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="" placeholder="0" readonly  id="worksheetFees1"  name="worksheetFees1"></th>
                                                                    <th class="text-center">{{Form::select('worksheetInst2', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], 'NA', ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'worksheetInst2'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="" placeholder="0" readonly  id="worksheetFees2"  name="worksheetFees2"></th>
                                                                    <th class="text-center">{{Form::select('worksheetInst3', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], 'NA', ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'worksheetInst3'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="" placeholder="0" readonly  id="worksheetFees3"  name="worksheetFees3"></th>
                                                                </tr>    
                                                                <tr>
                                                                    <th class="text-center">Transport Fee</th>
                                                                    <th class="text-center">{{Form::select('transportInst1', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], 'NA', ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'transportInst1'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="" placeholder="0" readonly  id="transportFees1"  name="transportFees1"></th>
                                                                    <th class="text-center">{{Form::select('transportInst2', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], 'NA', ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'transportInst2'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="" placeholder="0" readonly  id="transportFees2"  name="transportFees2"></th>
                                                                    <th class="text-center">{{Form::select('transportInst3', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], 'NA', ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'transportInst3'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="" placeholder="0" readonly  id="transportFees3"  name="transportFees3"></th>
                                                                </tr>    
                                                                <tr>
                                                                    <th class="text-center">GPS Charges</th>
                                                                    <th class="text-center">{{Form::select('gpsInst1', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], 'NA', ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'gpsInst1'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="" placeholder="0" readonly  id="gpsCharge1"  name="gpsCharge1"></th>
                                                                    <th class="text-center">{{Form::select('gpsInst2', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'], 'NA', ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'gpsInst2'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="" placeholder="0" readonly  id="gpsCharge2"  name="gpsCharge2"></th>
                                                                    <th class="text-center">{{Form::select('gpsInst3', ['Payable'=>'Payable', 'Waive-Off'=>'Waive-Off', 'NA'=>'NA'],'NA', ['placeholder'=>'Select Option','class'=>'form-control', 'id'=>'gpsInst3'])}}</th>
                                                                    <th class="text-center"><input type="text" class="form-control" value="" placeholder="0" readonly  id="gpsCharge3"  name="gpsCharge3"></th>
                                                                </tr>                                                               
                                                            </thead>
                                                        </table>
                                                    </div>                                                   
                                                </div>
                                            <div class="col-md-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:10px;">
                            <div class="col-md-5"></div>
                            <div class="col-md-3">
                                <button type="Submit" class="empAdd btn btn-success btn-lg">Save</button>
                                <a href="/employees" class="btn btn-danger btn-lg">Cancel</a>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    {!! Form::close() !!}     
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection

