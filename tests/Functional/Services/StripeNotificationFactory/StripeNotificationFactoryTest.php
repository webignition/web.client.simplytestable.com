<?php

namespace App\Tests\Functional\Services\StripeNotificationFactory;

use App\Event\Stripe\Event as StripeEvent;
use App\Model\StripeNotification;
use App\Services\StripeNotificationFactory;
use App\Tests\Functional\AbstractBaseTestCase;

class StripeNotificationFactoryTest extends AbstractBaseTestCase
{
    use CustomerSubscriptionCreatedDataProviderTrait;
    use CustomerSubscriptionDeletedDataProviderTrait;
    use CustomerSubscriptionTrialWillEndDataProviderTrait;
    use CustomerSubscriptionUpdatedDataProviderTrait;
    use InvoicePaymentFailedDataProviderTrait;
    use InvoicePaymentSucceededDataProviderTrait;

    const EVENT_USER = 'user@example.com';
    const ACCOUNT_URL = 'http://localhost/account';

    /**
     * @var StripeNotificationFactory
     */
    protected $stripeNotificationFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->stripeNotificationFactory = self::$container->get(StripeNotificationFactory::class);
    }

    /**
     * @dataProvider customerSubscriptionCreatedDataProvider
     * @dataProvider customerSubscriptionDeletedDataProvider
     * @dataProvider customerSubscriptionTrialWillEndDataProvider
     * @dataProvider customerSubscriptionUpdatedDataProvider
     * @dataProvider invoicePaymentFailedDataProvider
     * @dataProvider invoicePaymentSucceededDataProvider
     *
     * @param StripeEvent $event
     * @param array $subjectValueParameters
     * @param array $subjectKeyParameterNames
     * @param array $viewNameParameters
     * @param array $viewParameters
     * @param string $expectedSubjectSuffix
     * @param array $expectedMessageContains
     */
    public function testCreate(
        StripeEvent $event,
        array $subjectValueParameters,
        array $subjectKeyParameterNames,
        array $viewNameParameters,
        array $viewParameters,
        string $expectedSubjectSuffix,
        array $expectedMessageContains
    ) {
        $notification = $this->stripeNotificationFactory->create(
            $event,
            $subjectValueParameters,
            $subjectKeyParameterNames,
            $viewNameParameters,
            $viewParameters
        );

        $this->assertInstanceOf(StripeNotification::class, $notification);

        $this->assertEquals(self::EVENT_USER, $notification->getRecipient());
        $this->assertEquals('[Simply Testable] ' . $expectedSubjectSuffix, $notification->getSubject());

        foreach ($expectedMessageContains as $messageShouldContain) {
            $this->assertContains($messageShouldContain, $notification->getMessage());
        }


        $notification->getMessage();
    }
}
