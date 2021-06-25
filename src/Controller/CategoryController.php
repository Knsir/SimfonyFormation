<?php


namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CategoryController extends AbstractController
{

    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    /**
     * @param $slug
     * @ParamConverter("category", converter="ProductConverter")
     */
    public function edit(Request $request, Category $category)
    {
        $this->denyAccessUnlessGranted('ROLE_MANAGER');

        $form= $this->createForm(CategoryType::class,$category); 


        $form->handleRequest($request); 
        if($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('product_list');
        }
        return $this->render('category/edit.html.twig', ['form' => $form->createView(), 'category'=>$category]);
    }
}