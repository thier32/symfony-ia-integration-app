<?php

namespace App\Dto;

class CriteriaDto extends BaseDto
{
    public function __construct(
        public ?array $criteria = []
    ){}
}
