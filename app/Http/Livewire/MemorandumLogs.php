<?php

namespace App\Http\Livewire;

use App\Models\Memorandum;
use Livewire\Component;

class MemorandumLogs extends Component
{
    public Memorandum $memorandum;

    protected $listeners = ['memorandumUpdated' => '$refresh'];

    public function render()
    {
        return view('livewire.memorandum-logs', [
            'logs' => $this->memorandum->logs()->with('user')->latest()->get(),
        ]);
    }
}
