<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseDto extends JsonResponse
{
    public function __construct(mixed $data,string $message="Success",int $status = JsonResponse::HTTP_OK){
        $formattedData = [
            'code'    => $status,
            'message' => $message,
            'result'  => $data
        ];
        parent::__construct($formattedData, $status);
    }
}
