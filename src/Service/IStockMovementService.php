<?php

namespace App\Service;

use App\Dto\StockMovement\StockMovementCreateDto;
use App\Dto\StockMovement\StockMovementDto;
use App\Entity\Product;
use App\Entity\StockMovement;
use App\Enum\OperationType;

interface IStockMovementService extends IBaseService
{

    /**
     * Adjusts the stock of a product and logs the movement.
     * * @throws \Exception If stock drops below zero.
     */
    public function updateStockProduct(Product $product, int $quantityChange, OperationType $type): StockMovement;

    /**
     * @param StockMovementCreateDto $movementCreateDto
     * @return StockMovementDto
     */
    public function updateStock(StockMovementCreateDto $movementCreateDto): StockMovementDto;

}
