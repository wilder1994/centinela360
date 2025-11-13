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
            ? __('Nuevo memorando asignado: :title', ['title' => $memorandum->subject])
            : __('Memorando actualizado: :title', ['title' => $memorandum->subject]);

        $message = $this->action === 'created'
            ? __('Has sido asignado como responsable del memorando ":title".', ['title' => $memorandum->subject])
            : __('Se registraron cambios en el memorando ":title".', ['title' => $memorandum->subject]);

        return (new MailMessage)
            ->subject($subject)
            ->greeting(__('Hola,'))
            ->line($message)
            ->line(__('Estado actual: :status', ['status' => $memorandum->status?->label() ?? '']))
            ->action(__('Ver memorando'), route('company.memorandums.show', $memorandum))
            ->line(__('Gracias por usar la plataforma Centinela 360.'));
    }
}
