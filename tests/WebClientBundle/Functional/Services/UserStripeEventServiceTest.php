<?php

namespace Tests\WebClientBundle\Functional\Services;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserStripeEventService;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use webignition\Model\Stripe\Event\Event as StripeEvent;

class UserStripeEventServiceTest extends AbstractCoreApplicationServiceTest
{
    /**
     * @var UserStripeEventService
     */
    private $userStripeEventService;

    /**
     * @var User
     */
    private $user;

    /**
     * @var array
     */
    private $event1Data = [
        'id' => 'evt_2c6KUnrLeIFqQv',
        'data' => [
            'object' => [
                'object' => 'customer',
            ],
        ],
    ];

    /**
     * @var array
     */
    private $event2Data = [
        'id' => 'evt_2c6KUnrLeIFqQw',
        'data' => [
            'object' => [
                'object' => 'customer',
            ],
        ],
    ];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->userStripeEventService = $this->container->get(
            'SimplyTestable\WebClientBundle\Services\UserStripeEventService'
        );

        $this->user = new User('user@example.com');
        $this->coreApplicationHttpClient->setUser($this->user);
    }

    /**
     * @dataProvider getLatestDataProvider
     *
     * @param array $httpFixtures
     * @param StripeEvent $expectedEvent
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testGetLatest(array $httpFixtures, $expectedEvent)
    {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $event = $this->userStripeEventService->getLatest($this->user, 'customer.subscription.created');

        $this->assertEquals($expectedEvent, $event);
    }

    /**
     * @return array
     */
    public function getLatestDataProvider()
    {
        $event1 = new StripeEvent(json_encode($this->event1Data));
        $event2 = new StripeEvent(json_encode($this->event2Data));

        return [
            'empty' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([]),
                ],
                'expectedEvent' => null,
            ],
            'single event' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        [
                            'stripe_event_data' => json_encode($this->event1Data),
                        ],
                    ]),
                ],
                'expectedEvent' => $event1,
            ],
            'two events' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        [
                            'stripe_event_data' => json_encode($this->event2Data),
                        ],
                        [
                            'stripe_event_data' => json_encode($this->event1Data),
                        ],
                    ]),
                ],
                'expectedEvent' => $event2,
            ],
        ];
    }
}
