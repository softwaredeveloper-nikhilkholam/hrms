<?php
        use App\Helpers\Utility;
        $util = new Utility();
        
        $name = Session()->get('name');
        $user = Auth::user();
        $userType = $user->userType;
        $language = $user->language;
    ?>
    @extends('layouts.master')
    @section('title', 'Management')
    @section('content') 
    <div class="container">
        <!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title">Attendance</h4>
            </div> 
        </div>
        <!--End Page header-->
        <div class="row">
            <div class="card">
                <div class="card-body">
                    {!! Form::open(['action' => 'admin\EmpAttendancesController@search', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                        <div class="row mt-5">
                        @if($userType == '51' || $userType == '61' || $userType == '201' || $userType == '401' || $userType == '501')
                            <div class="col-md-2 col-lg-2">
                                <div class="form-group">
                                    <label class="form-label">Employee Code:</label>
                                    <input type="text" class="form-control" placeholder="Employee Code" value="{{(isset($empCode))?$empCode:''}}" name="empCode">
                                </div>
                            </div>
                            @if($userType == '61')
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Organisation :</label>
                                        {{Form::select('organisation', ['Ellora'=>'Ellora Medicals and Educational foundation', 'Snayraa'=>'Snayraa Agency', 'Tejasha'=>'Tejasha Educational and research foundation', 'Akshara Foodkort'=>'Akshara Foodkort', 'Aaryans Dairy Farm'=>'Aaryans Dairy Farm', 'Yo Bhajiwala'=>'Yo Bhajiwala', 'Aaryans Farming Society'=>'Aaryans Farming Society'], (isset($organisation))?$organisation:null, ['placeholder'=>'Select Organisation','class'=>'form-control', 'id'=>'organisation'])}}
                                    </div>
                                </div>
                            @endif 
                            <div class="col-md-2 col-lg-2">
                                <div class="form-group">
                                    <label class="form-label">Section:</label>
                                    {{Form::select('section', ['Teaching'=>'Teaching', 'Non Teaching'=>'Non Teaching'], ((isset($section))?$section:null), ['placeholder'=>'Select Section','class'=>'form-control'])}}
                                </div>
                            </div>
                            <div class="col-md-2 col-lg-2">
                                <div class="form-group">
                                    <label class="form-label">Branches:</label>
                                    {{Form::select('branchId', $branches, ((isset($branchId))?$branchId:null), ['placeholder'=>'Select Branch','class'=>'form-control'])}}
                                </div>
                            </div>
                            @if($userType != '61')
                            <div class="col-md-2 col-lg-2">
                                <div class="form-group">
                                    <label class="form-label">Department:</label>
                                    {{Form::select('departmentId', $departments, ((isset($departmentId))?$departmentId:null), ['placeholder'=>'Select Department','class'=>'form-control'])}}
                                </div>
                            </div>
                            @endif
                        @endif
                            <div class="col-md-2 col-lg-2">
                                <div class="form-group">
                                    <label class="form-label">Month:</label>
                                    <input type="month" class="form-control" value="{{(isset($month))?$month:date('Y-M')}}" name="month" max="{{date('Y-m')}}" min="{{date('2024-01')}}" required>
                                </div>
                            </div>
                            <div class="col-md-2 col-lg-2"> 
                                <div class="form-group mt-5">
                                    <input type="hidden" value="2" name="flag">
                                    <button type="submit" class="btn btn-primary btn-block">Search</button>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!} 
                </div>
                <div class="card-body" style="font-size:10px !important;">
                    @if(isset($attendances))
                                             
                        <div class="row">
                            <div class="col-md-12">
                                <b style="color:red; font-size:18px;">Note : </b>
                                <span class="badge badge-danger" >  
                                    <span class="" data-toggle="tooltip" data-placement="top" title="Absent">A</span>
                                </span> <b> - Absent</b> &nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="badge badge-success" >  
                                    <span class="" data-toggle="tooltip" data-placement="top" title="Present">P</span>
                                </span> <b> - Present</b>&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="badge badge-success" >  
                                    <span class="" data-toggle="tooltip" data-placement="top" title="Weekly Off">WO</span>
                                </span> <b> - Weekly Off</b>&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="attendPresent" style="background-color:blue;">WO</span>
                                <b> - Half day Weekly Off</b>&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="badge badge-success" >  
                                    <span class="" data-toggle="tooltip" data-placement="top" title="Listed Holiday">LH</span>
                                </span> <b> - Listed Holiday</b>&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="badge badge-success" >  
                                    <span class="" data-toggle="tooltip" data-placement="top" title="Weekly Off with Extra Working">WO +1</span>
                                </span> <b> - Weekly Off with Extra Working</b>&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="badge badge-success" >  
                                    <span class="" data-toggle="tooltip" data-placement="top" title="Absent">LH +1</span>
                                </span> <b> - Listed Holiday with Extra working</b>&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="badge badge-primary" >  
                                    <span class="" data-toggle="tooltip" data-placement="top" title="Absent">P/2</span>
                                </span> <b> - Half Day</b>&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="badge badge-warning" >  
                                    <span class="" data-toggle="tooltip" data-placement="top" title="Absent">PBL</span>
                                </span> <b> - Present But Late</b>&nbsp;&nbsp;&nbsp;&nbsp;
                            </div>
                        </div>
                         
                        <div class="table-responsive mt-3">
                            <table class="table text-nowrap table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        @if($userType == '61')
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>Timings</th>
                                        @else    
                                            <th>Code-Name</th>
                                        @endif
                                        
                                        <?php $month = date('Y-m', strtotime($startDate)); ?>
                                        @for($k=1; $k<=$days; $k++)
                                            <th class="text-center">{{$k}}
                                                <br><b style="font-size:10px;">{{date('D',strtotime($month.'-'.$k))}}</b>
                                            </th>
                                        @endfor
                                        <th>Present</th>
                                        <th>Absent</th>
                                        <th>WL</th>
                                        <th>Extra</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $k=0;$no=($attendances->currentPage()*70) - 69; ?>
                                    @if(count($attendances))
                                        @foreach($attendances as $key => $attend)
                                            @if($k==0)
                                                <input type="hidden" name="empId[]" value="{{$attend->empId}}">
                                                <?php $sandwichPol=0;$deduction=0;$wLeave=0;$sandwitchFlag=0;$tempDayStatus=0; ?>
                                                <tr>
                                                    <td class="text-center">{{$no++}}</td>
                                                    <td style="position:sticky;left:40px;background: white;z-index: 10;" width="7%"><?php  $totDays=$lateMark=$extraW=$sandTp=$wfh=0; ?>
                                                        <a href="/employees/{{$attend->empId}}" target="_blank">    
                                                            <b style="font-size:10px;color:green;" class="mt-2 fs-14">{{$attend->empCode}} - {{$attend->name}}</b>
                                                            <b style="font-size:10px;" class="mt-2 fs-14">{{$attend->designationName}}&nbsp;[{{$attend->branchName}}]&nbsp;&nbsp;[ {{($attend->startTime != '')?(date('H:i', strtotime($attend->startTime))." To ".date('H:i', strtotime($attend->endTime))):"NA"}} ]</b>
                                                        </a>
                                                    </td>
                                            @endif
                                            <td class="text-center"><b style="padding:4px;background-color:green;color:white;border-radius: 25px;">{{$attend->dayStatus}}</b></td>
                                            <?php $k++; ?>
                                            @if($k == $days)
                                                    <?php $wLeave=$wLeave+((int)($lateMark/3));  ?> 
                                                    <td>{{$totDays-$wLeave}}<input type="hidden" name="totPresent[]" value="{{$totDays}}"></td>
                                                    <td>{{$days-$totDays}}<input type="hidden" name="totAbsent[]" value="{{$days-$totDays}}"></td>
                                                    <td>{{$wLeave}}<input type="hidden" name="totWLeave[]" value="{{$wLeave}}"></td>
                                                    <td>{{$extraW}}<input type="hidden" name="extraWork[]" value="{{$extraW}}"></td>
                                                    <td>{{($totDays+$extraW)-$wLeave}}<input type="hidden" name="total[]" value="{{$totDays+$extraW}}"></td>
                                                </tr>
                                                <?php $k=0; ?>
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endsection
