<?php

namespace App\Http\Resources;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'invoice' => $this->invoice_id,
            'sub_total' => $this->sub_total,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'payment_status'=>$this->payment_status,
            'order_status'=>$this->order_status,
            'shipping_method'=>$this->shipping_method,
            'order_address'=>$this->order_address,
            'created_at' => $this->created_at,
            'order_product' => @$this->orderProducts->map(function ($item) {
                return [
                    'product_id' => (string)$item->id,
                    'product_image' => $item->product->thumb_image,
                    'name' => $item->product_name,
                    'slug' => $item->product->slug,
                    'quantity'=> $item->quantity,
                    'price' => $item->product->offer_price,
                ];
            })
            ];
    }

}
