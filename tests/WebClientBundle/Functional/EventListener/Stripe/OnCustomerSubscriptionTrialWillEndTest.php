<?php

namespace Tests\WebClientBundle\Functional\EventListener\Stripe;

use SimplyTestable\WebClientBundle\Event\Stripe\Event as StripeEvent;
use Tests\WebClientBundle\Factory\MockPostmarkMessageFactory;
use Tests\WebClientBundle\Helper\MockeryArgumentValidator;
use Symfony\Component\HttpFoundation\ParameterBag;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;

class OnCustomerSubscriptionTrialWillEndTest extends AbstractListenerTest
{
    const EVENT_NAME = 'customer.subscription.trial_will_end';

    /**
     * @var array
     */
    private $eventData = [
        'event' => self::EVENT_NAME,
        'user' => 'user@example.com',
    ];

    /**
     * @dataProvider onCustomerSubscriptionTrialWillEndDataProvider
     *
     * @param StripeEvent $event
     * @param PostmarkMessage $postmarkMessage
     * @throws \Twig_Error
     */
    public function testOnCustomerSubscriptionTrialWillEnd(StripeEvent $event, PostmarkMessage $postmarkMessage)
    {
        $mailService = $this->container->get('SimplyTestable\WebClientBundle\Services\Mail\Service');
        $postmarkSender = $this->container->get('SimplyTestable\WebClientBundle\Services\PostmarkSender');

        $mailService->setPostmarkMessage($postmarkMessage);

        $this->listener->onCustomerSubscriptionTrialWillEnd($event);

        $this->assertNotNull($postmarkSender->getLastMessage());
        $this->assertNotNull($postmarkSender->getLastResponse());
    }

    /**
     * @return array
     */
    public function onCustomerSubscriptionTrialWillEndDataProvider()
    {
        return [
            'has_card:0, currency:default' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
                    [
                        'has_card' => 0,
                        'plan_name' => 'Personal',
                        'plan_amount' => 900,
                    ]
                ))),
                'postmarkMessage' => MockPostmarkMessageFactory::createMockPostmarkMessage(
                    'user@example.com',
                    '[Simply Testable] Your personal account trial ends in 3 days, payment details needed',
                    [
                        'ErrorCode' => 0,
                        'Message' => 'OK',
                    ],
                    [
                        'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                            'trial period for your personal account subscription',
                            '£9.00 per month',
                            'add a credit or debit card to your account',
                            'http://localhost/account/',
                        ])),
                    ]
                ),
            ],
            'has_card:0, currency:usd' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
                    [
                        'has_card' => 0,
                        'plan_name' => 'Personal',
                        'plan_amount' => 900,
                        'plan_currency' => 'usd',
                    ]
                ))),
                'postmarkMessage' => MockPostmarkMessageFactory::createMockPostmarkMessage(
                    'user@example.com',
                    '[Simply Testable] Your personal account trial ends in 3 days, payment details needed',
                    [
                        'ErrorCode' => 0,
                        'Message' => 'OK',
                    ],
                    [
                        'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                            'trial period for your personal account subscription',
                            '$9.00 per month',
                            'add a credit or debit card to your account',
                            'http://localhost/account/',
                        ])),
                    ]
                ),
            ],
            'has_card:1' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
                    [
                        'has_card' => 1,
                        'plan_name' => 'Personal',
                        'plan_amount' => 900,
                    ]
                ))),
                'postmarkMessage' => MockPostmarkMessageFactory::createMockPostmarkMessage(
                    'user@example.com',
                    '[Simply Testable] Your personal account trial ends in 3 days',
                    [
                        'ErrorCode' => 0,
                        'Message' => 'OK',
                    ],
                    [
                        'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                            'trial period for your personal account subscription',
                            '£9.00 per month',
                        ])),
                    ]
                ),
            ],
        ];
    }
}
