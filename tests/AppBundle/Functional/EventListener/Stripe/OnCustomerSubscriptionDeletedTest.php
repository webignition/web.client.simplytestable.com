<?php

namespace Tests\AppBundle\Functional\EventListener\Stripe;

use AppBundle\Event\Stripe\Event as StripeEvent;
use Tests\AppBundle\Factory\PostmarkHttpResponseFactory;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\AppBundle\Services\HttpMockHandler;

class OnCustomerSubscriptionDeletedTest extends AbstractListenerTest
{
    const EVENT_NAME = 'customer.subscription.deleted';

    /**
     * @var array
     */
    private $eventData = [
        'event' => self::EVENT_NAME,
        'plan_name' => 'Personal',
        'user' => 'user@example.com',
    ];

    /**
     * @dataProvider onCustomerSubscriptionDeletedDataProvider
     *
     * @param StripeEvent $event
     * @param string[] $expectedEmailProperties
     *
     * @throws \Twig_Error
     */
    public function testOnCustomerSubscriptionDeleted(StripeEvent $event, array $expectedEmailProperties)
    {
        $httpMockHandler = self::$container->get(HttpMockHandler::class);
        $httpMockHandler->appendFixtures([
            PostmarkHttpResponseFactory::createSuccessResponse(),
        ]);

        $this->listener->onCustomerSubscriptionDeleted($event);

        $this->assertPostmarkEmail($expectedEmailProperties);
    }

    /**
     * @return array
     */
    public function onCustomerSubscriptionDeletedDataProvider()
    {
        return [
            'actioned by user during trial, singular trial_days_remaining' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
                    [
                        'actioned_by' => 'user',
                        'trial_days_remaining' => 1,
                        'is_during_trial' => 1,
                    ]
                ))),
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] Premium subscription to personal cancelled',
                    'TextBody' => [
                        'remaining 1 day of your trial',
                        'http://localhost/account/',
                    ],
                ],
            ],
            'actioned by user during trial, plural trial_days_remaining' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
                    [
                        'actioned_by' => 'user',
                        'trial_days_remaining' => 12,
                        'is_during_trial' => 1,
                    ]
                ))),
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] Premium subscription to personal cancelled',
                    'TextBody' => [
                        'remaining 12 days of your trial',
                        'http://localhost/account/',
                    ],
                ],
            ],
            'actioned by user outside trial' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
                    [
                        'actioned_by' => 'user',
                        'trial_days_remaining' => 12,
                        'is_during_trial' => 0,
                    ]
                ))),
                'expectedEmailProperties' => [
                    'Subject' => '[Simply Testable] Premium subscription to personal cancelled',
                    'TextBody' => [
                        'drop back to our free basic plan during your premium' . "\n" . 'trial',
                    ],
                ],
            ],
            'actioned by system' => [
                'event' => new StripeEvent(new ParameterBag(array_merge(
                    $this->eventData,
                    [
                        'actioned_by' => 'system',
                        'trial_days_remaining' => 12,
                        'is_during_trial' => 1,
                    ]
                ))),
                'expectedEmailProperties' => [
                    'Subject' => sprintf(
                        '%s%s',
                        '[Simply Testable] Premium subscription to personal cancelled, ',
                        'you\'ve been dropped down to our free plan'
                    ),
                    'TextBody' => [
                        'http://localhost/account/',
                    ],
                ],
            ],
        ];
    }
}
