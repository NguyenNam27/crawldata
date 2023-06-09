<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOriginal extends Model
{
    use HasFactory;
    public $timestamps = true   ;
    protected $table = 'product_originals';
    protected $fillable = ['code_product_original','name_product_original','price_product_original','url_product_original','category_id'];
    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }
}
