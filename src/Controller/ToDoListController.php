<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToDoListController extends AbstractController
{
    /**
     * @Route("/", name="to_do_list")
     */
    public function index(): Response
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)
            ->findBy([], ['id' => 'DESC']);

        return $this->render('index.html.twig', [
            'tasks' => $tasks
        ]);
    }

    /**
     * @Route("/create", name="create_task", methods={"POST"})
     */
    public function createTask(Request $request): Response
    {
        $title = trim($request->request->get('title'));
        if (empty($title)) {
            return $this->redirectToRoute('to_do_list');
        }

        $em = $this->getDoctrine()->getManager();

        $task = new Task();
        $task->setTitle($title);
        $em->persist($task);
        $em->flush();

        return $this->redirectToRoute('to_do_list');
    }

    /**
     * @Route("/update/{id}", name="update_task")
     */
    public function updateTask($id): Response
    {
        $em = $this->getDoctrine()->getManager();

        $task = $this->getDoctrine()->getRepository(Task::class)
            ->find($id);

        $task->setStatus( !$task->getStatus());
        $em->persist($task);
        $em->flush();

        return $this->redirectToRoute('to_do_list');
    }

    /**
     * @Route("/delete/{id}", name="delete_task")
     */
    public function deleteTask(Task $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($id);
        $em->flush();

        return $this->redirectToRoute('to_do_list');
    }

}