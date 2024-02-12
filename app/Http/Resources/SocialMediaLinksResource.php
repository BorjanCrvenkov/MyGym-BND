<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialMediaLinksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'gym_id'         => $this->gym_id,
            'instagram_link' => $this->instagram_link,
            'facebook_link'  => $this->facebook_link,
            'twitter_link'   => $this->twitter_link,
            'deleted_at'     => $this->deleted_at,
            'updated_at'     => $this->updated_at,
            'created_at'     => $this->created_at,
        ];
    }
}
