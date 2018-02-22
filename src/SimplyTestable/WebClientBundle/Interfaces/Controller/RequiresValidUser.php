<?php

namespace SimplyTestable\WebClientBundle\Interfaces\Controller;

use Symfony\Component\HttpFoundation\Response;

interface RequiresValidUser
{
    /**
     * @param Response $response
     */
    public function setResponse(Response $response);

    /**
     * @return bool
     */
    public function hasResponse();
}
