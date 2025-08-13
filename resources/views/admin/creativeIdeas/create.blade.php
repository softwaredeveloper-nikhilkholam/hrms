<?php
    use App\Helpers\Utility;
    $util = new Utility();
    
    $name = Session()->get('name');
    $user = Auth::user();
    $userType = $user->userType;
    $language = Auth::user()->language; 
    $transAllowed = Auth::user()->transAllowed; 

?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
  <div class="container">
    <div class="container">                        
      <div class="page-header d-xl-flex d-block">
          <div class="page-leftheader">
              <h4 class="page-title">Add Ideas</h4>
          </div> 
          <div class="page-rightheader">
              <div class="row">
                  <h4 class="page-title"><a class="btn btn-primary" href="/creativeIdeas/list">Active List</a></h4>&nbsp;&nbsp;
                  <h4 class="page-title"><a class="btn btn-primary" href="/creativeIdeas/dList">Deactive List</a></h4>&nbsp;&nbsp;
              </div> 

          </div> 
      </div>
      <!--End Page header-->
        <div class="row">
          <div class="col-xl-12 col-md-6 col-lg-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label" for="Gender">General Titles</label>
                        <select class="form-control" id="Gender" name="Gender">
                          <option selected disabled value="">Select Title</option>
                          <option value="Write Up" {{ old('Gender') == 'Male' ? 'selected' : '' }}>Write Up</option>
                          <option value="Ideas" {{ old('Gender') == 'Female' ? 'selected' : '' }}>Ideas</option>
                          <option value="Artical" {{ old('Gender') == 'Female' ? 'selected' : '' }}>Artical</option>
                          <option value="Speech" {{ old('Gender') == 'Female' ? 'selected' : '' }}>Speech</option>
                        </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                        <label class="form-label">{{($language == 1)?'Date':''}}<span class="text-red">*</span>:</label>
                        <input type="Date"  class="form-control" name="Date" placeholder="Date">
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                        <label class="form-label">{{($language == 1)?'Title':''}}<span class="text-red">*</span>:</label>
                        <input type="name"  class="form-control" name="name" placeholder="Title">
                      </div>
                  </div>
                  <div class="col-md-2 mb-2">
                    <label class="form-label" for="birthDate">Attachment</label>
                    <input class="form-control @error('file') is-invalid @enderror" id="file" name="File" type="File" value="{{ old('file') }}">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                        <label class="form-label" for="validationTooltip02">Discription</label>
                        <textarea class="form-control" name="Discription" rows="6" cols="3" placeholder="Discription"></textarea>
                  </div>
                </div>
                <div class="row">
                    <div class="col-md-5"></div>
                    <div class="col-md-2">
                      <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary btn-block">Save</button>
                      </div>
                    </div>
                     <div class="col-md-5"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
@endsection
