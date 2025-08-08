<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ValidationErrorResponse extends JsonResponse
{
    public function __construct(ValidationException $exception)
    {
        $data = [
            'error' => [
                'message' => $exception->validator->errors()->first(),
                'code' => 'E_INVALID_PARAM',
            ],
        ];

        parent::__construct($data, 422);
    }
}
