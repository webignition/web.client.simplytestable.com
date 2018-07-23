<?php

namespace App\Tests\Functional\EventListener\Stripe;

use App\Event\Stripe\Event as StripeEvent;
use App\Tests\Factory\PostmarkHttpResponseFactory;
use Symfony\Component\HttpFoundation\ParameterBag;
use App\Tests\Services\HttpMockHandler;

class OnCustomerSubscriptionUpdatedTest extends AbstractListenerTest
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
     * @param string[] $expectedEmailProperties
     *
     * @throws \Twig_Error
     */
    public function testOnCustomerSubscriptionUpdated(StripeEvent $event, array $expectedEmailProperties)
    {
        $httpMockHandler = self::$container->get(HttpMockHandler::class);
        $httpMockHandler->appendFixtures([
            PostmarkHttpResponseFactory::createSuccessResponse(),
        ]);

        $this->listener->onCustomerSubscriptionUpdated($event);

        $this->assertPostmarkEmail($expectedEmailProperties);
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
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] You\'ve changed to the agency plan',
                    'TextBody' => [
                        'switched to our agency plan at £12.00 per month',
                        'personal subscription will be pro-rata',
                        'agency subscription will be pro-rata',
                    ],
                ],
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
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] You\'ve changed to the agency plan',
                    'TextBody' => [
                        'switched to our agency plan at $12.00 per month',
                        'personal subscription will be pro-rata',
                        'agency subscription will be pro-rata',
                    ],
                ],
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
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] You\'ve changed to the agency plan',
                    'TextBody' => [
                        'switched to our agency plan',
                        ' free until 1 September 2018',
                    ],
                ],
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
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] Premium trial has ended, you\'ve been dropped down to our '
                        .'free plan',
                    'TextBody' => [
                        'trial of our personal plan has come to an end',
                        'http://localhost/account/',
                    ],
                ],
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
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] Premium trial has ended, payment for the next month '
                        .'will be taken soon',
                    'TextBody' => [
                        'trial of our personal plan has come to an end',
                        'will be charged £9.00 per month',
                        'http://localhost/account/',
                    ],
                ],
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
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] Premium trial has ended, payment for the next month '
                        .'will be taken soon',
                    'TextBody' => [
                        'trial of our personal plan has come to an end',
                        'will be charged $9.00 per month',
                        'http://localhost/account/',
                    ],
                ],
            ],
        ];
    }

    public function testOnCustomerSubscriptionUpdatedNoPlanChangeNoTransitionChange()
    {
        $event = new StripeEvent(new ParameterBag(
            $this->eventData
        ));

        $this->assertNull($this->listener->onCustomerSubscriptionUpdated($event));
    }
}
