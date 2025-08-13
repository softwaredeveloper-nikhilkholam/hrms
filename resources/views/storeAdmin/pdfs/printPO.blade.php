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
                    <td colspan="4"  style="text-align: center;"><b style="font-size:18px;">PURCHASE ORDER</b></td>
                    <td style="text-align: center;"><b style="font-size:18px;">{{date('Y')}} - {{date('Y', strtotime('+1 year'))}}</b></td>
                </tr>
                <tr>
                    <td style="padding: 7px 7px !important;text-align: left;" colspan="2" >{{$pOrder->tempTypeOfCompany}}</td>
                    <td style="padding: 7px 7px !important;text-align: left;"   colspan="2">Date</td>
                    <td  style="padding: 7px 7px !important;text-align: center;" >{{date('d-m-Y', strtotime($pOrder->generatedDate))}}</td>
                </tr>
                <tr>
                    <td style="padding: 7px 7px !important;text-align: left;"  colspan="2">E-2 PATANG PLAZA, KATRAJ, 411024</td>
                    <td style="padding: 7px 7px !important;text-align: left;"  colspan="2">BILL NO</td>
                    <td style="padding: 7px 7px !important;"  class="text-left"></td>
                </tr>
                <tr>
                    <td style="padding: 7px 7px !important;text-align: left;"  colspan="2">PHONE NO. : 9673004488</td>
                    <td style="padding: 7px 7px !important;text-align: left;"  colspan="2">PO NO</td>
                    <td style="padding: 7px 7px !important;"  class="text-left">{{$pOrder->poNumber}}</td>
                </tr>
                <tr>
                    <td style="padding: 7px 7px !important;text-align: left;"  colspan="2">LANDLINE NO. : 9673004488</td>
                    <td style="padding: 7px 7px !important;text-align: left;" colspan="2">REQ NO</td>
                    <td style="padding: 7px 7px !important;"  class="text-left">{{$pOrder->reqNo}}</td>
                </tr>
                <tr>
                    <td style="padding: 7px 7px !important;text-align: left;"  colspan="2">Website: www.aaraynsworldschool.com</td>
                    <td style="padding: 7px 7px !important;text-align: left;"  colspan="2">BRANCH</td>
                    <td style="padding: 7px 7px !important;text-align: left;"  class="text-left">BW</td>
                </tr>
                <tr>
                    <td style="padding: 7px 7px !important;background-color:#8080804f;"  colspan="2">VENDOR NAME</td>
                    <td  style="padding: 7px 7px !important;background-color:#8080804f;" colspan="3">DELIVERY ADDRESS</td>
                </tr>
                <tr>
                    <td style="padding: 7px 7px !important;" class="text-left">VENDOR NAME</td>
                    <td style="padding: 7px 7px !important;text-align: center;"  class="text-center" class="text-left">{{$pOrder->name}}</td>
                    <td  style="padding: 7px 7px !important;text-align: left;" class="text-left">COMPANY</td>
                    <td  style="padding: 7px 7px !important;text-align: left;" class="text-left" colspan="2">Aaryans World School</td>
                </tr>
                <tr>
                    <td  style="padding: 7px 7px !important;text-align: left;" class="text-left">CONTACT</td>
                    <td style="padding: 7px 7px !important;text-align: left;"  class="text-left">{{$pOrder->landlineNo}}</td>
                    <td  style="padding: 7px 7px !important;text-align: left;" style="padding: 7px 7px !important;" class="text-left" rowspan="2">ADDRESS</td>
                    <td  style="padding: 7px 7px !important;text-align: left;" class="text-left" rowspan="2" colspan="2">{{$pOrder->shippingAddress}}</td>
                </tr>
                <tr>
                    <td  style="padding: 7px 7px !important;text-align: left;" class="text-left">ADDRESS</td>
                    <td  style="padding: 7px 7px !important;text-align: left;" class="text-left">{{$pOrder->address}}</td>
                </tr>
                <tr>
                    <td  style="padding: 7px 7px !important;text-align: left;" class="text-left">PAN No</td>
                    <td  style="padding: 7px 7px !important;text-align: left;" class="text-left">{{$pOrder->PANNO}}</td>
                    <td  style="padding: 7px 7px !important;text-align: left;" class="text-left">GST No</td>
                    <td  style="padding: 7px 7px !important;text-align: left;" class="text-left" colspan="2">{{$pOrder->GSTNo}}</td>
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
                    <td style="padding: 7px 7px !important;text-align: center;" width="100%" colspan="5" class="text-center">{{$pOrder->quotationFor}}</td>
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
                                <td style="padding: 7px 7px !important;text-align: left;" class="text-left">{{$product->name}}</td>
                                <td style="padding: 7px 7px !important;">{{$product->qty}}</td>
                                <td style="padding: 7px 7px !important;">{{$product->unitPrice}}</td>
                                <td style="padding: 7px 7px !important;">{{$product->amount}}
                                    <?php $total = $total + $product->amount; ?></td>
                            </tr>
                        @endforeach
                    @endif
                </tr>
                <tr >
                    <td style="padding: 7px 7px !important;background-color:#8080804f;" colspan="3">Comments or special instructions</td>
                    <td style="padding: 7px 7px !important;">Sub Total</td>
                    <td  style="padding: 7px 7px !important;">{{$total}}</td>
                </tr>
                <tr>
                    <td style="padding: 7px 7px !important;" colspan="3" rowspan="6"></td>
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
                    <td style="padding: 7px 7px !important;text-align: center;" width="100%" colspan="5" class="text-center">If you have any questions on this quoation about this Purchase order please contact</td>
                </tr>
                <tr>
                    <td style="padding: 7px 7px !important;text-align: center;" width="100%" colspan="5" class="text-center">prch.exe2@aaryansworldschool.org / prch.exe3@aaryansworldschool.org</td>
                </tr>
                <tr>
                    <td style="padding: 7px 7px !important;text-align: center;" width="100%" colspan="5" class="text-center">Made By : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Purchase By: </td>
                </tr>
            </table>
            <table style="border: 0px solid white !important;">
                <tr style="border: 0px solid white !important;">
                    <td style="border: 0px solid white !important;"><p style="margin-top:0px;text-align:center;font-size:10px;"><b>Note: System Generated PO</b></p></td>
                </tr>
            </table>

            </div>
        </div>
    </body>
</html>