<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Form\TopicType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;
use App\Repository\MessageRepository;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\MessageType;

#[Route('/contact', name: 'contact_')]
class ContactController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function contactIndex(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $message->setSender($user);
            $entityManager->persist($message);
            $entityManager->flush();
            $this->addFlash('success', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('contact_index');
        }
        return $this->render('contact/contact.html.twig', ['form' => $form]);
    }

    #[Route('/message', name: 'message_index', methods: ['GET'])]
    public function show(MessageRepository $messageRepository): Response
    {
        $messages = $messageRepository->findAll();

        return $this->render('contact/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    #[Route('/message/{message}', name: 'message_show', methods: ['GET'])]
    public function showMessage(Message $message): Response
    {
        return $this->render('contact/show.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('message/{message}/delete', name: 'message_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {

        $entityManager->remove($message);
        $entityManager->flush();

        return $this->redirectToRoute('contact_message_index');
    }

    #[Route('/topics/new', name: 'new_topic', methods: ['GET', 'POST'])]
    public function newTopic(Request $request, EntityManagerInterface $entityManager): Response
    {
        $topic = new Topic();

        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($topic);
            $entityManager->flush();
            return $this->redirectToRoute('dashboard_topicList');
        }

        return $this->render('dashboard/topicEdit.html.twig', ['form' => $form]);
    }

    #[Route('/topics/{topic}', name: 'topic_edit', methods: ['GET', 'POST'])]
    public function topicEdit(Request $request, Topic $topic, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('dashboard_topicList', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/topicEdit.html.twig', ['form' => $form]);
    }

    #[Route('/topics/{topic}/delete', name: 'topic_delete')]
    public function topicDelete(Request $request, Topic $topic, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($topic);
        $entityManager->flush();

        return $this->redirectToRoute('dashboard_topicList');
    }
}
