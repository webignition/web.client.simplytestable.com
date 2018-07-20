<?php

namespace AppBundle\Controller;

use Postmark\Models\PostmarkException;
use Postmark\PostmarkClient;
use Symfony\Bundle\TwigBundle\Controller\ExceptionController as BaseExceptionController;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Twig\Environment as TwigEnvironment;

class ExceptionController extends BaseExceptionController
{
    const DEVELOPER_EMAIL_TEMPLATE = 'Email/exception.txt.twig';

    /**
     * @var PostmarkClient
     */
    private $postmarkClient;

    /**
     * @param TwigEnvironment $twig
     * @param $debug
     * @param PostmarkClient $postmarkClient
     */
    public function __construct(
        TwigEnvironment $twig,
        $debug,
        PostmarkClient $postmarkClient
    ) {
        parent::__construct($twig, $debug);

        $this->postmarkClient = $postmarkClient;
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
            $template = sprintf('Exception/%s%s.%s.twig', $name, $code, $format);
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
     * @throws PostmarkException
     */
    private function sendDeveloperEmail(Request $request, FlattenException $exception)
    {
        $this->postmarkClient->sendEmail(
            'robot@simplytestable.com',
            'jon@simplytestable.com',
            $this->getDeveloperEmailSubject($exception),
            null,
            $this->twig->render(self::DEVELOPER_EMAIL_TEMPLATE, [
                'status_code' => $exception->getStatusCode(),
                'status_text' => '"status text"',
                'exception' => $exception,
                'request' => (string)$request,
            ])
        );
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
