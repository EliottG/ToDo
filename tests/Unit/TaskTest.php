<?php

namespace App\Tests\Unit;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testIsDone()
    {
        $task = new Task();
        $task->setIsDone(true);
        $this->assertEquals(true, $task->getIsDone());
    }

    public function testCreatedAt()
    {
        $task = new Task();
        $datetime = new \DateTime();
        $task->setCreatedAt($datetime);
        $this->assertEquals($datetime, $task->getCreatedAt());
    }
}