<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenantPasswordResetNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $token,
        private readonly string $company,
        private readonly string $email,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('password.reset', ['token' => $this->token, 'company' => $this->company, 'email' => $this->email]);

        return (new MailMessage)
            ->subject('Tautan pemulihan password ERP')
            ->greeting('Halo!')
            ->line('Kami menerima permintaan untuk mengatur ulang password akun ERP Anda.')
            ->action('Atur Password Baru', $url)
            ->line('Tautan ini berlaku selama 60 menit dan hanya untuk perusahaan yang tercantum pada tautan.')
            ->line('Jika Anda tidak meminta reset password, abaikan email ini.');
    }
}
