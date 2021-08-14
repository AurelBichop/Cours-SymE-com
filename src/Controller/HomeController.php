<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class HomeController extends AbstractController
{
    #[Route("/",name: "home")]
    public function home(ProductRepository $productRepository): Response
    {

        return $this->render('home.html.twig',[
            "products"=> $productRepository->findBy([],[],3)
        ]);
    }
}