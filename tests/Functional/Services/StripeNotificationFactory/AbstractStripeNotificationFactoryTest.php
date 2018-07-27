<?php

namespace App\Tests\Functional\Services\StripeNotificationFactory;

use App\Services\StripeNotificationFactory;
use App\Tests\Functional\AbstractBaseTestCase;

abstract class AbstractStripeNotificationFactoryTest extends AbstractBaseTestCase
{
    const EVENT_USER = 'user@example.com';
    const ACCOUNT_URL = 'http://localhost/account';

    /**
     * @var StripeNotificationFactory
     */
    protected $stripeNotificationFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->stripeNotificationFactory = self::$container->get(StripeNotificationFactory::class);
    }
}
