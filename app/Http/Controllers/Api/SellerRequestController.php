<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SellerRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SellerRequestController extends Controller
{

    public function apply(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'store_address' => 'required|string',
        ]);

        $user = Auth::user();

        if ($user->role === 'seller') {
            return response()->json([
                'message' => 'Kamu sudah menjadi seller, tidak bisa mengajukan lagi'
            ], 400);
        }

        if (SellerRequest::where('user_id', $user->id)->where('status', 'pending')->exists()) {
            return response()->json([
                'message' => 'Pengajuan sedang diproses'
            ], 400);
        }

        $requestData = SellerRequest::create([
            'user_id' => $user->id,
            'store_name' => $request->store_name,
            'phone' => $request->phone,
            'store_address' => $request->store_address,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Pengajuan berhasil dikirim',
            'data' => $requestData
        ]);
    }



    public function index()
    {
        $requests = SellerRequest::with('user')->latest()->get();

        return response()->json([
            'data' => $requests
        ]);
    }


    public function approve($id)
    {
        $request = SellerRequest::findOrFail($id);

        if ($request->status !== 'pending') {
            return response()->json(['message' => 'Pengajuan sudah diproses'], 400);
        }

        $request->status = 'approved';
        $request->save();

        $user = User::find($request->user_id);
        $user->role = 'seller';
        $user->save();

        return response()->json(['message' => 'Pengajuan disetujui, user sekarang menjadi seller']);
    }


    public function reject($id)
    {
        $request = SellerRequest::findOrFail($id);

        if ($request->status !== 'pending') {
            return response()->json(['message' => 'Pengajuan sudah diproses'], 400);
        }

        $request->status = 'rejected';
        $request->save();

        return response()->json(['message' => 'Pengajuan ditolak']);
    }
}
