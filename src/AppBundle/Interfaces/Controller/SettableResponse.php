<?php

namespace AppBundle\Interfaces\Controller;

use Symfony\Component\HttpFoundation\Response;

interface SettableResponse
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
