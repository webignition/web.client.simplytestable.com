<?php

namespace App\Controller\View\Test\Results;

use App\Controller\View\Test\AbstractRequiresValidOwnerController;
use App\Entity\Test\Test;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Interfaces\Controller\RequiresValidUser;
use App\Model\RemoteTest\RemoteTest;
use App\Services\CacheValidatorService;
use App\Services\DefaultViewParameters;
use App\Services\PlansService;
use App\Services\RemoteTestService;
use App\Services\TestService;
use App\Services\UrlViewValuesService;
use App\Services\UserManager;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class RejectedController extends AbstractRequiresValidOwnerController implements RequiresValidUser
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
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param UrlViewValuesService $urlViewValues
     * @param UserManager $userManager
     * @param SessionInterface $session
     * @param TestService $testService
     * @param RemoteTestService $remoteTestService
     * @param UserService $userService
     * @param PlansService $plansService
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        UrlViewValuesService $urlViewValues,
        UserManager $userManager,
        SessionInterface $session,
        TestService $testService,
        RemoteTestService $remoteTestService,
        UserService $userService,
        PlansService $plansService
    ) {
        parent::__construct(
            $router,
            $twig,
            $defaultViewParameters,
            $cacheValidator,
            $urlViewValues,
            $userManager,
            $session
        );

        $this->testService = $testService;
        $this->remoteTestService = $remoteTestService;
        $this->userService = $userService;
        $this->plansService = $plansService;
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
    public function indexAction(Request $request, $website, $test_id)
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $test = $this->testService->get($website, $test_id);
        $remoteTest = $this->remoteTestService->get();

        $cacheValidatorParameters = [
            'website' => $website,
            'test_id' => $test_id,
        ];

        if ($this->isRejectedDueToCreditLimit($remoteTest)) {
            $userSummary = $this->userService->getSummary();
            $planConstraints = $userSummary->getPlanConstraints();
            $planCredits = $planConstraints['credits'];

            $rejection = $remoteTest->getRejection();
            $constraint = $rejection->getConstraint();

            $cacheValidatorParameters['limits'] = $constraint['limit'] . ':' . $planCredits['limit'];
            $cacheValidatorParameters['credits_remaining'] = $planCredits['limit'] - $planCredits['used'];
        }

        $response = $this->cacheValidator->createResponse($request, $cacheValidatorParameters);

        if ($this->cacheValidator->isNotModified($response)) {
            return $response;
        }

        if ($test->getWebsite() != $website) {
            return new RedirectResponse($this->generateUrl(
                'redirect_website_test',
                [
                    'website' => $test->getWebsite(),
                    'test_id' => $test_id
                ]
            ));
        }

        if (Test::STATE_REJECTED !== $test->getState()) {
            return new RedirectResponse($this->generateUrl(
                'view_test_progress',
                [
                    'website' => $website,
                    'test_id' => $test_id
                ]
            ));
        }

        $viewData = [
            'website' => $this->urlViewValues->create($website),
            'remote_test' => $remoteTest,
            'plans' => $this->plansService->getList(),
        ];

        if ($this->isRejectedDueToCreditLimit($remoteTest)) {
            $viewData['userSummary'] = $this->userService->getSummary();
        }

        return $this->renderWithDefaultViewParameters('test-results-rejected.html.twig', $viewData, $response);
    }

    /**
     * @param RemoteTest $remoteTest
     *
     * @return bool
     */
    private function isRejectedDueToCreditLimit(RemoteTest $remoteTest)
    {
        $rejection = $remoteTest->getRejection();

        if ('plan-constraint-limit-reached' !== $rejection->getReason()) {
            return false;
        }

        $constraint = $rejection->getConstraint();

        return 'credits_per_month' === $constraint['name'];
    }
}
