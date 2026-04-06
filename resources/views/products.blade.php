@foreach($products as $product)
    <p>Product Name: {{ $product->product_name }}</p>
    <p>Price: {{ $product->price }}</p>
    <p>Stock: {{ $product->stock }}</p>
    <hr>
@endforeach