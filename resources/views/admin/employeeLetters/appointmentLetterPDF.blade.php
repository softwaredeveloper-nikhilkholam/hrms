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
            @if(isset($flag))
                @if($flag == 1)
                    <table width="100%" id="pdfview">
                        <tbody>
                            <tr>
                                <td><img src="{{ public_path('admin/letterHeads/Ellora Medical (ASCON).jpg') }}" style="width: 100%; height: 250px"></td>
                            </tr>
                        </tbody>
                    </table>
                @else
                <div style="text-align: right;margin-top:265px;">
                    <b style="font-size:20px;">
                        Date: ___________
                    </b> <br><br>
                </div>
                <div class="row" style=""></div> 
                @endif
            @else
                <table width="100%" id="pdfview">
                    <tbody>
                        <tr>
                            <td><img src="{{ public_path('admin/letterHeads/ellor.jpg') }}" style="width: 100%; height: 250px"></td>
                        </tr>
                    </tbody>
                </table>
            @endif
            <div style="text-align: center">
                <b style="font-size:20px;">
                    Appointment Letter
                </b> <br><br>
            </div>
            <table width="100%" id="pdfview">
                <tbody>
                    <tr>
                        <td>
                            <div class="row">
                                <div class="col-md-12" style="margin-top:40px;margin-left:20px;">
                                    To, 
                                    <br>
                                    <br>
                                    <b>{{($empDet->gender == 'Male')?'Mr.':'Miss/Mrs.'}} {{$empDet->name}}</b> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5" style="margin-left:14px;">
                                    <table width="100%">
                                        <tbody>
                                            <tr>
                                                <td width="50%">{{$empDet->permanentAddress}}</td>
                                                <td width="50%"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-7"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="margin-top:50px;margin-left:20px;">
                                    Dear Sir/Madam,
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="margin-top:10px;margin-left:20px;">
                                    With reference to your application & subsequent interviews you had with us, we are pleased to appoint you as <b>{{$empDet->designationName}}</b> in {{(isset($empDet->organisation))?$empDet->organisation:'________'}}, Aaryans World School <b>{{$empDet->branchName}}</b>, with effect from {{$util->dateFormat(date('d-m-Y', strtotime($fromDate)),1)}}
                                </div>
                            </div>
                    
                            <div class="row">
                                <div class="col-md-12" style="margin-top:40px;margin-left:15px;">
                                    <ol type="i">
                                        <li>You will be paid consolidated salary of Rs.{{$util->numberFormatRound($salary)}} per month ({{$util->numberToWord($salary)}} Only)</li>
                                    </ol>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="margin-top:40px;margin-left:20px;">
                                    Your appointment is subject to the following conditions that:
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-12" style="margin-left:20px;">
                    <ol type="a">
                        <li>The appointment, salary etc will be solely at the discretion of the Management of Aaryans World School.</li>
                        <li style="margin-top:20px;">You should submit the original & self-attested copies of passing certificates, mark sheets, experience certificates, discharge certificate/relieving certificate, L.P. certificate & proof of your age before joining.</li>
                        <li style="margin-top:20px;">You should communicate your acceptance immediately when you receive your appointment order, failing which your appointment is liable for cancellation.</li>
                        @if($aPeriod == 'Purely Temporary')<li style="margin-top:20px;">Your appointment is purely temporary from {{($aPeriod == 'Purely Temporary')?date('d-m-Y', strtotime($fromDate)):' NA '}} to {{($aPeriod == 'Purely Temporary')?date('d-m-Y', strtotime($toDate)):' NA '}}</li>@endif
                        @if($aPeriod == 'Probation Period')<li style="margin-top:20px;">Your appointment is on probation period from {{($aPeriod == 'Probation Period')?date('d-m-Y', strtotime($fromDate)):' NA '}} to {{($aPeriod == 'Probation Period')?date('d-m-Y', strtotime($toDate)):' NA '}}</li>@endif
                        @if($aPeriod == 'Academic Year')<li style="margin-top:20px;">Your appointment is for the academic year {{($aPeriod == 'Academic Year')?date('Y', strtotime($fromDate)):' NA '}} to {{($aPeriod == 'Academic Year')?date('Y', strtotime($toDate)):' NA '}}</li>@endif
                        @if($aPeriod == 'Leave Vacancy')<li style="margin-top:20px;">Your appointment is in the leave vacancy for the period from{{($aPeriod == 'Leave Vacancy')?date('d-m-Y', strtotime($fromDate)):' NA '}} to {{($aPeriod == 'Leave Vacancy')?date('d-m-Y', strtotime($toDate)):' NA '}} </li>@endif
                        <li style="margin-top:20px;">Your services will be governed by the rules & regulations of "Aaryans World School"</li>
                        <li style="margin-top:20px;">Your services will be liable for termination as per the the rules mentioned in the policy document.</li>
                        <li style="margin-top:20px;">Your services are transferrable to any branch of "Aaryans World School"</li>
                        <li style="margin-top:20px;">You will have to sign 'Policy Document' to the effect that you will serve as per the service conditions laid down in the document</li>
                    </ol>
                </div>
            </div>
            <table>
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-md-12" style="margin-top:40px;margin-left:20px;">
                                Thanking You,
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="margin-top:40px;margin-left:20px;">
                                Yours sincerely
                            </div> 
                        </div>
                        
                        <?php $signDetials = $util->getSignatureDetail(($empDet->signId)?$empDet->signId:11);  ?>
                        @if($signDetials)
                            <div class="row">
                                <div class="col-md-12" style="margin-top:40px;margin-left:20px;">
                                    <img src="{{ public_path('admin/signFiles/').$signDetials->fileName}}" style="width: 200px; height: 130px"> 
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="margin-left:20px;">
                                    <b>{{$signDetials->name}}</b>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="margin-left:20px;">
                                    <b>{{$signDetials->designationName}}</b>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="margin-left:20px;">
                                    <b>Aaryans World School</b>
                                </div> 
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-12" style="margin-top:55px;margin-left:20px;">
                                Copy to: Accounts Dept<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Personal File
                            </div> 
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>