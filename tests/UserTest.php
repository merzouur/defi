<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\User;

class UserTest extends TestCase
{
    public function testUserEntity()
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setUsername('user');

        $this->assertSame('test@example.com', $user->getEmail());
        $this->assertSame('user', $user->getUsername());
    }
}
