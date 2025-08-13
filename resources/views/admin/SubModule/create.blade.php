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
              <h4 class="page-title">Add Sub Module</h4>
          </div> 
          <div class="page-rightheader">
              <div class="row">
                  <h4 class="page-title"><a class="btn btn-primary" href="/subModule/list">Active List</a></h4>&nbsp;&nbsp;
                  <h4 class="page-title"><a class="btn btn-primary" href="/subModule/dList">Deactive List</a></h4>&nbsp;&nbsp;
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
                        <label class="form-label">{{($language == 1)?'Project Name':''}}<span class="text-red">*</span>:</label>
                        <input type="name"  class="form-control" name="name" placeholder="Name Of Project">
                      </div>
                    </div>
                    <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label" for="Name">Project</label>
                        <select class="form-control" id="Name" name="Name">
                          <option selected disabled value="">Select Project</option>
                        </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                        <label class="form-label">{{($language == 1)?'Module':''}}<span class="text-red">*</span>:</label>
                        <input type="name"  class="form-control" name="name" placeholder="Name Of Module">
                      </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">{{($language == 1)?'Start Date':''}}<span class="text-red">*</span>:</label>
                          <input type="Date"  class="form-control" name="Date" placeholder="Date">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">{{($language == 1)?'End Date':''}}<span class="text-red">*</span>:</label>
                          <input type="Date"  class="form-control" name="Date" placeholder="Date">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">{{($language == 1)?'Completed Date':''}}<span class="text-red">*</span>:</label>
                          <input type="Date"  class="form-control" name="Date" placeholder="Date">
                        </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="form-label">{{($language == 1)?'Requested By':''}}<span class="text-red">*</span>:</label>
                        <input type="name"  class="form-control" name="name" placeholder="Name">
                      </div>
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
