<?php

namespace App\Dto\StockMovement;

use App\Dto\BaseDto;
use App\Enum\OperationType;

class StockMovementDto extends BaseDto
{
    public function __construct(
        public ?int $quantity = null,
        public ?OperationType $type = null,
        public ?int $productId = null,
        public ?string $productName = null
    )
    {
    }
}
