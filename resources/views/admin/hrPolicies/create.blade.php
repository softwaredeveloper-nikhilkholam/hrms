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
                            <a href="/hrPolicies" class="btn btn-primary mr-3">View Policy</a>
                            
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
                                    <h3 class="card-title">Edit Rules list</h3>
                                </div>
                                <div class="card-body">
                                    {!! Form::open(['action' => 'admin\HrPoliciesController@store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                        
                                        <ul class="list-group">
                                            @if(isset($policies))
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Month :</label>
                                                            <input type="month" class="form-control" name="month"  value="{{date('Y-m', strtotime('+1 month'))}}" min="{{date('Y-m', strtotime('+1 month'))}}" id="month" placeholder="Month">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Section :</label>
                                                            {{Form::select('section', ['Teaching'=>'Teaching', 'Non Teaching'=>'Non Teaching'],null, ['placeholder'=>'Pick a Option','class'=>'form-control','style'=>'color:red;'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                @foreach($policies as $policy)
                                                    @if($policy->name == 'Rule 1')
                                                        <li class="listorder">
                                                            <b>
                                                                Weekly off( Sunday and Holiday ) Paid for all Employees &nbsp;&nbsp;
                                                                <select class="" name="rule1Option">
                                                                    <option value="" {{($policy->temp1 == '')?'selected':''}}</option>>Pick a Option</option>
                                                                    <option value="Yes" {{($policy->temp1 == 'Yes')?'selected':''}}>Yes</option>
                                                                    <option value="No" {{($policy->temp1 == 'No')?'selected':''}}>No</option>
                                                                </select>
                                                            </b>
                                                        </li>
                                                    @endif
                                                    @if($policy->name == 'Rule 2')
                                                        <li class="listorder">
                                                            <b>
                                                                If an employee remains absent for 
                                                                <input type="text" name="rule2Days" value="{{($policy->temp1 == '')?'':$policy->temp1}}"> 
                                                                days of the same week. Only 
                                                                <select name="rule2Option">
                                                                    <option value="0" {{($policy->temp2 == '')?'selected':''}}>NA</option>
                                                                    <option value="1" {{($policy->temp2 == '1')?'selected':''}}>Half Day</option>
                                                                    <option value="2" {{($policy->temp2 == '2')?'selected':''}}>Full Day</option>
                                                                </select> 
                                                                will be consideredas paid weekly off and half day payment will be deducted.
                                                            </b>
                                                        </li>
                                                    @endif

                                                    @if($policy->name == 'Rule 3')
                                                        <li class="listorder">
                                                            <b>
                                                                The Employee will have 
                                                                <input type="text" name="rule3Day" value="{{($policy->temp1 == '')?'':$policy->temp1}}">
                                                                &nbsp;th/rd Saturyday as official off.
                                                                <select name="departmentId[]" class="form-control" multiple>
                                                                @foreach($departments as $department)
                                                                    @if(in_array($department->id, $departmentIds))
                                                                        <option value="{{$department->id}}" selected>{{$department->name}}</option>
                                                                    @else
                                                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                                                    @endif
                                                                @endforeach
                                                                </select>
                                                            </b>
                                                        </li>
                                                    @endif
                                                    @if($policy->name == 'Rule 4')
                                                        <li class="listorder">
                                                            <b>
                                                                A] Only 
                                                                <input type="text" name="rule4AMin" value="{{($policy->temp1 == '')?'':$policy->temp1}}">
                                                                &nbsp; min tolerance will be given for reporting timing.
                                                                <br><br>&nbsp;&nbsp;&nbsp;&nbsp;
                                                                B] After 
                                                                <input type="text" name="rule4BMin"  value="{{($policy->temp2 == '')?'':$policy->temp2}}">
                                                                &nbsp; min of office time, late mark is applicable.
                                                                <br><br>&nbsp;&nbsp;&nbsp;&nbsp;
                                                                C] If 
                                                                <input type="text" name="rule4CNoLate" value="{{($policy->temp3 == '')?'':$policy->temp3}}">
                                                                &nbsp; late marks in a month. 
                                                                <input type="text" name="rule4CDay1" value="{{($policy->temp4 == '')?'':$policy->temp4}}">
                                                                &nbsp; day salary will be cut,and like wise 
                                                                <input type="text" name="rule4CLate1" value="{{($policy->temp5 == '')?'':$policy->temp5}}">
                                                                &nbsp; late mark 
                                                                <input type="text" name="rule4CDay2"  value="{{($policy->temp6 == '')?'':$policy->temp6}}">
                                                                &nbsp;days.
                                                                <br><br>&nbsp;&nbsp;&nbsp;&nbsp;
                                                                D] If you exceed 
                                                                <input type="text" name="rule4DHr" value="{{($policy->temp7 == '')?'':$policy->temp7}}"> 
                                                                hour then it will be considered as a 
                                                                <select class="" name="rule4DOption">
                                                                    <option value=""  {{($policy->temp8 == '')?'selected':''}}>Pick a Option</option>
                                                                    <option value="0" {{($policy->temp8 == '0')?'selected':''}}>NA</option>
                                                                    <option value="1" {{($policy->temp8 == '1')?'selected':''}}>Half Day</option>
                                                                    <option value="2" {{($policy->temp8 == '2')?'selected':''}}>Full Day</option>
                                                                </select> 
                                                                day
                                                            </b>
                                                        </li>
                                                    @endif
                                                    @if($policy->name == 'Rule 5')
                                                        <li class="listorder">
                                                            <b>
                                                                15th August, and 26th January attendance is compulsory. Failure to attend will lead to deduction of 
                                                                <input type="text" name="rule5Day" value="{{$policy->temp1}}">
                                                                days.
                                                            </b>
                                                        </li>
                                                    @endif
                                                    @if($policy->name == 'Rule 6')
                                                        <li class="listorder">
                                                            <b>
                                                                Sandwich Leave Policy - If you take a leave on saturday and monday your Sunday will also consider your 3 leave. 
                                                                <select class="" name="rule6Option">
                                                                    <option value="" {{($policy->temp1 == '')?'selected':''}}>Pick a Option</option>
                                                                    <option value="Yes" {{($policy->temp1 == 'Yes')?'selected':''}}>Yes</option>
                                                                    <option value="No" {{($policy->temp1 == 'No')?'selected':''}}>No</option>
                                                                </select>
                                                            </b>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </ul>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 col-lg-5"></div>
                                                <div class="col-md-12 col-lg-3">
                                                    <button type="submit" class="btn btn-primary btn-lg">Save</button>
                                                    <a href="/hrPolicies" class="btn btn-danger btn-lg">Cancel</a>
                                                </div>
                                                <div class="col-md-12 col-lg-4"></div>
                                            </div>
                                        </div>
                                    {!! Form::close() !!}
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
