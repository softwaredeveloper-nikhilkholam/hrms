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
            font-family: gargi, dejvu sans, Times New Roman;
        }
        
        table,
        td,
        th {
            border: 0px solid #ddd;
            text-align: left;
        }
        
        table {
            border-collapse: collapse;
            width: 100%;
        }
        
        th,
        td {
            border: 0px solid #ddd;
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
            
            <div style="text-align: right">
                <b style="font-size:10px;">
                    Date: {{date('d-m-Y')}}
                </b> <br><br>
            </div>

            <div style="text-align: center">
                <b style="font-size:20px;">
                    PROMOTION LETTER
                </b> <br><br>
            </div>
           
            <table width="100%" id="pdfview">
                <tbody>
                    <tr>
                        <td>
                            
                            <div class="row">
                                <div class="col-md-12" style="margin-top:50px;margin-left:20px;">
                                    Dear {{($empDet->gender == 'Male')?'Mr.':'Mrs. /Miss.'}} <b>{{$empDet->name}}</b><br>
                                    Emp Code - <b>{{($empDet->firmType == 1)?'AWS':(($empDet->firmType == 1)?'AFF':'AFS')}} {{$empDet->empCode}}</b>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="margin-top:30px;margin-left:20px;">
                                    Congratulations!
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="margin-top:30px;margin-left:20px;">
                                We are pleased to inform you that you have been promoted from <b>{{$empDet->designationName}}</b> to <b>{{$newDepartment}} ( {{$newDesignation}} )</b> w.e.f. <b>{{date('d-m-Y', strtotime($forDate))}}</b>.
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="margin-top:30px;margin-left:20px;">
                                    Your work profile will be handed over to you soon and you will report to Head of the Department.
                                </div>
                            </div>
                            @if($revSalary != 0 || $revSalary != '')
                                <div class="row">
                                    <div class="col-md-12" style="margin-top:30px;margin-left:20px;">
                                        Your salary is revised from <b>Rs. {{$util->numberFormatRound($empDet->salaryScale)}}</b> to <b>Rs.{{$util->numberFormatRound($revSalary)}}</b>.
                                    </div>
                                </div>   
                            @endif      
                            <div class="row">
                                <div class="col-md-12" style="margin-top:30px;margin-left:20px;">
                                    All the other terms &amp; conditions of your service will remain unchanged.
                                </div>
                            </div>                      

                            <div class="row">
                                <div class="col-md-12" style="margin-top:30px;margin-left:20px;">
                                    Wishing you all the best for your new role.
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12" style="margin-top:50px;margin-left:20px;">
                                    Warm Regards,
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <table>
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-md-12" style="margin-top:55px;margin-left:20px;">
                                @if($signBy)
                                    @if($signBy->fileName != '' )
                                        <div class="row">
                                            <div class="col-md-12" style="margin-top:40px;margin-left:20px;">
                                                <img src="{{ public_path('admin/signFiles/').$signBy->fileName}}" style="width: 180px; height: 70px"> 
                                            </div> 
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-12" style="margin-left:0px;">
                                            <b>{{$signBy->name}}</b>
                                        </div> 
                                    </div>
                                @endif    
                                <b>Aaryans World School</b>
                            </div> 
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>