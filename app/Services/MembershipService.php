<?php

namespace App\Services;

use App\Models\BaseModel;
use App\Models\Membership;
use App\Models\MembershipType;
use App\Models\User;
use App\Notifications\Users\GymOwnerNewMembershipNotification;
use App\Notifications\Users\MembershipPaidInvoiceNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class MembershipService extends BaseService
{
    /**
     * @param Membership $model
     */
    public function __construct(Membership $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     * @return BaseModel|User
     * @throws IncompletePayment|ApiErrorException
     */
    public function store(array $data): BaseModel|User
    {
        $membershipType = MembershipType::query()->findOrFail($data['membership_type_id']);
        $user = Auth::user();

        $data = $this->payMembership($data, $membershipType, $user);

        $data = $this->resolveFields($data, $membershipType);

        $model = $this->model->create($data);

        $this->sendNotifications($model, $membershipType, $user);

        return $this->show($model);
    }

    /**
     * @param array $data
     * @param MembershipType $membershipType
     * @param User $user
     * @return array
     * @throws ApiErrorException
     * @throws IncompletePayment
     */
    public function payMembership(array $data, MembershipType $membershipType, User $user): array
    {
        if (config('app.env') == 'testing') {
            return $data;
        }

        Stripe::setApiKey(config('app.stripe_secret_key'));

        $paymentMethodId = $data['payment_method_id'];

        $amount = $membershipType->price * 100;
        $description = 'Membership charge';

        $this->createUserAsStripeCustomer($user, $paymentMethodId);
        $currency = config('app.stripe_currency');

        $charge = $user->charge($amount, $paymentMethodId, [
            'currency'      => $currency,
            'description'   => $description,
            'receipt_email' => $user->email,
        ]);

        $data['charge_id'] = $charge->latest_charge;

        return $data;
    }

    /**
     * @param User $user
     * @param $paymentMethodId
     * @return void
     * @throws ApiErrorException
     */
    public function createUserAsStripeCustomer(User $user, $paymentMethodId): void
    {
        if ($user->stripe_id) {
            return;
        }

        $customer = Customer::create([
            'email'          => $user->email,
            'name'           => $user->full_name,
            'payment_method' => $paymentMethodId,
        ]);

        $user->stripe_id = $customer->id;
        $user->updateDefaultPaymentMethod($paymentMethodId);
        $user->save();
    }

    /**
     * @param array $data
     * @param MembershipType $membershipType
     * @return array
     */
    public function resolveFields(array $data, MembershipType $membershipType): array
    {
        $data = $this->resolveEndDate($data, $membershipType);
        $data = $this->resolveOriginalMembershipTypeModel($data, $membershipType);
        $data = $this->resolveGymId($data, $membershipType);
        $data = $this->resolveName($data, $membershipType);

        return $data;
    }

    /**
     * @param array $data
     * @param MembershipType $membershipType
     * @return array
     */
    public function resolveEndDate(array $data, MembershipType $membershipType): array
    {
        $endDate = Carbon::parse($data['start_date'])->addWeeks($membershipType->duration_weeks)->toDateString();

        $data['end_date'] = $endDate;

        return $data;
    }

    /**
     * @param array $data
     * @param MembershipType $membershipType
     * @return array
     */
    public function resolveOriginalMembershipTypeModel(array $data, MembershipType $membershipType): array
    {
        $data['original_membership_type_model'] = json_encode($membershipType);

        return $data;
    }

    /**
     * @param array $data
     * @param MembershipType $membershipType
     * @return array
     */
    public function resolveGymId(array $data, MembershipType $membershipType): array
    {
        $data['gym_id'] = $membershipType->gym_id;

        return $data;
    }

    /**
     * @param array $data
     * @param MembershipType $membershipType
     * @return array
     */
    public function resolveName(array $data, MembershipType $membershipType): array
    {
        $data['name'] = $membershipType->gym->name . ' - ' . $membershipType->name;

        return $data;
    }

    /**
     * @param Membership $membership
     * @param MembershipType $membershipType
     * @param User $member
     * @return void
     */
    public function sendNotifications(Membership $membership, MembershipType $membershipType, User $member): void
    {
        $membership->gym->owner->notify(new GymOwnerNewMembershipNotification($member, $membershipType));

        $member->notify(new MembershipPaidInvoiceNotification($membershipType));
    }
}
