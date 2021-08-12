<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloWorld
{

    #[Route('/hello/{name?world}', name:'hello', methods: 'GET')]
    public function helloWorld($name):Response
    {
        return new Response("Hello $name");
    }
}