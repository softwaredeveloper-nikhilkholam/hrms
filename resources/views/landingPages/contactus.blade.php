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
                        <h4 class="card-title">Add Contact Us</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['action' => 'ContactusLandPagesController@store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                            <div class="">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Branch Name<span class="text-red">*</span></label>
                                    <input type="text" class="form-control"  maxlength="300" name="name" placeholder="Enter Branch Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Sub Title</label>
                                    <input type="text" class="form-control"  maxlength="500" name="subTitle" placeholder="Enter Sub Title">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Address<span class="text-red">*</span></label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3"  maxlength="1000" name="address"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Email<span class="text-red">*</span></label>
                                    <input type="email" class="form-control"  maxlength="200" name="email" placeholder="Enter Email" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Phone No<span class="text-red">*</span></label>
                                    <input type="text" class="form-control"  maxlength="30" name="phoneNo" placeholder="Enter Phone No" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Embeded Location Link<span class="text-red">*</span> </label>
                                    <input type="text" class="form-control"  maxlength="5000" name="mapLink" placeholder="Enter Embeded Location Link" required>
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
                        <h4 class="card-title">Edit Contact Us</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['action' => ['ContactusLandPagesController@update', $contact->id], 'method' => 'POST']) !!}
                            <div class="">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Branch Name<span class="text-red">*</span></label>
                                    <input type="text" class="form-control"  maxlength="300" name="name" value="{{$contact->branchName}}" placeholder="Enter Branch Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Sub Title</label>
                                    <input type="text" class="form-control"  maxlength="500" name="subTitle" value="{{$contact->subTitle}}" placeholder="Enter Sub Title">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Address<span class="text-red">*</span></label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3"  maxlength="1000" name="address">{{$contact->address}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Email<span class="text-red">*</span></label>
                                    <input type="email" class="form-control"  maxlength="200" name="email" value="{{$contact->email}}" placeholder="Enter Email" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Phone No<span class="text-red">*</span></label>
                                    <input type="text" class="form-control"  maxlength="30" name="phoneNo" value="{{$contact->phoneNo}}" placeholder="Enter Phone No" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Embeded Location Link<span class="text-red">*</span></label>
                                    <input type="text" class="form-control"  maxlength="5000" name="mapLink" value="{{$contact->link}}" placeholder="Enter Embeded Location Link" required>
                                </div>
                            </div>
                            <?php $contId = $contact->id; ?>
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
                        <h4 class="card-title">Contact Us Details</h4>
                    </div>
                    <div class="card-body">
                            <div class="">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Branch Name</label>
                                    <input type="text" class="form-control"  maxlength="300" name="name" value="{{$contact->branchName}}" placeholder="Enter Branch Name" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Sub Title</label>
                                    <input type="text" class="form-control"  maxlength="500" name="subTitle" value="{{$contact->subTitle}}" placeholder="Enter Sub Title" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Address</label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3"  maxlength="1000" name="address" readonly>{{$contact->address}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Email</label>
                                    <input type="email" class="form-control"  maxlength="200" name="email" value="{{$contact->email}}" placeholder="Enter Email" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Phone No</label>
                                    <input type="text" class="form-control"  maxlength="30" name="phoneNo" value="{{$contact->phoneNo}}" placeholder="Enter Phone No" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Embeded Location Link</label>
                                    <input type="text" class="form-control"  maxlength="5000" name="mapLink" value="{{$contact->link}}" placeholder="Enter Embeded Location Link" readonly>
                                </div>
                            </div>
                            <?php $contId = $contact->id; ?>

                            @if($contact->active == 0)
                                {!! Form::open(['action' => 'ContactusLandPagesController@activate', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                    <input type="hidden" value="{{$contact->id}}" name="id">
                                    <button  class="btn btn-success mt-4 mb-0">Activate</button>
                                {!! Form::close() !!}
                            @else
                                {!! Form::open(['action' => 'ContactusLandPagesController@deactivate', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                                    <input type="hidden" value="{{$contact->id}}" name="id">
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
                            <h3 class="card-title">Contact Us List&nbsp;&nbsp;&nbsp;<a href="/selectApplication/1" data-toggle="tooltip" title="Back to Home" class="btn btn-primary"><i class="fa fa-home" aria-hidden="true" style="font-size:25px;"></i></a></h3>
                        </div>
                        @if(count($contacts) > 0)
                            <div class="table-responsive">
                                <table class="table card-table table-vcenter text-nowrap mb-0">
                                    <thead >
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Branch Name</th>
                                            <th>Phone No<?php $i=1; ?></th>
                                            <th>Active</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($contacts as $contact)
                                            @if($flag != 1)
                                                @if($contact->id == $contId)
                                                    <tr style="background:#78f10969;">
                                                @else
                                                    <tr>
                                                @endif
                                            @else
                                                <tr>
                                            @endif
                                                <th scope="row">{{$i++}}</th>
                                                <td>{{$contact->branchName}}</td>
                                                <td>{{$contact->phoneNo}}</td>
                                                <td>@if($contact->active == 1)
                                                        <i class="fa fa-check-circle-o" style="font-size:25px;color:green;" aria-hidden="true"></i>
                                                    @else
                                                        <i class="fa fa-times-circle-o"  style="font-size:25px;color:red;"  aria-hidden="true"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="/contactusLandPage/{{$contact->id}}/edit" data-toggle="tooltip" title="Edit / Update Record"  class="btn btn-warning"><i class="feather feather-edit" style="font-size:17px;"></i></a>
                                                    <a href="/contactusLandPage/{{$contact->id}}"data-toggle="tooltip" title="View Details of this record" class="btn btn-success"><i class="feather feather-eye"  style="font-size:17px;"></i></a>
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
