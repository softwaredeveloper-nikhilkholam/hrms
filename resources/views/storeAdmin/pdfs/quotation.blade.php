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
                    <td width="100%" style="text-align: center;background-color:#C5C5C5;color:black;"><b style="font-size:18px;">Quotation {{$quotation->quotNo}}</b></td>
                </tr>
            </table>

            <table style="border:1px;">
                <tr>
                    <td width="80%" style="margin-top:0px;">
                        <table style="border:1px;">
                            <tr>
                                <td width="30%"><b>Name & Address of Company</b></td>
                                <td>{{$quotation->name}}<br>{{$quotation->address}}</td>
                                <td width="30%"><b>Date</b></td>
                                <td>{{date('d-m-Y', strtotime($quotation->validDate))}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Type of Company</b></td>
                                <td>{{$quotation->typeOfCompany}}</td>
                                <td width="30%"><b>Terms of payment</b></td>
                                <td>{{$quotation->termsOfPayment}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Bank Details</b></td>
                                <td>{{$quotation->accountNo}}<br>{{$quotation->IFSCCode}}<br>{{$quotation->bankBranch}}</td>
                                <td width="30%"><b>Shipping Address</b></td>
                                <td>{{$quotation->shippingAddress}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Quotation For</b></td>
                                <td>{{$quotation->quotationFor}}</td>
                                <td width="30%"><b>Requisition No</b></td>
                                <td>{{$quotation->reqNo}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Address</b></td>
                                <td>{{$quotation->officeAddress}}</td>
                                <td width="30%"><b>Tentative delivery date</b></td>
                                <td>{{date('d-m-Y', strtotime($quotation->tentativeDate))}}</td>
                            </tr>
                            <tr>
                                <td width="100%" colspan="4">
                                    <h3 style="color:red;"><center>Product List</center></h3> 
                                </td>
                            </tr>
                            <tr>
                                <td width="100%"  colspan="4">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th style="font-size:12px;font-weight: bold;text-align:center;" width="5%"><b>No.</b></th>
                                                <th style="font-size:12px;font-weight: bold;text-align:center;"><b>Product</b></th>
                                                <th style="font-size:12px;font-weight: bold;text-align:center;" width="12%"><b>Quantity</b></th>
                                                <th style="font-size:12px;font-weight: bold;text-align:center;" width="12%"><b>Unit Price</b></th>
                                                <th style="font-size:12px;font-weight: bold;text-align:center;" width="12%"><b>Discount</b></th>
                                                <th style="font-size:12px;font-weight: bold;text-align:center;" width="12%"><b>CGST</b></th>
                                                <th style="font-size:12px;font-weight: bold;text-align:center;" width="12%"><b>SGST</b></th>
                                                <th style="font-size:12px;font-weight: bold;text-align:center;" width="12%"><b>Amount<?php $i=0; ?></b></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($products))
                                                @foreach($products as $product)
                                                    <tr>
                                                        <td style="font-size:12px;color:black;text-align:right;">{{$i+1}}</td>
                                                        <td style="font-size:12px;color:black;">{{$product->productCode}} - {{$product->name}}</td>
                                                        <td style="font-size:12px;color:black;text-align:right;">{{$product->qty}}</td>
                                                        <td style="font-size:12px;color:black;text-align:right;">{{$product->unitPrice}}</td>
                                                        <td style="font-size:12px;color:black;text-align:right;">{{$product->discount}}</td>
                                                        <td style="font-size:12px;color:black;text-align:right;">{{$product->cgst}}</td>
                                                        <td style="font-size:12px;color:black;text-align:right;">{{$product->sgst}}</td>
                                                        <td style="font-size:12px;color:black;text-align:right;">{{$product->amount}}</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="7" style="font-size:12px;color:black;text-align:right;" class="text-right"><b>Total Rs.</b></td>
                                                    <td style="font-size:12px;text-align:right;">{{$quotation->totalRs}}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" style="font-size:12px;color:black;text-align:right;" class="text-right"><b>Transportation Rs.</b></td>
                                                    <td style="font-size:12px;text-align:right;">{{$quotation->transportationRs}}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" style="font-size:12px;color:black;text-align:right;" class="text-right"><b>Loading Rs.</b></td>
                                                    <td style="font-size:12px;text-align:right;">{{$quotation->loadingRs}}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" style="font-size:12px;color:black;text-align:right;" class="text-right"><b>Unloading Rs.</b></td>
                                                    <td style="font-size:12px;text-align:right;">{{$quotation->unloadingRs}}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" style="font-size:12px;color:black;text-align:right;" class="text-right"><b>Final Rs.</b></td>
                                                    <td style="font-size:12px;text-align:right;">{{$quotation->finalRs}}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </div>
        </div>
    </body>
</html>