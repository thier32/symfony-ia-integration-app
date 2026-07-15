<?php

namespace App\Business\Impl;

use App\Business\IStockMovementBusiness;
use App\Dto\StockMovement\StockMovementCreateDto;
use App\Dto\StockMovement\StockMovementDto;
use App\Enum\OperationType;
use App\Service\IN8nNotifier;
use App\Service\IStockMovementService;


class StockMovementBusiness implements IStockMovementBusiness
{
    public function __construct(private readonly IStockMovementService
                                $movementService,
                                private readonly IN8nNotifier $notifier,
    ){

    }


    public function receiveStock(StockMovementCreateDto $movementCreateDto): StockMovementDto
    {
        $movementCreateDto->type = OperationType::REFILL_PRODUCT;
        $result = $this->movementService->updateStock($movementCreateDto);
        $this->notifier->notify();
        return $result;
    }

    public function sellStock(StockMovementCreateDto $movementCreateDto): StockMovementDto
    {
        $movementCreateDto->type = OperationType::ORDER_SALE;
        $result =$this->movementService->updateStock($movementCreateDto);
        $this->notifier->notify();
        return $result;
    }
}
