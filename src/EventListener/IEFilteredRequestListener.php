<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use webignition\IEDetector\IEDetector;

class IEFilteredRequestListener
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $marketingSiteUrl;

    /**
     * @param LoggerInterface $logger
     * @param $marketingSiteUrl
     */
    public function __construct(LoggerInterface $logger, $marketingSiteUrl)
    {
        $this->logger = $logger;
        $this->marketingSiteUrl = $marketingSiteUrl;
    }

    /**
     * @param GetResponseEvent $event
     *
     * @return null|RedirectResponse
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return null;
        }

        $request = $event->getRequest();

        if (Request::METHOD_GET !== $request->getMethod()) {
            return null;
        }

        $userAgentString = $request->headers->get('user-agent');
        if (empty($userAgentString)) {
            $userAgentString = $request->server->get('HTTP_USER_AGENT');
        }

        if (empty($userAgentString)) {
            return null;
        }

        $isUsingOldIE =
            IEDetector::isIE6($userAgentString) ||
            IEDetector::isIE7($userAgentString) ||
            IEDetector::isIE8($userAgentString) ||
            IEDetector::isIE9($userAgentString);

        if ($isUsingOldIE) {
            $this->logger->error(sprintf(
                'FOO Detected old IE for [%s]',
                $userAgentString
            ));

            $event->setResponse(new RedirectResponse($this->marketingSiteUrl));
        }

        return null;
    }
}
