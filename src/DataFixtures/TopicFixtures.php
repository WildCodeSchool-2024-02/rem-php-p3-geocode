<?php

namespace App\DataFixtures;

use App\Entity\Topic;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TopicFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $topic1 = new Topic();
        $topic1->setName('Signaler un bug');
        $manager->persist($topic1);
        $this->addReference('topic_1', $topic1);

        $topic2 = new Topic();
        $topic2->setName('Signaler une borne HS');
        $manager->persist($topic2);
        $this->addReference('topic_2', $topic2);

        $topic3 = new Topic();
        $topic3->setName('Ma voiture n\'est pas dans la liste');
        $manager->persist($topic3);
        $this->addReference('topic_3', $topic3);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
