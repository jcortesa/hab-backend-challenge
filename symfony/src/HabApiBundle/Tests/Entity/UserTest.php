<?php

namespace HabApiBundle\Tests\Entity;

use HabApiBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGetEmail()
    {
        $user = new User();
        $user->setEmail('gary@gmail.com');
        $this->assertEquals('gary@gmail.com', $user->getEmail());
    }

    public function testGetPhone()
    {
        $user = new User();
        $user->setPhone('+34666123321');
        $this->assertEquals('+34666123321', $user->getPhone());
    }

    public function testGetAddress()
    {
        $user = new User();
        $user->setAddress('Plaça de Cort, 14, 07001, Palma, Illes Balears');
        $this->assertEquals(
            'Plaça de Cort, 14, 07001, Palma, Illes Balears',
            $user->getAddress()
        );
    }
}