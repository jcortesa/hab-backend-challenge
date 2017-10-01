<?php

namespace HabApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use HabApiBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends FOSRestController
{

    public function getCategoriesAction()
    {
        return $this->container->get('doctrine')->getManager()
            ->getRepository('HabApiBundle:Category')->findBy(['parent' => null]);
    }

    public function getCategoryAction(Category $category)
    {
        return $category;
    }
}
