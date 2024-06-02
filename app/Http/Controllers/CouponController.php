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
}
