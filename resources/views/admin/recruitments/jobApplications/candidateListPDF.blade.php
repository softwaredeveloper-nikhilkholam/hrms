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
                    <td style="border: 0px solid white !important;"><p style="margin-top:0px;text-align:right;font-size:10px;"><b></b></p></td>
                </tr>
            </table>
            <?php
                use App\Helpers\Utility;
                $util=new Utility(); 
            ?>
                <div style="text-align: center">
                    <b style="font-size:15px;">
                        Appointment List
                    </b> <br>
                </div>
                <hr>
                <table width="100%" id="pdfview">
                    <thead>
                        <tr style="background-color:#bdcbc3;">
                            <td style="font-size:12px;" width="5%"><b>#</b></td>
                            <td style="font-size:12px;" width="15%"><b>Date</b></td>
                            <!-- <td style="font-size:12px;" width="7%"><b>Priority</b></td> -->
                            <td style="font-size:12px;" width="20%"><b>Employee</b></td>
                            <td style="font-size:12px;" width="17%"><b>Department</b></td>
                            <td style="font-size:12px;" width="35%"><b>Agenda</b></td>
                            <td style="font-size:12px;" width="8%"><b>Status<?php $i=1; ?></b></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $row) 
                            <tr>
                                <td style="font-size:12px;">{{$i++}}</td>
                                <td style="font-size:12px;">{{date('d-m-y', strtotime($row->forDate))." ".date('H:i', strtotime($row->forTime))}}</td>
                                <!-- <td style="font-size:12px;">{{$row->priority}}</td> -->
                                <td style="font-size:12px;">{{$row->name}}</td>
                                <td style="font-size:12px;">{{$row->departmentName}}</td>
                                <td style="font-size:12px;">{{$row->reason}}</td>
                                <td style="font-size:12px;">{{($row->status == '1')?"Pending":(($row->status == '2')?"Approved":(($row->status == '3')?"Postpone":"Rejected"))}}</td>
                            </tr>
                        @endforeach
                    </tbody>                
                </table>
            </div>
        </div>
    </body>
</html>