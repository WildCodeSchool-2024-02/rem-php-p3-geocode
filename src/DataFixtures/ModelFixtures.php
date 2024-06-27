<?php

namespace App\DataFixtures;

use App\Entity\Model;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ModelFixtures extends Fixture
{
    public const CATEGORIES = [
        [
            'brand' => 'BMW',
            'model' => 'iX',
            'isHybrid' => false
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $models) {
            $model = new Model();
            $model->setBrand($models['brand']);
            $model->setModel($models['model']);
            $model->setHybrid($models['isHybrid']);
            $manager->persist($model);
            $this->addReference($models['brand'] . '_' . $models['model'], $model);
        }

        $manager->flush();
    }
}
