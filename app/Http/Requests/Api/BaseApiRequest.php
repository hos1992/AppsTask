<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class BaseApiRequest extends FormRequest
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
            //
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator$validator)
    {
        $errors = [];
        $errorsStr = "";
        $errorsArr = [];
        foreach ($validator->errors()->toArray() as $key => $error) {
            $errors[$key] = $error[0];
            $errorsStr .= $error[0] . "\n";
            array_push($errorsArr, $error[0]);
        }
        $response = new JsonResponse([
            'status' => 0,
            'errors' => $errors,
            'message' => $errorsStr,
            'errorsArr' => $errorsArr,
        ], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }

}
