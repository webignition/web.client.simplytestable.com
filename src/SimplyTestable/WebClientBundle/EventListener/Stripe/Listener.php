<?php

namespace SimplyTestable\WebClientBundle\EventListener\Stripe;

use Postmark\Models\PostmarkException;
use Postmark\PostmarkClient;
use Psr\Log\LoggerInterface;
use SimplyTestable\WebClientBundle\Event\Stripe\Event as StripeEvent;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;
use SimplyTestable\WebClientBundle\Services\Configuration\MailConfiguration;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class Listener
{
    const VIEW_BASE_PATH = 'Email/Stripe/Event/';
    const DEFAULT_CURRENCY_SYMBOL = '£';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var StripeEvent
     */
    private $event;

    /**
     * @var array
     */
    private $currencySymbolMap = [
        'gbp' => '£',
        'usd' => '$'
    ];

    /**
     * @var MailConfiguration
     */
    private $mailConfiguration;

    /**
     * @var PostmarkClient
     */
    private $postmarkClient;

    /**
     * @param LoggerInterface $logger
     * @param Twig_Environment $twig
     * @param RouterInterface $router
     * @param MailConfiguration $mailConfiguration
     * @param PostmarkClient $postmarkClient
     */
    public function __construct(
        LoggerInterface $logger,
        Twig_Environment $twig,
        RouterInterface $router,
        MailConfiguration $mailConfiguration,
        PostmarkClient $postmarkClient
    ) {
        $this->logger = $logger;
        $this->twig = $twig;
        $this->router = $router;
        $this->mailConfiguration = $mailConfiguration;
        $this->postmarkClient = $postmarkClient;
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
     * @throws \Twig_Error
     */
    public function onCustomerSubscriptionCreated(StripeEvent $event)
    {
        $this->event = $event;
        $eventData = $event->getData();

        $subject = $this->createSubject(
            $event,
            [
                'plan_name' => strtolower($eventData->get('plan_name'))
            ]
        );

        $viewParameters = [
            'plan_name' => strtolower($eventData->get('plan_name')),
            'trial_period_days' => $eventData->get('trial_period_days'),
            'trial_end' => $this->getFormattedDateString($eventData->get('trial_end')),
            'amount' => $this->getFormattedAmount($eventData->get('amount')),
            'account_url' => $this->generateAccountUrl(),
            'currency_symbol' => $this->getCurrencySymbol($eventData->get('currency'))
        ];

        $viewPathParameters = [
            'status'
        ];

        if ($eventData->get('status') == 'trialing') {
            $viewPathParameters[] = 'has_card';
        }

        $this->issueNotification(
            $subject,
            $this->twig->render($this->getViewPath($event, $viewPathParameters), $viewParameters)
        );
    }

    /**
     * @param StripeEvent $event
     *
     * @throws MailConfigurationException
     * @throws \Twig_Error
     */
    public function onCustomerSubscriptionTrialWillEnd(StripeEvent $event)
    {
        $this->event = $event;

        $eventData = $event->getData();

        $subject = $this->createSubject(
            $event,
            [
                'plan_name' => strtolower($eventData->get('plan_name')),
                'payment_details_needed_suffix' => ($eventData->get('has_card')) ? '' : ', payment details needed'
            ]
        );

        $viewParameters = [
            'plan_name' => strtolower($eventData->get('plan_name')),
            'plan_amount' => $this->getFormattedAmount($eventData->get('plan_amount')),
            'account_url' => $this->generateAccountUrl(),
            'currency_symbol' => $this->getCurrencySymbol($eventData->get('plan_currency'))
        ];

        $this->issueNotification($subject, $this->twig->render($this->getViewPath($event, [
            'has_card'
        ]), $viewParameters));
    }

    /**
     * @param StripeEvent $event
     *
     * @throws MailConfigurationException
     * @throws \Twig_Error
     */
    public function onCustomerSubscriptionUpdated(StripeEvent $event)
    {
        /**
         * Now only occurs for trialing to active
         * either 'all active now' or 'downgraded to free'
         * No more active to canceled
         * also plan change!
         */

        $this->event = $event;

        $eventData = $event->getData();

        if ($eventData->get('is_plan_change')) {
            $eventData->set('plan_change', 1);

            $subject = $this->createSubject(
                $event,
                [
                    'new_plan' => strtolower($eventData->get('new_plan'))
                ],
                [
                    'plan_change'
                ]
            );

            $viewParameters = [
                'new_plan' => strtolower($eventData->get('new_plan')),
                'old_plan' => strtolower($eventData->get('old_plan')),
                'new_amount' => $this->getFormattedAmount($eventData->get('new_amount')),
                'trial_end' => $this->getFormattedDateString($eventData->get('trial_end')),
                'currency_symbol' => $this->getCurrencySymbol($eventData->get('currency'))
            ];

            $this->issueNotification($subject, $this->twig->render($this->getViewPath($event, [
                'plan_change',
                'subscription_status'
            ]), $viewParameters));

            return;
        }

        if ($eventData->get('is_status_change')) {
            $transition = sprintf(
                '%s_to_%s',
                $eventData->get('previous_subscription_status'),
                $eventData->get('subscription_status')
            );
            $eventData->set('transition', $transition);

            $subject = $this->createSubject(
                $event,
                [],
                [
                'transition',
                'has_card'
                ]
            );

            $viewParameters = [
                'plan_name' => strtolower($eventData->get('plan_name')),
                'plan_amount' => $this->getFormattedAmount($eventData->get('plan_amount')),
                'account_url' => $this->generateAccountUrl(),
                'currency_symbol' => $this->getCurrencySymbol($eventData->get('currency'))
            ];

            $this->issueNotification($subject, $this->twig->render($this->getViewPath($event, [
                'transition',
                'has_card'
            ]), $viewParameters));

            return;
        }
    }

    /**
     * @param StripeEvent $event
     *
     * @throws MailConfigurationException
     * @throws \Twig_Error
     */
    public function onInvoicePaymentFailed(StripeEvent $event)
    {
        $this->event = $event;

        $eventData = $event->getData();

        $subject = $this->createSubject(
            $event,
            [
                'invoice_id' => $this->getFormattedInvoiceId($eventData->get('invoice_id'))
            ]
        );

        $viewParameters = [
            'invoice_id' => $this->getFormattedInvoiceId($eventData->get('invoice_id')),
            'account_url' => $this->generateAccountUrl(),
            'invoice_lines' => $this->getInvoiceLinesContent($eventData->get('lines'), $eventData->get('currency')),
        ];

        $this->issueNotification($subject, $this->twig->render($this->getViewPath($event), $viewParameters));
    }

    /**
     * @param StripeEvent $event
     *
     * @throws MailConfigurationException
     * @throws \Twig_Error
     */
    public function onInvoicePaymentSucceeded(StripeEvent $event)
    {
        $this->event = $event;

        $eventData = $event->getData();

        $viewParameters = [
            'plan_name' => strtolower($eventData->get('plan_name')),
            'account_url' => $this->generateAccountUrl(),
            'invoice_lines' => $this->getInvoiceLinesContent($eventData->get('lines'), $eventData->get('currency')),
            'invoice_id' => $this->getFormattedInvoiceId($eventData->get('invoice_id')),
            'subtotal' => (int)$eventData->get('subtotal'),
            'total_line' => $this->getInvoiceTotalLine((int)$eventData->get('total'), $eventData->get('currency')),
        ];

        if ($this->event->getData()->has('discount')) {
            $viewParameters['discount_line'] = $this->getInvoiceDiscountContent(
                $eventData->get('discount'),
                $eventData->get('currency')
            );
        }

        $subject = $this->createSubject(
            $event,
            [
                'invoice_id' => $this->getFormattedInvoiceId($event->getData()->get('invoice_id'))
            ]
        );

        $this->issueNotification($subject, $this->twig->render($this->getViewPath($event, [
            'has_discount'
        ]), $viewParameters));
    }

    /**
     * @param StripeEvent $event
     *
     * @throws MailConfigurationException
     * @throws \Twig_Error
     */
    public function onCustomerSubscriptionDeleted(StripeEvent $event)
    {
        $this->event = $event;

        $subjectKeyParameters = [
            'actioned_by'
        ];

        $eventData = $event->getData();

        if ($eventData->get('actioned_by') == 'user') {
            $subjectKeyParameters[] = 'is_during_trial';
        }

        $subject = $this->createSubject(
            $event,
            [
                'plan_name' => strtolower($eventData->get('plan_name'))
            ],
            $subjectKeyParameters
        );

        $viewParameters = [
            'trial_days_remaining' => $eventData->get('trial_days_remaining'),
            'trial_days_remaining_pluralisation' => ($eventData->get('trial_days_remaining') == 1 ? '' : 's'),
            'account_url' => $this->generateAccountUrl(),
            'plan_name' => strtolower($eventData->get('plan_name'))
        ];

        $viewPathParameters = [
            'actioned_by'
        ];

        if ($eventData->get('actioned_by') == 'user') {
            $viewPathParameters[] = 'is_during_trial';
        }

        $this->issueNotification(
            $subject,
            $this->twig->render($this->getViewPath($event, $viewPathParameters), $viewParameters)
        );
    }

    /**
     * @param StripeEvent $event
     * @param null $valueParameters
     * @param null $keyParameterNames
     *
     * @return string
     *
     * @throws MailConfigurationException
     */
    private function createSubject(StripeEvent $event, $valueParameters = null, $keyParameterNames = null)
    {
        $eventData = $event->getData();

        $key = 'stripe.' . $event->getName();

        if (is_array($keyParameterNames)) {
            $keyNameParameterisedParts = [];
            foreach ($keyParameterNames as $name) {
                if ($eventData->has($name)) {
                    $keyNameParameterisedParts[] = $name . '=' . $eventData->get($name);
                }
            }

            $key .= '-' . implode('-', $keyNameParameterisedParts);
        }

        $messageProperties = $this->mailConfiguration->getMessageProperties($key);

        foreach ($valueParameters as $key => $value) {
            $valueParameters['{{'.$key.'}}'] = $value;
            unset($valueParameters[$key]);
        }

        return str_replace(array_keys($valueParameters), array_values($valueParameters), $messageProperties['subject']);
    }

    /**
     * @param StripeEvent $event
     * @param array|null $parameterKeys
     *
     * @return string
     */
    private function getViewPath(StripeEvent $event, $parameterKeys = null)
    {
        $eventData = $event->getData();

        if (is_array($parameterKeys)) {
            $parameterisedParts = [];
            foreach ($parameterKeys as $key) {
                if ($eventData->has($key)) {
                    $parameterisedParts[] = $key . '=' . $eventData->get($key);
                }
            }

            $filenamebody = implode('-', $parameterisedParts);
        } else {
            $filenamebody = 'notification';
        }

        return self::VIEW_BASE_PATH . $eventData->get('event') . '/' . $filenamebody . '.txt.twig';
    }

    /**
     * @param string $subject
     * @param string $messageBody
     *
     * @throws MailConfigurationException
     * @throws PostmarkException
     */
    private function issueNotification($subject, $messageBody)
    {
        $sender = $this->mailConfiguration->getSender('notifications');

        $this->postmarkClient->sendEmail(
            $sender['email'],
            $this->event->getUser(),
            $subject,
            null,
            $messageBody
        );
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
            'view_user_account_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
