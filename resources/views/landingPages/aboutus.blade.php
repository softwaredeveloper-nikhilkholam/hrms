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
                        <h4 class="card-title">Add About Us</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['action' => 'AboutsLandPagesController@store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                            <div class="">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Description<span class="text-red">*</span></label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3" maxlength="3000" name="description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Sub Title 1<span class="text-red">*</span></label>
                                    <input type="text" class="form-control" maxlength="1000" name="subTitle1" placeholder="Enter Sub Title 1" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Description 1<span class="text-red">*</span></label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3" maxlength="3000" name="description1"></textarea>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Sub Title 2</label>
                                    <input type="text" class="form-control" maxlength="1000" name="subTitle2" placeholder="Enter Sub Title 2">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Description 2</label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3" maxlength="3000" name="description2"></textarea>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Sub Title 3</label>
                                    <input type="text" class="form-control" maxlength="1000" name="subTitle3" placeholder="Enter Sub Title 3">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Description 3</label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3" maxlength="3000" name="description3"></textarea>
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
                        <h4 class="card-title">Edit About Us</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['action' => ['AboutsLandPagesController@update', $about->id], 'method' => 'POST']) !!}
                            <div class="">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Description<span class="text-red">*</span></label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3" maxlength="3000" name="description">{{$about->description}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Sub Title 1<span class="text-red">*</span></label>
                                    <input type="text" class="form-control" value="{{$about->subTitle1}}" maxlength="1000" name="subTitle1" placeholder="Enter Sub Title 1" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Description 1<span class="text-red">*</span></label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3" maxlength="3000" name="description1">{{$about->description1}}</textarea>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Sub Title 2</label>
                                    <input type="text" class="form-control" value="{{$about->subTitle2}}" maxlength="1000" name="subTitle2" placeholder="Enter Sub Title 2">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Description 2</label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3" maxlength="3000" name="description2">{{$about->description2}}</textarea>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Sub Title 3</label>
                                    <input type="text" class="form-control" value="{{$about->subTitle3}}" maxlength="1000" name="subTitle3" placeholder="Enter Sub Title 3">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Description 3</label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3" maxlength="3000" name="description3">{{$about->description3}}</textarea>
                                </div>
                            </div>
                            <?php $aboutId = $about->id; ?>

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
                        <h4 class="card-title">About Us Details</h4>
                    </div>
                    <div class="card-body">
                            <div class="">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Description<span class="text-red">*</span></label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3" maxlength="3000" name="description3" readonly>{{$about->description}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Sub Title 1</label>
                                    <input type="text" class="form-control" value="{{$about->subTitle1}}" maxlength="1000" name="subTitle1" placeholder="Enter Sub Title 1" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Description 1<span class="text-red">*</span></label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3" maxlength="3000" name="description1" readonly>{{$about->description1}}</textarea>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Sub Title 2</label>
                                    <input type="text" class="form-control" value="{{$about->subTitle2}}" maxlength="1000" name="subTitle2" placeholder="Enter Sub Title 2" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Description 2</label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3" maxlength="3000" name="description1" readonly>{{$about->description2}}</textarea>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Sub Title 3</label>
                                    <input type="text" class="form-control" value="{{$about->subTitle3}}" maxlength="1000" name="subTitle3" placeholder="Enter Sub Title 3" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Description 3</label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3" maxlength="3000" name="description3" readonly>{{$about->description3}}</textarea>
                                </div>
                                
                            </div>
                            <?php $aboutId = $about->id; ?>

                            @if($about->active == 0)
                                {!! Form::open(['action' => 'AboutsLandPagesController@activate', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                    <input type="hidden" value="{{$about->id}}" name="id">
                                    <button  class="btn btn-success mt-4 mb-0">Activate</button>
                                {!! Form::close() !!}
                            @else
                                {!! Form::open(['action' => 'AboutsLandPagesController@deactivate', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                    <input type="hidden" value="{{$about->id}}" name="id">
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
                            <h3 class="card-title">About Us List&nbsp;&nbsp;&nbsp;<a href="/selectApplication/1" data-toggle="tooltip" title="Back to Home" class="btn btn-primary"><i class="fa fa-home" aria-hidden="true" style="font-size:25px;"></i></a></h3>
                        </div>
                        @if(count($abouts) > 0)
                            <div class="table-responsive">
                                <table class="table card-table table-vcenter text-nowrap mb-0">
                                    <thead >
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Description</th>
                                            <th>Active<?php $i=1; ?></th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($abouts as $about)
                                            @if($flag != 1)
                                                @if($about->id == $aboutId)
                                                    <tr style="background:#78f10969;">
                                                @else
                                                    <tr>
                                                @endif
                                            @else
                                                <tr>
                                            @endif
                                                <th scope="row">{{$i++}}</th>
                                                <td>{{implode(' ', array_slice(explode(' ', $about->description), 0, 10))}}</td>
                                                <td>@if($about->active == 1)
                                                        <i class="fa fa-check-circle-o" style="font-size:25px;color:green;" aria-hidden="true"></i>
                                                    @else
                                                        <i class="fa fa-times-circle-o"  style="font-size:25px;color:red;"  aria-hidden="true"></i>
                                                    @endif 
                                                </td>
                                                 <td>
                                                    <a href="/aboutusLandPage/{{$about->id}}/edit" data-toggle="tooltip" title="Edit / Update Record"  class="btn btn-warning"><i class="feather feather-edit" style="font-size:17px;"></i></a>
                                                    <a href="/aboutusLandPage/{{$about->id}}"data-toggle="tooltip" title="View Details of this record" class="btn btn-success"><i class="feather feather-eye"  style="font-size:17px;"></i></a>
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
