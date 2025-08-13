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
                font-size: 10px;
            }
            
            table {
                border-collapse: collapse;
                width: 100%;
                font-size: 10px;
            }
            
            th,
            td {
                border: 1px solid #ddd;
                padding: 8px;
                font-size: 10px;
            }
            
            th {
                color: #000;
                font-size: 10px;
            }
        </style>
    </head>
    <body>
            <?php
                use App\Helpers\Utility;
                $util=new Utility(); 
            ?>
        <div class="container">
            <div class="row">
                <table style="border: 0px solid white !important;">
                    <tr style="border: 0px solid white !important;">
                        <td style="border: 0px solid white !important;"><p style="margin-top:0px;text-align:left;font-size:10px;">PDF Generated At : {{date('d/m/Y h:i A')}}</p>   </td>
                        <td style="border: 0px solid white !important;"><p style="margin-top:0px;text-align:right;font-size:10px;"><b>Aaryans World</b></p></td>
                    </tr>
                </table>
                <div class="table-responsive mt-5">
                    <table id="pdfview" width="100%">
                        <thead>
                            <tr>
                                <th colspan="4">
                                    @if($empDet->organisation == 3)
                                        <center><b style="font-size:25px;">Tejasha Educational and research foundation</b></center>
                                        <center><b style="font-size:25px;">( Reg. No.: Mah./656/09)</b></center>
                                        <center><b style="font-size:25px;">E2, Patang Plaza, Phase 5, Bharti Vidyapeeth, Opp. PICT College, Katraj, Pune - 411046</b></center>
                                    @elseif($empDet->organisation == 2)
                                        <center><b style="font-size:25px;">Snayraa Educational Services Private Limited</b></center>
                                        <center><b style="font-size:25px;">Corporate Identity No. ( Reg. of Company : U80904PN2012PTC143934)</b></center>
                                        <center><b style="font-size:25px;">E2, Patang Plaza, Phase 5, Bharti Vidyapeeth, Opp. PICT College, Katraj, Pune - 411046</b></center>
                                    @else
                                        <center><b style="font-size:25px;">Ellora Medicals and Educational foundation</b></center>
                                        <center><b style="font-size:15px;">Reg. No.: F - 21036 / Pune</b></center>
                                        <center><b style="font-size:15px;">E2, Patang Plaza, Phase 5, Bharti Vidyapeeth, Opp. PICT College, Katraj, Pune - 411046</b></center>
                                    @endif
                                    <br>
                                    <center><b>Payslip for the month of {{date('M Y', strtotime($month))}}</b></center>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="2">
                                    <table style="border: 1px solid white;">
                                        <tr style="border: 1px solid white;">
                                            <td style="border: 1px solid white;">Employee Name</td>
                                            <td style="border: 1px solid white;">: {{$empDet->name}}</td>
                                        </tr>
                                        <tr style="border: 1px solid white;">
                                            <td style="border: 1px solid white;">Employee Code</td>
                                            <td style="border: 1px solid white;">: {{$empDet->empCode}}</td>
                                        </tr>
                                        <tr style="border: 1px solid white;">
                                            <td style="border: 1px solid white;">Join Date</td>
                                            <td style="border: 1px solid white;">: {{$salDet->joingDate}}</td>
                                        </tr>
                                        <tr style="border: 1px solid white;">
                                            <td style="border: 1px solid white;">Designation</td>
                                            <td style="border: 1px solid white;">: {{$empDet->desName}}</td>
                                        </tr>
                                        <tr style="border: 1px solid white;">
                                            <td style="border: 1px solid white;">Department</td>
                                            <td style="border: 1px solid white;">: {{$empDet->deptName}}</td>
                                        </tr>
                                        <tr style="border: 1px solid white;">
                                            <td style="border: 1px solid white;">Effective Work Days</td>
                                            <td style="border: 1px solid white;">: {{$salDet->presentWithWO}}</td>
                                        </tr>
                                        <tr style="border: 1px solid white;">
                                            <td style="border: 1px solid white;">Days In Month</td>
                                            <td style="border: 1px solid white;">: {{$salDet->totalDays}}</td>
                                        </tr>
                                    </table>
                                </th>
                                <th colspan="2">
                                    <table>
                                        <tr style="border: 1px solid white;">
                                            <td style="border: 1px solid white;">Bank Name</td>
                                            <td style="border: 1px solid white;">: {{$salDet->bankName}}</td>
                                        </tr>
                                        <tr style="border: 1px solid white;">
                                            <td style="border: 1px solid white;">Bank Account No</td>
                                            <td style="border: 1px solid white;">: {{$salDet->accountNo}}</td>
                                        </tr>
                                        <tr style="border: 1px solid white;">
                                            <td style="border: 1px solid white;">PF No</td>
                                            <td style="border: 1px solid white;">: {{$salDet->pf}}</td>
                                        </tr>
                                        <tr style="border: 1px solid white;">
                                            <td style="border: 1px solid white;">PF UAN</td>
                                            <td style="border: 1px solid white;">: {{$empDet->desName}}</td>
                                        </tr>
                                        <tr style="border: 1px solid white;">
                                            <td style="border: 1px solid white;">ESI No.</td>
                                            <td style="border: 1px solid white;">: {{$empDet->deptName}}</td>
                                        </tr>
                                        <tr style="border: 1px solid white;">
                                            <td style="border: 1px solid white;">PAN No</td>
                                            <td style="border: 1px solid white;">: {{$empDet->PANNo}}</td>
                                        </tr>
                                        <tr style="border: 1px solid white;">
                                            <td style="border: 1px solid white;">Absent</td>
                                            <td style="border: 1px solid white;">: {{$salDet->absent}}</td>
                                        </tr>
                                    </table>
                                </th>
                            </tr>
                            <tr>
                                <th width="40%">Earnings</th>
                                <th width="10%">Amount</th>
                                <th width="40%">Actual</th>
                                <th width="10%">Deductions</th>
                            </tr>
                            <tr style="border-bottom: 5px solid white;">
                                <th style="border-bottom: 5px solid white;">Basic</th>
                                <th style="border-bottom: 5px solid white;">{{$util->numberFormatRound($salDet->basicPayableSalary)}}</th>
                                <th style="border-bottom: 5px solid white;">Professional Tax</th>
                                <th style="border-bottom: 5px solid white;">{{$util->numberFormatRound($salDet->PT)}}</th>
                            </tr>
                            <tr style="border-bottom: 5px solid white;">
                                <th style="border-bottom: 5px solid white;">Extra Working</th>
                                <th style="border-bottom: 5px solid white;">{{$util->numberFormatRound($salDet->perDayRs * $salDet->extraWorkingHours)}}</th>
                                <th style="border-bottom: 5px solid white;">Provident Fund</th>
                                <th style="border-bottom: 5px solid white;">{{($salDet->PF)?$util->numberFormatRound($salDet->PF):0}}</th>
                            </tr>
                            <tr style="border-bottom: 5px solid white;">
                                <th style="border-bottom: 5px solid white;">Management Allownance</th>
                                <th style="border-bottom: 5px solid white;">0</th>
                                <th style="border-bottom: 5px solid white;">ESIC</th>
                                <th style="border-bottom: 5px solid white;">{{$util->numberFormatRound($salDet->ESIC)}}</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Debit</th>
                                <th>0</th>
                            </tr>
                            <tr>
                                <th>Total Earnings:INR. </th>
                                <th>{{$util->numberFormatRound($totalEarning = $salDet->basicPayableSalary+($salDet->perDayRs * $salDet->extraWorkingHours)+0)}}</th>
                                <th>Total Deductions:INR.</th>
                                <th>{{$util->numberFormatRound($totalDeduction = $salDet->PT+$salDet->PF+$salDet->ESIC)}}</th>
                            </tr>
                            <tr>
                                <th colspan="4">
                                    Net Pay for the month ( Total Earnings - Total Deductions): {{$util->numberFormatRound($totalEarning-$totalDeduction)}}<br>
                                    (Rupees {{$util->numberToWord($totalEarning-$totalDeduction)}} Only)
                                </tr>
                            </tr>
                        </thead>
                    </table>
                    <center>This is a system generated payslip and does not require signature.</center>
                </div>
            </div>
        </div>
    </body>
</html>



