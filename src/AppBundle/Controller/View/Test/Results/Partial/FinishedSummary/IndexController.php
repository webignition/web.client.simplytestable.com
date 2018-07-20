<?php

namespace AppBundle\Controller\View\Test\Results\Partial\FinishedSummary;

use AppBundle\Controller\BaseViewController;
use AppBundle\Exception\CoreApplicationRequestException;
use AppBundle\Exception\InvalidCredentialsException;
use AppBundle\Interfaces\Controller\RequiresValidUser;
use AppBundle\Interfaces\Controller\Test\RequiresValidOwner;
use AppBundle\Services\CacheValidatorService;
use AppBundle\Services\DefaultViewParameters;
use AppBundle\Services\RemoteTestService;
use AppBundle\Services\TestService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class IndexController extends BaseViewController implements RequiresValidUser, RequiresValidOwner
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

        $response = $this->cacheValidator->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
        ]);

        if ($this->cacheValidator->isNotModified($response)) {
            return $response;
        }

        $test = $this->testService->get($website, $test_id);
        $remoteTest = $this->remoteTestService->get();

        $viewData = [
            'test' => [
                'test' => $test,
                'remote_test' => $remoteTest,
            ]
        ];

        return $this->renderWithDefaultViewParameters(
            'Partials/Test/Summary/finished.html.twig',
            $viewData,
            $response
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getInvalidOwnerResponse(Request $request)
    {
        return new Response('', 400);
    }
}