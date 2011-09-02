<?php

namespace BlackRook\TaskBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use BlackRook\TaskBundle\Entity\Task;

class LoadTaskData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load($em)
    {
        $task = new Task();
        $task->setName('name');
        $task->setDescription('description');
        $em->persist($task);
        $em->flush();
    }

    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}