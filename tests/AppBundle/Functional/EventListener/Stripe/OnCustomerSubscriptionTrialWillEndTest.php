<?php

namespace Tests\AppBundle\Functional\EventListener\Stripe;

use App\Event\Stripe\Event as StripeEvent;
use Tests\AppBundle\Factory\PostmarkHttpResponseFactory;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\AppBundle\Services\HttpMockHandler;

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
     * @param string[] $expectedEmailProperties
     *
     * @throws \Twig_Error
     */
    public function testOnCustomerSubscriptionTrialWillEnd(StripeEvent $event, array $expectedEmailProperties)
    {
        $httpMockHandler = self::$container->get(HttpMockHandler::class);
        $httpMockHandler->appendFixtures([
            PostmarkHttpResponseFactory::createSuccessResponse(),
        ]);

        $this->listener->onCustomerSubscriptionTrialWillEnd($event);

        $this->assertPostmarkEmail($expectedEmailProperties);
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
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] Your personal account trial ends in 3 days, payment details needed',
                    'TextBody' => [
                        'trial period for your personal account subscription',
                        '£9.00 per month',
                        'add a credit or debit card to your account',
                        'http://localhost/account/',
                    ],
                ],
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
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] Your personal account trial ends in 3 days, payment details needed',
                    'TextBody' => [
                        'trial period for your personal account subscription',
                        '$9.00 per month',
                        'add a credit or debit card to your account',
                        'http://localhost/account/',
                    ],
                ],
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
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] Your personal account trial ends in 3 days',
                    'TextBody' => [
                        'trial period for your personal account subscription',
                        '£9.00 per month',
                    ],
                ],
            ],
        ];
    }
}
