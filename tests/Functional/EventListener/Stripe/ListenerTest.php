<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\EventListener\Stripe;

use App\Event\Stripe\Event as StripeEvent;
use App\EventListener\Stripe\Listener;
use App\Model\StripeNotification;
use App\Services\Mailer;
use App\Services\StripeNotificationFactory;
use App\Tests\Functional\AbstractBaseTestCase;
use Mockery\MockInterface;
use Symfony\Component\Routing\RouterInterface;

class ListenerTest extends AbstractBaseTestCase
{
    use CustomerSubscriptionCreatedDataProviderTrait;
    use CustomerSubscriptionDeletedDataProviderTrait;
    use CustomerSubscriptionTrialWillEndDataProviderTrait;
    use CustomerSubscriptionUpdatedDataProviderTrait;
    use InvoicePaymentFailedDataProviderTrait;
    use InvoicePaymentSucceededDataProviderTrait;

    /**
     * @dataProvider customerSubscriptionCreatedDataProvider
     * @dataProvider customerSubscriptionDeletedDataProvider
     * @dataProvider customerSubscriptionTrialWillEndDataProvider
     * @dataProvider customerSubscriptionUpdatedDataProvider
     * @dataProvider invoicePaymentFailedDataProvider
     * @dataProvider invoicePaymentSucceededDataProvider
     */
    public function testOnMethod(
        StripeEvent $event,
        string $listenerMethod,
        array $expectedStripeNotificationSubjectValueParameters,
        array $expectedStripeNotificationSubjectKeyParameterNames,
        array $expectedStripeNotificationViewNameParameters,
        array $expectedStripeNotificationViewParameters
    ) {
        /* @var StripeNotification|MockInterface $mockStripeNotification */
        $mockStripeNotification = \Mockery::mock(StripeNotification::class);

        /* @var StripeNotificationFactory|MockInterface $stripeNotificationFactory */
        $stripeNotificationFactory = \Mockery::mock(StripeNotificationFactory::class);
        $stripeNotificationFactory
            ->shouldReceive('create')
            ->withArgs([
                $event,
                $expectedStripeNotificationSubjectValueParameters,
                $expectedStripeNotificationSubjectKeyParameterNames,
                $expectedStripeNotificationViewNameParameters,
                $expectedStripeNotificationViewParameters,
            ])
            ->once()
            ->andReturn($mockStripeNotification);

        /* @var MockInterface|Mailer $mailer */
        $mailer = \Mockery::mock(Mailer::class);
        $mailer
            ->shouldReceive('sendStripeNotification')
            ->withArgs(function (StripeNotification $stripeNotification) use ($mockStripeNotification) {
                $this->assertEquals($mockStripeNotification, $stripeNotification);

                return true;
            })
            ->once();

        $listener = new Listener(self::$container->get(RouterInterface::class), $mailer, $stripeNotificationFactory);
        $listener->$listenerMethod($event);
    }
}
