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
        #tableBD,
        #tdBD,
        #thBD {
            border: 0.5px solid #ddd;
            text-align: left;
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
            <table width="100%">
                <tr>
                    <td width="5%"></td>
                    <td style="border: 2px solid #ddd;text-align: left;">
                        <table style="border: 0px solid white !important;">
                            <tr style="border: 0px solid white !important;">
                                <td style="border: 0px solid white !important;"><p style="margin-top:0px;text-align:left;font-size:10px;">PDF Generated At : {{date('d/m/Y h:i A')}}</p>   </td>
                                <th style="border: 0px solid white !important;" align="right"><p style="margin-top:0px;text-align:right;font-size:10px;"><b>AWS</b></p></th>
                            </tr>
                        </table>
                        <div style="text-align: center">
                            <b style="font-size:20px;">
                                Interview Drive Summary
                            </b> <br><br>
                            <b style="font-size:15px;">
                                {{date('d-m-Y', strtotime($fromDate))}} To {{date('d-m-Y', strtotime($toDate))}}
                            </b> 
                        </div>
                        <hr>
                        <table width="100%" style="border: 0.5px solid white !important;">
                            <tr style="border: 0.5px solid black !important;padding: 15px;">
                                <th style="border: 0.5px solid black !important;padding: 15px;" align="center"><b>Total: {{$total}}</b></th>
                                <th style="border: 0.5px solid black !important;padding: 15px;" align="center"><b>Selected: {{$selected}} ( {{($selected == '' || $selected == 0 || $selected == null)?'0':$util->numberFormat(($selected/$total)*100)}} %)</b></th>
                                <th style="border: 0.5px solid black !important;padding: 15px;" align="center"><b>CBC: {{$cBC}} ({{$util->numberFormat(($cBC/$total)*100)}}%)</b></th>
                                <th style="border: 0.5px solid black !important;padding: 15px;" align="center"><b>Rejected: {{$rejected}} ({{$util->numberFormat(($rejected/$total)*100)}}%)</b></th>
                                <th style="border: 0.5px solid black !important;padding: 15px;" align="center"><b>Pending: {{$total-($selected+$cBC+$rejected)}} ({{$util->numberFormat((($total-($selected+$cBC+$rejected))/$total)*100)}}%)</b></th>
                            </tr>
                        </table>
                        <hr>
                        <table width="100%" style="border: 0.5px solid white !important;">
                            <tr style="border: 0.5px solid black !important;padding: 10px;">
                                <th colspan="7" style="background-color:#80808026; border: 0.5px solid black !important;padding: 10px;" align="center"><b>Job Vacancy Summary</b></th>
                            </tr>
                            <tr style="background-color:#80808026;border: 0.5px solid black !important;padding: 10px;">
                                <th style="border: 0.5px solid black !important;padding: 10px;" align="center"><b>No.</b></th>
                                <th style="border: 0.5px solid black !important;padding: 10px;" align="center"><b>Designation</b></th>
                                <th style="border: 0.5px solid black !important;padding: 10px;" align="center"><b>Selected</b></th>
                                <th style="border: 0.5px solid black !important;padding: 10px;" align="center"><b>CBC</b></th>
                                <th style="border: 0.5px solid black !important;padding: 10px;" align="center"><b>Rejected</b></th>
                                <th style="border: 0.5px solid black !important;padding: 10px;" align="center"><b>Pending</b></th>
                                <th style="border: 0.5px solid black !important;padding: 10px;" align="center"><b>Total</b></th>
                            </tr>
                            @if(isset($jobs))
                                <?php $totalJobs=$totalSelected=$totalCBC=$totalRejected=$totalPending=$totalCount=0;
                                    $i=1; ?>
                                @foreach($jobs as $job)
                                    <tr style="border: 0.5px solid black !important;padding: 5px;">
                                        <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b>{{$i++}}</b></td>
                                        <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b>{{$job->jobPosition}}</b></td>
                                        <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b>{{$job->selected}}</b></td>
                                        <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b>{{$job->cBC}}</b></td>
                                        <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b>{{$job->rejected}}</b></td>
                                        <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b>{{$job->pending}}</b></td>
                                        <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b>{{$job->count}}</b></td>
                                        <?php
                                            $totalSelected = $totalSelected+$job->selected;
                                            $totalCBC = $totalCBC+$job->cBC;
                                            $totalRejected = $totalRejected+$job->rejected;
                                            $totalPending = $totalPending+$job->pending;
                                            $totalCount = $totalCount+$job->count;
                                        ?>
                                    </tr>
                                @endforeach
                                <tr style="background-color:#80808026; border: 0.5px solid black !important;padding: 5px;">
                                    <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b></b></td>
                                    <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b>Total</b></td>
                                    <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b>{{$totalSelected}}</b></td>
                                    <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b>{{$totalCBC}}</b></td>
                                    <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b>{{$totalRejected}}</b></td>
                                    <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b>{{$totalPending}}</b></td>
                                    <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b>{{$totalCount}}</b></td>
                                </tr>
                            @endif
                        </table>
                        <br>
                        <hr>

                        <table width="100%" style="border: 0.5px solid white !important;">
                            <tr style="border: 0.5px solid black !important;padding: 10px;">
                                <th colspan="4" style="background-color:#80808026; border: 0.5px solid black !important;padding: 10px;" align="center"><b>Candidate Reference Summary</b></th>
                            </tr>
                            <tr style="background-color:#80808026;border: 0.5px solid black !important;padding: 10px;">
                                <th style="border: 0.5px solid black !important;padding: 10px;" align="center" width="5%"><b>No.</b></th>
                                <th style="border: 0.5px solid black !important;padding: 10px;" align="center"><b>Reference</b></th>
                                <th style="border: 0.5px solid black !important;padding: 10px;" align="center" width="10%"><b>Count</b></th>
                                <th style="border: 0.5px solid black !important;padding: 10px;" align="center" width="10%"><b>Percentage(%)</b></th>
                            </tr>
                            @if(isset($references))
                                <?php $i=1; ?>
                                @foreach($references as $reference)
                                    <tr style="border: 0.5px solid black !important;padding: 5px;">
                                        <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b>{{$i++}}</b></td>
                                        <td style="border: 0.5px solid black !important;padding: 5px;"><b>{{($reference->advSource == '')?'Not Updated':$reference->advSource}}</b></td>
                                        <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b>{{$reference->count}}</b></td>
                                        <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b>{{$util->numberFormat(($reference->count/$totalReferences)*100)}}%</b></td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                        <table width="100%" style="border: 0.5px solid white !important;">
                            <tr style="border: 0.5px solid black !important;padding: 10px;">
                                <th colspan="4" style="background-color:#80808026; border: 0.5px solid black !important;padding: 10px;" align="center"><b>Interviewer Summary</b></th>
                            </tr>
                            <tr style="background-color:#80808026;border: 0.5px solid black !important;padding: 10px;">
                                <th style="border: 0.5px solid black !important;padding: 10px;" align="center" width="5%"><b>No.</b></th>
                                <th style="border: 0.5px solid black !important;padding: 10px;" align="center"><b>Interviewer</b></th>
                                <th style="border: 0.5px solid black !important;padding: 10px;" align="center" width="10%"><b>Count</b></th>
                                <th style="border: 0.5px solid black !important;padding: 10px;" align="center" width="10%"><b>Percentage(%)</b></th>
                            </tr>
                            @if(isset($takenBys))
                                <?php $i=1; ?>
                                @foreach($takenBys as $taken)
                                    <tr style="border: 0.5px solid black !important;padding: 5px;">
                                        <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b>{{$i++}}</b></td>
                                        <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b>{{$taken->name}}</b></td>
                                        <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b>{{$taken->count}}</b></td>
                                        <td style="border: 0.5px solid black !important;padding: 5px;" align="center"><b>{{$util->numberFormat(($taken->count/$totalTakenBys)*100)}}%</b></td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                        <br>
                        <hr>

                        <table width="100%" style="border: 0px solid white !important;">
                            <tr style="border: 0.5px solid black !important;padding: 10px;">
                                <th colspan="12" style="background-color:#80808026; border: 0.5px solid black !important;padding: 10px;" align="center"><b>1st Round Interview</b></th>
                            </tr>
                            @if(isset($interviews1))
                                @if(count($interviews1) >= 1)
                                    <tr style="background-color:#80808026;border: 0px solid black !important;padding: 10px;">
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="3%"><b>No.</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="21%"><b>Name</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Exp. Salary</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Eligibility</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Smartness</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Knowledge</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Appearance</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>English</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Confidence</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="15%"><b>Remark</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="5%"><b>Status</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="5%"><b>Taken By</b></th>
                                    </tr>
                                    <?php $i=1; ?>
                                    @foreach($interviews1 as $interview)
                                        <tr style="border: 0.5px solid black !important;padding: 5px;">
                                            <td style="border: 0.5px solid black !important;padding: 5px;" align="center">{{$i++}}</td>
                                            <td style="border: 0.5px solid black !important;padding: 5px; font-size:12px;">
                                                <b>
                                                    {{$interview->id}} - {{$interview->firstName}} {{$interview->middleName}} {{$interview->lastName}}<br>
                                                    {{$interview->mobileNo}}<br>
                                                    {{$interview->email}}<br>
                                                    {{$interview->designationName}}
                                                </b>
                                            </td>
                                            <td style="border: 0.5px solid black !important; font-size:12px;" align="center">{{$interview->expectedSalary}}</td>
                                            <td style="border: 0.5px solid black !important; font-size:30px;" align="center"><b>{{($interview->rating1 == 1)?'*':(($interview->rating1 == 2)?'**':(($interview->rating1 == 3)?'***':(($interview->rating1 == 4)?'****':(($interview->rating1 == 0)?'-':'*****'))))}}</b></td>
                                            <td style="border: 0.5px solid black !important; font-size:30px;" align="center"><b>{{($interview->rating2 == 1)?'*':(($interview->rating2 == 2)?'**':(($interview->rating2 == 3)?'***':(($interview->rating2 == 4)?'****':(($interview->rating2== 0)?'-':'*****'))))}}</b></td>
                                            <td style="border: 0.5px solid black !important; font-size:30px;" align="center"><b>{{($interview->rating3 == 1)?'*':(($interview->rating3 == 2)?'**':(($interview->rating3 == 3)?'***':(($interview->rating3 == 4)?'****':(($interview->rating3 == 0)?'-':'*****'))))}}</b></td>
                                            <td style="border: 0.5px solid black !important; font-size:30px;" align="center"><b>{{($interview->rating4 == 1)?'*':(($interview->rating4 == 2)?'**':(($interview->rating4 == 3)?'***':(($interview->rating4 == 4)?'****':(($interview->rating4 == 0)?'-':'*****'))))}}</b></td>
                                            <td style="border: 0.5px solid black !important; font-size:30px;" align="center"><b>{{($interview->rating5 == 1)?'*':(($interview->rating5 == 2)?'**':(($interview->rating5 == 3)?'***':(($interview->rating5 == 4)?'****':(($interview->rating5 == 0)?'-':'*****'))))}}</b></td>
                                            <td style="border: 0.5px solid black !important; font-size:30px;" align="center"><b>{{($interview->rating6 == 1)?'*':(($interview->rating6 == 2)?'**':(($interview->rating6 == 3)?'***':(($interview->rating6 == 4)?'****':(($interview->rating6 == 0)?'-':'*****'))))}}</b></td>
                                            <td style="border: 0.5px solid black !important; font-size:12px;" align="center">{{$interview->remarks}}</td>
                                            <td style="border: 0.5px solid black !important; font-size:12px;" align="center">{{$interview->appStatus}}</td>
                                            <td style="border: 0.5px solid black !important; font-size:12px;" align="center">{{$interview->updatedBy}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr style="border: 0.5px solid black !important;padding: 10px;">
                                        <th colspan="12" style="background-color:#80808026; border: 0.5px solid black !important;padding: 10px;" align="center"><b>1st Round Pending</b></th>
                                    </tr>                                 
                                @endif
                            @endif
                        </table>
                        <br>
                        <hr>

                        <table width="100%" style="border: 0px solid white !important;">
                            <tr style="border: 0.5px solid black !important;padding: 10px;">
                                <th colspan="12" style="background-color:#80808026; border: 0.5px solid black !important;padding: 10px;" align="center"><b>2nd Round Interview</b></th>
                            </tr>
                            @if(isset($interviews2))
                                @if(count($interviews2) >= 1)
                                    <tr style="background-color:#80808026;border: 0px solid black !important;padding: 10px;">
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="3%"><b>No.</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="21%"><b>Name</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Offered Salary</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Eligibility</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Smartness</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Appearance</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>English Fluency</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Confidence</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Content Knowledge</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="15%"><b>Remark</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="5%"><b>Status</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="5%"><b>Taken By</b></th>
                                    </tr>
                                   <?php $i=1; ?>
                                    @foreach($interviews2 as $interview)
                                        <tr style="border: 0.5px solid black !important;padding: 5px;">
                                            <td style="border: 0.5px solid black !important;padding: 5px;" align="center">{{$i++}}</td>
                                            <td style="border: 0.5px solid black !important;padding: 5px; font-size:12px;">
                                                <b>
                                                    {{$interview->id}} - {{$interview->firstName}} {{$interview->middleName}} {{$interview->lastName}}<br>
                                                    {{$interview->mobileNo}}<br>
                                                    {{$interview->designationName}}
                                                </b>
                                            </td>
                                            <td style="border: 0.5px solid black !important; font-size:12px;" align="center">{{$interview->expectedSalary}}</td>
                                            <td style="border: 0.5px solid black !important; font-size:30px;" align="center"><b>{{($interview->rating1 == 1)?'*':(($interview->rating1 == 2)?'**':(($interview->rating1 == 3)?'***':(($interview->rating1 == 4)?'****':(($interview->rating1 == 0)?'-':'*****'))))}}</b></td>
                                            <td style="border: 0.5px solid black !important; font-size:30px;" align="center"><b>{{($interview->rating2 == 1)?'*':(($interview->rating2 == 2)?'**':(($interview->rating2 == 3)?'***':(($interview->rating2 == 4)?'****':(($interview->rating2== 0)?'-':'*****'))))}}</b></td>
                                            <td style="border: 0.5px solid black !important; font-size:30px;" align="center"><b>{{($interview->rating3 == 1)?'*':(($interview->rating3 == 2)?'**':(($interview->rating3 == 3)?'***':(($interview->rating3 == 4)?'****':(($interview->rating3 == 0)?'-':'*****'))))}}</b></td>
                                            <td style="border: 0.5px solid black !important; font-size:30px;" align="center"><b>{{($interview->rating4 == 1)?'*':(($interview->rating4 == 2)?'**':(($interview->rating4 == 3)?'***':(($interview->rating4 == 4)?'****':(($interview->rating4 == 0)?'-':'*****'))))}}</b></td>
                                            <td style="border: 0.5px solid black !important; font-size:30px;" align="center"><b>{{($interview->rating5 == 1)?'*':(($interview->rating5 == 2)?'**':(($interview->rating5 == 3)?'***':(($interview->rating5 == 4)?'****':(($interview->rating5 == 0)?'-':'*****'))))}}</b></td>
                                            <td style="border: 0.5px solid black !important; font-size:30px;" align="center"><b>{{($interview->rating6 == 1)?'*':(($interview->rating6 == 2)?'**':(($interview->rating6 == 3)?'***':(($interview->rating6 == 4)?'****':(($interview->rating6 == 0)?'-':'*****'))))}}</b></td>
                                            <td style="border: 0.5px solid black !important; font-size:12px;" align="center">{{$interview->remarks}}</td>
                                            <td style="border: 0.5px solid black !important; font-size:12px;" align="center">{{$interview->appStatus}}</td>
                                            <td style="border: 0.5px solid black !important; font-size:12px;" align="center">{{$interview->updatedBy}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr style="border: 0.5px solid black !important;padding: 10px;">
                                        <th colspan="12" style="background-color:#80808026; border: 0.5px solid black !important;padding: 10px;" align="center"><b>2nd Round Pending</b></th>
                                    </tr>                                 
                                @endif
                            @endif
                        </table>
                        <br>
                        <hr>

                        <table width="100%" style="border: 0px solid white !important;">
                            <tr style="border: 0.5px solid black !important;padding: 10px;">
                                <th colspan="12" style="background-color:#80808026; border: 0.5px solid black !important;padding: 10px;" align="center"><b>3rd Round Interview</b></th>
                            </tr>
                            @if(isset($interviews3))
                                @if(count($interviews3) >= 1)
                                    <tr style="background-color:#80808026;border: 0px solid black !important;padding: 10px;">
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="3%"><b>No.</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="21%"><b>Name</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Content Knowledge</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Confidence</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Expression</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Overall Impact</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Appearance</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Video Link</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="15%"><b>Remark</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="5%"><b>Status</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="5%"><b>Taken By</b></th>
                                    </tr>
                                   <?php $i=1; ?>
                                    @foreach($interviews3 as $interview)
                                        <tr style="border: 0.5px solid black !important;padding: 5px;">
                                            <td style="border: 0.5px solid black !important;padding: 5px;" align="center">{{$i++}}</td>
                                            <td style="border: 0.5px solid black !important;padding: 5px; font-size:12px;">
                                                <b>
                                                    {{$interview->id}} - {{$interview->firstName}} {{$interview->middleName}} {{$interview->lastName}}<br>
                                                    {{$interview->mobileNo}}<br>
                                                    {{$interview->email}}<br>
                                                    {{$interview->designationName}}
                                                </b>
                                            </td>
                                            <td style="border: 0.5px solid black !important; font-size:30px;" align="center"><b>{{($interview->rating1 == 1)?'*':(($interview->rating1 == 2)?'**':(($interview->rating1 == 3)?'***':(($interview->rating1 == 4)?'****':(($interview->rating1 == 0)?'-':'*****'))))}}</b></td>
                                            <td style="border: 0.5px solid black !important; font-size:30px;" align="center"><b>{{($interview->rating2 == 1)?'*':(($interview->rating2 == 2)?'**':(($interview->rating2 == 3)?'***':(($interview->rating2 == 4)?'****':(($interview->rating2== 0)?'-':'*****'))))}}</b></td>
                                            <td style="border: 0.5px solid black !important; font-size:30px;" align="center"><b>{{($interview->rating3 == 1)?'*':(($interview->rating3 == 2)?'**':(($interview->rating3 == 3)?'***':(($interview->rating3 == 4)?'****':(($interview->rating3 == 0)?'-':'*****'))))}}</b></td>
                                            <td style="border: 0.5px solid black !important; font-size:30px;" align="center"><b>{{($interview->rating4 == 1)?'*':(($interview->rating4 == 2)?'**':(($interview->rating4 == 3)?'***':(($interview->rating4 == 4)?'****':(($interview->rating4 == 0)?'-':'*****'))))}}</b></td>
                                            <td style="border: 0.5px solid black !important; font-size:30px;" align="center"><b>{{($interview->rating5 == 1)?'*':(($interview->rating5 == 2)?'**':(($interview->rating5 == 3)?'***':(($interview->rating5 == 4)?'****':(($interview->rating5 == 0)?'-':'*****'))))}}</b></td>
                                            <td style="border: 0.5px solid black !important; font-size:12px;" align="center">{{($interview->videoLink == '')?'Not Uploaded':'Uploaded'}}</td>
                                            <td style="border: 0.5px solid black !important; font-size:12px;" align="center">{{$interview->remarks}}</td>
                                            <td style="border: 0.5px solid black !important; font-size:12px;" align="center">{{$interview->appStatus}}</td>
                                            <td style="border: 0.5px solid black !important; font-size:12px;" align="center">{{$interview->updated_by}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr style="border: 0.5px solid black !important;padding: 10px;">
                                        <th colspan="12" style="background-color:#80808026; border: 0.5px solid black !important;padding: 10px;" align="center"><b>3rd Round Pending</b></th>
                                    </tr>                                 
                                @endif
                            @endif
                        </table>
                        <br>
                        <hr>

                        <table width="100%" style="border: 0px solid white !important;">
                            <tr style="border: 0.5px solid black !important;padding: 10px;">
                                <th colspan="12" style="background-color:#80808026; border: 0.5px solid black !important;padding: 10px;" align="center"><b>4th Round Interview</b></th>
                            </tr>
                            @if(isset($interviews4))
                                @if(count($interviews4) >= 1)
                                    <tr style="background-color:#80808026;border: 0px solid black !important;padding: 10px;">
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="3%"><b>No.</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="21%"><b>Name</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Branch</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Timing</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Final Salary</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="8%"><b>Hike in Salary - Commitments if any</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="15%"><b>Remark</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="5%"><b>Status</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="5%"><b>Taken By</b></th>
                                        <th style="border: 0.5px solid black !important;padding: 10px; font-size:11px;" width="5%"><b>Updated By</b></th>
                                    </tr>
                                   <?php $i=1; ?>
                                    @foreach($interviews4 as $interview)
                                        <tr style="border: 0.5px solid black !important;padding: 5px;">
                                            <td style="border: 0.5px solid black !important;padding: 5px;" align="center">{{$i++}}</td>
                                            <td style="border: 0.5px solid black !important;padding: 5px; font-size:12px;">
                                                <b>
                                                    {{$interview->id}} - {{$interview->firstName}} {{$interview->middleName}} {{$interview->lastName}}<br>
                                                    {{$interview->mobileNo}}<br>
                                                    {{$interview->email}}<br>
                                                    {{$interview->designationName}}
                                                </b>
                                            </td>
                                            <td style="border: 0.5px solid black !important; font-size:12px;" align="center">{{$interview->branchId}}</td>
                                            <td style="border: 0.5px solid black !important; font-size:12px;" align="center">{{$interview->timing}}</td>
                                            <td style="border: 0.5px solid black !important; font-size:12px;" align="center">{{$interview->salary}}</td>
                                            <td style="border: 0.5px solid black !important; font-size:12px;" align="center">{{$interview->hikeComment}}</td>
                                            <td style="border: 0.5px solid black !important; font-size:12px;" align="center">{{$interview->remarks}}</td>
                                            <td style="border: 0.5px solid black !important; font-size:12px;" align="center">{{$interview->appStatus}}</td>
                                            <td style="border: 0.5px solid black !important; font-size:12px;" align="center">{{$interview->updatedBy}}</td>
                                            <td style="border: 0.5px solid black !important; font-size:12px;" align="center">{{$interview->updatedBy}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr style="border: 0.5px solid black !important;padding: 10px;">
                                        <th colspan="12" style="background-color:#80808026; border: 0.5px solid black !important;padding: 10px;" align="center"><b>4th Round Pending</b></th>
                                    </tr>                                 
                                @endif
                            @endif
                        </table>
                        <br>
                        <hr>
                    </td>
                    <td width="5%"></td>
                </tr>
            </table>
        </div>
    </body>
</html>