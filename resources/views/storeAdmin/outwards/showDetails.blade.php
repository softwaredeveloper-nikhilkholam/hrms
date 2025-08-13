<?php
    $userType = Auth::user()->userType;
    $userId = Auth::user()->id;
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
                
            </div>
            <div class="card-body">
                <div class="row"> 
                    <div class="col-md-2">
                        Outward Receipt No : <br><b>{{$outward->receiptNo}}</b>
                    </div>  
                    <div class="col-md-2 mt-2">
                        Requisition No : <br><b>{{$outward->requisitionNo}}</b>
                    </div>   
                    <div class="col-md-2 mt-2">
                        Outward Date : <br><b>{{date('d-m-Y', strtotime($outward->forDate))}}</b>
                    </div>  
                    <div class="col-md-2 mt-2">
                        Given By : <br><b>Store Department (BW)</b>
                    </div>   
                    <div class="col-md-2 mt-2">
                        Handler : <br><b>{{$requisition->requisitionerName}}</b>
                    </div>   
                    <div class="col-md-2 mt-2">
                        Deliver To : <br>{{Form::select('branchId', $branches, $requisition->branchId, ['class'=>'form-control', 'placeholder'=>'Select a Branch', 'required', 'id'=>'branchId', 'disabled'])}}
                    </div>   
                </div>
                
                <hr>
                <div class="row raiseRequestion">  
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered mb-0" id="myTable1">
                            <thead class="bg-white text-uppercase">
                                <tr class="ligth ligth-data">
                                    <th  style="padding: 5px 4px !important;font-size:13px;" width="3%" class="text-center">No</th>
                                    <th  style="padding: 5px 4px !important;font-size:13px;" width="10%" class="text-center">Image</th>
                                    <th  style="padding: 5px 4px !important;font-size:13px;" class="text-center">Product Detail</th>
                                    <th  style="padding: 5px 4px !important;font-size:13px;" width="10%" class="text-center">Qty<?php $i=1;$total=$totalQty=0; ?></th>
                                </tr>
                            </thead>
                            <tbody class="ligth-body">
                                @foreach($prodList as $product)
                                    @if($product->status == 1)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td><a href="/storeAdmin/productImages/{{$product->image}}" target="_blank"><img class="thumbnail zoom" height="70px" style="border-radius: 25px;border: 2px solid #73AD21;padding: 2px;" width="70px" alt="img" src="/storeAdmin/productImages/{{$product->image}}"></a></td>
                                            <td class="text-left">{{$product->productMName}}</td>
                                            <td>{{$util->numberFormat($product->requiredQty)}}<?php $totalQty = $totalQty + $product->requiredQty; ?></td>
                                        </tr>
                                    @endif
                                @endforeach
                                    <tr class="ligth ligth-data">
                                        <th  style="padding: 5px 4px !important;font-size:13px;" colspan="3">Total</th>
                                        <th  style="padding: 5px 4px !important;font-size:13px;">{{$util->numberFormat($totalQty)}}</th>
                                    </tr>
                            </tbody>
                        </table>
                    </div>                      
                </div>
                <hr>
                <?php $flag=0; ?>
                @if(count($trackingHistories))
                    <h5>Delivery Tracking</h5>
                    <div class="row">  
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered mb-0" id="">
                                <thead class="bg-white text-uppercase">
                                    <tr class="ligth ligth-data">
                                        <th  style="padding: 5px 4px !important;font-size:8px;" width="3%" class="text-center">No</th>
                                        <th  style="padding: 5px 4px !important;font-size:8px;" width="10%" class="text-center">Remark</th>
                                        <th  style="padding: 5px 4px !important;font-size:8px;" width="10%" class="text-center">Updated By<?php $i=1; ?></th>
                                    </tr>
                                </thead>
                                <tbody class="ligth-body">
                                    @foreach($trackingHistories as $history)
                                        @if($requisition->userId != $userId)
                                            <?php $flag = 1; ?>
                                            <tr style="background-color:gray;">
                                        @else
                                            <tr>
                                        @endif
                                            <td  style="padding: 5px 4px !important;font-size:8px;" >{{$i++}}</td>
                                            <td  style="padding: 5px 4px !important;font-size:8px;"  class="text-left">{{$history->userComment}}</td>
                                            <td  style="padding: 5px 4px !important;font-size:8px;"  class="text-left">{{$history->name}}[{{$history->designation}}][{{date('d-m-Y H:i', strtotime($history->created_at))}}]</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>                      
                    </div>
                @endif
                {{$flag}}
                @if($flag == 0 && $requisition->status == 1)
                    {!! Form::open(['action' => 'storeController\OutwardsController@updatedDeliveryHistory', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                        <div class="row">  
                            @if($requisition->userId == $userId)
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Requisition Status<span style="color:red;">*</span></label>
                                    {{Form::select('reqStatus', ['0'=>'Pending', '1'=>'Completed'], $requisition->status, ['class'=>'form-control', 'placeholder'=>'Select a Status', 'required', 'id'=>'reqStatus', 'required'])}}
                                </div>
                            </div>    
                            @endif
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Image<span style="color:red;">( Optional )</span></label>
                                    <input type="file" class="form-control" value="" name="image" placeholder="Select Any Image" >
                                </div>
                            </div>    
                            <div class="col-md-2">                      
                                <div class="form-group">
                                    <label>Remark<span style="color:red;">( Optional )</span></label>
                                    <input type="text" class="form-control" value="" name="userComment" placeholder="Enter Any Remark" >
                                </div>
                            </div>    
                        </div> 
                        <input type="hidden" name="outwardId" value="{{$outward->id}}">
                        <input type="hidden" name="requisitionId" value="{{$requisition->id}}">
                        <button type="submit" class="btn btn-success mr-2">Checked</button>
                    {!! Form::close() !!}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
