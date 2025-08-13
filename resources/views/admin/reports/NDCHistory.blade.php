@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container"> 
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">NDC History</h4>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="tab-menu-heading hremp-tabs p-0 ">
                        <div class="tabs-menu1">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs">
                            <li class="ml-4"><a href="#tab1" class="active" data-toggle="tab">NDC&nbsp;&nbsp;<b style="color:Red;">{{count($ndcs)}}</b></a></li>
                                <li><a href="#tab2" data-toggle="tab">Absconds&nbsp;&nbsp;<b style="color:Red;">{{count($absconds)}}</b></a></li>
                                <li><a href="#tab3" data-toggle="tab">No code&nbsp;&nbsp;<b style="color:Red;">{{count($nocodes)}}</b></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <div class="card-body">
                                    @if(isset($ndcs))
                                        @if(count($ndcs))
                                            <div class="table-responsive">
                                                <table class="table  table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Name</th>
                                                            <th>Emp Code</th>
                                                            <th>Department</th>
                                                            <th>Designation</th>
                                                            <th class="w-7">DOJ</th>
                                                            <th class="w-7">Exit Date</th>
                                                            <th class="w-10">Status</th>
                                                            <th class="w-5">Processed By</th>
                                                            <th class="w-25">Remarks<?php $i=1; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($ndcs as $row)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>{{$row->name}}</td>    
                                                                <td>{{$row->empCode}}</td>    
                                                                <td>{{$row->department}}</td>    
                                                                <td>{{$row->designation}}</td>    
                                                                <td>{{$row->DOJ}}</td>    
                                                                <td>{{$row->exitDate}}</td>    
                                                                <td>{{$row->NDCStatus}}</td>    
                                                                <td>{{$row->updated_by}}</td> 
                                                                <td>{{$row->remarks}}</td>       
                                                            </tr>
                                                        @endforeach                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                        <hr>
                                            <h4 style="color:red;">Record not Found.</h4>
                                        @endif
                                    @endif
                                </div>
                            </div>   
                            
                            <div class="tab-pane" id="tab2">
                                <div class="card-body">
                                    @if(isset($absconds))
                                        @if(count($absconds))
                                            <div class="table-responsive">
                                                <table class="table table-bordered text-nowrap border-bottom" id="example">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Name</th>
                                                            <th>Emp Code</th>
                                                            <th>Department</th>
                                                            <th>Designation</th>
                                                            <th class="w-7">DOJ</th>
                                                            <th class="w-7">Exit Date</th>
                                                            <th class="w-10">Status</th>
                                                            <th class="w-5">Processed By</th>
                                                            <th class="w-25">Remarks<?php $i=1; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($absconds as $row)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>{{$row->name}}</td>    
                                                                <td>{{$row->empCode}}</td>    
                                                                <td>{{$row->department}}</td>    
                                                                <td>{{$row->designation}}</td>    
                                                                <td>{{$row->DOJ}}</td>    
                                                                <td>{{$row->exitDate}}</td>    
                                                                <td>{{$row->NDCStatus}}</td>    
                                                                <td>{{$row->updated_by}}</td> 
                                                                <td>{{$row->remarks}}</td>       
                                                            </tr>
                                                        @endforeach                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                        <hr>
                                            <h4 style="color:red;">Record not Found.</h4>
                                        @endif
                                    @endif
                                </div>
                            </div> 

                            <div class="tab-pane" id="tab3"> 
                                <div class="card-body">
                                    @if(isset($nocodes))
                                        @if(count($nocodes))
                                            <div class="table-responsive">
                                                <table class="table table-bordered text-nowrap border-bottom" id="example1">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Name</th>
                                                            <th>Emp Code</th>
                                                            <th>Department</th>
                                                            <th>Designation</th>
                                                            <th class="w-7">DOJ</th>
                                                            <th class="w-7">Exit Date</th>
                                                            <th class="w-10">Status</th>
                                                            <th class="w-5">Processed By</th>
                                                            <th class="w-25">Remarks<?php $i=1; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($nocodes as $row)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>{{$row->name}}</td>    
                                                                <td>{{$row->empCode}}</td>    
                                                                <td>{{$row->department}}</td>    
                                                                <td>{{$row->designation}}</td>    
                                                                <td>{{$row->DOJ}}</td>    
                                                                <td>{{$row->exitDate}}</td>    
                                                                <td>{{$row->NDCStatus}}</td>    
                                                                <td>{{$row->updated_by}}</td> 
                                                                <td>{{$row->remarks}}</td>       
                                                            </tr>
                                                        @endforeach                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                        <hr>
                                            <h4 style="color:red;">Record not Found.</h4>
                                        @endif
                                    @endif
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
