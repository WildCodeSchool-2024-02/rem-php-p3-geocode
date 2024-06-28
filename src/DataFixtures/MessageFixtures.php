<?php

namespace App\DataFixtures;

use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $userReference = $this->getReference('user_user@project.com');

        for ($i = 1; $i <= 3; $i++) {
            $message = new Message();
            $message->setContent($faker->sentence(20));
            $message->setSender($userReference);
            $message->setTopic($this->getReference('topic_' . $faker->numberBetween(1, 3)));
            $manager->persist($message);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TopicFixtures::class,
        ];
    }
}
