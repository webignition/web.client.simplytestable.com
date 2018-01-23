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
}
