<?php

namespace App\Http\Resources;

use App\Models\FooterColumnThree;
use App\Models\FooterColumnTwo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FooterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'column_2' => [
                'title' => $this->footer_column_2_title,
//                'links' => $this->footerColumnTwo->pluck('link_field_name')->all()
            ],
            'column_3' => [
                'title' => $this->footer_column_3_title,
//                'links' => $this->footerColumnThree->pluck('link_field_name')->all()
            ]

        ];
    }
}
