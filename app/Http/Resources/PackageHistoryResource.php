<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageHistoryResource extends JsonResource
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
            'price' => $this->price,
            'created_at' => $this->created_at->diffForHumans(),
            'package' => [
                'id' => $this->package->id,
                'title' => $this->package->title,
                'description' => $this->package->description,
            ],
            'branch' => [
                'id' => $this->branch->id,
                'name' => $this->branch->name,
                'image' => $this->branch->image ?? null,
            ],
        ];
    }
}
