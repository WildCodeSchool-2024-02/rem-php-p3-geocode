<?php

namespace App\Controller;

use App\Repository\StationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home_index')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }
        return $this->render('home/index.html.twig');
    }

    #[Route('/map', name: 'map')]
    public function map(StationsRepository $stationsRepository, SerializerInterface $serializer): Response
    {
        $stations = $stationsRepository->findAll();
        $stations = $serializer->normalize($stations);

        return $this->render('home/map.html.twig', ['stations' => json_encode($stations)]);
    }

    #[Route('/json', name: 'json')]
    public function jsonTest(StationsRepository $stationsRepository, SerializerInterface $serializer): Response
    {
        $stations = $stationsRepository->findAll();

        $stations = $serializer->normalize($stations);
        //var_dump($stations);
        //die;

        return new JsonResponse(array('stations' => $stations));
    }
}
