<?php

namespace App\Controller\View\Test\Results;

use App\Controller\View\Test\AbstractRequiresValidOwnerController;
use App\Interfaces\Controller\Test\RequiresCompletedTest;
use App\Entity\Test\Test;
use App\Model\RemoteTest\RemoteTest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractResultsController extends AbstractRequiresValidOwnerController implements
    RequiresCompletedTest
{
    const RESULTS_PREPARATION_THRESHOLD = 100;

    /**
     * {@inheritdoc}
     */
    public function getFailedNoSitemapTestResponse(RouterInterface $router, Request $request)
    {
        return new RedirectResponse($router->generate(
            'view_test_results_failed_no_urls_detected',
            [
                'website' => $request->attributes->get('website'),
                'test_id' => $request->attributes->get('test_id')
            ]
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getRejectedTestResponse(RouterInterface $router, Request $request)
    {
        return new RedirectResponse($router->generate(
            'view_test_results_rejected',
            [
                'website' => $request->attributes->get('website'),
                'test_id' => $request->attributes->get('test_id')
            ]
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getNotFinishedTestResponse(RouterInterface $router, Request $request)
    {
        return new RedirectResponse($router->generate(
            'view_test_progress',
            [
                'website' => $request->attributes->get('website'),
                'test_id' => $request->attributes->get('test_id')
            ]
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
