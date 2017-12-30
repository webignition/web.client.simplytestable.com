<?php

namespace SimplyTestable\WebClientBundle\Controller\Stripe;

use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Controller\BaseController;
use SimplyTestable\WebClientBundle\Event\Stripe\Event as StripeEvent;

class EventController extends BaseController
{
    const LISTENER_EVENT_PREFIX = 'stripe.';

    /**
     * @return Response
     */
    public function indexAction()
    {
        $dispatcher = $this->container->get('event_dispatcher');

        $event = new StripeEvent($this->get('request')->request);
        $listenerEventName = self::LISTENER_EVENT_PREFIX . $event->getName();

        if (!$event->hasUser()) {
            return new Response('', 400);
        }

        $hasListenerForEvent = count($dispatcher->getListeners($listenerEventName)) > 0;

        if (!$hasListenerForEvent) {
            return new Response('', 400);
        }

        $dispatcher->dispatch(
            $listenerEventName,
            $event
        );

        return new Response('', 200);
    }
}
