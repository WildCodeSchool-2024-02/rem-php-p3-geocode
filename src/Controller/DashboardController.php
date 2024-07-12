<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Model;
use App\Entity\Stations;
use App\Entity\User;
use App\Form\CarCsvImportType;
use App\Form\ModelType;
use App\Form\StationCsvImportType;
use App\Form\StationType;
use App\Repository\CarRepository;
use App\Repository\ModelRepository;
use App\Repository\StationsRepository;
use App\Repository\TopicRepository;
use App\Repository\UserRepository;
use App\Service\CarCsvImportService;
use App\Service\FileUploader;
use App\Service\StationCsvImportService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route(path: '/dashboard/', name: 'dashboard_')]
class DashboardController extends AbstractController
{
    #[Route(path: 'index', name: 'index')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig');
    }

    #[Route(path: 'cars', name: 'carList')]
    public function carsList(
        ModelRepository $modelRepository,
        Request $request,
        FileUploader $fileUploader,
        CarCsvImportService $carImport
    ): Response {
        $cars = $modelRepository->findAll();
        $form = $this->createForm(CarCsvImportType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carFile = $form->get('carImport')->getData();
            if ($carFile) {
                $carFileName = $fileUploader->uploadCars($carFile);
                $carImport->carImport($carFileName);
                $this->addFlash('success', 'Cars imported successfully');

                return $this->redirectToRoute('dashboard_carList');
            }
        }

        return $this->render('dashboard/cars.html.twig', ['cars' => $cars, 'form' => $form]);
    }

    #[Route(path: 'new/car', name: 'create_Car')]
    public function createCar(Request $request, EntityManagerInterface $entityManager): Response
    {
        $model = new Model();
        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $model->setHybrid(false);
            $entityManager->persist($model);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_carList');
        }

        return $this->render('dashboard/addCar.html.twig', ['form' => $form]);
    }

    #[Route(path: '{model}/edit', name: 'edit_model')]
    public function editModel(Model $model, EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_carList');
        }

        return $this->render('dashboard/editModel.html.twig', ['form' => $form]);
    }

    #[Route(path: '{car}/delete', name: 'delete_car')]
    public function deleteCar(Car $car, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($car);
        $entityManager->flush();

        return $this->redirectToRoute('options');
    }

    #[Route(path: 'stations', name: 'stationList')]
    public function stationList(
        StationsRepository $stationsRepository,
        Request $request,
        FileUploader $fileUploader,
        StationCsvImportService $stationImport
    ): Response {
        $stations = $stationsRepository->findAll();
        $form = $this->createForm(StationCsvImportType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stationFile = $form->get('stationImport')->getData();
            if ($stationFile) {
                $stationFileName = $fileUploader->uploadStations($stationFile);
                $stationImport->stationImport($stationFileName);
                $this->addFlash('success', 'Stations imported successfully');

                return $this->redirectToRoute('dashboard_stationList');
            }
        }

        return $this->render('dashboard/stations.html.twig', ['stations' => $stations, 'form' => $form]);
    }

    #[Route(path: 'stations/{stations}/edit', name: 'edit_Station')]
    public function editStation(Stations $stations, EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(StationType::class, $stations);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_stationList');
        }

        return $this->render('dashboard/editStation.html.twig', ['form' => $form]);
    }

    #[Route(path: 'stations/{stations}/delete', name: 'delete_Station')]
    public function deleteStation(Stations $stations, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($stations);
        $entityManager->flush();

        return $this->redirectToRoute('dashboard_stationList');
    }

    #[Route(path: 'messages', name: 'messageList')]
    public function messageList(): Response
    {
        return $this->render('dashboard/messages.html.twig');
    }

    #[Route(path: 'topics', name: 'topicList')]
    public function topicList(TopicRepository $topicRepository): Response
    {
        $topics = $topicRepository->findAll();

        return $this->render('dashboard/topics.html.twig', ['topics' => $topics]);
    }
}
