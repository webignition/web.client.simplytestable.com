<?php

namespace Tests\AppBundle\Functional\Controller\Stripe;

use Mockery\Mock;
use App\Controller\Stripe\EventController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Event\Stripe\Event as StripeEvent;
use Tests\AppBundle\Functional\Controller\AbstractControllerTest;

class EventControllerTest extends AbstractControllerTest
{
    const ROUTE_NAME = 'action_user_resetpassword_index_request';
    const EMAIL = 'user@example.com';

    public function testIndexActionEventHasNoUser()
    {
        $this->client->request('POST', $this->router->generate('stripe_event'));
        $response = $this->client->getResponse();

        $this->assertTrue($response->isClientError());
    }

    public function testIndexActionNoListenerForEvent()
    {
        $this->client->request(
            'POST',
            $this->router->generate('stripe_event'),
            [
                'event' => 'invalid-event-name',
                'user' => 'user@example.com',
            ]
        );
        $response = $this->client->getResponse();

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
            ->withArgs(function ($eventName, StripeEvent $stripeEvent) {
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
