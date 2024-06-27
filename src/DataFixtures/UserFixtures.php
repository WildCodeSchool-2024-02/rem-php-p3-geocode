<?php

namespace App\DataFixtures;

use App\Entity\Car;
use App\Entity\User;
use App\Repository\CarRepository;
use App\Repository\ModelRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('user@project.com');
        $user->setFirstname('John');
        $user->setLastname('Doe');
        $user->setAddress('Rue du test, 99999 Testville');
        $user->setRoles(['ROLE_USER']);
        $user->addCar($this->getReference('iX'));
        $this->addReference('user_' . $user->getEmail(), $user);
        $hashedPassword = $this->userPasswordHasher->hashPassword($user, 'userpassword');

        $user->setPassword($hashedPassword);
        $manager->persist($user);


        $admin = new User();
        $car = new Car();
        $car->setModel($this->getReference('BMW_iX'));
        $car->setColor1('#182c67');
        $car->setColor2('black');

        $admin->setEmail('admin@project.com');
        $admin->setFirstname('Admin');
        $admin->setLastname('Doe');
        $admin->setAddress('Rue de l\'essai, 99999 Essaiville');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->addCar($car);
        $this->addReference('admin_' . $admin->getEmail(), $admin);
        $hashedPassword = $this->userPasswordHasher->hashPassword($admin, 'adminpassword');

        $admin->setPassword($hashedPassword);
        $manager->persist($admin);
        $manager->persist($car);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CarFixtures::class,
        ];
    }
}
