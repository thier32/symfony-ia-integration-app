<?php

namespace App\Entity;

use App\Enum\OperationType;
use App\Repository\StockMovementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockMovementRepository::class)]
class StockMovement extends BaseEntity
{

    #[ORM\ManyToOne(inversedBy: 'stockMovements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;


    private ?string $productName = null;

    #[ORM\Column]
    private ?int $quantity = null; // Can be positive (addition) or negative (reduction)

    #[ORM\Column(length: 100)]
    private ?OperationType $type = null; // e.g., 'INCOMING_SHIPMENT', 'ORDER_SALE', 'MANUAL_ADJUSTMENT'


    public function __construct()
    {
    }

    // Getters and Setters...

    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $product): static { $this->product = $product; return $this; }
    public function getQuantity(): ?int { return $this->quantity; }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return OperationType|null
     */
    public function getType(): ?OperationType
    {
        return $this->type;
    }

    /**
     * @param OperationType|null $type
     */
    public function setType(?OperationType $type): static
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getProductName(): ?string
    {
        $this->productName = $this->product ? $this->product->getName() : null;
        return $this->productName;
    }

    /**
     * @param string|null $productName
     */
    public function setProductName(?string $productName): static
    {
        $this->productName = $productName;
        return $this;
    }

}
