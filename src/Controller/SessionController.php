<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        
        if($session->has('nbVisite')) {
            $nbrVisite = $session->get('nbVisite');
            $nbrVisite += 1;
        } else {
            $nbrVisite = 1;
        }

        $session->set('nbVisite', $nbrVisite);

        $nbrVisite = 0;

        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController',
        ]);
    }
}
