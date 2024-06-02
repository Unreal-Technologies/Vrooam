<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('products.index', [
            'products' => Product::all()
        ]);
    }

    /**
     * @return View
     */
    public function editlist(): View
    {
        return view('products.editlist', [
            'products' => Product::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('products.entry', [
            'title' => 'Toevoegen',
            'route' => 'products.store',
            'method' => 'post',
            'param' => [],
            'description' => old('description'),
            'code' => old('code'),
            'price' => old('price'),
            'text' => old('text')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required',
            'description' => 'required',
            'price' => 'required|numeric|gt:0',
            'text' => 'required'
        ]);
        $validated['code'] = strtoupper($validated['code']);

        $product = Product::fromCode($validated['code']);
        if ($product !== null) {
            $message = 'Product met code "' . $validated['code'] . '" bestaat al.';
            throw ValidationException::withMessages([
                'code' => $message
            ]);
        }

        Product::factory()->create($validated);
        return redirect(route('products.editlist'));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $product = Product::fromId($id);

        return view('products.entry', [
            'title' => 'Bewerken',
            'route' => 'products.update',
            'method' => 'patch',
            'param' => [ 'product' => $product->id ],
            'description' => old('description') ?? $product->description,
            'code' => old('code') ?? $product->code,
            'price' => old('price') ?? $product->price,
            'text' => old('text') ?? $product->text
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'code' => 'required',
            'description' => 'required',
            'price' => 'required|numeric|gt:0',
            'text' => 'required'
        ]);
        $validated['code'] = strtoupper($validated['code']);

        $product = Product::fromId($id);
        $testProduct = Product::fromCode($validated['code']);
        if ($testProduct !== null && $testProduct->id !== $product->id) {
            $message = 'Product met code "' . $validated['code'] . '" bestaat al.';
            throw ValidationException::withMessages([
                'code' => $message
            ]);
        }

        $product->update($validated);
        $product->save();

        return redirect(route('products.editlist'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $product = Product::fromId($id);
        $product->delete();

        return redirect(route('products.editlist'));
    }
}
