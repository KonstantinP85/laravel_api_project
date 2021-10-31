<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'client_id', 'product_id'];

    public function product()
    {
        return $this->belongsto(Product::class);
    }

    public function client()
    {
        return $this->belongsto(Client::class);
    }

    public function orderHistory()
    {
        return $this->hasMany(OrderHistory::class);
    }
}
