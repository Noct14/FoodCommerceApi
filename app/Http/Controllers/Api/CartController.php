<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartController extends Controller
{

    public function getCart()
    {
        $user = Auth::user();
        $cart = $user->cart()->with('items.product', 'items.options')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'message' => 'cart kosong',
                'data' => []
            ]);
        }

        $data = $cart->items->map(function ($item) {
            return [
                'id' => $item->id,
                'quantity' => $item->quantity,
                'note' => $item->note,
                'product_name' => $item->product->product_name ?? null,
                'price' => $item->product->price ?? null,
                'options' => $item->options->map(function ($opt) {
                    return [
                        'id' => $opt->id,
                        'name' => $opt->name,
                        'additional_price' => $opt->additional_price,
                    ];
                })
            ];
        });

        return response()->json([
            'message' => 'Berhasil ambil data cart',
            'data' => $data
        ]);
    }

    public function addToCart(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'options' => 'array',
            'options.*' => 'exists:options,id',
            'note' => 'nullable|string',
        ]);

        $user = auth()->user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        $cartItem = $cart->items()->create([
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
            'note' => $data['note'] ?? null,
        ]);

        if (!empty($data['options'])) {
            $cartItem->options()->attach($data['options']);
        }

        return response()->json([
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'data' => $cartItem->load('options')
        ]);
    }

    public function updateCartItem(Request $request, $id)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string',
            'options' => 'array',
            'options.*' => 'exists:options,id',
        ]);

        $user = auth()->user();

        $cartItem = CartItem::whereHas('cart', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('id', $id)->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Item tidak ditemukan atau bukan milik user']);
        }

        $cartItem->update([
            'quantity' => $data['quantity'],
            'note' => $data['note'] ?? $cartItem->note,
        ]);

        if (isset($data['options'])) {
            $cartItem->options()->sync($data['options']);
        }

        return response()->json([
            'message' => 'Item cart berhasil diupdate',
            'data' => $cartItem->load('options')
        ]);
    }

    public function removeFromCart($id)
    {
        $user = auth()->user();

        $cartItem = CartItem::whereHas('cart', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->find($id);

        if (!$cartItem) {
            return response()->json([
                'message' => 'Item tidak ditemukan atau bukan milik user ini.'
            ], 404);
        }

        $cartItem->delete();

        $cart = $user->cart;
        $items = $cart ? $cart->items()->with(['product', 'options'])->get() : collect();

        $data = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'product_name' => $item->product->product_name ?? null,
                'price' => $item->product->price ?? null,
                'quantity' => $item->quantity,
                'note' => $item->note,
                'options' => $item->options->map(function ($opt) {
                    return [
                        'id' => $opt->id,
                        'name' => $opt->name,
                        'additional_price' => $opt->additional_price
                    ];
                })
            ];
        });

        return response()->json([
            'message' => 'Item berhasil dihapus dari cart',
            'data' => $data
        ]);
    }




    public function clearCart()
    {
        $user = auth()->user();
        $cart = $user->cart;

        if ($cart) {
            $cart->items()->delete();
        }

        return response()->json(['message' => 'Cart berhasil dikosongkan']);
    }
}
