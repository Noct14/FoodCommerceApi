<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OptionGroup;
use App\Models\Product;

class OptionGroupController extends Controller
{
    public function createOptionGroup(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string',
            'is_required' => 'boolean',
            'is_multiple' => 'boolean',
        ]);

        $optionGroup = OptionGroup::create($data);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan',
            'data' => $optionGroup,
        ], 201);
    }

    public function index($groupId)
    {
        $optionGroup = OptionGroup::with(['options', 'product'])->find($groupId);

        if (!$optionGroup) {
            return response()->json(['message' => 'option tidak ada'], 500);
        }

        return response()->json([
            'id_option_group' => $optionGroup->id,
            'product_id' => $optionGroup->product_id,
            'product_name' => $optionGroup->product->product_name ?? null,
            'option_name' => $optionGroup->name,
            'is_required' => $optionGroup->is_required,
            'is_multiple' => $optionGroup->is_multiple,
            'options' => $optionGroup->options,
        ]);
    }

    public function update(Request $request, $groupId)
    {
        $data = $request->validate([
            'name' => 'string',
            'is_required' => 'boolean',
            'is_multiple' => 'boolean',
            'product_id' => 'exists:products,id',
        ]);

        $optionGroup = OptionGroup::find($groupId);

        if (!$optionGroup) {
            return response()->json(['message' => 'Option group tidak ditemukan'], 404);
        }

        $updated = $optionGroup->update($data);

        if (!$updated) {
            return response()->json(['message' => 'Gagal update option group'], 500);
        }

        return response()->json([
            'message' => 'Option group berhasil diupdate',
            'data' => $optionGroup,
        ]);
    }

    public function destroy($groupId)
    {
        $optionGroup = OptionGroup::find($groupId);

        if (!$optionGroup) {
            return response()->json([
                'message' => 'Option group tidak ditemukan'
            ], 404);
        }

        if (!$optionGroup->delete()) {
            return response()->json([
                'message' => 'Gagal menghapus option group'
            ], 500);
        }

        return response()->json([
            'message' => 'Option group berhasil dihapus'
        ]);
    }

}
