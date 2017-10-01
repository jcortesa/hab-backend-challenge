<?php

namespace HabApiBundle\Tests\Entity;

use HabApiBundle\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryName extends TestCase
{
    public function testGetCategoryName()
    {
        $category = new Category();
        $category->setName('construcción');
        $this->assertEquals('construcción', $category->getName());
    }

    public function testGetCategorySlug()
    {
        $category = new Category();
        $category->setSlug('construccion-casas');
        $this->assertEquals('construccion-casas', $category->getSlug());
    }

    public function testGetCategoryChildren()
    {
        $category = new Category();
        $childCategories = [
            new Category(),
            new Category(),
        ];

        foreach ($childCategories as $childCategory) {
            $category->addChild($childCategory);
        }

        $this->assertCount(2, $category->getChildren());
    }
}