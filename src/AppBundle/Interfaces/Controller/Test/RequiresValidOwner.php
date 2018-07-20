<?php

namespace AppBundle\Interfaces\Controller\Test;

use AppBundle\Interfaces\Controller\SettableResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface RequiresValidOwner extends SettableResponse
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function getInvalidOwnerResponse(Request $request);
}
