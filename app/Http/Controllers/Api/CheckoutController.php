<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemOption;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->with('items.options', 'items.product')->firstOrFail();

        if ($cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart kosong'], 400);
        }

        $total = 0;
        foreach ($cart->items as $item) {
            $total += $item->calculateSubtotal();
        }

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $total,
            'status' => 'pending'
        ]);

        foreach ($cart->items as $item) {
            $basePrice = $item->product->price;
            $optionPrice = $item->options->sum('additional_price');

            $orderItem = $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $basePrice + $optionPrice,
                'note' => $item->note
            ]);

            if ($item->options->isNotEmpty()) {
                $orderItem->options()->attach($item->options->pluck('id'));
            }
        }

        $cart->items()->delete();

        return response()->json([
            'message' => 'Checkout berhasil',
            'order_id' => $order->id,
            'total' => $total
        ]);
    }

}
