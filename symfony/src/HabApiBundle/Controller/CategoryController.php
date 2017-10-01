<?php

namespace HabApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use HabApiBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends FOSRestController
{

    public function getCategoriesAction()
    {
        return $this->container->get('habapi.category.handler')->all();
    }

    public function getCategoryAction(Category $category)
    {
        return $category;
    }
}
