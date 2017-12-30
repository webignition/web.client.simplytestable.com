<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe;

use SimplyTestable\WebClientBundle\Event\Stripe\Event as StripeEvent;
use SimplyTestable\WebClientBundle\Tests\Factory\MockPostmarkMessageFactory;
use SimplyTestable\WebClientBundle\Tests\Helper\MockeryArgumentValidator;
use Symfony\Component\HttpFoundation\ParameterBag;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;

class OnCustomerSubscriptionCreatedTest extends AbstractListenerTest
{
    const EVENT_NAME = 'customer.subscription.created';

    /**
     * @var array
     */
    private $eventData = [
        'event' => self::EVENT_NAME,
        'plan_name' => 'Personal',
        'user' => 'user@example.com',
    ];

    /**
     * @dataProvider onCustomerSubscriptionCreatedDataProvider
     *
     * @param StripeEvent $event
     * @param PostmarkMessage $postmarkMessage
     * @throws \Twig_Error
     */
    public function testOnCustomerSubscriptionCreated(StripeEvent $event, PostmarkMessage $postmarkMessage)
    {
        $mailService = $this->container->get('simplytestable.services.mail.service');
        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');

        $mailService->setPostmarkMessage($postmarkMessage);

        $this->listener->onCustomerSubscriptionCreated($event);

        $this->assertNotNull($postmarkSender->getLastMessage());
        $this->assertNotNull($postmarkSender->getLastResponse());
    }

    /**
     * @return array
     */
    public function onCustomerSubscriptionCreatedDataProvider()
    {
        return [
            'status:active, currency:default' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
                    [
                        'status' => 'active',
                        'amount' => '900',
                    ]
                ))),
                'postmarkMessage' => MockPostmarkMessageFactory::createMockPostmarkMessage(
                    'user@example.com',
                    '[Simply Testable] You\'ve signed up to the personal plan',
                    [
                        'ErrorCode' => 0,
                        'Message' => 'OK',
                    ],
                    [
                        'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                            'personal plan',
                            '£9.00',
                            'http://localhost/account/',
                        ])),
                    ]
                ),
            ],
            'status:active, currency:usd' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
                    [
                        'status' => 'active',
                        'amount' => '100',
                        'currency' => 'usd',
                    ]
                ))),
                'postmarkMessage' => MockPostmarkMessageFactory::createMockPostmarkMessage(
                    'user@example.com',
                    '[Simply Testable] You\'ve signed up to the personal plan',
                    [
                        'ErrorCode' => 0,
                        'Message' => 'OK',
                    ],
                    [
                        'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                            'personal plan',
                            '$1.00',
                            'http://localhost/account/',
                        ])),
                    ]
                ),
            ],
            'status:trialing, has_card:0' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
                    [
                        'status' => 'trialing',
                        'amount' => '1800',
                        'has_card' => 0,
                        'trial_period_days' => 22,
                        'trial_end' => (new \DateTime('2018-01-02'))->format('U'),
                    ]
                ))),
                'postmarkMessage' => MockPostmarkMessageFactory::createMockPostmarkMessage(
                    'user@example.com',
                    '[Simply Testable] You\'ve signed up to the personal plan',
                    [
                        'ErrorCode' => 0,
                        'Message' => 'OK',
                    ],
                    [
                        'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                            'personal plan',
                            'for 22 days',
                            'ending 2 January 2018',
                            'add a credit or debit card to your account',
                        ])),
                    ]
                ),
            ],
            'status:trialing, has_card:1' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
                    [
                        'status' => 'trialing',
                        'amount' => '1800',
                        'has_card' => 1,
                        'trial_period_days' => 22,
                        'trial_end' => (new \DateTime('2018-01-03'))->format('U'),
                    ]
                ))),
                'postmarkMessage' => MockPostmarkMessageFactory::createMockPostmarkMessage(
                    'user@example.com',
                    '[Simply Testable] You\'ve signed up to the personal plan',
                    [
                        'ErrorCode' => 0,
                        'Message' => 'OK',
                    ],
                    [
                        'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                            'personal plan',
                            'ending 3 January 2018',
                        ])),
                    ]
                ),
            ],
        ];
    }
}
