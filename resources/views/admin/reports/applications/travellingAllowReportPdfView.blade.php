@php($data = storage_path('fonts/gargi.ttf'))
<?php
    $userType = Auth::user()->userType;  
?>
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
        .center {
            margin-left: 5px;
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
                $perKmRate = Auth::user()->perKmRate;
                $empId = Auth::user()->empId;
            ?>
                
                @if($empDet)
                    <table width="100%">
                        <thead>
                            <tr>
                                <th colspan="4"><center>Travelling Allowance Applications</center></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="10%">
                                    <b style="font-size:12px;">Name</b>
                                </td>
                                <td width="50%">
                                    <b style="font-size:12px;"> 
                                        @if($userType != '61')
                                            @if($empDet->firmType == 1)
                                                {{$empDet->empCode}}
                                            @elseif($empDet->firmType == 2)
                                                AFF{{$empDet->empCode}}
                                            @else
                                                AFS{{$empDet->empCode}}
                                            @endif 
                                        @else
                                            {{$empDet->empCode}}
                                        @endif
                                        - {{$empDet->empName}}
                                    </b>
                                </td>
                                <td width="10%">
                                    <b style="font-size:12px;">Department</b>
                                </td>
                                <td width="30%">
                                    <b style="font-size:12px;"> 
                                        {{$empDet->departmentName}}
                                    </b>
                                </td>
                            </tr>         
                            <tr>
                                <td width="10%">
                                    <b style="font-size:12px;">Period</b>
                                </td>
                                <td width="50%">
                                    <b style="font-size:12px;"> 
                                        {{date('d-M-Y', strtotime($startDate))}} To {{date('d-M-Y', strtotime($endDate))}}
                                    </b>
                                </td>
                                <td width="10%">
                                    <b style="font-size:12px;">Designation</b>
                                </td>
                                <td width="30%">
                                    <b style="font-size:12px;"> 
                                        {{$empDet->designationName}}
                                    </b>
                                </td>
                            </tr>                       
                        </tbody>
                    </table>
                @endif
                <br>
                @if(isset($travells))
                    <table>
                        <tbody>
                            <tr>
                                <td style="font-size:12px;" width="5%"><b>Sr.No.</b></td>
                                <td style="font-size:12px;" width="8%"><b>Date</b></td>
                                <td style="font-size:12px;" width="30%"><b>Reason</b></td>
                                <td style="font-size:12px;" width="12%"><b>From </b></td>
                                <td style="font-size:12px;" width="12%"><b>To</b></td>
                                <td style="font-size:12px;" width="5%"><b>KM<?php $i=1;$totKM=0;$totRs=0;  ?></b></td>
                                <td style="font-size:12px;" width="4%"><b>Rs</b></td>
                                <td style="font-size:12px;" width="8%"><b>Status</b></td>
                                @if($userType == '61')
                                    <td style="font-size:12px;" width="8%"><b>Payment Status</b></td>
                                @endif
                            </tr>
                            @foreach($travells as $row)
                                <tr  style="font-size:10px;">
                                    <td>{{$i++}}<?php $km = (double)str_replace("km","",$row->kms); 
                                        $totKM += $km; ?>
                                    <input type="hidden" value="{{$row->id}}" name="appId[]">  </td>
                                    <td>{{date('d-M', strtotime($row->startDate))}}</td>
                                    <td>{{$row->reason}}</td>
                                    <td>{{$row->fromDest}}</td>
                                    <td>{{$row->toDest}}</td>
                                    <td>{{$row->kms}}</td>
                                    <td>{{$amt = $km*$perKmRate}}
                                        <?php $totRs += $amt;?>
                                    </td>
                                    <td>{{($row->status == 1)?'Approved':(($row->status == '0')?'Pending':'Rejected')}}</td>
                                    @if($userType == '61')
                                        <td>{{($row->paymentStatus == 1)?'Paid':'UnPaid'}}</td>
                                    @endif
                                </tr>
                            @endforeach 
                            <tr style="font-size:12px;">
                                <td colspan="5"><center>Total</center></td>
                                <td>{{$util->numberFormatRound($totKM)}}</td>
                                <td>{{$util->numberFormatRound($totRs)}}</td>
                                <td></td>
                                @if($userType == '61')
                                    <td></td>
                                @endif
                            </tr>            
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </body>
</html>