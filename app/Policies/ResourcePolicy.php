<?php

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ResourcePolicy
{
    /**
     * Determine whether the user can update or delete the model.
     */
    public function updateOrDelete(User $user, Resource $resource): bool
    {
        return $user->id === $resource->user_id;
    }
}
