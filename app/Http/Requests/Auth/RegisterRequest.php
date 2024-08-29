<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class RegisterRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'phone'=>'required',
            'profile_picture' => 'nullable|image',
            'certificate' => 'file|mimes:pdf,doc,docx'
        ];
    }
//    protected function failedValidation(Validator $validator)
//    {
//
//        throw new HttpResponseException(response([
//            'status' => 'error',
//            'message' => null,
//            'data' => $validator->errors()
//        ], Response::HTTP_BAD_REQUEST));
//
//    }
}
