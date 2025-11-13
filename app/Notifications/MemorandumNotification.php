<?php

namespace App\Notifications;

use App\Models\Memorandum;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MemorandumNotification extends Notification
{
    use Queueable;

    public function __construct(public Memorandum $memorandum, public string $action)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $memorandum = $this->memorandum;

        $subject = $this->action === 'created'
            ? __('Nuevo memorando asignado: :title', ['title' => $memorandum->title])
            : __('Memorando actualizado: :title', ['title' => $memorandum->title]);

        $message = $this->action === 'created'
            ? __('Has sido asignado como responsable del memorando ":title".', ['title' => $memorandum->title])
            : __('Se registraron cambios en el memorando ":title".', ['title' => $memorandum->title]);

        return (new MailMessage)
            ->subject($subject)
            ->greeting(__('Hola,'))
            ->line($message)
            ->line(__('Estado actual: :status', ['status' => ucfirst(str_replace('_', ' ', $memorandum->status))]))
            ->action(__('Ver memorando'), route('company.memorandos.show', $memorandum))
            ->line(__('Gracias por usar la plataforma Centinela 360.'));
    }
}
