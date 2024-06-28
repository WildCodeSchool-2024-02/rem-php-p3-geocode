<?php

namespace App\Controller;

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
            if ($user instanceof User) {
                $message->setSender($user);
            }
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

    #[Route('message/{id}', name: 'message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $message->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($message);
            $entityManager->flush();
        }
        return $this->redirectToRoute('contact_index', [], Response::HTTP_SEE_OTHER);
    }
}
