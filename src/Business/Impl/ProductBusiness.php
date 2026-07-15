<?php

namespace App\Business\Impl;

use App\Business\IProductBusiness;
use App\Dto\Product\ProductCreateDto;
use App\Dto\Product\ProductDto;
use App\Service\IProductService;


class ProductBusiness implements IProductBusiness
{
    public function __construct(private readonly IProductService $productService){}

    public function createProduct(ProductCreateDto $bookCreateDto): ProductDto
    {
        return $this->productService->createProduct($bookCreateDto);
    }

    public function listProducts(array $searchCriteria = []): array
    {
        return $this->productService->getEntities(ProductDto::class,$searchCriteria);
    }
}
