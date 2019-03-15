<?php

namespace App\Controller\View\Test\Results;

use App\Controller\AbstractBaseViewController;
use App\Entity\Test;
use App\Exception\CoreApplicationRequestException;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\SystemUserService;
use App\Services\TestService;
use App\Services\UrlViewValuesService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class FailedNoUrlsDetectedController extends AbstractBaseViewController
{
    /**
     * @var TestService
     */
    private $testService;

    /**
     * @var UrlViewValuesService
     */
    private $urlViewValues;

    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        TestService $testService,
        UrlViewValuesService $urlViewValues,
        UserManager $userManager
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->testService = $testService;
        $this->urlViewValues = $urlViewValues;
        $this->userManager = $userManager;
    }

    /**
     * @param Request $request
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse|Response
     *
     * @throws CoreApplicationRequestException
     */
    public function indexAction(Request $request, string $website, int $test_id): Response
    {
        $viewRedirectParameters = [
            'route' => 'view_test_progress',
            'parameters' => [
                'website' => $website,
                'test_id' => $test_id
            ]
        ];

        $redirectParametersAsString = base64_encode(json_encode($viewRedirectParameters));

        $response = $this->cacheableResponseFactory->createResponse($request, [
            'website' => $website,
            'redirect' => $redirectParametersAsString
        ]);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
            return $response;
        }

        $user = $this->userManager->getUser();
        $test = $this->testService->get($website, $test_id);

        if ($test->getWebsite() != $website) {
            return new RedirectResponse($this->generateUrl(
                'view_test_results_failed_no_urls_detected',
                [
                    'website' => $test->getWebsite(),
                    'test_id' => $test_id
                ]
            ));
        }

        $testStateIsCorrect = Test::STATE_FAILED_NO_SITEMAP === $test->getState();

        if (!$testStateIsCorrect || !SystemUserService::isPublicUser($user)) {
            return new RedirectResponse($this->generateUrl(
                'view_test_progress',
                [
                    'website' => $website,
                    'test_id' => $test_id
                ]
            ));
        }

        return $this->renderWithDefaultViewParameters(
            'test-results-failed-no-urls-detected.html.twig',
            [
                'website' => $this->urlViewValues->create($website),
                'redirect' => $redirectParametersAsString,
            ],
            $response
        );
    }
}
