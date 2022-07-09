<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route("/todo")]
class TodoController extends AbstractController
{
    #[Route('/', name: 'app_todo')]
    public function index(Request $request): Response
    {

        $session = $request->getSession();

        if(!$session->has('todo')) {
            $todos = [
                'achat' => 'acheter du coca',
                'travail' => 'apprendre symfony',
                'workout' => 'faire exos quotidiens',
            ];
            $session->set('todo', $todos);
            $this->addFlash('info', "La liste des todos est bien initialisée");
        }

        return $this->render('todo/index.html.twig', [
            'controller_name' => 'TodoController',
        ]);
    }

    #[Route('/add/{name?sport}/{content?marcher}', name: 'todo.add')]
    public function addTodo(Request $request, $name, $content): RedirectResponse {
        
        $this->todoCRUD($request, 'create', $name, $content);

        return $this->redirectToRoute('app_todo');
        
    }

    #[Route('/delete/{name}', name: 'todo.delete')]
    public function deleteTodo(Request $request, $name): RedirectResponse {
        
        $this->todoCRUD($request, 'delete', $name);

        return $this->redirectToRoute('app_todo');

    }

    #[Route('/modify/{name}/{content}', name: 'todo.modify')]
    public function modifyTodo(Request $request, $name, $content): RedirectResponse {

        $this->todoCRUD($request, 'modify', $name, $content);

        return $this->redirectToRoute('app_todo');

    }

    #[Route('/reset', name: 'todo.reset')]
    public function resetTodo(Request $request): RedirectResponse {

        $this->todoCRUD($request, 'reset');

        return $this->redirectToRoute('app_todo');

    }

    private function todoCRUD(object $request, string $operation, string $name = '', string $content = '') : void {
        $session = $request->getSession();
        if ($session->has('todo')) {
            $todos = $session->get('todo');
            if (isset($todos[$name]) || $operation == 'create' || $operation == 'reset') {
                switch ($operation) {
                    case 'modify' :
                        $messageWord = 'modifié';
                    case 'create' :
                        $messageWord = 'créé';
                        $todos[$name] = $content;
                        break;
                    case 'delete' :
                        $messageWord = 'supprimé';
                        unset($todos[$name]);
                        break;
                    case 'reset' :
                        $session->remove('todo');
                        $messageWord = 'réinitialisé';
                        break;
                }
                if($operation == 'reset') {
                    $this->addFlash('success', "La todolist a été réinitialisée avec succès");
                } else {
                    $session->set('todo', $todos);
                    $this->addFlash('success', "L'élément $name a été $messageWord avec succès");
                }
            } else {
                $this->addFlash('error', "L'élément $name que vous souhaitez modifier n'existe pas");
            }
        } else {
            $this->addFlash('error', "Le tableau de todolist n'a pas été initialisé");
        }
    }

}
