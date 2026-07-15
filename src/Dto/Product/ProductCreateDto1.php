<?php

namespace App\Dto\Product;

use App\Dto\BaseDto;

class ProductCreateDto1 extends BaseDto
{
    public function __construct(
        public ?string $accountNumber = null
    )
    {

    }
}
