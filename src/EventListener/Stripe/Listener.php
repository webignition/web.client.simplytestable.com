<?php

namespace App\EventListener\Stripe;

use App\Services\Mailer;
use App\Services\StripeNotificationFactory;
use App\Event\Stripe\Event as StripeEvent;
use App\Exception\Mail\Configuration\Exception as MailConfigurationException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class Listener
{
    const VIEW_BASE_PATH = 'Email/Stripe/Event/';
    const DEFAULT_CURRENCY_SYMBOL = '£';

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var array
     */
    private $currencySymbolMap = [
        'gbp' => '£',
        'usd' => '$'
    ];

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var StripeNotificationFactory
     */
    private $stripeNotificationFactory;

    public function __construct(
        RouterInterface $router,
        Mailer $mailer,
        StripeNotificationFactory $stripeNotificationFactory
    ) {
        $this->router = $router;
        $this->mailer = $mailer;
        $this->stripeNotificationFactory = $stripeNotificationFactory;
    }

    /**
     * @param int $amount
     *
     * @return string
     */
    private function getFormattedAmount($amount)
    {
        return number_format($amount / 100, 2);
    }

    /**
     * @param int $timestamp
     *
     * @return string
     */
    private function getFormattedDateString($timestamp)
    {
        return date('j F Y', $timestamp);
    }

    /**
     * @param string $invoiceId
     *
     * @return string
     */
    private function getFormattedInvoiceId($invoiceId)
    {
        return '#' . str_replace('in_', '', $invoiceId);
    }

    /**
     * @param StripeEvent $event
     *
     * @throws MailConfigurationException
     */
    public function onCustomerSubscriptionCreated(StripeEvent $event)
    {
        $eventData = $event->getData();

        $subjectValueParameters = [
            'plan_name' => strtolower($eventData->get('plan_name'))
        ];

        $viewNameParameters = [
            'status'
        ];

        if ($eventData->get('status') == 'trialing') {
            $viewNameParameters[] = 'has_card';
        }

        $viewParameters = [
            'plan_name' => strtolower($eventData->get('plan_name')),
            'trial_period_days' => $eventData->get('trial_period_days'),
            'trial_end' => $this->getFormattedDateString($eventData->get('trial_end')),
            'amount' => $this->getFormattedAmount($eventData->get('amount')),
            'account_url' => $this->generateAccountUrl(),
            'currency_symbol' => $this->getCurrencySymbol($eventData->get('currency'))
        ];

        $stripeNotification = $this->stripeNotificationFactory->create(
            $event,
            $subjectValueParameters,
            [],
            $viewNameParameters,
            $viewParameters
        );

        $this->mailer->sendStripeNotification($stripeNotification);
    }

    /**
     * @param StripeEvent $event
     *
     * @throws MailConfigurationException
     */
    public function onCustomerSubscriptionTrialWillEnd(StripeEvent $event)
    {
        $eventData = $event->getData();

        $subjectValueParameters = [
            'plan_name' => strtolower($eventData->get('plan_name')),
            'payment_details_needed_suffix' => ($eventData->get('has_card')) ? '' : ', payment details needed'
        ];

        $viewParameters = [
            'plan_name' => strtolower($eventData->get('plan_name')),
            'plan_amount' => $this->getFormattedAmount($eventData->get('plan_amount')),
            'account_url' => $this->generateAccountUrl(),
            'currency_symbol' => $this->getCurrencySymbol($eventData->get('plan_currency'))
        ];

        $viewNameParameters = [
            'has_card',
        ];

        $stripeNotification = $this->stripeNotificationFactory->create(
            $event,
            $subjectValueParameters,
            [],
            $viewNameParameters,
            $viewParameters
        );

        $this->mailer->sendStripeNotification($stripeNotification);
    }

    /**
     * @param StripeEvent $event
     *
     * @throws MailConfigurationException
     */
    public function onCustomerSubscriptionUpdated(StripeEvent $event)
    {
        /**
         * Now only occurs for trialing to active
         * either 'all active now' or 'downgraded to free'
         * No more active to canceled
         * also plan change!
         */

        $eventData = $event->getData();

        if ($eventData->get('is_plan_change')) {
            $eventData->set('plan_change', 1);

            $subjectValueParameters = [
                'new_plan' => strtolower($eventData->get('new_plan'))
            ];

            $subjectKeyParameterNames = [
                'plan_change'
            ];

            $viewParameters = [
                'new_plan' => strtolower($eventData->get('new_plan')),
                'old_plan' => strtolower($eventData->get('old_plan')),
                'new_amount' => $this->getFormattedAmount($eventData->get('new_amount')),
                'trial_end' => $this->getFormattedDateString($eventData->get('trial_end')),
                'currency_symbol' => $this->getCurrencySymbol($eventData->get('currency'))
            ];

            $viewNameParameters = [
                'plan_change',
                'subscription_status'
            ];

            $stripeNotification = $this->stripeNotificationFactory->create(
                $event,
                $subjectValueParameters,
                $subjectKeyParameterNames,
                $viewNameParameters,
                $viewParameters
            );

            $this->mailer->sendStripeNotification($stripeNotification);

            return;
        }

        if ($eventData->get('is_status_change')) {
            $transition = sprintf(
                '%s_to_%s',
                $eventData->get('previous_subscription_status'),
                $eventData->get('subscription_status')
            );
            $eventData->set('transition', $transition);

            $subjectKeyParameterNames = [
                'transition',
                'has_card'
            ];

            $viewParameters = [
                'plan_name' => strtolower($eventData->get('plan_name')),
                'plan_amount' => $this->getFormattedAmount($eventData->get('plan_amount')),
                'account_url' => $this->generateAccountUrl(),
                'currency_symbol' => $this->getCurrencySymbol($eventData->get('currency'))
            ];

            $viewNameParameters = [
                'transition',
                'has_card'
            ];

            $stripeNotification = $this->stripeNotificationFactory->create(
                $event,
                [],
                $subjectKeyParameterNames,
                $viewNameParameters,
                $viewParameters
            );

            $this->mailer->sendStripeNotification($stripeNotification);

            return;
        }
    }

    /**
     * @param StripeEvent $event
     *
     * @throws MailConfigurationException
     */
    public function onInvoicePaymentFailed(StripeEvent $event)
    {
        $eventData = $event->getData();

        $subjectValueParameters = [
            'invoice_id' => $this->getFormattedInvoiceId($eventData->get('invoice_id'))
        ];

        $viewParameters = [
            'invoice_id' => $this->getFormattedInvoiceId($eventData->get('invoice_id')),
            'account_url' => $this->generateAccountUrl(),
            'invoice_lines' => $this->getInvoiceLinesContent($eventData->get('lines'), $eventData->get('currency')),
        ];

        $stripeNotification = $this->stripeNotificationFactory->create(
            $event,
            $subjectValueParameters,
            [],
            [],
            $viewParameters
        );

        $this->mailer->sendStripeNotification($stripeNotification);
    }

    /**
     * @param StripeEvent $event
     *
     * @throws MailConfigurationException
     */
    public function onInvoicePaymentSucceeded(StripeEvent $event)
    {
        $eventData = $event->getData();

        $subjectValueParameters = [
            'invoice_id' => $this->getFormattedInvoiceId($event->getData()->get('invoice_id'))
        ];

        $viewParameters = [
            'plan_name' => strtolower($eventData->get('plan_name')),
            'account_url' => $this->generateAccountUrl(),
            'invoice_lines' => $this->getInvoiceLinesContent($eventData->get('lines'), $eventData->get('currency')),
            'invoice_id' => $this->getFormattedInvoiceId($eventData->get('invoice_id')),
            'subtotal' => (int)$eventData->get('subtotal'),
            'total_line' => $this->getInvoiceTotalLine((int)$eventData->get('total'), $eventData->get('currency')),
        ];

        if ($eventData->has('discount')) {
            $viewParameters['discount_line'] = $this->getInvoiceDiscountContent(
                $eventData->get('discount'),
                $eventData->get('currency')
            );
        }

        $viewNameParameters = [
            'has_discount',
        ];

        $stripeNotification = $this->stripeNotificationFactory->create(
            $event,
            $subjectValueParameters,
            [],
            $viewNameParameters,
            $viewParameters
        );

        $this->mailer->sendStripeNotification($stripeNotification);
    }

    /**
     * @param StripeEvent $event
     *
     * @throws MailConfigurationException
     */
    public function onCustomerSubscriptionDeleted(StripeEvent $event)
    {
        $eventData = $event->getData();

        $subjectValueParameters = [
            'plan_name' => strtolower($eventData->get('plan_name'))
        ];

        $subjectKeyParameterNames = [
            'actioned_by'
        ];

        if ($eventData->get('actioned_by') == 'user') {
            $subjectKeyParameterNames[] = 'is_during_trial';
        }

        $viewParameters = [
            'trial_days_remaining' => $eventData->get('trial_days_remaining'),
            'trial_days_remaining_pluralisation' => ($eventData->get('trial_days_remaining') == 1 ? '' : 's'),
            'account_url' => $this->generateAccountUrl(),
            'plan_name' => strtolower($eventData->get('plan_name'))
        ];

        $viewNameParameters = [
            'actioned_by'
        ];

        if ($eventData->get('actioned_by') == 'user') {
            $viewNameParameters[] = 'is_during_trial';
        }

        $stripeNotification = $this->stripeNotificationFactory->create(
            $event,
            $subjectValueParameters,
            $subjectKeyParameterNames,
            $viewNameParameters,
            $viewParameters
        );

        $this->mailer->sendStripeNotification($stripeNotification);
    }

    /**
     * @param array $invoiceLines
     * @param string $currency
     *
     * @return string
     */
    private function getInvoiceLinesContent($invoiceLines, $currency)
    {
        $contentLines = [];

        foreach ($invoiceLines as $invoiceLine) {
            $contentLine = sprintf(
                ' * %s plan subscription, %s to %s (%s',
                $invoiceLine['plan_name'],
                $this->getFormattedDateString($invoiceLine['period_start']),
                $this->getFormattedDateString($invoiceLine['period_end']),
                $this->getCurrencySymbol($currency)
            );

            $contentLine .= $this->getFormattedAmount($invoiceLine['amount']);

            if (isset($invoiceLine['proration']) && $invoiceLine['proration']) {
                $contentLine .= ', prorated';
            }

            $contentLine .= ')';

            $contentLines[] = $contentLine;
        }

        return implode("\n", $contentLines);
    }

    /**
     * @param array $discount
     * @param string $currency
     *
     * @return string
     */
    private function getInvoiceDiscountContent($discount, $currency)
    {
        return sprintf(
            ' * %s%% off with coupon %s (-%s%s)',
            $discount['percent_off'],
            $discount['coupon'],
            $this->getCurrencySymbol($currency),
            number_format($discount['discount'] / 100, 2)
        );
    }

    /**
     * @param string $total
     * @param string $currency
     *
     * @return string
     */
    private function getInvoiceTotalLine($total, $currency)
    {
        return "   =====================\n" . sprintf(
            ' * Total: %s%s',
            $this->getCurrencySymbol($currency),
            number_format($total / 100, 2)
        );
    }

    /**
     * @param string $currency
     * @return string
     */
    private function getCurrencySymbol($currency)
    {
        if (!isset($this->currencySymbolMap[$currency])) {
            return self::DEFAULT_CURRENCY_SYMBOL;
        }

        return $this->currencySymbolMap[$currency];
    }

    /**
     * @return string
     */
    private function generateAccountUrl()
    {
        return $this->router->generate(
            'view_user_account',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
