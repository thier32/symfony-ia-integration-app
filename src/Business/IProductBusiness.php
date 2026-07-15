<?php

namespace App\Business;

use App\Dto\Product\ProductCreateDto;
use App\Dto\Product\ProductDto;

interface IProductBusiness
{
    /**
     * @param ProductCreateDto $bookCreateDto
     * @return ProductDto
     */
    public function createProduct(ProductCreateDto $bookCreateDto): ProductDto;

    /**
     * @param array $searchCriteria
     * @return array
     */
    public function listProducts(array $searchCriteria=[]): array;
}
