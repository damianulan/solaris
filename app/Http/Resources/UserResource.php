<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property User $resource
 */
final class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->resource;

        return [
            'id' => $user->id,
            'fullname' => $user->firstname . ' ' . $user->lastname,
            'email' => $user->email,
            'username' => $user->username,
            'editUrl' => route('users.edit', $user),
            'isSuspended' => $user->suspended_at !== null,
            'suspendUrl' => route('users.suspensions.store', $user),
        ];
    }
}
