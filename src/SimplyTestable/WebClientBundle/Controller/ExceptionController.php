<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Bundle\TwigBundle\Controller\ExceptionController as BaseExceptionController;
use SimplyTestable\WebClientBundle\Services\PostmarkSender;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use Twig\Environment as TwigEnvironment;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;

class ExceptionController extends BaseExceptionController
{
    const DEVELOPER_EMAIL_TEMPLATE = 'SimplyTestableWebClientBundle:Email:exception.txt.twig';

    /**
     * @var PostmarkSender
     */
    private $postmarkSender;

    /**
     * @var PostmarkMessage
     */
    private $postmarkMessage;

    /**
     * @param TwigEnvironment $twig
     * @param $debug
     * @param PostmarkSender $postmarkSender
     * @param PostmarkMessage $postmarkMessage
     */
    public function __construct(
        TwigEnvironment $twig,
        $debug,
        PostmarkSender $postmarkSender,
        PostmarkMessage $postmarkMessage
    ) {
        parent::__construct($twig, $debug);

        $this->postmarkSender = $postmarkSender;
        $this->postmarkMessage = $postmarkMessage;
    }

    /**
     * {@inheritdoc}
     */
    public function showAction(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null)
    {
        if (!$this->debug) {
            $this->sendDeveloperEmail($request, $exception);
        }

        return parent::showAction($request, $exception, $logger);
    }

    /**
     * {@inheritdoc}
     */
    protected function findTemplate(Request $request, $format, $code, $showException)
    {
        $name = $showException ? 'exception' : 'error';
        if ($showException && 'html' == $format) {
            $name = 'exception_full';
        }

        // For error pages, try to find a template for the specific HTTP status code and format
        if (!$showException) {
            $template = sprintf('SimplyTestableWebClientBundle:Exception:%s%s.%s.twig', $name, $code, $format);
            if ($this->templateExists($template)) {
                return $template;
            }
        }

        return parent::findTemplate($request, $format, $code, $showException);
    }

    /**
     * @param Request $request
     * @param FlattenException $exception
     *
     * @throws PostmarkResponseException
     */
    private function sendDeveloperEmail(Request $request, FlattenException $exception)
    {
        $this->postmarkMessage->addTo('jon@simplytestable.com');
        $this->postmarkMessage->setSubject($this->getDeveloperEmailSubject($exception));
        $this->postmarkMessage->setTextMessage($this->twig->render(self::DEVELOPER_EMAIL_TEMPLATE, [
            'status_code' => $exception->getStatusCode(),
            'status_text' => '"status text"',
            'exception' => $exception,
            'request' => (string)$request,
        ]));

        $this->postmarkSender->send($this->postmarkMessage);
    }

    /**
     * @param FlattenException $exception
     *
     * @return string
     */
    private function getDeveloperEmailSubject(FlattenException $exception)
    {
        $statusCode = $exception->getStatusCode();

        $subject = sprintf(
            'Exception [%s,%s]',
            $exception->getCode(),
            $statusCode
        );

        if (in_array($statusCode, [404, 500])) {
            $subject .= ' [' . $exception->getMessage() . ']';
        }

        return $subject;
    }
}
