<?php

namespace Tests\WebClientBundle\Functional\Controller\Stripe;

use Mockery\Mock;
use SimplyTestable\WebClientBundle\Controller\Stripe\EventController;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use SimplyTestable\WebClientBundle\Event\Stripe\Event as StripeEvent;

class EventControllerTest extends AbstractBaseTestCase
{
    const ROUTE_NAME = 'action_user_resetpassword_index_request';
    const EMAIL = 'user@example.com';

    public function testIndexActionEventHasNoUser()
    {
        $eventController = $this->container->get(EventController::class);

        $response = $eventController->indexAction(new Request());

        $this->assertTrue($response->isClientError());
    }

    public function testIndexActionNoListenerForEvent()
    {
        /* @var EventDispatcherInterface|Mock $eventDispatcher */
        $eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher
            ->shouldReceive('getListeners')
            ->with('stripe.customer.subscription.created')
            ->andReturn([]);

        $eventController = new EventController($eventDispatcher);

        $response = $eventController->indexAction(new Request([], [
            'event' => 'customer.subscription.created',
            'user' => 'user@example.com',
        ]));

        $this->assertTrue($response->isClientError());
    }

    public function testIndexActionSuccess()
    {
        /* @var EventDispatcherInterface|Mock $eventDispatcher */
        $eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher
            ->shouldReceive('getListeners')
            ->with('stripe.customer.subscription.created')
            ->andReturn([
                'foo',
            ]);

        $eventDispatcher
            ->shouldReceive('dispatch')
            ->withArgs(function ($eventName, $stripeEvent) {
                if ($eventName !== 'stripe.customer.subscription.created') {
                    return false;
                }

                if (!$stripeEvent instanceof StripeEvent) {
                    return false;
                }

                return true;
            });

        $eventController = new EventController($eventDispatcher);

        $response = $eventController->indexAction(new Request([], [
            'event' => 'customer.subscription.created',
            'user' => 'user@example.com',
        ]));

        $this->assertTrue($response->isSuccessful());
    }
}
