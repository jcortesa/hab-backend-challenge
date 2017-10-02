<?php

namespace HabApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use HabApiBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends FOSRestController
{

    public function getCategoriesAction()
    {
        $categories =  $this->container->get('doctrine')->getManager()
            ->getRepository('HabApiBundle:Category')->findBy(['parent' => null]);

        if (empty($categories)) {
            throw new NotFoundHttpException(sprintf('No resource was found.'));
        }

        return $categories;
    }

    public function getCategoryAction(Category $category)
    {
        return $category;
    }
}
