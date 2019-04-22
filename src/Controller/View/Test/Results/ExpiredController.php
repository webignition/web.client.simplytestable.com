<?php

namespace App\Controller\View\Test\Results;

use App\Controller\AbstractBaseViewController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\DecoratedTest;
use App\Model\TestInterface;
use App\Services\CacheableResponseFactory;
use App\Services\Configuration\CssValidationTestConfiguration;
use App\Services\DefaultViewParameters;
use App\Services\RemoteTestService;
use App\Services\SystemUserService;
use App\Services\TaskTypeService;
use App\Services\TestFactory;
use App\Services\TestOptions\RequestAdapterFactory as TestOptionsRequestAdapterFactory;
use App\Services\TestRetriever;
use App\Services\UrlViewValuesService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;
use webignition\ReadableDuration\Factory as ReadableDurationFactory;

class ExpiredController extends AbstractBaseViewController
{
    private $remoteTestService;
    private $taskTypeService;
    private $testOptionsRequestAdapterFactory;
    private $cssValidationTestConfiguration;
    private $urlViewValues;
    private $userManager;
    private $testFactory;
    private $testRetriever;
    private $readableDurationFactory;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        UrlViewValuesService $urlViewValues,
        UserManager $userManager,
        RemoteTestService $remoteTestService,
        TaskTypeService $taskTypeService,
        TestOptionsRequestAdapterFactory $testOptionsRequestAdapterFactory,
        CssValidationTestConfiguration $cssValidationTestConfiguration,
        TestFactory $testFactory,
        TestRetriever $testRetriever,
        ReadableDurationFactory $readableDurationFactory
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->remoteTestService = $remoteTestService;
        $this->taskTypeService = $taskTypeService;
        $this->testOptionsRequestAdapterFactory = $testOptionsRequestAdapterFactory;
        $this->cssValidationTestConfiguration = $cssValidationTestConfiguration;
        $this->urlViewValues = $urlViewValues;
        $this->userManager = $userManager;
        $this->testFactory = $testFactory;
        $this->testRetriever = $testRetriever;
        $this->readableDurationFactory = $readableDurationFactory;
    }

    /**
     * @param Request $request
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse|Response
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function indexAction(Request $request, string $website, int $test_id): Response
    {
        $user = $this->userManager->getUser();
        $testModel = $this->testRetriever->retrieve($test_id);

        if ($website !== $testModel->getWebsite()) {
            return new RedirectResponse($this->generateUrl(
                'view_test_results',
                [
                    'website' => $testModel->getWebsite(),
                    'test_id' => $test_id
                ]
            ));
        }

        if ($testModel->getRemoteTaskCount() > $testModel->getLocalTaskCount()) {
            return new RedirectResponse($this->generateUrl(
                'view_test_results_preparing',
                [
                    'website' => $website,
                    'test_id' => $test_id,
                ]
            ));
        }

        if (TestInterface::STATE_EXPIRED !== $testModel->getState()) {
            return new RedirectResponse($this->generateUrl(
                'view_test_results',
                [
                    'website' => $testModel->getWebsite(),
                    'test_id' => $test_id
                ]
            ));
        }

        $isPublicUserTest = $testModel->getUser() === SystemUserService::getPublicUser()->getUsername();

        $response = $this->cacheableResponseFactory->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
            'is_public' => $testModel->isPublic(),
            'is_public_user_test' => $isPublicUserTest,
        ]);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
            return $response;
        }

        $expiryDurationString = $this->createExpiryDurationString($testModel);

        $testOptionsAdapter = $this->testOptionsRequestAdapterFactory->create();
        $testOptionsAdapter->setRequestData(new ParameterBag($testModel->getTaskOptions()));

        $isOwner = in_array($user->getUsername(), $testModel->getOwners());

        $decoratedTest = new DecoratedTest($testModel);

        return $this->renderWithDefaultViewParameters(
            'test-expired.html.twig',
            [
                'website' => $this->urlViewValues->create($website),
                'test' => $decoratedTest,
                'is_public' => $testModel->isPublic(),
                'is_public_user_test' => $isPublicUserTest,
                'is_owner' => $isOwner,
                'available_task_types' => $this->getAvailableTaskTypes(
                    $testModel->getTaskTypes(),
                    $testModel->isPublic(),
                    $isOwner
                ),
                'task_types' => $this->taskTypeService->get(),
                'test_options' => $testOptionsAdapter->getTestOptions()->__toKeyArray(),
                'css_validation_ignore_common_cdns' =>
                    $this->cssValidationTestConfiguration->getExcludedDomains(),
                'domain_test_count' => $this->remoteTestService->getFinishedCount($website),
                'default_css_validation_options' => [
                    'ignore-warnings' => 1,
                    'vendor-extensions' => 'warn',
                    'ignore-common-cdns' => 1
                ],
                'expiry_duration_string' => $expiryDurationString,
            ],
            $response
        );
    }

    private function getAvailableTaskTypes(array $taskTypes, bool $isPublic, bool $isOwner): array
    {
        if ($isPublic && !$isOwner) {
            $availableTaskTypes = $this->taskTypeService->get();

            foreach ($availableTaskTypes as $taskTypeKey => $taskTypeDetails) {
                if (!in_array($taskTypeDetails['name'], $taskTypes)) {
                    unset($availableTaskTypes[$taskTypeKey]);
                }
            }

            return $availableTaskTypes;
        }

        return $this->taskTypeService->getAvailable();
    }

    private function createExpiryDurationString(TestInterface $test)
    {
        $expiryDuration = $this->readableDurationFactory->createFromDateTime($test->getEndDateTime());
        $readableExpiryDurationData = $this->readableDurationFactory->getInMostAppropriateUnits($expiryDuration, 1);
        $readableExpiryDurationValue = $readableExpiryDurationData[0];

        $value = $readableExpiryDurationValue['value'];
        $unit = $readableExpiryDurationValue['unit'];

        $expiryDurationString = (string) $value . ' ' . $unit;
        if ($value !== 1) {
            $expiryDurationString .= 's';
        }

        return $expiryDurationString;
    }
}
