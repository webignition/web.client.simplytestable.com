<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results;

use SimplyTestable\WebClientBundle\Controller\View\Test\CacheableViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresCompletedTest;
//use SimplyTestable\WebClientBundle\Entity\Test\Test;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class ResultsController
    extends CacheableViewController
    implements IEFiltered, RequiresValidUser, RequiresValidOwner, RequiresCompletedTest
{

    public function getFailedNoSitemapTestResponse() {
        return new RedirectResponse($this->generateUrl('view_test_results_failednourlsdetected_index_index', array(
            'website' => $this->getRequest()->attributes->get('website'),
            'test_id' => $this->getRequest()->attributes->get('test_id')
        ), true));
    }

    public function getRejectedTestResponse() {
        return new RedirectResponse($this->generateUrl('view_test_results_rejected_index_index', array(
            'website' => $this->getRequest()->attributes->get('website'),
            'test_id' => $this->getRequest()->attributes->get('test_id')
        ), true));
    }

    public function getNotFinishedTestResponse() {
        return new RedirectResponse($this->generateUrl('view_test_progress_index_index', array(
            'website' => $this->getRequest()->attributes->get('website'),
            'test_id' => $this->getRequest()->attributes->get('test_id')
        ), true));
    }

}