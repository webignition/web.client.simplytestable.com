<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class ViewController extends BaseViewController {

    /**
     * @var Test
     */
    private $test;


    /**
     * @var \SimplyTestable\WebClientBundle\Services\TestService
     */
    private $testService;


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


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TaskService
     */
    protected function getTaskService() {
        return $this->container->get('simplytestable.services.taskservice');
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


    public function getInvalidOwnerResponse() {
        foreach (['website', 'test_id'] as $requiredRequestAttribute) {
            if (!$this->getRequest()->attributes->has($requiredRequestAttribute)) {
                return new Response('', 400);
            }
        }

        if ($this->getUserService()->isLoggedIn()) {
            return $this->render(
                'SimplyTestableWebClientBundle:bs3/Test/Results:not-authorised.html.twig',
                array(
                    'public_site' => $this->container->getParameter('public_site'),
                    'is_logged_in' => false,
                    'test_id' => $this->getRequest()->attributes->get('test_id'),
                    'website' => $this->getRequest()->attributes->get('website')
                )
            );
        }

        $redirectParameters = json_encode(array(
            'route' => 'view_test_progress_index_index',
            'parameters' => array(
                'website' => $this->getRequest()->attributes->get('website'),
                'test_id' => $this->getRequest()->attributes->get('test_id')
            )
        ));

        $this->container->get('session')->setFlash('user_signin_error', 'test-not-logged-in');

        return new RedirectResponse($this->generateUrl('view_user_signin_index', array(
            'redirect' => base64_encode($redirectParameters)
        ), true));
    }


    /**
     * @return Test
     */
    protected function getTest() {
        if (is_null($this->test)) {
            $this->test = $this->getTestService()->get(
                $this->getRequest()->attributes->get('website'),
                $this->getRequest()->attributes->get('test_id')
            );
        }

        return $this->test;
    }


    /**
     * @return bool|\SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest
     */
    protected function getRemoteTest() {
        return $this->getTestService()->getRemoteTestService()->get();
    }


}
