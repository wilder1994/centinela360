<?php

namespace App\Events;

use App\Models\Memorandum;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MemorandumCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Memorandum $memorandum)
    {
    }
}
