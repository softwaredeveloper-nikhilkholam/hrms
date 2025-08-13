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
            padding: 8px;
        }
        
        table {
            border-collapse: collapse;
            width: 100%;
            padding: 8px;
        }
        
        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size:14px;
            
        }
        
        th {
            background-color: #dddddd;
            color: #000;
            padding: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <table style="border: 0px solid white !important;">
                <tr style="border: 0px solid white !important;">
                    <td style="border: 0px solid white !important;"><p style="margin-top:0px;text-align:left;font-size:10px;">PDF Generated At : {{date('d/m/Y h:i A')}}</p>   </td>
                    <td style="border: 0px solid white !important;"><p style="margin-top:0px;text-align:right;font-size:10px;"><b>Aaryans World</b></p></td>
                </tr>
            </table>
            <?php
                use App\Helpers\Utility;
                $util=new Utility(); 
                $userType = Auth::user()->userType;
            ?>
            <table>
                <tr>
                    <td width="100%" style="text-align: center;background-color:#C5C5C5;color:black;"><b style="font-size:18px;">Inward - {{$inward->invoiceNo}}</b></td>
                </tr>
            </table>

            <table>
                <tr>
                    <td width="100%" style="color:black;padding: 8px 4px !important;font-size:11px;"><b>Vendor</b></td>
                    <td width="100%" style="color:black;padding: 8px 4px !important;font-size:11px;"><b>{{$inward->name}}</b></td>
                    <td width="100%" style="color:black;padding: 8px 4px !important;font-size:11px;"><b>Vendor Address</b></td>
                    <td width="100%" style="color:black;padding: 8px 4px !important;font-size:11px;"><b>{{$inward->address}}</b></td>
                </tr>
                <tr>
                    <td width="100%" style="color:black;padding: 8px 4px !important;font-size:11px;"><b>Date</b></td>
                    <td width="100%" style="color:black;padding: 8px 4px !important;font-size:11px;"><b>{{date('d-m-Y', strtotime($inward->forDate))}}</b></td>
                    <td width="100%" style="color:black;padding: 8px 4px !important;font-size:11px;"><b>PO No</b></td>
                    <td width="100%" style="color:black;padding: 8px 4px !important;font-size:11px;"><b>{{$inward->poNumber}}</b></td>
                </tr>
                <tr>
                    <td width="100%" style="color:black;padding: 8px 4px !important;font-size:11px;"><b>Bill No</b></td>
                    <td width="100%" style="color:black;padding: 8px 4px !important;font-size:11px;"><b>{{$inward->billNo}}</b></td>
                    <td width="100%" style="color:black;padding: 8px 4px !important;font-size:11px;"><b>Invoice No</b></td>
                    <td width="100%" style="color:black;padding: 8px 4px !important;font-size:11px;"><b>{{$inward->invoiceNo}}</b></td>
                </tr>
                <tr>
                    <td width="100%" style="color:black;padding: 8px 4px !important;font-size:11px;"><b>Requsition Number</b></td>
                    <td width="100%" style="color:black;padding: 8px 4px !important;font-size:11px;"><b>{{$inward->reqNo}}</b></td>
                    <td width="100%" style="color:black;padding: 8px 4px !important;font-size:11px;"><b>GRN Number</b></td>
                    <td width="100%" style="color:black;padding: 8px 4px !important;font-size:11px;"><b>{{$inward->id}}</b></td>
                </tr>
                <tr>
                    <td width="100%" style="color:black;padding: 8px 4px !important;font-size:11px;"><b>Secuirty Gate No.</b></td>
                    <td width="100%" style="color:black;padding: 8px 4px !important;font-size:11px;"><b>{{$inward->securityGateNo}}</b></td>
                    <td width="100%" style="color:black;padding: 8px 4px !important;font-size:11px;"></td>
                    <td width="100%" style="color:black;padding: 8px 4px !important;font-size:11px;"></td>
                </tr>
            </table>

                <hr>
                <table width="100%">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th style="padding: 8px 4px !important;font-size:11px;" width="3%" class="text-center">No</th>
                            <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center" width="20%">Product</th>
                            <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">Expiry</th>
                            <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">HSN</th>
                            <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">Unit</th>
                            <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">Qty</th> 
                            <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">Rate</th>
                            <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">Gross Rs.</th>   
                            <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">CGST %</th>   
                            <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">CGST Rs.</th>
                            <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">SGST %</th>   
                            <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">SGST Rs.</th> 
                            <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">IGST %</th>   
                            <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">IGST Rs.</th>     
                            <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">Total<?php $k=1;$tot=$totgrossAmount=$totCGSTRs=$totSGSTRs=$totIGSTRs=0; ?></th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body" id="" style="font-size:12px;">
                        @if(count($productList))
                            @foreach($productList as $product)
                                <tr  class="success">
                                    <td style="padding: 7px 7px !important;font-size:11px;">{{$k++}}<input type="hidden" value="{{$product->productId}}" name="productId[]"></td>
                                    <td style="padding: 7px 7px !important;font-size:11px;" class="text-left">{{$product->name}}<br>Color: {{$product->color}} | Size: {{$product->size}}
                                <br>Hall: {{$product->hallName}} | Rack: {{$product->rackName}} | Shelf: {{$product->shelfName}}</td>
                                    <td style="padding: 7px 7px !important;font-size:11px;">{{($product->expiryDate == '')?'-':date('d-m-Y', strtotime($product->expiryDate))}}</td>                                    
                                    <td style="padding: 7px 7px !important;font-size:11px;">{{$product->HSNCode}}</td>                                    
                                    <td style="padding: 7px 7px !important;font-size:11px;">{{$product->unitName}}</td>                                    
                                    <td style="padding: 7px 7px !important;font-size:11px;">{{$product->qty}}</td>                                    
                                    <td style="padding: 7px 7px !important;font-size:11px;">{{$product->rate}}</td>                                    
                                    <td style="padding: 7px 7px !important;font-size:11px;">{{$product->grossAmount}}<?php $totgrossAmount = $totgrossAmount + $product->grossAmount; ?></td>                                    
                                    <td style="padding: 7px 7px !important;font-size:11px;">{{$product->CGSTPercent}}</td>                                    
                                    <td style="padding: 7px 7px !important;font-size:11px;">{{$product->CGSTRs}}<?php $totCGSTRs = $totCGSTRs + $product->CGSTRs; ?></td>                                    
                                    <td style="padding: 7px 7px !important;font-size:11px;">{{$product->SGSTPercent}}</td>                                    
                                    <td style="padding: 7px 7px !important;font-size:11px;">{{$product->SGSTRs}}<?php $totSGSTRs = $totSGSTRs + $product->SGSTRs; ?></td>                                    
                                    <td style="padding: 7px 7px !important;font-size:11px;">{{$product->IGSTPercent}}</td>                                    
                                    <td style="padding: 7px 7px !important;font-size:11px;">{{$product->IGSTRs}}<?php $totIGSTRs = $totIGSTRs + $product->IGSTRs; ?></td>                                    
                                    <td style="padding: 7px 7px !important;font-size:11px;">{{$util->numberFormat($product->total)}}<?php $tot = $tot + $product->total; ?></td>                                    
                                </tr>
                            @endforeach
                                <tr class="ligth ligth-data">
                                    <th style="padding: 8px 4px !important;font-size:11px;" class="text-center" colspan="7">Total</th>
                                    <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center" width="7%">{{$totgrossAmount}}</th>   
                                    <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center"></th>   
                                    <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">{{$totCGSTRs}}</th>
                                    <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center"></th>   
                                    <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">{{$totSGSTRs}}</th> 
                                    <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center"></th>   
                                    <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">{{$totIGSTRs}}</th>     
                                    <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">{{$util->numberFormat($tot)}}</th>
                                </tr>
                                <tr class="ligth ligth-data">
                                    <th style="padding: 8px 4px !important;font-size:11px;" class="text-center" colspan="12"></th>
                                    <th style="padding: 8px 4px !important;font-size:11px;" class="text-center" colspan="2">Sub Total</th>
                                    <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">{{$util->numberFormat($tot)}}</th>
                                </tr>
                                <tr class="ligth ligth-data">
                                    <th style="padding: 8px 4px !important;font-size:11px;" class="text-center" colspan="12"></th>
                                    <th style="padding: 8px 4px !important;font-size:11px;" class="text-center" colspan="2">Discount</th>
                                    <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">{{$util->numberFormat($inward->discount)}}</th>
                                </tr> 
                                <tr class="ligth ligth-data">
                                    <th style="padding: 8px 4px !important;font-size:11px;" class="text-center" colspan="12"></th>
                                    <th style="padding: 8px 4px !important;font-size:11px;" class="text-center" colspan="2">GST Rs.</th>
                                    <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">{{$util->numberFormat($inward->gstRs)}}</th>
                                </tr>
                                <tr class="ligth ligth-data">
                                    <th style="padding: 8px 4px !important;font-size:11px;" class="text-center" colspan="12"></th>
                                    <th style="padding: 8px 4px !important;font-size:11px;" class="text-center" colspan="2">Labour Charages</th>
                                    <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center" width="5%">{{$util->numberFormat($inward->labCharges)}}</th>
                                </tr>
                                <tr class="ligth ligth-data">
                                    <th style="padding: 8px 4px !important;font-size:11px;" class="text-center" colspan="12"></th>
                                    <th style="padding: 8px 4px !important;font-size:11px;" class="text-center" colspan="2">Other Charges</th>
                                    <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center" width="5%">{{$util->numberFormat($inward->otherCharges)}}</th>
                                </tr>
                                <tr class="ligth ligth-data">
                                    <th style="padding: 8px 4px !important;font-size:11px;" class="text-center" colspan="12"></th>
                                    <th style="padding: 8px 4px !important;font-size:11px;" class="text-center" colspan="2">Total</th>
                                    <th  style="padding: 8px 4px !important;font-size:11px;" class="text-center">{{$util->numberFormat($inward->netTotal)}}</th>
                                </tr>
                        @endif
                    </tbody>
                </table>
                <table>
                    <tr>
                        <td width="100%" style="text-align: center;background-color:#C5C5C5;color:black;"><b style="font-size:10px;">Narration - {{$inward->narration}}</b></td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>