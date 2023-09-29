<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'thumb_image' => $this->thumb_image,
            'vendor' => [
               'id'=> $this->vendor->id,
               'name'=> $this->vendor->shop_name,
               'slug'=> Str::slug($this->vendor->shop_name),
            ],
            'availability' => $this->quantity,
            'short_description' => $this->short_description,
            'full_description' => $this->full_description,
            'sku' => $this->sku,
            'price' => $this->price,
            'offer_price' => $this->offer_price,
            'product_type' => ($this->product_type === 'best_product' ? 'sale' : (($this->product_type === 'new_arrival' ? 'new' : 'featured'))),
            'status' => $this->status,
            'rating' =>  $this->reviews->average('rating'),
            'category' =>[
                'id'=> $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
                'subCategories' => $this->category->subCategories->map(function ($subCategory) {
                    return [
                        'id' => (string)$subCategory->id,
                        'name' => $subCategory->name,
                        'slug' => $subCategory->slug,
                    ];
                }),
            ],
            'subCategory' => [
                'id'=> $this->subCategory->id,
                'name' => $this->subCategory->name,
                'slug' => $this->subCategory->slug
            ],

        ];
    }
}
