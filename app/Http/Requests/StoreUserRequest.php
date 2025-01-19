<?php

namespace App\Http\Requests;

use App\Models\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:255',
                function ($attribute, $value, $fail) {
                    $users = User::where('email', $value)->where('id', '!=', $this->customerId)->first();
                    if ($users) {
                        $fail('The email address is already associated with a '.ucfirst($users->role).' list. Please use a different email address');
                    }
                }
            ],
            'address' => 'nullable|string|max:255',
            'mobile' => 'required|numeric|digits:11',
            'balance' => 'nullable|numeric',
            'product_target' => 'nullable|numeric',
            'sales_target' => 'nullable|numeric',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
        ];
    }
}
