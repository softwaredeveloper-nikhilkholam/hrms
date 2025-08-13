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
                        <h4 class="card-title">Add Social Media Links</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['action' => 'MediaLandPagesController@store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                            <div class="">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Twitter ID&nbsp;&nbsp;<i class="fa fa-twitter" style="font-size:22px;color:blue;"></i></label>
                                    <input type="text" class="form-control" maxlength="300" name="twitter" placeholder="Enter Projects Count" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Instagram ID&nbsp;&nbsp;<i class="fa fa-instagram" style="font-size:22px;color:red;"></i></label>
                                    <input type="text" class="form-control" maxlength="300" name="instagram" placeholder="Enter Happy Parents Count" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">WhatsApp No&nbsp;&nbsp;<i class="fa fa-whatsapp" style="font-size:22px;color:green;"></i></label>
                                    <input type="text" class="form-control" maxlength="300" name="whatsapp" placeholder="Enter Awards Count" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Facebook Id&nbsp;&nbsp;<i class="fa fa-facebook" style="font-size:22px;color:blue;"></i></label>
                                    <input type="text" class="form-control" maxlength="300" name="facebook" placeholder="Enter Happy Employees Count" required>
                                </div> 
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Email Id&nbsp;&nbsp;<i class="fa fa-google" style="font-size:22px;color:red;"></i></label>
                                    <input type="text" class="form-control" maxlength="300" name="gmail" placeholder="Enter Happy Employees Count" required>
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
                        <h4 class="card-title">Edit Social Media Links</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['action' => ['MediaLandPagesController@update', $media->id], 'method' => 'POST']) !!}
                            <div class="">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Twitter ID&nbsp;&nbsp;<i class="fa fa-twitter" style="font-size:22px;color:blue;"></i></label>
                                    <input type="text" class="form-control" maxlength="300" name="twitter" value="{{$media->twitter}}" placeholder="Enter Projects Count" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Instagram ID&nbsp;&nbsp;<i class="fa fa-instagram" style="font-size:22px;color:red;"></i></label>
                                    <input type="text" class="form-control" maxlength="300" name="instagram" value="{{$media->instagram}}" placeholder="Enter Happy Parents Count" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">WhatsApp No&nbsp;&nbsp;<i class="fa fa-whatsapp" style="font-size:22px;color:green;"></i></label>
                                    <input type="text" class="form-control" maxlength="300" name="whatsapp" value="{{$media->whatsapp}}" placeholder="Enter Awards Count" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Facebook Id&nbsp;&nbsp;<i class="fa fa-facebook" style="font-size:22px;color:blue;"></i></label>
                                    <input type="text" class="form-control" maxlength="300" name="facebook" value="{{$media->facebook}}" placeholder="Enter Happy Employees Count" required>
                                </div> 
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Email Id&nbsp;&nbsp;<i class="fa fa-google" style="font-size:22px;color:red;"></i></label>
                                    <input type="text" class="form-control" maxlength="300" name="gmail" value="{{$media->gmail}}" placeholder="Enter Happy Employees Count" required>
                                </div>   
                            </div>
                            <?php $medId = $media->id; ?>

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
                        <h4 class="card-title">Social Media Links Details</h4>
                    </div>
                    <div class="card-body">
                            <div class="">
                            <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Twitter ID&nbsp;&nbsp;<i class="fa fa-twitter" style="font-size:22px;color:blue;"></i></label>
                                    <input type="text" class="form-control" maxlength="300" name="twitter" value="{{$media->twitter}}" placeholder="Enter Projects Count" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Instagram ID&nbsp;&nbsp;<i class="fa fa-instagram" style="font-size:22px;color:red;"></i></label>
                                    <input type="text" class="form-control" maxlength="300" name="instagram" value="{{$media->instagram}}" placeholder="Enter Happy Parents Count" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">WhatsApp No&nbsp;&nbsp;<i class="fa fa-whatsapp" style="font-size:22px;color:green;"></i></label>
                                    <input type="text" class="form-control" maxlength="300" name="whatsapp" value="{{$media->whatsapp}}" placeholder="Enter Awards Count" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Facebook Id&nbsp;&nbsp;<i class="fa fa-facebook" style="font-size:22px;color:blue;"></i></label>
                                    <input type="text" class="form-control" maxlength="300" name="facebook" value="{{$media->facebook}}" placeholder="Enter Happy Employees Count" readonly>
                                </div> 
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Email Id&nbsp;&nbsp;<i class="fa fa-google" style="font-size:22px;color:red;"></i></label>
                                    <input type="text" class="form-control" maxlength="300" name="gmail" value="{{$media->gmail}}" placeholder="Enter Happy Employees Count" readonly>
                                </div>  
                            </div>
                            <?php $medId = $media->id; ?>

                            @if($media->active == 0)
                                {!! Form::open(['action' => 'MediaLandPagesController@activate', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                    <input type="hidden" value="{{$media->id}}" maxlength="300" name="id">
                                    <button  class="btn btn-success mt-4 mb-0">Activate</button>
                                {!! Form::close() !!}
                            @else
                                {!! Form::open(['action' => 'MediaLandPagesController@deactivate', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                    <input type="hidden" value="{{$media->id}}" maxlength="300" name="id">
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
                            <h3 class="card-title">Social Media Links List&nbsp;&nbsp;&nbsp;<a href="/selectApplication/1" data-toggle="tooltip" title="Back to Home" class="btn btn-primary"><i class="fa fa-home" aria-hidden="true" style="font-size:25px;"></i></a></h3>
                        </div>
                        @if(count($medias) > 0)
                            <div class="table-responsive">
                                <table class="table card-table table-vcenter text-nowrap mb-0">
                                    <thead >
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Gmail Id</th>
                                            <th>Active<?php $i=1; ?></th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($medias as $media)
                                                @if($flag != 1)
                                                    @if($media->id == $medId)
                                                        <tr style="background:#78f10969;">
                                                    @else
                                                        <tr>
                                                    @endif
                                                @else
                                                    <tr>
                                                @endif
                                                <th scope="row">{{$i++}}</th>
                                                <td>{{$media->gmail}}</td>
                                                <td>@if($media->active == 1)
                                                        <i class="fa fa-check-circle-o" style="font-size:25px;color:green;" aria-hidden="true"></i>
                                                    @else
                                                        <i class="fa fa-times-circle-o"  style="font-size:25px;color:red;"  aria-hidden="true"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="/socialMediaLandPage/{{$media->id}}/edit" data-toggle="tooltip" title="Edit / Update Record"  class="btn btn-warning"><i class="feather feather-edit" style="font-size:17px;"></i></a>
                                                    <a href="/socialMediaLandPage/{{$media->id}}"data-toggle="tooltip" title="View Details of this record" class="btn btn-success"><i class="feather feather-eye"  style="font-size:17px;"></i></a>
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
