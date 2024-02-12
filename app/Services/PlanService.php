<?php

namespace App\Services;

use App\Enums\SubscriptionStatusEnum;
use App\Models\Plan;
use App\Models\User;
use App\Notifications\Users\SubscriptionPaidInvoiceNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Stripe\Stripe;

class PlanService extends BaseService
{
    /**
     * @param Plan $model
     */
    public function __construct(Plan $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     * @return mixed
     * @throws IncompletePayment
     */
    public function subscribe(array $data): mixed
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $user = Auth::user();

        $planId = $data['plan_id'];
        $plan = Plan::query()->find($planId);
        $paymentMethodId = $data['payment_method_id'];

        $planChange = $user->plan_id != $planId;

        $userSubscription = $user
            ->subscriptions()
            ->where('status', '=', SubscriptionStatusEnum::ACTIVE->value)
            ->first();

        $subscription = match (true) {
            !$userSubscription => $this->createSubscription($user, $plan, $paymentMethodId),
            $userSubscription && !$planChange => $this->extendSubscription($user, $plan, $paymentMethodId, $userSubscription),
            $userSubscription && $planChange => $this->changeSubscription($user, $plan, $paymentMethodId, $userSubscription),
            default => null,
        };

        $invoice = $user->invoices()->last();
        $user->notify(new SubscriptionPaidInvoiceNotification($invoice, $plan));

        return $subscription;
    }

    /**
     * @param User $user
     * @param Plan $plan
     * @param $paymentMethodId
     * @return \Laravel\Cashier\Subscription
     * @throws IncompletePayment
     */
    public function createSubscription(User $user, Plan $plan, $paymentMethodId): \Laravel\Cashier\Subscription
    {
        $subscription = $user->newSubscription($plan->name, $plan->stripe_plan)
            ->create($paymentMethodId);

        $this->setSubscriptionEndDate($subscription, $plan);

        $this->setPlanForUser($plan, $user);

        $this->setSubscriptionPlan($subscription, $plan);

        return $subscription;
    }

    /**
     * @param User $user
     * @param Plan $plan
     * @param $paymentMethodId
     * @param $oldSubscription
     * @return \Laravel\Cashier\Subscription
     * @throws IncompletePayment
     */
    public function extendSubscription(User $user, Plan $plan, $paymentMethodId, $oldSubscription): \Laravel\Cashier\Subscription
    {
        $startDate = Carbon::parse($oldSubscription->ends_at);

        $newSubscription = $user->newSubscription($plan->name, $plan->stripe_plan)
            ->create($paymentMethodId);

        $oldSubscription->update([
            'status'              => SubscriptionStatusEnum::EXTENDED->value,
            'new_subscription_id' => $newSubscription->getKey(),
            'ends_at'             => now(),
        ]);

        $this->setSubscriptionEndDate($newSubscription, $plan, $startDate);

        $this->setPlanForUser($plan, $user);

        return $newSubscription;
    }

    /**
     * @param User $user
     * @param Plan $plan
     * @param $paymentMethodId
     * @param $oldSubscription
     * @return \Laravel\Cashier\Subscription
     * @throws IncompletePayment
     */
    public function changeSubscription(User $user, Plan $plan, $paymentMethodId, $oldSubscription): \Laravel\Cashier\Subscription
    {
        $newSubscription = $user->newSubscription($plan->name, $plan->stripe_plan)
            ->create($paymentMethodId);

        $oldSubscription->update([
            'status'              => SubscriptionStatusEnum::CHANGED->value,
            'new_subscription_id' => $newSubscription->getKey(),
            'ends_at'             => now(),
        ]);

        $this->setSubscriptionEndDate($newSubscription, $plan);

        $this->setPlanForUser($plan, $user);

        $this->setSubscriptionPlan($newSubscription, $plan);

        return $newSubscription;
    }

    /**
     * @param $subscription
     * @param Plan $plan
     * @param Carbon|null $startDate
     * @return void
     */
    public function setSubscriptionEndDate($subscription, Plan $plan, ?Carbon $startDate = null): void
    {
        $startDate = $startDate ?? now();
        $endDate = $startDate->addMonths($plan->duration_months)->toDateString();

        $subscription->update([
            'ends_at' => $endDate,
        ]);
    }

    /**
     * @param Plan $plan
     * @param User $user
     * @return void
     */
    public function setPlanForUser(Plan $plan, User $user): void
    {
        $user->update([
            'plan_id' => $plan->getKey(),
        ]);
    }

    /**
     * @param $subscription
     * @param Plan $plan
     * @return void
     */
    public function setSubscriptionPlan($subscription, Plan $plan): void
    {
        $subscription->update([
            'plan_id' => $plan->getKey(),
        ]);
    }
}
