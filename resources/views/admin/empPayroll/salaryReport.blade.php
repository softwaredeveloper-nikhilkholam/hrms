<?php 
use App\Helpers\Utility;
$util = new Utility();
$empCode = session()->get('empCode');
$salary = Session()->get('salary');
?>
@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Salary Report</h4>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <h4>Salary Report</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table  table-vcenter text-nowrap table-bordered border-bottom border-top" id="hr-payroll">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">Month-Year</th>
                                            <th class="border-bottom-0">(Days) Present Days</th>
                                            <th class="border-bottom-0">(Rs) Present Days</th>
                                            <th class="border-bottom-0">(Days) Absent Days</th>
                                            <th class="border-bottom-0">(Rs) Absent Days</th>
                                            <th class="border-bottom-0">(Rs) Earn Salary</th>
                                            <th class="border-bottom-0">(Rs) Gross Salary</th>
                                            <th class="border-bottom-0">Generate Slip</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $perDay=0; ?>
                                        @foreach($attendances as $attend)
                                            <tr>
                                                <?php $temp = $util->getMonthlyEmpAttendance($empCode, ($attend->month.'-'.$attend->year));
                                                    $days = date('t', strtotime($attend->month.'-'.$attend->year));
                                                    $perDay = round(($salary / $days),2); 
                                                    $absentD = $totPresent = 0;
                                                ?>
                                                <td>{{$attend->month}} - {{$attend->year}}</td>
                                                <td class="font-weight-semibold text-right">{{$totPresent = ($temp[0]+$temp[5]+($temp[4]/2))}}</td>
                                                <td class="font-weight-semibold text-right">Rs. {{$util->numberFormat($totPresent*$perDay)}}</td>
                                                <td class="font-weight-semibold text-right">{{$absentD = $days-$totPresent}}</td>
                                                <td class="font-weight-semibold text-right">Rs. {{$util->numberFormat($absentD*$perDay)}}</td>
                                                <td class="font-weight-semibold text-right">Rs. {{$util->numberFormat($totPresent*$perDay)}}</td>
                                                <td class="font-weight-semibold text-right">Rs. {{$util->numberFormat($salary)}}</td>
                                                <td>-</td>
                                                <td><span class="badge badge-success">Paid</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
