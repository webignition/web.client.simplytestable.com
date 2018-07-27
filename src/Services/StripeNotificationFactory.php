<?php

namespace App\Services;

use App\Event\Stripe\Event as StripeEvent;
use App\Exception\Mail\Configuration\Exception as MailConfigurationException;
use App\Model\StripeNotification;
use App\Services\Configuration\MailConfiguration;

class StripeNotificationFactory
{
    const VIEW_BASE_PATH = 'Email/Stripe/Event/';

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var MailConfiguration
     */
    private $mailConfiguration;

    public function __construct(\Twig_Environment $twig, MailConfiguration $mailConfiguration)
    {
        $this->twig = $twig;
        $this->mailConfiguration = $mailConfiguration;
    }

    public function create(
        StripeEvent $event,
        array $subjectValueParameters = [],
        array $subjectKeyParameterNames = [],
        array $viewNameParameters = [],
        array $viewParameters = []
    ) {
        $subject = $this->createSubject($event, $subjectValueParameters, $subjectKeyParameterNames);
        $viewName = $this->deriveViewPath($event, $viewNameParameters);

        return new StripeNotification(
            $event->getUser(),
            $subject,
            $this->twig->render($viewName, $viewParameters)
        );
    }

    /**
     * @param StripeEvent $event
     * @param array $valueParameters
     * @param array $keyParameterNames
     *
     * @return string
     *
     * @throws MailConfigurationException
     */
    private function createSubject(StripeEvent $event, array $valueParameters, array $keyParameterNames)
    {
        $eventData = $event->getData();

        $key = 'stripe.' . $event->getName();

        if (!empty($keyParameterNames)) {
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
    private function deriveViewPath(StripeEvent $event, array $parameterKeys)
    {
        $eventData = $event->getData();

        if (!empty($parameterKeys)) {
            $parameterisedParts = [];
            foreach ($parameterKeys as $key) {
                if ($eventData->has($key)) {
                    $parameterisedParts[] = $key . '=' . $eventData->get($key);
                }
            }

            $viewNameBody = implode('-', $parameterisedParts);
        } else {
            $viewNameBody = 'notification';
        }

        return self::VIEW_BASE_PATH . $eventData->get('event') . '/' . $viewNameBody . '.txt.twig';
    }
}
