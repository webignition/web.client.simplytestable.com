<?php

namespace App\Tests\Functional\Services\StripeNotificationFactory;

use App\Event\Stripe\Event as StripeEvent;
use App\Model\StripeNotification;
use App\Services\Configuration\MailConfiguration;
use App\Services\StripeNotificationFactory;
use App\Tests\Functional\AbstractBaseTestCase;
use Mockery\MockInterface;

class StripeNotificationFactoryTest extends AbstractBaseTestCase
{
    use CustomerSubscriptionCreatedDataProviderTrait;
    use CustomerSubscriptionDeletedDataProviderTrait;
    use CustomerSubscriptionTrialWillEndDataProviderTrait;
    use CustomerSubscriptionUpdatedDataProviderTrait;
    use InvoicePaymentFailedDataProviderTrait;
    use InvoicePaymentSucceededDataProviderTrait;

    const MOCK_RENDERED_MESSAGE = 'Mock rendered message';
    const EVENT_USER = 'user@example.com';
    const ACCOUNT_URL = 'http://localhost/account';

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
     * @param string $expectedSubjectSuffix
     * @param string $expectedViewName,
     */
    public function testCreate(
        StripeEvent $event,
        array $subjectValueParameters,
        array $subjectKeyParameterNames,
        array $viewNameParameters,
        string $expectedSubjectSuffix,
        string $expectedViewName
    ) {
        $viewParameters = [
            'foo' => 'bar',
        ];

        /* @var \Twig_Environment|MockInterface $twig */
        $twig = \Mockery::mock(\Twig_Environment::class);
        $twig
            ->shouldReceive('render')
            ->withArgs([
                $expectedViewName,
                $viewParameters
            ])
            ->once()
            ->andReturn(self::MOCK_RENDERED_MESSAGE);

        $stripeNotificationFactory = new StripeNotificationFactory(
            $twig,
            self::$container->get(MailConfiguration::class)
        );

        $notification = $stripeNotificationFactory->create(
            $event,
            $subjectValueParameters,
            $subjectKeyParameterNames,
            $viewNameParameters,
            $viewParameters
        );

        $this->assertInstanceOf(StripeNotification::class, $notification);

        $this->assertEquals(self::EVENT_USER, $notification->getRecipient());
        $this->assertEquals('[Simply Testable] ' . $expectedSubjectSuffix, $notification->getSubject());
        $this->assertEquals(self::MOCK_RENDERED_MESSAGE, $notification->getMessage());
    }
}
