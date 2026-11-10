<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'staff_number' => $this->staff_number,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'kra_pin' => $this->kra_pin,
            'hire_date' => $this->hire_date?->toDateString(),
            'termination_date' => $this->termination_date?->toDateString(),
            'employment_type' => $this->employment_type,
            'status' => $this->status,
            'department' => $this->whenLoaded('department', fn ($department) => DepartmentResource::make($department)),
            'job_title' => $this->whenLoaded('jobTitle', fn ($jobTitle) => [
                'id' => $jobTitle->id,
                'name' => $jobTitle->name,
            ]),
            'manager' => $this->whenLoaded('manager', fn ($manager) => [
                'id' => $manager->id,
                'full_name' => $manager->full_name
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
