<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            return url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));
        });

        ResetPassword::toMailUsing(function ($notifiable, string $url) {
            return (new MailMessage)
                ->from(config('mail.from.address'), config('mail.from.name')) // có thể đổi FROM tại đây
                ->subject('Khôi phục mật khẩu - DuManMinh Cinema')
                ->greeting('Xin chào '.($notifiable->username ?? 'bạn').'!')
                ->line('Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.')
                ->action('Đặt lại mật khẩu', $url)
                ->line('Liên kết có hiệu lực trong '.config('auth.passwords.users.expire').' phút.')
                ->line('Nếu không phải bạn yêu cầu, bạn có thể bỏ qua email này.')
                ->salutation('Trân trọng, DuManMinh Cinema');
        });
    }
}