<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FirstController extends AbstractController
{
    #[Route('/first', name: 'first')]
    public function first(): Response
    {

        return $this->render('first/index.html.twig', [
            'name' => 'Cabrol',
            'firstname' => 'Mathias'
        ]);
    }

    #[Route('/template', name: 'template')]
    public function template(): Response
    {

        return $this->render('template.html.twig', [
            'name' => 'Cabrol',
            'firstname' => 'Mathias'
        ]);
    }

    // #[Route('/sayHello/{name}/{firstname}', name: 'sayHello')]
    public function sayHello($name, $firstname): Response
    {
        return $this->render('first/hello.html.twig', [
            'name' => $name,
            'firstname' => $firstname,
        ]);
    }

    #[Route('multi/{entier1<\d+>}/{entier2<\d+>}', name: 'multi')]
    public function multi($entier1, $entier2): Response {
        $resultat = $entier1 * $entier2;
        return new Response("<h1>$resultat</h1>");
    }
}
