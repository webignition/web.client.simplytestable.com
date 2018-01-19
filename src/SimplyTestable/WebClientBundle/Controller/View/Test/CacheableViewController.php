<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test;

use SimplyTestable\WebClientBundle\Interfaces\Controller\Cacheable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class CacheableViewController extends ViewController implements Cacheable
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return (is_null($this->request)) ? $this->get('request') : $this->request;
    }

    /**
     * @param string $locationValue
     * @param Request|null $request
     *
     * @return RedirectResponse|JsonResponse
     */
    protected function issueRedirect($locationValue, $request = null)
    {
        if (empty($request)) {
            $request = $this->getRequest();
        }

        $requestHeaders = $request->headers;
        $requestedWithHeaderName = 'X-Requested-With';

        $isXmlHttpRequest = $requestHeaders->get($requestedWithHeaderName) == 'XMLHttpRequest';

        if ($isXmlHttpRequest) {
            return new JsonResponse([
                'this_url' => $locationValue
            ]);
        }

        return $this->redirect($locationValue, 302, $request);
    }
}
