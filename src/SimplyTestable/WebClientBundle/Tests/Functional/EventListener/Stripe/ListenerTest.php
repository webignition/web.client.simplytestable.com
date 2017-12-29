<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe;

use SimplyTestable\WebClientBundle\EventListener\Stripe\Listener;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseTestCase;

abstract class ListenerTest extends BaseTestCase
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

        $this->listener = $this->container->get('simplytestable.listener.stripeevent');
    }
}
