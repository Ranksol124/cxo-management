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
			'description' => 'nullable|string',
			'company' => 'required',
			'designation' => 'required',
			'job_type' => 'nullable',
			'salary' => 'nullable',
			'due_date' => 'required|date',
			'link' => 'nullable',
            'job_image' => 'file|mimes:jpg,jpeg,png,gif,webp|max:2048',
			'address' => 'nullable',
			'job_status' => 'nullable',
			'website_preview' => 'nullable'
		];
    }
}
