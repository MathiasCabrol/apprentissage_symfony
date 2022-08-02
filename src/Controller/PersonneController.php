<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/personne')]
class PersonneController extends AbstractController
{
    #[Route('/byPage/{page?1}/{nb?15}', name: 'personne.byPage')]
    public function byName(ManagerRegistry $doctrine, $page, $nb) : Response {

        $offset = ($page - 1) * $nb;
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findBy([], ['age' => 'ASC'], $nb, $offset);

        return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
            'page' => $page,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'personne.detail')]
    public function detail(Personne $personne = null) : Response {

        if(!$personne) {
            $this->addFlash('error', 'La personne recherchée n\'existe pas');
            return $this->redirectToRoute('personne.list');
        }

        return $this->render('personne/details.html.twig', [
            'personne' => $personne,
        ]);
    }

    #[Route('/add', name: 'personne.add')]
    public function addPersonne(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $personne = new Personne;
        $personne->setFirstname('Mathias');
        $personne->setName('Cabrol');
        $personne->setAge('24');
        $entityManager->persist($personne);
        $entityManager->flush();

        return $this->render('personne/details.html.twig', [
            'personne' => $personne,
        ]);
    }
}
