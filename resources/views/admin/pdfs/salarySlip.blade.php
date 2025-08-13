
<?php 
use App\Helpers\Utility;
$util = new Utility();
?>
        <table width="100%">
            <tr>
                <td width="10%"></td>
                <td>
                    <table border="20px" id="hr-payroll">
                        <thead style="font-size:10px;">
                            <tr>
                                <th colspan="5">
                                    @if($empDet->organisation == 3)
                                        <center><h4>Tejasha Educational and research foundation</h4></center>
                                        <center><h6>( Reg. No.: Mah./656/09)</h6></center>
                                        <center><h6>E2, Patang Plaza, Phase 5, Bharti Vidyapeeth, Opp. PICT College, Katraj, Pune - 411046</h6></center>
                                    @elseif($empDet->organisation == 2)
                                        <center><h4>Snayraa Educational Services Private Limited</h4></center>
                                        <center><h6>Corporate Identity No. ( Reg. of Company : U80904PN2012PTC143934)</h6></center>
                                        <center><h6>E2, Patang Plaza, Phase 5, Bharti Vidyapeeth, Opp. PICT College, Katraj, Pune - 411046</h6></center>
                                    @else
                                        <center><h4>Ellora Medicals and Educational foundation</h4></center>
                                        <center><h6>Reg. No.: F - 21036 / Pune</h6></center>
                                        <center><h6>E2, Patang Plaza, Phase 5, Bharti Vidyapeeth, Opp. PICT College, Katraj, Pune - 411046</h6></center>
                                    @endif
                                    <center><h5><b>Payslip for the month of {{date('M Y', strtotime($month))}}</b></h5></center>
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
                                                                <td style="border: 1px solid white;">: {{date('d-m-Y', strtotime($empDet->jobJoingDate))}}</td>
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
                                                                <td style="border: 1px solid white;">: {{date('t', strtotime($month))}}</td>
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
                                                                <td style="border: 1px solid white;">: {{$salDet->bankAccountNo}}</td>
                                                            </tr>
                                                            <tr style="border: 1px solid white;">
                                                                <td style="border: 1px solid white;">PF No</td>
                                                                <td style="border: 1px solid white;">: {{$salDet->pf}}</td>
                                                            </tr>
                                                            <tr style="border: 1px solid white;">
                                                                <td style="border: 1px solid white;">PF UAN</td>
                                                                <td style="border: 1px solid white;">: -</td>
                                                            </tr>
                                                            <tr style="border: 1px solid white;">
                                                                <td style="border: 1px solid white;">ESI No.</td>
                                                                <td style="border: 1px solid white;">: -</td>
                                                            </tr>
                                                            <tr style="border: 1px solid white;">
                                                                <td style="border: 1px solid white;">PAN No</td>
                                                                <td style="border: 1px solid white;">: {{$empDet->PANNo}}</td>
                                                            </tr>
                                                            <tr style="border: 1px solid white;">
                                                                <td style="border: 1px solid white;">Absent</td>
                                                                <td style="border: 1px solid white;">: {{$salDet->totAbsent}}</td>
                                                            </tr>
                                                        </table>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Earnings</th>
                                                    <th>Amount</th>
                                                    <th>Actual</th>
                                                    <th>Deductions</th>
                                                </tr>
                                                <tr style="border-bottom: 5px solid white;">
                                                    <th style="border-bottom: 5px solid white;">Basic</th>
                                                    <th style="border-bottom: 5px solid white;">{{$salDet->grossSalary}}</th>
                                                    <th style="border-bottom: 5px solid white;">Professional Tax</th>
                                                    <th style="border-bottom: 5px solid white;">0</th>
                                                </tr>
                                                <tr style="border-bottom: 5px solid white;">
                                                    <th style="border-bottom: 5px solid white;">Extra Working</th>
                                                    <th style="border-bottom: 5px solid white;">0</th>
                                                    <th style="border-bottom: 5px solid white;">Provident Fund</th>
                                                    <th style="border-bottom: 5px solid white;">0</th>
                                                </tr>
                                                <tr style="border-bottom: 5px solid white;">
                                                    <th style="border-bottom: 5px solid white;">Management Allownance</th>
                                                    <th style="border-bottom: 5px solid white;">0</th>
                                                    <th style="border-bottom: 5px solid white;">ESIC</th>
                                                    <th style="border-bottom: 5px solid white;">0</th>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th>Debit</th>
                                                    <th>0</th>
                                                </tr>
                                                <tr>
                                                    <th>Total Earnings:INR. </th>
                                                    <th>{{$salDet->grossSalary}}</th>
                                                    <th>Total Deductions:INR.</th>
                                                    <th>{{$salDet->otherDeduction+$salDet->advanceAgainstSalary}}</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="4">
                                                        Net Pay for the month {{$util->numberToWord($salDet->grossSalary)}})
                                                    </tr>
                                                </tr>
                        </thead>
                    </table>
                </td>
                <td width="10%"></td>
        </table>
    </center>
    <center>This is a system generated payslip and does not require signature.</center>
                                    