<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Dashboard;

use SimplyTestable\WebClientBundle\Controller\View\CacheableViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;

abstract class DashboardController extends CacheableViewController implements IEFiltered, RequiresValidUser {

    /**
     * @var \SimplyTestable\WebClientBundle\Services\TestService
     */
    private $testService;


    protected function modifyViewName($viewName) {
        return str_replace(
            ':Dashboard',
            ':bs3/Dashboard',
            $viewName
        );
    }


    /**
     *
     * @param string $url
     * @return string[]
     */
    protected function getUrlViewValues($url = null) {
        if (is_null($url) || trim($url) === '') {
            return array();
        }

        $websiteUrl = new \webignition\NormalisedUrl\NormalisedUrl($url);
        $websiteUrl->getConfiguration()->enableConvertIdnToUtf8();

        $utf8Raw = (string)$websiteUrl;
        $utf8Truncated_40 = $this->getTruncatedString($utf8Raw, 40);
        $utf8Truncated_50 = $this->getTruncatedString($utf8Raw, 50);
        $utf8Truncated_64 = $this->getTruncatedString($utf8Raw, 64);

        $utf8Schemeless = $this->trimUrl($utf8Raw);

        $utf8SchemelessTruncated_40 = $this->getTruncatedString($utf8Schemeless, 40);
        $utf8SchemelessTruncated_50 = $this->getTruncatedString($utf8Schemeless, 50);
        $utf8SchemelessTruncated_64 = $this->getTruncatedString($utf8Schemeless, 64);

        return array(
            'raw' => $url,
            'utf8' => array(
                'raw' => $utf8Raw,
                'truncated_40' => $utf8Truncated_40,
                'truncated_50' => $utf8Truncated_50,
                'truncated_64' => $utf8Truncated_64,
                'is_truncated_40' => ($utf8Raw != $utf8Truncated_40),
                'is_truncated_50' => ($utf8Raw != $utf8Truncated_50),
                'is_truncated_64' => ($utf8Raw != $utf8Truncated_64),
                'schemeless' => array(
                    'raw' => $utf8Schemeless,
                    'truncated_40' => $utf8SchemelessTruncated_40,
                    'truncated_50' => $utf8SchemelessTruncated_50,
                    'truncated_64' => $utf8SchemelessTruncated_64,
                    'is_truncated_40' => ($utf8Schemeless != $utf8SchemelessTruncated_40),
                    'is_truncated_50' => ($utf8Schemeless != $utf8SchemelessTruncated_50),
                    'is_truncated_64' => ($utf8Schemeless != $utf8SchemelessTruncated_64)
                )
            )
        );
    }


    private function trimUrl($url) {
        $url = $this->getSchemelessUrl($url);

        if (substr($url, strlen($url) - 1) == '/') {
            $url = substr($url, 0, strlen($url) - 1);
        }

        return $url;
    }

    private function getTruncatedString($input, $maxLength = 64) {
        if (mb_strlen($input) <= $maxLength) {
            return $input;
        }

        return mb_substr($input, 0, $maxLength);
    }


    /**
     *
     * @param string $url
     * @return string
     */
    private function getSchemelessUrl($url) {
        $schemeMarkers = array(
            'http://',
            'https://'
        );

        foreach ($schemeMarkers as $schemeMarker) {
            if (substr($schemeMarker, 0, strlen($schemeMarker)) == $schemeMarker) {
                return substr($url, strlen($schemeMarker));
            }
        }

        return $url;
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestService
     */
    protected function getTestService() {
        if (is_null($this->testService)) {
            $this->testService = $this->container->get('simplytestable.services.testservice');
            $this->testService->getRemoteTestService()->setUser($this->getUserService()->getUser());
        }

        return $this->testService;
    }


    protected function getRecentActivity() {
        $testList = $this->getTestService()->getRemoteTestService()->getRecent(3);

        foreach ($testList->get() as $testObject) {
            /* @var $remoteTest RemoteTest */
            $remoteTest = $testObject['remote_test'];

            $this->getTestService()->getRemoteTestService()->set($remoteTest);
            $test = $this->getTestService()->get($remoteTest->getWebsite(), $remoteTest->getId(), $remoteTest);

            $testList->addTest($test);

            if ($testList->requiresResults($test)) {
                if ($remoteTest->isSingleUrl()) {
                    $this->getTaskService()->getCollection($test);
                } else {
                    if (($remoteTest->getTaskCount() - self::RESULTS_PREPARATION_THRESHOLD) - $test->getTaskCount()) {
                        $this->getTaskService()->getCollection($test);
                    }
                }
            }
        }

        return $testList;
    }


    /**
     * @param int $limit
     * @param int $offset
     * @return \SimplyTestable\WebClientBundle\Model\TestList
     */
    private function getFinishedTests($limit = 3, $offset = 0) {
        $testList = $this->getTestService()->getRemoteTestService()->getFinished($limit, $offset);

        foreach ($testList->get() as $testObject) {
            /* @var $remoteTest RemoteTest */
            $remoteTest = $testObject['remote_test'];

            $this->getTestService()->getRemoteTestService()->set($remoteTest);
            $test = $this->getTestService()->get($remoteTest->getWebsite(), $remoteTest->getId(), $remoteTest);

            $testList->addTest($test);

            if ($testList->requiresResults($test)) {
                if ($remoteTest->isSingleUrl()) {
                    $this->getTaskService()->getCollection($test);
                } else {
                    if (($remoteTest->getTaskCount() - self::RESULTS_PREPARATION_THRESHOLD) - $test->getTaskCount()) {
                        $this->getTaskService()->getCollection($test);
                    }
                }
            }
        }

        return $testList;
    }


    /**
     * @return array
     */
    private function getCurrentTests() {
        $testList = $this->getTestService()->getRemoteTestService()->getCurrent();
        if ($testList->isEmpty()) {
            return array();
        }

        $tests = array();
        foreach ($testList->get() as $testObject) {
            /* @var $remoteTest RemoteTest */
            $remoteTest = $testObject['remote_test'];
            $currentTest = $remoteTest->getArraySource();

            if ($currentTest['state'] == 'failed-no-sitemap' && isset($currentTest['crawl'])) {
                $currentTest['state'] = 'crawling';
            }

            $currentTest['state_label_class'] = $this->getTestStateLabelClass($currentTest['state']);
            $currentTest['state_icon'] = $this->getTestStateIcon($currentTest['state']);
            $currentTest['website_label'] = $this->getWebsiteLabel($currentTest['website']);
            $currentTest['completion_percent'] = $remoteTest->getCompletionPercent();

            $tests[] = $currentTest;
        }

        return $tests;
    }

}