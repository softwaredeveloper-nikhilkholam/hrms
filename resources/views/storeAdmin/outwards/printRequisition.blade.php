<?php
    use App\Helpers\Utility;
    $util = new Utility();
    $userType = Auth::user()->userType;
    $i = 1;
    $totalQty = 0;
    $total = 0;
    $fontPath = storage_path('fonts/gargi.ttf');
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @font-face {
            font-family: "gargi";
            src: url("<?= $fontPath ?>") format('truetype');
        }

        body {
            font-family: gargi, dejvu sans, sans-serif;
            font-size: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            page-break-inside: auto;
        }

        table, th, td {
            border: 1px solid #ddd;
            text-align: left;
        }

        th, td {
            padding: 6px;
        }

        th {
            background-color: #dddddd;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        .page-break {
            page-break-after: always;
        }

        h5 {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <table>
            <tr>
                <td style="text-align:center; background-color:#D3D3D3;"><h5>REQUISITION</h5></td>
            </tr>
        </table>
        <br>

        <table>
            <tr>
                <th>Requisition Date</th>
                <th>Requisition No.</th>
                <th>Branch Name</th>
                <th>Requisitioner Name</th>
                <th>Department</th>
            </tr>
            <tr>
                <td style="text-align:center;">{{ date('d-m-Y', strtotime($requisition->requisitionDate)) }}</td>
                <td style="text-align:center;">{{ $requisition->requisitionNo }}</td>
                <td style="text-align:center;">{{ $requisition->branchName }}</td>
                <td style="text-align:center;">{{ $requisition->requisitionerName }}</td>
                <td style="text-align:center;">{{ $requisition->departmentName }}</td>
            </tr>
        </table>
        <br>

        <table>
            <tr>
                <th>Date of Requirement</th>
                <th>Severity</th>
                <th>Deliver To</th>
                <th>Authority Name</th>
            </tr>
            <tr>
                <td style="text-align:center;">{{ date('d-m-Y', strtotime($requisition->dateOfRequirement)) }}</td>
                <td style="text-align:center;">
                    {{ $requisition->sevirity == 1 ? 'NORMAL' : ($requisition->sevirity == 2 ? 'URGENT' : 'VERY URGENT') }}
                </td>
                <td style="text-align:center;">{{ $requisition->deliveryTo }}</td>
                <td style="text-align:center;">{{ $requisition->authorityName }}</td>
            </tr>
        </table>
        <br>

        <table>
            <tr>
                <th colspan="5" style="text-align:center;">Event Details / Requisition For</th>
            </tr>
            <tr>
                <td colspan="5" style="text-align:center;">{{ $requisition->requisitionFor }}</td>
            </tr>
        </table>
        <br>

        <table>
            <tr>
                <th colspan="6" style="text-align:center;">Required Products</th>
            </tr>
            <tr>
                <th>No.</th>
                <th>Product</th>
                <th>{{ $userType == '91' ? 'Location' : 'Stock' }}</th>
                <th>Qty</th>
                <th>Rs.</th>
                <th>Status</th>
            </tr>

            @foreach($prodList as $product)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>
                        {{ $product->storeProductName }}
                        @if($userType != '91')
                            <br>Category: {{ $product->categoryName }} |
                            Sub Category: {{ $product->subCategoryName }}<br>
                            Company: {{ $product->company }} |
                            Size: {{ $product->size }} | Color: {{ $product->color }}
                        @endif
                    </td>
                    <td>
                        @if($userType == '91')
                            Hall: {{ $product->hallName }} |
                            Rack: {{ $product->rackName }} |
                            Shelf: {{ $product->shelfName }}
                        @else
                            {{ $util->numberFormat($product->stock) }}
                        @endif
                    </td>
                    <td>
                        {{ $util->numberFormat($product->requiredQty) }} {{ $product->unitName }}
                        <?php $totalQty += $product->requiredQty; ?>
                    </td>
                    <td>
                        {{ $util->numberFormat($product->productRate * $product->requiredQty) }}
                        <?php $total += $product->productRate * $product->requiredQty; ?>
                    </td>
                    <td>
                        @if($userType == '91')
                            {{ $product->status == 0 ? 'Pending' : ($product->status == 1 ? 'Delivered' : ($product->status == 2 ? 'Rejected' : 'Hold')) }}
                        @else
                            @if($product->requiredQty > $product->stock)
                                <span style="color:red;">Insufficient Stock. PO raised to Purchase Dept.</span>
                            @else
                                Sufficient
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach

            <tr>
                <th colspan="3">Total</th>
                <th>{{ $util->numberFormat($totalQty) }}</th>
                <th>Rs. {{ $util->numberFormat($total) }}</th>
                <th></th>
            </tr>
        </table>
    </div>
</body>
</html>
