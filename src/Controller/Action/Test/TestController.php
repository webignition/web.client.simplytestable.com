<?php

namespace App\Controller\Action\Test;

use App\Controller\AbstractController;
use App\Entity\Test;
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
     * @param string $website
     * @param int $test_id
     *
     * @return Response
     */
    public function lockAction($website, $test_id)
    {
        return $this->lockUnlock($website, $test_id, function (?Test $test) {
            $this->remoteTestService->lock($test);
        });
    }

    /**
     * @param string $website
     * @param int $test_id
     *
     * @return Response
     */
    public function unlockAction($website, $test_id)
    {
        return $this->lockUnlock($website, $test_id, function (?Test $test) {
            $this->remoteTestService->unlock($test);
        });
    }

    private function lockUnlock($website, $test_id, callable $action): Response
    {
        try {
            $test = $this->testService->get($website, $test_id);
            $action($test);
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
     *
     * @throws CoreApplicationReadOnlyException
     */
    public function cancelAction($website, $test_id)
    {
        $routeParameters = [
            'website' => $website,
            'test_id' => $test_id,
        ];

        $test = null;

        try {
            $test = $this->testService->get($website, $test_id);
            $this->remoteTestService->cancel($test);
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            return new RedirectResponse($this->generateUrl('view_dashboard'));
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            return new RedirectResponse($this->generateUrl(
                'view_test_progress',
                $routeParameters
            ));
        }

        if (empty($test)) {
            return new RedirectResponse($this->generateUrl('view_dashboard'));
        }

        return new RedirectResponse($this->generateUrl(
            'view_test_results',
            $routeParameters
        ));
    }

    /**
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationReadOnlyException
     */
    public function cancelCrawlAction($website, $test_id)
    {
        try {
            $test = $this->testService->get($website, $test_id);
            $remoteTest = $this->remoteTestService->get($test);

            if ($remoteTest) {
                $crawlData = $remoteTest->getCrawl();
                $this->remoteTestService->cancelByTestProperties((int) $crawlData['id']);
            }
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            // Nothing happens, we redirect to the test progress page regardless
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            // Nothing happens, we redirect to the test progress page regardless
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
     * @param string $website
     * @param string $test_id
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function retestAction($website, $test_id)
    {
        $remoteTest = $this->remoteTestService->retest((int) $test_id, $website);

        return new RedirectResponse($this->generateUrl(
            'view_test_progress',
            [
                'website' => $remoteTest->getWebsite(),
                'test_id' => $remoteTest->getId(),
            ]
        ));
    }
}
