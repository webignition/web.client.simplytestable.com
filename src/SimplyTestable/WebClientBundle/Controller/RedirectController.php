<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use webignition\NormalisedUrl\NormalisedUrl;

/**
 * Redirects valid-looking URLs to those that match actual controller actions
 */
class RedirectController extends Controller
{
    const TASK_RESULTS_URL_PATTERN = '/\/[0-9]+\/[0-9]+\/results\/?$/';

    /**
     * @var string[]
     */
    private $testFinishedStates = [
        Test::STATE_CANCELLED,
        Test::STATE_COMPLETED,
        Test::STATE_FAILED_NO_SITEMAP,
    ];

    /**
     * @param Request $request
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse
     */
    public function testAction(Request $request, $website, $test_id = null)
    {
        $testService = $this->container->get('SimplyTestable\WebClientBundle\Services\TestService');
        $remoteTestService = $this->container->get('SimplyTestable\WebClientBundle\Services\RemoteTestService');
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $logger = $this->container->get('logger');
        $router = $this->container->get('router');

        $testRepository = $entityManager->getRepository(Test::class);

        $isTaskResultsUrl = preg_match(self::TASK_RESULTS_URL_PATTERN, $website) > 0;

        if ($isTaskResultsUrl) {
            $routeParameters = $this->getWebsiteAndTestIdAndTaskIdFromWebsite($website);

            return new RedirectResponse($router->generate(
                'view_test_task_results_index_index_verbose',
                $routeParameters,
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        list ($normalisedWebsite, $normalisedTestId) = $this->createNormalisedWebsiteAndTestId(
            $request,
            $website,
            $test_id
        );

        $hasWebsite = !is_null($normalisedWebsite);
        $hasTestId = !is_null($normalisedTestId);

        if ($hasWebsite && !$hasTestId) {
            $latestRemoteTest = $remoteTestService->retrieveLatest($normalisedWebsite);

            if ($latestRemoteTest instanceof RemoteTest) {
                return new RedirectResponse($router->generate(
                    'app_test_redirector',
                    [
                        'website' => $latestRemoteTest->getWebsite(),
                        'test_id' => $latestRemoteTest->getId()
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ));
            }

            /* @var Test $latestTest */
            $latestTest = $testRepository->findOneBy([
                'website' => (string)$normalisedWebsite,
            ], [
                'testId' => 'DESC',
            ]);

            if (!empty($latestTest)) {
                return new RedirectResponse($router->generate(
                    'app_test_redirector',
                    [
                        'website' => $normalisedWebsite,
                        'test_id' => $latestTest->getTestId(),
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ));
            }

            return new RedirectResponse($router->generate(
                'view_dashboard_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        if ($hasWebsite && $hasTestId) {
            $test = null;

            try {
                $test = $testService->get($normalisedWebsite, $normalisedTestId);
            } catch (CoreApplicationRequestException $coreApplicationRequestException) {
                $logger->error(
                    sprintf(
                        'RedirectController::CoreApplicationRequestException %s',
                        $coreApplicationRequestException->getCode()
                    ),
                    [
                        'request' => $coreApplicationRequestException->getRequest(),
                        'response' => $coreApplicationRequestException->getResponse(),
                    ]
                );

                return new RedirectResponse($router->generate(
                    'app_website',
                    [
                        'website' => $website
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ));
            }

            $routeName = in_array($test->getState(), $this->testFinishedStates)
                ? 'view_test_results_index_index'
                : 'view_test_progress_index_index';

            return new RedirectResponse($router->generate(
                $routeName,
                [
                    'website' => $normalisedWebsite,
                    'test_id' => $normalisedTestId
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        return new RedirectResponse($router->generate(
            'view_dashboard_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @param $website
     * @param string $test_id
     * @param int $task_id
     *
     * @return RedirectResponse
     */
    public function taskAction($website, $test_id, $task_id)
    {
        $router = $this->container->get('router');

        return new RedirectResponse($router->generate(
            'view_test_task_results_index_index',
            [
                'website' => $website,
                'test_id' => $test_id,
                'task_id' => $task_id
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @param Request $request
     * @param string $website
     * @param int $test_id
     *
     * @return array
     */
    private function createNormalisedWebsiteAndTestId(Request $request, $website, $test_id)
    {
        $requestWebsite = $request->request->get('website');
        if (empty($requestWebsite)) {
            $requestWebsite = $request->query->get('website');

            if (empty($requestWebsite)) {
                $requestWebsite = $website;
            }
        }

        if (empty($requestWebsite) && empty($test_id)) {
            return [
                null,
                null,
            ];
        }

        $normalisedWebsite = new NormalisedUrl($requestWebsite);

        if (!$normalisedWebsite->hasScheme()) {
            $normalisedWebsite->setScheme('http');
        }

        if (!$normalisedWebsite->hasHost()) {
            $normalisedWebsite = new NormalisedUrl($website . '/' . $test_id);

            return [
                (string)$normalisedWebsite,
                null,
            ];
        }

        if (is_int($test_id) || ctype_digit($test_id)) {
            return [
                (string)$normalisedWebsite,
                (int)$test_id,
            ];
        }

        $pathParts = explode('/', $normalisedWebsite->getPath());
        $pathPartLength = count($pathParts);

        for ($pathPartIndex = $pathPartLength - 1; $pathPartIndex >= 0; $pathPartIndex--) {
            if (ctype_digit($pathParts[$pathPartIndex])) {
                $normalisedWebsite->setPath('');

                return [
                    (string)$normalisedWebsite,
                    (int)$pathParts[$pathPartIndex],
                ];
            }
        }

        return [
            (string)$normalisedWebsite,
            null,
        ];
    }

    /**
     * @param string $website
     *
     * @return array
     */
    private function getWebsiteAndTestIdAndTaskIdFromWebsite($website)
    {
        $website = rtrim($website, '/');

        $pathParts = explode('/', $website);
        array_pop($pathParts);

        $taskId = array_pop($pathParts);
        $testId = array_pop($pathParts);

        return [
            'website' => implode('/', $pathParts),
            'test_id' => $testId,
            'task_id' => $taskId
        ];
    }
}
