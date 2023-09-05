<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => (string)$this->id,
            'category' => [
                'name' => $this->name,
                'slug' => $this->slug,
            ],
            'subCategory' => $this->subCategories->map(function ($subCategory) {
                return [
                    'id' => (string)$subCategory->id,
                    'name' => $subCategory->name,
                    'slug' => $subCategory->slug,
                ];
            }),
        ];
    }
}

