<?php

namespace Tests\Feature\Shop;

use Tests\TestCase;
use Tests\Logic\Auth;
use App\Models\Coupon;
use App\Logic\CouponTypes;

class CouponTest extends TestCase
{
    use Auth;

    /**
     * @return void
     */
    public function testCouponControllerScreenAuthenticationRequired(): void
    {
        $response = $this->get('/coupons');
        $response->assertStatus(302);
    }

    /**
     * @return void
     */
    public function testCouponControllerScreenCanBeRendered(): void
    {
        $response = $this->auth()->get('/coupons');
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testCouponControllerScreenCreateCanBeRendered(): void
    {
        $response = $this->auth()->get('/coupons/create');
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testCouponControllerAddCouponVrooam1(): void
    {
        $response = $this->auth()->post('/coupons', [
            'type' => CouponTypes::Percentage->value,
            'discount' => 10,
            'code' => 'vrooam1'
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('coupons.index', absolute: false));

        $coupon = Coupon::where('code', '=', 'vrooam1')->first();
        $this->assertFalse($coupon === null);
        if ($coupon !== null) {
            $this ->assertEquals('VROOAM1', $coupon->code);
        }
    }

    /**
     * @return void
     */
    public function testCouponControllerUpdateCouponVrooam1(): void
    {
        $coupon = Coupon::where('code', '=', 'vrooam1')->first();
        $this->assertFalse($coupon === null);

        if ($coupon !== null) {
            $response = $this->auth()->put('coupons/' . $coupon->id, [
                'discount' => 15,
                'type' => CouponTypes::Flat->value,
                'code' => 'vrooam1'
            ]);

            $this->assertAuthenticated();
            $response->assertRedirect(route('coupons.index', absolute: false));

            $new = Coupon::fromId($coupon->id);
            $this->assertEquals(15, $new->discount);
            $this->assertEquals(CouponTypes::Flat->value, $new->type);
        }
    }

    /**
     * @return void
     */
    public function testCouponModelCouponFromId(): void
    {
        $p1 = Coupon::fromId($this->baseCouponId());
        $p2 = Coupon::fromId(9999);

        $this->assertFalse($p1 === null);
        $this->assertTrue($p2 === null);
    }

    /**
     * @return void
     */
    public function testCouponMdelFromCode(): void
    {
        $p1 = Coupon::fromCode('vrooam1');
        $p2 = Coupon::fromCode('VROOAM1');
        $p3 = Coupon::fromCode('vROoam1');
        $p4 = Coupon::fromCode('vROoam2');

        $this->assertFalse($p1 === null);
        $this->assertFalse($p2 === null);
        $this->assertFalse($p3 === null);
        $this->assertTrue($p4 === null);
    }

    /**
     * @return void
     */
    public function testCouponModelCouponIsUsed(): void
    {
        $coupon = Coupon::fromId($this->baseCouponId());
        $this->assertFalse($coupon === null);

        if ($coupon !== null) {
            $this -> auth();

            $user = $this->user;
            $this->assertFalse($coupon->isUsed($user));

            $cart = $user->cart();
            $this->assertFalse($cart === null);

            if ($cart !== null) {
                $cart->coupon_id = $coupon->id;
                $cart->save();

                $this->assertTrue($coupon->isUsed($user));
            }
        }
    }

    /**
     * @return void
     */
    public function testCouponModelCouponText(): void
    {
        $coupon = Coupon::fromId($this->baseCouponId());
        $this->assertFalse($coupon === null);

        if ($coupon !== null) {
            $this->assertEquals('Kortings code <b>"VROOAM1"</b> (&euro; 15,00)', $coupon->text());
            $coupon->type = CouponTypes::Percentage->value;
            $this->assertEquals('Kortings code <b>"VROOAM1"</b> (15,00 %)', $coupon->text());
        }
    }

    /**
     * @return void
     */
    public function testCouponModelCouponTypeDescription(): void
    {
        $coupon = Coupon::fromId($this->baseCouponId());
        $this->assertFalse($coupon === null);

        if ($coupon !== null) {
            $this->assertEquals('Flat', $coupon->typeDescription());
            $coupon->type = CouponTypes::Percentage->value;
            $this->assertEquals('Percentage', $coupon->typeDescription());
        }
    }

    /**
     * @return void
     */
    public function testCouponControllerDeleteCouponVrooam1(): void
    {
        $coupon = Coupon::where('code', '=', 'vrooam1')->first();
        $this->assertFalse($coupon === null);

        if ($coupon !== null) {
            $response = $this->auth()->delete('coupons/' . $coupon->id);

            $this->assertAuthenticated();
            $response->assertRedirect(route('coupons.index', absolute: false));

            $new = Coupon::fromId($coupon->id);
            $this->assertTrue($new === null);
        }
    }
    
    private function baseCouponId(): int
    {
        return Coupon::fromCode('vrooam1')->id;
    }
}
