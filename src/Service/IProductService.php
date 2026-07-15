<?php

namespace App\Service;

use App\Dto\Product\ProductCreateDto;
use App\Dto\Product\ProductDto;

interface IProductService extends IBaseService
{
    public function createProduct(ProductCreateDto $productCreateDto): ProductDto;
}
