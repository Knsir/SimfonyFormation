<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Entity\Cart;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CartController extends AbstractController
{
    private $productRepository;
    private $cartRepository;


    public function __construct(ProductRepository $productRepository, CartRepository $cartRepository){
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;

    }

    public function addToCart($slug){
        $manager= $this->getDoctrine()->getManager();
        $user=$this->getUser();
        $product= $this->productRepository->findProductByslug($slug);

        $cart = $this->getUser()->getCart();
        $cart->addProduct($product);
        $cart->setUser($user);
        $manager->persist($cart);

        $manager->flush();

        //prepare Response

        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function($object, $format,$context){
                return $object->getId();
            }
        ];
        $encoder= new JsonEncoder();
        $normalizer= new ObjectNormalizer(null,null,null,null,null,null,$defaultContext);
        $serializer= new Serializer([$normalizer],[$encoder]);
        $jsonContent= $serializer->serialize($cart, 'json');

        $response= new JsonResponse();
        $response->setData(['cart' => $jsonContent]);
        return $response;

    }
}
