<?php

namespace Tests\Feature\Shop;

use Tests\TestCase;
use Tests\Logic\Auth;
use App\Models\Product;

class ProductTest extends TestCase
{
    use Auth;

    /**
     * @return void
     */
    public function test_products_controller_screen_authentication_required(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(302);
    }

    /**
     * @return void
     */
    public function test_products_controller_screen_can_be_rendered(): void
    {
        $response = $this->auth()->get('/products');
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_products_controller_screen_create_can_be_rendered(): void
    {
        $response = $this->auth()->get('/products/create');
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_products_controller_screen_editlist_can_be_rendered(): void
    {
        $response = $this->auth()->get('/products.editlist');
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_products_controller_add_product_a123(): void
    {
        $response = $this->auth()->post('products', [
            'description' => 'test1',
            'price' => 10,
            'text' => 'a simple text',
            'code' => 'a123'
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('products.editlist', absolute: false));

        $product = Product::where('code', '=', 'A123')->first();
        $this->assertFalse($product === null);
        if ($product !== null) {
            $this ->assertEquals('A123', $product->code);
        }
    }

    /**
     * @return void
     */
    public function test_products_controller_update_product_a123(): void
    {
        $product = Product::where('code', '=', 'A123')->first();
        $this->assertFalse($product === null);

        if ($product !== null) {
            $response = $this->auth()->put('products/' . $product->id, [
                'description' => 'test2',
                'price' => 10,
                'text' => 'a simple text',
                'code' => 'A123'
            ]);

            $this->assertAuthenticated();
            $response->assertRedirect(route('products.editlist', absolute: false));

            $new = Product::fromId($product->id);
            $this->assertEquals('test2', $new->description);
        }
    }

    /**
     * @return void
     */
    public function test_product_model_product_from_id(): void
    {
        $p1 = Product::fromId(1);
        $p2 = Product::fromId(2);

        $this->assertFalse($p1 === null);
        $this->assertTrue($p2 === null);
    }

    /**
     * @return void
     */
    public function test_product_model_from_code(): void
    {
        $p1 = Product::fromCode('a123');
        $p2 = Product::fromCode('A123');
        $p3 = Product::fromCode('b123');

        $this->assertFalse($p1 === null);
        $this->assertFalse($p2 === null);
        $this->assertTrue($p3 === null);
    }

    /**
     * @return void
     */
    public function test_products_controller_delete_product_a123(): void
    {
        $product = Product::where('code', '=', 'A123')->first();
        $this->assertFalse($product === null);

        if ($product !== null) {
            $response = $this->auth()->delete('products/' . $product->id);

            $this->assertAuthenticated();
            $response->assertRedirect(route('products.editlist', absolute: false));

            $new = Product::fromId($product->id);
            $this->assertTrue($new === null);
        }
    }
}
