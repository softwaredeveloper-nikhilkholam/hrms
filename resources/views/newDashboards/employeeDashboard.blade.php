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
                    {{$employee->empCode}}- {{$name}}</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                     <b style="font-size:20px;" class="page-title">Reporting: <b style="color:red;">[ {{$repoName}} ]</b> 
                     @if($section == 'Teaching')
                        <a href="/admin/letterHeads/EmploymentContractAgreementTeaching.pdf" target="_blank"><img src="admin/646.jpg" height="50" width="50"></a>
                    @endif
                <br>
                <b>Today's Punch-In Time: {{($punchTime == 0)?'-':date('h:i A', strtotime($punchTime))}}</b>
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
        </div>

        <div class="row"> 
            <div class="col-xl-4 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header border-0">
                        <h4 class="card-title">{{date('F-Y')}} (Till {{date('d-m-Y', strtotime('-1 day'))}} Attendance)</h4>
                    </div>
                    <div class="pt-4">
                        <div class="table-responsive">
                            <table class="table transaction-table mb-0 text-nowrap">
                                <tbody>
                                    <tr class="border-bottom">
                                        <td class="d-flex pl-6">
                                            <span class="bg-blue pink-border brround d-block mr-5 mt-1 h-5 w-5"></span>
                                            <div class="my-auto">
                                                <span class="mb-1 font-weight-semibold fs-17">Number Of Days</span>
                                            </div>
                                        </td>
                                        <td class="text-right pr-6">
                                            <a class="d-block fs-16" href="#" style="color:#083dc1;">{{$mDay = date('d',strtotime('-1 day'))}} Days</a>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="d-flex pl-6">
                                            <span class="bg-green pink-border brround d-block mr-5 h-5 w-5"></span>
                                            <div class="my-auto">
                                                <span class="mb-1 font-weight-semibold fs-17">Present Days</span>
                                            </div>
                                        </td>
                                        <td class="text-right pr-6">
                                            <a class="d-block fs-16" href="#" style="color:green;">{{$presentDays}} Days</a>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="d-flex pl-6">
                                            <span class="bg-yellow pink-border brround d-block mr-5 mt-1 h-5 w-5"></span>
                                            <div class="my-auto">
                                                <span class="mb-1 font-weight-semibold fs-17">Week off</span>
                                            </div>
                                        </td>
                                        <td class="text-right pr-6">
                                            <a class="d-block fs-16" href="#" style="color:orange;">{{$weekoff}} Days</a>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="d-flex pl-6">
                                            <span class="bg-pink pink-border brround d-block mr-5 mt-1 h-5 w-5"></span>
                                            <div class="my-auto">
                                                <span class="mb-1 font-weight-semibold fs-17">Extra Working</span>
                                            </div>
                                        </td>
                                        <td class="text-right pr-6">
                                            <a class="d-block fs-16" href="#" style="color:black;">{{$extraDays}} Days</a>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="d-flex pl-6">
                                            <span class="bg-red pink-border brround d-block mr-5 mt-1 h-5 w-5"></span>
                                            <div class="my-auto">
                                                <span class="mb-1 font-weight-semibold fs-17">Absent Days</span>
                                            </div>
                                        </td>
                                        <td class="text-right pr-6">
                                            <a class="d-block fs-16" href="#" style="color:red;">{{$absentDays}} Days</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>    
            </div>    
            <div class="col-xl-4 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header border-0">
                        <h4 class="card-title">{{date('F-Y')}} (Till Date Salary Calculation)</h4>
                    </div>
                    <div class="pt-4">
                        <div class="table-responsive">
                            <table class="table transaction-table mb-0 text-nowrap">
                                <tbody>
                                    <tr class="border-bottom">
                                        <td class="d-flex pl-6">
                                            <span class="bg-blue pink-border brround d-block mr-5 mt-1 h-5 w-5"></span>
                                            <div class="my-auto">
                                                <span class="mb-1 font-weight-semibold fs-17">Per Days (RS.)</span>
                                            </div>
                                        </td>
                                        <?php $mDay = date('d',strtotime('-1 day'));
                                              $perDay = $salary/date('t'); 
                                        ?>
                                        <td class="text-right pr-6">
                                            <a class="d-block fs-16" href="#" style="color:#083dc1;">Rs. {{$util->numberFormat($perDay)}}</a>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="d-flex pl-6">
                                            <span class="bg-green pink-border brround d-block mr-5 h-5 w-5"></span>
                                            <div class="my-auto">
                                                <span class="mb-1 font-weight-semibold fs-17">Present Days (RS.)</span>
                                            </div>
                                        </td>
                                        <td class="text-right pr-6">
                                            <a class="d-block fs-16" href="#" style="color:green;">Rs. {{$util->numberFormat($perDay*$presentDays)}}</a>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="d-flex pl-6">
                                            <span class="bg-yellow pink-border brround d-block mr-5 mt-1 h-5 w-5"></span>
                                            <div class="my-auto">
                                                <span class="mb-1 font-weight-semibold fs-17">Week off (RS.)</span>
                                            </div>
                                        </td>
                                        <td class="text-right pr-6">
                                            <a class="d-block fs-16" href="#" style="color:orange;">Rs. {{$util->numberFormat($perDay*$weekoff)}}</a>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="d-flex pl-6">
                                            <span class="bg-pink pink-border brround d-block mr-5 mt-1 h-5 w-5"></span>
                                            <div class="my-auto">
                                                <span class="mb-1 font-weight-semibold fs-17">Extra Working (RS.)</span>
                                            </div>
                                        </td>
                                        <td class="text-right pr-6">
                                            <a class="d-block fs-16" href="#" style="color:black;">Rs. {{$util->numberFormat($perDay*$extraDays)}}</a>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="d-flex pl-6">
                                            <span class="bg-red pink-border brround d-block mr-5 mt-1 h-5 w-5"></span>
                                            <div class="my-auto">
                                                <span class="mb-1 font-weight-semibold fs-17">Absent Days (RS.)</span>
                                            </div>
                                        </td>
                                        <td class="text-right pr-6">
                                            <a class="d-block fs-16" href="#" style="color:red;">Rs. {{$util->numberFormat($perDay*$absentDays)}}</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>    
            </div> 
            <div class="col-xl-4 col-md-12 col-lg-12">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12">
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
                    <div class="col-xl-6 col-lg-6 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-9">
                                        <div class="mt-0 text-left"> <h5 class="">{{($language == 1)?'Retention':'पगार'}}</h5>
                                            <!-- <h5 class="mb-0 mt-auto text-danger showRetention" style="cursor: -webkit-grab; cursor: grab;">Show</h5> -->
                                            <!-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Show</button> -->
                                            <h5 data-toggle="modal" data-target="#myModal" style="cursor: -webkit-grab; cursor: grab;color:red;">Show</h5>
                                                <!-- Modal -->
                                                <div id="myModal" class="modal fade" role="dialog">
                                                    <div class="modal-dialog">

                                                        <!-- Modal content-->
                                                        <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h4 class="modal-title">Retention Details</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No.</th>
                                                                        <th>Month</th>
                                                                        <th>Amount<?php $i=2;$total=0; ?></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if(isset($oldRetention))
                                                                        <tr>
                                                                            <td style="font-size:14px;">1</td>
                                                                            <td style="font-size:14px;">Old Retention</td>
                                                                            <td style="font-size:14px;">{{($oldRetention)?$util->numberFormat($oldRetention->retentionAmount):0}}
                                                                                <?php $total = ($oldRetention)?$oldRetention->retentionAmount:0; ?>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    @if(count($retention))
                                                                        @foreach($retention as $temp)
                                                                            <tr>
                                                                                <td style="font-size:14px;">{{$i++}}</td>
                                                                                <td style="font-size:14px;">{{date('M-Y', strtotime($temp->month))}}</td>
                                                                                <td style="font-size:14px;">{{$util->numberFormat($temp->retentionAmount)}}
                                                                                    <?php $total = $total + $temp->retentionAmount; ?>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @endif
                                                                </tbody>
                                                                <thead>
                                                                    <tr>
                                                                        <th colspan="2">Total</th>
                                                                        <th style="color:red;">{{$util->numberFormat($total)}}</th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                        </div>

                                                    </div>
                                                </div>
                                           
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="icon1 bg-danger my-auto  float-right"><i style="font-size:24px" class="fa">&#xf0a6;</i></div>
                                    </div>                                                                        
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>   
            </div>   
        </div>
        

        <div class="row">
            <div class="col-xl-4 col-md-12 col-lg-12">
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
            <div clas="col-xl-4 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header border-0">
                        <h4 class="card-title">Up Coming Birthday</h4>
                    </div>
                    <div class="pt-4">
                        @if(isset($birthdays))
                            <div class="table-responsive">
                                <table class="table transaction-table mb-0 text-nowrap" width="100%">
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
            <div class="col-xl-4 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header border-0">
                        <h4 class="card-title">{{($language == 1)?'Notice Board':'नोटीस बोर्ड'}}</h4>
                    </div>
                    <div class="pt-4">
                        <div class="table-responsive">
                            <table class="table transaction-table mb-0 text-nowrap">
                                <tbody style="overflow-y:scroll; height:10px;">
                                    @foreach($notices as $notice)
                                        <tr class="border-bottom">
                                            <td class="d-flex pl-6">
                                                <span class="bg-pink pink-border brround d-block mr-5 mt-1 h-5 w-5"></span>
                                                <div class="my-auto">
                                                    <span class="mb-1 font-weight-semibold fs-17">{{$notice->title}}</span>
                                                    <div class="clearfix"></div>
                                                    <small class="text-muted fs-14">{{$notice->description}}</small>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> 
        </div>

	</div>
</div>

@endsection
