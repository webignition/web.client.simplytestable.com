<?php

namespace SimplyTestable\WebClientBundle\Interfaces\Controller\Test;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface RequiresValidOwner
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function getInvalidOwnerResponse(Request $request);

    /**
     * @param Response $response
     */
    public function setResponse(Response $response);

    /**
     * @return bool
     */
    public function hasResponse();
}
