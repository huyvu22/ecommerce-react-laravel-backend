<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product->name,
            'rating' => $this->rating,
            'user_name' => $this->user->name,
            'user_avatar' => $this->user->image,
            'vendor' => $this->vendor->shop_name,
            'comment' => $this->review,
            'images' => $this->productReviewGalleries->map(function($image){
                return [
                    'id' => (string)$image->id,
                    'image' => $image->image,
                ];
            }),
            'created_at' => $this->created_at
        ];
    }
}
