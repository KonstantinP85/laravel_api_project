<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'price', 'product_category_id'];

    public function productCategory()
    {
        return $this->belongsto(ProductCategory::class);
    }
}
