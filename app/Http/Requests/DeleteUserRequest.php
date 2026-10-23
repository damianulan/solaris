<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

final class DeleteUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $authenticatedUser = $this->user();
        $targetUser = $this->route('user');

        return $authenticatedUser instanceof User
            && $targetUser instanceof User
            && ! $authenticatedUser->is($targetUser);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }
}
