<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\DTO\CartItemDto;
use App\Models\User;
use App\Models\CartItems;

class CartItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
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
    }

    /**
     * Display the specified resource.
     */
    public function show(CartItems $cartItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CartItems $cartItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $item = CartItems::fromId($id);
        $validated = $request->validate([
            'amount' => 'required'
        ]);

        $amount = (int)$validated['amount'];
        
        if($amount === 0)
        {
            $item->delete();
            return redirect(route('cart.index'));
        }
        
        $item->amount = $amount;
        $item->save();
        
        return redirect(route('cart.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItems $cartItem)
    {
    }
}
