<?php

namespace App\Controller\View\Test\Results;

use App\Controller\AbstractBaseViewController;
use App\Entity\Test;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\DecoratedTest;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\PlansService;
use App\Services\TestFactory;
use App\Services\TestRetriever;
use App\Services\UrlViewValuesService;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class RejectedController extends AbstractBaseViewController
{
    private $userService;
    private $plansService;
    private $urlViewValues;
    private $testFactory;
    private $testRetriever;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        UrlViewValuesService $urlViewValues,
        UserService $userService,
        PlansService $plansService,
        TestFactory $testFactory,
        TestRetriever $testRetriever
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->userService = $userService;
        $this->plansService = $plansService;
        $this->urlViewValues = $urlViewValues;
        $this->testFactory = $testFactory;
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
        $testModel = $this->testRetriever->retrieve($test_id);

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
}
