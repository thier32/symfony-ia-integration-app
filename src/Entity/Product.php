<?php
namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;


#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product extends BaseEntity
{
    public const PRODUCT_ID = 'productId';
    public const PRODUCT_NAME = 'name';

    #[ORM\Column(type: Types::BIGINT,unique: true)]
    #[Groups("product_read")]
    private ?int $productId = null;

    #[ORM\Column(length: 255)]
    #[Groups("product_read")]
    private ?string $name = null;


    #[ORM\Column]
    private int $currentStock = 0;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: StockMovement::class, cascade: ['remove'])]
    private Collection $stockMovements;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $sku = null;


    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups("product_read")]
    private ?\DateTimeInterface $publishedAt = null;


    #[ORM\Column]
    #[\Symfony\Component\Serializer\Annotation\Groups(['product_read'])]
    #[OA\Property(description: "Indique si le produit est disponible.", example: true)]
    private bool $available = true;


    public function __construct()
    {
        $this->stockMovements = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }


    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getProductId(): ?int
    {
        return $this->productId;
    }

    /**
     * @param int|null $productId
     * @return Product
     */
    final public function setProductId(?int $productId): static
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentStock(): int
    {
        return $this->currentStock;
    }

    /**
     * @param int $currentStock
     */
    public function setCurrentStock(int $currentStock): static
    {
        $this->currentStock = $currentStock;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     * @param string|null $sku
     */
    public function setSku(?string $sku): static
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * @return bool
     */
    final public function isAvailable(): bool
    {
        return $this->available;
    }

    /**
     * @param bool $available
     */
    final public function setAvailable(bool $available): static
    {
        $this->available = $available;
        return $this;
    }


    /**
     * Internal method to safely update the cached stock count.
     */
    public function addStock(int $quantity): static
    {
        $this->currentStock += $quantity;
        return $this;
    }

    public function removeStock(int $quantity): static
    {
        $this->currentStock -= $quantity;
        return $this;
    }

    /**
     * @return Collection<int, StockMovement>
     */
    public function getStockMovements(): Collection
    {
        return $this->stockMovements;
    }
}
