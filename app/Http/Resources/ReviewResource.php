<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'rating'      => $this->rating,
            'body'        => $this->body,
            'reviewer_id' => $this->reviewer_id,
            'model_id'    => $this->model_id,
            'model_type'  => $this->model_type,
            'reviewer'    => new UserResource($this->whenLoaded('reviewer')),
            'gym'         => new GymResource($this->whenLoaded('gym')),
            'deleted_at'  => $this->deleted_at,
            'updated_at'  => $this->updated_at,
            'created_at'  => $this->created_at,
        ];
    }
}
