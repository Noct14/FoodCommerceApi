<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function sellerOrderHistory()
    {
        $seller = auth()->user();

        $orderItems = OrderItem::with(['order.user', 'product'])
            ->whereHas('product', function ($q) use ($seller) {
                $q->where('seller_id', $seller->id);
            })
            ->orderByDesc('created_at')
            ->get();

        $data = $orderItems->map(function ($item) {
            return [
                'order_id' => $item->order_id,
                'product_name' => $item->product->product_name ?? '-',
                'buyer_name' => $item->order->user->name ?? '-',
                'quantity' => $item->quantity,
                'note' => $item->note,
                'price' => $item->price,
                'status' => $item->order->status,
                'ordered_at' => $item->created_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'message' => 'History order sebagai seller',
            'data' => $data
        ]);
    }
}
