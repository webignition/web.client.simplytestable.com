<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Services\PostmarkSender;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * ExceptionController.
 *
 */
class ExceptionController extends Controller
{
    /**
     * @param Request $request
     * @param FlattenException $exception
     * @param DebugLoggerInterface|null $logger
     *
     * @return Response
     *
     * @throws \SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception
     */
    public function showAction(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null)
    {
        if (!$this->container->get('kernel')->isDebug()) {
            $this->sendDeveloperEmail($request, $exception);
        }

        $currentContent = $this->getAndCleanOutputBuffering($request->headers->get('X-Php-Ob-Level', -1));

        $templating = $this->container->get('templating');
        $code = $exception->getStatusCode();

        $request->setRequestFormat('html');

        return $templating->renderResponse(
            $this->findTemplate($templating, $request->getRequestFormat(), $code, $this->container->get('kernel')->isDebug()),
            array(
                'status_code'    => $code,
                'status_text'    => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
                'exception'      => $exception,
                'logger'         => $logger,
                'currentContent' => $currentContent,
                'requestUri' => $request->getRequestUri(),
            )
        );
    }

    /**
     * @param int $startObLevel
     *
     * @return string
     */
    protected function getAndCleanOutputBuffering($startObLevel)
    {
        // ob_get_level() never returns 0 on some Windows configurations, so if
        // the level is the same two times in a row, the loop should be stopped.
        $previousObLevel = null;
        $currentContent = '';

        while (($obLevel = ob_get_level()) > $startObLevel && $obLevel !== $previousObLevel) {
            $previousObLevel = $obLevel;
            $currentContent .= ob_get_clean();
        }

        return $currentContent;
    }

    /**
     * @param EngineInterface $templating
     * @param string          $format
     * @param integer         $code       An HTTP response status code
     * @param Boolean         $debug
     *
     * @return TemplateReference
     */
    protected function findTemplate($templating, $format, $code, $debug)
    {
        $name = $debug ? 'exception' : 'error';
        if ($debug && 'html' == $format) {
            $name = 'exception_full';
        }

        // when not in debug, try to find a template for the specific HTTP status code and format
        if (!$debug) {
            $template = new TemplateReference('SimplyTestableWebClientBundle', 'Exception', $name.$code, $format, 'twig');
            if ($templating->exists($template)) {
                return $template;
            }
        }

        // try to find a template for the given format
        $template = new TemplateReference('TwigBundle', 'Exception', $name, $format, 'twig');

        if ($templating->exists($template)) {
            return $template;
        }

        return new TemplateReference('TwigBundle', 'Exception', $name, 'html', 'twig');
    }

    /**
     * @param Request $request
     * @param FlattenException $exception
     *
     * @throws \SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception
     */
    private function sendDeveloperEmail(Request $request, FlattenException $exception)
    {
        /* @var $message \MZ\PostmarkBundle\Postmark\Message */
        $message  = $this->get('postmark.message');
        $message->addTo('jon@simplytestable.com');
        $message->setSubject($this->getDeveloperEmailSubject($exception));
        $message->setTextMessage($this->renderView('SimplyTestableWebClientBundle:Email:exception.txt.twig', [
            'status_code' => $exception->getStatusCode(),
            'status_text' => '"status text"',
            'exception' => $exception,
            'request' => (string)$request,
        ]));

        $postmarkSenderService = $this->container->get(PostmarkSender::class);

        $postmarkSenderService->send($message);
    }

    /**
     * @param FlattenException $exception
     *
     * @return string
     */
    private function getDeveloperEmailSubject(FlattenException $exception)
    {
        $subject = 'Exception ['.$exception->getCode().','.$exception->getStatusCode().']';

        if ($exception->getStatusCode() == 404) {
            $subject .= ' [' . $exception->getMessage() . ']';
        }

        if ($exception->getStatusCode() == 500) {
            $subject .= ' [' . $exception->getMessage() . ']';
        }

        return $subject;
    }
}
