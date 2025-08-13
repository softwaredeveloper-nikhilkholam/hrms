<?php
    $userType = Auth::user()->userType;
    use App\Helpers\Utility;
    $util=new Utility(); 
?>
@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-"><b style="color:red;">EOD Product Report</b></div>
                            <div  class="col-lg-4"></div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\ReportsController@EODProductReport', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="date" name="forDate" class="form-control" value="{{date('Y-m-d', strtotime($forDate))}}" max="{{date('Y-m-d')}}" placeholder="Vendor Name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{Form::select('productId', $productList, $productId, ['placeholder'=>'Select Product','class'=>'form-control', 'id'=>'productId'])}}
                                </div>
                            </div>
                            <div class="col-md-2 col-lg-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">Search</button>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!} 
                    <hr>
                    @if(count($products))
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <div class="table-responsive" width="100%">
                                    <table id="" width="100%" class="table table-bordered table-striped" style="">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th style="font-size:14px;white-space: nowrap;" width="5%">No</th>
                                                <th style="font-size:14px;white-space: nowrap;" width="10%">Product Code</th>
                                                <th style="font-size:14px;white-space: nowrap;" width="20%">Product Name</th>
                                                <th style="font-size:14px;white-space: nowrap;">Opening Stock</th>
                                                <th style="font-size:14px;white-space: nowrap;">Closing Stock</th>
                                                <th style="font-size:14px;white-space: nowrap;">Count<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="ligth-body">
                                            @foreach($products as $row)
                                                <tr>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$i++}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->productCode}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$row->name}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$util->numberFormatRound($row->newOpeningStock)}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$util->numberFormatRound($row->newOpeningStock+$row->newClosingStock)}}</td>
                                                    <td style="padding: 0px 17px !important;font-size:12px;white-space: nowrap;text-align:left;">{{$util->numberFormatRound(($row->newOpeningStock+$row->newClosingStock) - $row->newOpeningStock)}}</td>
                                                 </tr>
                                            @endforeach                                            
                                        </tbody>
                                    </table>
                                    <div class="row" style="margin-top:15px;">
                                        <div class='col-md-8'>{{$products->links()}}</div>
                                        <div class='col-md-4'>
                                            <form action="{{ route('export.EODProductReportExport') }}" method="GET">
                                                <input type="hidden" name="productId" value="{{ request()->productId }}">
                                                <input type="hidden" name="forDate" value="{{ request()->forDate }}">
                                                <input type="hidden" name="pageNumber" value="{{ $products->currentPage() }}">
                                                <button type="submit" class="btn btn-success">Export to Excel</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <h4>Record not found</h4>
                    @endif
            </div>
        </div>
    </div>

@endsection

