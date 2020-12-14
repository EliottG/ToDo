<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 20; $i++) {
            $task = new Task();
            $task->setContent('Content task'. $i);
            $task->setCreatedAt(new \DateTime());
            $task->setTitle('TitleTask' . $i);
            switch ($i) {
                case ($i < 4) :
                    $task->setUser($this->getReference(UserFixtures::USER_REFERENCE_ONE));
                    break;
                case ($i < 8) :
                    $task->setUser($this->getReference(UserFixtures::USER_REFERENCE_TWO));
                    break;
                case ($i < 12) :
                    $task->setUser($this->getReference(UserFixtures::USER_REFERENCE_THREE));
                    break;
                case ($i < 16) :
                    $task->setUser($this->getReference(UserFixtures::USER_REFERENCE_FOUR));
                    break;
            }
            $manager->persist($task);
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }


}
