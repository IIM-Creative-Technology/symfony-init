<?php

namespace App\DataFixtures;


use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $task = new Task();
            $task->setTitle('Task number ' . $i);
            $task->setDescription('Blablabla');
            $task->setDone(false);
            $manager->persist($task);
        }

        $manager->flush();
    }
}
