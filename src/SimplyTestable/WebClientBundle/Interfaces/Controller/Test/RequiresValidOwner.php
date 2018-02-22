<?php

namespace SimplyTestable\WebClientBundle\Interfaces\Controller\Test;

use SimplyTestable\WebClientBundle\Interfaces\Controller\SettableResponse;
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
