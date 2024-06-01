<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Coupon;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = User::fromId(auth()->id());
        if ($user === null) { //If product not found, throw error
            throw new \Exception('User not found', 404);
        }
        $cart = $user->cart();
        $items = $cart->items();

        $coupon = $cart->coupon();
        $sum = 0;
        foreach ($items as $item) {
            $sum += $item->amount * $item->product()->price;
        }
        
        $discount = 0;
        if($coupon !== null)
        {
            $enum = \App\Logic\CouponTypes::from($coupon->type);
            switch($enum)
            {
                case \App\Logic\CouponTypes::Flat:
                    $discount = $coupon->discount;
                    break;
                case \App\Logic\CouponTypes::Percentage:
                    $discount = ($sum / 100) * $coupon -> discount;
                    break;
            }
        }

        return view('cart.index', [
            'items' => $items,
            'sum' => $sum,
            'coupon' => $coupon,
            'discount' => $discount,
            'total' => $sum - $discount
        ]);
    }

    public function removecoupon(Request $request, int $id): RedirectResponse
    {
        $cart = Cart::fromId($id);
        if($cart === null)
        {
            throw new \Exception('Cannot find cart with id "'.$id.'"', 404);
        }
        $user = $request->user();
        if($cart->user()->id !== $user->id)
        {
            throw new \Exception('Cart mismatch', 300);
        }
        
        $cart->coupon_id = null;
        $cart->save();
        
        return redirect(route('cart.index'));
    }
    
    /**
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     * @throws \Exception
     */
    public function addcoupon(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required'
        ]);
        
        $coupon = Coupon::byCode($validated['code']);
        if($coupon === null)
        {
            throw ValidationException::withMessages(['code' => 'De coupon met code "'.$validated['code'].'" is niet gevonden.']);
        }
        $cart = Cart::fromId($id);
        if($cart === null)
        {
            throw new \Exception('Cannot find cart with id "'.$id.'"', 404);
        }
        $user = $request->user();
        if($cart->user()->id !== $user->id)
        {
            throw new \Exception('Cart mismatch', 300);
        }
        if($coupon->isUsed($user))
        {
            throw ValidationException::withMessages(['code' => 'De coupon met code "'.$validated['code'].'" is al gebruikt.']);
        }
        $cart->coupon_id = $coupon->id;
        $cart->save();
        
        return redirect(route('cart.index'));
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart): RedirectResponse
    {
        $cart->delete();
        $cart->save();
        return redirect(route('cart.index'));
    }
}
