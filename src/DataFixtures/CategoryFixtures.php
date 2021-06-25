<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use App\Service\Slugify;

class CategoryFixtures extends Fixture
{

 private $slugify;

    public function __construct(Slugify $slugify){
        $this->slugify = $slugify;
    }


    public function load(ObjectManager $manager)
    {

        for($i =1;$i<40; $i++){
            ${"category_$i"} = new Category();
            $name= 'category'.$i;
            ${"category_$i"}->setName($name);
            ${"category_$i"}->setSlug($this->slugify->slugify($name));
            ${"category_$i"}->setIsTrue(rand(0,1));
            $manager->persist(${"category_$i"});
        }

        $manager->flush();
    }
}
