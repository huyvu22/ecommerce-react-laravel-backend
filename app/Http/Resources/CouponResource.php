<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'discount_type' => $this->discount_type,
            'discount'=>$this->discount_value,
            'quantity' => $this->quantity,
            'max_use'=>$this->max_use,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}
