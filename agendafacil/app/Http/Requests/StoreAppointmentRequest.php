<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'service_id' => ['required', 'exists:services,id'],
            'professional_id' => ['required', 'exists:professionals,id'],
            'start_at' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'service_id.required' => 'Selecione um serviço.',
            'service_id.exists' => 'O serviço selecionado não existe.',
            'professional_id.required' => 'Selecione um profissional.',
            'professional_id.exists' => 'O profissional selecionado não existe.',
            'start_at.required' => 'Selecione um horário.',
            'start_at.date' => 'O horário informado é inválido.',
        ];
    }
}
