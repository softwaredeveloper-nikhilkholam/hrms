@php($data = storage_path('fonts/gargi.ttf'))
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <style>
        @font-face {
            font-family: "gargi";
            src: url("{{ $data }}") format("truetype");
        }

        body {
            font-family: "gargi", DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: center;
            vertical-align: middle;
        }

        .header-table td {
            border: none !important;
            font-size: 10px;
        }

        .qr-cell {
            border: 1px solid #000;
            padding: 6px;
        }

        .inner-table {
            border: none;
            margin: auto;
        }

        .inner-table td {
            border: none;
            padding: 2px;
        }

        .product-code {
            font-size: 8px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>

<body>

    <table class="header-table" width="100%">
        <tr>
            <td style="text-align: left;">PDF Generated At: {{ date('d/m/Y h:i A') }}</td>
            <td style="text-align: right;"><strong>Aaryans World</strong></td>
        </tr>
    </table>

    <?php 
        use App\Helpers\Utility;
        $util = new Utility();
        $userType = Auth::user()->userType;
        $i = 1;
        $max = $size == '50' ? 5 : ($size == '75' ? 4 : 3);
        ?>

    <table>
        @foreach($productList as $list)
            <?php $qrCode = 'https://hrms.aaryansworld.com/products/printProductQR/' . $list->id; ?>

            @if($i == 1)
                <tr>
            @endif

            <td class="qr-cell">
                <table class="inner-table">
                    <tr>
                        <td>
                            <img src="{{ public_path('landingpage/images/logo.png') }}" style="height:40px; width:75px;">
                        </td>
                        <td>
                            <img src="data:image/png;base64,{{ base64_encode(QrCode::size(40)->generate($qrCode)) }}">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="product-code">{{ $list->assetProductCode }}</td>
                    </tr>
                </table>
            </td>

            @if($i == $max)
                </tr>
                <?php $i = 1; ?>
            @else
                <?php $i++; ?>
            @endif
        @endforeach

        {{-- Close any unclosed row --}}
        @if($i != 1)
            </tr>
        @endif
    </table>

</body>
</html>
