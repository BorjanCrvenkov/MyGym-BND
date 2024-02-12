<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MembershipResource extends JsonResource
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
            'start_date'                     => $this->start_date,
            'end_date'                       => $this->end_date,
            'user_id'                        => $this->user_id,
            'original_membership_type_model' => $this->original_membership_type_model,
            'membership_type_id'             => $this->membership_type_id,
            'gym_id'                         => $this->gym_id,
            'active_session_id'              => $this->active_session_id,
            'refunded'                       => $this->refunded,
            'user'                           => new UserResource($this->whenLoaded('user')),
            'active_session'                 => new SessionResource($this->whenLoaded('active_session')),
            'sessions'                       => new SessionCollection($this->whenLoaded('sessions')),
            'deleted_at'                     => $this->deleted_at,
            'updated_at'                     => $this->updated_at,
            'created_at'                     => $this->created_at,
        ];
    }
}
