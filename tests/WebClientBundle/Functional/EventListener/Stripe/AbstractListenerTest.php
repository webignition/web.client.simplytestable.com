<?php

namespace Tests\WebClientBundle\Functional\EventListener\Stripe;

use SimplyTestable\WebClientBundle\EventListener\Stripe\Listener as StripeListener;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Tests\WebClientBundle\Services\PostmarkMessageVerifier;
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

        $this->listener = $this->container->get(StripeListener::class);
    }

    /**
     * @param string[] $expectedEmailProperties
     */
    protected function assertPostmarkEmail(array $expectedEmailProperties)
    {
        $httpHistoryContainer = $this->container->get(HttpHistoryContainer::class);
        $postmarkMessageVerifier = $this->container->get(PostmarkMessageVerifier::class);

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
