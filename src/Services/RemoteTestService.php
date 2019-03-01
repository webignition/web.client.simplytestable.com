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
    /**
     * @var RemoteTest
     */
    private $remoteTest = null;

    /**
     * @var Test
     */
    private $test;

    /**
     * @var CoreApplicationHttpClient
     */
    private $coreApplicationHttpClient;

    /**
     * @var JsonResponseHandler
     */
    private $jsonResponseHandler;

    /**
     * @var RegisterableDomainService
     */
    private $registerableDomainService;

    /**
     * @param CoreApplicationHttpClient $coreApplicationHttpClient
     * @param JsonResponseHandler $jsonResponseHandler
     * @param RegisterableDomainService $registerableDomainService
     */
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
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function setTest(Test $test)
    {
        $this->test = $test;
        $remoteTest = $this->get();

        if ($remoteTest instanceof RemoteTest && $remoteTest->getId() !== $test->getTestId()) {
            $this->remoteTest = null;
        }
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

    /**
     * @param TestOptions $testOptions
     * @param string $domain
     */
    private function setCustomCookieDomain(TestOptions $testOptions, $domain)
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
     * @param UserInterface $user
     *
     * @return bool
     *
     * @throws CoreApplicationRequestException
     */
    public function owns(UserInterface $user)
    {
        if ($user->getUsername() == $this->test->getUser()) {
            return true;
        }

        try {
            $remoteTest = $this->get();
            $owners = $remoteTest->getOwners();

            return $owners->contains($user->getUsername());
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            // Does not own
        }

        return false;
    }

    /**
     * @return bool|RemoteTest
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function get()
    {
        if (is_null($this->remoteTest)) {
            try {
                $response = $this->coreApplicationHttpClient->get(
                    'test_status',
                    [
                        'canonical_url' => $this->test->getWebsite(),
                        'test_id' => $this->test->getTestId(),
                    ]
                );

                $remoteTestData = $this->jsonResponseHandler->handle($response);
                $this->remoteTest = new RemoteTest($remoteTestData);
            } catch (InvalidContentTypeException $invalidContentTypeException) {
                return false;
            }
        }

        return $this->remoteTest;
    }

    /**
     * @param RemoteTest $remoteTest
     */
    public function set(RemoteTest $remoteTest)
    {
        $this->remoteTest = $remoteTest;
    }

    /**
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function cancel()
    {
        $this->cancelByTestProperties($this->test->getTestId(), $this->test->getWebsite());
    }

    /**
     * @param int $testId
     * @param string $website
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function cancelByTestProperties($testId, $website)
    {
        $this->coreApplicationHttpClient->post('test_cancel', [
            'canonical_url' => (string)$website,
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
    public function retest($testId, $website)
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

    public function lock()
    {
        $this->coreApplicationHttpClient->post('test_set_private', [
            'canonical_url' => $this->test->getWebsite(),
            'test_id' => $this->test->getTestId()
        ]);
    }

    public function unlock()
    {
        $this->coreApplicationHttpClient->post('test_set_public', [
            'canonical_url' => $this->test->getWebsite(),
            'test_id' => $this->test->getTestId()
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
    public function getRecent($limit = 3)
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
    public function getFinished($limit, $offset, $filter = null)
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
    public function getFinishedWebsites()
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

    /**
     * @param string|null $filter
     *
     * @return int
     */
    public function getFinishedCount($filter = null)
    {
        $finishedCount = null;

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
    private function getList(array $routeParameters)
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

    /**
     * @param $canonicalUrl
     *
     * @return RemoteTest|null
     */
    public function retrieveLatest($canonicalUrl)
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
