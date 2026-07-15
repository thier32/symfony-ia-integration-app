<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class MemberResponseDto extends ResponseDto
{
    public function __construct(mixed $data,string $message="Success",int $status = JsonResponse::HTTP_OK){

        parent::__construct($data,$message, $status);
    }
}
