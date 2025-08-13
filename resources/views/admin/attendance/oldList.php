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
	<div class="container">                        
		<!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title">{{($language == 1)?'Attendance': 'अटेंडन्स'}}</h4>
            </div> 
        </div>
        <!--End Page header-->
        <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::open(['action' => 'admin\EmpAttendancesController@search', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                            <div class="row mt-5">
                                @if($userType != '61')
                                    <div class="col-md-2 col-lg-2">
                                        <div class="form-group">
                                            <label class="form-label">Employee Code:</label>
                                            <input type="text" class="form-control" placeholder="Employee Code" value="{{(isset($empCode))?$empCode:''}}" name="empCode">
                                        </div>
                                    </div>
                                @endif
                                @if($userType == '61')
                                <div class="col-md-3 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Organisation:</label>
                                        {{Form::select('organisation', ['1'=>'Ellora Medicals and Educational foundation', '2'=>'Snayraa Agency', '3'=>'Tejasha Educational and research foundation'], ((isset($organisation))?$organisation:null), ['placeholder'=>'Select Organisation','class'=>'form-control'])}}
                                    </div>
                                </div>
                                @endif 
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label">Branches:</label>
                                        {{Form::select('branchId', $branches, ((isset($branchId))?$branchId:null), ['placeholder'=>'Select Branch','class'=>'form-control custom-select select2'])}}
                                    </div>
                                </div>
                                @if($userType != '61')
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label">Department:</label>
                                        {{Form::select('departmentId', $departments, ((isset($departmentId))?$departmentId:null), ['placeholder'=>'Select Department','class'=>'form-control custom-select select2'])}}
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Month:</label>
                                        <input type="month" class="form-control" value="{{(isset($month))?$month:''}}" name="month" required>
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
                    <div class="card-body">
                        @if(isset($attendances))
                            <div class="d-flex mb-3 mt-0">
                                <div class="mr-3">
                                    <label class="form-label">Note:</label>
                                </div>
                                <div>
                                    <span class="badge badge-success-light mr-2"><i class="feather feather-check-circle text-success"></i> ---> Present</span>
                                    <span class="badge badge-danger-light mr-2"><i class="feather feather-check-circle text-danger"></i> ---> Present but Late Mark</span>
                                    <span class="badge badge-danger-light mr-2"><i class="feather feather-x-circle text-danger"></i> ---> Absent</span>
                                    <span class="badge badge-warning-light mr-2"><i class="fa fa-star text-warning"></i> ---> Holiday</span>
                                    <span class="badge badge-orange-light mr-2"><i class="fa fa-adjust text-orange"></i>  ---> Half Day</span>
                                    <span class="badge badge-primary-light mr-2"><i class="fa fa-star text-primary"></i>  ---> Office timing not Set</span>
                                </div>
                            </div>
                            <style>
                                table thead th:first-child {
                                    position: sticky;
                                    left: 0;
                                    z-index: 2;
                                    background: white;
                                    }
                                table tbody th {
                                    position: sticky;
                                    left: 0;
                                    background: white;
                                    z-index: 1;
                                    }
                            </style>
                            <div class="table-responsive hr-attlist">
                                <table class="table  table-vcenter text-nowrap table-bordered border-top" id="hr-attendance">
                                    <thead>
                                        <tr>
                                            <td>Employee Name</td>
                                            <?php $month = date('Y-m', strtotime($startDate)); ?>
                                            @for($k=1; $k<=$days; $k++)
                                                <th class="border-bottom-0 w-5">{{$k}}
                                                    <br><b style="font-size:12px;">{{date('D',strtotime($month.'-'.$k))}}</b>
                                                </th>
                                            @endfor
                                            <th>Total Present</th>
                                            <th>Total Extra Working</th>
                                            <th>Total Days</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $k=0; ?>
                                        @foreach($attendances as $key => $attend)
                                            @if($k==0)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex">
                                                            <div class="mr-3 mt-0 mt-sm-2 d-block">
                                                                <h6  style="font-size:16px;" class="mb-1 fs-14"><b style="color:red;">
                                                                @if($attend->firmType == 1)
                                                                    {{$attend->empCode}}
                                                                @elseif($attend->firmType == 2)
                                                                    AFF{{$attend->empCode}}
                                                                @else
                                                                    AFS{{$attend->empCode}}
                                                                @endif
                                                                </b> - <b>{{$attend->name}}</b> <b style="color:purple;">[{{$attend->startTime}} To {{$attend->endTime}}]</b>
                                                                @if($attend->officeTimeStatus == 1)
                                                                    <span class="fa fa-star text-primary" data-toggle="tooltip" data-placement="top" title="Office timing not Set"></span>
                                                                @endif
                                                                <?php  $totDays=$lateMark=$extraW=0; ?>
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                            @endif
                                            <td>
                                                <?php 
                                                    $lateMark=$lateMark+$attend->lateMarkDay; 
                                                    if($lateMark == 3)
                                                    {
                                                        $totDays=$totDays-1;
                                                        $lateMark=0;
                                                    }
                                                ?>
                                                @if($attend->dayStatus == 'WO')
                                                    <?php 
                                                        $sandwichPol=1;
                                                        if(isset($attendances[$key-1]->forDate))
                                                        {
                                                            if($attendances[$key-1]->dayStatus == 'A' || $attendances[$key-1]->dayStatus == '0')
                                                                $sandwichPol++;
                                                        }
                                                        if(isset($attendances[$key-2]->forDate))
                                                        {
                                                            if($attendances[$key-2]->dayStatus == 'A' || $attendances[$key-2]->dayStatus == '0')
                                                                $sandwichPol++;
                                                        }
                                                        if(isset($attendances[$key-3]->forDate))
                                                        {
                                                            if($attendances[$key-3]->dayStatus == 'A' || $attendances[$key-3]->dayStatus == '0')
                                                                $sandwichPol++;
                                                        }
                                                        if(isset($attendances[$key-4]->forDate))
                                                        {
                                                            if($attendances[$key-4]->dayStatus == 'A' || $attendances[$key-4]->dayStatus == '0')
                                                                $sandwichPol++;
                                                        }
                                                        if(isset($attendances[$key-5]->forDate))
                                                        {
                                                            if($attendances[$key-5]->dayStatus == 'A' || $attendances[$key-5]->dayStatus == '0')
                                                                $sandwichPol++;
                                                        }
                                                        if(isset($attendances[$key-6]->forDate))
                                                        {
                                                            if($attendances[$key-6]->dayStatus == 'A' || $attendances[$key-6]->dayStatus == '0')
                                                                $sandwichPol++;
                                                        }

                                                        if($sandwichPol == 3)
                                                        {
                                                            $totDays=$totDays+0.5;
                                                            ?>
                                                                <span class="badge badge-orange-light">  
                                                                <span class="fa fa-adjust text-orange"></span></span>
                                                            <?php
                                                        }
                                                        if($sandwichPol > 3)
                                                        {
                                                            ?>
                                                                <span class="badge badge-danger-light">  
                                                                <span class="feather feather-x-circle text-danger "  data-toggle="tooltip" data-placement="top" title="Absent"></span></span>
                                                            <?php
                                                        }

                                                        if($sandwichPol <= 2)
                                                        {
                                                            $totDays=$totDays+1;
                                                            ?>
                                                                <span class="badge badge-warning-light">  
                                                                <span class="fa fa-star text-warning" data-toggle="tooltip" data-placement="top" title="Sunday/Holiday with Extra Work"></span></span>
                                                            <?php
                                                        }

                                                        $extraW=$extraW+$attend->extraWorkingDay; 
                                                    ?>
                                                @elseif($attend->dayStatus == 'WOP')
                                                    <div class="hr-listd">
                                                        <a href="#" data-toggle="modal" data-target="#presentmodal{{$attend->id}}" class="hr-listmodal"></a>
                                                        <span class="badge badge-success-light">  
                                                        <span class="fa fa-star text-success" data-toggle="tooltip" data-placement="top" title="Sunday/Holiday with Extra Work"></span></span>
                                                        <?php 
                                                            $totDays=$totDays+1;
                                                            $extraW=$extraW+$attend->extraWorkingDay; 
                                                        ?>
                                                    </div>
                                                @elseif($attend->dayStatus == 'WOPL')
                                                    <div class="hr-listd">
                                                        <a href="#" data-toggle="modal" data-target="#presentmodal{{$attend->id}}" class="hr-listmodal"></a>
                                                        <span class="badge badge-danger-light">  
                                                        <span class="fa fa-star text-danger
                                                        " data-toggle="tooltip" data-placement="top" title="Sunday/Holiday with Extra Work"></span></span>
                                                        <?php 
                                                            $extraW=$extraW+$attend->extraWorkingDay; 
                                                            $totDays=$totDays+1;
                                                        ?>
                                                    </div>
                                                @elseif($attend->dayStatus == 'WOPLH')
                                                    <div class="hr-listd">
                                                        <a href="#" data-toggle="modal" data-target="#presentmodal{{$attend->id}}" class="hr-listmodal"></a>
                                                        <span class="badge badge-orange-light">  
                                                        <span class="fa fa-adjust text-orange"></span></span>
                                                        <?php 
                                                            $extraW=$extraW+$attend->extraWorkingDay; 
                                                            $totDays=$totDays+1;
                                                        ?>
                                                    </div>
                                                @elseif($attend->dayStatus == 'WOPH')
                                                    <div class="hr-listd">
                                                        <a href="#" data-toggle="modal" data-target="#presentmodal{{$attend->id}}" class="hr-listmodal"></a>
                                                        <span class="badge badge-orange-light">  
                                                        <span class="fa fa-adjust text-orange"></span></span>
                                                        <?php 
                                                            $extraW=$extraW+$attend->extraWorkingDay; 
                                                            $totDays=$totDays+1;
                                                        ?>
                                                    </div>
                                                @elseif($attend->dayStatus == 'P')
                                                    <div class="hr-listd">
                                                        <a href="#" data-toggle="modal" data-target="#presentmodal{{$attend->id}}" class="hr-listmodal"></a>
                                                        <span class="badge badge-success-light">
                                                        <span class="feather feather-check-circle text-success"></span></span>
                                                        <?php $totDays=$totDays+1; ?>
                                                    </div>
                                                @elseif($attend->dayStatus == 'PL')
                                                    <div class="hr-listd">
                                                        <a href="#" data-toggle="modal" data-target="#presentmodal{{$attend->id}}" class="hr-listmodal"></a>
                                                        <span class="badge badge-danger-light">
                                                        <span class="feather feather-check-circle text-danger"></span></span>
                                                        <?php 
                                                            $extraW=$extraW+$attend->extraWorkingDay; 
                                                            $totDays=$totDays+1;
                                                        ?>
                                                    </div>
                                                @elseif($attend->dayStatus == 'PLH')
                                                    <div class="hr-listd">
                                                        <a href="#" data-toggle="modal" data-target="#presentmodal{{$attend->id}}" class="hr-listmodal"></a>
                                                        <span class="badge badge-orange-light">  
                                                        <span class="fa fa-adjust text-orange"></span></span>
                                                        <?php 
                                                            $extraW=$extraW+$attend->extraWorkingDay; 
                                                            $totDays=$totDays+1;
                                                        ?>
                                                    </div>
                                                @elseif($attend->dayStatus == 'PH')
                                                    <div class="hr-listd">
                                                        <a href="#" data-toggle="modal" data-target="#presentmodal{{$attend->id}}" class="hr-listmodal"></a>
                                                        <span class="badge badge-orange-light">  
                                                        <span class="fa fa-adjust text-orange"></span></span>
                                                        <?php 
                                                            $extraW=$extraW+$attend->extraWorkingDay; 
                                                            $totDays=$totDays+1;
                                                        ?>
                                                    </div>
                                                @else
                                                    @if($attend->forDate == "2021-01-26" || $attend->forDate == "2021-08-15")
                                                        <?php $totDays=$totDays-3; ?>
                                                    @endif
                                                    <span class="badge badge-danger-light">  
                                                    <span class="feather feather-x-circle text-danger "  data-toggle="tooltip" data-placement="top" title="Absent"></span></span>
                                                @endif
                                                <div class="modal fade" id="presentmodal{{$attend->id}}">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Date: {{date('d-M-Y', strtotime($attend->forDate))}}</h5>
                                                                <a href="#" class="btn btn-outline-primary" data-dismiss="modal">close</a>
                                                            </div>
                                                            <div class="modal-body">
                                                                <center><b style="color:purple;font-size:20px;">{{$attend->name}}</b><center>
                                                                <div class="row mb-5 mt-4">
                                                                    <div class="col-md-4">
                                                                        <div class="pt-5 text-center">
                                                                            <h6 class="mb-1 fs-16 font-weight-semibold">    
                                                                                <b>{{($attend->inTime == "0")?'-':date('H:i', strtotime($attend->inTime))}}</b></b>
                                                                            </h6>
                                                                            <small class="text-muted fs-14">Log In</small>
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
                                                                                    @if(date('Y-m-d') == $attend->forDate)
                                                                                        -
                                                                                    @else
                                                                                        {{($attend->outTime == "0")?'-':date('H:i', strtotime($attend->outTime))}}
                                                                                    @endif
                                                                                    </b>
                                                                            </h6>
                                                                            <small class="text-muted fs-14">Log Out</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                           
                                            <?php $k++; ?>
                                            @if($k==$days)
                                                    <td>{{$totDays}}</td>
                                                    <td>{{$extraW}}</td>
                                                    <td>{{$totDays+$extraW}}</td>
                                                </tr>
                                                <?php $k=0; ?>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row" style="margin-top:15px;">
                                <div class='col-md-8'>{{$attendances->links()}}</div>
                                <div class='col-md-4'>
                                    <a href="/empAttendances/exportExcel/{{$empCode}}/{{$branchId}}/{{$departmentId}}/{{$month}}" class="btn btn-primary"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel <i class="fa fa-download" aria-hidden="true"></i></a>
                                    <a href="/empAttendances/exportPDF/{{$empCode}}/{{$branchId}}/{{$departmentId}}/{{$month}}" class="btn btn-primary"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Export PDF <i class="fa fa-download" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection
