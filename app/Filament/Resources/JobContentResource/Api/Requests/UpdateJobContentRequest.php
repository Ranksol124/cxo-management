<?php

namespace App\Filament\Resources\JobContentResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobContentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'members_id' => 'required',
			'jobs_id' => 'required',
			'cv_upload' => 'required'
		];
    }
}
