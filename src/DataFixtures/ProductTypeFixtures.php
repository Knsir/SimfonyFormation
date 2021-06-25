<?php

namespace App\DataFixtures;
use App\Entity\ProductType;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types = [
            ['classical_goods','Biens et services classiques',20],
            ['natural_goods','produits agricoles non transfo, bois, travaux logement',10],
            ['food','produits alimentaires, énergies, repas, culture',5.5],
        ];
        foreach($types as $type ){
         $productType = new ProductType();
         $productType->setName($type[0]);
         $productType->setLabel($type[1]);
         $productType->setTaux($type[2]);
         $manager->persist($productType);
    }
        $manager->flush();
    }
}
