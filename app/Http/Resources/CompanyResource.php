<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'kra_pin' => $this->kra_pin,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
            'employees_count' => $this->whenCounted('employees'),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
