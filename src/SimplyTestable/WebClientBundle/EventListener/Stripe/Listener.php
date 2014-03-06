<?php

namespace SimplyTestable\WebClientBundle\EventListener\Stripe;

use Symfony\Component\HttpKernel\Log\LoggerInterface as Logger;

class Listener
{
    const VIEW_BASE_PATH = 'SimplyTestableWebClientBundle:Email/Stripe/Event/';
    
    
    /**
     *
     * @var Logger
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
     * @var \SimplyTestable\WebClientBundle\Event\Stripe\Event
     */
    private $event;
    
    
    /**
     *
     * @param Logger $logger
     */
    public function __construct(
            Logger $logger,
            \Symfony\Bundle\TwigBundle\TwigEngine $templating,
            \Symfony\Bundle\FrameworkBundle\Routing\Router $router,
            \SimplyTestable\WebClientBundle\Services\Mail\Service $mailService
    ) {        
        $this->logger = $logger;
        $this->templating = $templating;
        $this->router = $router;
        $this->mailService = $mailService;
    }
    
    private function getViewPath($parameterKeys = null) {        
        if (is_array($parameterKeys)) {
            $parameterisedParts = array();        
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
    
    private function getSubject($valueParameters = null, $keyParameterNames = null) {
        $key = 'stripe.' . $this->event->getName();
        
        if (is_array($keyParameterNames)) {
            $keyNameParameterisedParts = array();        
            foreach ($keyParameterNames as $name) {
                if ($this->event->getData()->has($name)) {
                    $keyNameParameterisedParts[] = $name . '=' . $this->event->getData()->get($name);
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
    
    
    public function onCustomerSubscriptionCreated(\SimplyTestable\WebClientBundle\Event\Stripe\Event $event) {              
        $this->event = $event;        
        
        $subject = $this->getSubject(array(
            'plan_name' => strtolower($event->getData()->get('plan_name'))
        ));
        
        $viewParameters = array(
            'plan_name' => strtolower($event->getData()->get('plan_name')),
            'trial_period_days' => $event->getData()->get('trial_period_days'),
            'trial_end' => $this->getFormattedDateString($event->getData()->get('trial_end')),            
            'amount' => $this->getFormattedAmount($event->getData()->get('amount')),            
            'account_url' => $this->router->generate('user_account_index', array(), true)
        );
        
        $viewPathParameters = array(
            'status'
        );
        
        if ($event->getData()->get('status') == 'trialing') {
            $viewPathParameters[] = 'has_card';
        }
        
        $this->issueNotification($subject, $this->templating->render($this->getViewPath($viewPathParameters), $viewParameters));
    }
    
    
    public function onCustomerSubscriptionTrialWillEnd(\SimplyTestable\WebClientBundle\Event\Stripe\Event $event) {        
        $this->event = $event;
        
        $subject = $this->getSubject(array(
            'plan_name' => strtolower($event->getData()->get('plan_name')),
            'payment_details_needed_suffix' => ($event->getData()->get('has_card')) ? '' : ', payment details needed'
        ));
        
        $viewParameters = array(
            'plan_name' => strtolower($event->getData()->get('plan_name')),           
            'plan_amount' => $this->getFormattedAmount($event->getData()->get('plan_amount')),            
            'account_url' => $this->router->generate('user_account_index', array(), true)
        );        
        
        $this->issueNotification($subject, $this->templating->render($this->getViewPath(array(
            'has_card'            
        )), $viewParameters));
    }      
    
    
    public function onCustomerSubscriptionUpdated(\SimplyTestable\WebClientBundle\Event\Stripe\Event $event) {              
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
                'trial_end' => $this->getFormattedDateString($event->getData()->get('trial_end'))
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
                'plan_amount' => strtolower($event->getData()->get('plan_amount')),
                'account_url' => $this->router->generate('user_account_index', array(), true)
            );
            
            $this->issueNotification($subject, $this->templating->render($this->getViewPath(array(
                'transition',
                'has_card'                
            )), $viewParameters));            
            return;
        }
    }
        
    
    public function onInvoicePaymentFailed(\SimplyTestable\WebClientBundle\Event\Stripe\Event $event) {                
        $this->event = $event;  
        
        $subject = $this->getSubject(array(
            'invoice_id' => $this->getFormattedInvoiceId($event->getData()->get('invoice_id'))
        ));
        
        $viewParameters = array(         
            'invoice_id' => $this->getFormattedInvoiceId($event->getData()->get('invoice_id')),
            'account_url' => $this->router->generate('user_account_index', array(), true),
            'invoice_lines' => $this->getInvoiceLinesContent($event->getData()->get('lines')),
        );
        
        $this->issueNotification($subject, $this->templating->render($this->getViewPath(), $viewParameters));
    }

    
    public function onInvoicePaymentSucceeded(\SimplyTestable\WebClientBundle\Event\Stripe\Event $event) {                
        $this->event = $event;
        
        $viewParameters = array(
            'plan_name' => strtolower($event->getData()->get('plan_name')),
            'account_url' => $this->router->generate('user_account_index', array(), true),
            'invoice_lines' => $this->getInvoiceLinesContent($event->getData()->get('lines')),
            'invoice_id' => $this->getFormattedInvoiceId($event->getData()->get('invoice_id'))
        );
        
        $this->issueNotification($this->getSubject(array(
            'invoice_id' => $this->getFormattedInvoiceId($event->getData()->get('invoice_id'))
        )), $this->templating->render($this->getViewPath(), $viewParameters));
    }  
    
    
    public function onCustomerSubscriptionDeleted(\SimplyTestable\WebClientBundle\Event\Stripe\Event $event) {                
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
    
    private function getInvoiceLinesContent($invoiceLines) {
        $contentLines = array();
        
        foreach ($invoiceLines as $invoiceLine) {
            $contentLine = ' * ' . $invoiceLine['plan_name'] . ' plan subscription, ' . $this->getFormattedDateString($invoiceLine['period_start']) . ' to ' . $this->getFormattedDateString($invoiceLine['period_end']) . ' (£';
            $contentLine .= $this->getFormattedAmount($invoiceLine['amount']);
            
            if (isset($invoiceLine['proration']) && $invoiceLine['proration']) {
                $contentLine .= ', prorated';
            }
            
            $contentLine .= ')';
            
            $contentLines[] = $contentLine;
        }
        
        return implode("\n", $contentLines);
    }    

}