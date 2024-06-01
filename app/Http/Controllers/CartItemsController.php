<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\CartItems;

class CartItemsController extends Controller
{
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

        if ($amount === 0) {
            $item->delete();
            return redirect(route('cart.index'))->with('status', 'item-deleted')->with('product', $item->product()->description);
        }

        $item->amount = $amount;
        $item->save();

        return redirect(route('cart.index'))->with('status', 'item-updated-' . $id);
    }
}
