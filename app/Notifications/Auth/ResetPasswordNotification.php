<?php

namespace App\Notifications\Auth;

use Illuminate\Auth\Notifications\ResetPassword;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ResetPasswordNotification extends ResetPassword
{
    /**
     * @param $notifiable
     * @return string
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function resetUrl($notifiable): string
    {
        return config('urls.frontend_reset_password_url') . '?resetToken=' . $this->token . '&email=' . urlencode(request()->get('email'));
    }
}
