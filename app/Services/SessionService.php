<?php

namespace App\Services;

use App\Models\BaseModel;
use App\Models\Membership;
use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class SessionService extends BaseService
{
    /**
     * @param Session $model
     */
    public function __construct(Session $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     * @return BaseModel|User
     */
    public function store(array $data): BaseModel|User
    {
        $session = $this->model->create($data);

        $this->setOrUnsetMembershipActiveSession($session);

        return $this->show($session);
    }

    /**
     * @param BaseModel|User $session
     * @param array $data
     * @return Model|Collection|Builder|array|Builder[]
     */
    public function update(BaseModel|User $session, array $data): Model|Collection|Builder|array
    {
        $session->update($data);

        $this->setOrUnsetMembershipActiveSession($session, false);

        return $this->show($session);
    }

    /**
     * @param Session $session
     * @param bool $setActiveSession
     * @return Session
     */
    public function setOrUnsetMembershipActiveSession(Session $session, bool $setActiveSession = true): Session
    {
        $membership = $session->membership();

        $activeSessionIdValue = $setActiveSession ? $session->getKey() : null;

        $membership->update([
            'active_session_id' => $activeSessionIdValue
        ]);

        return $session;
    }
}
