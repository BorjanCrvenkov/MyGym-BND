<?php

namespace App\Http\Resources;

use App\Models\Gym;
use App\Models\Plan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'first_name'          => $this->first_name,
            'last_name'           => $this->last_name,
            'email'               => $this->email,
            'email_verified_at'   => $this->email_verified_at,
            'date_of_birth'       => $this->date_of_birth,
            'date_of_employment'  => $this->date_of_employment,
            'bio'                 => $this->bio,
            'username'            => $this->username,
            'deleted_at'          => $this->deleted_at,
            'updated_at'          => $this->updated_at,
            'created_at'          => $this->created_at,
            'confirmation_token'  => $this->confirmation_token,
            'role_id'             => $this->role_id,
            'gym_id'              => $this->gym_id,
            'plan_id'             => $this->plan_id,
            'working_schedule_id' => $this->working_schedule_id,
            'can_register_gyms'   => $this->canUserRegisterGyms(),
            'plan_ends_at'        => $this->planEndsAt(),
            'image'               => new FileResource($this->whenLoaded('image')),
            'identification_file' => new FileResource($this->whenLoaded('identification_file')),
            'role'                => new RoleResource($this->whenLoaded('role')),
            'gyms'                => new GymCollection($this->whenLoaded('gyms')),
            'plan'                => new PlanResource($this->whenLoaded('plan')),
            'working_schedule'    => new WorkingScheduleResource($this->whenLoaded('working_schedule')),
        ];
    }

    /**
     * @return bool
     */
    public function canUserRegisterGyms(): bool
    {
        $plan = Plan::query()
            ->find($this->plan_id);

        return $plan && Gym::query()
                ->where('owner_id', '=', $this->id)
                ->whereNull('shutdown_date')
                ->count() < $plan->number_of_allowed_gyms;
    }

    /**
     * @return string|null
     */
    public function planEndsAt(): ?string
    {
        $subscription = $this->subscriptions()->first();
        return $subscription ? Carbon::parse($subscription->ends_at)->format('Y-m-d') : now()->addMonth()->format('Y-m-d');
    }
}
