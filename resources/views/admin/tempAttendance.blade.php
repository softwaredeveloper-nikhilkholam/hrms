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
                    <div class="card-body" style="font-size:10px !important;">
                        @if(isset($attendances))
                            <div class="table-responsive hr-attlist mt-3">
                                <table class="table table-vcenter text-nowrap table-bordered border-top border-bottom" id="">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Code-Name</th>
                                            <th>Designation</th>
                                            <th>Timings</th>
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
                                        <?php $k=0;$no=1; ?>
                                        @if(count($attendances))
                                            @foreach($attendances as $key => $attend)
                                                @if($k==0)
                                                    <input type="hidden" name="empId[]" value="{{$attend->empId}}">
                                                    <?php $sandwichPol=0;$deduction=0;$wLeave=0;$sandwitchFlag=0;$tempDayStatus=0; ?>
                                                    @if($attend->salaryHoldRelease == 1 || $attend->lastDate != null)
                                                        <tr style="background-color:#f6b0b0;">
                                                    @else
                                                        <tr>
                                                    @endif
                                                    <td style="position:sticky;left:-1px;background: white;z-index: 10;" class="text-center">{{$no++}}</td>
                                                    <td><h6 style="font-size:12px;color:green;" class="mt-2 fs-14">{{$attend->empCode}} - {{$attend->name}}</h6></td>
                                                    
                                                    <td width="7%">
                                                        <?php  $totDays=$lateMark=$extraW=$sandTp=$wfh=0; ?>
                                                        <a href="/employees/{{$attend->empId}}" target="_blank">    
                                                            <h6 style="font-size:12px;" class="mt-2 fs-14">{{$attend->designationName}}[{{$attend->branchName}}]</h6>
                                                        </a>
                                                    </td>
                                                    <td><h6 style="font-size:12px;color:red;" class="mt-2 fs-14"><b>{{($attend->startTime != '')?(date('H:i', strtotime($attend->startTime))." To ".date('H:i', strtotime($attend->endTime))):"NA"}}</h6></td>
                                                @endif
                                                <td class="text-center">
                                                    {{$attend->dayStatus}}
                                                </td>
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
