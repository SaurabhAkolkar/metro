<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UserRegisterValidation extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            "name" => "required",
            "email" => "required|email|unique:users",
            "gender" => "required|alpha|max:1",
            "profile_for" => "required",
            "marital_status" => "required|numeric",
            "aadhaar" => "required|numeric",
            "password" => "required",
            "blood_group" => "required|numeric",
            "dob" => "required|date",
            "birth_place" => "required",
            "birth_time" => "required",
            "height" => "required|numeric",
            "manglik" => "required|numeric",
            "qualification" => "required",
            "caste" => "required|numeric",
            //"income" => "required|numeric",
            //"work_place" => "required",
            'gotra' => "required",
            "gotra_nanihal" => "required",
            //"organisation_name" => "required",
            "city" => "required",
            "state" => "required|numeric",
            "district" => "required|numeric",
            "mobile" => "required|numeric|digits:10|unique:users",
            "pin" => "required|numeric",
            "otp" => "required|numeric|digits:4",
            "working" => "required|numeric|max:1"
        ];

    }



    protected function failedValidation(Validator $validator)
    {
       
        $response = new JsonResponse([

            'success' => false,
            'message' => $validator->errors()->first(),

        ], 200);

        throw new ValidationException($validator, $response);
    }
}
