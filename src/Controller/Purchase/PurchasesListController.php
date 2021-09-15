<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class PurchasesListController extends AbstractController
{
    public function __construct(
        protected Security $security,
        protected RouterInterface $router,
        protected Environment $twig
    ){}

    #[Route('purchases', name: "purchases_index")]
    public function index(){

        /** @var User $user */
        $user = $this->security->getUser();

        if(!$user){
            throw new AccessDeniedException("Vous devez être connecté");
        }

        $html = $this->twig->render('purchase/index.html.twig',[
            'purchases'=> $user->getPurchases()
        ]);

        return new Response($html);
    }
}