<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'time_start'    => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'time_end'      => $this->time_end ? Carbon::parse($this->time_end)->format('Y-m-d H:i:s') : null,
            'membership_id' => $this->membership_id,
            'description'   => $this->description,
            'deleted_at'    => $this->deleted_at,
            'updated_at'    => $this->updated_at,
            'created_at'    => $this->created_at,
        ];
    }
}
