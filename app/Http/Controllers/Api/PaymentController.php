<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class PaymentController
{
    public function pay(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string',
        ]);

        $order = Order::find($request->order_id);

        if ($order->is_paid) {
            return response()->json(['message' => 'Order sudah di bayar'], 400);
        }

        $order->payment_method = $request->payment_method;
        $order->is_paid = true;
        $order->paid_at = now();
        $order->pickup_code = strtoupper(Str::random(8));
        $order->status = 'paid';
        $order->save();

        return response()->json([
            'message' => 'Payment successful',
            'order_id' => $order->id,
            'pickup_code' => $order->pickup_code,
            'status' => $order->status
        ]);
    }

    public function verifyPickupCode(Request $request)
    {
        $request->validate([
            'pickup_code' => 'required|string'
        ]);

        $order = Order::where('pickup_code', $request->pickup_code)->first();

        if (!$order) {
            return response()->json(['message' => 'Invalid pickup code'], 404);
        }

        if ($order->status !== 'paid') {
            return response()->json(['message' => 'Order belum dibayar'], 400);
        }

        $order->status = 'completed';
        $order->save();

        return response()->json([
            'message' => 'Pickup verified successfully',
            'order_id' => $order->id,
            'status' => $order->status
        ]);
    }
}
