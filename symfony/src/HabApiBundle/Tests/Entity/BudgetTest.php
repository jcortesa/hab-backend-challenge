<?php

namespace HabApiBundle\Tests\Entity;

use HabApiBundle\Entity\Budget;
use HabApiBundle\Entity\Category;
use HabApiBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class BudgetName extends TestCase
{
    public function testGetBudgetTitle()
    {
        $budget = new Budget();
        $budget->setTitle('Reforma baño');
        $this->assertEquals('Reforma baño', $budget->getTitle());
    }

    public function testGetBudgetDescription()
    {
        $budget = new Budget();
        $budget->setDescription('Necesito de profesionales en la construcción de 6 unifamiliares en madrid de 709 m2 construidos. Estructura metálica, cerramiento en tosco o termo arcilla, mono capa. Interior en pladur, tarima flotante en las dos plantas y gres en cocina, baños y aseo.');
        $this->assertEquals('Necesito de profesionales en la construcción de 6 unifamiliares en madrid de 709 m2 construidos. Estructura metálica, cerramiento en tosco o termo arcilla, mono capa. Interior en pladur, tarima flotante en las dos plantas y gres en cocina, baños y aseo.', $budget->getDescription());
    }

    public function testGetBudgetStatus()
    {
        $budget = new Budget();
        $budget->setStatus('pendiente');
        $this->assertEquals('pendiente', $budget->getStatus());
    }

    public function testGetBudgetUser()
    {
        $budget = new Budget();
        $user = new User();
        $budget->setUser($user);
        $this->assertEquals($user, $budget->getUser());
    }

    public function testGetBudgetCategory()
    {
        $budget = new Budget();
        $category = new Category();
        $budget->setCategory($category);
        $this->assertEquals($category, $budget->getCategory());
    }
}