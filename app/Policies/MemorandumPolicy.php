<?php

namespace App\Policies;

use App\Models\Memorandum;
use App\Models\User;

class MemorandumPolicy
{
    public function view(User $user, Memorandum $memorandum): bool
    {
        return $user->company_id === $memorandum->company_id;
    }

    public function update(User $user, Memorandum $memorandum): bool
    {
        return $user->company_id === $memorandum->company_id;
    }
}
