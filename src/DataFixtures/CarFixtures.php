<?php

namespace App\DataFixtures;

use App\Entity\Car;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CarFixtures extends Fixture implements DependentFixtureInterface
{
    public const CARS = [
        [
            'reference' => 'BMW_iX1',
            'color1' => '#606f72',
            'color2' => 'black',
        ],
        [
            'reference' => 'BMW_iX3',
            'color1' => '#30333e',
            'color2' => '#f4f4f4',
        ],
        [
            'reference' => 'BMW_i4',
            'color1' => '#182c67',
            'color2' => 'black',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CARS as $cars) {
            $car = new Car();
            $car->setModel($this->getReference($cars['reference']));
            $car->setColor1($cars['color1']);
            $car->setColor2($cars['color2']);

            $manager->persist($car);
        }


        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ModelFixtures::class,
        ];
    }
}
