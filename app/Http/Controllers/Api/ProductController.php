<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $query = Product::with('optionGroups.options');;

        if ($search) {
            $query->where('product_name', 'like', '%' . $search . '%');
        }

        $products = $query->paginate($perPage);
        return response()->json($products);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'stock' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $sellerId = auth()->id();

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $imageName);
            $imageUrl = asset('uploads/products/' . $imageName);
        }

        $product = Product::create([
            'seller_id' => $sellerId,
            'product_name' => $validated['product_name'],
            'price' => $validated['price'],
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'stock' => $validated['stock'] ?? null,
            'image_url' => $imageUrl,
        ]);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan',
            'data' => $product,
        ], 201);
    }


    public function productsBySeller($sellerId)
    {
        $products = Product::where('seller_id', $sellerId)->paginate(10);

        return response()->json($products);
    }

    public function show(string $id)
    {
        return Product::findOrFail($id);
    }


    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        if ($product->seller_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'product_name' => 'nullable|string',
            'price' => 'nullable|numeric',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
            'stock' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($product->image_url) {
                $oldImagePath = public_path(parse_url($product->image_url, PHP_URL_PATH));
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $imageName);
            $imageUrl = asset('uploads/products/' . $imageName);

            $validated['image_url'] = $imageUrl;
        }


        $filtered = array_filter($validated, fn($val) => $val !== null && $val !== '');

        $product->update($filtered);

        return response()->json([
            'message' => 'Produk berhasil diperbarui',
            'data' => $product,
        ]);
    }




    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // Validasi Seller
        if ($product->seller_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $product->delete();
        return response()->json(['message' => 'Produk berhasil dihapus'], 200);
    }
}
