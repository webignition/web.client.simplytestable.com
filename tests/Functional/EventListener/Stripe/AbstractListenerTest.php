<?php

namespace App\Tests\Functional\EventListener\Stripe;

use App\EventListener\Stripe\Listener as StripeListener;
use App\Tests\Functional\AbstractBaseTestCase;
use App\Tests\Services\PostmarkMessageVerifier;
use webignition\HttpHistoryContainer\Container as HttpHistoryContainer;

abstract class AbstractListenerTest extends AbstractBaseTestCase
{
    const EVENT_USER = 'user@example.com';

    const EXPECTED_EMAIL_FROM = 'jon@simplytestable.com';
    const EXPECTED_EMAIL_TO = self::EVENT_USER;

    /**
     * @var StripeListener
     */
    protected $listener;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->listener = self::$container->get(StripeListener::class);
    }

    /**
     * @param string[] $expectedEmailProperties
     */
    protected function assertPostmarkEmail(array $expectedEmailProperties)
    {
        $httpHistoryContainer = self::$container->get(HttpHistoryContainer::class);
        $postmarkMessageVerifier = self::$container->get(PostmarkMessageVerifier::class);

        $verificationResult = $postmarkMessageVerifier->verify(
            array_merge(
                [
                    'From' => self::EXPECTED_EMAIL_FROM,
                    'To' => self::EVENT_USER,
                ],
                $expectedEmailProperties
            ),
            $httpHistoryContainer->getLastRequest()
        );

        $this->assertTrue($verificationResult, $verificationResult);
    }
}