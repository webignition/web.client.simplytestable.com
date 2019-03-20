<?php

namespace App\Controller\View\Test\Results;

use App\Controller\AbstractBaseViewController;
use App\Entity\Test;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\RemoteTest\RemoteTest;
use App\Model\Test\DecoratedTest;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\PlansService;
use App\Services\RemoteTestService;
use App\Services\TestFactory;
use App\Services\TestService;
use App\Services\UrlViewValuesService;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class RejectedController extends AbstractBaseViewController
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
     * @var UserService
     */
    private $userService;

    /**
     * @var PlansService
     */
    private $plansService;

    /**
     * @var UrlViewValuesService
     */
    private $urlViewValues;

    private $testFactory;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        UrlViewValuesService $urlViewValues,
        TestService $testService,
        RemoteTestService $remoteTestService,
        UserService $userService,
        PlansService $plansService,
        TestFactory $testFactory
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->testService = $testService;
        $this->remoteTestService = $remoteTestService;
        $this->userService = $userService;
        $this->plansService = $plansService;
        $this->urlViewValues = $urlViewValues;
        $this->testFactory = $testFactory;
    }

    /**
     * @param Request $request
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse|Response
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     * @throws InvalidContentTypeException
     */
    public function indexAction(Request $request, string $website, int $test_id): Response
    {
        $test = $this->testService->get($test_id);
        $remoteTest = $this->remoteTestService->get($test->getTestId());
        $testModel = $this->testFactory->create($test, [
            'website' => $remoteTest->getWebsite(),
            'user' => $remoteTest->getUser(),
            'state' => $remoteTest->getState(),
            'type' => $remoteTest->getType(),
            'url_count' => $remoteTest->getUrlCount(),
            'task_count' => $remoteTest->getTaskCount(),
            'errored_task_count' => $remoteTest->getErroredTaskCount(),
            'cancelled_task_count' => $remoteTest->getCancelledTaskCount(),
            'parameters' => $remoteTest->getEncodedParameters(),
            'amendments' => $remoteTest->getAmmendments(),
            'crawl' => $remoteTest->getCrawl(),
            'task_types' => $remoteTest->getTaskTypes(),
            'task_count_by_state' => $remoteTest->getRawTaskCountByState(),
            'rejection' => $this->createRejectionData($remoteTest),
            'is_public' => $remoteTest->getIsPublic(),
            'task_type_options' => $remoteTest->getTaskTypeOptions(),
            'owners' => $remoteTest->getOwners(),
        ]);

        $cacheValidatorParameters = [
            'website' => $website,
            'test_id' => $test_id,
        ];

        if ($this->isRejectedDueToCreditLimit($testModel->getRejection())) {
            $userSummary = $this->userService->getSummary();
            $planConstraints = $userSummary->getPlanConstraints();
            $planCredits = $planConstraints['credits'];

            $rejectionData = $testModel->getRejection();
            $constraint = $rejectionData['constraint'];

            $cacheValidatorParameters['limits'] = $constraint['limit'] . ':' . $planCredits['limit'];
            $cacheValidatorParameters['credits_remaining'] = $planCredits['limit'] - $planCredits['used'];
        }

        $response = $this->cacheableResponseFactory->createResponse($request, $cacheValidatorParameters);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
            return $response;
        }

        if ($website !== $testModel->getWebsite()) {
            return new RedirectResponse($this->generateUrl(
                'view_test_results_rejected',
                [
                    'website' => $testModel->getWebsite(),
                    'test_id' => $test_id
                ]
            ));
        }

        if (Test::STATE_REJECTED !== $testModel->getState()) {
            return new RedirectResponse($this->generateUrl(
                'view_test_progress',
                [
                    'website' => $website,
                    'test_id' => $test_id
                ]
            ));
        }

        $decoratedTest = new DecoratedTest($testModel);

        $viewData = [
            'website' => $this->urlViewValues->create($website),
            'test' => $decoratedTest,
            'plans' => $this->plansService->getList(),
        ];

        if ($this->isRejectedDueToCreditLimit($testModel->getRejection())) {
            $viewData['userSummary'] = $this->userService->getSummary();
        }

        return $this->renderWithDefaultViewParameters('test-results-rejected.html.twig', $viewData, $response);
    }

    private function isRejectedDueToCreditLimit(array $rejectionData): bool
    {
        if (empty($rejectionData)) {
            return false;
        }

        if ('plan-constraint-limit-reached' !== $rejectionData['reason']) {
            return false;
        }

        return 'credits_per_month' === $rejectionData['constraint']['name'];
    }

    private function createRejectionData(RemoteTest $remoteTest): array
    {
        $rejectionData = [];
        $remoteTestRejection = $remoteTest->getRejection();

        if (empty($remoteTestRejection)) {
            return $rejectionData;
        }

        $rejectionData['reason'] = $remoteTestRejection->getReason();

        $constraint = $remoteTestRejection->getConstraint();
        if ($constraint) {
            $rejectionData['constraint'] = $constraint;
        }

        return $rejectionData;
    }
}
