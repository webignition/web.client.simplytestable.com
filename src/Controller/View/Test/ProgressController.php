<?php

namespace App\Controller\View\Test;

use App\Controller\AbstractBaseViewController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Entity\Test;
use App\Model\Test as TestModel;
use App\Model\DecoratedTest;
use App\Services\CacheableResponseFactory;
use App\Services\Configuration\CssValidationTestConfiguration;
use App\Services\DefaultViewParameters;
use App\Services\SystemUserService;
use App\Services\TaskTypeService;
use App\Services\TestOptions\RequestAdapterFactory as TestOptionsRequestAdapterFactory;
use App\Services\TestRetriever;
use App\Services\TestService;
use App\Services\UrlViewValuesService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;
use webignition\NormalisedUrl\NormalisedUrl;

class ProgressController extends AbstractBaseViewController
{
    const RESULTS_PREPARATION_THRESHOLD = 100;

    private $testService;
    private $taskTypeService;
    private $testOptionsRequestAdapterFactory;
    private $cssValidationTestConfiguration;
    private $urlViewValues;
    private $userManager;
    private $testRetriever;

    /**
     * @var string[]
     */
    private $testStateLabelMap = array(
        'new' => 'New, waiting to start',
        'queued' => 'waiting for first test to begin',
        'resolving' => 'Resolving website',
        'resolved' => 'Resolving website',
        'preparing' => 'Finding URLs to test: looking for sitemap or news feed',
        'crawling' => 'Finding URLs to test',
        'failed-no-sitemap' => 'Finding URLs to test: preparing to crawl'
    );

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        UrlViewValuesService $urlViewValues,
        UserManager $userManager,
        TestService $testService,
        TaskTypeService $taskTypeService,
        TestOptionsRequestAdapterFactory $testOptionsRequestAdapterFactory,
        CssValidationTestConfiguration $cssValidationTestConfiguration,
        TestRetriever $testRetriever
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->testService = $testService;
        $this->taskTypeService = $taskTypeService;
        $this->testOptionsRequestAdapterFactory = $testOptionsRequestAdapterFactory;
        $this->cssValidationTestConfiguration = $cssValidationTestConfiguration;
        $this->urlViewValues = $urlViewValues;
        $this->userManager = $userManager;
        $this->testRetriever = $testRetriever;
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
        $user = $this->userManager->getUser();
        $testModel = $this->testRetriever->retrieve($test_id);

        $testWebsite = $testModel->getWebsite();

        if ($testWebsite !== $website) {
            return $this->createRedirectResponse(
                $request,
                'view_test_progress',
                [
                    'website' => $testWebsite,
                    'test_id' => $test_id
                ]
            );
        }

        if ($testModel->isFinished()) {
            $shouldRedirectToTestResults =
                TestModel::STATE_FAILED_NO_SITEMAP !== $testModel->getState() || SystemUserService::isPublicUser($user);

            if ($shouldRedirectToTestResults) {
                return $this->createRedirectResponse(
                    $request,
                    'view_test_results',
                    [
                        'website' => $testWebsite,
                        'test_id' => $test_id
                    ]
                );
            }

            return $this->createRedirectResponse(
                $request,
                'action_test_retest',
                [
                    'test_id' => $test_id
                ]
            );
        }

        $requestTimeStamp = $request->query->get('timestamp');
        $isPublicUserTest = $testModel->getUser() === SystemUserService::getPublicUser()->getUsername();

        $response = $this->cacheableResponseFactory->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
            'is_public' => $testModel->isPublic(),
            'is_public_user_test' => $isPublicUserTest,
            'timestamp' => empty($requestTimeStamp) ? '' : $requestTimeStamp,
            'state' => $testModel->getState()
        ]);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
            return $response;
        }

        $testOptionsAdapter = $this->testOptionsRequestAdapterFactory->create();
        $testOptionsAdapter->setRequestData(new ParameterBag($testModel->getTaskOptions()));

        $decoratedTest = new DecoratedTest($testModel);

        $commonViewData = [
            'test' => $decoratedTest,
            'state_label' => $this->createStateLabel($testModel),
        ];

        if ($this->requestIsForApplicationJson($request)) {
            $viewData = array_merge($commonViewData, [
                'this_url' => $this->generateUrl(
                    'view_test_progress',
                    [
                        'website' => $testWebsite,
                        'test_id' => $test_id
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
            ]);

            $response->setContent(json_encode($viewData));
            $response->headers->set('content-type', 'application/json');
        } else {
            $viewData = array_merge($commonViewData, [
                'website' => $this->urlViewValues->create($testWebsite),
                'available_task_types' => $this->taskTypeService->getAvailable(),
                'task_types' => $this->taskTypeService->get(),
                'test_options' => $testOptionsAdapter->getTestOptions()->__toKeyArray(),
                'is_public_user_test' => $isPublicUserTest,
                'css_validation_ignore_common_cdns' =>
                    $this->cssValidationTestConfiguration->getExcludedDomains(),
                'default_css_validation_options' => [
                    'ignore-warnings' => 1,
                    'vendor-extensions' => 'warn',
                    'ignore-common-cdns' => 1
                ],
            ]);

            $response = $this->renderWithDefaultViewParameters('test-progress.html.twig', $viewData, $response);
        }

        return $response;
    }

    private function createStateLabel(TestModel $testModel): string
    {
        $state = $testModel->getState();
        $label = $this->testStateLabelMap[$state] ?? '';

        if ($state == TestModel::STATE_IN_PROGRESS) {
            $label = $testModel->getCompletionPercent() . '% done';
        }

        if (in_array($state, [TestModel::STATE_QUEUED, TestModel::STATE_IN_PROGRESS])) {
            $label = $testModel->getUrlCount() . ' urls, ' . $testModel->getRemoteTaskCount() . ' tests; ' . $label;
        }

        if ($state == TestModel::STATE_CRAWLING) {
            $crawlData = $testModel->getCrawlData();

            $label .= sprintf(
                ': %s pages examined, %s of %s found',
                $crawlData['processed_url_count'],
                $crawlData['discovered_url_count'],
                $crawlData['limit']
            );
        }

        return $label;
    }

    /**
     * @param Request $request
     * @param string $routeName
     * @param array $routeParameters
     *
     * @return RedirectResponse|JsonResponse
     */
    private function createRedirectResponse(Request $request, $routeName, array $routeParameters = [])
    {
        $requestHeaders = $request->headers;
        $requestedWithHeaderName = 'X-Requested-With';

        $isXmlHttpRequest = $requestHeaders->get($requestedWithHeaderName) == 'XMLHttpRequest';

        $urlReferenceType = $isXmlHttpRequest
            ? UrlGeneratorInterface::ABSOLUTE_URL
            : UrlGeneratorInterface::ABSOLUTE_PATH;

        $redirectUrl = $this->generateUrl($routeName, $routeParameters, $urlReferenceType);

        if ($isXmlHttpRequest) {
            return new JsonResponse([
                'this_url' => $redirectUrl
            ]);
        }

        $requestQuery = $request->query;

        if ($requestQuery->get('output') == 'json') {
            $normalisedUrl = new NormalisedUrl($redirectUrl);

            if ($normalisedUrl->hasQuery()) {
                $normalisedUrl->getQuery()->set('output', 'json');
            } else {
                $normalisedUrl->setQuery('output=json');
            }

            $redirectUrl = (string)$normalisedUrl;
        }

        return new RedirectResponse($redirectUrl);
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    private function requestIsForApplicationJson(Request $request)
    {
        if (!$request->headers->has('accept')) {
            return false;
        }

        $acceptHeaders = AcceptHeader::fromString($request->headers->get('Accept'))->all();

        return key($acceptHeaders) === 'application/json';
    }
}
