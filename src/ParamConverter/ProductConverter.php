<?php

namespace App\ParamConverter;

use App\Repository\CategoryRepository;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductConverter implements ParamConverterInterface
{
     private $categoryRepository; 

     public function __construct(CategoryRepository $categoryRepository){
         $this->categoryRepository = $categoryRepository;
     }

    

    public function apply(Request $request, ParamConverter $configuration)
    {
        //on recupere le slug 

        $slug=$request->get('slug');
        

        if(substr($slug,0,1)=='p'){
            $slug= substr($slug, 1);
        }

        $category= $this->categoryRepository->findOneBy(['slug'=> $slug]);
        $request->attributes->set('category', $category);
        return true;
    }


    public function supports(ParamConverter $configuration)
    {
        return $configuration->getName() === 'category';
    }

}