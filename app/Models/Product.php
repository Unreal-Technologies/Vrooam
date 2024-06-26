<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'description', 'text', 'price'];

    /**
     * @param int $id
     * @return Product|null
     */
    public static function fromId(int $id): ?Product
    {
        return self::where('id', '=', $id)->first();
    }

    /**
     * @param string $code
     * @return Product|null
     */
    public static function fromCode(string $code): ?Product
    {
        return self::where('code', '=', $code)->first();
    }
}
