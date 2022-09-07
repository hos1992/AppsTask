<?php

namespace App\Http\Requests\Api;

class UserRegisterRequest extends BaseApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|max:100',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6|max:100|confirmed',
            'departments' => 'required|array',
            'departments.*.id' => 'required|exists:departments,id',
            'departments.*.section_id' => 'nullable|exists:sections,id',
//            'departments.*.is_manger' => 'required|in:0,1',
        ];
    }
}
