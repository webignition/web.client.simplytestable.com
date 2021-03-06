<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services;

use App\Services\UserStripeEventService;
use App\Tests\Factory\HttpResponseFactory;
use webignition\Model\Stripe\Event\Event as StripeEvent;
use webignition\SimplyTestableUserModel\User;

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

    private $event1Data = [
        'id' => 'evt_2c6KUnrLeIFqQv',
        'data' => [
            'object' => [
                'object' => 'customer',
            ],
        ],
    ];

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

        $this->userStripeEventService = self::$container->get(UserStripeEventService::class);

        $this->user = new User('user@example.com');
    }

    /**
     * @dataProvider getLatestDataProvider
     */
    public function testGetLatest(array $httpFixtures, ?StripeEvent $expectedEvent)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $event = $this->userStripeEventService->getLatest($this->user, 'customer.subscription.created');

        $this->assertEquals($expectedEvent, $event);
    }

    public function getLatestDataProvider(): array
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
