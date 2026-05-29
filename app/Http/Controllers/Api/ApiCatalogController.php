<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ApiCatalogController extends Controller
{
    /**
     * Browse available products with dynamic filtering.
     */
    public function index(Request $request)
    {
        $query = Product::with('occasions');

        // Filter by category
        if ($request->has('category') && $request->category !== 'all' && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        // Filter by occasion slug or name
        if ($request->has('occasion') && !empty($request->occasion)) {
            $query->whereHas('occasions', function ($q) use ($request) {
                $q->where('slug', $request->occasion)
                  ->orWhere('name', 'like', '%' . $request->occasion . '%');
            });
        }

        // Search in name, sku, or description
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('sku', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Paginate results
        $products = $query->paginate(15);

        return response()->json($products);
    }

    /**
     * Get detailed specs for a single product.
     */
    public function show($id)
    {
        $product = Product::with('occasions')->find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.',
            ], 404);
        }

        return response()->json($product);
    }
}
