<?php

namespace Tests\Logic;

use App\Models\User;

trait Auth
{
    /**
     * @var User|null
     */
    protected ?User $user = null;

    /**
     * @return mixed
     */
    protected function auth(): mixed
    {
        $this->user = User::where('id', '!=', 0)->first();
        if ($this->user === null) {
            $this->user = User::factory()->unverified()->create();
        }
        return $this->actingAs($this->user);
    }
}
