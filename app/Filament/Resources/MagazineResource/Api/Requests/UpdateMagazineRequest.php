<?php

namespace App\Filament\Resources\MagazineResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMagazineRequest extends FormRequest
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
			'title' => 'required',
			'description' => 'required|string',
			'file' => 'required',
			'file_type' => 'required',
			'status' => 'required',
			'website_preview' => 'required'
		];
    }
}
