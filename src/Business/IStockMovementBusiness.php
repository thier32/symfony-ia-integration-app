<?php

namespace App\Business;

use App\Dto\StockMovement\StockMovementCreateDto;
use App\Dto\StockMovement\StockMovementDto;

interface IStockMovementBusiness
{
    /**
     * @param StockMovementCreateDto $movementCreateDto
     * @return StockMovementDto
     */
    public function receiveStock(StockMovementCreateDto $movementCreateDto): StockMovementDto;

    /**
     * @param StockMovementCreateDto $movementCreateDto
     * @return StockMovementDto
     */
    public function sellStock(StockMovementCreateDto $movementCreateDto): StockMovementDto;

}
