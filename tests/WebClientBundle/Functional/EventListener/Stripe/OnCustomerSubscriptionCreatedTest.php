<?php

namespace Tests\WebClientBundle\Functional\EventListener\Stripe;

use SimplyTestable\WebClientBundle\Event\Stripe\Event as StripeEvent;
use Tests\WebClientBundle\Factory\PostmarkHttpResponseFactory;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\WebClientBundle\Services\HttpMockHandler;

class OnCustomerSubscriptionCreatedTest extends AbstractListenerTest
{
    const EVENT_NAME = 'customer.subscription.created';

    /**
     * @var array
     */
    private $eventData = [
        'event' => self::EVENT_NAME,
        'plan_name' => 'Personal',
        'user' => self::EVENT_USER,
    ];

    /**
     * @dataProvider onCustomerSubscriptionCreatedDataProvider
     *
     * @param StripeEvent $event
     * @param string[] $expectedEmailProperties
     *
     * @throws \Twig_Error
     */
    public function testOnCustomerSubscriptionCreated(StripeEvent $event, array $expectedEmailProperties)
    {
        $httpMockHandler = self::$container->get(HttpMockHandler::class);
        $httpMockHandler->appendFixtures([
            PostmarkHttpResponseFactory::createSuccessResponse(),
        ]);

        $this->listener->onCustomerSubscriptionCreated($event);

        $this->assertPostmarkEmail($expectedEmailProperties);
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
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] You\'ve signed up to the personal plan',
                    'TextBody' => [
                        'personal plan',
                        '£9.00',
                        'http://localhost/account/',
                    ],
                ],
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
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] You\'ve signed up to the personal plan',
                    'TextBody' => [
                        'personal plan',
                        '$1.00',
                        'http://localhost/account/',
                    ],
                ],
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
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] You\'ve signed up to the personal plan',
                    'TextBody' => [
                        'personal plan',
                        'for 22 days',
                        'ending 2 January 2018',
                        'add a credit or debit card to your account',
                    ],
                ],
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
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] You\'ve signed up to the personal plan',
                    'TextBody' => [
                        'personal plan',
                        'ending 3 January 2018',
                    ],
                ],
            ],
        ];
    }
}
