<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
            'model_id'    => $this->model_id,
            'model_type'  => $this->model_type,
            'reason'      => $this->reason,
            'heading'     => $this->heading,
            'reporter_id' => $this->reporter_id,
            'reporter'    => new UserResource($this->whenLoaded('reporter')),
            'deleted_at'  => $this->deleted_at,
            'updated_at'  => $this->updated_at,
            'created_at'  => $this->created_at,
        ];
    }
}
