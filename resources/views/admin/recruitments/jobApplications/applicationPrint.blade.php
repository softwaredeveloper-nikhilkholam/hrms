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
                    <td>Recruitement No : {{$application->id}}</td>
                    <td></td>
                    <td align="right">Date : {{date('d-m-Y', strtotime($application->forDate))}}</td>
                </tr>
            </table>
            <hr>
            
            <table width="100%" border="0" style="font-size:12px;">
                <tr>
                    <td width="70%">
                        <table width="100%" border="0" style="font-size:12px;">
                            <tr>
                                <th align="left">Section</th>
                                <td>{{$application->section}}</td>
                                <th align="left">Department </th>
                                <td>{{$application->departmentName}}</td>
                            </tr>
                            <tr>
                                <th align="left">Post Applied for</th>
                                <td>{{$application->designationName}}</td>
                            </tr>
                        </table>
                    </td>
                    <td width="30%">
                        <table width="100%" border="0" style="font-size:12px;">
                            <tr>
                                <th align="left">
                                    @if($application->profilePhoto != '')    
                                        <img src="{{ ($application->profilePhoto != '')?public_path('/admin/images/recPhotos/'.$application->profilePhoto):'#' }}" width="100" height="100">
                                    @endif
                                </th>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
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
                    <th align="left">Mother Name : </th>
                    <td>{{$application->motherName}}</td>
                    <th align="left"></th>
                    <td></td>
                    <th align="left"></th>
                    <td></td>
                </tr>
            </table>
            <h5>Name as on Adhar Card</h5>
            <table width="100%" border="0" style="font-size:12px;">
                <tr>
                    <th align="left">First Name </th>
                    <td>{{$application->adharFirstName}}</td>
                    <th align="left">Middel Name </th>
                    <td>{{$application->adharMiddleName}}</td>
                    <th align="left">Last Name </th>
                    <td>{{$application->adharLastName}}</td>
                </tr>
                <tr>
                    <th align="left">Birth Date </th>
                    <td>{{($application->DOB == '')?'NA':$application->DOB}}</td>
                    <th align="left">Gender</th>
                    <td>{{$application->gender}}</td>
                    <th align="left">Religion</th>
                    <td>{{($application->religion == '')?'NA':$application->religion}}</td>
                </tr>
                <tr>
                    <th align="left">Caste</th>
                    <td>{{$application->caste}}</td>
                    <th align="left">Category</th>
                    <td>{{$application->category}}</td>
                    <th align="left">Marital status</th>
                    <td>{{$application->maritalStatus}}</td>
                </tr>
            </table>
            <hr>
            <table width="100%" border="0" style="font-size:12px;">
                <tr>
                    <th align="left">Present Address in detail</th>
                    <td>{{$application->presentAddress}}</td>
                </tr>
                <tr>
                    <th align="left">Permenant Address in detail</th>
                    <td>{{$application->permanentAddress}}</td>
                </tr>
            </table>
            <hr>
            <table width="100%" border="0" style="font-size:12px;">
                <tr>
                    <th align="left">Mobile No.</th>
                    <td>{{$application->mobileNo}}</td>
                    <th align="left">W.A. No.</th>
                    <td>{{$application->whatsMobileNo}}</td>
                    <th align="left">Email Id </th>
                    <td>{{$application->email}}</td>
                </tr>
            </table>
            <hr>
            <h5>Available on</h5>
            <table width="100%" border="0" style="font-size:12px;">
                <tr>
                    <th align="left">Facebook</th>
                    <td>{{($application->facebook == 1)?'Yes':'No'}}</td>
                    <th align="left">Instagram</th>
                    <td>{{($application->instagram == 1)?'Yes':'No'}}</td>
                    <th align="left">LinkedIn</th>
                    <td>{{($application->linkedIn == 1)?'Yes':'No'}}</td>
                    <th align="left">Twitter</th>
                    <td>{{($application->twitter == 1)?'Yes':'No'}}</td>
                    <th align="left">YouTube</th>
                    <td>{{($application->youTube == 1)?'Yes':'No'}}</td>
                    <th align="left">Google +</th>
                    <td>{{($application->googlePlus == 1)?'Yes':'No'}}</td>
                </tr>
            </table>
            <hr>
            <h5>Languages known</h5>
            <table width="100%" border="0" style="font-size:12px;">
                <tr>
                    <th align="left">English</th>
                    <td>{{($application->english == 1)?'Yes':'No'}}</td>
                    <th align="left">Hindi</th>
                    <td>{{($application->hindi == 1)?'Yes':'No'}}</td>
                    <th align="left">Marathi</th>
                    <td>{{($application->marathi == 1)?'Yes':'No'}}</td>
                    <th align="left">Other</th>
                    <td>{{($application->otherLanguage == 1)?'Yes':'No'}}</td>
                </tr>
            </table>
            <hr>
            <h5>Emergency contact details</h5>
            <table width="100%" border="0" style="font-size:12px;">
                <tr>
                    <th align="left">Name of the person</th>
                    <td>{{$application->emergencePersonName}}</td>
                    <th align="left">Relation</th>
                    <td>{{$application->emergenceRelation}}</td>
                    <th align="left">Mob</th>
                    <td>{{$application->emergenceMob}}</td>
                </tr>
            </table>
            <hr>
            <h5>Advertisement and Reference Source</h5>
            <table width="100%" border="0" style="font-size:12px;">
                <tr>
                    <th align="left">Advertisement Source</th>
                    <td>{{$application->advSource}}</td>
                    <th align="left"></th>
                    <td></td>
                    <th align="left"></th>
                    <td></td>
                </tr>
            </table>
            <hr>
            <br><br><br>
            <h5>Educational Qualification Details</h5>
            <table width="100%" style="font-size:12px;border: 1px solid black;border-collapse: collapse;">
                <tr>
                    <th style="border: 1px solid black;padding: 4px;">No.</th>
                    <th style="border: 1px solid black;padding: 4px;">Education</th>
                    <th style="border: 1px solid black;padding: 4px;">Degree / Stream / Qualification</th>
                    <th style="border: 1px solid black;padding: 4px;">Board / Universtity</th>
                    <th style="border: 1px solid black;padding: 4px;">Year Of Passing</th>
                    <th style="border: 1px solid black;padding: 4px;">Percentage</th>
                </tr>
                <tr>
                    <td align="center" style="border: 1px solid black;padding: 4px;">1</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">Std. 10th</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->degree1}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->board1}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->passingYear1}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->percent1}}</td>
                </tr>
                <tr>
                    <td align="center" style="border: 1px solid black;padding: 4px;">2</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">Std. 12th</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->degree2}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->board2}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->passingYear2}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->percent2}}</td>
                </tr>
                <tr>
                    <td align="center" style="border: 1px solid black;padding: 4px;">3</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">Graduation</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->degree3}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->board3}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->passingYear3}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->percent3}}</td>
                </tr>
                <tr>
                    <td align="center" style="border: 1px solid black;padding: 4px;">4</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">Post Graduation</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->degree4}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->board4}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->passingYear4}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->percent4}}</td>
                </tr>
                <tr>
                    <td align="center" style="border: 1px solid black;padding: 4px;">5</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">Trainee Degree</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->degree5}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->board5}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->passingYear5}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->percent5}}</td>
                </tr>
                <tr>
                    <td align="center" style="border: 1px solid black;padding: 4px;">6</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">Methods / Subjects / Topic</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->degree6}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->board6}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->passingYear6}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->percent6}}</td>
                </tr>
                <tr>
                    <td align="center" style="border: 1px solid black;padding: 4px;">7</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">Any other Special qualification (if any)</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->overallComputerProficiency}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->board7}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->passingYear7}}</td>
                    <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->percent7}}</td>
                </tr>
            </table>
            <hr>
            <h5>Overall Computer Proficiency</h5>
            <table width="100%" border="0" style="font-size:12px;">
                <tr>
                    <th align="left">Beginner</th>
                    <td>{{($application->overallComputerProficiency == 1)?'Yes':'No'}}</td>
                    <th align="left">Medium</th>
                    <td>{{($application->overallComputerProficiency == 2)?'Yes':'No'}}</td>
                    <th align="left">Expert</th>
                    <td>{{($application->overallComputerProficiency == 3)?'Yes':'No'}}</td>
                </tr>
            </table>
            <hr>
            <h5>Microsoft Office (Word, Excel, PPT)</h5>
            <table width="100%" border="0" style="font-size:12px;">
                <tr>
                    <th align="left">Beginner</th>
                    <td>{{($application->microsoftOffice == 1)?'Yes':'No'}}</td>
                    <th align="left">Medium</th>
                    <td>{{($application->microsoftOffice == 2)?'Yes':'No'}}</td>
                    <th align="left">Expert</th>
                    <td>{{($application->microsoftOffice == 3)?'Yes':'No'}}</td>
                </tr>
            </table>
            <hr>
            <table width="100%" border="0" style="font-size:12px;">
                <tr>
                    <th align="left">Special Education / Certification in Ccomputer (if any)</th>
                    <td>{{$application->specialEducation}}</td>
                </tr>
            </table>
            <hr>
            <table width="100%" border="0" style="font-size:12px;">
                <tr>
                    <th align="left">Work Experience Details</th>
                    <td>{{($application->workExperienceDetails == 1)?'Fresher':'Experienced'}}</td>
                    @if($application->workExperienceDetails == 2)
                        <th align="left">Total Experience</th>
                        <td>{{$application->experience}}</td>
                    @endif
                </tr>
            </table>
            <hr>
            @if($application->workExperienceDetails == 2)
                <h5>Work Experience (till date)(Please mention all the experiences whether it is relevant or not)</h5>
                <table width="100%" style="font-size:12px;border: 1px solid black;border-collapse: collapse;">
                    <tr>
                        <th style="border: 1px solid black;padding: 4px;">No.</th>
                        <th style="border: 1px solid black;padding: 4px;">Name Of The Organizations</th>
                        <th style="border: 1px solid black;padding: 4px;">Exp In Yrs/Mnths</th>
                        <th style="border: 1px solid black;padding: 4px;">From (Month And Year)</th>
                        <th style="border: 1px solid black;padding: 4px;">To (Month And Year)</th>
                        <th style="border: 1px solid black;padding: 4px;">Post/Responsibility</th>
                        <th style="border: 1px solid black;padding: 4px;">Std&Sub</th>
                        <th style="border: 1px solid black;padding: 4px;">Reason For Leaving</th>
                    </tr>
                    <tr>
                        <td align="center" style="border: 1px solid black;padding: 4px;">1</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->organisation1}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->exp1}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->from1}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->to1}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->post1}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->std1}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->reason1}}</td>
                    </tr>
                    <tr>
                        <td align="center" style="border: 1px solid black;padding: 4px;">2</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->organisation2}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->exp2}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->from2}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->to2}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->post2}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->std2}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->reason2}}</td>
                    </tr>
                    <tr>
                        <td align="center" style="border: 1px solid black;padding: 4px;">3</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->organisation3}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->exp3}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->from3}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->to3}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->post3}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->std3}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->reason3}}</td>
                    </tr>
                    <tr>
                        <td align="center" style="border: 1px solid black;padding: 4px;">4</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->organisation4}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->exp4}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->from4}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->to4}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->post4}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->std4}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->reason4}}</td>
                    </tr>
                    
                </table>
                <hr>
                <h5>Reference details of last two Organizations</h5>
                <table width="100%" style="font-size:12px;border: 1px solid black;border-collapse: collapse;">
                    <tr>
                        <th style="border: 1px solid black;padding: 4px;">No.</th>
                        <th style="border: 1px solid black;padding: 4px;">Name Of The Organization</th>
                        <th style="border: 1px solid black;padding: 4px;">Name Of Reporting Authority</th>
                        <th style="border: 1px solid black;padding: 4px;">Post Of Reporting Authority</th>
                        <th style="border: 1px solid black;padding: 4px;">Contact No.</th>
                        <th style="border: 1px solid black;padding: 4px;">Email Id</th>
                    </tr>
                    <tr>
                        <td align="center" style="border: 1px solid black;padding: 4px;">1</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->refOrganization1}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->refrepoAuth1}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->refRepoAuthPost1}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->refContctNo1}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->refEmail1}}</td>
                    </tr>
                    <tr>
                        <td align="center" style="border: 1px solid black;padding: 4px;">2</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->refOrganization2}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->refrepoAuth2}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->refRepoAuthPost2}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->refContctNo2}}</td>
                        <td align="center" style="border: 1px solid black;padding: 4px;">{{$application->refEmail2}}</td>
                    </tr>
                </table>
                <hr>
            @endif
            <table width="100%" border="0" style="font-size:12px;">
                <tr>
                    <th align="left">Last drawn in-hand salary</th>
                    <th align="left">{{$application->lastSalary}}</th>
                    <th align="left">Expected Salary</th>
                    <th align="left">{{$application->expectedSalary}}</th>
                </tr>
            </table>
            <hr>
            <h5>About you</h5>
            <table width="100%" style="font-size:12px;border: 1px solid black;border-collapse: collapse;">
                <tr>
                    <th align="left" style="border: 1px solid black;padding: 4px;">Your Strenghths</th>
                    <td align="left" style="border: 1px solid black;padding: 4px;">{{$application->strenghths}}</td>
                </tr>
                <tr>
                    <th align="left" style="border: 1px solid black;padding: 4px;">Hobbies</th>
                    <td align="left" style="border: 1px solid black;padding: 4px;">{{$application->hobbies}}</td>
                </tr>
                <tr>
                    <th align="left" style="border: 1px solid black;padding: 4px;">Extra-curricular activities and achievements (if any)</th>
                    <td align="left" style="border: 1px solid black;padding: 4px;">{{$application->extraCurricular}}</td>
                </tr>
            </table>
            <hr>
            <h5>Medical History if any</h5>
            <table width="100%" style="font-size:12px;border: 1px solid black;border-collapse: collapse;">
                <tr>
                    <th align="left" style="border: 1px solid black;padding: 4px;">Previous</th>
                    <th align="left" style="border: 1px solid black;padding: 4px;">{{$application->medicalPrevious}}</th>
                </tr>
                <tr>
                    <th align="left" style="border: 1px solid black;padding: 4px;">Current</th>
                    <th align="left" style="border: 1px solid black;padding: 4px;">{{$application->medicalCurrent}}</th>
                </tr>
                <tr>
                    <th align="left" style="border: 1px solid black;padding: 4px;">Blood Group</th>
                    <th align="left" style="border: 1px solid black;padding: 4px;">{{$application->bloodGp}}</th>
                </tr>
                <tr>
                    <th align="left" style="border: 1px solid black;padding: 4px;">Previously applied here</th>
                    <th align="left" style="border: 1px solid black;padding: 4px;">{{$application->prevAppliedFor}}</th>
                </tr>
                <tr>
                    <th align="left" style="border: 1px solid black;padding: 4px;">Ex-Employee of Aaryans World School</th>
                    <th align="left" style="border: 1px solid black;padding: 4px;">{{$application->exEmployee}}</th>
                </tr>
            </table>
            <br>
            <b style="font-size:14px;">Declaration :</b><br>
            <b style="color:red;font-size:12px;">I hereby declare that the above information is true & correct.</b>
            <hr>
            <h4>Interview Details</h4>
            <hr>
            @if($interview1)
                <h5><center>Interview 1 <br>[Taken By {{$interview1->name}}]</center></h5>
                <table width="100%" style="font-size:12px;border: 1px solid black;border-collapse: collapse;">
                    <tr>
                        <th align="center" style="border: 1px solid black;padding: 4px;">Eligibility</th>
                        <th align="center" style="border: 1px solid black;padding: 4px;">Smartness</th>
                        <th align="center" style="border: 1px solid black;padding: 4px;">Knowledge</th>
                        <th align="center" style="border: 1px solid black;padding: 4px;">Appearance</th>
                        <th align="center" style="border: 1px solid black;padding: 4px;">English Fluency</th>
                        <th align="center" style="border: 1px solid black;padding: 4px;">Confidence</th>
                    </tr>
                    <tr>
                        <th align="center" style="border: 1px solid black;padding: 4px;">{{$interview1->rating1}}/5</th>
                        <th align="center" style="border: 1px solid black;padding: 4px;">{{$interview1->rating2}}/5</th>
                        <th align="center" style="border: 1px solid black;padding: 4px;">{{$interview1->rating3}}/5</th>
                        <th align="center" style="border: 1px solid black;padding: 4px;">{{$interview1->rating4}}/5</th>
                        <th align="center" style="border: 1px solid black;padding: 4px;">{{$interview1->rating5}}/5</th>
                        <th align="center" style="border: 1px solid black;padding: 4px;">{{$interview1->rating6}}/5</th>
                    </tr>
                </table>
                <table width="100%" style="font-size:12px;border: 1px solid black;border-collapse: collapse;">
                    <tr>
                        <th width="30%"  align="left" style="border: 1px solid black;padding: 4px;">Remarks</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview1->remarks}}</td>
                    </tr>
                    <tr>
                        <th width="30%" align="left" style="border: 1px solid black;padding: 4px;">Expected Salary</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview1->expectedSalary}}</td>
                    </tr>
                    <tr>
                        <th width="30%" align="left" style="border: 1px solid black;padding: 4px;">Post Offered</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview1->postOffered}}</td>
                    </tr>
                    <tr>
                        <th width="30%" align="left" style="border: 1px solid black;padding: 4px;">Offered Salary</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview1->offeredSalary}}</td>
                    </tr>
                    <tr>
                        <th width="30%" align="left" style="border: 1px solid black;padding: 4px;">Application Status</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview1->appStatus}}</td>
                    </tr>
                </table>
                <hr>
            @endif
            
            @if($interview2)
                <h5><center>Interview 2 <br>[Taken By {{$interview2->name}}]</center></h5>
                <table width="100%" style="font-size:12px;border: 1px solid black;border-collapse: collapse;">
                    <tr>
                        <th width="30%"  align="left" style="border: 1px solid black;padding: 4px;">Date Of Demo</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview2->demoDate}}</td>
                    </tr>
                    <tr>
                        <th width="30%" align="left" style="border: 1px solid black;padding: 4px;">Branch</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview2->branchId}}</td>
                    </tr>
                    <tr>
                        <th width="30%" align="left" style="border: 1px solid black;padding: 4px;">Subject</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview2->subject}}</td>
                    </tr>
                    <tr>
                        <th width="30%" align="left" style="border: 1px solid black;padding: 4px;">Standard</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview2->standard}}</td>
                    </tr>
                    <tr>
                        <th width="30%" align="left" style="border: 1px solid black;padding: 4px;">Topic</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview2->topic}}</td>
                    </tr>
                    <tr>
                        <th width="30%" align="left" style="border: 1px solid black;padding: 4px;">Video Link</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview2->videoLink}}</td>
                    </tr>
                    <tr>
                        <th width="30%" align="left" style="border: 1px solid black;padding: 4px;">Name Of The Observer</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview2->nameOfObserver}}</td>
                    </tr>
                    <tr>
                        <th width="30%" align="left" style="border: 1px solid black;padding: 4px;">Remark Of The Observer</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview2->remarks}}</td>
                    </tr>
                    <tr>
                        <th width="30%" align="left" style="border: 1px solid black;padding: 4px;">Recomandation</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview2->recomandation}}</td>
                    </tr>
                    <tr>
                        <th width="30%" align="left" style="border: 1px solid black;padding: 4px;">Application Status</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview2->appStatus}}</td>
                    </tr>
                </table>
                <hr>
            @endif
            @if($interview3)
                <h5><center>Interview  <br>[Taken By {{$interview3->name}}]</center></h5>
                <table width="100%" style="font-size:12px;border: 1px solid black;border-collapse: collapse;">
                    <tr>
                        <th align="center" style="border: 1px solid black;padding: 4px;">Eligibility</th>
                        <th align="center" style="border: 1px solid black;padding: 4px;">Smartness</th>
                        <th align="center" style="border: 1px solid black;padding: 4px;">Knowledge</th>
                        <th align="center" style="border: 1px solid black;padding: 4px;">Appearance</th>
                        <th align="center" style="border: 1px solid black;padding: 4px;">English Fluency</th>
                        <th align="center" style="border: 1px solid black;padding: 4px;">Confidence</th>
                    </tr>
                    <tr>
                        <th align="center" style="border: 1px solid black;padding: 4px;">{{$interview3->rating1}}/5</th>
                        <th align="center" style="border: 1px solid black;padding: 4px;">{{$interview3->rating2}}/5</th>
                        <th align="center" style="border: 1px solid black;padding: 4px;">{{$interview3->rating3}}/5</th>
                        <th align="center" style="border: 1px solid black;padding: 4px;">{{$interview3->rating4}}/5</th>
                        <th align="center" style="border: 1px solid black;padding: 4px;">{{$interview3->rating5}}/5</th>
                        <th align="center" style="border: 1px solid black;padding: 4px;">{{$interview3->rating6}}/5</th>
                    </tr>
                </table>
                <table width="100%" style="font-size:12px;border: 1px solid black;border-collapse: collapse;">
                    <tr>
                        <th width="30%"  align="left" style="border: 1px solid black;padding: 4px;">Remarks</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview3->remarks}}</td>
                    </tr>
                    <tr>
                        <th width="30%" align="left" style="border: 1px solid black;padding: 4px;">Expected Salary</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview3->expectedSalary}}</td>
                    </tr>
                    <tr>
                        <th width="30%" align="left" style="border: 1px solid black;padding: 4px;">Post Offered</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview3->postOffered}}</td>
                    </tr>
                    <tr>
                        <th width="30%" align="left" style="border: 1px solid black;padding: 4px;">Offered Salary</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview3->offeredSalary}}</td>
                    </tr>
                    <tr>
                        <th width="30%" align="left" style="border: 1px solid black;padding: 4px;">Application Status</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview3->appStatus}}</td>
                    </tr>
                </table>
                <hr>
            @endif
            @if($interview4)
                <h5><center>Interview 4 <br>[Taken By {{$interview4->name}}]</center></h5>
                <table width="100%" style="font-size:12px;border: 1px solid black;border-collapse: collapse;">
                    <tr>
                        <th width="30%"  align="left" style="border: 1px solid black;padding: 4px;">Selected Branch</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview4->branchId}}</td>
                        <th width="30%"  align="left" style="border: 1px solid black;padding: 4px;">Post Offered</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview4->postOffered}}</td>
                    </tr>
                    <tr>
                        <th width="30%"  align="left" style="border: 1px solid black;padding: 4px;">Section Selected For</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview4->sectionSelectedFor}}</td>
                        <th width="30%"  align="left" style="border: 1px solid black;padding: 4px;">Subject Selected For</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview4->subjectSelectedFor}}</td>
                    </tr>
                    <tr>
                        <th width="30%"  align="left" style="border: 1px solid black;padding: 4px;">Date Of Joining</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview4->dateOfJoining}}</td>
                        <th width="30%"  align="left" style="border: 1px solid black;padding: 4px;">Reporting Authority</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview4->reportingAuthId}}</td>
                    </tr>
                    <tr>
                        <th width="30%"  align="left" style="border: 1px solid black;padding: 4px;">Mentor / Buddy</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview4->mentorBuddy}}</td>
                        <th width="30%"  align="left" style="border: 1px solid black;padding: 4px;">Timing</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview4->timing}}</td>
                    </tr>
                    <tr>
                        <th width="30%"  align="left" style="border: 1px solid black;padding: 4px;">Final Salary</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview4->salary}}</td>
                        <th width="30%"  align="left" style="border: 1px solid black;padding: 4px;">Application Status</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview4->appStatus}}</td>
                    </tr>
                </table>
                <table width="100%" style="font-size:12px;border: 1px solid black;border-collapse: collapse;">
                    <tr>
                        <th width="30%"  align="left" style="border: 1px solid black;padding: 4px;">Hike In Salary - Commitments If Any</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview4->hikeComment}}</td>
                    </tr>
                    <tr>
                        <th width="30%" align="left" style="border: 1px solid black;padding: 4px;">Remarks</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview4->remarks}}</td>
                    </tr>
                </table>
                <table width="100%" style="font-size:12px;border: 1px solid black;border-collapse: collapse;">
                    <tr>
                        <th width="30%"  align="left" style="border: 1px solid black;padding: 4px;">Signature</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview4->signs}}</td>
                        <th width="30%"  align="left" style="border: 1px solid black;padding: 4px;">Username</th>
                        <td align="left" style="border: 1px solid black;padding: 4px;">{{$interview4->username}}</td>
                    </tr>
                </table>
                <hr>
            @endif
        </div>
    </body>
</html>