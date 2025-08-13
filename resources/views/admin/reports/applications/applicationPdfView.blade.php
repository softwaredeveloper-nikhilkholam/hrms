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
            margin-top:5px;
 
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
            padding: 3px;
            text-align:center;
        }
        
        th {
            background-color: #dddddd;
            color: #000;
            text-align:center;
        }
        .center {
            margin-left: 2px;
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
                                <th colspan="4"><center>
                                    @if($appType ==4)
                                        Travelling Allowance Applications
                                    @elseif($appType ==1)
                                        AGF Applications
                                    @elseif($appType ==2)
                                        Exit Pass Applications
                                    @else
                                        Leave Applications
                                    @endif
                                </center></th>
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
                                            @elseif($empDet->firmType == 3)
                                                AFS{{$empDet->empCode}}
                                            @else
                                                ADF{{$empDet->empCode}}
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
                @if($appType == 1)
                    @if(isset($applications))
                        <table>
                            <tbody>
                                <tr>
                                    <th  style="font-size:12px;"  width="5%">#</th>
                                    <th  style="font-size:12px;"  width="13%">Date</th>
                                    <th  style="font-size:12px;"  width="10%">Issue</th>
                                    <th  style="font-size:12px;" >Description</th>
                                    <th  style="font-size:12px;"  width="12%">Reporting Authority</th>
                                    <th  style="font-size:12px;"  width="12%">HR Dept.</th>
                                    <th  style="font-size:12px;"  width="12%">Accounts Dept.<?php $i=1;?></th>
                                    <th  style="font-size:12px;"  width="12%">Reason</th>
                                </tr>
                                @foreach($applications as $row)
                                    <tr style="font-size:10px;">
                                        <td>{{$i++}}</td>
                                        <td>{{date('d-M', strtotime($row->startDate))}} <b style="color:Red;">{{date('D', strtotime($row->startDate))}}</b><br>
                                            [{{date('d-M h:i A', strtotime($row->created_at))}}]
                                        </td>
                                        <td>{{$row->reason}}</td>
                                        <td>{{$row->description}}</td>
                                        <td><b style="color:{{($row->status1 == 0)?'purple':(($row->status1 == 1)?'green':'red')}}">{{($row->status1 == 0)?'Pending':(($row->status1 == 1)?'Approved':'Rejected')}}</b><br>
                                            {{($row->approvedBy1 == '')?'':$row->approvedBy1}}<br>
                                            {{($row->updatedAt1 == '')?'-':(date('d-M h:i A', strtotime($row->updatedAt1)))}}
                                        </td>
                                        <td><b style="color:{{($row->status2 == 0)?'purple':(($row->status2 == 1)?'green':'red')}}">{{($row->status2 == 0)?'Pending':(($row->status2 == 1)?'Approved':'Rejected')}}</b><br>
                                            {{($row->approvedBy2 == '')?'':$row->approvedBy2}}<br>
                                            {{($row->updatedAt2 == '')?'-':(date('d-M h:i A', strtotime($row->updatedAt2)))}}
                                        </td>

                                        <td><b style="color:{{($row->status == 0)?'purple':(($row->status == 1)?'green':'red')}}">{{($row->status == 0)?'Pending':(($row->status == 1)?'Approved':'Rejected')}}</b><br>
                                            {{($row->approvedBy3 == '')?'':$row->approvedBy3}}<br>
                                            {{($row->updatedAt3 == '')?'-':(date('d-M h:i A', strtotime($row->updatedAt3)))}}
                                        </td>
                                        <td>{{($row->rejectReason == '')?'-':$row->rejectReason}}</td>
                                    </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    @endif
                @endif

                @if($appType == 2)
                    @if(isset($applications))
                        <table>
                            <tbody>
                                <tr>
                                    <td style="font-size:12px;" width="5%"><b>Sr.No.</b></td>
                                    <td style="font-size:12px;" width="10%"><b>Date</b></td>
                                    <td style="font-size:12px;" width="20%"><b>Reason</b></td>
                                    <td style="font-size:12px;" width="25%"><b>Description </b></td>
                                    <td style="font-size:12px;" width="8%"><b>Time Out </b></td>
                                    <td style="font-size:12px;" width="8%"><b>Time In</b></td>
                                    <td style="font-size:12px;" width="10%"><b>Status</b></td>
                                    <td style="font-size:12px;" width="14%"><b>Updated By<?php $i=1;?></b></td>
                                </tr>
                                @foreach($applications as $row)
                                    <tr style="font-size:10px;">
                                        <td>{{$i++}}</td>
                                        <td>{{date('d-M', strtotime($row->startDate))}}</td>
                                        <td>{{$row->reason}}</td>
                                        <td>{{$row->description}}</td>
                                        <td>{{$row->timeout}}</td>
                                        <td>{{$row->timein}}</td>
                                        <td style="color:{{($row->status == 0)?'orange':(($row->status == 1)?'green':'Red')}};"><b>{{($row->status == 0)?'Pending':(($row->status == 1)?'Approved':'Rejected')}}</b></td>
                                        <td>{{$row->updated_by}}</td>
                                    </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    @endif
                @endif
                
                @if($appType == 3)
                    @if(isset($applications))
                        <table>
                            <tbody>
                                <tr>
                                    <td style="font-size:12px;" width="5%"><b>Sr.No.</b></td>
                                    <td style="font-size:12px;" width="10%"><b>Date</b></td>
                                    <td style="font-size:12px;" width="15%"><b>Reason</b></td>
                                    <td style="font-size:12px;" width="28%"><b>Description </b></td>
                                    <td style="font-size:12px;" width="10%"><b>From</b></td>
                                    <td style="font-size:12px;" width="10%"><b>To</b></td>
                                    <td style="font-size:12px;" width="12%"><b>Leave Type</b></td>
                                    <td style="font-size:12px;" width="10%"><b>Status<?php $i=1;?></b></td>
                                </tr>
                                @foreach($applications as $row)
                                    <tr style="font-size:10px;">
                                        <td>{{$i++}}</td>
                                        <td>{{date('d-M', strtotime($row->startDate))}}</td>
                                        <td>{{$row->reason}}</td>
                                        <td>{{$row->description}}</td>
                                        <td>{{date('d-m-y', strtotime($row->startDate))}}</td>
                                        <td>{{date('d-m-y', strtotime($row->endDate))}}</td>
                                        <td>{{($row->leaveType == '1')?'Full Day Leave':(($row->leaveType == '2')?'Half Day Leave( 1st Half )':'Half Day Leave( 2nd Half )')}}</td>
                                        <td style="color:{{($row->status == 0)?'orange':(($row->status == 1)?'green':'Red')}};"><b>{{($row->status == 0)?'Pending':(($row->status == 1)?'Approved':'Rejected')}}</b></td>
                                    </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    @endif
                @endif

                @if($appType == 4)
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
                                    <td style="font-size:12px;" width="8%"><b>Reporting</b></td>
                                    <td style="font-size:12px;" width="8%"><b>HR</b></td>
                                    <td style="font-size:12px;" width="8%"><b>Accounts</b></td>
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
                                        <td>{{$amt = $km*$perKmRate}}/-
                                            <?php $totRs += $amt;?>
                                        </td>
                                        <td>{{($row->appRep == '' || $row->appRep == '1')?'Approved':(($row->appRep == '0')?'Pending':'Rejected')}}</td>
                                        <td>{{($row->appHr == '' || $row->appHr == '1')?'Approved':(($row->appHr == '0')?'Pending':'Rejected')}}</td>
                                        <td>{{($row->status == '' || $row->status == '1')?'Approved':(($row->status == '0')?'Pending':'Rejected')}}</td>
                                    </tr>
                                @endforeach 
                                    <tr style="font-size:12px;">
                                        <td colspan="5"><center>Total</center></td>
                                        <td>{{$util->numberFormatRound($totKM)}}</td>
                                        <td>{{$util->numberFormatRound($totRs)}}/-</td>
                                        <td colspan="3"></td>
                                    </tr>            
                            </tbody>
                        </table><br>
                        @if($empId == $empDet->id || $userType == '61')
                            @if($payment)
                                <table width="100%">
                                    <tbody>
                                        <tr style="font-size:15px;">
                                            <td colspan="3"><center><b>Account Department</b></center></td>
                                        </tr>  
                                        <tr style="font-size:12px;">
                                            <th width="60%">Remark</th>
                                            <th width="20%">Updated By</th>
                                            <th width="20%">Updated At</th>
                                        </tr>   
                                        <tr style="font-size:12px;">
                                            <td width="60%">{{$payment->remark}}</td>
                                            <td width="20%">{{$payment->updated_by}}</td>
                                            <td width="20%">{{date('d-m-Y H:i', strtotime($payment->updated_at))}}</td>
                                        </tr>
                                    <tbody>
                                </table>
                            @endif
                        @endif
                    @endif
                @endif
            </div>
        </div>
    </body>
</html>