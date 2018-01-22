<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results\FailedNoUrlsDetected;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class IndexController extends BaseViewController implements IEFiltered, RequiresValidUser
{
    /**
     * @param Request $request
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse|Response
     *
     * @throws WebResourceException
     */
    public function indexAction(Request $request, $website, $test_id)
    {
        $testService = $this->container->get('simplytestable.services.testservice');
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');
        $userService = $this->container->get('simplytestable.services.userservice');
        $router = $this->container->get('router');
        $cacheValidatorService = $this->container->get('simplytestable.services.cachevalidator');
        $templating = $this->container->get('templating');
        $urlViewValuesService = $this->container->get('simplytestable.services.urlviewvalues');

        $viewRedirectParameters = [
            'route' => 'view_test_progress_index_index',
            'parameters' => [
                'website' => $website,
                'test_id' => $test_id
            ]
        ];

        $redirectParametersAsString = base64_encode(json_encode($viewRedirectParameters));

        $response = $cacheValidatorService->createResponse($request, [
            'website' => $website,
            'redirect' => $redirectParametersAsString
        ]);

        if ($cacheValidatorService->isNotModified($response)) {
            return $response;
        }

        $user = $userService->getUser();
        $remoteTestService->setUser($user);

        $test = $testService->get($website, $test_id);

        if ($test->getWebsite() != $website) {
            $redirectUrl = $router->generate(
                'app_test_redirector',
                [
                    'website' => $test->getWebsite(),
                    'test_id' => $test_id
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return new RedirectResponse($redirectUrl);
        }

        $testStateIsCorrect = Test::STATE_FAILED_NO_SITEMAP === $test->getState();
        $isPublicUser = $userService->isPublicUser($user);

        if (!$testStateIsCorrect || !$isPublicUser) {
            $redirectUrl = $router->generate(
                'view_test_progress_index_index',
                [
                    'website' => $website,
                    'test_id' => $test_id
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return new RedirectResponse($redirectUrl);
        }

        $viewData = [
            'website' => $urlViewValuesService->create($website),
            'redirect' => $redirectParametersAsString,
        ];

        $content = $templating->render(
            'SimplyTestableWebClientBundle:bs3/Test/Results/FailedNoUrlsDetected/Index:index.html.twig',
            array_merge($this->getDefaultViewParameters(), $viewData)
        );

        $response->setContent($content);
        $response->headers->set('content-type', 'text/html');

        return $response;
    }
}
