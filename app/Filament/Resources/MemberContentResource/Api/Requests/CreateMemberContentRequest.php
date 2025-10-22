<?php

namespace App\Filament\Resources\MemberContentResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMemberContentRequest extends FormRequest
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
            'member_id' => 'required',
            'title' => 'required',
            'description' => 'required|string',
            'status' => 'required',
            'content_type' => 'required',
            'news_type' => 'required',
            'file_path' => 'file|mimes:jpg,jpeg,png,gif,webp|max:2048'
        ];
    }
}
