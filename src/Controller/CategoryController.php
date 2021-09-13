<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController
{
    #[Route('/admin/category/create', name: 'category_create')]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger):Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_edit',['id'=>$category->getId()]);
        }

        return $this->render('category/create.html.twig',[
            "form"=>$form->createView()
        ]);
    }

    #[Route('/admin/category/edit/{id}', name: 'category_edit')]
    public function edit(Category $category,Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger):Response
    {
        //$this->denyAccessUnlessGranted('CAN_EDIT',$category, "Vous n'etes pas le proprio");

        //        $user = $this->getUser();
//
//        if(!$user){
//            return $this->redirectToRoute("security_login");
//        }
//
//        if($user !== $category->getOwner()){
//            throw new AccessDeniedHttpException("Vous n'étes pas autorisé !!!");
//        }

        $form = $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $entityManager->flush();

            return $this->redirectToRoute('category_edit',['id'=>$category->getId()]);
        }

        return $this->render('category/edit.html.twig',[
            "form"=>$form->createView(),
            "category"=>$category
        ]);
    }
}
