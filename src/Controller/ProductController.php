<?php

namespace App\Controller;


use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/{slug}', name: 'product_category')]
    public function category(string $slug, CategoryRepository $productRepository): Response
    {
        $product = $productRepository->findOneBy(["slug"=>$slug]);

        if(!$product){
            throw $this->createNotFoundException();
        }

        return $this->render('product/category.html.twig', [
            'category' => $product,
        ]);
    }
    
    #[Route('/{category_slug}/{slug}', name: 'product_details')]
    public function show(string $slug, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBy(["slug"=>$slug]);

        if(!$product){
            throw $this->createNotFoundException();
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
