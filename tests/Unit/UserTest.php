<?php

namespace App\Tests\Unit;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testRemoveTask()
    {
        $user = new User();
        $user->setId(114525536436);
        $task = new Task();
        $task->setUser($user);
        $this->assertEquals($user, $user->removeTask($task));
    }

    public function testAddTask()
    {
        $user = new User();
        $task = new Task();
        $this->assertEquals($user,$user->addTask($task));
    }
}
