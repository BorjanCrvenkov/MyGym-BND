<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                             => $this->id,
            'name'                           => $this->name,
            'description'                    => $this->description,
            'cost'                           => $this->cost,
            'recurring'                      => $this->recurring,
            'recurring_every_number_of_days' => $this->recurring_every_number_of_days,
            'next_recurring_date'            => $this->next_recurring_date,
            'gym_id'                         => $this->gym_id,
            'deleted_at'                     => $this->deleted_at,
            'updated_at'                     => $this->updated_at,
            'created_at'                     => $this->created_at,
        ];
    }
}
