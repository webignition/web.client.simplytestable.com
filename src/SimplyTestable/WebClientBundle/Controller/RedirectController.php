<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Repository\TestRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use webignition\NormalisedUrl\NormalisedUrl;

/**
 * Redirects valid-looking URLs to those that match actual controller actions
 */
class RedirectController extends BaseController
{
    const TASK_RESULTS_URL_PATTERN = '/\/[0-9]+\/[0-9]+\/results\/?$/';

    /**
     * @var string
     */
    private $website = null;

    /**
     * @var null int
     */
    private $test_id = null;

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
        $testService = $this->container->get('simplytestable.services.testservice');
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $logger = $this->container->get('logger');

        /* @var TestRepository $testRepository */
        $testRepository = $entityManager->getRepository(Test::class);

        $remoteTestService->setUser($this->getUser());

        $isTaskResultsUrl = preg_match(self::TASK_RESULTS_URL_PATTERN, $website) > 0;

        if ($isTaskResultsUrl) {
            $routeParameters = $this->getWebsiteAndTestIdAndTaskIdFromWebsite($website);

            return $this->redirect($this->generateUrl(
                'view_test_task_results_index_index_verbose',
                $routeParameters,
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $this->prepareNormalisedWebsiteAndTestId($request, $website, $test_id);

        $hasWebsite = !is_null($this->website);
        $hasTestId = !is_null($this->test_id);

        if ($hasWebsite && !$hasTestId) {
            $latestRemoteTest = $remoteTestService->retrieveLatest($this->website);

            if ($latestRemoteTest instanceof RemoteTest) {
                return $this->redirect($this->generateUrl(
                    'app_test_redirector',
                    [
                        'website' => $latestRemoteTest->getWebsite(),
                        'test_id' => $latestRemoteTest->getId()
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ));
            }

            if ($testRepository->hasForWebsite($this->website)) {
                $testId = $testRepository->getLatestId($this->website);

                return $this->redirect($this->getRedirectorUrl($this->website, $testId));
            }

            return $this->redirect($this->generateUrl(
                'view_dashboard_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        if ($hasWebsite && $hasTestId) {
            $test = null;

            try {
                $test = $testService->get($this->website, $this->test_id);
            } catch (WebResourceException $webResourceException) {
                $logger->error(sprintf(
                    'RedirectController::webResourceException %s',
                    $webResourceException->getResponse()->getStatusCode()
                ));
                $logger->error('[request]');
                $logger->error($webResourceException->getRequest());
                $logger->error('[response]');
                $logger->error($webResourceException->getResponse());

                return $this->redirect($this->getWebsiteUrl($website));
            }

            if (in_array($test->getState(), $this->testFinishedStates)) {
                return $this->redirect($this->getResultsUrl($this->website, $this->test_id));
            } else {
                return $this->redirect($this->getProgressUrl($this->website, $this->test_id));
            }
        }

        return $this->redirect($this->generateUrl(
            'view_dashboard_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    public function taskAction($website, $test_id = null, $task_id = null) {
        return $this->redirect($this->getTaskResultsUrl($website, $test_id, $task_id));
    }

    private function prepareNormalisedWebsiteAndTestId(Request $request, $website, $test_id)
    {
        $requestWebsite = $request->request->get('website');
        if (empty($requestWebsite)) {
            $requestWebsite = $request->query->get('website');

            if (empty($requestWebsite)) {
                $requestWebsite = $website;
            }
        }

        if (empty($requestWebsite) && empty($test_id)) {
            return;
        }

        $normalisedWebsite = new NormalisedUrl($requestWebsite);

        if (!$normalisedWebsite->hasScheme()) {
            $normalisedWebsite->setScheme(self::DEFAULT_WEBSITE_SCHEME);
        }

        if (!$normalisedWebsite->hasHost()) {
            $normalisedWebsite = new NormalisedUrl($website . '/' . $test_id);

            $this->website = (string)$normalisedWebsite;
            $this->test_id = null;

            return;
        }

        if (is_int($test_id) || ctype_digit($test_id)) {
            $this->website = (string)$normalisedWebsite;
            $this->test_id = (int)$test_id;

            return;
        }

        $pathParts = explode('/', $normalisedWebsite->getPath());
        $pathPartLength = count($pathParts);

        for ($pathPartIndex = $pathPartLength - 1; $pathPartIndex >= 0; $pathPartIndex--) {
            if (ctype_digit($pathParts[$pathPartIndex])) {
                $normalisedWebsite->setPath('');

                $this->website = (string)$normalisedWebsite;
                $this->test_id = (int)$pathParts[$pathPartIndex];

                return;
            }
        }

        $this->website = (string)$normalisedWebsite;
        $this->test_id = null;

        return;
    }


    /**
     *
     * @param string $website
     * @return string
     */
    protected function getWebsiteurl($website) {
        return $this->generateUrl(
            'app_website',
            array(
                'website' => $website
            ),
            true
        );
    }


    /**
     *
     * @param string $website
     * @param string $test_id
     * @return string
     */
    protected function getRedirectorUrl($website, $test_id) {
        return $this->generateUrl(
            'app_test_redirector',
            array(
                'website' => $website,
                'test_id' => $test_id
            ),
            true
        );
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
