<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                          => $this->id,
            'name'                        => $this->name,
            'stripe_plan'                 => $this->stripe_plan,
            'price'                       => $this->price,
            'description'                 => $this->description,
            'number_of_allowed_gyms'      => $this->number_of_allowed_gyms,
            'number_of_allowed_employees' => $this->number_of_allowed_employees,
            'duration_months'             => $this->duration_months,
            'deleted_at'                  => $this->deleted_at,
            'updated_at'                  => $this->updated_at,
            'created_at'                  => $this->created_at,
        ];
    }
}
