<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\ThemeVariant;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateThemeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'variant' => ['required', Rule::enum(ThemeVariant::class)],
        ];
    }

    public function themeVariant(): ThemeVariant
    {
        return ThemeVariant::from($this->string('variant')->toString());
    }
}
