<?php

namespace App\Service\Impl;

use App\Dto\StockMovement\StockMovementCreateDto;
use App\Dto\StockMovement\StockMovementDto;
use App\Entity\Product;
use App\Entity\StockMovement;
use App\Enum\OperationType;
use App\Repository\StockMovementRepository;
use App\Service\IStockMovementService;
use App\Utils\Mapper;
use Symfony\Component\Serializer\SerializerInterface;

class StockMovementService extends BaseService implements IStockMovementService
{
    public function __construct(private readonly StockMovementRepository $stockMovementRepository,
                                private readonly ProductService          $productService,
                                public SerializerInterface               $serializer
    ){

        parent::__construct(
            $this->stockMovementRepository
        );
    }

    public function updateStock(StockMovementCreateDto $movementCreateDto): StockMovementDto
    {
        $product = $this->productService->getEntity([Product::PRODUCT_ID => $movementCreateDto->productId]);
        if(!$product){
            throw new \Exception(sprintf("Product not found with %s",$movementCreateDto->productId));
        }
        $stockMovement =  $this->updateStockProduct($product,$movementCreateDto->quantity,$movementCreateDto->type);
        return Mapper::map($stockMovement,StockMovementDto::class);
    }

    public function updateStockProduct(Product $product, int $quantityChange, OperationType $type): StockMovement
    {
        // 1. Prevent negative stock if that's a business rule
        if ($product->getCurrentStock() + $quantityChange < 0) {
            throw new \Exception("Cannot reduce stock. Insufficient quantity available.");
        }

        // 2. Log the movement
        $movement = new StockMovement();
        $movement->setProduct($product);
        $movement->setQuantity($quantityChange);
        $movement->setType($type);

        $withdraw =[OperationType::ORDER_SALE];
        $deposit = [OperationType::REFILL_PRODUCT,OperationType::INCOMING_SHIPMENT];

        if (in_array($type,$withdraw)){
            $product->removeStock($quantityChange);
        }

        if (in_array($type,$deposit)){
            $product->addStock($quantityChange);
        }

        $this->productService->updateEntity($product);

        $this->stockMovementRepository->save($movement);


        return $movement;
    }
}
