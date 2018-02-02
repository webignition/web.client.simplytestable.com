<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services;

use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserStripeEventService;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
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
            'simplytestable.services.userstripeeventservice'
        );

        $this->user = new User('user@example.com');
    }

    /**
     * @dataProvider getListDataProvider
     *
     * @param array $httpFixtures
     * @param int $expectedListLength
     *
     * @throws WebResourceException
     */
    public function testGetList(array $httpFixtures, $expectedListLength)
    {
        $this->setHttpFixtures($httpFixtures);

        $this->userStripeEventService->setUser($this->user);
        $list = $this->userStripeEventService->getList($this->user, 'customer.subscription.created');

        $this->assertInternalType('array', $list);
        $this->assertCount($expectedListLength, $list);

        $this->assertEquals(
            'http://null/user/user@example.com/stripe-events/customer.subscription.created/',
            $this->getLastRequest()->getUrl()
        );
    }

    /**
     * @return array
     */
    public function getListDataProvider()
    {
        return [
            'empty' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([]),
                ],
                'expectedListLength' => 0,
            ],
            'single event' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        [
                            'stripe_event_data' => json_encode($this->event1Data),
                        ],
                    ]),
                ],
                'expectedListLength' => 1,
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
                'expectedListLength' => 2,
            ],
        ];
    }

    /**
     * @dataProvider getLatestDataProvider
     *
     * @param array $httpFixtures
     * @param StripeEvent $expectedEvent
     *
     * @throws WebResourceException
     */
    public function testGetLatest(array $httpFixtures, $expectedEvent)
    {
        $this->setHttpFixtures($httpFixtures);

        $this->userStripeEventService->setUser($this->user);
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
