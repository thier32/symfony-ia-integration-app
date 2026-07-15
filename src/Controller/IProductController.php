<?php
namespace App\Controller;

use App\Dto\Product\ProductCreateDto;
use App\Response\ProductResponseDto;

interface IProductController
{
    /**
     * @param ProductCreateDto $productCreateDto
     * @return ProductResponseDto
     */
    public function createProduct(ProductCreateDto $productCreateDto): ProductResponseDto;


    /**
     * @param array $searchCriteria
     * @return ProductResponseDto
     */
    public function listProduct(
        array $searchCriteria = []): ProductResponseDto;

}
