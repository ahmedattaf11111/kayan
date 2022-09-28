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

    public function getBiggestClientDiscountProducts(
        $categoryId,
        $categoryLevel,
        $name,
        $effectiveMaterial,
        $pharmacologicalFormId,
        $companyId,
        $supplierId,
        $discount,
        $pageSize
    ) {
        return $this->productRepository
            ->getBiggestClientDiscountProducts(
                $categoryId,
                $categoryLevel,
                $name,
                $effectiveMaterial,
                $pharmacologicalFormId,
                $companyId,
                $supplierId,
                $discount,
                $pageSize
            );
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
