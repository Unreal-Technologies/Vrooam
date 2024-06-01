<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Collection;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'is_processed'];

    /**
     * @param Product $product
     * @param int $amount
     * @return void
     */
    public function addOrUpdateProduct(Product $product, int $amount): void
    {
        $item = CartItems::where([
            ['cart_id', '=', $this->id],
            ['product_id', '=', $product->id]
        ])->first();

        if ($item === null) {
            CartItems::create([
                'cart_id' => $this->id,
                'product_id' => $product->id,
                'amount' => $amount
            ]);
        } else {
            $item->amount += $amount;
            $item->save();
        }
    }

    /**
     * @param User $user
     * @return Cart
     */
    public static function getByUser(User $user): Cart
    {
        $cart = Cart::where([
            ['user_id', '=', $user->id],
            ['is_processed', '=', false]
        ])->first();
        if ($cart === null) { //Create cart if user doesn't have a un-processed cart
            Cart::create([
                'user_id' => $user->id,
            ]);
            $cart = Cart::where([
                ['user_id', '=', $user->id],
                ['is_processed', '=', false]
            ])->first();
        }
        return $cart;
    }
    
    /**
     * @return Collection
     */
    public function items(): Collection
    {
        return CartItems::where('cart_id', '=', $this->id)->get();
    }
    
    /**
     * @param int $id
     * @return CartItems|null
     */
    public function getItem(int $id): ?CartItems
    {
        return CartItems::fromId($id);
    }
    
    /**
     * @param int $id
     * @return Cart|null
     */
    public static function fromId(int $id): ?Cart
    {
        return self::where('id', '=', $id)->first();
    }
    
    /** 
     * @return Coupon|null
     */
    public function coupon(): ?Coupon
    {
        if($this->coupon_id === null)
        {
            return null;
        }
        return Coupon::fromId($this->coupon_id);
    }
    
    /**
     * @return User
     */
    public function user(): User
    {
        return User::fromId($this->user_id);
    }
}
