<?php

namespace SimplyTestable\WebClientBundle\Interfaces\Controller;

use Symfony\Component\HttpFoundation\Response;

interface IEFiltered
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
