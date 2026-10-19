<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

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
     *     logoutUrl: string,
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => trim("{$this->firstname} {$this->lastname}"),
            'email' => $this->email,
            'avatarUrl' => $this->avatarUrl(),
            'profileUrl' => route('profile.edit'),
            'logoutUrl' => route('logout'),
        ];
    }

    private function avatarUrl(): ?string
    {
        if (! is_string($this->avatar)) {
            return null;
        }

        $diskName = config('filepond.disk', 'public');
        $disk = Storage::disk(is_string($diskName) ? $diskName : 'public');

        if (! $disk instanceof FilesystemAdapter) {
            throw new RuntimeException('The avatar disk must support URL generation.');
        }

        return $disk->url($this->avatar);
    }
}
