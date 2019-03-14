<?php

namespace App\Controller\Action\Test;

use App\Controller\AbstractController;
use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Services\RemoteTestService;
use App\Services\TestService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class TestController extends AbstractController
{
    /**
     * @var TestService
     */
    private $testService;

    /**
     * @var RemoteTestService
     */
    private $remoteTestService;

    /**
     * @param RouterInterface $router
     * @param TestService $testService
     * @param RemoteTestService $remoteTestService
     */
    public function __construct(
        RouterInterface $router,
        TestService $testService,
        RemoteTestService $remoteTestService
    ) {
        parent::__construct($router);

        $this->testService = $testService;
        $this->remoteTestService = $remoteTestService;
        $this->router = $router;
    }

    /**
     * @param int $test_id
     *
     * @return Response
     */
    public function lockAction(int $test_id): Response
    {
        try {
            $this->remoteTestService->lock($test_id);
        } catch (\Exception $e) {
            // We already redirect back to test results regardless of if this action succeeds
        }

        return new Response();
    }

    /**
     * @param int $test_id
     *
     * @return Response
     */
    public function unlockAction(int $test_id): Response
    {
        try {
            $this->remoteTestService->unlock($test_id);
        } catch (\Exception $e) {
            // We already redirect back to test results regardless of if this action succeeds
        }

        return new Response();
    }

    /**
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse
     */
    public function cancelAction($website, $test_id)
    {
        $routeName = 'view_test_results';

        $routeParameters = [
            'website' => $website,
            'test_id' => $test_id,
        ];

        try {
            $this->remoteTestService->cancel((int) $test_id);
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            $routeName = 'view_test_progress';
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            $routeName = 'view_test_progress';
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
            $routeName = 'view_test_progress';
        }

        return new RedirectResponse($this->generateUrl($routeName, $routeParameters));
    }

    /**
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse
     */
    public function cancelCrawlAction($website, $test_id)
    {
        try {
            $this->remoteTestService->cancel((int) $test_id);
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            // Do nothing
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            // Do nothing
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
            // Do nothing
        }

        return new RedirectResponse($this->generateUrl(
            'view_test_progress',
            [
                'website' => $website,
                'test_id' => $test_id,
            ]
        ));
    }

    /**
     * @param int $test_id
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function retestAction(int $test_id): RedirectResponse
    {
        $remoteTest = $this->remoteTestService->retest((int) $test_id);

        return new RedirectResponse($this->generateUrl(
            'view_test_progress',
            [
                'website' => $remoteTest->getWebsite(),
                'test_id' => $remoteTest->getId(),
            ]
        ));
    }
}
