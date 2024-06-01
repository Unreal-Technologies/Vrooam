<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Coupon extends Model
{
    use HasFactory;
    
    /**
     * @param int $id
     * @return Coupon|null
     */
    public static function fromId(int $id): ?Coupon
    {
        return self::where('id', '=', $id)->first();
    }
    
    /**
     * @param string $code
     * @return Coupon|null
     */
    public static function byCode(string $code): ?Coupon
    {
        return self::where('code', '=', strtoupper($code))->first();
    }
    
    /**
     * @param User $user
     * @return bool
     */
    public function isUsed(User $user): bool
    {
        return Cart::where([
            ['user_id', '=', $user->id],
            ['coupon_id', '=', $this->id]
        ])->exists();
    }
}
