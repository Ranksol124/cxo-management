<?php

namespace App\Filament\Resources\SpotlightResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSpotlightRequest extends FormRequest
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
			'plan_id' => 'required',
			'name' => 'required',
			'email' => 'required',
			'profile_picture' => 'required',
			'contact' => 'required',
			'address' => 'required',
			'email_verified_at' => 'required',
			'password' => 'required',
			'remember_token' => 'required',
			'spotlight' => 'required'
		];
    }
}
