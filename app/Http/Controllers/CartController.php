<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        if($product === null) //If product not found, throw error
        {
            throw new \Exception('Product not found', 404);
        }
        
        $cart = Cart::where([
            ['user_id', '=', $user->id],
            ['product_id', '=', $product->id]
        ])->first(); //Check if current item is already in cart, of so, update amount

        if ($cart === null) {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'amount' => $validated['amount']
            ]);
        } else {
            $cart->amount += $validated['amount'];
            $cart->save();
        }

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
