<?php

namespace App\Services;

use App\Entity\Test\Test;
use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\RemoteTest\RemoteTest;
use App\Model\TestList;
use App\Model\TestOptions;
use webignition\SimplyTestableUserInterface\UserInterface;

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
     * @param Test $test
     */
    public function setTest(Test $test)
    {
    }

    /**
     * @param string $canonicalUrl
     * @param TestOptions $testOptions
     * @param string $testType
     *
     * @return RemoteTest
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function start(
        string $canonicalUrl,
        TestOptions $testOptions,
        string $testType = Test::TYPE_FULL_SITE
    ): RemoteTest {
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
            [
                'canonical_url' => $canonicalUrl,
            ],
            array_merge(
                [
                    'type' => $testType,
                ],
                $testOptions->__toArray()
            )
        );

        $responseData = $this->jsonResponseHandler->handle($response);

        return new RemoteTest($responseData);
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
     * @param Test $test
     * @param UserInterface $user
     *
     * @return bool
     *
     * @throws CoreApplicationRequestException
     */
    public function owns(Test $test, UserInterface $user): bool
    {
        if ($user->getUsername() === $test->getUser()) {
            return true;
        }

        try {
            $remoteTest = $this->get($test);
            if (empty($remoteTest)) {
                return false;
            }

            $owners = $remoteTest->getOwners();

            return $owners->contains($user->getUsername());
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            // Does not own
        }

        return false;
    }

    /**
     * @param Test $test
     *
     * @return RemoteTest|null
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function get(?Test $test): ?RemoteTest
    {
        $remoteTest = null;

        if (null === $test) {
            return $remoteTest;
        }

        try {
            $response = $this->coreApplicationHttpClient->get(
                'test_status',
                [
                    'canonical_url' => $test->getWebsite(),
                    'test_id' => $test->getTestId(),
                ]
            );

            $remoteTestData = $this->jsonResponseHandler->handle($response);
            $remoteTest = new RemoteTest($remoteTestData);
        } catch (InvalidContentTypeException $invalidContentTypeException) {
        }

        return $remoteTest;
    }

    /**
     * @param Test $test
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function cancel(Test $test)
    {
        $this->cancelByTestProperties($test->getTestId(), $test->getWebsite());
    }

    /**
     * @param int $testId
     * @param string $website
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function cancelByTestProperties(int $testId, string $website)
    {
        $this->coreApplicationHttpClient->post('test_cancel', [
            'canonical_url' => $website,
            'test_id' => $testId,
        ]);
    }

    /**
     * @param int $testId
     * @param string $website
     *
     * @return RemoteTest
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function retest(int $testId, string $website): RemoteTest
    {
        $response = $this->coreApplicationHttpClient->post(
            'test_retest',
            [
                'canonical_url' => (string)$website,
                'test_id' => $testId
            ]
        );

        $responseData = $this->jsonResponseHandler->handle($response);

        return new RemoteTest($responseData);
    }

    public function lock(Test $test)
    {
        $this->coreApplicationHttpClient->post('test_set_private', [
            'canonical_url' => $test->getWebsite(),
            'test_id' => $test->getTestId()
        ]);
    }

    public function unlock(Test $test)
    {
        $this->coreApplicationHttpClient->post('test_set_public', [
            'canonical_url' => $test->getWebsite(),
            'test_id' => $test->getTestId()
        ]);
    }

    /**
     * @param int $limit
     *
     * @return TestList
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function getRecent(int $limit = 3): TestList
    {
        return $this->getList([
            'limit' => $limit,
            'offset' => 0,
            'exclude-states' => [
                'new',
                'preparing',
                'resolving',
                'resolved',
                'rejected',
                'queued',
            ],
        ]);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @param string $filter
     *
     * @return TestList
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function getFinished(int $limit, int $offset, ?string $filter = null): TestList
    {
        return $this->getList([
            'limit' => $limit,
            'offset' => $offset,
            'exclude-states' => ['rejected'],
            'exclude-current' => 1,
            'url-filter' => $filter,
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

    /**
     * @param array $routeParameters
     *
     * @return TestList
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    private function getList(array $routeParameters): TestList
    {
        $list = new TestList();

        $response = $this->coreApplicationHttpClient->get('tests_list', $routeParameters);
        $responseData = $this->jsonResponseHandler->handle($response);

        $list->setMaxResults($responseData['max_results']);
        $list->setLimit($responseData['limit']);
        $list->setOffset($responseData['offset']);

        foreach ($responseData['jobs'] as $remoteTestData) {
            $list->addRemoteTest(new RemoteTest($remoteTestData));
        }

        return $list;
    }

    public function retrieveLatest(string $canonicalUrl): ?RemoteTest
    {
        $remoteTest = null;

        try {
            $response = $this->coreApplicationHttpClient->get(
                'test_latest',
                [
                    'canonical_url' => $canonicalUrl,
                ]
            );

            $remoteTestData = $this->jsonResponseHandler->handle($response);

            $remoteTest = new RemoteTest($remoteTestData);
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            // Don't care
        } catch (InvalidContentTypeException $invalidContentTypeException) {
            // Don't care
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            // Don't care
        }

        return $remoteTest;
    }
}
