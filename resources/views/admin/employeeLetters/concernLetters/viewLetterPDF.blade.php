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
            padding-left: 12px;
            padding-top: 6px;
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
                <table width="100%" id="pdfview" style="padding-left: 0px !important;">
                    <tbody style="padding-left: 0px !important;">
                        <tr style="padding-left: 0px !important;">
                            <td style="padding-left: 0px !important;"><img src="{{ public_path('admin/letterHeads/Ellora Medical (ASCON).jpg') }}" style="width: 700px; height: 220px"></td>
                        </tr>
                    </tbody>
                </table>
                <div style="text-align: center">
                     <br><br>
                    <b style="font-size:20px;">
                        Notification Letter
                     <br><br>
                </div>
                <table width="100%" id="pdfview">
                    <tbody>
                        <tr>
                            <td>To, </td>
                        </tr>
                        <tr>
                            <td>Mr/Ms. <b>{{$letter->empName}}</b></td>
                        </tr>
                        <tr>
                            <td>Employee Code: <b>{{$letter->empCode}}</b></td>
                        </tr>
                        <tr>
                            <td>Designation: <b>{{$letter->designationName}}</b></td>
                        </tr>
                        <tr>
                            <td><br><br></td>
                        </tr>
                        <tr>
                            <td style="text-align:center;"><b>Subject: Attendance Deviation Letter –  {{date('M Y', strtotime($letter->letterForMonth))}}</b></td>
                        </tr>
                        <tr>
                            <td>Dear Sir /Ma’am,</td>
                        </tr>
                        <tr>
                            <td>Greetings from Aaryans World School.</td>
                        </tr>
                        <tr>
                            <td>
                                We’d like to bring to your attention a slight deviation in your biometric attendance record for June 2025, where {{$letter->lateMarkCount}} instances of late arrival were noted (equivalent to {{floor($letter->lateMarkCount / 3)}} full day as per policy).
                            </td>
                        </tr>
                        <tr>
                            <td>While this does fall under the scope of salary deduction, the management has decided to waive the deduction this time as a goodwill gesture. We truly hope this gives you an opportunity to realign with our punctuality expectations moving forward.</td>
                        </tr>
                        <tr>
                            <td>We value your presence and contribution and request you to be more mindful of reporting time henceforth. Repeated occurrences may lead to deductions or further action as per policy.</td>
                        </tr>
                        <tr>
                            <td>Let’s continue working together towards a disciplined and professional work culture.</td>
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
                            <td><b>Thanking You,</b></td>
                        </tr>
                        @if($signBy)
                            <tr>
                                <td style="padding-bottom:2px !important;"><img src="{{ public_path('admin/signFiles/').$signBy->fileName }}" style="width: 130px; height: 50px"></td>
                            </tr>
                            <tr>
                                <td style="padding-top:2px !important;">
                                    {{$signBy->empName}}
                                </td>
                            </tr> 
                            <tr>
                                <td style="padding-top:2px !important;">
                                    {{$signBy->designationName}}
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    Aaryans World School
                                </td>
                            </tr>
                        @endif
                    </tbody>                
                </table>
            </div>
        </div>
    </body>
</html>