<?php

namespace SimplyTestable\WebClientBundle\Services;

use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Message\Request;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Model\TestList;
use SimplyTestable\WebClientBundle\Model\TestOptions;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use webignition\WebResource\JsonDocument\JsonDocument;
use Pdp\PublicSuffixListManager as PdpPublicSuffixListManager;
use Pdp\Parser as PdpParser;
use webignition\WebResource\WebResource;

class RemoteTestService extends CoreApplicationService
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
     * @var CoreApplicationRouter
     */
    private $coreApplicationRouter;

    /**
     * @param WebResourceService $webResourceService
     * @param CoreApplicationRouter $coreApplicationRouter
     */
    public function __construct(
        WebResourceService $webResourceService,
        CoreApplicationRouter $coreApplicationRouter
    ) {
        parent::__construct($webResourceService);

        $this->coreApplicationRouter = $coreApplicationRouter;
    }

    /**
     * @param Test $test
     *
     * @throws WebResourceException
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
     * @return Test
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @param string $canonicalUrl
     * @param TestOptions $testOptions
     * @param string $testType
     *
     * @return JsonDocument
     *
     * @throws WebResourceException
     */
    public function start($canonicalUrl, TestOptions $testOptions, $testType = 'full site')
    {
        $this->setCustomCookieDomain(
            $testOptions,
            $this->getCookieDomain($canonicalUrl)
        );

        $requestUrl = $this->coreApplicationRouter->generate('test_start', array_merge([
            'canonical_url' => $canonicalUrl,
            'type' => $testType,
        ], $testOptions->__toArray()));

        $httpClientService = $this->webResourceService->getHttpClientService();
        $httpRequest = $httpClientService->getRequest($requestUrl);

        $this->addAuthorisationToRequest($httpRequest);

        /* @var $response JsonDocument */
        try {
            $response = $this->webResourceService->get($httpRequest);
        } catch (CurlException $curlException) {
            throw $curlException;
        }

        return $response;
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
     * @return bool
     *
     * @throws WebResourceException
     * @throws CurlException
     */
    public function authenticate()
    {
        if ($this->owns()) {
            return true;
        }

        return $this->isPublic();
    }

    /**
     * @return bool
     * @throws WebResourceException
     */
    public function owns()
    {
        if ($this->getUser()->getUsername() == $this->getTest()->getUser()) {
            return true;
        }

        try {
            $remoteTest = $this->get();
            $owners = $remoteTest->getOwners();

            return $owners->contains($this->getUser()->getUsername());
        } catch (WebResourceException $webResourceException) {
            if ($webResourceException->getCode() == 403) {
                return false;
            }

            throw $webResourceException;
        }
    }

    /**
     * @return bool
     *
     * @throws WebResourceException
     */
    public function isPublic()
    {
        return $this->get()->getIsPublic();
    }

    /**
     * @return bool|RemoteTest
     *
     * @throws WebResourceException
     * @throws CurlException
     */
    public function get()
    {
        if (is_null($this->remoteTest)) {
            $httpClientService = $this->webResourceService->getHttpClientService();

            $requestUrl = $this->coreApplicationRouter->generate('test_status', [
                'canonical_url' => (string)$this->test->getWebsite(),
                'test_id' => $this->test->getTestId(),
            ]);

            $httpRequest = $httpClientService->getRequest($requestUrl);

            $this->addAuthorisationToRequest($httpRequest);

            /* @var JsonDocument $response */
            $remoteJsonDocument = $this->webResourceService->get($httpRequest);

            if ($remoteJsonDocument instanceof JsonDocument) {
                $this->remoteTest = new RemoteTest($remoteJsonDocument->getContentArray());
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
     * @return WebResource
     * @throws WebResourceException
     */
    public function cancel()
    {
        return $this->cancelByTestProperties($this->test->getTestId(), $this->test->getWebsite());
    }

    /**
     * @param int $testId
     * @param string $website
     *
     * @return WebResource
     * @throws WebResourceException
     */
    public function cancelByTestProperties($testId, $website)
    {
        $requestUrl = $this->coreApplicationRouter->generate('test_cancel', [
            'canonical_url' => (string)$website,
            'test_id' => $testId,
        ]);

        $httpRequest = $this->webResourceService->getHttpClientService()->getRequest($requestUrl);

        $this->addAuthorisationToRequest($httpRequest);

        return $this->webResourceService->get($httpRequest);
    }

    /**
     * @param int $testId
     * @param string $website
     *
     * @return WebResource
     * @throws WebResourceException
     */
    public function retest($testId, $website)
    {
        $requestUrl = $this->coreApplicationRouter->generate('test_retest', [
            'canonical_url' => (string)$website,
            'test_id' => $testId
        ]);

        $httpRequest = $this->webResourceService->getHttpClientService()->getRequest($requestUrl);

        $this->addAuthorisationToRequest($httpRequest);

        return $this->webResourceService->get($httpRequest);
    }

    public function lock()
    {
        $requestUrl = $this->coreApplicationRouter->generate('test_set_private', [
            'canonical_url' => (string)$this->getTest()->getWebsite(),
            'test_id' => $this->getTest()->getTestId()
        ]);

        $request = $this->webResourceService->getHttpClientService()->getRequest($requestUrl);

        $this->addAuthorisationToRequest($request);
        $this->webResourceService->get($request);
    }

    public function unlock()
    {
        $requestUrl = $this->coreApplicationRouter->generate('test_set_public', [
            'canonical_url' => (string)$this->getTest()->getWebsite(),
            'test_id' => $this->getTest()->getTestId()
        ]);

        $request = $this->webResourceService->getHttpClientService()->getRequest($requestUrl);

        $this->addAuthorisationToRequest($request);
        $this->webResourceService->get($request);
    }

    /**
     * @param int $limit
     *
     * @return TestList
     */
    public function getRecent($limit = 3)
    {
        $requestUrl = $this->coreApplicationRouter->generate('tests_list', [
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

        $request = $this->webResourceService->getHttpClientService()->getRequest($requestUrl);

        return $this->getList($request);
    }

    /**
     * @return TestList
     */
    public function getCurrent()
    {
        $requestUrl = $this->coreApplicationRouter->generate('tests_list', [
            'limit' => 100,
            'offset' => 0,
            'exclude-finished' => 1,
        ]);

        $request = $this->webResourceService->getHttpClientService()->getRequest($requestUrl);

        return $this->getList($request);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @param string $filter
     *
     * @return TestList
     */
    public function getFinished($limit, $offset, $filter = null)
    {
        $requestUrl = $this->coreApplicationRouter->generate('tests_list', [
            'limit' => $limit,
            'offset' => $offset,
            'exclude-states' => ['rejected'],
            'exclude-current' => 1,
            'url-filter' => $filter,
        ]);

        $request = $this->webResourceService->getHttpClientService()->getRequest($requestUrl);

        return $this->getList($request);
    }

    /**
     * @return array
     */
    public function getFinishedWebsites()
    {
        $requestUrl = $this->coreApplicationRouter->generate('tests_list_websites', [
            'exclude-states' => [
                'cancelled',
                'rejected'
            ],
            'exclude-current' => 1,
        ]);

        $request = $this->webResourceService->getHttpClientService()->getRequest($requestUrl);

        $this->addAuthorisationToRequest($request);

        try {
            /* @var $responseDocument JsonDocument */
            $responseDocument = $this->webResourceService->get($request);
            $list = json_decode($responseDocument->getContent());
        } catch (CurlException $curlException) {
            return [];
        } catch (WebResourceException $webResourceServiceException) {
            return [];
        }

        return $list;
    }

    /**
     * @param null $filter
     *
     * @return int
     */
    public function getFinishedCount($filter = null)
    {
        $requestUrl = $this->coreApplicationRouter->generate('tests_list_count', [
            'exclude-states' => ['rejected'],
            'exclude-current' => 1,
            'url-filter' => $filter,
        ]);

        $request = $this->webResourceService->getHttpClientService()->getRequest($requestUrl);

        $this->addAuthorisationToRequest($request);

        try {
            /* @var $responseDocument JsonDocument */
            $responseDocument = $this->webResourceService->get($request);
            $count = json_decode($responseDocument->getContent());
        } catch (CurlException $curlException) {
            return null;
        } catch (WebResourceException $webResourceServiceException) {
            return null;
        }

        return $count;
    }

    /**
     * @param Request $request
     *
     * @return TestList
     */
    private function getList(Request $request)
    {
        $this->addAuthorisationToRequest($request);

        $list = new TestList();

        try {
            /* @var $responseDocument JsonDocument */
            $responseDocument = $this->webResourceService->get($request);
            $responseData = $responseDocument->getContentArray();

            $list->setMaxResults($responseData['max_results']);
            $list->setLimit($responseData['limit']);
            $list->setOffset($responseData['offset']);

            foreach ($responseData['jobs'] as $remoteTestData) {
                $list->addRemoteTest(new RemoteTest($remoteTestData));
            }
        } catch (CurlException $curlException) {
        } catch (WebResourceException $webResourceServiceException) {
        }

        return $list;
    }

    /**
     * @param $canonicalUrl
     * @return bool|null|RemoteTest
     */
    public function retrieveLatest($canonicalUrl)
    {
        $requestUrl = $this->coreApplicationRouter->generate('test_latest', [
            'canonical_url' => $canonicalUrl,
        ]);

        $request = $this->webResourceService->getHttpClientService()->getRequest($requestUrl);

        $this->addAuthorisationToRequest($request);

        try {
            $remoteJsonDocument = $this->webResourceService->get($request);

            if ($remoteJsonDocument instanceof JsonDocument) {
                return new RemoteTest($remoteJsonDocument->getContentArray());
            }
        } catch (CurlException $curlException) {
            // Intentionally swallow
        } catch (WebResourceException $webResourceException) {
            // Intentionally swallow
        }

        return null;
    }
}
