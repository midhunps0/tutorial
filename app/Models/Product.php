<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Ynotz\MediaManager\Traits\OwnsMedia;

class Product extends Model
{
    use HasFactory, OwnsMedia;

    protected $guarded = [];

    protected $casts = [
        'features' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryId', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'products_tags', 'productId', 'tagId');
    }

    public function getMediaStorage(): array
    {
        return [
            'image' => $this->storageLocation(
                'public',
                'products'
            )
        ];
    }

    public function image(): Attribute
    {
        return Attribute::make(
            get: function ($val) {
                return $this->getSingleMediaForEAForm('image');
            }
        );
    }
}
