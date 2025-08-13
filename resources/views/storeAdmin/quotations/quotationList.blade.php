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
                            <div  class="col-lg-4"><b style="color:red;">Pending Quotation List ({{count($quotations)}})</b></div>
                            <div  class="col-lg-8 text-right">
                                @if(Auth::user()->userType == '701' || $userType == '801')
                                    <a href="/quotation" class="btn mb-1 btn-danger">Generate</a>
                                @endif
                                <a href="/quotation/quotationList" class="btn mb-1 btn-success">Pending List</a>
                                <a href="/quotation/approvedQuotationList" class="btn mb-1 btn-primary">Approved List</a>
                                <a href="/quotation/rejectedQuotationList" class="btn mb-1 btn-primary">Rejected List</a>
                                <a href="/quotation/saveList" class="btn mb-1 btn-primary">Save List</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'storeController\PurchaseTransactions@list', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="autocomplete">
                                    <input id="myInputVendor" type="text" name="myInputVendorName" value="{{$myInputVendorName}}" class="form-control" placeholder="Vendor Name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                {{Form::select('raisedBys', $users, $raisedBys, ['placeholder'=>'Select Option','class'=>'form-control'])}}
                            </div>
                            <div class="col-md-2 col-lg-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">Search</button>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!} 
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                @if(count($quotations))
                                    <table id="datatable" data-page-length='25' class="table data-table table-bordered table-striped" style="">
                                        <thead class="bg-white text-uppercase">
                                            <tr class="ligth ligth-data">
                                                <th style="font-size:12px !important;" width="3%">No</th>
                                                <th style="font-size:12px !important;" width="5%">Quot. GP No</th>
                                                <th style="font-size:12px !important;" width="15%">Vendor</th>
                                                <th style="font-size:12px !important;" width="5%">Rs.</th>
                                                <th style="font-size:12px !important;">Requisition For</th>
                                                <th style="font-size:12px !important;" width="5%">Type Of Company</th>
                                                <th style="font-size:12px !important;" width="5%">Already Paid</th>
                                                <th style="font-size:12px !important;" width="5%">Updated At & Raised By</th>
                                                <th style="font-size:12px !important;" width="5%">Action<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="ligth-body">
                                            <?php $quotNO=0; $temp=0; ?>
                                            @foreach($quotations as $quotation)
                                                @if($temp != $quotation->commQuotNo)
                                                    @if($quotation->quotStatus == 'Pending')
                                                        <tr>
                                                            <td style="padding: 0px 17px !important;font-size:12px !important;text-align:center;"><b>{{$i++}}</b></td>
                                                            <td style="padding: 0px 17px !important;font-size:12px !important;text-align:center;"><b>{{$quotation->commQuotNo}}</b></td>
                                                            <td style="padding: 0px 17px !important;font-size:12px !important;text-align:center;"><b>{{$quotation->vendorName}}</b></td>
                                                            <td style="padding: 0px 17px !important;font-size:12px !important;text-align:center;"><b>{{$util->numberFormat($quotation->finalRs)}}</b></td>
                                                            <td style="padding: 0px 17px !important;font-size:12px !important;text-align:left;"><b>{{ucwords(strtolower($quotation->quotationFor))}}</b></td>
                                                            <td style="padding: 0px 17px !important;font-size:12px !important;text-align:center;"><b>{{$quotation->typeOfCompanyName}}</b></td>
                                                            <td style="padding: 0px 17px !important; font-size:12px !important;{{($quotation->alreadyPaid == 0)?'color:red;':'color:green;'}}" class="text-center"><b>{{($quotation->alreadyPaid == 0)?'✘':'✓'}}</b></td>
                                                            <td style="padding: 0px 17px !important;font-size:12px !important;text-align:center;"><b>{{date('d-m-y H:i', strtotime($quotation->created_at))}}<br>{{($quotation->raisedBy != '')?$util->getQuotRaisedBy($quotation->raisedBy):'-'}}</b></td>
                                                            <td style="padding: 0px 17px !important;font-size:12px !important;text-align:center;">
                                                                <div class="d-flex align-items-center list-action">
                                                                    <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="View Quotation"
                                                                        href="/quotation/{{$quotation->commQuotNo}}"><i class="fa fa-eye mr-0"></i></a>
                                                                    <a class="badge bg-danger mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete Quotations"
                                                                        href="/quotation/{{$quotation->commQuotNo}}/deactivate" onclick="return confirm('Are you sure to Delete Quotations....?')"><i class="fa fa-trash mr-0"></i></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                                <?php $temp = $quotation->commQuotNo; ?>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <h4>Record not found</h4>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
