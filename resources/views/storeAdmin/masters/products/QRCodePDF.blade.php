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
                padding: 8px;
            }
            
            table {
                border-collapse: collapse;
                width: 100%;
                padding: 8px;
            }
            
            th,
            td {
                border: 1px solid #ddd;
                padding: 8px;
                font-size:14px;
                
            }
            
            th {
                background-color: #dddddd;
                color: #000;
                padding: 8px;
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
                    $userType = Auth::user()->userType;
                    $i=1;
                    $temp='';
                ?>
                <table>
                    @if($size == '50')
                        @foreach($productList as $list)
                            @if($i==1)
                                <tr>
                            @endif
                                <td><img src="data:image/png;base64, {!! base64_encode(\QrCode::size(50)->generate('https://hrms.aaryansworld.com/product/printProductQR/'.$list->id)) !!} "><br><b style="font-size:8px;">{{$list->name}}</b></td>
                            @if($i==10)
                                </tr>
                                <?php $i=1;?>
                            @else
                                <?php $i++; ?>
                            @endif
                        @endforeach
                    @elseif($size == '75')
                        @foreach($productList as $list)
                            @if($i==1)
                                    <tr>
                                @endif
                                    <td><img src="data:image/png;base64, {!! base64_encode(\QrCode::size(75)->generate('https://hrms.aaryansworld.com/products/printProductQR/'.$list->id)) !!} "><br><b style="font-size:8px;">{{$list->name}}</b></td>
                                @if($i==7)
                                    </tr>
                                    <?php $i=1;?>
                                @else
                                    <?php $i++; ?>
                                @endif
                            @endforeach
                    @elseif($size == '100')
                        @foreach($productList as $list)
                            @if($i==1)
                                    <tr>
                                @endif
                                    <td><img src="data:image/png;base64, {!! base64_encode(\QrCode::size(100)->generate('https://hrms.aaryansworld.com/products/printProductQR/'.$list->id)) !!} "><br><b style="font-size:8px;">{{$list->name}}</b></td>
                                @if($i==5)
                                    </tr>
                                    <?php $i=1;?>
                                @else
                                    <?php $i++; ?>
                                @endif
                            @endforeach
                    @endif
                </table>

            </div>
        </div>
    </body>
</html>