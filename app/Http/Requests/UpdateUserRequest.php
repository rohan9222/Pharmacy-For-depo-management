<?php

namespace App\Http\Requests;

use App\Models\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => 'required|string|max:250',
            // 'email' => 'required|string|email:rfc,dns|max:250|unique:users,email,'.$this->user->id,
            'email' => ['required', 'email:rfc,dns', 'max:255',
                function ($attribute, $value, $fail) {
                    $users = User::where('email', $value)->where('id', '!=', $this->user->id)->first();
                    if ($users) {
                        $fail('The email address is already associated with a '.ucfirst($users->role).' list. Please use a different email address');
                    }
                }
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required'
        ];
    }
}
