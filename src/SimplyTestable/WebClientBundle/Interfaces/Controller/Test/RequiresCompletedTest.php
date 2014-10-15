<?php

namespace SimplyTestable\WebClientBundle\Interfaces\Controller\Test;

interface RequiresCompletedTest {

    public function getFailedNoSitemapTestResponse();
    public function getRejectedTestResponse();
    public function getNotFinishedTestResponse();
    public function getRequestWebsiteMismatchResponse();

    public function setRequest(\Symfony\Component\HttpFoundation\Request $request);
    public function getRequest();

}