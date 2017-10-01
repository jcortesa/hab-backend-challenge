<?php

namespace HabApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateController extends FOSRestController
{
    /**
     * @Annotations\QueryParam(name="email", nullable=true)
     */
    public function getValidateAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $email = $paramFetcher->get('email');
        $re = '/@hotmail./m';

        if (preg_match_all($re, $email, $matches, PREG_SET_ORDER, 0)) {
            $response = new Response(null, Response::HTTP_FORBIDDEN);
        } else {
            $response = new Response(null, Response::HTTP_ACCEPTED);
        }
        return $response;
    }
}
