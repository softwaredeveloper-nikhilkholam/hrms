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
                        @if($applicationType == 0)         
                            Candidate Application List
                        @elseif($applicationType == 1)  
                            Walk-in Candidate List
                        @elseif($applicationType == 2)  
                            Interview Drive Candidate List 
                        @else
                            Recruitement Id's List
                        @endif 
                    </b> <br>
                    <b style="font-size:15px;">
                    @if($status == 'Selected')
                        Selected Candidates
                    @elseif($status == 'CBC')
                        CBC Candidates
                    @elseif($status == 'Selected')
                        Rejected Candidates
                    @else
                        All Candidates
                    @endif
                    </b> <br><br>
                    <b style="font-size:15px;">From: {{date('d-M-Y', strtotime($fromDate))}} To {{date('d-M-Y', strtotime($toDate))}}</b> 
                </div>
                <hr>
                <table width="100%" id="pdfview">
                    <thead>
                        <tr style="background-color:#bdcbc3;">
                            <td style="font-size:12px;" width="5%"><b>#</b></td>
                            <td style="font-size:12px;" width="5%"><b>Candidate Id</b></td>
                            <td style="font-size:12px;" width="15%"><b>Date</b></td>
                            <td style="font-size:12px;" width="22%"><b>Candidate Name</b></td>
                            <td style="font-size:12px;" width="20%"><b>Department</b></td>
                            <td style="font-size:12px;" width="23%"><b>Designation</b></td>
                            <td style="font-size:12px;" width="10%"><b>Status</b><?php $i=1; ?></td>                     
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $row) 
                            <tr>
                                <td style="font-size:12px;">{{$i++}}</td>
                                <td style="font-size:12px;">{{$row->id}}</td>
                                <td style="font-size:12px;">{{date('d-m-Y', strtotime($row->created_at))}}</td>
                                <td style="font-size:12px;">{{$row->firstName}} {{$row->middleName}} {{$row->lastName}}</td>
                                <td style="font-size:12px;">{{$row->departmentName}}</td>
                                <td style="font-size:12px;">{{$row->designationName}}</td>
                                <td style="font-size:12px;">{{($row->appStatus == '')?'Pending':$row->appStatus}}</td>
                            </tr>
                        @endforeach
                    </tbody>                
                </table>
            </div>
        </div>
    </body>
</html>