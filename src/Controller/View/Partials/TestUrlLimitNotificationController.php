<?php

namespace App\Controller\View\Partials;

use App\Controller\AbstractBaseViewController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidCredentialsException;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\RemoteTestService;
use App\Services\SystemUserService;
use App\Services\TestService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class TestUrlLimitNotificationController extends AbstractBaseViewController
{
    /**
     * @var TestService
     */
    private $testService;

    /**
     * @var RemoteTestService
     */
    private $remoteTestService;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        TestService $testService,
        RemoteTestService $remoteTestService
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->testService = $testService;
        $this->remoteTestService = $remoteTestService;
    }

    /**
     * @param Request $request
     * @param string $website
     * @param int $test_id
     *
     * @return Response
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function indexAction(Request $request, $website, $test_id)
    {
        $test = $this->testService->get($website, $test_id);

        if (empty($test)) {
            return new Response();
        }

        $this->remoteTestService->setTest($test);
        $remoteTest = $this->remoteTestService->get($test);

        if (empty($remoteTest)) {
            return new Response();
        }

        $ammendments = $remoteTest->getAmmendments();

        if (empty($ammendments)) {
            return new Response();
        }

        $isPublicUserTest = $test->getUser() === SystemUserService::getPublicUser()->getUsername();

        $response = $this->cacheableResponseFactory->createResponse($request, [
            'test_id' => $test_id,
            'is_public_user_test' => $isPublicUserTest,
        ]);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
            return $response;
        }

        $ammendments = $remoteTest->getAmmendments();
        $firstAmmendment = $ammendments[0];

        $total = (int)str_replace('plan-url-limit-reached:discovered-url-count-', '', $firstAmmendment['reason']);
        $limit = $firstAmmendment['constraint']['limit'];

        return $this->renderWithDefaultViewParameters(
            'Partials/Alert/Content/url-limit.html.twig',
            [
                'is_public_user_test' => $isPublicUserTest,
                'total' => $total,
                'limit' => $limit,
            ],
            $response
        );
    }
}
