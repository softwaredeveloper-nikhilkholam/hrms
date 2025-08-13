
<?php
$userType = Auth::user()->userType;
use App\Helpers\Utility;
$util=new Utility(); 
?>

@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div  class="col-lg-6"><b style="color:red;">WorkOrder List</b></div>
                        <div  class="col-lg-6 text-right">
                            @if(Auth::user()->userType == '701' || $userType == '801')
                                <a href="/workOrder/create" class="btn mb-1 btn-primary">Generate</a>
                            @endif
                            <a href="/workOrder" class="btn mb-1 btn-primary">Pending WorkOrder List <span class="badge badge-danger ml-2">{{$pendingOrdersCount}}</span></a>
                            <a href="/workOrder/approvedOrderList" class="btn mb-1 btn-primary">Approved List <span class="badge badge-danger ml-2">{{$approvedOrdersCount}}</span></a>
                            <a href="/workOrder/rejectedOrderList" class="btn mb-1 btn-primary">Rejected List <span class="badge badge-danger ml-2">{{$rejectedOrdersCount}}</span></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">  
                    <div class="col-lg-12">
                        <table class="table table-bordered data-table table-striped" width="100%">
                            <tr>
                                <td style="padding: 7px 7px !important;font-size: xx-large;font-family: fantasy;" colspan="4">WORK ORDER</td>
                                <td style="padding: 7px 7px !important;" >{{date('Y')}}-{{date('Y', strtotime('+1 year'))}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;"  colspan="2" class="text-left">{{$order->typeOfCompanyName}}</td>
                                <td style="padding: 7px 7px !important;"  colspan="2" class="text-left">Date</td>
                                <td  style="padding: 7px 7px !important;" class="text-left">{{date('d-m-Y', strtotime($order->generatedDate))}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;"  colspan="2" class="text-left">{{$order->shippingAddress}}</td>
                                <td style="padding: 7px 7px !important;"  colspan="2" class="text-left">BILL NO</td>
                                <td style="padding: 7px 7px !important;"  class="text-left">{{$order->billNo}}&nbsp;&nbsp;<a href="/storeAdmin/workOrders/{{$order->workOrderFile}}" target="_blank">Bill Copy</a></td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;"  colspan="2" class="text-left">PHONE NO. : {{$order->letterHeadMobileNo}}</td>
                                <td style="padding: 7px 7px !important;"  colspan="2"class="text-left">PO NO</td>
                                <td style="padding: 7px 7px !important;"  class="text-left">{{$order->poNumber}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;"  colspan="2" class="text-left">LANDLINE NO. : {{$order->letterHeadLandline}}</td>
                                <td style="padding: 7px 7px !important;"  colspan="2" class="text-left">REQ NO</td>
                                <td style="padding: 7px 7px !important;"  class="text-left">{{$order->reqNo}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;"  colspan="2" class="text-left">Website / Email: {{$order->letterHeadEmail}}</td>
                                <td style="padding: 7px 7px !important;"  colspan="2" class="text-left">BRANCH</td>
                                <td style="padding: 7px 7px !important;"  class="text-left">{{$order->branchName}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;background-color:#8080804f;"  colspan="2">VENDOR NAME</td>
                                <td  style="padding: 7px 7px !important;background-color:#8080804f;" colspan="3">DELIVERY ADDRESS</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;" class="text-left">VENDOR NAME</td>
                                <td style="padding: 7px 7px !important;"  class="text-left">{{$order->name}}</td>
                                <td  style="padding: 7px 7px !important;" class="text-left">COMPANY</td>
                                <td  style="padding: 7px 7px !important;" class="text-left" colspan="2">Aaryans World School</td>
                            </tr>
                            <tr>
                                <td  style="padding: 7px 7px !important;" class="text-left">CONTACT</td>
                                <td style="padding: 7px 7px !important;"  class="text-left">{{$order->landlineNo}}</td>
                                <td  style="padding: 7px 7px !important;" style="padding: 7px 7px !important;" class="text-left" rowspan="2">ADDRESS</td>
                                <td  style="padding: 7px 7px !important;" class="text-left" rowspan="2" colspan="2">{{$order->shippingAddress}}</td>
                            </tr>
                            <tr>
                                <td  style="padding: 7px 7px !important;" class="text-left">ADDRESS</td>
                                <td  style="padding: 7px 7px !important;" class="text-left">{{$order->address}}</td>
                            </tr>
                            <tr>
                                <td  style="padding: 7px 7px !important;" class="text-left">PAN No</td>
                                <td  style="padding: 7px 7px !important;" class="text-left">{{$order->PANNO}}</td>
                                <td  style="padding: 7px 7px !important;" class="text-left">GST No</td>
                                <td  style="padding: 7px 7px !important;" class="text-left" colspan="2">{{$order->GSTNo}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;background-color:#8080804f;"  colspan="2">REQUISITIONER</td>
                                <td  style="padding: 7px 7px !important;background-color:#8080804f;" colspan="3">AUTHORITY NAME</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;"  colspan="2">{{($reqDet != '-')?$reqDet:''}}</td>
                                <td  style="padding: 7px 7px !important;" colspan="3">-</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;" width="100%" colspan="5" class="text-center">{{$order->WOFor}}</td>
                            </tr>
                            <tr >
                                <td  style="padding: 7px 7px !important;background-color:#8080804f;" width="15%">No.</td>
                                <td  style="padding: 7px 7px !important;background-color:#8080804f;">Particular</td>
                                <td style="padding: 7px 7px !important;background-color:#8080804f;" width="10%" >Qty</td>
                                <td style="padding: 7px 7px !important;background-color:#8080804f;"  width="10%">Rate</td>
                                <td  style="padding: 7px 7px !important;background-color:#8080804f;" width="10%">Amount</td>
                            </tr>
                                <?php $products = $util->getWorkOrderList($order->id); $k=1;$total=0; ?>
                                @if(count($products))
                                    @foreach($products as $product)
                                        <tr  class="success">
                                            <td style="padding: 7px 7px !important;">{{$k++}}</td>
                                            <td style="padding: 7px 7px !important;" class="text-left">{{$product->particular}}</td>
                                            <td style="padding: 7px 7px !important;">{{$product->qty}} / {{$product->unit}}</td>
                                            <td style="padding: 7px 7px !important;">{{$util->numberFormat($product->rate)}}</td>
                                            <td style="padding: 7px 7px !important;">{{$util->numberFormat($product->amount)}}
                                               <?php $total = $total + $product->amount; ?></td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tr>
                            <tr >
                                <td style="padding: 7px 7px !important;background-color:#8080804f;" colspan="3">Comments or special instructions</td>
                                <td style="padding: 7px 7px !important;">Sub Total</td>
                                <td  style="padding: 7px 7px !important;">{{$order->totalRs}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;" colspan="3" rowspan="9">
                                    <?php $i=1;?>
                                    <table width="100%">
                                        <tr>
                                            <td style="padding: 7px 7px !important;">Sr. No</td>
                                            <td style="padding: 7px 7px !important;">Percent of Payment</td>
                                            <td style="padding: 7px 7px !important;">Payment Date</td>
                                            <td style="padding: 7px 7px !important;">Amount</td>
                                            <td style="padding: 7px 7px !important;">Remark</td>
                                        </tr>
                                        @foreach($payments as $payment)
                                            @if($payment->percent != 0)
                                                <tr>
                                                    <td style="padding: 7px 7px !important;">Payment {{$i++}}</td>
                                                    <td style="padding: 7px 7px !important;">{{$payment->percent}}%</td>
                                                    <td style="padding: 7px 7px !important;">{{date('d-m-Y', strtotime($payment->forDate))}}</td>
                                                    <td style="padding: 7px 7px !important;">{{$util->numberFormat($payment->amount)}}</td>
                                                    <td style="padding: 7px 7px !important;">Transaction No: {{$payment->transactionId}}[ {{$payment->remark}} ]</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>                                        
                                </td>
                                <td style="padding: 7px 7px !important;"  >LABOUR CHARGES</td>
                                <td  style="padding: 7px 7px !important;">{{$util->numberFormat($order->labourCharges)}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;">TRANSPORTATION</td>
                                <td  style="padding: 7px 7px !important;">{{$util->numberFormat($order->transportationRs)}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;">SHIFTING</td>
                                <td style="padding: 0px 17px !important;">{{$util->numberFormat($order->shiftingCharges)}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;">DISCOUNT</td>
                                <td style="padding: 0px 17px !important;">{{$util->numberFormat($order->discount)}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;">SUB TOTAL</td>
                                <td style="padding: 0px 17px !important;">{{$util->numberFormat($order->totalRs)}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;">CGST</td>
                                <td style="padding: 0px 17px !important;">{{$util->numberFormat($order->cgst)}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;">SGST</td>
                                <td style="padding: 7px 7px !important;" >{{$util->numberFormat($order->sgst)}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;">Advance Rs.</td>
                                <td style="padding: 7px 7px !important;" >{{$util->numberFormat($order->advancePayment)}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;">GRAND TOTAL</td>
                                <td style="padding: 7px 7px !important;" >{{$util->numberFormat($order->finalRs)}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;" width="100%" colspan="5" class="text-center">If you have any questions on this work Order please contact</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;" width="100%" colspan="5" class="text-center">prch.exe2@aaryansworldschool.org / prch.exe3@aaryansworldschool.org</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;" width="100%" colspan="5" class="text-center">Made By : {{$order->raisedBy}}&nbsp;&nbsp;&nbsp;|&nbsp;Purchase By: </td>
                            </tr>
                        </table> 
                          <a href="/workOrder/printWO/{{$order->id}}" class="btn btn-danger mt-3" target="_blank"><i class="fa fa-print"></i>Print WO<a> 
                    </div>
                </div>  
            </div>
        </div>
    </div>
@endsection