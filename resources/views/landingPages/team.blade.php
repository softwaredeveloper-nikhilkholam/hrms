@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
    <br>
    <div class="row">
        @if($flag == 1)
            <div class="col-lg-12 col-xl-4 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header border-bottom-0">
                        <h4 class="card-title">Add Team Member</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['action' => 'OurTeamLandPagesController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                            <div class="">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Select Photo<span class="text-red">*</span></label>
                                    <input type="file" class="form-control" name="image" placeholder="Select Photo" required>
                                    <b style="color:red;">Note: Image size should be 555 X 555</b>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Name<span class="text-red">*</span></label>
                                    <input type="text" class="form-control" maxlength="300"  name="name" placeholder="Enter Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Designation<span class="text-red">*</span></label>
                                    <input type="text" class="form-control" maxlength="300" name="designation" placeholder="Enter Designation" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Description<span class="text-red">*</span></label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3" maxlength="1000" name="description"></textarea>
                                </div>
                            </div>
                            <button  class="btn btn-primary mt-4 mb-0">Submit</button>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        @elseif($flag == 2)
            <div class="col-lg-12 col-xl-4 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header border-bottom-0">
                        <h4 class="card-title">Edit Team Member</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['action' => ['OurTeamLandPagesController@update', $team->id], 'method' => 'POST', 'enctype'=>'multipart/form-data']) !!}
                            <div class="">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Select Photo<span class="text-red">*</span></label>
                                    @if($team->photo == '' || $team->photo == null)
                                        <input type="file" class="form-control" name="image" placeholder="Select Slider Image" required>
                                    @else
                                        <input type="file" class="form-control" name="image" placeholder="Select Slider Image">
                                    @endif
                                    <b style="color:black;">Old Image: {{$team->photo}}</b><br>
                                    <b style="color:red;">Note: Image size should be 555 X 555</b>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Name</label>
                                    <input type="text" class="form-control" maxlength="300"  name="name" value="{{$team->name}}" placeholder="Enter Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Designation<span class="text-red">*</span></label>
                                    <input type="text" class="form-control" value="{{$team->designation}}" maxlength="300" name="designation" placeholder="Enter Designation" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Description</label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3" maxlength="1000" name="description">{{$team->description}}</textarea>
                                </div>
                            </div>
                            <?php $temId = $team->id; ?>

                            {{Form::hidden('_method', 'PUT')}}
                            {{Form::submit('Update', ['class'=>'btn btn-primary mt-4 mb-0', "onclick"=>"return confirm('Are you sure?')"])}}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        @else
            <div class="col-lg-12 col-xl-4 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header border-bottom-0">
                        <h4 class="card-title">Team Member Details</h4>
                    </div>
                    <div class="card-body">
                            <div class="">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Team Member Photo<span class="text-red">*</span></label>
                                    <img src="/landingpage/teams/{{$team->photo}}" height="180px" width="280px">

                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Name</label>
                                    <input type="text" class="form-control" maxlength="300"  name="name" value="{{$team->name}}" placeholder="Enter Name" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Designation<span class="text-red">*</span></label>
                                    <input type="text" class="form-control" maxlength="300" name="designation" value="{{$team->designation}}" placeholder="Enter Designation" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Description</label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3" maxlength="1000" name="description" readonly>{{$team->description}}</textarea>
                                </div>
                            </div>
                            <?php $temId = $team->id; ?>

                            @if($team->active == 0)
                                {!! Form::open(['action' => 'OurTeamLandPagesController@activate', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                    <input type="hidden" value="{{$team->id}}" name="id">
                                    <button  class="btn btn-success mt-4 mb-0">Activate</button>
                                {!! Form::close() !!}
                            @else
                                {!! Form::open(['action' => 'OurTeamLandPagesController@deactivate', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                    <input type="hidden" value="{{$team->id}}" name="id">
                                    <button  class="btn btn-danger mt-4 mb-0">Deactivate</button>
                                {!! Form::close() !!}
                            @endif
                    </div>
                </div>
            </div>
        @endif
        <div class="col-lg-12 col-xl-8 col-md-12 col-sm-12">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header border-bottom-0">
                            <h3 class="card-title">Team Member List&nbsp;&nbsp;&nbsp;<a href="/selectApplication/1" data-toggle="tooltip" title="Back to Home" class="btn btn-primary"><i class="fa fa-home" aria-hidden="true" style="font-size:25px;"></i></a></h3>
                        </div>
                        @if(count($teams) > 0)
                            <div class="table-responsive">
                                <table class="table card-table table-vcenter text-nowrap mb-0">
                                    <thead >
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Name</th>
                                            <th>Active<?php $i=1; ?></th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($teams as $team)
                                            @if($flag != 1)
                                                    @if($team->id == $temId)
                                                        <tr style="background:#78f10969;">
                                                    @else
                                                        <tr>
                                                    @endif
                                                @else
                                                    <tr>
                                                @endif
                                                <td scope="row">{{$i++}}</td>
                                                <td>{{$team->name}}</td>
                                                <td>@if($team->active == 1)
                                                        <i class="fa fa-check-circle-o" style="font-size:25px;color:green;" aria-hidden="true"></i>
                                                    @else
                                                        <i class="fa fa-times-circle-o"  style="font-size:25px;color:red;"  aria-hidden="true"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="/ourTeamLandPage/{{$team->id}}/edit" data-toggle="tooltip" title="Edit / Update Record"  class="btn btn-warning"><i class="feather feather-edit" style="font-size:17px;"></i></a>
                                                    <a href="/ourTeamLandPage/{{$team->id}}"data-toggle="tooltip" title="View Details of this record" class="btn btn-success"><i class="feather feather-eye"  style="font-size:17px;"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="card-footer border-bottom-0">
                                <h3 class="card-title" style="color:red;">Records not found, Please add Record</h3>
                            </div>
                        @endif
                        <!-- table-responsive -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
