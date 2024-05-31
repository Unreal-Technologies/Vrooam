<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class CartItems extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'product_id', 'amount'];

    /**

     * @return Product
     */
    public function product(): Product
    {
        return Product::where('id', '=', $this->product_id)->first();
    }
}
