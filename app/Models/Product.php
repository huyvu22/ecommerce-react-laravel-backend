<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'thumb_image',
        'name',
        'slug',
        'vendor_id',
        'category_id',
        'sub_category_id',
        'sku',
        'price',
        'offer_price',
        'quantity',
        'short_description',
        'full_description',
        'product_type',
        'seo_title',
        'seo_description',
        'is_approved' ,
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }
}
