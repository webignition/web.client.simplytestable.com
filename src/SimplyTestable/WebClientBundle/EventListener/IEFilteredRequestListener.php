<?php

namespace SimplyTestable\WebClientBundle\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use webignition\IEDetector\IEDetector;

class IEFilteredRequestListener extends AbstractRequestListener
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
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!parent::onKernelController($event)) {
            return;
        }

        if ($this->controller instanceof IEFiltered) {
            $request = $event->getRequest();

            $userAgentString = $request->headers->get('user-agent');
            if (empty($userAgentString)) {
                $userAgentString = $request->server->get('HTTP_USER_AGENT');
            }

            if (empty($userAgentString)) {
                return;
            }

            $isUsingOldIE =
                IEDetector::isIE6($userAgentString) ||
                IEDetector::isIE7($userAgentString) ||
                IEDetector::isIE8($userAgentString);

            if ($isUsingOldIE) {
                $this->logger->error(sprintf(
                    'Detected old IE for [%s]',
                    $userAgentString
                ));

                /* @var IEFiltered $controller */
                $controller = $this->controller;
                $controller->setResponse(new RedirectResponse($this->marketingSiteUrl));
            }
        }
    }
}
