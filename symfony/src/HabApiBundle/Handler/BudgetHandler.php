<?php

namespace HabApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use HabApiBundle\Entity\User;
use Symfony\Component\Form\FormFactoryInterface;

class BudgetHandler
{
    private $om;
    private $repository;
    private $formFactory;

    public function __construct(ObjectManager $om, FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->formFactory = $formFactory;
        $this->repository = $this->om->getRepository('HabApiBundle:Budget');
    }

    public function all()
    {
        return $this->repository->findAll();
    }

    public function processForm($budget, array $parameters, $method = "PATCH")
    {
        $form = $this->formFactory->create(
            'HabApiBundle\Form\BudgetType',
            $budget,
            ['method' => $method]
        );
        $form->submit($parameters, 'PATCH' !== $method);

        if ($form->isValid()) {
            $budget = $form->getData();
            $budget->setStatus('pendiente');
            $email = $form->get('email')->getData();

            // check if user exist. if not, create it
            $user = $this->om->getRepository('HabApiBundle:User')
                ->findOneBy(['email' => $email])
            ;

            if (empty($user)) {
                $user = new User();
                $user->setEmail($email);
            }

            $user
                ->setPhone($form->get('phone')->getData())
                ->setAddress($form->get('address')->getData())
            ;

            $this->om->persist($user);

            $budget->setUser($user);
            $this->om->persist($budget);
            $this->om->flush();
            return $budget;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }
}
