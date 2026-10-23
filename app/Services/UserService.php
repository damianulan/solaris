<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RahulHaque\Filepond\Facades\Filepond;
use RuntimeException;
use Throwable;

final class UserService
{
    public function delete(User $user): void
    {
        $user->delete();
    }

    public function suspend(User $user): User
    {
        $user->update(['suspended_at' => now()]);

        return $user->refresh();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function updateProfile(User $user, array $attributes): User
    {
        $avatarUpload = Arr::pull($attributes, 'avatar');
        $locale = Arr::pull($attributes, 'lang');
        $previousAvatar = $user->avatar;
        $storedAvatar = null;

        try {
            if (is_string($locale)) {
                $attributes['locale'] = $locale;
            }

            if (is_string($avatarUpload)) {
                $storedAvatar = $this->storeAvatar($user, $avatarUpload);
                $attributes['avatar'] = $storedAvatar;
            }

            $user->fill($attributes);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();
        } catch (Throwable $exception) {
            if ($storedAvatar !== null) {
                Storage::disk($this->avatarDisk())->delete($storedAvatar);
            }

            throw $exception;
        }

        if ($storedAvatar !== null && $previousAvatar !== null) {
            Storage::disk($this->avatarDisk())->delete($previousAvatar);
        }

        return $user->refresh();
    }

    private function storeAvatar(User $user, string $avatarUpload): string
    {
        $file = Filepond::field($avatarUpload)->moveTo(
            "avatars/{$user->id}/" . Str::uuid(),
            $this->avatarDisk(),
            'public',
        );

        if (! is_array($file) || ! is_string($file['location'] ?? null)) {
            throw new RuntimeException('The avatar could not be stored.');
        }

        return $file['location'];
    }

    private function avatarDisk(): string
    {
        $disk = config('filepond.disk', 'public');

        return is_string($disk) ? $disk : 'public';
    }
}
