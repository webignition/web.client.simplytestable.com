<?php

namespace App\Services;

use App\Model\Test as TestModel;
use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\TestIdentifier;
use App\Model\TestOptions;

class RemoteTestService
{
    private $coreApplicationHttpClient;
    private $jsonResponseHandler;
    private $registerableDomainService;

    public function __construct(
        CoreApplicationHttpClient $coreApplicationHttpClient,
        JsonResponseHandler $jsonResponseHandler,
        RegisterableDomainService $registerableDomainService
    ) {
        $this->coreApplicationHttpClient = $coreApplicationHttpClient;
        $this->jsonResponseHandler = $jsonResponseHandler;
        $this->registerableDomainService = $registerableDomainService;
    }

    /**
     * @param string $canonicalUrl
     * @param TestOptions $testOptions
     * @param string $testType
     *
     * @return TestIdentifier
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function start(
        string $canonicalUrl,
        TestOptions $testOptions,
        string $testType = TestModel::TYPE_FULL_SITE
    ): TestIdentifier {
        if ($testOptions->hasFeatureOptions('cookies')) {
            $cookieDomain = $this->registerableDomainService->getRegisterableDomain($canonicalUrl);

            if (empty($cookieDomain)) {
                $testOptions->removeFeatureOptions('cookies');
            } else {
                $this->setCustomCookieDomain($testOptions, $cookieDomain);
            }
        }

        $response = $this->coreApplicationHttpClient->post(
            'test_start',
            [],
            array_merge(
                [
                    'url' => $canonicalUrl,
                    'type' => $testType,
                ],
                $testOptions->__toArray()
            )
        );

        $responseData = $this->jsonResponseHandler->handle($response);

        return new TestIdentifier(
            $responseData['id'],
            $responseData['website']
        );
    }

    private function setCustomCookieDomain(TestOptions $testOptions, string $domain)
    {
        $cookieOptions = $testOptions->getFeatureOptions('cookies');
        $cookies = $cookieOptions['cookies'];

        foreach ($cookies as $index => $cookie) {
            if (isset($cookie['name']) && !empty($cookie['name'])) {
                if (!isset($cookie['path'])) {
                    $cookie['path'] = '/';
                }

                if (!isset($cookie['domain'])) {
                    $cookie['domain'] = '.' . $domain;
                }

                $cookies[$index] = $cookie;
            }
        }

        $cookieOptions['cookies'] = $cookies;
        $testOptions->setFeatureOptions('cookies', $cookieOptions);
    }

    /**
     * @param int $testId
     *
     * @return array|null
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function getSummaryData(int $testId): ?array
    {
        $remoteTestData = null;

        try {
            $response = $this->coreApplicationHttpClient->get(
                'test_status',
                [
                    'test_id' => $testId,
                ]
            );

            $remoteTestData = $this->jsonResponseHandler->handle($response);
        } catch (InvalidContentTypeException $invalidContentTypeException) {
        }

        return $remoteTestData;
    }

    /**
     * @param int $testId
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function cancel(int $testId)
    {
        $this->cancelByTestProperties($testId);
    }

    /**
     * @param int $testId
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function cancelByTestProperties(int $testId)
    {
        $this->coreApplicationHttpClient->post('test_cancel', [
            'test_id' => $testId,
        ]);
    }

    /**
     * @param int $testId
     *
     * @return TestIdentifier
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function retest(int $testId): TestIdentifier
    {
        $response = $this->coreApplicationHttpClient->post(
            'test_retest',
            [
                'test_id' => $testId
            ]
        );

        $responseData = $this->jsonResponseHandler->handle($response);

        return new TestIdentifier(
            $responseData['id'],
            $responseData['website']
        );
    }

    public function lock(int $testId)
    {
        $this->coreApplicationHttpClient->post('test_set_private', [
            'test_id' => $testId,
        ]);
    }

    public function unlock(int $testId)
    {
        $this->coreApplicationHttpClient->post('test_set_public', [
            'test_id' => $testId,
        ]);
    }

    /**
     * @return string[]
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function getFinishedWebsites(): array
    {
        $response = $this->coreApplicationHttpClient->get(
            'tests_list_websites',
            [
                'exclude-states' => [
                    'cancelled',
                    'rejected'
                ],
                'exclude-current' => 1,
            ]
        );

        return $this->jsonResponseHandler->handle($response);
    }

    public function getFinishedCount(?string $filter = null): int
    {
        $finishedCount = 0;

        try {
            $response = $this->coreApplicationHttpClient->get(
                'tests_list_count',
                [
                    'exclude-states' => ['rejected'],
                    'exclude-current' => 1,
                    'url-filter' => $filter,
                ]
            );

            $finishedCount = $this->jsonResponseHandler->handle($response);
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            // Don't care
        } catch (InvalidContentTypeException $invalidContentTypeException) {
            // Don't care
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            // Don't care
        }

        return $finishedCount;
    }

    public function retrieveLatest(string $canonicalUrl): ?TestIdentifier
    {
        $testIdentifier = null;

        try {
            $response = $this->coreApplicationHttpClient->get(
                'test_latest',
                [
                    'canonical_url' => $canonicalUrl,
                ]
            );

            $responseData = $this->jsonResponseHandler->handle($response);

            $testIdentifier = new TestIdentifier(
                $responseData['id'],
                $responseData['website']
            );
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            // Don't care
        } catch (InvalidContentTypeException $invalidContentTypeException) {
            // Don't care
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            // Don't care
        }

        return $testIdentifier;
    }

    public function isAuthorised(int $testId): bool
    {
        $isAuthorised = false;

        try {
            $response = $this->coreApplicationHttpClient->get(
                'test_is_authorised',
                [
                    'test_id' => $testId,
                ]
            );

            $isAuthorised = $this->jsonResponseHandler->handle($response);
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            // Don't care
        } catch (InvalidContentTypeException $invalidContentTypeException) {
            // Don't care
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            // Don't care
        }

        return $isAuthorised;
    }
}
