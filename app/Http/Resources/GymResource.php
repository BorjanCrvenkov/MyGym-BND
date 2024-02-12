<?php

namespace App\Http\Resources;

use App\Enums\UserRolesEnum;
use App\Models\Membership;
use App\Models\Role;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class GymResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                           => $this->id,
            'name'                         => $this->name,
            'address'                      => $this->address,
            'date_opened'                  => $this->date_opened,
            'working_times'                => $this->working_times,
            'phone_number'                 => $this->phone_number,
            'rating'                       => $this->rating,
            'deleted_at'                   => $this->deleted_at,
            'updated_at'                   => $this->updated_at,
            'created_at'                   => $this->created_at,
            'owner_id'                     => $this->owner_id,
            'email'                        => $this->email,
            'shutdown'                     => $this->shutdown,
            'shutdown_date'                => $this->shutdown_date,
            'cover_image'                  => new FileResource($this->whenLoaded('cover_image')),
            'images'                       => new FileResource($this->whenLoaded('images')),
            'owner'                        => new FileResource($this->whenLoaded('user')),
            'social_media_links'           => new SocialMediaLinksResource($this->whenLoaded('social_media_links')),
            'can_owner_register_employees' => $this->canOwnerRegisterEmployees(),
            'active_sessions_count'        => $this->currentlyActiveSessionsCount(),
            'can_leave_review'             => $this->canUserLeaveReview(),
        ];
    }

    /**
     * @return bool
     */
    public function canOwnerRegisterEmployees(): bool
    {
        $employeeRole = Role::query()
            ->where('name', '=', UserRolesEnum::EMPLOYEE->value)->first();

        $currentGymEmployeeNumber = User::query()
            ->where('gym_id', '=', $this->id)
            ->where('role_id', '=', $employeeRole->getKey())
            ->count();

        return $currentGymEmployeeNumber < $this->owner->plan->number_of_allowed_employees;
    }

    /**
     * @return int
     */
    public function currentlyActiveSessionsCount(): int
    {
        return Session::query()
            ->join('memberships', 'memberships.id', '=', 'sessions.membership_id')
            ->where('memberships.gym_id', '=', $this->id)
            ->whereNull('sessions.time_end')
            ->count();
    }

    /**
     * @return bool
     */
    public function canUserLeaveReview(): bool
    {
        return Membership::query()
            ->where('user_id', '=', Auth::id())
            ->where('gym_id', '=', $this->id)
            ->where('end_date', '>=', now())
            ->exists();
    }
}
