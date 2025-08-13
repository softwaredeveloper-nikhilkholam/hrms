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
            font-family: gargi, dejvu sans, Times New Roman;
        }
        
        table,
        td,
        th {
            border: 0px solid #ddd;
            text-align: left;
        }
        
        table {
            border-collapse: collapse;
            width: 100%;
        }
        
        th,
        td {
            border: 0px solid #ddd;
            padding: 8px;
        }
        
        th {
            background-color: #dddddd;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <?php
                use App\Helpers\Utility;
                $util=new Utility(); 
            ?>
                <table width="100%" id="pdfview">
                    <tbody>
                        <tr>
                            <td><img src="{{ public_path('admin/letterHeads/ellor.jpg') }}" style="width: 100%; height: 250px"></td>
                        </tr>
                    </tbody>
                </table>
                <div style="text-align: center">
                    <b style="font-size:20px;">
                        Offer Letter
                     <br><br>
                </div>
                <table width="100%" id="pdfview">
                    <tbody>
                        <tr>
                            <td>To, </td>
                        </tr>
                        <tr>
                            <td>{{$empDet->name}}</td>
                        </tr>
                        <tr>
                            <td>Dear Sir /Ma’am,</td>
                        </tr>
                        <tr>
                            <td>With reference to your application & subsequent interviews conducted with us, we are pleased to offer you the position of "{{$empDet->designationName}}" in Ellora Medicals & Educational Foundation’s Aaryans World School. </td>
                        </tr>
                        <tr>
                            <td>Your monthly salary as per the discussion with the authorities will be Rs. {{$util->numberFormatRound($empDet->salaryScale)}} /- per month. ({{$util->numberToWord($empDet->salaryScale)}} Only).
                                As discussed your date of Joining will be {{$util->dateFormat($empDet->jobJoingDate, 1)}}.
                            </td>
                            </tr>
                        <tr>
                            <td>We look forward to a long association with you.</td>
                        </tr>
                        <tr>
                            <td>Please sign the second copy of this letter as a token of your acceptance.</td>
                        </tr>

                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td><b>HR Manager<br>Aaryans World School</b></td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td><b>Thanking You,<br>Yours sincerely</b></td>
                        </tr>
                        @if($signBy)
                            <tr>
                                <td style="text-align: right;"><img src="{{ public_path('admin/signFiles/').$signBy->fileName    }}" style="width: 130px; height: 40px"></td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">
                                    {{$signBy->name}}
                                </td>
                            </tr>
                        @endif
                    </tbody>                
                </table>
            </div>
        </div>
    </body>
</html>