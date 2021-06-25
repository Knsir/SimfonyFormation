<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Product;
use App\Service\Slugify;
use App\Repository\CategoryRepository;
use App\Repository\ProductTypeRepository;

class ProductFixtures extends Fixture implements DependentfixtureInterface
{
    private $slugify;
    private $categoryRepository;
    private $productTypeRepository;

    public function __construct(Slugify $slugify, CategoryRepository $categoryRepository, ProductTypeRepository $productTypeRepository){
        $this->slugify = $slugify;
        $this->categoryRepository = $categoryRepository;
        $this->productTypeRepository = $productTypeRepository;
    }

    public function load(ObjectManager $manager)
    {
        $categories= $this->categoryRepository->findAll();
        $productTypes= $this->productTypeRepository->findAll();
        for($i =1;$i<40; $i++){
            $product = new Product();
            $product->setPrice(rand(3,50));
            $name= 'product'.$i;
            $product->setName($name);
            $product->setSlug($this->slugify->slugify($name));
            $product->setDescription('ceci est le '. $i.' produit');

            $cat_id = $categories[array_rand($categories)];
            $product->setCategory($cat_id);

            $productType_id = $productTypes[array_rand($productTypes)];
            $product->setProductType($productType_id);
            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies(){

        return [CategoryFixtures::class];
    }
}
