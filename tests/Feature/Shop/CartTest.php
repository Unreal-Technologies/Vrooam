<?php

namespace Tests\Feature\Shop;

use Tests\TestCase;
use Tests\Logic\Auth;
use App\Models\Cart;
use App\Models\Coupon;
use App\Logic\CouponTypes;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    use Auth, RefreshDatabase;
    
    /**
     * @return void
     */
    public function testCartControllerScreenAuthenticationRequired(): void
    {
        $response = $this->get('/cart');
        $response->assertStatus(302);
    }

    /**
     * @return void
     */
    public function testCartControllerScreenCanBeRendered(): void
    {
        $response = $this->auth()->get('/cart');
        $response->assertStatus(200);
    }
    
    /**
     * @return void
     */
    public function testCartControllerAddCouponVrooam3(): void
    {
        $this->auth();

        $coupon = $this -> createCouponVrooam3();
        $this->assertFalse($coupon === null);
        
        $user = $this->user;
        $this->assertFalse($user === null);
        
        $cart = $user->cart();
        $this->assertFalse($cart === null);
        
        $response = $this->auth()->post('cart.addcoupon/'.$cart->id, [
            'code' => $coupon->code
        ]);
        
        $this->assertAuthenticated();
        $response->assertRedirect(route('cart.index', absolute: false));
        
        $cart2 = $user->cart();
        $this->assertFalse($cart2 === null);
        $this->assertTrue($coupon->id === $cart2->coupon_id);
    }
    
    /**
     * @return void
     */
    public function testCartControllerRemoveCoupon(): void
    {
        $this->testCartControllerAddCouponVrooam3();
        
        $this->auth();
        $user = $this->user;
        $this->assertFalse($user === null);
        
        $cart = $user->cart();
        $this->assertFalse($cart === null);
        $this->assertFalse($cart->coupon_id === null);
        
        $response = $this->auth()->post('cart.removecoupon/'.$cart->id);
        
        $this->assertAuthenticated();
        $response->assertRedirect(route('cart.index', absolute: false));
        
        $cart2 = $user->cart();
        $this->assertFalse($cart2 === null);
        $this->assertTrue($cart2->coupon_id === null);
    }
    
    /**
     * @return void
     */
    public function testCartControllerStoreAdd(): void
    {
        $this->auth();
        
        $product = $this -> createProductA();
        $this->assertFalse($product === null);
        
        $user = $this->user;
        $this->assertFalse($user === null);
        
        $cart = $user->cart();
        $this->assertFalse($cart === null);
        $this->assertTrue($cart->itemCount() === 0);
        
        $response = $this->auth()->post('/cart', [
            'id' => $product->id,
            'amount' => 5
        ]);
        
        $this->assertAuthenticated();
        $response->assertRedirect(route('products.index', absolute: false));
        
        $this->assertTrue($cart->itemCount() === 5);
    }

    /**
     * @return void
     */
    public function testCartControllerStoreUpdate(): void
    {
        $this->testCartControllerStoreAdd();
        $this->auth();
        
        $product = $this -> createProductA();
        $this->assertFalse($product === null);
        
        $user = $this->user;
        $this->assertFalse($user === null);
        
        $cart = $user->cart();
        $this->assertFalse($cart === null);
        $this->assertTrue($cart->itemCount() === 5);
        
        $response = $this->auth()->post('/cart', [
            'id' => $product->id,
            'amount' => 1
        ]);
        
        $this->assertAuthenticated();
        $response->assertRedirect(route('products.index', absolute: false));
        
        $this->assertTrue($cart->itemCount() === 6);
    }
    
    public function testCartControllerDestroy(): void
    {
        $this->testCartControllerStoreUpdate();
        
        $user = $this->user;
        $this->assertFalse($user === null);
        
        $cart = $user->cart();
        $this->assertFalse($cart === null);
        
        $response = $this->auth()->delete('/cart/'.$cart->id);
        
        $this->assertAuthenticated();
        $response->assertRedirect(route('cart.index', absolute: false));
    }
    
    /**
     * @return Cart
     */
    public function testCartModelGetByUser(): Cart
    {
        $this->auth();
        
        $user = $this->user;
        $this->assertFalse($user === null);
        
        $cart = Cart::getByUser($user);
        $this->assertFalse($cart === null);
        
        return $cart;
    }
    
    /**
     * @return void
     */
    public function testCartModelFromId(): void
    {
        $c1 = Cart::fromId($this->testCartModelGetByUser()->id);
        $c2 = Cart::fromId(9999);
        $this->assertFalse($c1 === null);
        $this->assertTrue($c2 === null);
    }
    
    /**
     * @return void
     */
    public function testCartModelFromCoupon(): void
    {
        $this->testCartControllerAddCouponVrooam3();
        $coupon = Coupon::fromCode('vrooam3');
        $this->assertFalse($coupon === null);
        
        $collection = Cart::fromCoupon($coupon);
        $this->assertTrue($collection->count() === 1);
    }
    
    /**
     * @return void
     */
    public function testCartModelCoupon(): void
    {
        $this->testCartControllerAddCouponVrooam3();
        
        $user = $this->user;
        $this->assertFalse($user === null);
        
        $cart = Cart::getByUser($user);
        $this->assertFalse($cart === null);
        
        $coupon = $cart->coupon();
        $this->assertFalse($coupon === null);
    }
    
    /**
     * @return void
     */
    public function testCartModelUser(): void
    {
        $this->testCartControllerAddCouponVrooam3();
        
        $user = $this->user;
        $this->assertFalse($user === null);
        
        $cart = Cart::getByUser($user);
        $this->assertFalse($cart === null);
        
        $userX = $cart->user();
        $this->assertFalse($userX === null);
    }
    
    /**
     * @return void
     */
    public function testCartModelItemCount(): void
    {
        $this->testCartControllerStoreUpdate();
        
        $user = $this->user;
        $this->assertFalse($user === null);
        
        $cart = Cart::getByUser($user);
        $this->assertFalse($cart === null);
        
        $this->assertEquals(6, $cart->itemCount());
    }
    
    /**
     * @return void
     */
    public function testCartModelItems(): void
    {
        $this->testCartControllerStoreAdd();
        
        $user = $this->user;
        $this->assertFalse($user === null);
        
        $cart = Cart::getByUser($user);
        $this->assertFalse($cart === null);
        
        $collection = $cart->items();
        $this->assertEquals(1, $collection->count());
    }
    
    /**
     * @return void
     */
    public function testCartModelGetItem(): void
    {
        $this->testCartControllerStoreAdd();
        
        $user = $this->user;
        $this->assertFalse($user === null);
        
        $cart = Cart::getByUser($user);
        $this->assertFalse($cart === null);
        
        $id = null;
        foreach($cart->items() as $item)
        {
            $id = $item->id;
            break;
        }
        
        $item = $cart->getItem($id);
        $this->assertFalse($item === null);
    }
    
    public function testCartModelAddOrUpdateProduct(): void
    {
        $this->testCartControllerStoreAdd();
        $product = $this -> createProductA();
        
        $user = $this->user;
        $this->assertFalse($user === null);
        
        $cart = Cart::getByUser($user);
        $this->assertFalse($cart === null);
        
        $this->assertEquals(5, $cart->itemCount());
        
        $cart->addOrUpdateProduct($product, 10);
        $this->assertEquals(15, $cart->itemCount());
    }
    
    /**
     * @return Product
     */
    private function createProductA(): Product
    {
        $product = Product::fromCode('P1A');
        if($product !== null)
        {
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
    
    /**
     * @return void
     */
    private function createCouponVrooam3(): Coupon
    {
        $response = $this->auth()->post('/coupons', [
            'type' => CouponTypes::Percentage->value,
            'discount' => 10,
            'code' => 'vrooam3'
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('coupons.index', absolute: false));

        $coupon = Coupon::where('code', '=', 'vrooam3')->first();
        $this->assertFalse($coupon === null);
        if ($coupon !== null) {
            $this ->assertEquals('VROOAM3', $coupon->code);
        }
        return $coupon;
    }
}