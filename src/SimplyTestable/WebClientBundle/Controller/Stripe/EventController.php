<?php

namespace SimplyTestable\WebClientBundle\Controller\Stripe;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Event\Stripe\Event as StripeEvent;

class EventController extends Controller
{
    const LISTENER_EVENT_PREFIX = 'stripe.';

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $dispatcher = $this->container->get('event_dispatcher');

        $event = new StripeEvent($request->request);
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
