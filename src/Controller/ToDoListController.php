<?php

namespace App\Controller;

use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToDoListController extends AbstractController
{
    #[Route('/todo', name: 'app_to_do_list')]
    public function index(\Symfony\Component\HttpFoundation\Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->has("todos")) {
            $todos = [
                'achat' => 'acheter clé usb',
                'cours' => 'Finaliser mon cours',
                'correction' => 'corriger mes examens'
            ];
            $session->set("todos", $todos);
            $this->addFlash('info', "la liste des todos viens d'etre initialisée");
        }
        return $this->render('to_do_list/index.html.twig', [
            'controller_name' => 'ToDoListController'
        ]);
    }

    #[Route('/todo/add/{name}/{content}', name: 'todo.add')]
    public function addTodo(\Symfony\Component\HttpFoundation\Request $request, $name, $content)
    {
        $session = $request->getSession();
     //cas ou le tableau todos existe dans la session
        if ($session->has('todos')) {
            $todos = $session->get('todos');//recuperer le tableau
            if (isset($todos[$name])) { //si l'element existe dans la liste
                $this->addFlash("error", "l'element dont le nom est  $name existe deja dans la liste !");
            } else { // si l'elment n'existe pas dans la liste
                $todos[$name] = $content;
                $session->set("todos",$todos); //renvoyer la liste dans sa nouvelle version
                $this->addFlash('success', "l'element dont le nom est $name est bien ajouté dans la liste  ");
            }


        } else {
            //cas ou Todos n'existe meme pas
            $this->addFlash("error", "la liste des Todos n'est pas encore initialisée!!");
        }


        return $this->redirectToRoute('app_to_do_list'); //rediriger vers /todos*
    }


    #[Route('/todo/delete/{name}', name: 'todo.delete')]
    public function deleteTodo(\Symfony\Component\HttpFoundation\Request $request, $name)
    {
        $session = $request->getSession();
        //cas ou le tableau todos existe dans la session
        if ($session->has('todos')) {
            $todos = $session->get('todos');//recuperer le tableau
            if (isset($todos[$name])) { //si l'element existe dans la liste
                unset($todos[$name]);
                $session->set("todos",$todos);
                $this->addFlash("success", "l'element dont le nom est  $name bien supprimé de la liste!!");
            } else { // si l'elment n'existe pas dans la liste
                $this->addFlash('error', "l'element dont le nom est $name n'existe pas dans la liste!!");
            }


        } else { //cas ou Todos n'existe meme pas
            $this->addFlash("error", "la liste des Todos n'est pas encore initialisée!!");
        }


        return $this->redirectToRoute('app_to_do_list'); //rediriger vers /todos*
    }



    #[Route('/todo/reset', name: 'todo.reset')]
    public function resetTodo(\Symfony\Component\HttpFoundation\Request $request)
    {
        $session = $request->getSession();
       $session->remove("todos");


        return $this->redirectToRoute('app_to_do_list'); //rediriger vers /todos*
    }




}

