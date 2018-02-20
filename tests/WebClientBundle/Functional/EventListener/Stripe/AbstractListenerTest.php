<?php

namespace Tests\WebClientBundle\Functional\EventListener\Stripe;

use SimplyTestable\WebClientBundle\EventListener\Stripe\Listener as StripeListener;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;

abstract class AbstractListenerTest extends AbstractBaseTestCase
{
    const EVENT_USER = 'user@example.com';

    /**
     * @var Listener
     */
    protected $listener;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->listener = $this->container->get(StripeListener::class);
    }
}
