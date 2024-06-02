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
        $match = Coupon::byCode($validated['code']);
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
}
