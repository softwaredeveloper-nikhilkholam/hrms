@php($data = storage_path('fonts/gargi.ttf'))
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
    <style>
        @font-face {
            src: url("{{$data}}") format('truetype');
            font-family: "gargi";
        }
        
        body {
            font-family: gargi, dejvu sans, sans-serif;
        }
        
        table,
        td,
        th {
            border: 1px solid #ddd;
            text-align: left;
        }
        
        table {
            border-collapse: collapse;
            width: 100%;
        }
        
        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        
        th {
            background-color: #dddddd;
            color: #000;
        }
    </style>
</head>
<body>
    <?php
    $userType = Auth::user()->userType;
    use App\Helpers\Utility;
    $util=new Utility(); 
    ?>
    <div class="container">
        <table width="100%">
            <tr>
                <td style="text-align:center;background-color:#D3D3D3;padding:1px;"><H5>REQUISITION</H5></td>
            </tr>
        </table><br>       
        <table width="100%">
            <tr>
                <td style="text-align:center;">
                    <b style="font-size:12px;">Requisition Date</b>
                </td>
                <td style="text-align:center;">
                    <b style="font-size:12px;">Requisition No.</b>
                </td>
                <td style="text-align:center;">
                    <b style="font-size:12px;">Branch Name</b>
                </td>
                <td style="text-align:center;">
                    <b style="font-size:12px;">Requisitioner Name</b>
                </td>
                <td style="text-align:center;">
                    <b style="font-size:12px;">Department</b>
                </td>
            </tr>
            <tr>
                <td style="text-align:center;">
                    <b style="font-size:10px;">{{ date('d-m-Y', strtotime($requisition->requisitionDate)) }}</b>
                </td>
                <td style="text-align:center;">
                    <b style="font-size:10px;">{{ $requisition->requisitionNo }}</b>
                </td>
                <td style="text-align:center;">
                    <b style="font-size:10px;">{{ $requisition->branchName }}</b>
                </td>
                <td style="text-align:center;">
                    <b style="font-size:10px;">{{ $requisition->requisitionerName }}</b>
                </td>
                <td style="text-align:center;">
                    <b style="font-size:10px;">{{ $requisition->departmentName }}</b>
                </td>
            </tr>
        </table><br>
        <table width="100%">
            <tr>
                <td style="text-align:center;">
                    <b style="font-size:12px;">Date of Requirement</b>
                </td>
                <td style="text-align:center;">
                    <b style="font-size:12px;">Sevirity</b>
                </td>
                <td style="text-align:center;">
                    <b style="font-size:12px;">Deliver To</b>
                </td>
                <td style="text-align:center;">
                    <b style="font-size:12px;">Authority Name</b>
                </td>               
            </tr>
            <tr>
                <td style="text-align:center;">
                    <b style="font-size:10px;">{{ date('d-m-Y', strtotime($requisition->dateOfRequirement)) }}</b>
                </td>
                <td style="text-align:center;">
                    <b style="font-size:10px;">{{ ($requisition->sevirity == 1)?'NORMAL':(($requisition->sevirity == 2)?'URGENT':'VERY URGENT') }}</b>
                </td>
                <td style="text-align:center;">
                    <b style="font-size:10px;">{{ $requisition->deliveryTo }}</b>
                </td>
                <td style="text-align:center;">
                    <b style="font-size:10px;">{{ $requisition->authorityName }}</b>
                </td>               
            </tr>
        </table><br>
        <table width="100%">
            <tr>
                <td style="text-align:center;" colspan='5'>
                    <b style="font-size:12px;">Event Details / Requisition For</b>
                </td>               
            </tr>
            <tr>
                <td style="text-align:center;" colspan='5'>
                    <b style="font-size:10px;">{{ $requisition->requisitionFor }}</b>
                </td>               
            </tr>
        </table>
        <br>
        <table width="100%">
            <tr>
                <td style="text-align:center;" colspan='5'>
                    <b style="font-size:12px;">Required Products</b>
                </td>               
            </tr>
            <tr>
                <td style="text-align:center;" colspan='5'>
                    <table width="100%">
                        <tr>
                            <td style="font-size:12px;">No.</td>
                            <td style="font-size:12px;">Product</td>
                            <td style="font-size:12px;">Location</td>
                            <td style="font-size:12px;">Qty</td>
                            <td style="font-size:12px;">Rs.<?php $i=1;$total= $totalQty=0; ?></td>
                            <td style="font-size:12px;">Status</td>
                        </tr>
                        @foreach($prodList as $product)
                            @if($product->productType == 1)
                                <?php $productDetail = $util->getProductDetail($product->productId);?>
                                @if($productDetail)
                                    <tr>
                                        <td style="font-size:12px;">{{$i++}}</td>
                                        <td style="font-size:12px;" class="text-left">{{$productDetail->productName}}</td>
                                        <td style="font-size:12px;" class="text-left">Hall : {{$productDetail->hallName}}&nbsp;|&nbsp;Rack : {{$productDetail->rackName}}&nbsp;|&nbsp;Shelf : {{$productDetail->shelfName}}</td>
                                        <td style="font-size:12px;">{{$util->numberFormat($product->qty)}}&nbsp;{{$product->unitName}}<?php $totalQty = $totalQty + $product->qty; ?></td>
                                        <td style="font-size:12px;">{{$util->numberFormat($productDetail->productRate*$product->qty)}}<?php $total = $total + ($productDetail->productRate*$product->qty)?></td>                                    
                                        <td style="font-size:12px;">{{($product->status == 0)?'Pending':(($product->status == 1)?'Delivered':(($product->status == 2)?'Rejected':'Hold'))}}</td>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td style="font-size:12px;">{{$product->productName}}<br>
                                                                Description :{{$product->description}}<br>
                                                                Return :YES
                                    </td>
                                    <td style="font-size:12px;">{{($product->productType == 2)?'New Product':'Rental Product'}}</td>
                                    <td style="font-size:12px;">{{$util->numberFormat($product->qty)}}&nbsp;&nbsp;{{$product->unitName}}<?php $totalQty = $totalQty + $product->qty; ?></td>
                                    <td style="font-size:12px;">{{$util->numberFormat($product->productRate*$product->requiredQty)}}<?php $total = $total + ($product->productRate*$product->requiredQty)?></td>                                    
                                    <td style="font-size:12px;">{{($product->status == 0)?'Pending':(($product->status == 1)?'Delivered':(($product->status == 2)?'Rejected':'Hold'))}}<br>
                                    <br>{{($product->status == 2)?$product->storeRejectReason:''}}</td>
                                </tr>
                            @endif
                        @endforeach
                        <tr class="ligth ligth-data">
                            <th style="font-size:12px;" colspan="3">Total</th>
                            <th style="font-size:12px;">{{$util->numberFormat($totalQty)}}</th>
                            <th style="font-size:12px;">Rs. {{$util->numberFormat($total)}}</th>     
                            <th></th>                               
                        </tr>
                    </table>
                </td>               
            </tr>
        </table>                
    </body>
</html>