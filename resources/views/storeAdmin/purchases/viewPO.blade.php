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
                        <div  class="col-lg-"><b style="color:red;">Purchase Order Detail</b></div>
                        <div  class="col-lg-4">
                            <a href="/purchaseOrder/purchaseOrderList" class="btn mb-1 btn-primary">
                                List <span class="badge badge-danger ml-2">{{$orders}}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">  
                    <div class="col-lg-12">
                        <table class="table table-bordered data-table table-striped" width="100%">
                            <tr>
                                <td style="padding: 7px 7px !important;font-size: xx-large;font-family: fantasy;" colspan="4">PURCHASE ORDER</td>
                                <td style="padding: 7px 7px !important;" >{{date('Y')}}-{{date('Y', strtotime('+1 year'))}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;"  colspan="2" class="text-left">{{$pOrder->tempTypeOfCompany}}</td>
                                <td style="padding: 7px 7px !important;"  colspan="2" class="text-left">Date</td>
                                <td  style="padding: 7px 7px !important;" class="text-left">{{date('d-m-Y', strtotime($pOrder->generatedDate))}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;"  colspan="2" class="text-left">{{$pOrder->shippingAddress}}</td>
                                <td style="padding: 7px 7px !important;"  colspan="2" class="text-left">Bill Attachement</td>
                                <td style="padding: 7px 7px !important;"  class="text-left"><a href="/storeAdmin/quotations/{{$pOrder->quotationFile}}" target="_blank">Click here</a></td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;"  colspan="2" class="text-left">PHONE NO. : 9673004488</td>
                                <td style="padding: 7px 7px !important;"  colspan="2"class="text-left">PO NO</td>
                                <td style="padding: 7px 7px !important;"  class="text-left">{{$pOrder->poNumber}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;"  colspan="2" class="text-left">LANDLINE NO. : 9673004488</td>
                                <td style="padding: 7px 7px !important;"  colspan="2" class="text-left">REQ NO</td>
                                <td style="padding: 7px 7px !important;"  class="text-left">{{$pOrder->reqNo}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;"  colspan="2" class="text-left">Website: www.aaraynsworldschool.com</td>
                                <td style="padding: 7px 7px !important;"  colspan="2" class="text-left">BRANCH</td>
                                <td style="padding: 7px 7px !important;"  class="text-left">{{$branchName}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;background-color:#8080804f;"  colspan="2">VENDOR NAME</td>
                                <td  style="padding: 7px 7px !important;background-color:#8080804f;" colspan="3">DELIVERY ADDRESS</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;" class="text-left">VENDOR NAME</td>
                                <td style="padding: 7px 7px !important;"  class="text-center" class="text-left">{{$pOrder->name}}</td>
                                <td  style="padding: 7px 7px !important;" class="text-left">COMPANY</td>
                                <td  style="padding: 7px 7px !important;" class="text-left" colspan="2">Aaryans World School</td>
                            </tr>
                            <tr>
                                <td  style="padding: 7px 7px !important;" class="text-left">CONTACT</td>
                                <td style="padding: 7px 7px !important;"  class="text-left">{{$pOrder->landlineNo}}</td>
                                <td  style="padding: 7px 7px !important;" style="padding: 7px 7px !important;" class="text-left" rowspan="2">ADDRESS</td>
                                <td  style="padding: 7px 7px !important;" class="text-left" rowspan="2" colspan="2">{{$pOrder->shippingAddress}}</td>
                            </tr>
                            <tr>
                                <td  style="padding: 7px 7px !important;" class="text-left">ADDRESS</td>
                                <td  style="padding: 7px 7px !important;" class="text-left">{{$pOrder->address}}</td>
                            </tr>
                            <tr>
                                <td  style="padding: 7px 7px !important;" class="text-left">PAN No</td>
                                <td  style="padding: 7px 7px !important;" class="text-left">{{$pOrder->PANNO}}</td>
                                <td  style="padding: 7px 7px !important;" class="text-left">GST No</td>
                                <td  style="padding: 7px 7px !important;" class="text-left" colspan="2">{{$pOrder->GSTNo}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;background-color:#8080804f;"  colspan="2">REQUISITIONER</td>
                                <td  style="padding: 7px 7px !important;background-color:#8080804f;" colspan="3">AUTHORITY NAME</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;"  colspan="2">ABC</td>
                                <td  style="padding: 7px 7px !important;" colspan="3">MANAGER</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;" width="100%" colspan="5" class="text-center">{{$pOrder->quotationFor}}</td>
                            </tr>
                            <tr >
                                <td  style="padding: 7px 7px !important;background-color:#8080804f;" width="15%">SR. NO.</td>
                                <td  style="padding: 7px 7px !important;background-color:#8080804f;">DESCRIPTION</td>
                                <td style="padding: 7px 7px !important;background-color:#8080804f;" width="10%" >QTY</td>
                                <td style="padding: 7px 7px !important;background-color:#8080804f;"  width="10%">RATE</td>
                                <td  style="padding: 7px 7px !important;background-color:#8080804f;" width="10%">TOTAL</td>
                            </tr>
                                <?php $products = $util->getQuotProdList($pOrder->quotationId); $k=1;$total=0; ?>
                                @if(count($products))
                                    @foreach($products as $product)
                                        <tr  class="success">
                                            <td style="padding: 7px 7px !important;">{{$k++}}</td>
                                            <td style="padding: 7px 7px !important;" class="text-left">{{$product->name}}</td>
                                            <td style="padding: 7px 7px !important;">{{$product->qty}}</td>
                                            <td style="padding: 7px 7px !important;">{{$util->numberFormat($product->unitPrice)}}</td>
                                            <td style="padding: 7px 7px !important;">{{$util->numberFormat($product->amount)}}
                                               <?php $total = $total + $product->amount; ?></td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tr>
                            <tr >
                                <td style="padding: 7px 7px !important;background-color:#8080804f;" colspan="3">Comments or special instructions</td>
                                <td style="padding: 7px 7px !important;">Sub Total</td>
                                <td  style="padding: 7px 7px !important;">{{$pOrder->totalRs}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;" colspan="3" rowspan="6">
                                    <?php $i=1;?>
                                    <table width="100%">
                                        <tr>
                                            <td style="padding: 7px 7px !important;">Sr. No</td>
                                            <td style="padding: 7px 7px !important;">Percent of Payment</td>
                                            <td style="padding: 7px 7px !important;">Payment Date</td>
                                            <td style="padding: 7px 7px !important;">Amount</td>
                                            <td style="padding: 7px 7px !important;">Transaction Number</td>
                                        </tr>
                                        @foreach($payments as $payment)
                                            <tr>
                                                <td style="padding: 7px 7px !important;">Payment {{$i++}}</td>
                                                <td style="padding: 7px 7px !important;">{{$payment->percent}}%</td>
                                                <td style="padding: 7px 7px !important;">{{date('d-m-Y', strtotime($payment->forDate))}}</td>
                                                <td style="padding: 7px 7px !important;">{{$util->numberFormat($payment->amount)}}</td>
                                                <td style="padding: 7px 7px !important;">{{($payment->transactionId != '')?$payment->transactionId:'Pending'}}</td>
                                            </tr>
                                        @endforeach
                                    </table>                                        
                                </td>
                                <td style="padding: 7px 7px !important;"  >GST Amount</td>
                                <td  style="padding: 7px 7px !important;">0</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;">Discount</td>
                                <td  style="padding: 7px 7px !important;">0</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;">Transportation Rs.</td>
                                <td style="padding: 0px 17px !important;">{{$pOrder->transportationRs}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;">Loading Rs.</td>
                                <td style="padding: 0px 17px !important;">{{$pOrder->loadingRs}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;">Unloading Rs.</td>
                                <td style="padding: 0px 17px !important;">{{$pOrder->unloadingRs}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;">Net Amount</td>
                                <td style="padding: 7px 7px !important;" >{{$util->numberFormat($pOrder->finalRs)}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;" width="100%" colspan="5" class="text-center">If you have any questions on this quoation about this Purchase order please contact</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;" width="100%" colspan="5" class="text-center">prch.exe2@aaryansworldschool.org / prch.exe3@aaryansworldschool.org</td>
                            </tr>
                            <tr>
                                <td style="padding: 7px 7px !important;" width="100%" colspan="5" class="text-center">Made By : {{$util->getQuotRaisedBy($pOrder->raisedBy)}}&nbsp;&nbsp;&nbsp;|&nbsp;Purchase By: </td>
                            </tr>
                        </table> 
                          <a href="/purchaseOrder/printPO/{{$pOrder->id}}" class="btn btn-danger mt-3" target="_blank"><i class="fa fa-print"></i>Print PO<a> 
                    </div>
                </div>  
            </div>
        </div>
    </div>
@endsection