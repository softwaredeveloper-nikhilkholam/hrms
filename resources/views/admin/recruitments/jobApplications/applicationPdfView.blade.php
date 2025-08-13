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
        
        
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <table style="border: 0px solid white !important;">
                <tr style="border: 0px solid white !important;">
                    <td style="border: 0px solid white !important;"><p style="margin-top:0px;text-align:left;font-size:10px;">PDF Generated At : {{date('d/m/Y h:i A')}}</p>   </td>
                    <th style="border: 0px solid white !important;" align="right"><p style="margin-top:0px;text-align:right;font-size:10px;"><b>AWS</b></p></th>
                </tr>
            </table>
            <?php
                use App\Helpers\Utility;
                $util=new Utility(); 
            ?>
            <div style="text-align: center">
                <b style="font-size:15px;">
                    Candidate Application
                </b> <br>
            </div>
            <hr>
            <table width="100%" border="0">
                <tr>
                    <td>Job Position : {{$application->jobPosition}}</td>
                    <td></td>
                    <td align="right">Date : {{date('d-m-Y', strtotime($application->forDate))}}</td>
                </tr>
            </table>
            <hr>
            <h5>Personal Details</h5>
            <table width="100%" border="0" style="font-size:12px;">
                <tr>
                    <th align="left">First Name </th>
                    <td>{{$application->firstName}}</td>
                    <th align="left">Middel Name </th>
                    <td>{{$application->middleName}}</td>
                    <th align="left">Last Name </th>
                    <td>{{$application->lastName}}</td>
                </tr>
                <tr>
                    <th align="left">Mobile No. </th>
                    <td>{{$application->mobileNo}}</td>
                    <th align="left">Emergency No. </th>
                    <td>{{($application->emergencyNo == '')?'NA':$application->emergencyNo}}</td>
                    <th align="left">Mother Name : </th>
                    <td>{{$application->motherName}}</td>
                </tr>
                <tr>
                    <th align="left">Birth Date </th>
                    <td>{{($application->DOB == '')?'NA':$application->DOB}}</td>
                    <th align="left">Marital status </th>
                    <td>{{$application->maritalStatus}}</td>
                    <th align="left">Language known </th>
                    <td>{{$application->language}}</td>
                </tr>
            </table>
            <hr>
            <h5>Education Details</h5>
            <table width="100%" style="font-size:12px;border: 1px solid black;border-collapse: collapse;">
                <tr>
                    <th style="border: 1px solid black;padding: 4px;">No.</th>
                    <th style="border: 1px solid black;padding: 4px;">Education</th>
                    <th style="border: 1px solid black;padding: 4px;">Board / Universtity</th>
                    <th style="border: 1px solid black;padding: 4px;">Year Of Passing</th>
                    <th style="border: 1px solid black;padding: 4px;">Percentage</th>
                </tr>
                <tr>
                    <td align="center" style="border: 1px solid black;padding: 4px;">1</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">Std 10</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->board10Th}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->yearPass10Th}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->percent10Th}}</td>
                </tr>
                <tr>
                    <td align="center" style="border: 1px solid black;padding: 4px;">2</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">Std 12th</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->board12Th}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->yearPass12Th}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->percent12Th}}</td>
                </tr>
                <tr>
                    <td align="center" style="border: 1px solid black;padding: 4px;">3</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">Graduate</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->boardGrad}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->yearPassGrad}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->percentGrad}}</td>
                </tr>
                <tr>
                    <td align="center" style="border: 1px solid black;padding: 4px;">4</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">Post Graduate</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->boardPostG}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->yearPassPostG}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->percentPostG}}</td>
                </tr>
            </table>
            <hr>
            <h5>Computer Proficiency Details</h5>
            <table width="100%" border="0" style="font-size:12px;">
                <tr>
                    <th align="left">Work Experience (Till Date:) : {{$application->totalWorkExp}}</th>
                    <th align="left">Last Salary : {{$application->lastSalary}}</th>
                </tr>
            </table>
            <table width="100%" style="font-size:12px;border: 1px solid black;border-collapse: collapse;">
                <tr>
                    <th style="border: 1px solid black;padding: 4px;">No.</th>
                    <th style="border: 1px solid black;padding: 4px;">Name Of The Organisations</th>
                    <th style="border: 1px solid black;padding: 4px;">Exp In Years</th>
                    <th style="border: 1px solid black;padding: 4px;">Responsiblity/Post</th>
                    <th style="border: 1px solid black;padding: 4px;">Reason For Leaving</th>
                </tr>
                <tr>
                    <td align="center" style="border: 1px solid black;padding: 4px;">1</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->organisation1}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->exp1}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->respon1}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->reasonLeav1}}</td>
                </tr>
                <tr>
                    <td align="center" style="border: 1px solid black;padding: 4px;">2</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->organisation2}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->exp2}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->respon2}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->reasonLeav2}}</td>
                </tr>
                <tr>
                    <td align="center" style="border: 1px solid black;padding: 4px;">3</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->organisation3}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->exp3}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->respon3}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->reasonLeav3}}</td>
                </tr>
            </table><br>
            <table width="100%" border="0" style="font-size:12px;">
                <tr>
                    <th align="left">Your Strenghths : {{$application->yourStrenghths}}</th>
                    <th align="left">Hobbies : {{$application->hobbies}}</th>
                </tr>
            </table>
            <br>
            <b style="font-size:14px;">Declaration :</b><br>
            <b style="color:red;font-size:12px;">I hereby declare that the above information is true & correct.</b>
            <hr>
            @if(isset($interview1))
                <h4><center>Office Work</center></h4>
                <table width="100%" style="font-size:12px;border: 1px solid black;border-collapse: collapse;">
                    <tr>
                        <th style="border: 1px solid black;font-size:15px;color:red;padding: 7px;" colspan="4">1St Round Details</th>
                    </tr>
                    <tr>
                        <th style="border: 1px solid black;padding: 7px;"  align="left" width="20%">Remark</th>
                        <td style="border: 1px solid black;padding: 7px;" colspan="3"> {{$interview1->remarks}}</td>
                    </tr>
                    <tr>
                        <th style="border: 1px solid black;padding: 7px;"  align="left"  width="20%">Application Status</th>
                        <td style="border: 1px solid black;padding: 7px;"> {{$interview1->appStatus}}</td>
                        <th style="border: 1px solid black;padding: 7px;"  align="left"  width="20%">Expected Salary</th>
                        <td style="border: 1px solid black;padding: 7px;"  width="20%">{{$interview1->expectedSalary}}</td>
                    </tr>
                </table>
                <br>
            @endif
            @if(isset($interview2))
                <table width="100%" style="font-size:12px;border: 1px solid black;border-collapse: collapse;">
                    <tr>
                        <th style="border: 1px solid black;font-size:15px;color:red;padding: 7px;" colspan="4">2nd Round Details</th>
                    </tr>
                    <tr>
                        <th style="border: 1px solid black;padding: 7px;">Remark</th>
                        <td style="border: 1px solid black;padding: 7px;" colspan="3">{{$interview2->remarks}}</td>
                    </tr>
                    <tr>
                        <th style="border: 1px solid black;padding: 7px;">Application Status</th>
                        <td style="border: 1px solid black;padding: 7px;">{{$interview2->appStatus}}</td>
                        <th style="border: 1px solid black;padding: 7px;">Offered Salary</th>
                        <td style="border: 1px solid black;padding: 7px;">{{$interview2->offeredSalary}}</td>
                    </tr>
                    <tr>
                        <th style="border: 1px solid black;padding: 7px;">Rating</th>
                        <td style="border: 1px solid black;padding: 7px;">{{$interview2->rating}}/5</td>
                        <th style="border: 1px solid black;padding: 7px;"></th>
                        <th style="border: 1px solid black;padding: 7px;"></th>
                    </tr>
                </table>
            @endif
        </div>
    </body>
</html>