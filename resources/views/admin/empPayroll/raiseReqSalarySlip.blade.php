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
                    <h4 class="page-title">Generate Salary Slip</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                        </div>
                    </div>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                 <style>
      .payslip-table {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border: 2px solid #000; /* Added main table border */
        }
        .payslip-table th, .payslip-table td {
            border: 2px solid #000; /* Updated border thickness */
            padding: 8px;
            text-align: left;
        }
    </style>
                <div class="col-xl-2 col-md-2 col-lg-2"></div>
                <div class="col-xl-8 col-md-8 col-lg-8">
                    <div class="card">
                        <div class="card-header border-0">
                            <h4>Generate Salary Slip</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['action' => 'admin\EmpAttendancesController@raiseReqSalarySlip', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="month" name="month" class="form-control" value="{{(isset($month))?$month:''}}" max="{{date('Y-m', strtotime('-1 month'))}}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="submit" value="Generate" class="btn btn-primary">
                                    </div>
                                </div>
                            {!! Form::close() !!} 
                            @if(isset($month))
                                @if($salDet && $empDet)
                                
                                    <table class="mt-4 payslip-table" width="100%">
                                        <tr border="2">
                                            <td style="padding:3px;" width="30%" border="2" rowspan=2 colspan="2"><img src="https://hrms.aaryansworld.com/landingpage/images/logo.png"></td>
                                            <td style="padding:3px;" width="70%" border="2" class="text-center" colspan="3">
                                                <h5>{{$empDet->organisationName}}</h5>
                                                <b>Aaryans World School</b><br>
                                                Aaryans World School Corporate Office., Above Hotel Shree Kateel<br>
                                                New Pune – Satara bypass highway, Near Navle bridge, Narhe, Pune – 411041.<br>
                                            </td>
                                        </tr> 
                                        <tr>
                                            <td   style="padding:3px;background-color:black; color:white;text-align:center;" colspan="3"><b>Payslip for the month of June 2023</b></td>
                                        </tr>
                                    </table>
                                    <table class="payslip-table" width="100%">
                                        <tr>
                                            <td><span style="text-align:right;">Name</span><span  style="text-align:left;">Vikas Ganpat Devgirikar</span></td>
                                            <td>Vikas Ganpat Devgirikar</td>
                                            <td>Bank Name:</td>
                                            <td>ICICI Bank</td>
                                        </tr>
                                        <tr>
                                            <td>Join Date: </td>
                                            <td>11-Mar-19</td>
                                            <td>Bank Account No.: </td>
                                            <td>777701530153</td>
                                        </tr>
                                    </table>
                                @else
                                    <hr>
                                    <h4 style="color:red;">Selected month Salary not found</h4>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-2 col-lg-2"></div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection