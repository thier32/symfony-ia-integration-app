<?php

namespace App\Dto\Product;

use App\Dto\BaseDto;

class ProductDto extends BaseDto
{
    public ?string $createdAt = null;

    /**
     * @param int|null $productId
     * @param int|null $name
     * @param int|null $sku
     * @param string|null $description
     * @param \DateTimeInterface|string|null $createdDateAt
     */
    public function __construct(
        public ?int $productId = null,
        public ?string $name = null,
        public ?string $sku = null,
        public ?string $description = null,
        \DateTimeInterface|string|null $createdDateAt = null
    )
    {
        $this->createdAt = $createdDateAt ? $createdDateAt->format('Y-m-d H:i:s') : null;
    }

}
