<?php

namespace HabApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use HabApiBundle\Entity\Budget;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BudgetController extends FOSRestController
{
    public function getBudgetsAction()
    {
        /**
         * @todo filter by user email
         * @todo paginate results
         */
        return $this->container->get('habapi.budget.handler')->all();
    }

    public function getBudgetAction(Budget $budget)
    {
        return $budget;
    }

    public function postBudgetAction(Request $request)
    {
        try {
            $budget = new Budget();
            $budget = $this->container->get('habapi.budget.handler')
                ->processForm(
                    $budget,
                    $request->request->all(),
                    'POST'
                )
            ;
            return $this->routeRedirectView(
                'get_budget',
                [
                    'budget' => $budget,
                    '_format' => $request->get('_format')
                ],
                Response::HTTP_CREATED
            );
        } catch (InvalidFormException $e) {
            return new Response(
                json_encode($e->getForm()),
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function patchBudgetAction(Request $request, Budget $budget)
    {
        try {
            $budget = $this->container->get('habapi.budget.handler')
                ->processForm(
                    $budget,
                    $request->request->all(),
                    'PATCH'
                )
            ;
            return $this->routeRedirectView(
                'get_budget',
                [
                    'budget' => $budget,
                    '_format' => $request->get('_format')
                ],
                Response::HTTP_CREATED
            );
        } catch (InvalidFormException $e) {
            return new Response(
                json_encode($e->getForm()),
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @Route("/budgets/{id}/publish")
     */
    public function publishBudgetAction(Request $request, Budget $budget)
    {
        try {
            $budget = $this->container->get('habapi.budget.handler')->publish($budget);
            return $this->routeRedirectView(
                'get_budget',
                [
                    'budget' => $budget,
                    '_format' => $request->get('_format')
                ],
                Response::HTTP_NO_CONTENT
            );
        } catch (InvalidFormException $e) {
            return new Response(
                json_encode($e->getForm()),
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @Route("/budgets/{id}/discard")
     */
    public function discardBudgetAction(Request $request, Budget $budget)
    {
        try {
            $budget = $this->container->get('habapi.budget.handler')->discard($budget);
            return $this->routeRedirectView(
                'get_budget',
                [
                    'budget' => $budget,
                    '_format' => $request->get('_format')
                ],
                Response::HTTP_NO_CONTENT
            );
        } catch (InvalidFormException $e) {
            return new Response(
                json_encode($e->getForm()),
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}