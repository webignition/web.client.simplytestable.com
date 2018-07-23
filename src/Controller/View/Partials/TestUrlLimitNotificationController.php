<?php

namespace App\Controller\View\Partials;

use App\Controller\AbstractBaseViewController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidCredentialsException;
use App\Interfaces\Controller\RequiresValidUser;
use App\Interfaces\Controller\Test\RequiresValidOwner;
use App\Services\CacheValidatorService;
use App\Services\DefaultViewParameters;
use App\Services\RemoteTestService;
use App\Services\SystemUserService;
use App\Services\TestService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class TestUrlLimitNotificationController extends AbstractBaseViewController implements
    RequiresValidUser,
    RequiresValidOwner
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
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param TestService $testService
     * @param RemoteTestService $remoteTestService
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        TestService $testService,
        RemoteTestService $remoteTestService
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheValidator);

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
        if ($this->hasResponse()) {
            return $this->response;
        }

        $test = $this->testService->get($website, $test_id);
        $this->remoteTestService->setTest($test);

        $remoteTest = $this->remoteTestService->get();
        $ammendments = $remoteTest->getAmmendments();

        if (empty($ammendments)) {
            return new Response();
        }

        $isPublicUserTest = $test->getUser() === SystemUserService::getPublicUser()->getUsername();

        $response = $this->cacheValidator->createResponse($request, [
            'test_id' => $test_id,
            'is_public_user_test' => $isPublicUserTest,
        ]);

        if ($this->cacheValidator->isNotModified($response)) {
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

    /**
     * @inheritdoc}
     */
    public function getInvalidOwnerResponse(Request $request)
    {
        return new Response('');
    }
}
