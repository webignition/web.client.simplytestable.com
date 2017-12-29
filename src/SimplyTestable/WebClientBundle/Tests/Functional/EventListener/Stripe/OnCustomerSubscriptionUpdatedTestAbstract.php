<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe;

use SimplyTestable\WebClientBundle\Event\Stripe\Event as StripeEvent;
use SimplyTestable\WebClientBundle\Tests\Factory\MockPostmarkMessageFactory;
use SimplyTestable\WebClientBundle\Tests\Helper\MockeryArgumentValidator;
use Symfony\Component\HttpFoundation\ParameterBag;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;

class OnCustomerSubscriptionUpdatedTestAbstract extends AbstractListenerTest
{
    const EVENT_NAME = 'customer.subscription.updated';

    /**
     * @var array
     */
    private $eventData = [
        'event' => self::EVENT_NAME,
        'user' => 'user@example.com',
    ];

    /**
     * @dataProvider onCustomerSubscriptionUpdatedDataProvider
     *
     * @param StripeEvent $event
     * @param PostmarkMessage $postmarkMessage
     * @throws \Twig_Error
     */
    public function testOnCustomerSubscriptionUpdated(StripeEvent $event, PostmarkMessage $postmarkMessage)
    {
        $mailService = $this->container->get('simplytestable.services.mail.service');
        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');

        $mailService->setPostmarkMessage($postmarkMessage);

        $this->listener->onCustomerSubscriptionUpdated($event);

        $this->assertNotNull($postmarkSender->getLastMessage());
        $this->assertNotNull($postmarkSender->getLastResponse());
    }

    /**
     * @return array
     */
    public function onCustomerSubscriptionUpdatedDataProvider()
    {
        return [
            'plan_change:1, subscription_status:active, currency:default' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
                    [
                        'is_plan_change' => 1,
                        'subscription_status' => 'active',
                        'new_plan' => 'Agency',
                        'old_plan' => 'Personal',
                        'new_amount' => 1200,
                        'trial_end' => (new \DateTime('2018-09-01'))->format('U'),
                    ]
                ))),
                'postmarkMessage' => MockPostmarkMessageFactory::createMockPostmarkMessage(
                    'user@example.com',
                    '[Simply Testable] You\'ve changed to the agency plan',
                    [
                        'ErrorCode' => 0,
                        'Message' => 'OK',
                    ],
                    [
                        'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                            'switched to our agency plan at £12.00 per month',
                            'personal subscription will be pro-rata',
                            'agency subscription will be pro-rata',
                        ])),
                    ]
                ),
            ],
            'plan_change:1, subscription_status:active, currency:usd' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
                    [
                        'is_plan_change' => 1,
                        'subscription_status' => 'active',
                        'new_plan' => 'Agency',
                        'old_plan' => 'Personal',
                        'new_amount' => 1200,
                        'trial_end' => (new \DateTime('2018-09-01'))->format('U'),
                        'currency' => 'usd',
                    ]
                ))),
                'postmarkMessage' => MockPostmarkMessageFactory::createMockPostmarkMessage(
                    'user@example.com',
                    '[Simply Testable] You\'ve changed to the agency plan',
                    [
                        'ErrorCode' => 0,
                        'Message' => 'OK',
                    ],
                    [
                        'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                            'switched to our agency plan at $12.00 per month',
                            'personal subscription will be pro-rata',
                            'agency subscription will be pro-rata',
                        ])),
                    ]
                ),
            ],
            'plan_change:1, subscription_status:trialing' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
                    [
                        'is_plan_change' => 1,
                        'subscription_status' => 'trialing',
                        'new_plan' => 'Agency',
                        'old_plan' => 'Personal',
                        'new_amount' => 1200,
                        'trial_end' => (new \DateTime('2018-09-01'))->format('U'),
                    ]
                ))),
                'postmarkMessage' => MockPostmarkMessageFactory::createMockPostmarkMessage(
                    'user@example.com',
                    '[Simply Testable] You\'ve changed to the agency plan',
                    [
                        'ErrorCode' => 0,
                        'Message' => 'OK',
                    ],
                    [
                        'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                            'switched to our agency plan',
                            ' free until 1 September 2018',
                        ])),
                    ]
                ),
            ],
            'transition:trialing_to_active; has_card:0' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
                    [
                        'is_status_change' => 1,
                        'previous_subscription_status' => 'trialing',
                        'subscription_status' => 'active',
                        'has_card' => 0,
                        'plan_name' => 'Personal',
                    ]
                ))),
                'postmarkMessage' => MockPostmarkMessageFactory::createMockPostmarkMessage(
                    'user@example.com',
                    '[Simply Testable] Premium trial has ended, you\'ve been dropped down to our free plan',
                    [
                        'ErrorCode' => 0,
                        'Message' => 'OK',
                    ],
                    [
                        'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                            'trial of our personal plan has come to an end',
                            'http://localhost/account/',
                        ])),
                    ]
                ),
            ],
            'transition:trialing_to_active; has_card:1, currency:default' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
                    [
                        'is_status_change' => 1,
                        'previous_subscription_status' => 'trialing',
                        'subscription_status' => 'active',
                        'has_card' => 1,
                        'plan_name' => 'Personal',
                        'plan_amount' => 900,
                    ]
                ))),
                'postmarkMessage' => MockPostmarkMessageFactory::createMockPostmarkMessage(
                    'user@example.com',
                    '[Simply Testable] Premium trial has ended, payment for the next month will be taken soon',
                    [
                        'ErrorCode' => 0,
                        'Message' => 'OK',
                    ],
                    [
                        'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                            'trial of our personal plan has come to an end',
                            'will be charged £9.00 per month',
                            'http://localhost/account/',
                        ])),
                    ]
                ),
            ],
            'transition:trialing_to_active; has_card:1, currency:usd' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
                    [
                        'is_status_change' => 1,
                        'previous_subscription_status' => 'trialing',
                        'subscription_status' => 'active',
                        'has_card' => 1,
                        'plan_name' => 'Personal',
                        'plan_amount' => 900,
                        'currency' => 'usd',
                    ]
                ))),
                'postmarkMessage' => MockPostmarkMessageFactory::createMockPostmarkMessage(
                    'user@example.com',
                    '[Simply Testable] Premium trial has ended, payment for the next month will be taken soon',
                    [
                        'ErrorCode' => 0,
                        'Message' => 'OK',
                    ],
                    [
                        'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                            'trial of our personal plan has come to an end',
                            'will be charged $9.00 per month',
                            'http://localhost/account/',
                        ])),
                    ]
                ),
            ],
        ];
    }
}
