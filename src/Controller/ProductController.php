<?php

namespace App\Controller;


use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{
    #[Route('/admin/product/create', name: 'product_create')]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger):Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class,$product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $product->setSlug($slugger->slug($product->getName()));
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_edit',['id'=>$product->getId()]);
        }

        return $this->render('product/create.html.twig',[
            "form"=>$form->createView()
        ]);
    }

    #[Route('/admin/product/edit/{id}', name: 'product_edit')]
    public function edit(Product $product,Request $request,SluggerInterface $slugger,EntityManagerInterface $entityManager):Response
    {
        $form = $this->createForm(ProductType::class,$product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $product->setSlug($slugger->slug($product->getName()));
            $entityManager->flush();

            return $this->redirectToRoute('product_edit',['id'=>$product->getId()]);
        }

        return $this->render('product/edit.html.twig',[
            "form"=>$form->createView(),
            "product"=>$product
        ]);
    }

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
