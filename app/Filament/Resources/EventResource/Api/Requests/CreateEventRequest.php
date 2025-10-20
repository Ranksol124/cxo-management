<?php

namespace App\Filament\Resources\EventResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEventRequest extends FormRequest
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
			'user_id' => 'required',
			'title' => 'required',
			'description' => 'required|string',
			'start_date' => 'required|date',
			'end_date' => 'required|date',
			'link' => 'required',
			'event_status' => 'required',
			'event_type' => 'required',
			'event_image' => 'required',
			'website_preview' => 'required'
		];
    }
}
