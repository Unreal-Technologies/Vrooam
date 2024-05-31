<?php

namespace App\DTO;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Cart;
use App\Models\CartItems;

class CartItemDto
{
    /**
     * @var string
     */
    public string $description;

    /**
     * @var string
     */
    public int $amount;

    /**
     * @var string
     */
    public float $price;

    public int $id;

    /**
     * @param string $description
     * @param int $amount
     * @param float $price
     */
    private function __construct(int $id, string $description, int $amount, float $price)
    {
        $this -> description = $description;
        $this -> amount = $amount;
        $this -> price = $price;
        $this -> id = $id;
    }

    /**
     * @param Cart $cart
     * @return CartDto
     */
    public static function fromCart(Cart $cart): array
    {
        $buffer = [];
        foreach (CartItems::where('cart_id', '=', $cart->id)->get() as $item) {
            $buffer[] = self::fromItem($item);
        }

        return $buffer;
    }

    /**
     * @param CartItems $item
     * @return CartItemDto
     */
    public static function fromItem(CartItems $item): CartItemDto
    {
        $product = $item->product();
        return new CartItemDto($item->id, $product->description, $item->amount, $product->price);
    }

    /**
     * @return float
     */
    public function price(): float
    {
        return $this -> amount * $this -> price;
    }
}
