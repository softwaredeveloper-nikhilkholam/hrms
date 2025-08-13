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
        <img src="{{ public_path('admin/letterHeads/Ellora Medical (ASCON).jpg') }}" style="width: 100%; height: 250px">
        <div class="row">
            <?php
                use App\Helpers\Utility;
                $util=new Utility(); 
            ?>
          
            <div style="text-align: center">
                <b style="font-size:20px;">
                    EXPERIENCE CERTIFICATE
                </b> <br><br>
            </div>
            <div style="text-align: center;margin-top:15px;">
                <b style="font-size:16px;">
                    TO WHOMSOEVER IT MAY CONCERN
                </b> <br><br>
            </div>
            <table width="100%" id="pdfview">
                <tbody>
                    <tr>
                        <td>
                            
                            <div class="row">
                                <div class="col-md-12" style="margin-top:50px;margin-left:20px;">
                                    This is to certify that Mr. /Mrs. /Ms. <b>{{$empDet->name}}</b> is working with <b>{{$empDet->organisationName}}</b>, "AARYANS WORLD SCHOOL" as <b>{{$empDet->designationName}}</b> from <b>{{date('d-M-Y', strtotime($empDet->jobJoingDate))}} {{($empDet->lastDate != '')?('To '.date('d M Y', strtotime($empDet->lastDate))):'till date'}}</b>. {{($empDet->gender == 'Male')?'He':'She'}} is a sincere, honest employee & a resourceful asset to the organization.
                                </div>
                            </div>
                            @if($empDet->active != 1)
                                <div class="row">
                                    <div class="col-md-12" style="margin-top:10px;margin-left:20px;">
                                        {{$comment}}
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-12" style="margin-top:10px;margin-left:20px;">
                                    This certificate is issued on {{($empDet->gender == 'Male')?'his':'her'}} own written request.
                                </div>
                            </div>
                           
                        </td>
                    </tr>
                </tbody>
            </table>
            <br><br><br><br>
            <table>
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-md-12" style="margin-top:55px;margin-left:20px;">
                                @if($signDetials)
                                    <div class="section" style="margin-top: 20px;">
                                        <div><b>Thanking you</b></div>
                                        <img src="{{ public_path('admin/signFiles/' . $signDetials->fileName) }}"
                                            style="width: 180px; height: 90px;">
                                        <div><b>{{ $signDetials->name }}</b></div>
                                        <div><b>{{ $signDetials->designationName }}</b></div>
                                        <div><b>Aaryans World School</b></div>
                                    </div>
                                @endif
                            </div> 
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>