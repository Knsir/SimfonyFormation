<?php 

namespace App\Service;

use App\Entity\ProductType;
use App\Repository\ProductTypeRepository;
use App\Service\Calculator;
class TVASelector
{
    private $producTypeRepository;


    public function __construct(ProductTypeRepository $productTypeRepository)
    {
        $this->producTypeRepository = $productTypeRepository;
    }


    public function getTVA(ProductType $productType)
    {
    
    }
}