<?php

namespace App\Listeners;

use App\Events\MemorandumCreated;
use App\Events\MemorandumUpdated;
use App\Notifications\MemorandumNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMemorandumResponsible implements ShouldQueue
{
    public function handle(MemorandumCreated|MemorandumUpdated $event): void
    {
        $memorandum = $event->memorandum->loadMissing(['responsible', 'company']);

        if ($memorandum->responsible) {
            $action = $event instanceof MemorandumCreated ? 'created' : 'updated';
            $memorandum->responsible->notify(new MemorandumNotification($memorandum, $action));
        }
    }
}
