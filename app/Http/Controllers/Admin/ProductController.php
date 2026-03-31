<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('category_id')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'     => 'required|exists:product_categories,id',
            'name'            => 'required|string',
            'price'           => 'required|numeric|min:0',
            'daily_profit'    => 'required|numeric|min:0',
            'duration_days'   => 'required|integer|min:1',
            'min_vip_level'   => 'required|integer|min:0',
        ]);

        Product::create([
            'category_id'   => $request->category_id,
            'name'          => $request->name,
            'price'         => $request->price,
            'daily_profit'  => $request->daily_profit,
            'duration_days' => $request->duration_days,
            'total_profit'  => $request->daily_profit * $request->duration_days,
            'min_vip_level' => $request->min_vip_level,
            'is_active'     => 1,
        ]);

        return redirect('/admin/products')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = ProductCategory::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'category_id'   => $request->category_id,
            'name'          => $request->name,
            'price'         => $request->price,
            'daily_profit'  => $request->daily_profit,
            'duration_days' => $request->duration_days,
            'total_profit'  => $request->daily_profit * $request->duration_days,
            'min_vip_level' => $request->min_vip_level,
        ]);

        return redirect('/admin/products')->with('success', 'Produk berhasil diperbarui');
    }

    public function toggle($id)
    {
        $product = Product::findOrFail($id);
        $product->is_active = !$product->is_active;
        $product->save();

        return back()->with('success', 'Status produk diperbarui');
    }
}
