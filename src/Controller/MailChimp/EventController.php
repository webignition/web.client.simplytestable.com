<?php

namespace App\Controller\MailChimp;

use Psr\Log\LoggerInterface;
use App\Controller\AbstractEventController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Event\MailChimp\Event as MailChimpEvent;

class EventController extends AbstractEventController
{
    const LISTENER_EVENT_PREFIX = 'mailchimp.';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param LoggerInterface $logger
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, LoggerInterface $logger)
    {
        parent::__construct($eventDispatcher);

        $this->logger = $logger;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        if ($request->isMethod('GET')) {
            return new Response();
        }

        $requestData = $request->request;

        if (!$requestData->has('type')) {
            return new Response('', 400);
        }

        $event = new MailChimpEvent($requestData);
        $listenerEventName = self::LISTENER_EVENT_PREFIX . $event->getType();
        $dispatcherHasListener = count($this->eventDispatcher->getListeners($listenerEventName)) > 0;

        if (!$dispatcherHasListener) {
            return new Response('', 400);
        }

        try {
            $this->eventDispatcher->dispatch(
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

            $this->logger->error('Controller\MailChimp\EventController \DomainException START');
            $this->logger->error($logMessage);
            $this->logger->error($domainException);
            $this->logger->error($domainException);
            $this->logger->error('Controller\MailChimp\EventController \DomainException END');
        } catch (\UnexpectedValueException $unexpectedValueException) {
            // Event raw data is missing "data"
            $this->logger->error('Controller\MailChimp\EventController \UnexpectedValueException START');
            $this->logger->error('Controller\MailChimp\EventController event raw data is missing "data"');
            $this->logger->error($request);
            $this->logger->error($unexpectedValueException);
            $this->logger->error('Controller\MailChimp\EventController \UnexpectedValueException END');
        }

        return new Response('', 200);
    }
}
