<?php

namespace App\Controller;

use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Services\TestRetriever;
use App\Exception\CoreApplicationRequestException;
use App\Services\RemoteTestService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use webignition\NormalisedUrl\NormalisedUrl;

/**
 * Redirects valid-looking URLs to those that match actual controller actions
 */
class RedirectController extends AbstractController
{
    const TASK_RESULTS_URL_PATTERN = '/\/[0-9]+\/[0-9]+\/results\/?$/';

    public function testAction(
        TestRetriever $testRetriever,
        RemoteTestService $remoteTestService,
        Request $request,
        $website,
        $test_id = null
    ): RedirectResponse {
        $isTaskResultsUrl = preg_match(self::TASK_RESULTS_URL_PATTERN, $website) > 0;

        if ($isTaskResultsUrl) {
            $routeParameters = $this->getWebsiteAndTestIdAndTaskIdFromWebsite($website);

            return new RedirectResponse($this->generateUrl(
                'view_task_results_verbose',
                $routeParameters
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
            $testIdentifier = $remoteTestService->retrieveLatest($normalisedWebsite);

            if ($testIdentifier) {
                return new RedirectResponse($this->generateUrl(
                    'redirect_website_test',
                    $testIdentifier->toArray()
                ));
            }
        }

        if ($hasWebsite && $hasTestId) {
            $test = null;

            try {
                $test = $testRetriever->retrieve($normalisedTestId);
            } catch (CoreApplicationRequestException $coreApplicationRequestException) {
                // Do nothing
            } catch (InvalidContentTypeException $invalidContentTypeException) {
                // Do nothing
            } catch (InvalidCredentialsException $invalidCredentialsException) {
                // Do nothing
            }

            if (null === $test) {
                return new RedirectResponse($this->generateUrl(
                    'redirect_website',
                    [
                        'website' => $website
                    ]
                ));
            }

            $routeName = $test->isFinished()
                ? 'view_test_results'
                : 'view_test_progress';

            return new RedirectResponse($this->generateUrl(
                $routeName,
                [
                    'website' => $normalisedWebsite,
                    'test_id' => $normalisedTestId
                ]
            ));
        }

        return new RedirectResponse($this->generateUrl('view_dashboard'));
    }

    /**
     * @param $website
     * @param int $test_id
     * @param int $task_id
     *
     * @return RedirectResponse
     */
    public function taskAction(string $website, int $test_id, int $task_id): RedirectResponse
    {
        return new RedirectResponse($this->generateUrl(
            'view_task_results',
            [
                'website' => $website,
                'test_id' => $test_id,
                'task_id' => $task_id,
                'trailingSlash' => '',
            ]
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
