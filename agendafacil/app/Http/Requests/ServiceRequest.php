<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:480'],
            'price' => ['required', 'numeric', 'min:0'],
            'active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Informe o nome do serviço.',
            'name.max' => 'O nome deve ter no máximo 120 caracteres.',
            'duration_minutes.required' => 'Informe a duração do serviço.',
            'duration_minutes.min' => 'A duração mínima é de 5 minutos.',
            'duration_minutes.max' => 'A duração máxima é de 480 minutos.',
            'price.required' => 'Informe o preço do serviço.',
            'price.min' => 'O preço não pode ser negativo.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'active' => $this->boolean('active'),
        ]);
    }
}
