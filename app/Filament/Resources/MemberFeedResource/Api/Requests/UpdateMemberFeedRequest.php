<?php

namespace App\Filament\Resources\MemberFeedResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMemberFeedRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'content' => 'required|string',
            'public' => 'required|boolean',
            'attachment_path' => 'nullable|array',
            'attachment_path.*' => 'file|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ];
    }

}
