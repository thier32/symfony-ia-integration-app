<?php

namespace App\Service\Impl;

use App\Dto\Product\ProductCreateDto;
use App\Dto\Product\ProductDto;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\IProductService;
use Symfony\Component\Serializer\SerializerInterface;

class ProductService extends BaseService implements IProductService
{
    public function __construct(private ProductRepository $productRepository,
                                public SerializerInterface $serializer
    ){

        parent::__construct(
            $this->productRepository
        );
    }


    public function createProduct(ProductCreateDto $productCreateDto): ProductDto
    {
        $product = $this->productRepository->findBy(['sku' => $productCreateDto->sku]);
        if ($product) {
            throw new \Exception(sprintf("Product already exists with %s",$productCreateDto->sku));
        }
        return $this->addEntityDto($productCreateDto,Product::class,ProductDto::class);
    }
}
