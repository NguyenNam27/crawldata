<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = ['code_product','name','category_id','link_product','price_cost', 'status','created_at'];
    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }

}
