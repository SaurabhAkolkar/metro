<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UpdateRequest extends FormRequest
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
            'user_id' => "numeric",
            "gender" => "alpha|max:1",
            "marital_status" => "numeric",
            "aadhaar" => "numeric",
            "blood_group" => "numeric",
            "dob" => "date",
            "height" => "numeric",
            "manglik" => "numeric",
            "occupation" => "numeric",
            "income" => "numeric",
            "state" => "numeric",
            "district" => "numeric",
            "pin" => "numeric",
            "whatsapp_no" => "numeric|digits:10",
            "mobile2" => "numeric|digits:10",
            "father_email" => "email",
            "critical" => "numeric|digits:1"
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
