<?php 
namespace App\Command;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
class CreateSale extends Command
{
    protected static $defaultName = 'app:create_sale';
    private $productRepository;
    private $entityManager;
    private $categoryRepository;
    public function __construct(ProductRepository $productRepository, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository)
    {
        parent::__construct();
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
        $this->categoryRepository = $categoryRepository;
    }


    protected function configure()
    {
        $this
        ->setDescription("Create Sale to All Products")
            ->setHelp('This command will aplly a 10% sale to all products')
            ->addArgument('pourcentage', InputArgument::REQUIRED, 'The Percentage of the sale')
            ->addOption('category',null,InputOption::VALUE_REQUIRED,'category of product');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(
            [
                'Applyin Sale ',
                '============='
            ]
            );

            $output->writeln('Pourcentage:' . $input->getArgument('pourcentage'));
            $output->writeln('Category du produit'.$input->getOption('category'));
            $pourcentage=  $input->getArgument('pourcentage');
            $category= $input->getOption('category');
            if(!is_null($category)){
                $products = $this->productRepository->findAll();
            }else{
                $categoryType=$this->categoryRepository->findOneBy(['name'=>$category]);
               dd($categoryType->getProducts());
                $products= $this->productRepository->findBy(['category' =>$categoryType]);
            }
        foreach($products as $product)
        {
            $rabais= 1-($pourcentage/100);
            $product=$product->setPrice($product->getPrice()*$rabais);
            $em = $this->entityManager;
            $em->persist($product);
                    
        }
        $em->flush();
        return Command::SUCCESS;
    }
}