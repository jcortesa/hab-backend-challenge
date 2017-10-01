<?php

namespace HabApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends FOSRestController
{

    public function getCategoriesAction()
    {
        return $this->container->get('habapi.category.handler')->all();
    }

}
