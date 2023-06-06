<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPartner extends Model
{
    use HasFactory;

    protected $table = 'product_partners';

    protected $fillable = ['code_product_partner','name_product_partner','price_product_partner','url_product_partner'];
}
