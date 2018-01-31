<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Stripe;

use Mockery\Mock;
use SimplyTestable\WebClientBundle\Controller\Stripe\EventController;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use SimplyTestable\WebClientBundle\Event\Stripe\Event as StripeEvent;

class EventControllerTest extends AbstractBaseTestCase
{
    const ROUTE_NAME = 'action_user_resetpassword_index_request';
    const EMAIL = 'user@example.com';

    /**
     * @var EventController
     */
    private $eventController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->eventController = new EventController();
    }

    public function testIndexActionEventHasNoUser()
    {
        $container = $this->createContainer([
            'request' => new Request(),
            'event_dispatcher' => null,
        ]);

        $this->eventController->setContainer($container);

        $response = $this->eventController->indexAction();

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

        $container = $this->createContainer([
            'request' => new Request([], [
                'event' => 'customer.subscription.created',
                'user' => 'user@example.com',
            ]),
            'event_dispatcher' => $eventDispatcher,
        ]);

        $this->eventController->setContainer($container);

        $response = $this->eventController->indexAction();

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

        $container = $this->createContainer([
            'request' => new Request([], [
                'event' => 'customer.subscription.created',
                'user' => 'user@example.com',
            ]),
            'event_dispatcher' => $eventDispatcher,
        ]);

        $this->eventController->setContainer($container);

        $response = $this->eventController->indexAction();

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @param array $services
     * @return Mock|ContainerInterface
     */
    private function createContainer($services = [])
    {
        /* @var ContainerInterface|Mock $container */
        $container = \Mockery::mock(ContainerInterface::class);

        $container
            ->shouldReceive('get')
            ->with('request')
            ->andReturn($services['request']);

        $container
            ->shouldReceive('get')
            ->with('event_dispatcher')
            ->andReturn($services['event_dispatcher']);

        return $container;
    }
}
