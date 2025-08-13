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
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Organisation :</label>
                                        {{Form::select('organisation', ['Ellora'=>'Ellora Medicals and Educational foundation', 'Snayraa'=>'Snayraa Agency', 'Tejasha'=>'Tejasha Educational and research foundation', 'Akshara Foodkort'=>'Akshara Foodkort', 'Aaryans Dairy Farm'=>'Aaryans Dairy Farm', 'Yo Bhajiwala'=>'Yo Bhajiwala', 'Aaryans Farming Society'=>'Aaryans Farming Society'], (isset($organisation))?$organisation:null, ['placeholder'=>'Select Organisation','class'=>'form-control', 'id'=>'organisation'])}}
                                    </div>
                                </div>
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
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Department:</label>
                                        {{Form::select('departmentId', $departments, ((isset($departmentId))?$departmentId:null), ['placeholder'=>'Select Department','class'=>'form-control'])}}
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-2 col-lg-2">
                                <div class="form-group">
                                    <label class="form-label">Month&nbsp;&nbsp;<span style="color:red;">*</span>:</label>
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
                            <style>
                                .table-responsive {
                                    height: 500px;
                                    overflow: auto;
                                    position: relative;
                                }

                                /* Sticky Header */
                                thead th {
                                    position: sticky;
                                    top: 0;
                                    background: white;
                                    z-index: 10;
                                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                                }

                                /* Sticky First Column */
                                th:nth-child(1), td:nth-child(1) {
                                    position: sticky;
                                    left: -5;
                                    background: white;
                                    z-index: 11;
                                    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
                                }

                                /* Sticky Second Column */
                                th:nth-child(2), td:nth-child(2) {
                                    position: sticky;
                                    left: 50px; /* Adjust based on column width */
                                    background: white;
                                    z-index: 11;
                                    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
                                }
                            </style>
                        @if($userType == '61')
                            <style>
                                
                                /* Sticky Second Column */
                                th:nth-child(3), td:nth-child(3) {
                                    position: sticky;
                                    left: 250px; /* Adjust based on column width */
                                    background: white;
                                    z-index: 11;
                                    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
                                }

                                /* Sticky Second Column */
                                th:nth-child(4), td:nth-child(4) {
                                    position: sticky;
                                    left: 450px; /* Adjust based on column width */
                                    background: white;
                                    z-index: 11;
                                    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
                                }
                            </style>
                        @endif
                        <style>

                            /* Attendance Status Styling */
                            .attendPresent {
                                background-color: green;
                                color: white;
                                padding: 5px 10px;
                                font-weight: bold;
                                border-radius: 25px;
                            }

                            .attendAbsent {
                                background-color: red;
                                color: white;
                                padding: 5px 10px;
                                font-weight: bold;
                                border-radius: 25px;
                            }

                            .attendPBL {
                                background-color: #ffc107;
                                color: white;
                                padding: 5px 10px;
                                font-weight: bold;
                                border-radius: 25px;
                            }

                            /* Tooltip Styling */
                            .tooltip {
                                position: relative;
                                display: inline-block;
                                border-bottom: 1px dotted black;
                                cursor: pointer;
                            }

                            .tooltip .tooltiptext {
                                visibility: hidden;
                                width: 140px;
                                background-color: #555;
                                color: #fff;
                                text-align: center;
                                border-radius: 6px;
                                padding: 5px 10px;
                                position: absolute;
                                z-index: 1;
                                bottom: 100%;
                                left: 50%;
                                transform: translateX(-50%);
                                opacity: 0;
                                transition: opacity 0.3s ease-in-out;
                            }

                            .tooltip .tooltiptext::after {
                                content: "";
                                position: absolute;
                                top: 100%;
                                left: 50%;
                                transform: translateX(-50%);
                                border-width: 5px;
                                border-style: solid;
                                border-color: #555 transparent transparent transparent;
                            }

                            .tooltip:hover .tooltiptext {
                                visibility: visible;
                                opacity: 1;
                            }

                        </style>
                         
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
                         
                        <div class="table-responsive hr-attlist mt-3">
                            <table class="table table-vcenter text-nowrap table-bordered border-top border-bottom" id="">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        @if($userType == '61')
                                            <th>Code-Name</th>
                                            <th>Designation</th>
                                            <th>Timings</th>
                                        @else    
                                            <th>Code-Name</th>
                                        @endif
                                        
                                        <?php $month = date('Y-m', strtotime($startDate)); ?>
                                        @for($k=1; $k<=$days; $k++)
                                            <th class="text-center border-bottom-0 w-5">{{$k}}
                                                <br><b style="font-size:12px;">{{date('D',strtotime($month.'-'.$k))}}</b>
                                            </th>
                                        @endfor
                                        <th>Present</th>
                                        <th>Absent</th>
                                        <th>WL</th>
                                        <th>Extra</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $k=0;
                                    $no=($attendances->currentPage()*70) - 69; ?>
                                    @if(count($attendances))
                                        @foreach($attendances as $key => $attend)
                                            @if($k==0)
                                                <input type="hidden" name="empId[]" value="{{$attend->empId}}">
                                                <?php $sandwichPol=0;$deduction=0;$wLeave=0;$sandwitchFlag=0;$tempDayStatus=0; ?>
                                                @if($attend->salaryHoldRelease == 1)
                                                    <tr style="background-color:#f6b0b0;">
                                                @else
                                                    <tr>
                                                @endif

                                                <td style="position:sticky;left:-1px;background: white;z-index: 10;" class="text-center">{{$no++}}</td>
                                                @if($userType == '61')
                                                    <td><h6 style="font-size:12px;color:green;" class="mt-2 fs-14">{{$attend->empCode}} - {{$attend->name}}</h6></td>
                                                    <td width="7%">
                                                        <?php  $totDays=$lateMark=$extraW=$sandTp=$wfh=0; ?>
                                                        <a href="/employees/{{$attend->empId}}" target="_blank">    
                                                            <h6 style="font-size:12px;" class="mt-2 fs-14">{{$attend->designationName}}[{{$attend->branchName}}]</h6>
                                                        </a>
                                                    </td>
                                                    <td><h6 style="font-size:12px;color:red;" class="mt-2 fs-14"><b>{{($attend->startTime != '')?(date('H:i', strtotime($attend->startTime))." To ".date('H:i', strtotime($attend->endTime))):"NA"}}</h6></td>
                                                @else
                                                    <td style="position:sticky;left:40px;background: white;z-index: 10;" width="7%"><?php  $totDays=$lateMark=$extraW=$sandTp=$wfh=0; ?>
                                                        <a href="/employees/{{$attend->empId}}" target="_blank">    
                                                            <h6 style="font-size:12px;color:green;" class="mt-2 fs-14">{{$attend->empCode}} - {{$attend->name}}</h6>
                                                            <h6 style="font-size:12px;" class="mt-2 fs-14">{{$attend->designationName}}&nbsp;[{{$attend->branchName}}]&nbsp;&nbsp;[ {{($attend->startTime != '')?(date('H:i', strtotime($attend->startTime))." To ".date('H:i', strtotime($attend->endTime))):"NA"}} ]</h6>
                                                        </a>
                                                    </td>
                                                @endif
                                            @endif
                                            @if($attend->dayName == 'Sun')
                                                <td class="text-center"  style="background-color:#9b93965c;">
                                            @else
                                                <td class="text-center">
                                            @endif
                                                <?php 
                                                    $holidayFlag=$jobJoining=0;
                                                    if($attend->jobJoingDate <= $attend->forDate)
                                                    {
                                                        $jobJoining = 0;
                                                    }
                                                    else
                                                        $jobJoining = 1;

                                                    if($attend->holiday != 0)
                                                    {
                                                        $prev = $attendances[$key-1];
                                                        if(($k+1) < $days)
                                                            $next = $attendances[$key+1];

                                                        if(isset($next) && $prev)
                                                        {
                                                            $i=0;
                                                            while(isset($next->dayStatus) == 0 && isset($next->holiday) == 0)
                                                            {
                                                                $next = $attendances[$key+$i];
                                                                $i++;
                                                            }
                                                        
                                                            if(isset($next) && isset($prev))
                                                            {
                                                            
                                                                if(($prev->dayStatus == '0' || $prev->dayStatus == 'A') && ($next->dayStatus == '0' || $next->dayStatus == 'A'))
                                                                {    
                                                                    if($prev->AGFStatus == 0 && $next->AGFStatus == 0 && $deduction >= 4)
                                                                        $holidayFlag=1;
                                                                    else
                                                                        $holidayFlag=0;
                                                                    
                                                                }
                                                            }
                                                        }
                                                    }
                                                ?>
                                                @if($jobJoining == 0)
                                                    <div class="hr-listd">
                                                        <a href="#" data-toggle="modal" data-target="#presentmodal{{$attend->id}}" class="hr-listmodal"></a>
                                                        @if($attend->forDate == $attend->jobJoingDate)
                                                            <b style="color:purple;">New Joinnee</b><br>
                                                        @endif
                                                        @if($attend->dayStatus == 'WO' && $attend->day == '01' && isset($attendances[$key+1]) && isset($attendances[$key+2]) && isset($attendances[$key+2]))
                                                            @if($attendances[$key+1]->dayStatus == 'A' && $attendances[$key+2]->dayStatus == 'A' && $attendances[$key+3]->dayStatus == 'A')
                                                                <span class="attendAbsent">A</span>
                                                                <?php $deduction=$deduction+1; ?>
                                                                <?php $wLeave=$wLeave+1; ?>
                                                            @else
                                                                <span class="attendPresent">WO</span>
                                                                <?php $totDays=$totDays+1; ?>
                                                                @if($attend->repAuthStatus != 0)
                                                                    <br><b><a href="/empApplications/{{$attend->empId}}/{{$month}}/1/AGFShow">AGF({{$attend->AGFDayStatus}})</a></b><br>
                                                                    <b style="{{($attend->repAuthStatus != 0)?'color:green;':'color:red;'}}">{{($attend->repAuthStatus != 0)?'✓':'✗'}}</b>
                                                                    <b style="{{($attend->HRStatus != 0)?'color:green;':'color:red;'}}">{{($attend->HRStatus != 0)?'✓':'✗'}}</b>
                                                                    <b style="{{($attend->AGFStatus != 0)?'color:green;':'color:red;'}}">{{($attend->AGFStatus != 0)?'✓':'✗'}}</b>
                                                                    @if($attend->AGFStatus != 0)
                                                                        @if($attend->AGFDayStatus == 'Full Day')
                                                                            <?php $extraW=$extraW+1; ?>
                                                                        @else
                                                                            <?php $extraW=$extraW+0.5; ?>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @elseif($attend->paymentType == 3 && $attend->dayStatus == 'WO')                                                         
                                                            @if($attend->halfDayTime <= $attend->workingHr)
                                                                <span class="attendPresent" style="background-color:blue;">WO</span>
                                                                <?php $totDays=$totDays+1; ?>
                                                                @if($attend->repAuthStatus != 0)
                                                                    <br><b><a href="/empApplications/{{$attend->empId}}/{{$month}}/1/AGFShow">AGF({{$attend->AGFDayStatus}})</a></b><br>
                                                                    <b style="{{($attend->repAuthStatus != 0)?'color:green;':'color:red;'}}">{{($attend->repAuthStatus != 0)?'✓':'✗'}}</b>
                                                                    <b style="{{($attend->HRStatus != 0)?'color:green;':'color:red;'}}">{{($attend->HRStatus != 0)?'✓':'✗'}}</b>
                                                                    <b style="{{($attend->AGFStatus != 0)?'color:green;':'color:red;'}}">{{($attend->AGFStatus != 0)?'✓':'✗'}}</b>
                                                                    @if($attend->AGFStatus != 0)
                                                                        @if($attend->AGFDayStatus == 'Full Day')
                                                                            <?php $extraW=$extraW+1; ?>
                                                                        @else
                                                                            <?php $extraW=$extraW+0.5; ?>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @else
                                                                <span class="attendAbsent">A</span>
                                                                @if($attend->repAuthStatus != 0)
                                                                    <br><b><a href="/empApplications/{{$attend->empId}}/{{$month}}/1/AGFShow"><a href="/empApplications/{{$attend->empId}}/{{$month}}/1/AGFShow">AGF({{$attend->AGFDayStatus}})</a></a></b><br>
                                                                    <b style="{{($attend->repAuthStatus != 0)?'color:green;':'color:red;'}}">{{($attend->repAuthStatus != 0)?'✓':'✗'}}</b>
                                                                    <b style="{{($attend->HRStatus != 0)?'color:green;':'color:red;'}}">{{($attend->HRStatus != 0)?'✓':'✗'}}</b>
                                                                    <b style="{{($attend->AGFStatus != 0)?'color:green;':'color:red;'}}">{{($attend->AGFStatus != 0)?'✓':'✗'}}</b>
                                                                    @if($attend->AGFStatus != 0)
                                                                        @if($attend->AGFDayStatus == 'Full Day')
                                                                            <?php $totDays=$totDays+1; ?>
                                                                        @else
                                                                            <?php $totDays=$totDays+0.5; ?>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @elseif($attend->dayStatus == 'WO' && isset($attendances[$key+1]) && isset($attendances[$key-1]) && $attendances[$key+1]->dayStatus == 'A' && ($attendances[$key-1]->dayStatus == 'A' && $attendances[$key-1]->AGFStatus == 0))
                                                                <span class="attendAbsent">A</span>
                                                                <?php $deduction=$deduction+1; ?>
                                                                <?php $wLeave=$wLeave+1; ?>
                                                        @elseif(($attend->dayStatus != 'WO') && ($attend->outTime == NULL || $attend->inTime == $attend->outTime))
                                                            <span class="attendAbsent">A</span>
                                                            @if($attend->repAuthStatus != 0)
                                                                <br><b><a href="/empApplications/{{$attend->empId}}/{{$month}}/1/AGFShow"><a href="/empApplications/{{$attend->empId}}/{{$month}}/1/AGFShow">AGF({{$attend->AGFDayStatus}})</a></a></b><br>
                                                                <b style="{{($attend->repAuthStatus != 0)?'color:green;':'color:red;'}}">{{($attend->repAuthStatus != 0)?'✓':'✗'}}</b>
                                                                <b style="{{($attend->HRStatus != 0)?'color:green;':'color:red;'}}">{{($attend->HRStatus != 0)?'✓':'✗'}}</b>
                                                                <b style="{{($attend->AGFStatus != 0)?'color:green;':'color:red;'}}">{{($attend->AGFStatus != 0)?'✓':'✗'}}</b>
                                                                @if($attend->AGFStatus != 0)
                                                                    @if($attend->AGFDayStatus == 'Full Day')
                                                                        <?php $totDays=$totDays+1; ?>
                                                                    @else
                                                                        <?php $totDays=$totDays+0.5; ?>
                                                                    @endif
                                                                @else
                                                                    <?php $deduction=$deduction+1; ?>
                                                                @endif
                                                            @else
                                                                <br>
                                                                <?php $deduction=$deduction+1; ?>
                                                            @endif
                                                        @elseif($attend->dayStatus == 'WO' && $attend->dayName == 'Sun' && $holidayFlag == 1)
                                                            <span class="attendAbsent">
                                                                A
                                                            </span>
                                                            <?php $deduction=0; ?>
                                                            <?php $wLeave++; ?>
                                                        @elseif($attend->dayStatus == 'A')
                                                            <span class="attendAbsent">
                                                                A
                                                            </span>
                                                            @if($attend->repAuthStatus != 0)
                                                                <br><b><a href="/empApplications/{{$attend->empId}}/{{$month}}/1/AGFShow">AGF({{$attend->AGFDayStatus}})</a></b><br>
                                                                <b style="{{($attend->repAuthStatus != 0)?'color:green;':'color:red;'}}">{{($attend->repAuthStatus != 0)?'✓':'✗'}}</b>
                                                                <b style="{{($attend->HRStatus != 0)?'color:green;':'color:red;'}}">{{($attend->HRStatus != 0)?'✓':'✗'}}</b>
                                                                <b style="{{($attend->AGFStatus != 0)?'color:green;':'color:red;'}}">{{($attend->AGFStatus != 0)?'✓':'✗'}}</b>
                                                                @if($attend->AGFStatus != 0)
                                                                    @if($attend->AGFDayStatus == 'Full Day')
                                                                        <?php $totDays=$totDays+1; ?>
                                                                    @else
                                                                        <?php $totDays=$totDays+0.5; ?>
                                                                    @endif
                                                                @else
                                                                    <?php $deduction=$deduction+1; ?>
                                                                @endif
                                                            @else
                                                                <br>
                                                                <?php $deduction=$deduction+1; ?>
                                                            @endif
                                                            
                                                        @elseif($attend->dayStatus == 'WO' && $attend->dayName == 'Sun') 
                                                            @if(isset($attendances[$key-1]) && isset($attendances[$key+1]))
                                                                @if(($attendances[$key-1]->dayStatus == '0' && $attendances[$key+1]->dayStatus == '0') || ($attendances[$key-1]->outTime == NULL && ($attendances[$key+1]->dayStatus == '0' || $attendances[$key+1]->dayStatus == 'A')))
                                                                    @if($deduction == 3 || $deduction == 3.5)
                                                                        <span class="badge badge-primary" >  
                                                                            <span class="" data-toggle="tooltip" data-placement="top" title="Weekly off">P/2</span>
                                                                        </span>
                                                                        <?php $wLeave=$wLeave+0.5; ?>
                                                                        <?php $totDays=$totDays+0.5; ?>
                                                                    @elseif($deduction >= 4)
                                                                        <span class="attendAbsent">A</span>
                                                                        <?php $wLeave++; 
                                                                        $tempDayStatus='A'; ?>
                                                                        <?php $totDays=$totDays+1; ?>
                                                                    @else
                                                                        @if(($attendances[$key-1]->dayStatus == '0' && $attendances[$key+1]->dayStatus == '0') || ($attendances[$key-1]->outTime == NULL && ($attendances[$key+1]->dayStatus == '0' || $attendances[$key+1]->dayStatus == 'A')))
                                                                            @if($attendances[$key+1]->AGFStatus != 0 || $attendances[$key-1]->AGFStatus != 0)
                                                                                @if($attend->holiday == 3)
                                                                                    <span class="attendPresent">LH</span>     
                                                                                @else
                                                                                    <span class="attendPresent">WO</span>
                                                                                @endif
                                                                                @if($attend->paymentType == 3)
                                                                                    <?php $totDays=$totDays+0.5; ?>   
                                                                                @elseif($attend->paymentType == 2)
                                                                                    <?php $totDays=$totDays+0; ?>  
                                                                                @else 
                                                                                    <?php $totDays=$totDays+1; ?> 
                                                                                @endif

                                                                            @else
                                                                                @if($attend->dayStatus == 'WO')
                                                                                    <span class="attendPresent">WO</span>
                                                                                        @if($attend->paymentType == 3)
                                                                                            <?php $totDays=$totDays+0.5; ?>   
                                                                                        @elseif($attend->paymentType == 2)
                                                                                            <?php $totDays=$totDays+0; ?>  
                                                                                        @else 
                                                                                            <?php $totDays=$totDays+1; ?> 
                                                                                        @endif
                                                                                @else
                                                                                    <span class="attendAbsent">A</span>
                                                                                    <?php $wLeave++; ?>
                                                                                    <?php $sandwitchFlag++; ?>
                                                                                @endif
                                                                            @endif
                                                                        @else
                                                                            @if($attend->holiday == 3)
                                                                                <span class="attendPresent">LH</span>     
                                                                            @else
                                                                                <span class="attendPresent">WO</span>
                                                                            @endif
                                                                            @if($attend->paymentType == 3)
                                                                                <?php $totDays=$totDays+0.5; ?>   
                                                                            @elseif($attend->paymentType == 2)
                                                                                <?php $totDays=$totDays+0; ?>  
                                                                            @else 
                                                                                <?php $totDays=$totDays+1; ?> 
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                    <?php $deduction=0; ?>
                                                                    @if($attend->repAuthStatus != 0)
                                                                        <br><b><a href="/empApplications/{{$attend->empId}}/{{$month}}/1/AGFShow">AGF({{$attend->AGFDayStatus}})</a></b><br>
                                                                        <b style="{{($attend->repAuthStatus != 0)?'color:green;':'color:red;'}}">{{($attend->repAuthStatus != 0)?'✓':'✗'}}</b>
                                                                        <b style="{{($attend->HRStatus != 0)?'color:green;':'color:red;'}}">{{($attend->HRStatus != 0)?'✓':'✗'}}</b>
                                                                        <b style="{{($attend->AGFStatus != 0)?'color:green;':'color:red;'}}">{{($attend->AGFStatus != 0)?'✓':'✗'}}</b>
                                                                        @if($attend->AGFStatus != 0)
                                                                            @if($attend->AGFDayStatus == 'Full Day')
                                                                                <?php $extraW=$extraW+1; ?>
                                                                            @else
                                                                                <?php $extraW=$extraW+0.5; ?>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @else 
                                                                    @if($deduction == 3 || $deduction == 3.5)
                                                                            <span class="badge badge-primary" >  
                                                                                <span class="" data-toggle="tooltip" data-placement="top" title="Weekly off">P/2</span>
                                                                            </span>
                                                                            <?php $wLeave=$wLeave+0.5; ?>
                                                                            <?php $totDays=$totDays+0.5; ?>
                                                                    @elseif($deduction >= 4)
                                                                        <span class="attendAbsent">A</span>
                                                                        <?php $wLeave++; 
                                                                        $tempDayStatus='A'; ?>
                                                                    @else
                                                                        @if(($attendances[$key-1]->dayStatus == '0' && $attendances[$key+1]->dayStatus == '0') || ($attendances[$key-1]->outTime == NULL && ($attendances[$key+1]->dayStatus == '0' || $attendances[$key+1]->dayStatus == 'A')))
                                                                            <span class="attendAbsent">A</span>
                                                                            <?php $wLeave++; ?>
                                                                            <?php $sandwitchFlag++; ?>
                                                                        @else
                                                                            @if($attend->holiday == 3)
                                                                                <span class="attendPresent">LH</span>     
                                                                            @else
                                                                                <span class="attendPresent">WO</span>
                                                                            @endif
                                                                            @if($attend->paymentType == 3)
                                                                                <?php $totDays=$totDays+0.5; ?>   
                                                                            @elseif($attend->paymentType == 2)
                                                                                <?php $totDays=$totDays+0; ?>  
                                                                            @else 
                                                                                <?php $totDays=$totDays+1; ?> 
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                    <?php $deduction=0; ?>
                                                                    @if($attend->repAuthStatus != 0)
                                                                        <br><b><a href="/empApplications/{{$attend->empId}}/{{$month}}/1/AGFShow">AGF({{$attend->AGFDayStatus}})</a></b><br>
                                                                        <b style="{{($attend->repAuthStatus != 0)?'color:green;':'color:red;'}}">{{($attend->repAuthStatus != 0)?'✓':'✗'}}</b>
                                                                        <b style="{{($attend->HRStatus != 0)?'color:green;':'color:red;'}}">{{($attend->HRStatus != 0)?'✓':'✗'}}</b>
                                                                        <b style="{{($attend->AGFStatus != 0)?'color:green;':'color:red;'}}">{{($attend->AGFStatus != 0)?'✓':'✗'}}</b>
                                                                        @if($attend->AGFStatus != 0)
                                                                            @if($attend->AGFDayStatus == 'Full Day')
                                                                                <?php $extraW=$extraW+1; ?>
                                                                            @else
                                                                                <?php $extraW=$extraW+0.5; ?>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @endif         
                                                            @else
                                                                @if($deduction == 3 || $deduction == 3.5)
                                                                    <span class="badge badge-primary" >  
                                                                        <span class="" data-toggle="tooltip" data-placement="top" title="Weekly off">P/2</span>
                                                                    </span>
                                                                    <?php $wLeave=$wLeave+0.5; ?>
                                                                    <?php $totDays=$totDays+0.5; ?>
                                                                @elseif($deduction >= 4)
                                                                    <span class="attendAbsent">A</span>
                                                                    <?php $wLeave++; 
                                                                    $tempDayStatus='A'; ?>
                                                                @else
                                                                    @if(isset($attendances[$key-1]) && isset($attendances[$key+1]))
                                                                        @if(($attendances[$key-1]->dayStatus == '0' && $attendances[$key+1]->dayStatus == '0') || ($attendances[$key-1]->outTime == NULL && ($attendances[$key+1]->dayStatus == '0' || $attendances[$key+1]->dayStatus == 'A')))
                                                                            <span class="attendAbsent">A</span>
                                                                            <?php $wLeave++; ?>
                                                                            <?php $sandwitchFlag++; ?>
                                                                        @else
                                                                            @if($attend->holiday == 3)
                                                                                <span class="attendPresent">LH</span>     
                                                                            @else
                                                                                <span class="attendPresent">WO</span>
                                                                            @endif
                                                                            @if($attend->paymentType == 3)
                                                                                <?php $totDays=$totDays+0.5; ?>   
                                                                            @elseif($attend->paymentType == 2)
                                                                                <?php $totDays=$totDays+0; ?>  
                                                                            @else 
                                                                                <?php $totDays=$totDays+1; ?> 
                                                                            @endif
                                                                        @endif
                                                                    @else
                                                                        @if($attend->holiday == 3)
                                                                            <span class="attendPresent">LH</span>     
                                                                        @else
                                                                            <span class="attendPresent">WO</span>
                                                                        @endif
                                                                        @if($attend->paymentType == 3)
                                                                            <?php $totDays=$totDays+0.5; ?>   
                                                                        @elseif($attend->paymentType == 2)
                                                                            <?php $totDays=$totDays+0; ?>  
                                                                        @else 
                                                                            <?php $totDays=$totDays+1; ?> 
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                                <?php $deduction=0; ?>
                                                                @if($attend->repAuthStatus != 0)
                                                                    <br><b><a href="/empApplications/{{$attend->empId}}/{{$month}}/1/AGFShow">AGF({{$attend->AGFDayStatus}})</a></b><br>
                                                                    <b style="{{($attend->repAuthStatus != 0)?'color:green;':'color:red;'}}">{{($attend->repAuthStatus != 0)?'✓':'✗'}}</b>
                                                                    <b style="{{($attend->HRStatus != 0)?'color:green;':'color:red;'}}">{{($attend->HRStatus != 0)?'✓':'✗'}}</b>
                                                                    <b style="{{($attend->AGFStatus != 0)?'color:green;':'color:red;'}}">{{($attend->AGFStatus != 0)?'✓':'✗'}}</b>
                                                                    @if($attend->AGFStatus != 0)
                                                                        @if($attend->AGFDayStatus == 'Full Day')
                                                                            <?php $extraW=$extraW+1; ?>
                                                                        @else
                                                                            <?php $extraW=$extraW+0.5; ?>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @elseif($attend->dayStatus == 'WO')
                                                            @if($deduction == 5)
                                                                @if($attend->repAuthStatus != 0)
                                                                    @if($attend->AGFStatus != 0)
                                                                        <span class="attendPresent">WO</span>
                                                                    @endif
                                                                    <br><b><a href="/empApplications/{{$attend->empId}}/{{$month}}/1/AGFShow">AGF({{$attend->AGFDayStatus}})</a></b><br>
                                                                    <b style="{{($attend->repAuthStatus != 0)?'color:green;':'color:red;'}}">{{($attend->repAuthStatus != 0)?'✓':'✗'}}</b>
                                                                    <b style="{{($attend->HRStatus != 0)?'color:green;':'color:red;'}}">{{($attend->HRStatus != 0)?'✓':'✗'}}</b>
                                                                    <b style="{{($attend->AGFStatus != 0)?'color:green;':'color:red;'}}">{{($attend->AGFStatus != 0)?'✓':'✗'}}</b>
                                                                    @if($attend->AGFStatus != 0)
                                                                        @if($attend->AGFDayStatus == 'Full Day')
                                                                            <?php $extraW=$extraW+1; ?>
                                                                        @else
                                                                            <?php $extraW=$extraW+0.5; ?>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    <span class="attendAbsent">A</span>
                                                                    <?php $wLeave++; ?>
                                                                    <?php $sandwitchFlag++; ?>
                                                                @endif
                                                            @else
                                                                @if(isset($attendances[$key+1]) && isset($attendances[$key-1]))
                                                                    @if((($attendances[$key+1]->dayStatus == 'A' || $attendances[$key+1]->dayStatus == '0') && $attendances[$key+1]->AGFStatus == 0) && (($attendances[$key-1]->dayStatus == 'A' || $attendances[$key-1]->dayStatus == '0') && $attendances[$key-1]->AGFStatus == 0))
                                                                        <span class="attendAbsent">A</span>
                                                                        <?php $wLeave++; ?>
                                                                    @else
                                                                        @if($tempDayStatus == 'A' && $attendances[$key+1]->dayStatus == '0'  && $attend->dayStatus != 'WO')
                                                                            <span class="attendAbsent">A</span>
                                                                            <?php $wLeave++; ?>
                                                                            <?php $sandwitchFlag++; $tempDayStatus = 0;?>
                                                                        @else
                                                                            <span class="attendPresent">WO</span>
                                                                            @if($attend->repAuthStatus != 0)
                                                                                <br><b><a href="/empApplications/{{$attend->empId}}/{{$month}}/1/AGFShow">AGF({{$attend->AGFDayStatus}})</a></b><br>
                                                                                <b style="{{($attend->repAuthStatus != 0)?'color:green;':'color:red;'}}">{{($attend->repAuthStatus != 0)?'✓':'✗'}}</b>
                                                                                <b style="{{($attend->HRStatus != 0)?'color:green;':'color:red;'}}">{{($attend->HRStatus != 0)?'✓':'✗'}}</b>
                                                                                <b style="{{($attend->AGFStatus != 0)?'color:green;':'color:red;'}}">{{($attend->AGFStatus != 0)?'✓':'✗'}}</b>
                                                                                @if($attend->AGFStatus != 0)
                                                                                    @if($attend->AGFDayStatus == 'Full Day')
                                                                                        <?php $extraW=$extraW+1; ?>
                                                                                    @else
                                                                                        <?php $extraW=$extraW+0.5; ?>
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                            @if($attend->paymentType == 3)
                                                                                <?php $totDays=$totDays+0.5; ?>   
                                                                            @elseif($attend->paymentType == 2)
                                                                                <?php $totDays=$totDays+0; ?>  
                                                                            @else 
                                                                                <?php $totDays=$totDays+1; ?> 
                                                                            @endif
                                                                        @endif
                                                                    @endif                                                               
                                                                @else
                                                                        <span class="attendPresent">WO</span>
                                                                        @if($attend->repAuthStatus != 0)
                                                                            <br><b><a href="/empApplications/{{$attend->empId}}/{{$month}}/1/AGFShow">AGF({{$attend->AGFDayStatus}})</a></b><br>
                                                                            <b style="{{($attend->repAuthStatus != 0)?'color:green;':'color:red;'}}">{{($attend->repAuthStatus != 0)?'✓':'✗'}}</b>
                                                                            <b style="{{($attend->HRStatus != 0)?'color:green;':'color:red;'}}">{{($attend->HRStatus != 0)?'✓':'✗'}}</b>
                                                                            <b style="{{($attend->AGFStatus != 0)?'color:green;':'color:red;'}}">{{($attend->AGFStatus != 0)?'✓':'✗'}}</b>
                                                                            @if($attend->AGFStatus != 0)
                                                                                @if($attend->AGFDayStatus == 'Full Day')
                                                                                    <?php $extraW=$extraW+1; ?>
                                                                                @else
                                                                                    <?php $extraW=$extraW+0.5; ?>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                        @if($attend->paymentType == 3)
                                                                            <?php $totDays=$totDays+0.5; ?>   
                                                                        @elseif($attend->paymentType == 2)
                                                                            <?php $totDays=$totDays+0; ?>  
                                                                        @else 
                                                                            <?php $totDays=$totDays+1; ?> 
                                                                        @endif
                                                                @endif
                                                            @endif                                                           
                                                        @elseif($attend->dayStatus == 'P')
                                                            <span class="attendPresent">P</span>
                                                            <?php $totDays=$totDays+1; ?>
                                                        @elseif($attend->dayStatus == 'PL')
                                                            <span class="attendPBL">PBL</span>
                                                            @if($attend->repAuthStatus != 0)
                                                                <br><b><a href="/empApplications/{{$attend->empId}}/{{$month}}/1/AGFShow">AGF({{$attend->AGFDayStatus}})</a></b><br>
                                                                <b style="{{($attend->repAuthStatus != 0)?'color:green;':'color:red;'}}">{{($attend->repAuthStatus != 0)?'✓':'✗'}}</b>
                                                                <b style="{{($attend->HRStatus != 0)?'color:green;':'color:red;'}}">{{($attend->HRStatus != 0)?'✓':'✗'}}</b>
                                                                <b style="{{($attend->AGFStatus != 0)?'color:green;':'color:red;'}}">{{($attend->AGFStatus != 0)?'✓':'✗'}}</b>
                                                                @if($attend->AGFStatus == 0)
                                                                    <?php ++$lateMark; ?>
                                                                @endif
                                                            @else
                                                                <br>
                                                                <?php ++$lateMark; ?>
                                                            @endif
                                                            <?php $totDays=$totDays+1; ?>
                                                        @elseif($attend->dayStatus == 'PL1')
                                                            <span class="attendPBL">PBL</span>
                                                            <?php ++$lateMark; ?>
                                                            <?php $totDays=$totDays+1; ?>
                                                        @elseif($attend->dayStatus == 'PLH')
                                                            <span class="badge badge-warning" >  
                                                                <span class="" data-toggle="tooltip" data-placement="top" title="Weekly off">P/2</span>
                                                            </span>
                                                            <?php $lateMark++; ?>
                                                            @if($attend->repAuthStatus != 0)
                                                                <br><b><a href="/empApplications/{{$attend->empId}}/{{$month}}/1/AGFShow">AGF({{$attend->AGFDayStatus}})</a></b><br>
                                                                <b style="{{($attend->repAuthStatus != 0)?'color:green;':'color:red;'}}">{{($attend->repAuthStatus != 0)?'✓':'✗'}}</b>
                                                                <b style="{{($attend->HRStatus != 0)?'color:green;':'color:red;'}}">{{($attend->HRStatus != 0)?'✓':'✗'}}</b>
                                                                <b style="{{($attend->AGFStatus != 0)?'color:green;':'color:red;'}}">{{($attend->AGFStatus != 0)?'✓':'✗'}}</b>
                                                                @if($attend->AGFStatus != 0)
                                                                    @if($attend->AGFDayStatus == 'Full Day')
                                                                        <?php $totDays=$totDays+1; ?>
                                                                    @else
                                                                        <?php $totDays=$totDays+0.5; ?>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                <br>
                                                                <?php $totDays=$totDays+0.5;?>                                                                
                                                            @endif

                                                        @elseif($attend->dayStatus == 'PH')    
                                                            <span class="badge badge-primary" >  
                                                                <span class="" data-toggle="tooltip" data-placement="top" title="Weekly off">P/2</span>
                                                            </span>
                                                            @if($attend->repAuthStatus != 0)
                                                                <br><b><a href="/empApplications/{{$attend->empId}}/{{$month}}/1/AGFShow">AGF({{$attend->AGFDayStatus}})</a></b><br>
                                                                <b style="{{($attend->repAuthStatus != 0)?'color:green;':'color:red;'}}">{{($attend->repAuthStatus != 0)?'✓':'✗'}}</b>
                                                                <b style="{{($attend->HRStatus != 0)?'color:green;':'color:red;'}}">{{($attend->HRStatus != 0)?'✓':'✗'}}</b>
                                                                <b style="{{($attend->AGFStatus != 0)?'color:green;':'color:red;'}}">{{($attend->AGFStatus != 0)?'✓':'✗'}}</b>
                                                                @if($attend->AGFStatus != 0)
                                                                    @if($attend->AGFDayStatus == 'Full Day')
                                                                        <?php $totDays=$totDays+1; ?>
                                                                    @else
                                                                        <?php $totDays=$totDays+0.5; ?>
                                                                    @endif
                                                                @else
                                                                    <?php $deduction=$deduction+0.5; ?>
                                                                    <?php $totDays=$totDays+0.5; ?>
                                                                @endif
                                                            @else
                                                                <br>
                                                                <?php $totDays=$totDays+0.5; ?>
                                                                <?php $deduction=$deduction+0.5; ?>
                                                            @endif
                                                        @else
                                                            <span class="attendAbsent">A</span>
                                                            @if($attend->repAuthStatus != 0)
                                                                <br><b><a href="/empApplications/{{$attend->empId}}/{{$month}}/1/AGFShow">AGF({{$attend->AGFDayStatus}})</a></b><br>
                                                                <b style="{{($attend->repAuthStatus != 0)?'color:green;':'color:red;'}}">{{($attend->repAuthStatus != 0)?'✓':'✗'}}</b>
                                                                <b style="{{($attend->HRStatus != 0)?'color:green;':'color:red;'}}">{{($attend->HRStatus != 0)?'✓':'✗'}}</b>
                                                                <b style="{{($attend->AGFStatus != 0)?'color:green;':'color:red;'}}">{{($attend->AGFStatus != 0)?'✓':'✗'}}</b>
                                                                @if($attend->AGFStatus != 0)
                                                                    @if($attend->AGFDayStatus == 'Full Day')
                                                                        <?php $totDays=$totDays+1; ?>
                                                                    @else
                                                                        <?php $totDays=$totDays+0.5; ?>
                                                                    @endif
                                                                @else
                                                                    <?php $deduction=$deduction+1; ?>
                                                                @endif
                                                            @else
                                                                <br>
                                                                <?php $deduction=$deduction+1; ?>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    @if($attend->dayName == 'Sun')
                                                        <?php $deduction = 0;?>
                                                    @endif  
                                                                                                    
                                                    <div class="modal fade" id="presentmodal{{$attend->id}}">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Date: {{date('d-M-Y', strtotime($attend->forDate))}}</h5>
                                                                    <a href="#" class="btn btn-outline-primary" data-dismiss="modal">close</a>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <center><b style="color:purple;font-size:20px;">{{$attend->name}}</b><center>
                                                                    <center><b style="color:green;font-size:14px;">Today's Office Time : {{$attend->officeInTime}} To {{$attend->officeOutTime}}</b><center>
                                                                    
                                                                    <div class="row mb-5 mt-4">
                                                                        <div class="col-md-4">
                                                                            <div class="pt-5 text-center">
                                                                                <h6 class="mb-1 fs-16 font-weight-semibold">    
                                                                                    <b>{{($attend->inTime == NULL)?'-':date('H:i', strtotime($attend->inTime))}}</b></b>
                                                                                </h6>
                                                                                <small class="text-muted fs-14">{{($attend->deviceInTime != NULL)?("Logged in at ".$attend->deviceInTime):'Log In'}}</small>
                                                                            </div>
                                                                        </div>
                                                                        @if($attend->dayStatus == "WOP" || $attend->dayStatus == "P" || $attend->dayStatus == "P")
                                                                            <div class="col-md-4">
                                                                                <div class="chart-circle chart-circle-md" data-value="100" data-thickness="6" data-color="#0dcd94">
                                                                                    <div class="chart-circle-value"><b>{{$attend->workingHr}} hrs</b></div>
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            <div class="col-md-4">
                                                                                @if($attend->dayStatus == "WOPL" || $attend->dayStatus == "PL")
                                                                                    <div class="chart-circle chart-circle-md" data-value=".90" data-thickness="6" data-color="red">
                                                                                @else
                                                                                    <div class="chart-circle chart-circle-md" data-value=".50" data-thickness="6" data-color="red">
                                                                                @endif
                                                                                    <div class="chart-circle-value"><b>{{$attend->workingHr}} hrs</b></div>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                        <div class="col-md-4">
                                                                            <div class="pt-5 text-center">
                                                                                <h6 class="mb-1 fs-16 font-weight-semibold"> 
                                                                                    <b>
                                                                                        {{($attend->outTime == NULL)?'-':date('H:i', strtotime($attend->outTime))}}
                                                                                    </b>
                                                                                </h6>
                                                                                <small class="text-muted fs-14">{{($attend->deviceOutTime != NULL)?("Logged Out at ".$attend->deviceOutTime):'Log Out'}}</small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mt-2">
                                                                        <div class="col-md-12">
                                                                            <div class="pt-5 text-center">
                                                                                @if($attend->repAuthStatus != 0 && ($userType == '61' || $userType == '51' || $userType == '501'))
                                                                                    <h4><a target="_blank" href="/empApplications/{{$attend->empId}}/{{$month}}/1/AGFShow">AGF({{$attend->AGFDayStatus}})</a></h4><br>
                                                                                @endif
                                                                                    <center><b style="color:Red;">Till Date Calculations (For Half Day minimum working hours is {{$attend->halfDayTime}})</b></center>
                                                                                        <style>
                                                                                            #agf {
                                                                                                border-collapse: collapse;
                                                                                                table-layout: fixed;
                                                                                                width: 310px;
                                                                                            }
                                                                                            #agf td {
                                                                                                border: solid 1px #666;
                                                                                                width: 110px;
                                                                                                word-wrap: break-word;
                                                                                            }
                                                                                        </style>
                                                                                        <table class="table" width="100%">
                                                                                            <tr>
                                                                                                <th>Total Days</th>
                                                                                                <th>Total Extra Work</th>
                                                                                                <th>Total Late Mark</th>
                                                                                                <th>Day Status</th>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>{{$totDays}}</td>
                                                                                                <td>{{$extraW}}</td>
                                                                                                <td>{{$lateMark}}</td>
                                                                                                <td>{{$attend->dayStatus}}</td>
                                                                                            </tr>
                                                                                        </table>                                                                                   
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <b>NA</b>
                                                @endif
                                            </td>
                                            <?php $k++; ?>
                                            @if($k == $days)
                                                    <?php $lateMark=((int)($lateMark/3));  ?> 
                                                    <td>{{$totDays-$lateMark}}<input type="hidden" name="totPresent[]" value="{{$totDays-$lateMark}}"></td>
                                                    <td>{{$days-$totDays-$wLeave}}<input type="hidden" name="totAbsent[]" value="{{$days-$totDays-$wLeave}}"></td>
                                                    <td>{{$wLeave+$lateMark}}<input type="hidden" name="totWLeave[]" value="{{$wLeave+$lateMark}}"></td>
                                                    <td>{{$extraW}}<input type="hidden" name="extraWork[]" value="{{$extraW}}"></td>
                                                    <td>{{($totDays-$lateMark)+($extraW)}}<input type="hidden" name="total[]" value="{{($totDays-$lateMark)+($extraW)}}"></td>
                                                    <td><b>{{($attend->salaryHoldRelease == 1)?'Hold':'Release'}}</b></td>
                                                </tr>
                                                <?php $k=0; ?>
                                            @endif
                                        @endforeach
                                    @endif
                                    </tbody>
                            </table>
                        </div>

                        <div class="row" style="margin-top:15px;">
                            <div class='col-md-8'>
                                {{$attendances->links()}}
                            </div>
                            <div class='col-md-4'>
                            @if($userType == '51' || $userType == '61' || $userType == '201' || $userType == '401' || $userType == '501')
                                {!! Form::open(['action' => 'admin\EmpAttendancesController@exportExcel', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                    <div class="row"> 
                                        <div class="col-md-6 col-lg-6"> 
                                            <div class="form-group mt-5">
                                                <input type="hidden" value="{{$empCode}}" name="empCode">
                                                <input type="hidden" value="{{$organisation}}" name="organisation">
                                                <input type="hidden" value="{{$branchId}}" name="branchId">
                                                <input type="hidden" value="{{$section}}" name="section">
                                                <input type="hidden" value="{{$departmentId}}" name="departmentId">
                                                <input type="hidden" value="{{$month}}" name="month">
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-file-excel-o" aria-hidden="true"></i>Export to Excel</button>
                                            </div>
                                        </div>
                                    </div>
                                {!! Form::close() !!}                                                    
                            @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endsection
