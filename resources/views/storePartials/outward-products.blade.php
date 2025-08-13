@if(count($products))
    <ul style="margin-bottom: 0;">
        @foreach($products as $product)
            <li>{{ $product->name }} — Qty: {{ $product->quantity }} — ₹{{ $product->price }}</li>
        @endforeach
    </ul>
@else
    <p>No products found for this entry.</p>
@endif