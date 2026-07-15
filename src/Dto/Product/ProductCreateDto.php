<?php

namespace App\Dto\Product;

use App\Dto\BaseDto;

class ProductCreateDto extends BaseDto
{
    public function __construct(
        public ?string $name = null,
        public ?string $sku = null,
        public ?string $description = null
    )
    {
    }
}
