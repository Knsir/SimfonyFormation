<?php

namespace App\Tests;

use App\Entity\Product;
use App\Service\Slugify;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class CalculatorTest extends WebTestCase
{
    /** @var Slugify */
    private $slugify;
   
    protected function setUp():void
    {
        self::bootKernel();
        $container= self::$container;
        $this->slugify= $container->get('App\Service\Slugify');
    }
    
    public function testSomething(): void
    {
        $testvalue = 'trilou se';

        $this->assertTrue($this->slugify->slugify($testvalue)==='trilou-se');

    }
}
