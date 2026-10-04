<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'bio' => ['nullable', 'string', 'max:500'],
            'birth_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'years_of_experience' => ['required', 'integer', 'between:0,80'],
            'timezone' => ['required', Rule::in([
                'America/New_York',
                'Asia/Tokyo',
                'Europe/London',
                'Europe/Warsaw',
            ])],
            'preferred_contact' => ['required', Rule::in(['email', 'phone'])],
            'newsletter' => ['required', 'boolean'],
            'public_profile' => ['required', 'boolean'],
            'profile_completion' => ['required', 'integer', 'between:0,100'],
            'resume' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ];
    }
}
