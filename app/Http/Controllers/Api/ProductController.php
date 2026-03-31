<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
{
    // Mulai query
    $query = Product::with('category');

    // Jika ada pencarian nama (?name=sepatu)
    if ($request->has('name')) {
        $query->where('name', 'like', '%' . $request->name . '%');
    }

    // Pagination (5 data per halaman)
    $products = $query->latest()->paginate(5);

    return response()->json($products, 200);
}


    public function store(Request $request)
{
    // ... Validasi sebelumnya ...
    $validator = Validator::make($request->all(), [
        'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Tambahan validasi gambar
        // ... validasi lain ...
    ]);

    // Proses Upload
    $image = $request->file('image');
    $image->storeAs('public/products', $image->hashName());

    // Simpan ke DB
    $product = Product::create([
        'image' => $image->hashName(), // Simpan nama filenya saja
        'category_id' => $request->category_id,
        'name' => $request->name,
        'price' => $request->price,
        'stock' => $request->stock,
        'description' => $request->description
    ]);

    return response()->json(['data' => $product], 201);
}

    
    // ... (Implementasikan Show, Update, Destroy mirip dengan CategoryController)
}

