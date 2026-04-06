<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function showProducts() {
    $products = Product::all();
    return view('products', ['products' => $products]);
}
public function addProducts() {
    Product::create([
        'product_name' => 'Burger',
        'price' => 120,
        'stock' => 50,
    ]);

    $products = Product::all();
    return view('products', ['products' => $products]);
}

}