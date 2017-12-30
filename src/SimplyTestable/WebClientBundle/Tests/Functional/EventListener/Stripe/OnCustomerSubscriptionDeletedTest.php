<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe;

use SimplyTestable\WebClientBundle\Event\Stripe\Event as StripeEvent;
use SimplyTestable\WebClientBundle\Tests\Factory\MockPostmarkMessageFactory;
use SimplyTestable\WebClientBundle\Tests\Helper\MockeryArgumentValidator;
use Symfony\Component\HttpFoundation\ParameterBag;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;

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
     * @param PostmarkMessage $postmarkMessage
     * @throws \Twig_Error
     */
    public function testOnCustomerSubscriptionDeleted(StripeEvent $event, PostmarkMessage $postmarkMessage)
    {
        $mailService = $this->container->get('simplytestable.services.mail.service');
        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');

        $mailService->setPostmarkMessage($postmarkMessage);

        $this->listener->onCustomerSubscriptionDeleted($event);

        $this->assertNotNull($postmarkSender->getLastMessage());
        $this->assertNotNull($postmarkSender->getLastResponse());
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
                'postmarkMessage' => MockPostmarkMessageFactory::createMockPostmarkMessage(
                    'user@example.com',
                    '[Simply Testable] Premium subscription to personal cancelled',
                    [
                        'ErrorCode' => 0,
                        'Message' => 'OK',
                    ],
                    [
                        'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                            'remaining 1 day of your trial',
                            'http://localhost/account/',
                        ])),
                    ]
                ),
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
                'postmarkMessage' => MockPostmarkMessageFactory::createMockPostmarkMessage(
                    'user@example.com',
                    '[Simply Testable] Premium subscription to personal cancelled',
                    [
                        'ErrorCode' => 0,
                        'Message' => 'OK',
                    ],
                    [
                        'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                            'remaining 12 days of your trial',
                            'http://localhost/account/',
                        ])),
                    ]
                ),
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
                'postmarkMessage' => MockPostmarkMessageFactory::createMockPostmarkMessage(
                    'user@example.com',
                    '[Simply Testable] Premium subscription to personal cancelled',
                    [
                        'ErrorCode' => 0,
                        'Message' => 'OK',
                    ]
                ),
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
                'postmarkMessage' => MockPostmarkMessageFactory::createMockPostmarkMessage(
                    'user@example.com',
                    sprintf(
                        '%s%s',
                        '[Simply Testable] Premium subscription to personal cancelled, ',
                        'you\'ve been dropped down to our free plan'
                    ),
                    [
                        'ErrorCode' => 0,
                        'Message' => 'OK',
                    ],
                    [
                        'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                            'http://localhost/account/',
                        ])),
                    ]
                ),
            ],
        ];
    }
}
