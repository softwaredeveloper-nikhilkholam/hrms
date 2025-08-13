<?php
    $user = Auth::user();
    $language = $user->language;
?>
@extends('layouts.master')
@section('title', 'AWS')
@section('content') 
<div class="container">
    <div class="app-content main-content">
        <div class="side-app">        
            <!--Page header-->
            <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">{{($language == 1)?'Forms & Circular':'फॉर्म & सर्क्युलरस'}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header  border-0">
                            <h4 class="card-title">{{($language == 1)?'Forms & Circular':'फॉर्म & सर्क्युलरस'}}</h4>
                        </div>
                        <div class="card-body">
                            @if(isset($formCirculars))
                                @if(count($formCirculars) >= 1)
                                    <div class="table-responsive">
                                        <table class="table table-vcenter text-nowrap table-bordered border-top border-bottom" id="hr-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0 w-5">#</th>
                                                    <th class="border-bottom-0">{{($language == 1)?'Circular No':'सर्क्युलरस नंबर'}}</th>
                                                    <th class="border-bottom-0">{{($language == 1)?'Circular Name':'सर्क्युलरस नाव'}}<?php $i=1; ?></th>
                                                    <th class="border-bottom-0 text-right w-15">{{($language == 1)?'Updated At':'अपडेट वेळ'}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($formCirculars as $circular)
                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td><a href="/formsCirculars/{{$circular->id}}" style="color:red;">{{$circular->circularNo}}</a></td>
                                                        <td>{{$circular->name}}</td>
                                                        <td class="text-right">{{date('d/m/Y h:i A', strtotime($circular->updated_at))}}</td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <h4 style="color:red;">{{($language == 1)?'Not Found Active Records.':'फॉर्म & सर्क्युलरस रेकॉर्ड नाहीत.'}}</h4>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div><!-- end app-content-->
</div>
@endsection
