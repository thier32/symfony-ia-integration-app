<?php
namespace App\Controller;

use App\Dto\StockMovement\StockMovementCreateDto;
use App\Response\StockMovementResponseDto;

interface IStockController
{


    /**
     * @param StockMovementCreateDto $movementCreateDto
     * @return StockMovementResponseDto
     */
    public function sellStockProduct(StockMovementCreateDto $movementCreateDto): StockMovementResponseDto;

    /**
     * @param StockMovementCreateDto $movementCreateDto
     * @return StockMovementResponseDto
     */
    public function receiveStockProduct(StockMovementCreateDto $movementCreateDto): StockMovementResponseDto;

}
