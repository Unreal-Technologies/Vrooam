<?php

namespace Tests\Feature\Shop;

use Tests\TestCase;
use Tests\Logic\Auth;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use Auth, RefreshDatabase;

    /**
     * @return void
     */
    public function testUserModelCart(): void
    {
        $this -> auth();

        $cart = $this->user->cart();
        $this->assertFalse($cart === null);
    }
    
    /**
     * @return void
     */
    public function testUserModelFromId(): void
    {
        $this -> auth();
        
        $user = User::fromId($this->user->id);
        $this->assertFalse($user === null);
    }
}
