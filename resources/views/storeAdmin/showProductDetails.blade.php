<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            max-width: 800px;
            margin: 30px auto;
            border-left: 4px solid #007BFF;
            border-right: 4px solid #007BFF;
            background: #fff;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .product-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }
        .product-image img {
            width: 150px;
            height: auto;
            border: 1px solid #ccc;
            padding: 5px;
        }
        .product-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .product-info td {
            padding: 6px 12px;
            text-align: left;
        }
        
        .label {
            font-weight: bold;
            width: 130px;
            white-space: nowrap;
        }
        .product-qr {
            text-align: center;
            margin-top: 20px;
        }
        .product-qr img {
            width: 120px;
            height: auto;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <h2>Product Details</h2>

    <div class="product-container">
        <!-- Product Image -->
        <div class="product-image">
            <img src="{{ asset('/storeAdmin/productImages/' . $product->image) }}" alt="Product Image">
        </div>

        <!-- Product Info -->
        <div class="product-info">
            <table>
                <tr>
                    <td class="label">Name:</td>
                    <td>{{ $product->name }}</td>
                </tr>
                <tr>
                    <td class="label">Product Code:</td>
                    <td>{{ $product->assetProductCode }}</td>
                </tr>
                <tr>
                    <td class="label">Branch:</td>
                    <td>{{ $product->branch->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Department:</td>
                    <td>{{ $product->department->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Created Date:</td>
                    <td>{{ $product->created_at->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Description:</td>
                    <td>{{ $product->description }}</td>
                </tr>
            </table>
        </div>

        
    </div>
</div>

</body>
</html>
