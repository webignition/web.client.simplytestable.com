<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Services\TestService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class ViewController extends BaseViewController
{
    /**
     * @param string $url
     *
     * @return string[]
     */
    protected function getUrlViewValues($url = null)
    {
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
            if (substr($url, 0, strlen($schemeMarker)) == $schemeMarker) {
                return substr($url, strlen($schemeMarker));
            }
        }

        return $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getInvalidOwnerResponse(Request $request)
    {
        foreach (['website', 'test_id'] as $requiredRequestAttribute) {
            if (!$request->attributes->has($requiredRequestAttribute)) {
                return new Response('', 400);
            }
        }

        if ($this->getUserService()->isLoggedIn()) {
            return $this->render(
                'SimplyTestableWebClientBundle:bs3/Test/Results:not-authorised.html.twig',
                array_merge($this->getDefaultViewParameters(), [
                    'test_id' => $request->attributes->get('test_id'),
                    'website' => $this->getUrlViewValues($request->attributes->get('website')),
                ])
            );
        }

        $redirectParameters = json_encode(array(
            'route' => 'view_test_progress_index_index',
            'parameters' => array(
                'website' => $request->attributes->get('website'),
                'test_id' => $request->attributes->get('test_id')
            )
        ));

        $this->container->get('session')->getFlashBag()->set('user_signin_error', 'test-not-logged-in');

        return new RedirectResponse($this->generateUrl('view_user_signin_index', array(
            'redirect' => base64_encode($redirectParameters)
        ), true));
    }
}
