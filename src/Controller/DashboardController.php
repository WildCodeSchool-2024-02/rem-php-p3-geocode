<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\StationsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/dashboard/', name: 'dashboard_')]
class DashboardController extends AbstractController
{
    #[Route(path: 'index', name: 'index')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig');
    }

    #[Route(path: 'users', name: 'userList')]
    public function userList(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('dashboard/users.html.twig', ['users' => $users]);
    }

    #[Route(path: 'stations', name: 'stationList')]
    public function stationList(StationsRepository $stationsRepository): Response
    {
        $stations = $stationsRepository->findAll();

        return $this->render('dashboard/stations.html.twig', ['stations' => $stations]);
    }

    #[Route(path: 'messages', name: 'messageList')]
    public function messageList(): Response
    {
        return $this->render('dashboard/messages.html.twig');
    }
}
