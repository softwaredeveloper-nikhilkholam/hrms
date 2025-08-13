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
        <table style="border: 0px solid white !important;">
            <tr style="border: 0px solid white !important;">
                <td style="border: 0px solid white !important;"><p style="margin-top:0px;text-align:left;font-size:10px;">Generated At : {{date('d/m/Y h:i A')}}</p>   </td>
                <td style="border: 0px solid white !important;"><p style="margin-top:0px;text-align:right;font-size:10px;"><b>Aaryans World</b></p></td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td width="25%" style="text-align:center;padding: 3px 3px !important;padding:1px;font-size:10px;"><img src="storeAdmin/images/230921120510.png" style="height:50px;width:100px;"></td>
                <td width="50%" style="text-align:center;padding: 3px 3px !important;background-color:#D3D3D3;padding:1px;"><H2>Outward Slip</H2></td>
                <td width="25%" style="text-align:center;padding: 3px 3px !important;padding:1px;">
                    <img src="data:image/png;base64, {!! base64_encode(\QrCode::size(80)->generate("https://hrms.aaryansworld.com//outwards/showDetails/".$outward->id)) !!} ">
                </td>
            </tr>
        </table>   
        <table width="100%">
            <tr>
                <td style="text-align:center;padding: 3px 3px !important;">
                    <b style="font-size:10px;">Requisition Date</b>
                </td>
                <td style="text-align:center;padding: 3px 3px !important;">
                    <b style="font-size:10px;">Outward No.</b>
                </td>
                <td style="text-align:center;padding: 3px 3px !important;">
                    <b style="font-size:10px;">From Branch</b>
                </td>
                <td style="text-align:center;padding: 3px 3px !important;">
                    <b style="font-size:10px;">To Branch</b>
                </td>                
            </tr>
            <tr>
                <td style="text-align:center;padding: 3px 3px !important;">
                    <b style="font-size:10px;">{{ date('d-m-Y', strtotime($requisition->requisitionDate)) }}</b>
                </td>
                <td style="text-align:center;padding: 3px 3px !important;">
                    <b style="font-size:10px;">{{ $outward->receiptNo }}</b>
                </td>
                <td style="text-align:center;padding: 3px 3px !important;">
                    <b style="font-size:10px;">BW</b>
                </td>
                <td style="text-align:center;padding: 3px 3px !important;">
                    <b style="font-size:10px;">{{ $requisition->deliveryTo }}</b>
                </td>
            </tr>
            <tr>
                <td style="text-align:center;padding: 3px 3px !important;" colspan="4">
                    <b style="font-size:10px;">Requisition For</b>
                </td>
            </tr>
           
            <tr>
                <td style="text-align:center;padding: 3px 3px !important;" colspan="4">
                    <b style="font-size:10px;">{{ $requisition->requisitionFor }}</b>
                </td>
            </tr>
           
            <tr>
                <td style="text-align:center;padding: 3px 3px !important;" colspan="2">
                    <b style="font-size:10px;">Given By</b>
                </td>
                <td style="text-align:center;padding: 3px 3px !important;" colspan="2">
                    <b style="font-size:10px;">handler</b>
                </td>
            </tr>
            <tr>
                <td style="text-align:center;padding: 3px 3px !important;" colspan="2">
                    <b style="font-size:10px;">BW Store</b>
                </td>
                <td style="text-align:center;padding: 3px 3px !important;" colspan="2">
                    <b style="font-size:10px;">{{ $requisition->requisitionerName }} <br>{{ $requisition->departmentName }}</b>
                </td>
            </tr>
        </table>
        <table width="100%"  style="page-break-inside: always;">
            <tr style="page-break-inside: always;">
                <td style="text-align:center;padding: 3px 3px !important;page-break-inside: always;">
                    <b style="font-size:11px;page-break-inside: always;">No</b>
                </td>
                <td style="text-align:center;padding: 3px 3px !important;page-break-inside: always;">
                    <b style="font-size:11px;page-break-inside: always;">Product Detail</b>
                </td>
                <td style="text-align:center;padding: 3px 3px !important;page-break-inside: always;">
                    <b style="font-size:11px;page-break-inside: always;">Qty<?php $i=1;$totalQty=0; ?></b>
                </td>
                <td style="text-align:center;padding: 3px 3px !important;page-break-inside: always;">
                    <b style="font-size:11px;page-break-inside: always;">Status</b>
                </td>
            </tr>
            @foreach($prodList as $product)
                <tr style="page-break-inside: always;">
                    <td style="font-size:10px;text-align:center;padding: 3px 3px !important;page-break-inside: always;">{{$i++}}</td>
                    <td style="font-size:10px;" class="text-leftpage-break-inside: always;">{{$product->productMName}} | {{$product->categoryName}} | {{$product->subCategoryName}} | {{($product->color == '')?'-':$product->color}} | {{($product->size == '')?'-':$product->size}}
                    | Hall: {{$product->hallName}} | Rack: {{$product->rackName}} | Shelf: {{$product->shelfName}}</td>
                    <td style="font-size:10px;text-align:center;padding: 3px 3px !important;page-break-inside: always;">{{$util->numberFormat($product->receivedQty)}}&nbsp;{{$product->unitName}}<?php $totalQty = $totalQty + $product->receivedQty; ?></td>
                    <td style="font-size:10px;" class="text-leftpage-break-inside: always;">
                    {{($product->status == 1)?'Outward Generated':(($product->status == 2)?'Delivered':(($product->status == 7)?'Out for Delivery':''))}}
                </td>
                </tr>
            @endforeach
            <tr style="page-break-inside: always;">
                <td colspan="2" style="font-size:10px;text-align:center;padding: 3px 3px !important;page-break-inside: always;">Total</td>
                <td style="font-size:10px;text-align:center;padding: 3px 3px !important;page-break-inside: always;">{{$util->numberFormat($totalQty)}}</td>
                <td></td>
            </tr>
        </table>
    </body>
</html>