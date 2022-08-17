<?php

namespace App\Http\Controllers\BalanceSheets;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * class which handles the list / create / update / delete of targets
 */
class ProductController extends Controller
{
    /**
     * target list index
     * @return {view} list view
     */
    public function search(Request $request)
    {
        if (!$request->has('term') || strlen($request->term) < 2) {
            return [];
        }

        $userId = auth()->user()->id;
        $response = Product::select('name')
            ->where('user_id', $userId)
            ->where('name', 'like', $request->term . '%')
            ->limit(8)
            ->get()
            ->pluck('name')
            ->toArray();

        return response()->json($response);
    }
}
