<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class AdminCreatedUserVerification extends Notification
{
    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = URL::temporarySignedRoute(
            'admin.user.verify',
            now()->addHours(72),
            [
                'id'   => $notifiable->id,
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        return (new MailMessage)
            ->subject('E-posta Adresinizi Doğrulayın')
            ->greeting('Merhaba, ' . $notifiable->name . '!')
            ->line('Yönetici tarafından hesabınız oluşturuldu. E-posta adresinizi doğrulamak için aşağıdaki butona tıklayın.')
            ->action('E-postayı Doğrula', $url)
            ->line('Bu bağlantı **72 saat** geçerlidir.')
            ->line('Eğer bu hesabı siz talep etmediyseniz bu e-postayı dikkate almayınız.');
    }
}
