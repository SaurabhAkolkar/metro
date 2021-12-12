<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ImageValidationRequest extends FormRequest
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
 * Get the error messages for the defined validation rules.
 *
 * @return array
 */
public function messages()
{
    return [
             'id_proof.image' => 'The images must be type image',
             'id_proof.max' => 'The images must be less than 4 mb',
             'id_proof.mimes' => 'The images must be one of the following types :values',
         ];
}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
             "id_proof" => 'required|image|mimes:jpg,jpeg,png,bmp|max:4096',
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
