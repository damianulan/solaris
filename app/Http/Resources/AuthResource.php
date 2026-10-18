<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin User */
final class AuthResource extends JsonResource
{
    /**
     * @return array{
     *     id: int,
     *     name: string,
     *     email: string|null,
     *     avatarUrl: string|null,
     *     profileUrl: string,
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => trim("{$this->firstname} {$this->lastname}"),
            'email' => $this->email,
            'avatarUrl' => $this->avatar === null
                ? null
                : Storage::disk((string) config('filepond.disk', 'public'))->url($this->avatar),
            'profileUrl' => route('profile.edit'),
        ];
    }
}
