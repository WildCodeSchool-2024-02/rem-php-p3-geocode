<?php

namespace App\Controller;

use App\Repository\StationsRepository;
use App\Service\IpService;
use App\Service\UserLocationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

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
    public function map(
        StationsRepository $stationsRepository,
        ObjectNormalizer $objectNormalizer,
        Request $request,
        IpService $ipService,
        UserLocationService $userLoc
    ): Response {
        $user = $this->getUser();

        if ($user->getCars()->isEmpty()) {
            $this->addFlash('danger', "Vous n'avez pas enregistré de véhicule !");
            return $this->redirectToRoute('home_index');
        }
        $normalizer = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizer, []);
        $stations = $stationsRepository->findAll();
        $stations = $serializer->normalize($stations);
        //$clientIp = $ipService->getIp($request);
        $clientIp = '90.110.223.141';
        $clientLocInfo = $userLoc->getUserLocation($clientIp);

        //dd($clientLocInfo);

        return $this->render('home/map.html.twig', ['stations' => json_encode($stations),
            'clientInfo' => json_encode($clientLocInfo)]);
    }

    #[Route('/options', name: 'options')]
    public function options(): Response
    {
        return $this->render('home/options.html.twig');
    }

    #[Route('/json', name: 'json')]
    public function jsonTest(
        StationsRepository $stationsRepository,
        ObjectNormalizer $objectNormalizer,
        UserLocationService $userLoc
    ): Response {
        $normalizer = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizer, []);
        $stations = $stationsRepository->findAll();

        $stations = $serializer->normalize($stations);

        $clientIp = '90.110.223.141';
        $clientLocInfo = $userLoc->getUserLocation($clientIp);
        //var_dump($stations);
        //die;

        return new JsonResponse(array('stations' => $stations,
            'clientInfo' => $clientLocInfo));
    }
}
