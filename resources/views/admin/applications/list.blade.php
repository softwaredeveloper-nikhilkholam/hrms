<?php $language = Auth::user()->language; 
$transAllowed = Auth::user()->transAllowed; 

?>
@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Applications</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle"  data-toggle="dropdown"><i class="fa fa-newspaper-o" aria-hidden="true"></i>&nbsp;&nbsp;Apply Application
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu">
                                <a class="dropdown-item" href="/empApplications/applyApplication/1">AGF</a>
                                <a class="dropdown-item" href="/empApplications/applyApplication/2">Exit Pass</a>
                                <a class="dropdown-item" href="/empApplications/applyApplication/3">Leave</a>
                                @if($transAllowed == 1)
                                    <a class="dropdown-item" href="/empApplications/applyApplication/4">Travelling Allowance</a>
                                @endif
                            </div>
                        </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="tab-menu-heading hremp-tabs p-0 ">
                        <div class="tabs-menu1">
                            <ul class="nav panel-tabs">
                                <li class="ml-4"><a href="#tab1" class="active" data-toggle="tab">AGF Applications&nbsp;&nbsp;<b style="color:Red;">{{$agfsCt}}</b></a></li>
                                <li><a href="#tab2" data-toggle="tab">Leave Applications&nbsp;&nbsp;<b style="color:Red;">{{$leavesCt}}</b></a></li>
                                <li><a href="#tab3" data-toggle="tab">Exit Pass Applications&nbsp;&nbsp;<b style="color:Red;">{{$exitPassesCt}}</b></a></li>
                                @if($transAllowed == 1)
                                    <li><a href="#tab4" data-toggle="tab">Travelling Allownces Applications&nbsp;&nbsp;<b style="color:Red;">{{$travellsCt}}</b></a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <div class="card-body">
                                    @if(isset($agfs))
                                        @if(count($agfs) >= 1)
                                            <div class="table-responsive">
                                                <table width="100%" class="table table-striped card-table table-vcenter text-nowrap mb-0 " id="example">
                                                    <thead class="bg-primary text-white">
                                                        <tr>
                                                            <th class="text-white w-7">#</th>
                                                            <th class="text-white w-7">{{($language == 1)?'Date': 'दिनांक'}}</th>
                                                            <th class="text-white w-12">{{($language == 1)?'Issue In Brief': 'एप्लीकेशन टाईप'}}</th>
                                                            <th class="text-white w-10">{{($language == 1)?'Reporting Auth': 'स्टेटस'}}</th>
                                                            <th class="text-white w-10">{{($language == 1)?'HR Dept.': 'स्टेटस'}}</th>
                                                            <th class="text-white w-10">{{($language == 1)?'Accounts Dept.': 'स्टेटस'}}</th>
                                                            <th class="text-white w-7">{{($language == 1)?'Actions': 'ॲक्शन'}}<?php $i=1; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($agfs as $app)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>{{date('d-m-Y', strtotime($app->startDate))}}</td>
                                                                <td>{{$app->reason}}</td>
                                                                <td style="color:{{($app->status1 == 0)?'purple':(($app->status1 == 1)?'green':'red')}}"><b>{{($app->status1 == 0)?'Pending':(($app->status1 == 1)?'Approved':'Rejected')}}</b></td>
                                                                <td style="color:{{($app->status2 == 0)?'purple':(($app->status2 == 1)?'green':'red')}}"><b>{{($app->status2 == 0)?'Pending':(($app->status2 == 1)?'Approved':'Rejected')}}</b></td>
                                                                <td style="color:{{($app->status == 0)?'purple':(($app->status == 1)?'green':'red')}}"><b>{{($app->status == 0)?'Pending':(($app->status == 1)?'Approved':'Rejected')}}</b></td>
                                                                <td>
                                                                    @if($app->status == 0)
                                                                        <a href="/empApplications/{{$app->id}}/edit" class="btn btn-primary btn-icon btn-sm">
                                                                            <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                                        </a>
                                                                        <a href="/empApplications/{{$app->id}}/deactivate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                                            <i class="fa fa-times-circle-o" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Deactivate"></i>
                                                                        </a>
                                                                    @endif
                                                                    <a href="/empApplications/{{$app->id}}" class="btn btn-success btn-icon btn-sm">
                                                                        <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <h4 style="color:red;">Not Found Active Records.</h4>
                                        @endif
                                    @endif
                                </div>
                            </div>   
                            <div class="tab-pane" id="tab2">
                                <div class="card-body">
                                @if(isset($leaves))
                                    @if(count($leaves) >= 1)
                                        <div class="table-responsive">
                                                <table class="table table-striped card-table table-vcenter text-nowrap mb-0 " id="example1">
                                                    <thead class="bg-primary text-white">
                                                        <tr>
                                                            <th class="text-white w-5">#</th>
                                                            <th class="text-white">{{($language == 1)?'Date': 'दिनांक'}}</th>
                                                            <th class="text-white">{{($language == 1)?'Application Type': 'एप्लीकेशन टाईप'}}</th>
                                                            <th class="text-white">{{($language == 1)?'Status': 'स्टेटस'}}</th>
                                                            <th class="text-white">{{($language == 1)?'Updated At': 'अपडेटेड टाईम'}}</th>
                                                            <th class="text-white">{{($language == 1)?'Updated By': 'अपडेटेड बाय'}}</th>
                                                            <th class="text-white w-15">{{($language == 1)?'Actions': 'ॲक्शन'}}<?php $i=1; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($leaves as $app)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>{{date('d-m-Y', strtotime($app->startDate))}}</td>
                                                                <td>{{(($app->type == 1)?'AGF Application':(($app->type == 2)?'Exit Pass Application':'Leave Application'))}}</td>
                                                                <td style="color:{{($app->status == 0)?'purple':(($app->status == 1)?'green':(($app->status == 2)?'orange':'red'))}};">
                                                                    <b>{{($app->status == 0)?'Pending':(($app->status == 1)?'Approved':(($app->status == 2)?'Forwarded to Senior':'Rejected'))}}</b></td>
                                                                <td>{{date('d-m-Y h:i A', strtotime($app->updated_at))}}</td>
                                                                <td>{{$app->updated_by}}</td>
                                                                <td>
                                                                    @if($app->status == 0)
                                                                    <a href="/empApplications/{{$app->id}}/edit" class="btn btn-primary btn-icon btn-sm">
                                                                        <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                                    </a>
                                                                    <a href="/empApplications/{{$app->id}}/deactivate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                                        <i class="fa fa-times-circle-o" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Deactivate"></i>
                                                                    </a>
                                                                    @endif
                                                                    <a href="/empApplications/{{$app->id}}" class="btn btn-success btn-icon btn-sm">
                                                                        <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                    @else
                                        <h4 style="color:red;">Not Found Active Records.</h4>
                                    @endif
                                @endif
                                </div>
                            </div>   
                            <div class="tab-pane" id="tab3">
                                <div class="card-body">
                                @if(isset($exitPasses))
                                        @if(count($exitPasses) >= 1)
                                            <div class="table-responsive">
                                                <table class="table table-striped card-table table-vcenter text-nowrap mb-0 " id="example2">
                                                    <thead class="bg-primary text-white">
                                                        <tr>
                                                            <th class="text-white w-5">#</th>
                                                            <th class="text-white">{{($language == 1)?'Date': 'दिनांक'}}</th>
                                                            <th class="text-white">{{($language == 1)?'Status': 'स्टेटस'}}</th>
                                                            <th class="text-white">{{($language == 1)?'Updated At': 'अपडेटेड टाईम'}}</th>
                                                            <th class="text-white">{{($language == 1)?'Updated By': 'अपडेटेड बाय'}}</th>
                                                            <th class="text-white w-15">{{($language == 1)?'Actions': 'ॲक्शन'}}<?php $i=1; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($exitPasses as $app)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>{{date('d-m-Y', strtotime($app->startDate))}}</td>
                                                                <td style="color:{{($app->status == 0)?'purple':(($app->status == 1)?'green':(($app->status == 2)?'orange':'red'))}};">
                                                                    <b>{{($app->status == 0)?'Pending':(($app->status == 1)?'Approved':(($app->status == 2)?'Forwarded to Senior':'Rejected'))}}</b></td>
                                                                <td>{{date('d-m-Y h:i A', strtotime($app->updated_at))}}</td>
                                                                <td>{{$app->updated_by}}</td>
                                                                <td>
                                                                    @if($app->status == 0)
                                                                    <a href="/empApplications/{{$app->id}}/edit" class="btn btn-primary btn-icon btn-sm">
                                                                        <i class="fa fa-edit" style="font-size:20px;" data-toggle="tooltip" data-original-title="Edit"></i>
                                                                    </a>
                                                                    <a href="/empApplications/{{$app->id}}/deactivate" class="btn btn-danger btn-icon btn-sm" onclick="return confirm('Are you sure?')">
                                                                        <i class="fa fa-times-circle-o" style="font-size:20px;" aria-hidden="true"data-toggle="tooltip" data-original-title="Deactivate"></i>
                                                                    </a>
                                                                    @endif
                                                                    <a href="/empApplications/{{$app->id}}" class="btn btn-success btn-icon btn-sm">
                                                                        <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <h4 style="color:red;">Not Found Active Records.</h4>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane" id="tab4">
                                <div class="card-body">
                                    @if(isset($travells))
                                        @if(count($travells) >= 1)
                                            <div class="table-responsive">
                                                <table class="table table-striped card-table table-vcenter text-nowrap mb-0 " id="example3">
                                                    <thead class="bg-danger text-white">
                                                        <tr>
                                                            <th class="text-white w-5">#</th>
                                                            <th class="text-white">Month-Year</th>
                                                            <th class="text-white w-20">Name</th>
                                                            <th class="text-white w-20">Dept. Name</th>
                                                            <th class="text-white w-20">Designation Name</th>
                                                            <th class="text-white w-20">Pending</th>
                                                            <th class="text-white w-10">Actions<?php $i=1; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($travells as $app)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>{{$app->forDate}}</td>
                                                                <td>{{$app->empName}}</td>
                                                                <td>{{$app->departmentName}}</td>
                                                                <td>{{$app->designationName}}</td>
                                                                <td>{{$app->pendingCt}}</td>
                                                                <td>
                                                                    <a href="/empApplications/{{$app->empId}}/{{$app->forDateYear.'-'.$app->forDateMonth}}/4/viewMore" class="btn btn-success btn-icon btn-sm">
                                                                        <i class="fa fa-eye" style="font-size:20px;" data-toggle="tooltip" data-original-title="show"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <hr>
                                            <h4 style="color:red;">Record not Found.</h4>
                                        @endif
                                    @endif
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
