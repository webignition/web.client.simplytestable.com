<?php

namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Model\TestList;
use SimplyTestable\WebClientBundle\Model\TestOptions;
use Pdp\PublicSuffixListManager as PdpPublicSuffixListManager;
use Pdp\Parser as PdpParser;
use SimplyTestable\WebClientBundle\Model\User;

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
     * @param CoreApplicationHttpClient $coreApplicationHttpClient
     */
    public function __construct(CoreApplicationHttpClient $coreApplicationHttpClient)
    {
        $this->coreApplicationHttpClient = $coreApplicationHttpClient;
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

        if ($remoteTest instanceof RemoteTest && $remoteTest->getId() != $test->getTestId()) {
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
    public function start($canonicalUrl, TestOptions $testOptions, $testType = 'full site')
    {
        $this->setCustomCookieDomain(
            $testOptions,
            $this->getCookieDomain($canonicalUrl)
        );

        $responseData = $this->coreApplicationHttpClient->post(
            'test_start',
            [
                'canonical_url' => $canonicalUrl,
            ],
            array_merge(
                [
                    'type' => $testType,
                ],
                $testOptions->__toArray()
            ),
            [
                CoreApplicationHttpClient::OPT_EXPECT_JSON_RESPONSE => true,
            ]
        );

        return new RemoteTest($responseData);
    }

    /**
     * @param string $canonicalUrl
     *
     * @return string
     */
    private function getCookieDomain($canonicalUrl)
    {
        $pslManager = new PdpPublicSuffixListManager();
        $parser = new PdpParser($pslManager->getList());

        return $parser->parseUrl($canonicalUrl)->host->registerableDomain;
    }

    /**
     * @param TestOptions $testOptions
     * @param string $domain
     */
    private function setCustomCookieDomain(TestOptions $testOptions, $domain)
    {
        if (!$testOptions->hasFeatureOptions('cookies')) {
            return;
        }

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
     * @param User $user
     * @return bool
     *
     * @throws CoreApplicationRequestException
     */
    public function owns(User $user)
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
            $remoteTestData = null;

            try {
                $remoteTestData = $this->coreApplicationHttpClient->get(
                    'test_status',
                    [
                        'canonical_url' => (string)$this->test->getWebsite(),
                        'test_id' => $this->test->getTestId(),
                    ],
                    [
                        CoreApplicationHttpClient::OPT_EXPECT_JSON_RESPONSE => true,
                    ]
                );
            } catch (InvalidContentTypeException $invalidContentTypeException) {
            }

            if (is_array($remoteTestData)) {
                $this->remoteTest = new RemoteTest($remoteTestData);
            } else {
                $this->remoteTest = false;
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
     * @throws InvalidContentTypeException
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
     * @throws InvalidContentTypeException
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
        $responseData = $this->coreApplicationHttpClient->post(
            'test_retest',
            [
                'canonical_url' => (string)$website,
                'test_id' => $testId
            ],
            [],
            [
                CoreApplicationHttpClient::OPT_EXPECT_JSON_RESPONSE => true,
            ]
        );

        return new RemoteTest($responseData);
    }

    public function lock()
    {
        $this->coreApplicationHttpClient->post('test_set_private', [
            'canonical_url' => (string)$this->test->getWebsite(),
            'test_id' => $this->test->getTestId()
        ]);
    }

    public function unlock()
    {
        $this->coreApplicationHttpClient->post('test_set_public', [
            'canonical_url' => (string)$this->test->getWebsite(),
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
        return $this->coreApplicationHttpClient->get(
            'tests_list_websites',
            [
                'exclude-states' => [
                    'cancelled',
                    'rejected'
                ],
                'exclude-current' => 1,
            ],
            [
                CoreApplicationHttpClient::OPT_EXPECT_JSON_RESPONSE => true,
            ]
        );
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
            $finishedCount = $this->coreApplicationHttpClient->get(
                'tests_list_count',
                [
                    'exclude-states' => ['rejected'],
                    'exclude-current' => 1,
                    'url-filter' => $filter,
                ],
                [
                    CoreApplicationHttpClient::OPT_EXPECT_JSON_RESPONSE => true,
                ]
            );
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

        $responseData = $this->coreApplicationHttpClient->get(
            'tests_list',
            $routeParameters,
            [
                CoreApplicationHttpClient::OPT_EXPECT_JSON_RESPONSE => true,
            ]
        );
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
            $remoteTestData = $this->coreApplicationHttpClient->get(
                'test_latest',
                [
                    'canonical_url' => $canonicalUrl,
                ],
                [
                    CoreApplicationHttpClient::OPT_EXPECT_JSON_RESPONSE => true,
                ]
            );

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
