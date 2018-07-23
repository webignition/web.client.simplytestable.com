<?php

namespace App\Controller\View\Test\Results;

use App\Controller\AbstractBaseViewController;
use App\Entity\Test\Test;
use App\Exception\CoreApplicationRequestException;
use App\Interfaces\Controller\RequiresValidUser;
use App\Services\CacheValidatorService;
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

class FailedNoUrlsDetectedController extends AbstractBaseViewController implements RequiresValidUser
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

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param TestService $testService
     * @param UrlViewValuesService $urlViewValues
     * @param UserManager $userManager
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        TestService $testService,
        UrlViewValuesService $urlViewValues,
        UserManager $userManager
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheValidator);

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
    public function indexAction(Request $request, $website, $test_id)
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $viewRedirectParameters = [
            'route' => 'view_test_progress_index_index',
            'parameters' => [
                'website' => $website,
                'test_id' => $test_id
            ]
        ];

        $redirectParametersAsString = base64_encode(json_encode($viewRedirectParameters));

        $response = $this->cacheValidator->createResponse($request, [
            'website' => $website,
            'redirect' => $redirectParametersAsString
        ]);

        if ($this->cacheValidator->isNotModified($response)) {
            return $response;
        }

        $user = $this->userManager->getUser();

        $test = $this->testService->get($website, $test_id);

        if ($test->getWebsite() != $website) {
            return new RedirectResponse($this->generateUrl(
                'app_test_redirector',
                [
                    'website' => $test->getWebsite(),
                    'test_id' => $test_id
                ]
            ));
        }

        $testStateIsCorrect = Test::STATE_FAILED_NO_SITEMAP === $test->getState();

        if (!$testStateIsCorrect || !SystemUserService::isPublicUser($user)) {
            return new RedirectResponse($this->generateUrl(
                'view_test_progress_index_index',
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
