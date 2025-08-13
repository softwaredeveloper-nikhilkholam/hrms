<?php
    use App\Helpers\Utility;
    $util = new Utility();
?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">HR Policy</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                            <a href="/hrPolicies/create" class="btn btn-primary mr-3">Edit Policy</a>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header border-bottom-0">
                                    <h3 class="card-title">View Rules list</h3>
                                </div>
                                <div class="card-body">
                                    {!! Form::open(['action' => 'admin\HrPoliciesController@index', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Month :</label>
                                                <input type="month" class="form-control" name="month"  value="{{$month}}" id="month" placeholder="Month">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Section :</label>
                                                {{Form::select('section', ['Teaching'=>'Teaching', 'Non Teaching'=>'Non Teaching'], $section, ['placeholder'=>'Pick a Option','class'=>'form-control','style'=>'color:red;'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group mt-5">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                            </div>
                                        </div>
                                    </div>                             
                                
                                {!! Form::close() !!}

                                    @if(isset($policies))
                                        @if(count($policies) > 0)
                                            <ul class="list-group">
                                                @foreach($policies as $policy)
                                                    @if($policy->name == 'Rule 1')
                                                        <li class="listorder">
                                                            <b>
                                                                Weekly off( Sunday and Holiday ) Paid for all Employees? 
                                                                <b style="color:red;">
                                                                    @if($policy->temp1 == '')
                                                                        NA 
                                                                    @elseif($policy->temp1 == 'Yes')
                                                                        Yes
                                                                    @else 
                                                                        No 
                                                                    @endif  
                                                                </b>
                                                            </b>
                                                        </li>
                                                    @endif
                                                    @if($policy->name == 'Rule 2')
                                                        <li class="listorder">
                                                            <b>
                                                                If an employee remains absent for 
                                                                <b style="color:red;">{{$policy->temp1}}</b> 
                                                                days of the same week. Only 
                                                                <b style="color:red;">
                                                                    @if($policy->temp2 == '0')
                                                                        NA 
                                                                    @elseif($policy->temp2 == '1')
                                                                        Half Day
                                                                    @else 
                                                                        Full Day
                                                                    @endif  
                                                                </b>
                                                                will be consideredas paid weekly off and half day payment will be deducted.
                                                            </b>
                                                        </li>
                                                    @endif
                                                    @if($policy->name == 'Rule 3')
                                                        <li class="listorder">
                                                            <b>
                                                                All the Employee will have 
                                                                <b style="color:red;">
                                                                @if($policy->temp1 == 1)
                                                                    1st
                                                                @elseif($policy->temp1 == 2)
                                                                    2nd
                                                                @elseif($policy->temp1 == 3)
                                                                    3rd
                                                                @elseif($policy->temp1 >= 4)
                                                                    {{$policy->temp1}}th
                                                                @endif
                                                                </b>
                                                                Saturday as official off.
                                                            </b>
                                                        </li>
                                                    @endif
                                                    @if($policy->name == 'Rule 4')
                                                        <li class="listorder">
                                                            <b>
                                                                A] Only 
                                                                <b style="color:red;">{{$policy->temp1}}</b>
                                                                 min tolerance will be given for reporting timing.
                                                                <br><br>&nbsp;&nbsp;&nbsp;&nbsp;
                                                                B] After 
                                                                <b style="color:red;">{{$policy->temp2}}</b>
                                                                 min of office time, late mark is applicable.
                                                                <br><br>&nbsp;&nbsp;&nbsp;&nbsp;
                                                                C] If 
                                                                <b style="color:red;">{{$policy->temp3}}</b>
                                                                 late marks in a month. 
                                                                <b style="color:red;">{{$policy->temp4}}</b>
                                                                 day salary will be cut,and like wise
                                                                <b style="color:red;">{{$policy->temp5}}</b>
                                                                 late mark 
                                                                <b style="color:red;">{{$policy->temp6}}</b>
                                                                days.
                                                                <br><br>&nbsp;&nbsp;&nbsp;&nbsp;
                                                                D] If you exceed 
                                                                <b style="color:red;">{{$policy->temp7}}</b>
                                                                hour then it will be considered as a 
                                                                <b style="color:red;">
                                                                    @if($policy->temp8 == '' || $policy->temp8 == '0')
                                                                        NA
                                                                    @elseif($policy->temp8 == '1')
                                                                        Half Day
                                                                    @else
                                                                        Full Day
                                                                    @endif                                                                
                                                                </b>
                                                                day
                                                            </b>
                                                        </li>
                                                    @endif
                                                    @if($policy->name == 'Rule 5')
                                                        <li class="listorder">
                                                            <b>
                                                                15th August, and 26th January attendance is compulsory. Failure to attend will lead to deduction of 
                                                                <b style="color:red;">{{$policy->temp1}}</b>
                                                                days.
                                                            </b>
                                                        </li>
                                                    @endif
                                                    @if($policy->name == 'Rule 6')
                                                    <li class="listorder">
                                                        <b>
                                                        Sandwich Leave Policy - If you take a leave on saturday and monday your Sunday will also consider your 3 leave? 
                                                            <b style="color:red;">
                                                            @if($policy->temp1 == '')
                                                                NA  
                                                            @else 
                                                                {{$policy->temp1}}
                                                            @endif
                                                            </b>
                                                        </b>
                                                    </li>
                                                    @endif
                                                @endforeach
                                            </ul>
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
