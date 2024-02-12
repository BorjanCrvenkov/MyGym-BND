<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Equipment;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\File;
use App\Models\Gym;
use App\Models\Membership;
use App\Models\MembershipType;
use App\Models\Plan;
use App\Models\Report;
use App\Models\Review;
use App\Models\Role;
use App\Models\Session;
use App\Models\SocialMediaLinks;
use App\Models\User;
use App\Models\WorkingSchedule;
use App\Models\WorkingTime;
use App\Policies\EquipmentPolicy;
use App\Policies\ExpensePolicy;
use App\Policies\ExpenseTypePolicy;
use App\Policies\FilePolicy;
use App\Policies\GymPolicy;
use App\Policies\MembershipPolicy;
use App\Policies\MembershipTypePolicy;
use App\Policies\PlanPolicy;
use App\Policies\ReportPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\RolePolicy;
use App\Policies\SessionPolicy;
use App\Policies\SocialMediaLinksPolicy;
use App\Policies\UserPolicy;
use App\Policies\WorkingSchedulePolicy;
use App\Policies\WorkingTimePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Equipment::class        => EquipmentPolicy::class,
        Expense::class          => ExpensePolicy::class,
        ExpenseType::class      => ExpenseTypePolicy::class,
        File::class             => FilePolicy::class,
        Gym::class              => GymPolicy::class,
        Membership::class       => MembershipPolicy::class,
        MembershipType::class   => MembershipTypePolicy::class,
        Plan::class             => PlanPolicy::class,
        Report::class           => ReportPolicy::class,
        Review::class           => ReviewPolicy::class,
        Role::class             => RolePolicy::class,
        Session::class          => SessionPolicy::class,
        SocialMediaLinks::class => SocialMediaLinksPolicy::class,
        User::class             => UserPolicy::class,
        WorkingSchedule::class  => WorkingSchedulePolicy::class,
        WorkingTime::class      => WorkingTimePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
