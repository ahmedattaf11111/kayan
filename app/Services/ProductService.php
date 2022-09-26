<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
    private $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getBiggestClientDiscountProducts($categoryId, $mainCategory, $pageSize)
    {
        return $this->productRepository
            ->getBiggestClientDiscountProducts($categoryId, $mainCategory, $pageSize);
    }

    public function getMainWithSubCategories()
    {
        return $this->productRepository->getMainWithSubCategories();
    }
    public function getDealProducts($limit)
    {
        return $this->productRepository->getDealProducts($limit);
    }
}
