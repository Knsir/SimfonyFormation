<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


use App\Service\Calculator;
use App\Service\TVASelector;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Service\Slugify;

class ProductController extends AbstractController
{

    private $tvaSelector; 
    private $calculator;
    private $productRepository;
    private $slugify;
    

    public function __construct(Calculator $calculator, TVASelector $tvaSelector, ProductRepository $productRepository, Slugify $slugify){
        $this->calculator = $calculator;
        $this->tvaSelector = $tvaSelector;
        $this->productRepository = $productRepository;
        $this->slugify = $slugify;
    }

    public function index(Request $request, $slug){
        return new Response('<body> blabla</body>');
    }


    public function list($page){
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', 
        ['page' => $page, 
        'products'=>$products]);
    }

    public function show(Request $request, $slug, Calculator $calcu){
       // return  new Response('test2');
       $product= $this->productRepository->findProductByslug($slug);
       $prix=$product->getPrice();
        return $this->render('product/product.html.twig', 
        ['slug' => $slug,
        'price'=>$prix, 
        'priceTVA'=> $calcu->calculTVA($prix), 
        'priceTTC'=> $calcu->calculTTC($prix)]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function edit(Request $request, Product $product){
        $this->denyAccessUnlessGranted('ROLE_MANAGER');

        /*$form= $this->createFormBuilder()
            ->add("name", TextType::class)
            ->add("description", TextareaType::class)
            ->add('price', TextType::class)
            ->add('valider', SubmitType::class);*/

        //$product= $this->productRepository->findOneById($id);
        $form= $this->createForm(ProductType::class,$product); 


        $form->handleRequest($request); 
        if($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('product_show',['slug'=> $product->getSlug()]);
        }
        return $this->render('product/edit.html.twig', ['form' => $form->createView(), 'product'=>$product]);



    }


    public function create(Request $request){
        $form= $this->createForm(ProductType::class); 


        $form->handleRequest($request); 
        if($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();
            $product->setSlug($this->slugify->slugify($product->getName()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('product_show',['slug'=> $product->getSlug()]);
        }
        return $this->render('product/edit.html.twig', ['form' => $form->createView()]);

    }
}