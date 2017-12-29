<?php

namespace SimplyTestable\WebClientBundle\EventListener\Stripe;

use Psr\Log\LoggerInterface;
use SimplyTestable\WebClientBundle\Event\Stripe\Event as StripeEvent;

class Listener
{
    const VIEW_BASE_PATH = 'SimplyTestableWebClientBundle:Email/Stripe/Event/';
    const DEFAULT_CURRENCY_SYMBOL = '£';

    /**
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     *
     * @var \Symfony\Bundle\TwigBundle\TwigEngine
     */
    private $templating;


    /**
     *
     * @var \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    private $router;


    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\Mail\Service
     */
    private $mailService;


    /**
     *
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
     * @param LoggerInterface $logger
     * @param \Symfony\Bundle\TwigBundle\TwigEngine $templating
     * @param \Symfony\Bundle\FrameworkBundle\Routing\Router $router
     * @param \SimplyTestable\WebClientBundle\Services\Mail\Service $mailService
     */
    public function __construct(
        LoggerInterface $logger,
            \Symfony\Bundle\TwigBundle\TwigEngine $templating,
            \Symfony\Bundle\FrameworkBundle\Routing\Router $router,
            \SimplyTestable\WebClientBundle\Services\Mail\Service $mailService
    ) {
        $this->logger = $logger;
        $this->templating = $templating;
        $this->router = $router;
        $this->mailService = $mailService;
    }

    /**
     * @param array|null $parameterKeys
     *
     * @return string
     */
    private function getViewPath($parameterKeys = null)
    {
        if (is_array($parameterKeys)) {
            $parameterisedParts = [];
            foreach ($parameterKeys as $key) {
                if ($this->event->getData()->has($key)) {
                    $parameterisedParts[] = $key . '=' . $this->event->getData()->get($key);
                }
            }

            $filenamebody = implode('-', $parameterisedParts);
        } else {
            $filenamebody = 'notification';
        }

        return self::VIEW_BASE_PATH . $this->event->getData()->get('event') . ':' . $filenamebody . '.txt.twig';
    }

    private function getSubject($valueParameters = null, $keyParameterNames = null)
    {
        $eventData = $this->event->getData();

        $key = 'stripe.' . $this->event->getName();

        if (is_array($keyParameterNames)) {
            $keyNameParameterisedParts = array();
            foreach ($keyParameterNames as $name) {
                if ($eventData->has($name)) {
                    $keyNameParameterisedParts[] = $name . '=' . $eventData->get($name);
                }
            }

            $key .= '-' . implode('-', $keyNameParameterisedParts);
        }

        $messageProperties = $this->mailService->getConfiguration()->getMessageProperties($key);

        if (!is_array($valueParameters)) {
            return $messageProperties['subject'];
        }

        foreach ($valueParameters as $key => $value) {
            $valueParameters['{{'.$key.'}}'] = $value;
            unset($valueParameters[$key]);
        }

        return str_replace(array_keys($valueParameters), array_values($valueParameters), $messageProperties['subject']);
    }


    /**
     *
     * @param int $amount
     * @return string
     */
    private function getFormattedAmount($amount) {
        return number_format($amount / 100, 2);
    }


    /**
     *
     * @param int $timestamp
     * @return string
     */
    private function getFormattedDateString($timestamp) {
        return date('j F Y', $timestamp);
    }


    /**
     *
     * @param string $invoiceId
     * @return string
     */
    private function getFormattedInvoiceId($invoiceId) {
        return '#' . str_replace('in_', '', $invoiceId);
    }

    /**
     * @param StripeEvent $event
     *
     * @throws \Twig_Error
     */
    public function onCustomerSubscriptionCreated(StripeEvent $event)
    {
        $this->event = $event;
        $eventData = $event->getData();

        $subject = $this->getSubject([
            'plan_name' => strtolower($eventData->get('plan_name'))
        ]);

        $viewParameters = [
            'plan_name' => strtolower($eventData->get('plan_name')),
            'trial_period_days' => $eventData->get('trial_period_days'),
            'trial_end' => $this->getFormattedDateString($eventData->get('trial_end')),
            'amount' => $this->getFormattedAmount($eventData->get('amount')),
            'account_url' => $this->router->generate('view_user_account_index_index', [], true),
            'currency_symbol' => $this->getCurrencySymbol($eventData->get('currency'))
        ];

        $viewPathParameters = [
            'status'
        ];

        if ($event->getData()->get('status') == 'trialing') {
            $viewPathParameters[] = 'has_card';
        }

        $this->issueNotification(
            $subject,
            $this->templating->render($this->getViewPath($viewPathParameters), $viewParameters)
        );
    }

    /**
     * @param StripeEvent $event
     *
     * @throws \Twig_Error
     */
    public function onCustomerSubscriptionTrialWillEnd(StripeEvent $event)
    {
        $this->event = $event;

        $eventData = $event->getData();

        $subject = $this->getSubject(array(
            'plan_name' => strtolower($eventData->get('plan_name')),
            'payment_details_needed_suffix' => ($eventData->get('has_card')) ? '' : ', payment details needed'
        ));

        $viewParameters = [
            'plan_name' => strtolower($eventData->get('plan_name')),
            'plan_amount' => $this->getFormattedAmount($eventData->get('plan_amount')),
            'account_url' => $this->router->generate('view_user_account_index_index', [], true),
            'currency_symbol' => $this->getCurrencySymbol($eventData->get('plan_currency'))
        ];

        $this->issueNotification($subject, $this->templating->render($this->getViewPath([
            'has_card'
        ]), $viewParameters));
    }

    public function onCustomerSubscriptionUpdated(StripeEvent $event) {
        /**
         * Now only occurs for trialing to active
         * either 'all active now' or 'downgraded to free'
         * No more active to canceled
         * also plan change!
         */

        $this->event = $event;

        if ($event->getData()->get('is_plan_change')) {
            $this->event->getData()->set('plan_change', 1);

            $subject = $this->getSubject(array(
                'new_plan' => strtolower($event->getData()->get('new_plan'))
            ), array(
                'plan_change'
            ));

            $viewParameters = array(
                'new_plan' => strtolower($event->getData()->get('new_plan')),
                'old_plan' => strtolower($event->getData()->get('old_plan')),
                'new_amount' => $this->getFormattedAmount($event->getData()->get('new_amount')),
                'trial_end' => $this->getFormattedDateString($event->getData()->get('trial_end')),
                'currency_symbol' => $this->getCurrencySymbol($event->getData()->get('currency'))
            );

            $this->issueNotification($subject, $this->templating->render($this->getViewPath(array(
                'plan_change',
                'subscription_status'
            )), $viewParameters));
            return;
        }

        if ($event->getData()->get('is_status_change')) {
            $transition = $event->getData()->get('previous_subscription_status') . '_to_' . $event->getData()->get('subscription_status');
            $this->event->getData()->set('transition', $transition);

            $subject = $this->getSubject(array(), array(
                'transition',
                'has_card'
            ));

            $viewParameters = array(
                'plan_name' => strtolower($event->getData()->get('plan_name')),
                'plan_amount' => $this->getFormattedAmount($event->getData()->get('plan_amount')),
                'account_url' => $this->router->generate('view_user_account_index_index', array(), true),
                'currency_symbol' => $this->getCurrencySymbol($event->getData()->get('currency'))
            );

            $this->issueNotification($subject, $this->templating->render($this->getViewPath(array(
                'transition',
                'has_card'
            )), $viewParameters));
            return;
        }
    }


    public function onInvoicePaymentFailed(StripeEvent $event) {
        $this->event = $event;

        $subject = $this->getSubject(array(
            'invoice_id' => $this->getFormattedInvoiceId($event->getData()->get('invoice_id'))
        ));

        $viewParameters = array(
            'invoice_id' => $this->getFormattedInvoiceId($event->getData()->get('invoice_id')),
            'account_url' => $this->router->generate('view_user_account_index_index', array(), true),
            'invoice_lines' => $this->getInvoiceLinesContent($event->getData()->get('lines'), $event->getData()->get('currency')),
        );

        $this->issueNotification($subject, $this->templating->render($this->getViewPath(), $viewParameters));
    }


    public function onInvoicePaymentSucceeded(StripeEvent $event) {
        $this->event = $event;

        $viewParameters = array(
            'plan_name' => strtolower($event->getData()->get('plan_name')),
            'account_url' => $this->router->generate('view_user_account_index_index', array(), true),
            'invoice_lines' => $this->getInvoiceLinesContent($event->getData()->get('lines'), $event->getData()->get('currency')),
            'invoice_id' => $this->getFormattedInvoiceId($event->getData()->get('invoice_id')),
            'subtotal' => (int)$event->getData()->get('subtotal'),
            'total_line' => $this->getInvoiceTotalLine((int)$event->getData()->get('total'), $event->getData()->get('currency')),
        );

        if ($this->event->getData()->has('discount')) {
            $viewParameters['discount_line'] = $this->getInvoiceDiscountContent($event->getData()->get('discount'), $event->getData()->get('currency'));
        }

        $this->issueNotification($this->getSubject(array(
            'invoice_id' => $this->getFormattedInvoiceId($event->getData()->get('invoice_id'))
        )), $this->templating->render($this->getViewPath([
            'has_discount'
        ]), $viewParameters));
    }

    /**
     * @param StripeEvent $event
     *
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

        $subject = $this->getSubject([
            'plan_name' => strtolower($eventData->get('plan_name'))
        ], $subjectKeyParameters);

        $viewParameters = [
            'trial_days_remaining' => $eventData->get('trial_days_remaining'),
            'trial_days_remaining_pluralisation' => ($eventData->get('trial_days_remaining') == 1 ? '' : 's'),
            'account_url' => $this->router->generate('view_user_account_index_index', [], true),
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
            $this->templating->render($this->getViewPath($viewPathParameters), $viewParameters)
        );
    }


    private function issueNotification($subject, $messageBody) {
        $sender = $this->mailService->getConfiguration()->getSender('notifications');

        $message = $this->mailService->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo($this->event->getUser());
        //$message->addTo('jon@simplytestable.com');
        $message->setSubject($subject);
        $message->setTextMessage($messageBody);

//        var_dump($message);
//        exit();

        $this->mailService->getSender()->send($message);
    }

    private function getInvoiceLinesContent($invoiceLines, $currency) {
        $contentLines = array();

        foreach ($invoiceLines as $invoiceLine) {
            $contentLine = ' * ' . $invoiceLine['plan_name'] . ' plan subscription, ' . $this->getFormattedDateString($invoiceLine['period_start']) . ' to ' . $this->getFormattedDateString($invoiceLine['period_end']) . ' (' . $this->getCurrencySymbol($currency) . '';
            $contentLine .= $this->getFormattedAmount($invoiceLine['amount']);

            if (isset($invoiceLine['proration']) && $invoiceLine['proration']) {
                $contentLine .= ', prorated';
            }

            $contentLine .= ')';

            $contentLines[] = $contentLine;
        }

        return implode("\n", $contentLines);
    }


    private function getInvoiceDiscountContent($discount, $currency) {
        return ' * ' . $discount['percent_off'] . '% off with coupon ' . $discount['coupon'] . ' (-' . $this->getCurrencySymbol($currency) . '' . (number_format($discount['discount'] / 100, 2)) . ')';
    }


    private function getInvoiceTotalLine($total, $currency) {
        return "   =====================\n".' * Total: ' . $this->getCurrencySymbol($currency) . '' . number_format($total / 100, 2);
    }


    /**
     * @param string $currency
     * @return string
     */
    private function getCurrencySymbol($currency) {
        if (!isset($this->currencySymbolMap[$currency])) {
            return self::DEFAULT_CURRENCY_SYMBOL;
        }

        return $this->currencySymbolMap[$currency];
    }

}