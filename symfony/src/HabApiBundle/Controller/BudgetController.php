<?php

namespace HabApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use HabApiBundle\Entity\Budget;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\OffsetRepresentation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BudgetController extends FOSRestController
{
    /**
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing publications.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="3", description="How many publications to return.")
     *
     * @Annotations\QueryParam(name="email", nullable=true, description="Filter by user email")
     * @todo paginate results
     */
    public function getBudgetsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $email = $paramFetcher->get('email');
        $user = $this->container->get('doctrine')->getManager()
            ->getRepository('HabApiBundle:User')->findOneBy(['email' => $email]);

        if (!empty($email) && empty($user)) {
            throw new NotFoundHttpException(sprintf('No budget was found by that user email'));
        }

        $allBudgets = $this->container->get('doctrine')->getManager()
            ->getRepository('HabApiBundle:Budget')->findByPaginated($user);
        if (empty($allBudgets)) {
            throw new NotFoundHttpException(sprintf('No resource was found.'));
        }

        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        $paginatedBudgets = $this->container->get('doctrine')->getManager()
            ->getRepository('HabApiBundle:Budget')->findByPaginated(
                $user,
                $limit,
                $offset
            )
        ;
        if (empty($paginatedBudgets)) {
            throw new NotFoundHttpException(sprintf('No resource was found in that page.'));
        }

        return new OffsetRepresentation(
            new CollectionRepresentation($paginatedBudgets, 'budgets', 'budgets'),
            'get_budgets',
            $paramFetcher->all(),
            $offset,
            $limit,
            count($allBudgets),
            'offset',
            'limit',
            false
        );
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
            if (
                'pendiente' !== $budget->getStatus() ||
                empty($budget->getTitle()) ||
                empty($budget->getDescription())
            ) {
                throw new \Exception();
            }

            $budget = $this->container->get('habapi.budget.handler')->publish($budget);
            return $this->routeRedirectView(
                'get_budget',
                [
                    'budget' => $budget,
                    '_format' => $request->get('_format')
                ],
                Response::HTTP_NO_CONTENT
            );
        } catch (\Exception $e) {
            return new Response(
                'La solicitud de presupuesto ya está publicada o no cumple con los requisitos para ser publicada',
                Response::HTTP_FORBIDDEN
            );
        }
    }

    /**
     * @Route("/budgets/{id}/discard")
     */
    public function discardBudgetAction(Request $request, Budget $budget)
    {
        try {
            if ('descartada' === $budget->getStatus()) {
                throw new \Exception();
            }

            $budget = $this->container->get('habapi.budget.handler')->discard($budget);
            return $this->routeRedirectView(
                'get_budget',
                [
                    'budget' => $budget,
                    '_format' => $request->get('_format')
                ],
                Response::HTTP_NO_CONTENT
            );
        } catch (\Exception $e) {
            return new Response(
                'La solicitud de presupuesto ya está descartada',
                Response::HTTP_FORBIDDEN
            );
        }
    }
}
