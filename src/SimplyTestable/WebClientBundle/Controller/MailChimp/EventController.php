<?php

namespace SimplyTestable\WebClientBundle\Controller\MailChimp;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Controller\BaseController;
use SimplyTestable\WebClientBundle\Event\MailChimp\Event as MailChimpEvent;

class EventController extends BaseController
{
    const LISTENER_EVENT_PREFIX = 'mailchimp.';

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $dispatcher = $this->container->get('event_dispatcher');
        $logger = $this->container->get('logger');

        if ($request->isMethod('GET')) {
            return new Response();
        }

        $requestData = $request->request;

        if (!$requestData->has('type')) {
            return new Response('', 400);
        }

        $event = new MailChimpEvent($requestData);
        $listenerEventName = self::LISTENER_EVENT_PREFIX . $event->getType();
        $dispatcherHasListener = count($dispatcher->getListeners($listenerEventName)) > 0;

        if (!$dispatcherHasListener) {
            return new Response('', 400);
        }

        try {
            $dispatcher->dispatch(
                $listenerEventName,
                $event
            );
        } catch (\DomainException $domainException) {
            // Invalid event data list_id
            $eventData = $requestData->get('data');

            $logMessage = 'Controller\MailChimp\EventController ';

            $logMessage .= (array_key_exists('list_id', $eventData))
                ? 'invalid list_id "' . $eventData['list_id'] . '" in event data'
                : 'no list_id in event data';

            $logger->error('Controller\MailChimp\EventController \DomainException START');
            $logger->error($logMessage);
            $logger->error($domainException);
            $logger->error($domainException);
            $logger->error('Controller\MailChimp\EventController \DomainException END');
        } catch (\UnexpectedValueException $unexpectedValueException) {
            // Event raw data is missing "data"
            $logger->error('Controller\MailChimp\EventController \UnexpectedValueException START');
            $logger->error('Controller\MailChimp\EventController event raw data is missing "data"');
            $logger->error($request);
            $logger->error($unexpectedValueException);
            $logger->error('Controller\MailChimp\EventController \UnexpectedValueException END');
        }

        return new Response('', 200);
    }
}
