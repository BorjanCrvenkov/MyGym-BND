<?php

namespace App\Http\Resources;

use App\Models\Membership;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MembershipTypeResource extends JsonResource
{
    private ?Membership $membership;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->membership = $this->fetchMembership();
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'description'    => $this->description,
            'price'          => $this->price,
            'duration_weeks' => $this->duration_weeks,
            'gym_id'         => $this->gym_id,
            'deleted_at'     => $this->deleted_at,
            'updated_at'     => $this->updated_at,
            'created_at'     => $this->created_at,
            'is_active'      => (bool)$this->membership,
            'valid_until'    => $this->membership ? $this->membership->end_date : null,
        ];
    }

    /**
     * Fetches the active membership for the logged in user and the membership type
     * @return Model|Builder|null
     */
    public function fetchMembership(): Model|Builder|null
    {
        return Membership::query()
            ->where('user_id', '=', Auth::id())
            ->where('membership_type_id', '=', $this->id)
            ->where('end_date', '>', now())
            ->first() ?? null;
    }
}
