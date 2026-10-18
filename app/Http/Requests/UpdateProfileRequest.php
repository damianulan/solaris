<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use RahulHaque\Filepond\Rules\FilepondRule;

final class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() instanceof User;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $availableLocales = config('app.available_locales', []);

        return [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'username' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique(User::TABLE, 'username')->ignore($this->user()),
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique(User::TABLE, 'email')->ignore($this->user()),
            ],
            'bio' => ['nullable', 'string', 'max:500'],
            'lang' => ['required', 'string', Rule::in(is_array($availableLocales) ? $availableLocales : [])],
            'timezone' => ['required', 'timezone:all'],
            'avatar' => [
                'nullable',
                new FilepondRule(['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048']),
            ],
        ];
    }

    /** @return array<string, mixed> */
    public function profileAttributes(): array
    {
        return $this->safe()->only([
            'firstname',
            'lastname',
            'middlename',
            'username',
            'email',
            'bio',
            'lang',
            'timezone',
            'avatar',
        ]);
    }
}
