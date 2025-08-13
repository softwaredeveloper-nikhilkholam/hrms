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
            width: 94%;
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
        .center {
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body style="border: 1px solid black;">
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
                <div style="text-align: center">
                    <b style="font-size:15px;">
                        AGF Report
                    </b> <br>
                    <b style="font-size:15px;">{{$branchId}}</b> <br>
                    <b style="font-size:15px;">{{$departmentId}}</b> <br>
                    <b style="font-size:15px;">From: {{date('d-M-Y', strtotime($fromDate))}} To {{date('d-M-Y', strtotime($toDate))}}</b> 
                </div>
                <hr>
                <table width="100%" class="center" id="pdfview">
                    <thead>
                        <tr style="background-color:#bdcbc3;">
                            <td style="font-size:12px;" width="5%"><b>#</b></td>
                            <td style="font-size:12px;" width="15%"><b>Date</b></td>
                            <td style="font-size:12px;" width="10%"><b>Emp Code</b></td>
                            <td style="font-size:12px;" width="30%"><b>Name</b></td>
                            <td style="font-size:12px;" width="20%"><b>Department</b></td>
                            <td style="font-size:12px;" width="20%"><b>Designation</b></td>
                            <td style="font-size:12px;" width="10%"><b>Status</b><?php $i=1; ?></td>    
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app) 
                            <tr>
                                <td style="font-size:12px;">{{$i++}}</td>
                                <td style="font-size:12px;">{{date('d-m-Y', strtotime($app->startDate))}}</td>
                                <td style="font-size:12px;">
                                    {{$app->empCode}}
                                </td>
                                <td style="font-size:12px;">{{$app->empName}}</td>
                                <td style="font-size:12px;">{{$app->departmentName}}</td>
                                <td style="font-size:12px;">{{$app->designationName}}</td>
                                <td style="font-size:12px;"><b>{{($app->status == 0)?'Pending':(($app->status == 1)?'Approved':(($app->status == 2)?'Rejected':''))}}</b></td>
                            </tr>
                        @endforeach
                    </tbody>                
                </table>
            </div>
        </div>
    </body>
</html>