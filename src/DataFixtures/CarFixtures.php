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
            'reference' => 'BMW_iX',
            'color1' => '#182c67',
            'color2' => 'black',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CARS as $cars) {
            $car = new Car();
            $car->setModel($this->getReference($cars['reference']));
            $this->addReference($car->getModel()->getModel(), $car);
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
