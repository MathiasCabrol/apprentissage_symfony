<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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

    #[Route('/byAge/stats/{ageMin}/{ageMax}', name: 'personne.byAge')]
    public function statsByAge(ManagerRegistry $doctrine, $ageMin, $ageMax) : Response {

        $repository = $doctrine->getRepository(Personne::class);
        $stats = current($repository->statsPersonnesByAgeInterval($ageMin, $ageMax));

        return $this->render('personne/stats.html.twig', [
            'stats' => $stats,
            'ageMin' => $ageMin,
            'ageMax' => $ageMax,
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
    public function addPersonne(ManagerRegistry $doctrine, SluggerInterface $slugger, Request $request): Response
    {
        $personne = new Personne;
        //$personne est l'image du formulaire PersonneType
        $form = $this->createForm(PersonneType::class, $personne);
        $form->remove('created_at');
        $form->remove('updated_at');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->persist($personne);
                    /** @var UploadedFile $brochureFile */
                $photoFile = $form->get('photo')->getData();
        
                if ($photoFile) {
                    $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();
                
                    try {
                        $photoFile->move(
                            $this->getParameter('personne_image_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        dump($e);
                        // ... handle exception if something happens during file upload
                    }
                
                    $personne->setImage($newFilename);
                }
                $manager->flush();
                $this->addFlash('success', 'La personne'.$personne->getName().' a été ajoutée avec succès');
                return $this->redirectToRoute('personne.byPage');
        } else {
            //Thème bootstrap du formulaire chargé dans config/packages/twig.yaml
            return $this->render('personne/add-personne.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }
        

    #[Route('/edit/{id?0}', name: 'personne.edit')]
    public function editPersonne(ManagerRegistry $doctrine, Request $request, Personne $personne = null): Response
    {
        $new = false;
        if(!$personne) {
            $personne = new Personne();
            $new = true;
        } 
        //$personne est l'image du formulaire PersonneType
        $form = $this->createForm(PersonneType::class, $personne);
        $form->remove('created_at');
        $form->remove('updated_at');

        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $manager = $doctrine->getManager();
            $manager->persist($personne);
            $manager->flush();
            $message = 'La personne '.$personne->getName().' a bien été mise à jour';
            if($new){
                $message = 'La personne'.$personne->getName().' a bien été crée car l\'id n\'existait pas.';
            }
            $this->addFlash('success', $message);
            return $this->redirectToRoute('personne.byPage');
        } else {
            //Thème bootstrap du formulaire chargé dans config/packages/twig.yaml
            return $this->render('personne/add-personne.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        
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
