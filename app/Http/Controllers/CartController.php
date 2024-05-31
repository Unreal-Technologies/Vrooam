<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\DTO\CartItemDto;
use App\Models\User;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = User::where('id', '=', auth()->id())->first();
        if ($user === null) { //If product not found, throw error
            throw new \Exception('User not found', 404);
        }
        $dtoItems = CartItemDto::fromCart($user->cart());

        $sum = 0;
        foreach ($dtoItems as $item) {
            $sum += $item->price();
        }

        return view('cart.index', [
            'items' => $dtoItems,
            'sum' => $sum
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id' => 'required',
            'amount' => 'required'
        ]);

        $user = $request->user();

        $product = Product::where('id', '=', $validated['id'])->first();
        if ($product === null) { //If product not found, throw error
            throw new \Exception('Product not found', 404);
        }

        $cart = $user->cart();
        $cart->addOrUpdateProduct($product, $validated['amount']);

        return redirect(route('cart.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
