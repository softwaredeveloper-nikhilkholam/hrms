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
                        <h4 class="card-title">Add Slider</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['action' => 'SliderLandPagesController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                            <div class="">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Select Slider<span class="text-red">*</span>&nbsp;&nbsp;<i class="fa fa-picture-o" style="font-size:25px;color:green;" aria-hidden="true"></i></label>
                                    <input type="file" class="form-control" name="image" placeholder="Select Slider Image" required>
                                    <b style="color:red;">Note: Image size should be 1600 X 600</b>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Text Position on Slider</label>
                                    {{Form::select('titleDescAlign', ['1'=>'Left Side', '2'=>'Center Side', '3'=>'Right Side'], null, ['placeholder'=>'Select Position','class'=>'form-control', 'id'=>'titleDescAlign'])}}
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Title</label>
                                    <input type="text" class="form-control"  maxlength="500" name="title" placeholder="Enter Title" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Description</label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3" maxlength="1000" name="description" disabled></textarea>
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
                        <h4 class="card-title">Edit Slider</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['action' => ['SliderLandPagesController@update', $slider->id], 'method' => 'POST', 'enctype'=>'multipart/form-data']) !!}
                            <div class="">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Select Slider<span class="text-red">*</span>&nbsp;&nbsp;<i class="fa fa-picture-o" style="font-size:25px;color:green;" aria-hidden="true"></i></label>
                                    <input type="file" class="form-control" name="image" placeholder="Select Slider Image">
                                    <b style="color:red;">Note: Image size should be 1600 X 600</b>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Select Text Position on Slider</label>
                                    {{Form::select('titleDescAlign', ['1'=>'Left Side', '2'=>'Center Side', '3'=>'Right Side'], $slider->titleDescAlign, ['class'=>'form-control', 'id'=>'titleDescAlign'])}}
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Title</label>
                                    <input type="text" class="form-control" maxlength="500" name="title" value="{{$slider->title}}" placeholder="Enter Title" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Description</label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3" maxlength="1000" name="description" disabled>{{$slider->description}}</textarea>
                                </div>
                            </div>
                            <?php $slidId = $slider->id; ?>

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
                        <h4 class="card-title">Slider Details</h4>
                    </div>
                    <div class="card-body">
                            <div class="">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Slider<span class="text-red">*</span>&nbsp;&nbsp;<i class="fa fa-picture-o" style="font-size:25px;color:green;" aria-hidden="true"></i></label>
                                    <img src="/landingpage/sliders/{{$slider->image}}" height="100px" width="330px">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Text Position on Slider</label>
                                    <input type="text" class="form-control" maxlength="500" name="title" value="{{($slider->titleDescAlign == 1)?'Left Side':(($slider->titleDescAlign == 2)?'Center Side':'Right Side')}}" placeholder="Enter Title" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Title</label>
                                    <input type="text" class="form-control" maxlength="500" name="title" value="{{$slider->title}}" placeholder="Enter Title" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Description</label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3" maxlength="1000" name="description" readonly>{{$slider->description}}</textarea>
                                </div>
                            </div>
                            <?php $slidId = $slider->id; ?>

                            @if($slider->active == 0)
                                {!! Form::open(['action' => 'SliderLandPagesController@activate', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                    <input type="hidden" value="{{$slider->id}}" name="id">
                                    <button  class="btn btn-success mt-4 mb-0">Activate</button>
                                {!! Form::close() !!}
                            @else
                                {!! Form::open(['action' => 'SliderLandPagesController@deactivate', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                    <input type="hidden" value="{{$slider->id}}" name="id">
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
                            <h3 class="card-title">Slider List&nbsp;&nbsp;&nbsp;<a href="/selectApplication/1" data-toggle="tooltip" title="Back to Home" class="btn btn-primary"><i class="fa fa-home" aria-hidden="true" style="font-size:25px;"></i></a></h3>
                        </div>
                        @if(count($sliders) > 0)
                            <div class="table-responsive">
                                <table class="table card-table table-vcenter text-nowrap mb-0">
                                    <thead >
                                        <tr>                                       
                                            <th>Sr. No</th>
                                            <th>Title</th>
                                            <th>Active<?php $i=1; ?></th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sliders as $slider)
                                            @if($flag != 1)
                                                @if($slider->id == $slidId)
                                                    <tr style="background:#78f10969;">
                                                @else
                                                    <tr>
                                                @endif
                                            @else
                                                <tr>
                                            @endif
                                                <td scope="row">{{$i++}}</td>
                                                <td>{{$slider->title}}</td>
                                                <td>@if($slider->active == 1)
                                                        <i class="fa fa-check-circle-o" style="font-size:25px;color:green;" aria-hidden="true"></i>
                                                    @else
                                                        <i class="fa fa-times-circle-o"  style="font-size:25px;color:red;"  aria-hidden="true"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="/sliderLandPage/{{$slider->id}}/edit" data-toggle="tooltip" title="Edit / Update Record"  class="btn btn-warning"><i class="feather feather-edit" style="font-size:17px;"></i></a>
                                                    <a href="/sliderLandPage/{{$slider->id}}"data-toggle="tooltip" title="View Details of this record" class="btn btn-success"><i class="feather feather-eye"  style="font-size:17px;"></i></a>
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
