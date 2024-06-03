<?php

namespace Tests\Feature\Shop;

use Tests\TestCase;
use Tests\Logic\Auth;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\CartItems;

class CartItemsTest extends TestCase
{
    use Auth;
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testCartItemsControllerUpdateSetToZero(): void
    {
        $cartItem = $this->setupData();
        $this->assertFalse($cartItem === null);

        $response = $this->auth()->patch('/cartitems/' . $cartItem->id, [
            'amount' => 0
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('cart.index', absolute: false));

        $cartItemTest = CartItems::fromId($cartItem->id);
        $this->assertTrue($cartItemTest === null);
    }

    /**
     * @return void
     */
    public function testCartItemsControllerUpdateSetToFive(): void
    {
        $cartItem = $this->setupData();
        $this->assertFalse($cartItem === null);

        $response = $this->auth()->patch('/cartitems/' . $cartItem->id, [
            'amount' => 5
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('cart.index', absolute: false));

        $cartItemTest = CartItems::fromId($cartItem->id);
        $this->assertEquals(5, $cartItemTest->amount);
    }

    /**
     * @return void
     */
    public function testCartItemsModelProduct(): void
    {
        $cartItem = $this->setupData();
        $this->assertFalse($cartItem === null);

        $product = $cartItem->product();
        $this->assertFalse($product === null);
    }

    /**
     * @return void
     */
    public function testCartItemsModelFromId(): void
    {
        $id = $this->setupData()->id;

        $cartItem = CartItems::fromId($id);
        $this->assertFalse($cartItem === null);
    }

    /**
     * @return CartItems
     */
    private function setupData(): CartItems
    {
        $this -> auth();
        $user = $this->user;
        $this->assertFalse($user === null);

        $cart = $user->cart();
        $this->assertFalse($cart === null);

        $product = $this->createProductA();
        $this->assertFalse($product === null);

        CartItems::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'amount' => 1
        ]);

        return CartItems::where('id', '!=', 0)->first();
    }

    /**
     * @return Product
     */
    private function createProductA(): Product
    {
        $product = Product::fromCode('P1A');
        if ($product !== null) {
            return $product;
        }

        Product::create([
            'description' => 'Product A',
            'price' => 24.99,
            'text' => 'ABC',
            'code' => 'P1A'
        ]);
        return Product::fromCode('P1A');
    }
}
