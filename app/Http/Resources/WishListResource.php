<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class WishListResource extends JsonResource
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
            'username' => Auth::user()->name,
            'product_id' => $this->product_id,
            'name' => $this->product->name,
            'thumb_image' => $this->product->thumb_image,
            'slug' => $this->product->slug,
            'stock' => $this->product->quantity,
            'offer_price' => $this->product->offer_price,
            'price' => $this->product->price,
        ];
    }
}
