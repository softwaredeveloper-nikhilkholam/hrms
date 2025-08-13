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
              <h4 class="page-title">Add</h4>
          </div> 
          <div class="page-rightheader">
              <div class="row">
                  <h4 class="page-title"><a class="btn btn-primary" href="/projectCredentials/list">Active List</a></h4>&nbsp;&nbsp;
                  <h4 class="page-title"><a class="btn btn-primary" href="/projectCredentials/dList">Deactive List</a></h4>&nbsp;&nbsp;
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
                      <label class="form-label" for="name">Project</label>
                        <select class="form-control" id="name" name="name">
                          <option selected disabled value="">Select Project</option>
                        </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                        <label class="form-label">{{($language == 1)?'Type':''}}<span class="text-red">*</span>:</label>
                        <input type="Tyep"  class="form-control" name="Type" placeholder="select Type">
                      </div>
                  </div>
                   <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">{{($language == 1)?'username':''}}<span class="text-red">*</span>:</label>
                            <input type="name"  class="form-control" name="name" placeholder="username">
                        </div>
                  </div>
                  <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">{{($language == 1)?'Password':''}}<span class="text-red">*</span>:</label>
                            <input type="name"  class="form-control" name="name" placeholder="Password">
                        </div>
                  </div>
                  <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">{{($language == 1)?'Path':''}}<span class="text-red">*</span>:</label>
                            <input type="name"  class="form-control" name="name" placeholder="Password">
                        </div>
                  </div>
                  <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">{{($language == 1)?'Port':''}}<span class="text-red">*</span>:</label>
                            <input type="name"  class="form-control" name="name" placeholder="Password">
                        </div>
                  </div>
                  <div class="col-md-2 mb-2">
                    <label class="form-label" for="birthDate">Attachment</label>
                    <input class="form-control @error('file') is-invalid @enderror" id="file" name="File" type="File" value="{{ old('file') }}">
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
