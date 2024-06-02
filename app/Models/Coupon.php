<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Logic\CouponTypes;

class Coupon extends Model
{
    use HasFactory;
    
    protected $fillable = ['code', 'discount', 'type'];

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
    public static function fromCode(string $code): ?Coupon
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

    /**
     * @return string
     */
    public function text(): string
    {
        $text = 'Kortings code <b>"' . $this->code . '"</b>';
        $enum = CouponTypes::from($this->type);
        switch ($enum) {
            case CouponTypes::Flat:
                return $text . ' (&euro; ' . number_format($this->discount, 2, ',', '.') . ')';
            case CouponTypes::Percentage:
                return $text . ' (' . number_format($this->discount, 2, ',', '.') . ' %)';
        }
    }

    /**
     * @return string
     */
    public function typeDescription(): string
    {
        $enum = CouponTypes::from($this->type);
        return $enum->name;
    }
}
