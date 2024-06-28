<?php

namespace App\Controller;

use App\Repository\CarRepository;
use App\Repository\UserRepository;
use App\Service\DistanceCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\User;

class UserController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function index(DistanceCalculatorService $distanceCalc, CarRepository $carRepository): Response
    {
        $battery = 87;
        $distance = $distanceCalc->calculateDistance(87);


        return $this->render('User/profile.html.twig', ['distance' => $distance, 'battery' => $battery]);
    }

    #[Route('/maptest', name: 'maptest')]
    public function getIp(Request $request): string
    {
        $clientIp = $request->getClientIp();

        return $clientIp;
    }
}
