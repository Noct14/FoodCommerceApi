<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Validation\ValidationException;

class OptionController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'option_group_id' => 'required|exists:option_groups,id',
                'name' => 'required|string',
                'additional_price' => 'integer',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        }

        $option = Option::create($data);

        return response()->json([
            'message' => 'Option berhasil ditambahkan',
            'data' => $option,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $option = Option::find($id);

        if (!$option) {
            return response()->json(['massage' => 'id option tidak di temukan']);
        }

        $data = $request->validate([
            'name' => 'sometimes|string|nullable',
            'additional_price' => 'sometimes|integer|nullable',
        ]);

        $option->update($data);

        return response()->json([
            'message' => 'Option berhasil diupdate',
            'data' => $option,
        ], 200);

    }

    public function destroy(string $id)
    {
        $option = Option::find($id);

        if (!$option) {
            return response()->json(['massage' => 'id option tidak di temukan']);
        }

        $option->delete();
        return response()->json(['message' => 'Produk berhasil dihapus'], 200);
    }

}
