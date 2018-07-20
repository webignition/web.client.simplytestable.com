<?php

namespace AppBundle\Interfaces\Controller\Test;

use AppBundle\Interfaces\Controller\SettableResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

interface RequiresCompletedTest extends SettableResponse
{
    /**
     * @param RouterInterface $router
     * @param Request $request
     *
     * @return Response
     */
    public function getFailedNoSitemapTestResponse(RouterInterface $router, Request $request);

    /**
     * @param RouterInterface $router
     * @param Request $request
     *
     * @return Response
     */
    public function getRejectedTestResponse(RouterInterface $router, Request $request);

    /**
     * @param RouterInterface $router
     * @param Request $request
     *
     * @return Response
     */
    public function getNotFinishedTestResponse(RouterInterface $router, Request $request);

    /**
     * @param RouterInterface $router
     * @param Request $request
     *
     * @return Response
     */
    public function getRequestWebsiteMismatchResponse(RouterInterface $router, Request $request);
}
