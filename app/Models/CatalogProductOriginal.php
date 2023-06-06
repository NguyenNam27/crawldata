<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogProductOriginal extends Model
{
    use HasFactory;
    protected $table = 'catalog_product_originals';
    protected $fillable = ['name'];
}
