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
            <table style="border: 0px solid white !important;">
                <tr style="border: 0px solid white !important;">
                    <td style="border: 0px solid white !important;"><p style="margin-top:0px;text-align:left;font-size:10px;">PDF Generated At : {{date('d/m/Y h:i A')}}</p>   </td>
                    <td style="border: 0px solid white !important;"><p style="margin-top:0px;text-align:right;font-size:10px;"><b>Aaryans World</b></p></td>
                </tr>
            </table>
            <?php
                use App\Helpers\Utility;
                $util=new Utility(); 
            ?>
                <div style="text-align: center">
                    <b style="font-size:15px;">
                        AGF List Of {{date('M-Y', strtotime($month))}}
                    </b> <br>
                </div>
                <hr>
                @if($type == 1)
                    @if(isset($applications))
                        @if(count($applications) >= 1)
                            <table width="100%" id="pdfview">
                                <thead>
                                    <tr style="background-color:#bdcbc3;">
                                        <th style="font-size:12px;" width="5%">#</th>
                                        <th style="font-size:12px;" width="7%">Code</th>
                                        <th style="font-size:12px;">Name</th>
                                        <th style="font-size:12px;" width="18%">Department</th>
                                        <th style="font-size:12px;" width="18%">Designation</th>
                                        <th style="font-size:12px;" width="10%">Total</th>
                                        <th style="font-size:12px;" width="10%">Reporting</th>
                                        <th style="font-size:12px;" width="7%">HR</th>
                                        <th style="font-size:12px;" width="10%">Accounts<?php $i=1; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $app) 
                                        <tr>
                                            <td style="font-size:12px;">{{$i++}}</td>
                                            <td style="font-size:12px;">{{$app['empCode']}}</td>
                                            <td style="font-size:12px;">{{$app['empName']}}</td>
                                            <td style="font-size:12px;">{{$app['departmentName']}}</td>
                                            <td style="font-size:12px;">{{$app['designationName']}}</td>
                                            <td style="font-size:12px;">{{$app['totalCt']}}</td>
                                            <td style="font-size:12px;">{{$app['reportAuthCt']}}</td>
                                            <td style="font-size:12px;">{{$app['hrPendingCt']}}</td>
                                            <td style="font-size:12px;">{{$app['accountPendingCt']}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>                
                            </table>
                        @endif
                    @endif
                @else
                    @if(isset($applications))
                        @if(count($applications) >= 1)
                            <table width="100%" id="pdfview">
                                <thead>
                                    <tr style="background-color:#bdcbc3;">
                                        <th style="font-size:12px;" width="4%">#</th>
                                        <th style="font-size:12px;" width="10%">Day</th>
                                        <th style="font-size:12px;" width="5%">Code</th>
                                        <th style="font-size:12px;" width="10%">Name</th>
                                        <th style="font-size:12px;" width="5%">Branch</th>
                                        <th style="font-size:12px;" width="5%">Department</th>
                                        <th style="font-size:12px;" width="5%">Designation</th>
                                        <th style="font-size:12px;" width="7%">Issue in Brief</th>
                                        <th style="font-size:12px;">Description</th>
                                        <th style="font-size:12px;" width="10%">Reporting Authority</th>
                                        <th style="font-size:12px;" width="10%">HR Dept.</th>
                                        <th style="font-size:12px;" width="10%">Accounts Dept.<?php $i=1;?></th>
                                        <th style="font-size:12px;" width="10%">Reason<?php $i=1; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $app) 
                                        <tr>
                                            <td style="font-size:12px;">{{$i++}}</td>
                                            <td style="font-size:12px;">{{date('d-m-Y', strtotime($app->startDate))}}<br>
                                            <hr>{{date('d-m-Y h:i A', strtotime($app->created_at))}}</td>
                                            <td style="font-size:12px;">{{$app->empCode}}</td>
                                            <td style="font-size:12px;">{{$app->empName}}</td>
                                            <td style="font-size:12px;">{{$app->branchName}}</td>
                                            <td style="font-size:12px;">{{$app->departmentName}}</td>
                                            <td style="font-size:12px;">{{$app->designationName}}</td>
                                            <td style="font-size:12px;">{{$app->reason}}</td>
                                            <td style="font-size:12px;">{{$app->description}}</td>
                                            <td style="font-size:12px;">{{($app->status1 == 0)?'Pending':(($app->status1 == 1)?'Approved':'Rejected')}}<br>{{($app->updatedAt1 != null)?date('d-m-Y h:i A', strtotime($app->updatedAt1)):'-'}}</td>
                                            <td style="font-size:12px;">{{($app->status2 == 0)?'Pending':(($app->status2 == 1)?'Approved':'Rejected')}}<br>{{($app->updatedAt2 != null)?date('d-m-Y h:i A', strtotime($app->updatedAt2)):'-'}}</td>
                                            <td style="font-size:12px;">{{($app->status == 0)?'Pending':(($app->status == 1)?'Approved':'Rejected')}}<br>{{($app->updatedAt3 != null)?date('d-m-Y h:i A', strtotime($app->updatedAt3)):'-'}}</td>
                                            <td style="font-size:12px;">{{$app->rejectReason}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>                
                            </table>
                        @endif
                    @endif
                @endif
            </div>
        </div>
    </body>
</html>