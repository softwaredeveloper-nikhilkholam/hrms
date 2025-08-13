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
            padding: 5px;
            font-size:12px;
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
                    <td width="20%"><h3><center>Code<br>{{$employee->empCode}}</center></h3></td>
                    <td width="80%" style="text-align: center;background-color:#C5C5C5;color:black;"><b style="font-size:18px;">EMPLOYEE  INFORMATION FORM</b></td>
                </tr>
            </table>

            <table style="border:1px;">
                <tr>
                    <td width="80%" style="margin-top:0px;">
                        <table style="border:1px;">
                            <tr>
                                <td width="30%"><b>Name</b></td>
                                <td>{{$employee->name}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Department</b></td>
                                <td>{{$employee->departmentName}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Designation</b></td>
                                <td>{{$employee->designationName}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Branch</b></td>
                                <td>{{$employee->branchName}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Job Joining Date</b></td>
                                <td>{{($employee->jobJoingDate == '0' || $employee->jobJoingDate == '' || $employee->jobJoingDate == '1970-01-01')?'-':date('d-m-Y', strtotime($employee->jobJoingDate))." (".$util->calculateExperience($employee->jobJoingDate)." Experience in Aaryans)"}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Date of Birth</b></td>
                                <td>{{($employee->DOB == '' || $employee->DOB == '1970-01-01')?'-':date('d-m-Y', strtotime($employee->DOB))." (Age - ".$util->calculateExperience($employee->DOB).")"}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Present Address</b></td>
                                <td>{{$employee->presentAddress}}, {{$presentRegion}}, {{$presentCity}} - {{$employee->presentPINCode}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Permanent Address</b></td>
                                <td>{{$employee->permanentAddress}}, {{$permanentRegion}}, {{$permanentCity}} - {{$employee->permanentPINCode}}</td>
                            </tr>
                        </table>
                    </td>
                    <td width="20%">
                        <b><center><img src="{{public_path('admin/images/employees/'.$employee->profilePhoto)}}" style="border: 3px solid #555;" width="120px" height="150px">
                        <br>{{($employee->active == 1)?"Active":"Dective"}}</center></b>
                    </td>
                </tr>
            </table>

            <table style="border:1px;">
                <tr>
                    <td width="50%" style="margin-top:0px;">
                        <table style="border:1px;">
                            <tr>
                                <td width="30%"><b>Phone No</b></td>
                                <td>+91 {{$employee->phoneNo}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Email Id</b></td>
                                <td>{{$employee->email}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Gender</b></td>
                                <td>{{$employee->gender}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>AADHAR No</b></td>
                                <td>{{$employee->AADHARNo}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>PAN No</b></td>
                                <td>{{$employee->PANNo}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Marital Status</b></td>
                                <td>{{$employee->maritalStatus}}</td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%">
                        <table style="border:1px;">
                            <tr>
                                <td width="30%"><b>Whatsapp No</b></td>
                                <td>{{$employee->whatsappNo}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Cast & Type</b></td>
                                <td>{{$employee->cast}} ({{$employee->type}})</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Fees Concession</b></td>
                                <td>{{($employee->feesConcession == 1)?'Yes':'No'}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Contract</b></td>
                                <td>{{($employee->contractStartDate == '0' || $employee->contractStartDate == '' || $employee->contractStartDate == '1970-01-01')?'-':date('d-m-Y', strtotime($employee->contractStartDate))}} To 
                                {{($employee->contractEndDate == '0' || $employee->contractEndDate == '' || $employee->contractEndDate == '1970-01-01')?'-':date('d-m-Y', strtotime($employee->contractEndDate))}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Qualification</b></td>
                                <td>{{$employee->qualification}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><b>Office Time</b></td>
                                <td>{{$employee->startTime}} To {{$employee->endTime}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <br>

            @if($userType == '61' || $userType == '401' || $userType == '201' || $userType == '501')
                <b>Accounts Details</b>
                <table style="border:1px;">
                    <tr>
                        <td width="20%"><b>Salary</b></td>
                        <td width="30%">Rs. {{($employee->salaryScale == '' || $employee->salaryScale == 0)?'0':$util->numberFormatRound($employee->salaryScale)}}</td>
                        <td width="20%"><b>Retention Amount</b></td>
                        <td width="30%">{{($employee->retentionAmount == '' || $employee->retentionAmount == null)?'0':$employee->retentionAmount}}</td>
                    </tr>
                    <tr>
                        <td width="20%"><b>Provident Fund</b></td>
                        <td width="30%">{{($employee->PF == '' || $employee->PF == null)?'No':$employee->PF}}</td>
                        <td width="20%"><b>TDS</b></td>
                        <td width="30%">{{($employee->TDS == '' || $employee->TDS == null)?'No':$employee->TDS}}</td>
                    </tr>
                    <tr>
                        <td width="20%"><b>Professional Tax</b></td>
                        <td width="30%">{{($employee->PT == '' || $employee->PT == null)?'No':$employee->PT}}</td>
                        <td width="20%"><b>ESIC</b></td>
                        <td width="30%">{{($employee->ESIC == '' || $employee->ESIC == null)?'No':$employee->ESIC}}</td>
                    </tr>
                    <tr>
                        <td width="20%"><b>MLWF</b></td>
                        <td width="30%">{{($employee->MLWF == '' || $employee->MLWF == null)?'No':$employee->MLWF}}</td>
                        <td width="20%"><b><br></b></td>
                        <td width="30%"><br></td>
                    </tr>
                </table>
                <br>
            @endif

            <b>Bank Details</b>
            <table style="border:1px;">
                <tr>
                    <td width="20%"><b>Bank Name</b></td>
                    <td width="30%">{{$employee->bankName}}</td>
                    <td width="20%"><b>Account Number</b></td>
                    <td width="30%">{{$employee->bankAccountNo}}</td>
                </tr>
                <tr>
                    <td width="20%"><b>Branch Name</b></td>
                    <td width="30%">{{$employee->branchName}}</td>
                    <td width="20%"><b>IFSC Code</b></td>
                    <td width="30%">{{$employee->bankIFSCCode}}</td>
                </tr>
            </table>
            <br>
            <b>Social Media Details</b>
            <table style="border:1px;">
                <tr>
                    <td width="30%"><b>Instagram id</b></td>
                    <td><b>Twitter id</b></td>
                    <td width="30%"><b>Facebook id</b></td>
                </tr>
                <tr>
                    <td width="30%">{{($employee->instagramId == '')?'-':$employee->instagramId}}</td>
                    <td>{{($employee->twitterId == '')?'-':$employee->twitterId}}</td>
                    <td width="30%">{{($employee->facebookId == '')?'-':$employee->facebookId}}</td>
                </tr>
            </table>

            <br>
            <b>Uploaded Documents</b>
            <table style="border:1px;">
                <tr>
                    <td width="50%" style="margin-top:0px;">
                        <table style="border:1px;">
                            <tr>
                                <th>Document Name</td>
                                <th width="20%">Status</td>
                            </tr>
                            <tr>
                                <td>Aadhaar Card</td>
                                <td width="20%">{{($docs1 != 0)?'Yes':'No'}}</td>
                            </tr>
                            <tr>
                                <td>PAN card</td>
                                <td width="20%">{{($docs2 != 0)?'Yes':'No'}}</td>
                            </tr>
                            <tr>
                                <td>Testimonials / Marksheet</td>
                                <td width="20%">{{($docs3 != 0)?'Yes':'No'}}</td>
                            </tr>
                            <tr>
                                <td>Driving License</td>
                                <td width="20%">{{($docs4 != 0)?'Yes':'No'}}</td>
                            </tr>
                            <tr>
                                <td>RTO Batch</td>
                                <td width="20%">{{($docs5 != 0)?'Yes':'No'}}</td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%">
                    <table style="border:1px;">
                            <tr>
                                <th>Document Name</td>
                                <th width="20%">Status</td>
                            </tr>
                            <tr>
                                <td>Electricity Bill</td>
                                <td width="20%">{{($docs6 != 0)?'Yes':'No'}}</td>
                            </tr>
                            <tr>
                                <td>Bank Details</td>
                                <td width="20%">{{($docs7 != 0)?'Yes':'No'}}</td>
                            </tr>
                            <tr>
                                <td>Employee Contract</td>
                                <td width="20%">{{($docs8 != 0)?'Yes':'No'}}</td>
                            </tr>
                            <tr>
                                <td>Other Documents</td>
                                <td width="20%">{{($docs9 != 0)?'Yes':'No'}}</td>
                            </tr>
                            <tr>
                                <td><br></td>
                                <td width="20%"><br></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <hr>
            <table style="border:0px;">
                <tr style="border:0px;">
                    <td style="border:0px;">
                         <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike" checked>
                        <label for="vehicle1"><b>I hereby declare that all the information given above is true and correct to the best of my knowledge.</b></label>
                    </td>
                </tr>
            </table>
            <br>

            <table style="border:0px;">
                <tr style="border:0px;">
                    <td width="20%" style="margin-top:0px;border:0px;"><b>Date: {{date('d-m-Y')}}</b></td>
                    <td width="60%" style="margin-top:0px;border:0px;"></td>
                    <td width="20%" style="margin-top:0px;border:0px;"><b>Employee Sign</b><br></td>
                </tr>
            </table>
            </div>
        </div>
    </body>
</html>