<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOriginal extends Model
{
    use HasFactory;
    protected $table = 'product_originals';
    protected $fillable = ['code_product_original','name_product_original','price_product_original','url_product_original','catalog_product_original_id'];
}
