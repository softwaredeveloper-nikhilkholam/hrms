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
                        <h4 class="card-title">Add Business Logo</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['action' => 'BusinessLogoLandPagesController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                            <div class="">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Select Logo<span class="text-red">*</span></label>
                                    <input type="file" class="form-control" name="logo[]"  multiple="multiple" placeholder="Select Logo" required>
                                    <b style="color:red;">Note: Logo size should be 632 X 190</b><br>
                                    <b style="color:red;">Note: You can upload Multiple files, using CTR or Shift Key</b>
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
                        <h4 class="card-title">Edit Business Logo</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['action' => ['BusinessLogoLandPagesController@update', $logo->id], 'method' => 'POST', 'enctype'=>'multipart/form-data']) !!}
                            <div class="">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Select Logo<span class="text-red">*</span></label>
                                    <input type="file" class="form-control" name="logo" placeholder="Select Logo">
                                    <b style="color:black;">Old Image: {{$logo->logoImage}}</b><br>
                                    <b style="color:red;">Note: Logo size should be 632 X 190</b>
                                </div>
                            </div>
                            <?php $logoId = $logo->id; ?>

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
                        <h4 class="card-title">Business Logo Details</h4>
                    </div>
                    <div class="card-body">
                            <div class="">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Logo<span class="text-red">*</span></label>
                                    <img src="/landingpage/businesslogo/{{$logo->logoImage}}">
                                </div>
                            </div>
                            <?php $logoId = $logo->id; ?>

                            @if($logo->active == 0)
                                {!! Form::open(['action' => 'BusinessLogoLandPagesController@activate', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                    <input type="hidden" value="{{$logo->id}}" name="id">
                                    <button  class="btn btn-success mt-4 mb-0">Activate</button>
                                {!! Form::close() !!}
                            @else
                                {!! Form::open(['action' => 'BusinessLogoLandPagesController@deactivate', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                    <input type="hidden" value="{{$logo->id}}" name="id">
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
                            <h3 class="card-title">Business Logo List&nbsp;&nbsp;&nbsp;<a href="/selectApplication/1" data-toggle="tooltip" title="Back to Home" class="btn btn-primary"><i class="fa fa-home" aria-hidden="true" style="font-size:25px;"></i></a></h3>
                      </div>
                        @if(count($logos) > 0)
                            <div class="table-responsive">
                                <table class="table card-table table-vcenter text-nowrap mb-0">
                                    <thead >
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Logo<?php $i=1; ?></th>
                                            <th>Active</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($logos as $logo)
                                            @if($flag != 1)
                                                @if($logo->id == $logoId)
                                                    <tr style="background:#78f10969;">
                                                @else
                                                    <tr>
                                                @endif
                                            @else
                                                <tr>
                                            @endif
                                                <th scope="row">{{$i++}}</th>
                                                <td><img src="/landingpage/businesslogo/{{$logo->logoImage}}" height="50px" width="80px"></td>
                                                <td>@if($logo->active == 1)
                                                        <i class="fa fa-check-circle-o" style="font-size:25px;color:green;" aria-hidden="true"></i>
                                                    @else
                                                        <i class="fa fa-times-circle-o"  style="font-size:25px;color:red;"  aria-hidden="true"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="/businesslogoLandPage/{{$logo->id}}/edit" data-toggle="tooltip" title="Edit / Update Record"  class="btn btn-warning"><i class="feather feather-edit" style="font-size:17px;"></i></a>
                                                    <a href="/businesslogoLandPage/{{$logo->id}}"data-toggle="tooltip" title="View Details of this record" class="btn btn-success"><i class="feather feather-eye"  style="font-size:17px;"></i></a>
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
