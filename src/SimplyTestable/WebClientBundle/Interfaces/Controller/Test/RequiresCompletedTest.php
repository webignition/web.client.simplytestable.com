<?php

namespace SimplyTestable\WebClientBundle\Interfaces\Controller\Test;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface RequiresCompletedTest
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function getFailedNoSitemapTestResponse(Request $request);

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function getRejectedTestResponse(Request $request);

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function getNotFinishedTestResponse(Request $request);

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function getRequestWebsiteMismatchResponse(Request $request);

    /**
     * @param Request $request
     */
    public function setRequest(Request $request);

    /**
     * @return Request
     */
    public function getRequest();
}
