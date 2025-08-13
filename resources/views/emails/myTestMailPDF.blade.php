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
            font-size:12px;
            padding: 10px; border: 2px solid #ddd;
        }
        
        table,
        td,
        th {
            border: 1px solid #ddd;
        }
        
        table {
            border-collapse:</b> collapse;
            width: 100%;
        }
        
        th,
        td {
            border: 1px solid #ddd;
            padding: 1px;
        }
        
        th {
            background-color:</b> #dddddd;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <table width="100%">
                <tbody>
                    <tr>
                        <td>
                        <table width="100%" style="border: 0px solid #ddd;">
                            <tbody>
                                <tr style="border: 0px solid #ddd;">
                                    <td width="25%" style="border: 0px solid #ddd;">
                                        <img src="{{ public_path('/landingPage/img/ctc.jpg') }}" style="width: 200px; height: 100px">
                                    </td>
                                    <td width="75%" style="border: 0px solid #ddd;">
                                        <table width="100%" style="border: 0px solid #ddd;">
                                            <tbody>
                                                <tr style="border: 0px solid #ddd;margin-bottom:0px;">
                                                    <td style="border: 0px solid #ddd;font-size:23px;" colspan="2"><b><center>Ellora Medicals & Educational Foundation</center></b></td>
                                                </tr>
                                                <tr style="border: 0px solid #ddd;">
                                                    <td style="border: 0px solid #ddd;font-size:17px;" colspan="2"><b><center>AARYANS COMMANDO TRAINING CAMP, PUNE</center></b></td>
                                                </tr>
                                                <tr style="border: 0px solid #ddd;">
                                                    <td style="border: 0px solid #ddd;" colspan="2"><center>Address- Aaryans Valley, Satara Road, Near to R.K. Transport, Before Old Katraj Ghat,</center></td>
                                                </tr>
                                                <tr style="border: 0px solid #ddd;">
                                                    <td style="border: 0px solid #ddd;" colspan="2"><center>Bhilarewadi, Pune- 411046</center></td>
                                                </tr>
                                                <tr style="border: 0px solid #ddd;">
                                                    <td style="border: 0px solid #ddd;">Contact No - 9595800200</td>
                                                    <td style="border: 0px solid #ddd;text-align:right;">Email ID- commandokids.aws@gmail.com</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                            </tbody>                
                        </table>
                        <hr>
                        <table width="100%" style="border: 0px solid #ddd;">
                            <tbody>
                                <tr style="border: 0px solid #ddd;">
                                    <td width="50%" style="border: 0px solid #ddd;text-align:left;"><b>Receipt Number : {{$receiptNo}}</b></td>
                                    <td width="50%" style="border: 0px solid #ddd;text-align:right;"><b>Date : {{date('d M Y')}}</b></td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <table width="100%" style="border: 0px solid #ddd;">
                            <tbody>
                                <tr style="border: 0px solid #ddd;">
                                    <td style="border: 0px solid #ddd;">
                                        <table width="100%" style="border: 0px solid #ddd;">
                                            <tbody>
                                                <tr style="border: 0px solid #ddd;">
                                                    <td style="border: 0px solid #ddd;">{{($gender == 'Male')?'Master':'Miss'}}</td>
                                                    <td style="border: 0px solid #ddd;">: {{$name}}</td>
                                                    <td style="border: 0px solid #ddd;"></td>
                                                </tr>
                                                <tr style="border: 0px solid #ddd;">
                                                    <td style="border: 0px solid #ddd;">Batch</td>
                                                    <td style="border: 0px solid #ddd;">: {{$batch}}</td>
                                                    <td style="border: 0px solid #ddd;"></td>
                                                </tr>
                                                <tr style="border: 0px solid #ddd;">
                                                    <td style="border: 0px solid #ddd;">Paid For</td>
                                                    <td style="border: 0px solid #ddd;">: {{$payFor}}</td>
                                                    <td style="border: 0px solid #ddd;"></td>
                                                </tr>
                                                <tr style="border: 0px solid #ddd;">
                                                    <td style="border: 0px solid #ddd;">Amount (Rs.)</td>
                                                    <td style="border: 0px solid #ddd;">: {{$rs}}</td>
                                                    <td style="border: 0px solid #ddd;"></td>
                                                </tr>
                                                <tr style="border: 0px solid #ddd;">
                                                    <td style="border: 0px solid #ddd;">Remark</td>
                                                    <td style="border: 0px solid #ddd;">: </td>
                                                    <td style="border: 0px solid #ddd;"></td>
                                                </tr>
                                                <tr style="border: 0px solid #ddd;">
                                                    <td style="border: 0px solid #ddd;font-size:17px;" colspan="3"></td>
                                                </tr>
                                                <tr style="border: 0px solid #ddd;">
                                                    <td style="border: 0px solid #ddd;font-size:17px;" colspan="3"></td>
                                                </tr>
                                                <tr style="border: 0px solid #ddd;">
                                                    <td style="border: 0px solid #ddd;font-size:17px;" colspan="3"><center><b>Net Banking Details</b></center></td>
                                                </tr>
                                                <tr style="border: 0px solid #ddd;">
                                                    <td style="border: 0px solid #ddd;" colspan="3">
                                                        <table>
                                                            <tr>
                                                                <th width="5%"><center>No.</center></th>
                                                                <th width="45%"><center>Transaction ID</center></th>
                                                                <th width="15%"><center>Date</center></th>
                                                                <th width="20%"><center>Bank / Card Name</center></th>
                                                                <th width="15%"><center>Amount (Rs.)</center></th>
                                                            </tr>
                                                            <tr>
                                                                <td><center>1</center></td>
                                                                <td><center>{{$transId}}</center></td>
                                                                <td><center>{{date('d-M-Y')}} </center></td>
                                                                <td><center>{{$PAYMENTMODE}}</center></td>
                                                                <td><center>{{$TXNAMOUNT}}</center></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>                
                                        </table>
                                        <br><br><br><br><br><br>
                                        <table style="border: 0px solid #ddd;">
                                            <tr style="border: 0px solid #ddd;">
                                                <td style="border: 0px solid #ddd;"><center><b>ERP Department</b></center></td>
                                                <td style="border: 0px solid #ddd;"><center><b>Account Department</b></center></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>                
                        </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
    </body>
</html>