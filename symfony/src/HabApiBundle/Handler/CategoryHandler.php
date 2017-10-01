<?php

namespace HabApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;

class CategoryHandler
{
    private $om;
    private $repository;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
        $this->repository = $this->om->getRepository('HabApiBundle:Category');
    }

    public function all()
    {
        return $this->repository->findBy(['parent' => null]);
    }
}
