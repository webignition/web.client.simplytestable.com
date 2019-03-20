<?php

namespace App\Controller\View\Test\Results;

use App\Controller\AbstractBaseViewController;
use App\Entity\Test;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\SystemUserService;
use App\Services\TestRetriever;
use App\Services\UrlViewValuesService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class FailedNoUrlsDetectedController extends AbstractBaseViewController
{
    private $urlViewValues;
    private $userManager;
    private $testRetriever;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        UrlViewValuesService $urlViewValues,
        UserManager $userManager,
        TestRetriever $testRetriever
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

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
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
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

        $testModel = $this->testRetriever->retrieve($test_id);

        if ($website !== $testModel->getWebsite()) {
            return new RedirectResponse($this->generateUrl(
                'view_test_results_failed_no_urls_detected',
                [
                    'website' => $testModel->getWebsite(),
                    'test_id' => $test_id
                ]
            ));
        }

        $user = $this->userManager->getUser();
        $testStateIsCorrect = Test::STATE_FAILED_NO_SITEMAP === $testModel->getState();

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
