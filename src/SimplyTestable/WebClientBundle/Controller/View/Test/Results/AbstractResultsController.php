<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results;

use SimplyTestable\WebClientBundle\Controller\View\Test\AbstractRequiresValidOwnerController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresCompletedTest;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractResultsController extends AbstractRequiresValidOwnerController implements
    RequiresValidUser,
    RequiresValidOwner,
    RequiresCompletedTest
{
    const RESULTS_PREPARATION_THRESHOLD = 100;

    /**
     * {@inheritdoc}
     */
    public function getFailedNoSitemapTestResponse(RouterInterface $router, Request $request)
    {
        return new RedirectResponse($router->generate(
            'view_test_results_failednourlsdetected_index_index',
            [
                'website' => $request->attributes->get('website'),
                'test_id' => $request->attributes->get('test_id')
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getRejectedTestResponse(RouterInterface $router, Request $request)
    {
        return new RedirectResponse($router->generate(
            'view_test_results_rejected_index_index',
            [
                'website' => $request->attributes->get('website'),
                'test_id' => $request->attributes->get('test_id')
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getNotFinishedTestResponse(RouterInterface $router, Request $request)
    {
        return new RedirectResponse($router->generate(
            'view_test_progress_index_index',
            [
                'website' => $request->attributes->get('website'),
                'test_id' => $request->attributes->get('test_id')
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @param RemoteTest $remoteTest
     * @param Test $test
     *
     * @return bool
     */
    protected function requiresPreparation(RemoteTest $remoteTest, Test $test)
    {
        return ($remoteTest->getTaskCount() - self::RESULTS_PREPARATION_THRESHOLD) > $test->getTaskCount();
    }
}
