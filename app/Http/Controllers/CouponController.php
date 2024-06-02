<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Coupon;
use Illuminate\Validation\ValidationException;
use App\Logic\CouponTypes;

class CouponController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('coupon.index', [
            'items' => Coupon::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('coupon.entry', [
            'title' => 'Toevoegen',
            'route' => 'coupons.store',
            'method' => 'post',
            'param' => [],
            'code' => old('code'),
            'discount' => old('discount'),
            'type' => old('type')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required',
            'discount' => 'required|numeric|gt:0',
            'type' => 'required'
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $match = Coupon::fromCode($validated['code']);
        if ($match !== null) {
            throw ValidationException::withMessages([
                'code' => 'De korting met code "' . $validated['code'] . '" bestaat al.'
            ]);
        }

        $enum = CouponTypes::from($validated['type']);
        if ($enum === CouponTypes::Percentage && (float)$validated['discount'] > 100) {
            throw ValidationException::withMessages([
                'discount' => 'Korting "' . $validated['discount'] . '" moet kleiner zijn dan 100 als type "' . $enum->name . '" is.'
            ]);
        }

        Coupon::factory()->create($validated);
        return redirect(route('coupons.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $coupon = Coupon::fromId($id);

        return view('coupon.entry', [
            'title' => 'Bewerken',
            'route' => 'coupons.update',
            'method' => 'patch',
            'param' => [ 'coupon' => $coupon->id ],
            'code' => old('code') ?? $coupon->code,
            'discount' => old('discount') ?? $coupon->discount,
            'type' => old('type') ?? $coupon->type
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'code' => 'required',
            'discount' => 'required|numeric|gt:0',
            'type' => 'required'
        ]);
        $validated['code'] = strtoupper($validated['code']);

        $coupon = Coupon::fromId($id);
        $match = Coupon::fromCode($validated['code']);
        if ($match !== null && $match->id !== $coupon->id) {
            throw ValidationException::withMessages([
                'code' => 'De korting met code "' . $validated['code'] . '" bestaat al.'
            ]);
        }

        $coupon->update($validated);
        $coupon->save();

        return redirect(route('coupons.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $coupon = Coupon::fromId($id);

        foreach (Cart::fromCoupon($coupon) as $cart) {
            $cart->coupon_id = null;
            $cart->save();
        }

        $coupon->delete();

        return redirect(route('coupons.index'));
    }
}
