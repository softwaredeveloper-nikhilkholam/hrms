<?php
    use App\Helpers\Utility;
    $util = new Utility();
    $name = Session()->get('name');
    $section = Session()->get('section');
    $user = Auth::user();
    $language = $user->language;
    $salary = Session()->get('salary');
?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
	<div class="container">                        
		<!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <b style="font-size:20px;" class="page-title">
                    @if($employee->firmType == 1)
                        {{$employee->empCode}} 
                    @elseif($employee->firmType == 2)
                        AFF{{$employee->empCode}} 
                    @else
                        AFS{{$employee->empCode}} 
                    @endif
                     - {{$name}}</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ 
                     <b style="font-size:20px;" class="page-title">Reporting: <b style="color:red;">{{$repoName}}</b> ]
                     @if($section == 'Teaching')
                        <a href="/admin/letterHeads/EmploymentContractAgreementTeaching.pdf" target="_blank"><img src="admin/646.jpg" height="50" width="50"></a>
                    @endif
            </div>
            <style>
                    .marquee {
                    margin: 0 auto;
                    overflow: hidden;
                    white-space: nowrap;
                    }
                    .marquee span {
                    display: inline-block;
                    font-size: 20px;
                    position: relative;
                    left: 100%;
                    animation: marquee 8s linear infinite;
                    }
                    .marquee:hover span {
                    animation-play-state: paused;
                    }

                    .marquee span:nth-child(1) {
                    animation-delay: 0s;
                    }
                    .marquee span:nth-child(2) {
                    animation-delay: 0.8s;
                    }
                    .marquee span:nth-child(3) {
                    animation-delay: 1.6s;
                    }
                    .marquee span:nth-child(4) {
                    animation-delay: 2.4s;
                    }
                    .marquee span:nth-child(5) {
                    animation-delay: 3.2s;
                    }

                    @keyframes marquee {
                    0%   { left: 100%; }
                    100% { left: -100%; }
                    }
            </style>
            <div class="page-rightheader ml-md-auto">
               
            </div>
        </div>
        <!--End Page header-->
        <hr>
        <!--Row-->
        <h5>This Month <b style="color:red;">[ Today's Punch-In Time: {{($punchTime == 0)?'-':date('h:i A', strtotime($punchTime))}} ]</b></h5>           
        <div class="row">
            <div class="col-xl-2 col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-7">
                                <div class="mt-0 text-left"> <h5 class="">{{($language == 1)?'Number of Days':'टोटल वर्किंग दिवस'}}</h5> 
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="icon1 bg-success my-auto  float-right">{{date('d')}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-7">
                                <div class="mt-0 text-left"> <h5 class="">{{($language == 1)?'Present Days':'प्रेझेंट दिवस'}}</h5>
                                   
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="icon1 bg-primary my-auto  float-right"> {{$presentDays}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-7">
                                <div class="mt-0 text-left"> <h5 class="">{{($language == 1)?'Absent Days':'अबसेन्ट दिवस'}}</h5>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="icon1 bg-secondary my-auto  float-right"> {{$absentDays}} </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-7">
                                <div class="mt-0 text-left"> <h5 class="">{{($language == 1)?'Extra Work':'अबसेन्ट दिवस'}}</h5>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="icon1 bg-purple my-auto  float-right"> {{$extraDays}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-7">
                                <div class="mt-0 text-left"> <h5 class="">{{($language == 1)?'Salary':'पगार'}}</h5>
                                    <h5 class="mb-0 mt-auto text-danger showSalary" style="cursor: -webkit-grab; cursor: grab;">Show</h5>
                                    <input type="hidden" value="{{$util->getSalaryAmount($user->empId)}}" id="salaryAmount">
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="icon1 bg-danger my-auto  float-right"> <i class="feather feather-award"></i> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="mt-0 text-left"> <h5 class="">{{($language == 1)?'Retention':'पगार'}}</h5>
                                    <h5 class="mb-0 mt-auto text-danger showRetention" style="cursor: -webkit-grab; cursor: grab;">Show</h5>
                                    <input type="hidden" value="{{$util->getRetentionAmount($user->empId)}}" id="retentionAmount">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="icon1 bg-danger my-auto  float-right"> <i class="feather feather-award"></i> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Row-->
        <div class="row">
            @if(isset($wishes))
                @if(count($wishes))
                <?php $tp=0; ?>
                    <marquee>
                        @foreach($wishes as $wish)
                            @if($tp == 0)
                                <b style="color:red;">Birthday Wishes from {{$wish->name}}</b> |
                                <?php $tp=1; ?>
                            @else
                                <b style="color:green;">Birthday Wishes from {{$wish->name}}</b> |
                                <?php $tp=0; ?>
                            @endif
                            
                        @endforeach
                    </marquee>
                @endif
            @endif
        </div>
        <!--Row-->
        <div class="row"> 
            <div class="col-xl-9 col-md-12 col-lg-12">
                <div class="row">
                    <div class="col-xl-6 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header border-0">
                                <h4 class="card-title">{{date('F-Y')}} (Till Date)</h4>
                            </div>
                            <div class="pt-4">
                                <div class="table-responsive">
                                    <table class="table transaction-table mb-0 text-nowrap">
                                        <tbody>
                                            <tr class="border-bottom">
                                                <td class="d-flex pl-6">
                                                    <span class="bg-blue pink-border brround d-block mr-5 mt-1 h-5 w-5"></span>
                                                    <div class="my-auto">
                                                        <span class="mb-1 font-weight-semibold fs-17">Total Days/Rs</span>
                                                    </div>
                                                </td>
                                                <td class="text-right pr-6">
                                                    <a class="d-block fs-16" href="#" style="color:#083dc1;">{{$mDay = date('t')}} Days / Rs. {{$util->numberFormat($perDay = $salary / $mDay)}}/-</a>
                                                </td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td class="d-flex pl-6">
                                                    <span class="bg-green pink-border brround d-block mr-5 h-5 w-5"></span>
                                                    <div class="my-auto">
                                                        <span class="mb-1 font-weight-semibold fs-17">Present Days/Rs</span>
                                                    </div>
                                                </td>
                                                <td class="text-right pr-6">
                                                    <a class="d-block fs-16" href="#" style="color:green;">{{$presentDays}} Days / Rs. {{ $util->numberFormat($perDay*$presentDays) }}/-</a>
                                                </td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td class="d-flex pl-6">
                                                    <span class="bg-red pink-border brround d-block mr-5 mt-1 h-5 w-5"></span>
                                                    <div class="my-auto">
                                                        <span class="mb-1 font-weight-semibold fs-17">Absent Days/Rs </span>
                                                    </div>
                                                </td>
                                                <td class="text-right pr-6">
                                                    <a class="d-block fs-16" href="#" style="color:red;">{{$absentDays}} Days / Rs. {{ $util->numberFormat($perDay*$absentDays) }}/-</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header border-0">
                                <h4 class="card-title">Up Coming Holidays &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                @if($holidayList)        
                                    [<a style="color:red;" href="/admin/holidayLists/{{$holidayList->fileName}}" target="_blank">Holiday List {{$holidayList->year}}</a>]</h4>
                                @endif
                            </div>
                            <div class="card-body mt-1">
                                @if(isset($holidays))
                                    @if(count($holidays) >= 1)
                                        @foreach($holidays as $holiday)
                                            <div class="mb-5">
                                                <div class="d-flex coming_holidays calendar-icon icons">
                                                        @if(date('d-M') == date('d-M', strtotime($holiday->forDate)))
                                                            <span class="date_time bg-success-transparent bradius mr-3">
                                                        @else
                                                            <span class="date_time bg-danger-transparent bradius mr-3">
                                                        @endif
                                                        <span class="date fs-15">{{date('d', strtotime($holiday->forDate))}}</span>
                                                        <span class="month fs-10">{{date('M', strtotime($holiday->forDate))}}</span>
                                                    </span>
                                                    <div class="mr-3 mt-0 mt-sm-1 d-block">
                                                        <h6 class="mb-1 font-weight-semibold">{{($holiday->name == 'Sunday' || $holiday->name == 'Saturday')?'Week Off':$holiday->name}}</h6>
                                                        <span class="clearfix"></span>
                                                        <h6 class="mb-1 font-weight-semibold">{{date('D', strtotime($holiday->forDate))}}</h6>
                                                    </div>
                                                    <p class="float-right text-muted  mb-0 fs-13 ml-auto bradius my-auto">{{$util->getDateDiff($holiday->forDate)}}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <b style="color:red;">Coming Soon...</b>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-12 col-lg-12">
                        <div class="card mg-b-20 card--events">
                            <div class="card-header border-bottom-0">
                                <div class="card-title">Up Coming Birthday</div>
                            </div>
                            <div class="card-body p-0">
                                @if(isset($birthdays))
                                    <div class="table-responsive">
                                        <table class="table transaction-table mb-0 text-nowrap">
                                            <tbody>
                                                @foreach($birthdays as $birthday)
                                                    <tr class="border-bottom">
                                                        <td class="d-flex  coming_holidays calendar-icon icons pl-6">
                                                            @if(date('m-d', strtotime($birthday->DOB)) == date('m-d'))
                                                                <span class="date_time bg-success-transparent bradius mr-3">
                                                            @else
                                                                <span class="date_time bg-primary-transparent bradius mr-3">
                                                            @endif
                                                                <span class="date fs-15">{{date('d', strtotime($birthday->DOB))}}</span>
                                                                <span class="month fs-15">{{date('M', strtotime($birthday->DOB))}}</span>
                                                            </span>
                                                            <div class="mr-3 mt-0 mt-sm-1 d-block">
                                                                <h6 class="mb-1 font-weight-semibold">{{$birthday->name}}</h6>
                                                                <span class="clearfix"></span>
                                                                <h6 class="mb-1 font-weight-semibold">{{$birthday->designationName}}</h6>
                                                                <span class="clearfix"></span>
                                                                <h6 class="mb-1 font-weight-semibold">{{$birthday->branchName}}</h6>
                                                            </div>
                                                            @if(date('m-d', strtotime($birthday->DOB)) == date('m-d'))
                                                                &nbsp;
                                                                <div class="text-right mr-10">
                                                                    <a class="btn btn-outline-orange mt-0" href="/employees/bdayWish/{{$birthday->id}}" id="wish" to="{{$birthday->name}}" bdayId="{{$birthday->id}}"><i class="fa fa-birthday-cake mr-2"></i>Send your Wishes</a>
                                                                </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div> 
            </div> 
            @if($flag == 0)
                <div class="col-xl-3 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">{{($language == 1)?'Notice Board':'नोटीस बोर्ड'}}</h4>
                        </div>
                        <div class="pt-4">
                            <div class="table-responsive">
                                <table class="table transaction-table mb-0 text-nowrap">
                                    <tbody style="overflow-y:scroll; height:10px;">
                                        @if(isset($todayLateMark))
                                            @if($todayLateMark[0] != 0)
                                                <tr class="border-bottom">
                                                    <td class="d-flex pl-6">
                                                        <span class="bg-pink pink-border brround d-block mr-5 mt-1 h-5 w-5"></span>
                                                        <div class="my-auto">
                                                            <span class="mb-1 font-weight-semibold fs-17">Late Mark</span>
                                                            <div class="clearfix"></div>
                                                            <small class="text-muted fs-14">Your office intime is {{$todayLateMark[2]}}</small>
                                                            <div class="clearfix"></div>
                                                            <small class="text-muted fs-14">Late time {{$todayLateMark[1]}} min</small>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                        @if(isset($application))
                                            <tr class="border-bottom">
                                                <td class="d-flex pl-6">
                                                    <span class="bg-success warning-border brround d-block mr-5 mt-1 h-5 w-5"></span>
                                                    <div class="my-auto">
                                                        <span class="mb-1 font-weight-semibold fs-17"><a href="/empApplications">{{($application->type == 1)?'AGF Application':(($application->type == 2)?'Exit Pass':'Leave Application')}}</a></span>
                                                        <div class="clearfix"></div>
                                                        @if($application->type == 1 || $application->type == 2)
                                                            <small class="text-muted fs-14">{{date('d M Y', strtotime($application->startDate))}}</small>
                                                        @else    
                                                            <small class="text-muted fs-14">{{date('d M Y', strtotime($application->startDate))}} To {{date('d M Y', strtotime($application->endDate))}}</small>
                                                        @endif
                                                        <div class="clearfix"></div>
                                                        <small class="text-muted fs-14">{{($application->status == 1)?'Approved By':(($application->status == 3)?'Rejected By':'Forwarded to Senior')}} {{$application->approvedBy}} at {{date('d M Y, H:i A', strtotime($application->updated_at))}}</small>
                                                    </div>
                                                </td>
                                                <td class="text-right pr-6">
                                                    <a class="text-muted d-block fs-16" href="#">{{$util->getTimeDiff($application->updated_at)}} ago</a>
                                                </td>
                                            </tr>
                                        @endif
                                        @if(isset($forms))
                                            <tr class="border-bottom">
                                                <td class="d-flex pl-6">
                                                    <span class="bg-warning warning-border brround d-block mr-5 mt-1 h-5 w-5"></span>
                                                    <div class="my-auto">
                                                        <span class="mb-1 font-weight-semibold fs-17"><a href="/formsCirculars/employeeList">{{$forms->name}} Circular</a></span>
                                                        <div class="clearfix"></div>
                                                        <small class="text-muted fs-14">Circular No: {{$forms->circularNo}}</small>
                                                        <div class="clearfix"></div>
                                                        <small class="text-muted fs-14">{{date('d M Y', strtotime($forms->updated_at))}}</small>
                                                    </div>
                                                </td>
                                                <td class="text-right pr-6">
                                                    <a class="text-muted d-block fs-16" href="#">{{$util->getTimeDiff($forms->updated_at)}} ago</a>
                                                </td>
                                            </tr>
                                        @endif
                                        @if(isset($attendanceNoti))
                                            @if($attendanceNoti)
                                                <tr class="border-bottom">
                                                    <td class="d-flex pl-6">
                                                        <span class="bg-pink pink-border brround d-block mr-5 mt-1 h-5 w-5"></span>
                                                        <div class="my-auto">
                                                            <span class="mb-1 font-weight-semibold fs-17">{{date('M-Y', strtotime($attendanceNoti->month))}} Attendance</span>
                                                            <div class="clearfix"></div>
                                                            <small class="fs-14">Attendance Confirm By HR Dept.</small>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> 
            @else
                <div class="col-xl-3 col-md-12 col-lg-12">
                    <div class="card mg-b-20 card--events">
                        <div class="card-header border-bottom-0">
                            <div class="card-title">Birthday Wishes</div>
                        </div>
                        <div class="card-body p-0">
                            <div class="">
                                <ul class="list-group mb-0">
                                    @if(isset($wishes))
                                     <marquee direction="up" class="marquee">
                                        @foreach($wishes as $wish)
                                            <li class="list-group-item d-flex border-top-0 border-left-0 border-right-0">
                                                <div class="w-3 h-3 bg-primary mr-3 mt-1 brround"></div>
                                                <div>
                                                    <h5>
                                                        {{$wish->name}}
                                                    </h5>
                                                    <p class="mb-0 fs-12"><strong>{{$wish->designationName}}</strong>, {{$birthday->departmentName}}</p>
                                                </div>
                                                &nbsp;&nbsp;
                                                <div class="text-right mr-10">
                                                    <a class="btn btn-outline-orange mt-5" href="#"><i class="fa fa-birthday-cake mr-2"></i>HBD</a>
                                                </div>
                                            </li>
                                        @endforeach
                                        </marquee>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>       
	</div>
</div>
@endsection
