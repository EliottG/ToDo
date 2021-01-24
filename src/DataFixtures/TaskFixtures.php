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
		for ($i = 1; $i < 200; $i++) {
			$task = new Task();
			$task->setContent('Content task' . $i);
			$task->setCreatedAt(new \DateTime());
			$task->setTitle('TitleTask' . $i);
			if ($i < 50) {
				$task->setUser($this->getReference(UserFixtures::USER_REFERENCE_ONE));
			} elseif ($i < 100) {
				$task->setUser($this->getReference(UserFixtures::USER_REFERENCE_TWO));
			} elseif ($i < 150) {
				$task->setUser($this->getReference(UserFixtures::USER_REFERENCE_THREE));
			} elseif ($i < 200) {
				$task->setUser($this->getReference(UserFixtures::USER_REFERENCE_ONE));
			}
			$manager->persist($task);
		}
		$manager->flush();
	}

	public function getDependencies()
	{
		return [UserFixtures::class];
	}
}
