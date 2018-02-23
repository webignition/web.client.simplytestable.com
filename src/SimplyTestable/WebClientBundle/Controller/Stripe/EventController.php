<?php

namespace SimplyTestable\WebClientBundle\Controller\Stripe;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Event\Stripe\Event as StripeEvent;

class EventController
{
    const LISTENER_EVENT_PREFIX = 'stripe.';

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $event = new StripeEvent($request->request);
        $listenerEventName = self::LISTENER_EVENT_PREFIX . $event->getName();

        if (!$event->hasUser()) {
            return new Response('', 400);
        }

        $hasListenerForEvent = count($this->eventDispatcher->getListeners($listenerEventName)) > 0;

        if (!$hasListenerForEvent) {
            return new Response('', 400);
        }

        $this->eventDispatcher->dispatch(
            $listenerEventName,
            $event
        );

        return new Response('', 200);
    }
}
