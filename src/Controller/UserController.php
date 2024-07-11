<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Form\RegistrationFormType;
use App\Form\UserEditType;
use App\Repository\CarRepository;
use App\Repository\UserRepository;
use App\Service\DistanceCalculatorService;
use App\Service\UserLocationService;
use Cassandra\Type\UserType;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(
        DistanceCalculatorService $distanceCalc,
        CarRepository $carRepository,
        UserLocationService $userLoc
    ): Response {
        $battery = 64;
        $distance = $distanceCalc->calculateDistance($battery);
        $clientIp = '90.110.223.141';
        $clientLocInfo = $userLoc->getUserLocation($clientIp);


        return $this->render(
            'User/profile.html.twig',
            ['distance' => $distance,
                'battery' => $battery,
                'clientInfo' => json_encode($clientLocInfo)
            ]
        );
    }

    #[Route('/{user}', name: 'show_User')]
    public function showUser(User $user): Response
    {
        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    #[Route('/{user}/edit', name: 'edit_User')]
    public function editUser(Request $request, User $user, EntityManagerInterface $entity): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cars = $form->get('cars')->getData();

            foreach ($cars as $car) {
                $car->setUser($user);
            }

            $entity->persist($user);
            $entity->flush();

            return $this->redirectToRoute('dashboard_userList');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form,
            'user' => $user
        ]);
    }

    #[Route(path: '/{user}/addcar', name: 'add_car')]
    public function registerCars(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $car->setUser($user);
            $car->setColor1('#182c67');
            $car->setColor2('black');
            $entityManager->persist($car);
            $entityManager->flush();

            return $this->redirectToRoute('show_User', ['user' => $user->getId()]);
        }

        return $this->render('registration/registerCars.html.twig', ['registrationForm' => $form]);
    }

    #[Route('/{user}/delete', name: 'delete_User')]
    public function deleteUser(Request $request, User $user, EntityManagerInterface $entity): Response
    {
        $entity->remove($user);
        $entity->flush();
        $this->addFlash('danger', 'L\utilisateur a bien été supprimé');

        return $this->redirectToRoute('dashboard_userList');
    }

    #[Route(path: '{user}/{car}/delete', name: 'delete_user_car')]
    public function deleteCar(Car $car, User $user, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($car);
        $entityManager->flush();

        return $this->redirectToRoute('show_User', ['user' => $user->getId()]);
    }

    #[Route('/maptest', name: 'maptest')]
    public function getIp(Request $request): string
    {
        $clientIp = $request->getClientIp();

        return $clientIp;
    }
}
