<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;
    use HasFactory;

    public function category()
    {
        return $this->hasMany(Product::class, 'categoryId', 'id');
    }
}
