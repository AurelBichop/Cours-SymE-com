<?php

namespace App\Controller;

use Cocur\Slugify\Slugify;
use App\Inspection\Detector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloWorld extends AbstractController
{
    #[Route('/hello/{name?world}', name:'hello', methods: 'GET')]
    public function helloWorld($name,Slugify $slugify, Detector $detector):Response
    {
        dump($detector->detect(101));
        dump($detector->detect(10));

        $slug =  $slugify->slugify("hello World");
        dump($slug);

        return new Response("Hello $name");
    }
}