@php($data = storage_path('fonts/gargi.ttf'))
<!doctype html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
        <style>
            @font-face {
                src: url("{{$data}}") format('truetype');
                font-family: "gargi";
            }
            
            body {
                font-family: gargi, dejvu sans, sans-serif;
                border: 4px solid black;
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
                <table style="border: 1px solid black !important;">
                    <tr style="border: 1px solid black !important;">
                        <td width="30%" style="border: 1px solid black !important;"><img src="{{public_path('admin/assets/images/brand/logo.png')}}" width="200px" height="60px"></td>
                        @if($resins->section != 'Teaching')
                            <td width="70%" style="border: 1px solid black !important;"><h2><center>NO DUES FORM - NON TEACHING</center></h2></td>
                        @else    
                            <td width="70%" style="border: 1px solid black !important;"><h2><center>NO DUES FORM - TEACHING</center></h2></td>
                        @endif
                    </tr>
                </table>
                <table style="border: 0px solid black !important;">
                    <tr style="border: 0px solid black !important;">
                        <td width="40%" style="border: 0px solid black;font-size:12px; !important;">Name: <b style="font-size:20px;">{{$resins->empCode}}</b>&nbsp;&nbsp;<b style="font-size:17px;">{{$resins->name}}</b></td>
                       
                    </tr>
                </table>

                <table style="border: 0px solid black !important;">
                    <tr style="border: 0px solid black !important;">
                        <td width="30%" style="border: 0px solid black;font-size:12px; !important;">Designation: <b>{{$resins->designationName}}</b></td>
                        <td width="30%" style="border: 0px solid black;font-size:12px; !important;">Branch: <b>{{$resins->branchName}}</b></td>
                        <td width="20%" style="border: 0px solid black;font-size:12px; !important;">Mobile No: <b>{{$resins->phoneNo}}</b></td>
                        <td width="20%" style="border: 0px solid black;font-size:12px; !important;">WhatsApp No: <b>{{$resins->whatsappNo}}</b></td>
                    </tr>
                </table>

                <table style="border: 0px solid black !important;" width="100%">
                    <tr style="border: 0px solid black !important;">
                        <td style="border: 0px solid black !important;">
                            <table style="border: 1px solid black !important;" width="100%">
                                <tr style="border: 1px solid black !important;">
                                    <th width="20%" style="border: 1px solid black;font-size:15px;text-align: center; !important;">Content</th>
                                    <th width="20%" style="border: 1px solid black;font-size:15px;text-align: center; !important;">Details</th>
                                    <th width="20%" style="border: 1px solid black;font-size:15px;text-align: center; !important;">Date</th>
                                    <th width="20%" style="border: 1px solid black;font-size:15px;text-align: center; !important;">Authority</th>
                                    <th width="20%" style="border: 1px solid black;font-size:15px;text-align: center;!important;">Updated By</th>
                                </tr>
                                <tr style="border: 1px solid black !important;">
                                    <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;">Joining Date</td>
                                    <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                    <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b>{{($resins->jobJoingDate == null)?'-':date('d-m-Y', strtotime($resins->jobJoingDate))}}</b></td>
                                    <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                    <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                </tr>
                                <tr style="border: 1px solid black !important;">
                                    <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;">Last Working Date</td>
                                    <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                    <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b>{{($resins->lastDate == null)?'-':date('d-m-Y', strtotime($resins->lastDate))}}</b></td>
                                    <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                    <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                </tr>
                                @if($resins->section != 'Teaching')
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;">Student Data</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">{{($exitProcess2->apron == null || $exitProcess2->apron == 'Not Applicable')?'-':$exitProcess2->apron}}</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">{{($exitProcess2->apron == null || $exitProcess2->apron == 'Not Applicable')?'-':date('d-m-Y', strtotime($exitProcess2->created_at))}}</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">{{(!isset($exitProcess2))?'-':"Store Department"}}</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">{{(!isset($exitProcess2))?'-':"Store Department"}}</b></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;">PC, Mail ID & Password</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"><b style="text-align:center;">{{(!isset($exitProcess3))?'-':date('d-m-Y', strtotime($exitProcess3->created_at))}}</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"><b style="text-align:center;">IT Department</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"><b style="text-align:center;">IT Department</b></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;">Petty Cash</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">{{($exitProcess1->apron == null || $exitProcess1->apron == 'Not Applicable')?'-':$exitProcess1->apron}}</b></b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;">Uniform</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b><b style="text-align:center;">{{($exitProcess1->muster == null || $exitProcess1->muster == 'Not Applicable')?'-':$exitProcess1->muster}}</b></b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">Store Deparetment</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">Store Deparetment</b></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;">Store</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">{{($exitProcess1->reportCard == null || $exitProcess1->reportCard == 'Not Applicable')?'-':$exitProcess1->reportCard}}</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"><b style="text-align:center;">Store Department</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">Store Department</b></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;">Mobile With Sim card</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">{{(isset($exitProcess3))?(($exitProcess3->mobile == 'Yes' || $exitProcess3->sim == 'Yes')?('Yes'):'-'):'-'}}</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">{{(isset($exitProcess3))?(($exitProcess3->mobile == 'Yes' || $exitProcess3->sim == 'Yes')?(date('d-m-Y', strtotime($exitProcess3->created_at))):'-'):'-'}}</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">IT Department</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">IT Department</b></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;">Accounts</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">{{($exitProcess1->libraryBooks == null || $exitProcess1->libraryBooks == 'Not Applicable')?'-':$exitProcess1->libraryBooks}}</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;">Canteen</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">{{($exitProcess1->libraryBooks == null || $exitProcess1->libraryBooks == 'Not Applicable')?'-':$exitProcess1->libraryBooks}}</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;">Administration</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"><b style="text-align:center;">{{($exitProcess1->libraryBooks == null || $exitProcess1->libraryBooks == 'Not Applicable')?'-':$exitProcess1->libraryBooks}}</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;">Apron</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"><b style="text-align:center;">Store Deparetment</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"><b style="text-align:center;">Store Deparetment</b></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;">Blazer</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"><b style="text-align:center;">Store Deparetment</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"><b style="text-align:center;">Store Deparetment</b></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;">I - Card</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b>{{($exitProcess2->iCard == null || $exitProcess2->iCard == 'Not Applicable')?'-':$exitProcess2->iCard}}</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"><b style="text-align:center;">Store Deparetment</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">Store Deparetment</b></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;">Class room Keys</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"><b style="text-align:center;">{{($exitProcess1->logBook == null || $exitProcess1->logBook == 'Not Applicable')?'-':$exitProcess1->logBook}}</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">Reporting Authority</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"><b style="text-align:center;">Reporting Authority</b></td>
                                    </tr>
                                @else
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;">Apron</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">{{($exitProcess2->apron == null || $exitProcess2->apron == 'Not Applicable')?'-':$exitProcess2->apron}}</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">Store Deparetment</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">Store Deparetment</b></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;">Book Set</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"><b><b style="text-align:center;">{{($exitProcess1->bookSet == null || $exitProcess1->bookSet == 'Not Applicable')?'-':$exitProcess1->bookSet}}</b></b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;">Evaluation Sheet</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b><b style="text-align:center;">{{($exitProcess1->apron == null || $exitProcess1->apron == 'Not Applicable')?'-':$exitProcess1->apron}}</b></b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;">Muster</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b><b style="text-align:center;">{{($exitProcess1->muster == null || $exitProcess1->muster == 'Not Applicable')?'-':$exitProcess1->muster}}</b></b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;">Report Card</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">{{($exitProcess1->reportCard == null || $exitProcess1->reportCard == 'Not Applicable')?'-':$exitProcess1->reportCard}}</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;">Planner</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">{{($exitProcess1->planner == null || $exitProcess1->planner == 'Not Applicable')?'-':$exitProcess1->planner}}</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;">Accounts</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">{{($exitProcess1->libraryBooks == null || $exitProcess1->libraryBooks == 'Not Applicable')?'-':$exitProcess1->libraryBooks}}</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;">Library</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b style="text-align:center;">{{($exitProcess1->libraryBooks == null || $exitProcess1->libraryBooks == 'Not Applicable')?'-':$exitProcess1->libraryBooks}}</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;">Stores</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"><b style="text-align:center;">{{($exitProcess1->libraryBooks == null || $exitProcess1->libraryBooks == 'Not Applicable')?'-':$exitProcess1->libraryBooks}}</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;">Teacher's Kit</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;">I - Card</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"><b>{{($exitProcess2->iCard == null || $exitProcess2->iCard == 'Not Applicable')?'-':$exitProcess2->iCard}}</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                    </tr>
                                    <tr style="border: 1px solid black !important;">
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;">Log Book</td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"><b style="text-align:center;">{{($exitProcess1->logBook == null || $exitProcess1->logBook == 'Not Applicable')?'-':$exitProcess1->logBook}}</b></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px;text-align:center; !important;"></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; text-align:center;!important;"></td>
                                    </tr>
                                @endif
                            </table>
                        </td>
                    </tr>
                </table>

                <table style="border: 0px solid black !important;" width="100%">
                    <tr style="border: 0px solid black !important;">
                        <td style="border: 0px solid black !important;">
                            <table style="border: 0px solid black !important;">
                                <tr style="border: 0px solid black !important;">
                                    <td width="100%" style="border: 0px solid black;font-size:12px; !important;">I <b><u>{{$resins->empCode}}-{{$resins->name}}</u></b> hereby confirm that I have handed over the above mentioned, Material to the organization given to me at the time of Joining.</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <table style="border: 0px solid black !important;" width="100%">
                    <tr style="border: 0px solid black !important;">
                        <td style="border: 0px solid black !important;">
                            <table style="border: 1px solid black !important;" width="100%">
                                <tr style="border: 1px solid black !important;">
                                    <td style="border: 1px solid black;font-size:12px; !important;">
                                        @if(isset($exitProcess1))
                                            <center><b style="font-family: DejaVu Sans, sans-serif;color:green;font-size:13px">Approved ✔</b></center><br>
                                            {{date('d-m-Y h:i A', strtotime($exitProcess1->created_at))}}<br>
                                        @endif
                                    </td>
                                    <td style="border: 1px solid black;font-size:12px; !important;">
                                        @if(isset($exitProcess2))
                                            <center><b style="font-family: DejaVu Sans, sans-serif;color:green;font-size:13px">Approved ✔</b></center><br>
                                            {{date('d-m-Y h:i A', strtotime($exitProcess2->created_at))}}<br>
                                        @endif
                                    </td>
                                    <td style="border: 1px solid black;font-size:12px; !important;">
                                        @if(isset($exitProcess3))
                                            <center><b style="font-family: DejaVu Sans, sans-serif;color:green;font-size:13px">Approved ✔</b></center><br>
                                            {{date('d-m-Y h:i A', strtotime($exitProcess2->created_at))}}<br>
                                        @endif
                                    </td>
                                    <td style="border: 1px solid black;font-size:12px; !important;">
                                        @if(isset($exitProcess4))
                                            <center><b style="font-family: DejaVu Sans, sans-serif;color:green;font-size:13px">Approved ✔</b></center><br>
                                            {{date('d-m-Y h:i A', strtotime($exitProcess4->created_at))}}<br>
                                        @endif
                                    </td>
                                    <td style="border: 1px solid black;font-size:12px; !important;">
                                        @if(isset($exitProcess5))
                                            <center><b style="font-family: DejaVu Sans, sans-serif;color:green;font-size:13px">Approved ✔</b></center><br>
                                            {{date('d-m-Y h:i A', strtotime($exitProcess5->created_at))}}<br>
                                        @endif
                                    </td>
                                    <td style="border: 1px solid black;font-size:12px; !important;">
                                        @if(isset($exitProcess6))
                                            <center><b style="font-family: DejaVu Sans, sans-serif;color:green;font-size:13px">Approved ✔</b></center><br>
                                            {{date('d-m-Y h:i A', strtotime($exitProcess6->created_at))}}<br>
                                        @endif
                                    </td>
                                    <td style="border: 1px solid black;font-size:12px; !important;">
                                        @if(isset($exitProcess7))
                                            <center><b style="font-family: DejaVu Sans, sans-serif;color:green;font-size:13px">Approved ✔</b></center><br>
                                            {{date('d-m-Y h:i A', strtotime($exitProcess7->created_at))}}<br>
                                        @endif
                                    </td>
                                </tr>
                                <tr style="border: 1px solid black !important;">
                                    <th style="border: 1px solid black;font-size:13px; !important;"><center>Reporting Authority</center></th>
                                    <th style="border: 1px solid black;font-size:13px; !important;"><center>Store Department</center></th>
                                    <th style="border: 1px solid black;font-size:13px; !important;"><center>IT Department</center></th>
                                    <th style="border: 1px solid black;font-size:13px; !important;"><center>ERP Department</center></th>
                                    <th style="border: 1px solid black;font-size:13px; !important;"><center>HR Department</center></th>
                                    <th style="border: 1px solid black;font-size:13px; !important;"><center>{{($resins->section == 'Teaching')?'MD / CEO':'COO'}}</center></th>
                                    <th style="border: 1px solid black;font-size:13px; !important;"><center>Account Department</center></th>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <table style="border: 0px solid black !important;">
                    <tr style="border: 0px solid black !important;">
                        <td>
                            <h3><center>Confirmation towards release of following documents</center></h3>
                            <table>
                                <tr style="border: 1px solid black !important;">
                                    <th width="20%" style="border: 1px solid black; text-align: center; !important;">Particulars</th>
                                    <th width="20%" style="border: 1px solid black; text-align: center;!important;">Yes</th>
                                    <th width="20%" style="border: 1px solid black;text-align: center; !important;">No</th>
                                </tr>
                                <tr style="border: 1px solid black !important;">
                                    <td width="20%" style="border: 1px solid black;font-size:12px; !important;">Original Documents</td>
                                    @if($exitProcess1->originalDocs == 'Yes')
                                        <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center><b style="font-family: DejaVu Sans, sans-serif;color:green;font-size:13px"></b></center></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center></center></td>
                                    @else
                                        <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center>-</center></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center><div style="font-family: DejaVu Sans, sans-serif;color:red;font-size:13px">✗</center></td>
                                    @endif
                                </tr>
                                <tr style="border: 1px solid black !important;">
                                    <td width="20%" style="border: 1px solid black;font-size:12px; !important;">Experience Certificate</td>
                                    @if($exitProcess1->experienceCertificate == 'Yes')
                                        <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center><div style="font-family: DejaVu Sans, sans-serif;color:green;font-size:13px"><b>✔</b></center></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center>-</center></td>
                                    @else
                                        <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center>-</center></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center><div style="font-family: DejaVu Sans, sans-serif;color:red;font-size:13px">✗</center></td>
                                    @endif
                                </tr>
                                <tr style="border: 1px solid black !important;">
                                    <td width="20%" style="border: 1px solid black;font-size:12px; !important;">Salary</td>
                                    @if($exitProcess1->salary == 'Yes')
                                        <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center><div style="font-family: DejaVu Sans, sans-serif;color:green;font-size:13px"><b>✔</b></center></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center>-</center></td>
                                    @else
                                        <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center>-</center></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center><div style="font-family: DejaVu Sans, sans-serif;color:red;font-size:13px">✗</b> </center></td>
                                    @endif
                                </tr>
                                <tr style="border: 1px solid black !important;">
                                    <td width="20%" style="border: 1px solid black;font-size:12px; !important;">Retention Amount</td>
                                    @if($exitProcess1->retentionAmt == 'Yes')
                                        <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center><div style="font-family: DejaVu Sans, sans-serif;color:green;font-size:15px"><b>✔</b></div> </center></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center>-</center></td>
                                    @else
                                        <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center>-</center></td>
                                        <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center><div style="font-family: DejaVu Sans, sans-serif;color:red;font-size:15px">✗</div> </center></td>
                                    @endif
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                   
                <table style="border: 0px solid black !important;" width="100%">
                    <tr style="border: 0px solid black !important;">
                        <td width="33%"  style="border: 0px solid black !important;">
                            <table style="border: 0px solid black !important;">
                                <tr style="border: 0px solid black !important;">
                                    <td width="100%" style="border: 1px solid black !important;">
                                        @if(isset($exitProcess5))
                                            <center><b><div style="font-family: DejaVu Sans, sans-serif;color:green;font-size:15px"> Approved ✔</div>{{date('d-m-Y h:i A', strtotime($exitProcess5->updated_at))}}</b></center>
                                        @else
                                            <center><b>-</b></center>
                                        @endif
                                    </td>
                                </tr>
                                <tr style="border: 0px solid black !important;">
                                    <th width="100%" style="border: 1px solid black;text-align: center; !important;">HR Manager</th>
                                </tr>
                            </table>
                        </td>
                        <td width="33%" style="border: 0px solid black !important;">
                            <table style="border: 0px solid black !important;">
                                <tr style="border: 0px solid black !important;">
                                    <td width="100%" style="border: 1px solid black !important;">
                                        @if(isset($exitProcess6))
                                            <center><b><div style="font-family: DejaVu Sans, sans-serif;color:green;font-size:15px">Approved ✔</div>{{date('d-m-Y h:i A', strtotime($exitProcess6->updated_at))}}</b></center>
                                        @else
                                            <center><b>-</b></center>
                                        @endif
                                    </td>
                                </tr>
                                <tr style="border: 0px solid black !important;">
                                    <th width="100%" style="border: 1px solid black;text-align: center; !important;">
                                    @if($resins->section != 'Teaching')
                                        COO
                                    @else
                                        MD
                                    @endif
                                    </th>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <hr>
                <h>
                <table style="border: 0px solid black !important;" width="100%">
                    <tr style="border: 0px solid black !important;">
                        <td style="border: 0px solid black !important;">
                            <table style="border: 0px solid black !important;">
                                <tr style="border: 0px solid black !important;">
                                    <td width="70%" style="border: 0px solid black !important;">I <u><b>{{$resins->name}}</b></u> confirmed that I received following.</td>
                                    <td width="30%" style="border: 0px solid black;font-size:14px; !important;">Date : {{($resins->empAcceptanceDate != '' || $resins->empAcceptanceDate != null)?date('d-m-Y', strtotime($resins->empAcceptanceDate)):'-'}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <table style="border: 0px solid black !important;" width="100%">
                    <tr style="border: 0px solid black !important;">
                        <td style="text-align:center;border: 0px solid black !important;">
                            <table>
                                <tr style="border: 1px solid black !important;">
                                    <th width="20%" style="border: 1px solid black; text-align: center; !important;">Documents</th>
                                    <th width="20%" style="border: 1px solid black; text-align: center;!important;">Details</th>
                                    <th width="20%" style="border: 1px solid black;text-align: center; !important;">Sign / Updated By</th>
                                </tr>
                                <tr style="border: 1px solid black !important;">
                                    <td width="20%" style="border: 1px solid black;font-size:12px; !important;">Original Certificate</td>
                                    <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center><div style="font-family: DejaVu Sans, sans-serif;color:green;font-size:20px"><b></b></div> </center></td>
                                    <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center></center></td>
                                </tr>
                               
                                <tr style="border: 1px solid black !important;">
                                    <td width="20%" style="border: 1px solid black;font-size:12px; !important;">Salary</td>
                                    <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center><div style="font-family: DejaVu Sans, sans-serif;color:green;font-size:20px"><b></b></div> </center></td>
                                    <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center></center></td>
                                </tr>
                                <tr style="border: 1px solid black !important;">
                                    <td width="20%" style="border: 1px solid black;font-size:12px; !important;">Full & Final Retention Amount</td>
                                    <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center><div style="font-family: DejaVu Sans, sans-serif;color:green;font-size:20px"><b></b></div> </center></td>
                                    <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center></center></td>
                                </tr>
                                <tr style="border: 1px solid black !important;">
                                    <td width="20%" style="border: 1px solid black;font-size:12px; !important;">Experience Certificate</td>
                                    <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center><div style="font-family: DejaVu Sans, sans-serif;color:green;font-size:20px"><b></b></div> </center></td>
                                    <td width="20%" style="border: 1px solid black;font-size:12px; !important;"><center></center></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <table style="border: 0px solid black !important;">
                    <tr style="border: 0px solid black !important;">
                        <td style="border: 0px solid black !important;">
                            <table style="border: 0px solid black !important;">
                                <tr style="border: 0px solid black !important;">
                                    <td width="100%" style="border: 0px solid black;font-size:14px; !important;">I share cordial relationship with the organization. <br>I resign from the job willingly.</td>
                                </tr>
                                <tr style="border: 0px solid black !important;">
                                    <td width="100%" style="border: 0px solid black;font-size:14px; !important;">With this I, <u><b>{{$resins->name}}</b></u><br>confirm thot nothing is pending towards me and this is my full& final settlement. Henceforth I shall not make any financial & Legal claim to the organization. I will not cause the organization any discrepancy or
                                     any other consequences in the future. I have received all documents and dues.</td>
                                </tr>
                                <tr style="border: 0px solid black !important;">
                                    <td width="100%" style="border: 0px solid black;font-size:14px; !important;">Thanks for your cooperation.</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <table style="border: 0px solid black !important;">
                    <tr style="border: 0px solid black !important;">
                        <td style="border: 0px solid black !important;">
                            <table style="border: 0px solid black !important;">
                                <tr style="border: 0px solid black !important;">
                                    <td width="70%" style="border: 0px solid black;font-size:14px; !important;">Signature :- </td>
                                    <td width="30%" style="border: 0px solid black;font-size:14px; !important;">Date :- </td>
                                </tr>
                                <tr style="border: 0px solid black !important;">
                                    <td width="100%" colspan="2" style="border: 0px solid black;font-size:14px; !important;">Name:- <u><b>{{$resins->empCode}}-{{$resins->name}}</b></u> </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>