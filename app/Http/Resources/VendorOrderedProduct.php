<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorOrderedProduct extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'sub_total' => $this->order->sub_total,
            'coupon' => $this->order->coupon,
            'order_address' => $this->order->order_address,
            'invoice_id' => $this->order->invoice_id,
            'payment_method' => $this->order->payment_method,
            'payment_status' => $this->order->payment_status,
            'client' => $this->order->user->name,
            'created_at' => $this->order->created_at,
            'product' => $this->order->orderProducts,
            'order_status' => $this->order->order_status
        ];
    }
}
