<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/personne')]
class PersonneController extends AbstractController
{

    #[Route('/byAge/{ageMin}/{ageMax}', name: 'personne.byAge')]
    public function byAge(ManagerRegistry $doctrine, $ageMin, $ageMax) : Response {

        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findPersonneByAgeInterval($ageMin, $ageMax);

        return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
            'isPaginated' => false,
        ]);
    }

    #[Route('/byPage/{page?1}/{nb?15}', name: 'personne.byPage')]
    public function byName(ManagerRegistry $doctrine, $page, $nb) : Response {

        $offset = ($page - 1) * $nb;
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findBy([], ['age' => 'ASC'], $nb, $offset);

        return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
            'page' => $page,
            'isPaginated' => true,
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

    #[Route('/delete/{id}', name: 'personne.delete')]
    public function deletePersonne(Personne $personne = null, ManagerRegistry $doctrine) : RedirectResponse 
    {
        if($personne) {
            $manager = $doctrine->getManager();
            $manager->remove($personne);
            $manager->flush();
            $this->addFlash('success', 'La personne a été supprimée');
        } else {
            $this->addFlash('error', 'La personne que vous souhaitez supprimer n\'existe pas');
        }

        return $this->redirectToRoute('personne.byPage');
    }

    #[Route('/update/{id}/{name}/{firstname}/{age}', name: 'personne.update')]
    public function updatePersonne(Personne $personne = null, ManagerRegistry $doctrine, $name, $firstname, $age) : RedirectResponse
    {
        //Vérifier que la personne existe
        if($personne) {
            $personne->setName($name);
            $personne->setFirstname($firstname);
            $personne->setAge($age);
            $manager = $doctrine->getManager();
            $manager->persist($personne);
            $manager->flush();
            $this->addFlash('succes', 'La personne a bien été modifiée');
        } else {
            $this->addFlash('error', 'La personne n\'existe pas');
        }

        return $this->redirectToRoute('personne.byPage');
    }
}
