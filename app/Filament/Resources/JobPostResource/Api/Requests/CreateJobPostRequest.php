<?php

namespace App\Filament\Resources\JobPostResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateJobPostRequest extends FormRequest
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
			'company' => 'required',
			'designation' => 'required',
			'job_type' => 'required',
			'salary' => 'required',
			'due_date' => 'required|date',
			'link' => 'required',
			'job_image' => 'required',
			'address' => 'required',
			'job_status' => 'required',
			'website_preview' => 'required'
		];
    }
}
