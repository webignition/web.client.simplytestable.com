<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Task\Results;

use SimplyTestable\WebClientBundle\Controller\View\Test\AbstractRequiresValidOwnerController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Model\TaskOutput\CssTextFileMessage;
use SimplyTestable\WebClientBundle\Model\TaskOutput\JsTextFileMessage;
use SimplyTestable\WebClientBundle\Model\TaskOutput\LinkIntegrityMessage;
use SimplyTestable\WebClientBundle\Services\CacheValidatorService;
use SimplyTestable\WebClientBundle\Services\DocumentationUrlCheckerService;
use SimplyTestable\WebClientBundle\Services\RemoteTestService;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\TaskService;
use SimplyTestable\WebClientBundle\Services\TestService;
use SimplyTestable\WebClientBundle\Services\UrlViewValuesService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use webignition\HtmlValidationErrorLinkifier\HtmlValidationErrorLinkifier;
use webignition\HtmlValidationErrorNormaliser\HtmlValidationErrorNormaliser;
use webignition\HtmlValidationErrorNormaliser\Result as HtmlValidationErrorNormalisationResult;

class IndexController extends AbstractRequiresValidOwnerController implements IEFiltered, RequiresValidUser
{
    const DOCUMENTATION_SITEMAP_RESOURCE_PATH =
        '@SimplyTestableWebClientBundle/Resources/config/documentation_sitemap.xml';

    /**
     * @param Request $request
     * @param string $website
     * @param int $test_id
     * @param int $task_id
     *
     * @return Response
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function indexAction(Request $request, $website, $test_id, $task_id)
    {
        $router = $this->container->get('router');
        $testService = $this->container->get(TestService::class);
        $remoteTestService = $this->container->get(RemoteTestService::class);
        $urlViewValuesService = $this->container->get(UrlViewValuesService::class);
        $taskService = $this->container->get(TaskService::class);
        $cacheValidatorService = $this->container->get(CacheValidatorService::class);
        $templating = $this->container->get('templating');
        $userManager = $this->container->get(UserManager::class);
        $documentationUrlLinkChecker = $this->container->get(DocumentationUrlCheckerService::class);
        $kernel = $this->container->get('kernel');

        $user = $userManager->getUser();
        $test = $testService->get($website, $test_id);
        $isOwner = $remoteTestService->owns($user);

        $task = $taskService->get($test, $task_id);

        if (empty($task)) {
            return new RedirectResponse($router->generate(
                'app_test_redirector',
                [
                    'website' => $website,
                    'test_id' => $test_id
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $taskOutput = $task->getOutput();
        $taskHasErrorsOrWarnings = $taskOutput->getErrorCount() > 0 || $taskOutput->getWarningCount() > 0;

        if (!$taskHasErrorsOrWarnings || $taskService->isIncomplete($task)) {
            return new RedirectResponse($router->generate(
                'app_test_redirector',
                [
                    'website' => $website,
                    'test_id' => $test_id
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $isPublicUserTest = $test->getUser() === SystemUserService::getPublicUser()->getUsername();

        $response = $cacheValidatorService->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
            'task_id' => $task_id,
            'is_public_user_test' => $isPublicUserTest,
        ]);

        if ($cacheValidatorService->isNotModified($response)) {
            return $response;
        }

        $viewData = array(
            'website_url' => $urlViewValuesService->create($website),
            'test' => $test,
            'task' => $task,
            'task_url' => $urlViewValuesService->create($task->getUrl()),
            'is_owner' => $isOwner,
            'is_public_user_test' => $isPublicUserTest,
        );

        if (Task::TYPE_HTML_VALIDATION === $task->getType()) {
            $documentationUrls = $this->getHtmlValidationErrorDocumentationUrls(
                $documentationUrlLinkChecker,
                $kernel,
                $task
            );
            $fixes = $this->getHtmlValidationErrorFixes($task, $documentationUrls);

            $viewData['documentation_urls'] = $documentationUrls;
            $viewData['fixes'] = $fixes;
            $viewData['distinct_fixes'] = $this->getDistinctHtmlValidationErrorFixes($fixes);
        }

        if (Task::TYPE_CSS_VALIDATION === $task->getType()) {
            $taskOutputResult = $taskOutput->getResult();

            /* @var CssTextFileMessage[] $errors */
            $errors = $taskOutputResult->getErrors();

            /* @var CssTextFileMessage[] $warnings */
            $warnings = $taskOutputResult->getWarnings();

            $viewData['errors_by_ref'] = $this->getCssValidationIssuesGroupedByRef($errors);
            $viewData['warnings_by_ref'] = $this->getCssValidationIssuesGroupedByRef($warnings);
        }

        if (Task::TYPE_JS_STATIC_ANALYSIS === $task->getType()) {
            $viewData['errors_by_js_context'] = $this->getJsStaticAnalysisErrorsGroupedByContext($task);
        }

        if (Task::TYPE_LINK_INTEGRITY === $task->getType()) {
            $viewData['errors_by_link_state'] = $this->getLinkIntegrityErrorsGroupedByLinkState($task);
            $viewData['link_class_labels'] = [
                'curl' => 'Network-Level (curl)',
                'http' => 'HTTP'
            ];
            $viewData['link_state_descriptions'] = $this->container->getParameter('link_integrity_error_code_map');
        }

        $content = $templating->render(
            'SimplyTestableWebClientBundle:bs3/Test/Task/Results/Index:index.html.twig',
            array_merge($this->getDefaultViewParameters(), $viewData)
        );

        $response->setContent($content);
        $response->headers->set('content-type', 'text/html');

        return $response;
    }

    /**
     * @param DocumentationUrlCheckerService $documentationUrlChecker
     * @param KernelInterface $kernel
     * @param Task $task
     *
     * @return array
     */
    private function getHtmlValidationErrorDocumentationUrls(
        DocumentationUrlCheckerService $documentationUrlChecker,
        KernelInterface $kernel,
        Task $task
    ) {
        $documentationUrls = [];

        if (0 === $task->getOutput()->getErrorCount()) {
            return $documentationUrls;
        }

        $documentationSiteProperties = $this->container->getParameter('documentation_site');

        $baseUrl = $documentationSiteProperties['urls']['home']
            . $documentationSiteProperties['urls']['errors']
            . $documentationSiteProperties['urls']['html-validation'];

        $errors = $task->getOutput()->getResult()->getErrors();

        $normaliser = new HtmlValidationErrorNormaliser();
        $linkifier = new HtmlValidationErrorLinkifier();

        $sitemapPath = $kernel->locateResource(self::DOCUMENTATION_SITEMAP_RESOURCE_PATH);

        $documentationUrlChecker->setDocumentationSitemapPath($sitemapPath);
        foreach ($errors as $error) {
            /* @var HtmlValidationErrorNormalisationResult $normalisationResult */
            $normalisationResult = $normaliser->normalise($error->getMessage());

            if ($normalisationResult->isNormalised()) {
                $normalisedError = $normalisationResult->getNormalisedError();
                $normalForm = $normalisedError->getNormalForm();

                $parameterisedUrl = sprintf(
                    '%s%s/%s/',
                    $baseUrl,
                    $linkifier->linkify($normalForm),
                    $linkifier->linkify($normalForm, $normalisedError->getParameters())
                );

                if ($documentationUrlChecker->exists($parameterisedUrl)) {
                    $documentationUrls[] = [
                        'url' => $parameterisedUrl,
                        'exists' => true
                    ];
                } else {
                    $url = $baseUrl . $linkifier->linkify($normalForm) . '/';
                    $documentationUrls[] = [
                        'url' => $url,
                        'exists' => $documentationUrlChecker->exists($url)
                    ];
                }
            } else {
                $url = $baseUrl . $linkifier->linkify($normalisationResult->getRawError()) . '/';
                $documentationUrls[] = [
                    'url' => $url,
                    'exists' => $documentationUrlChecker->exists($url)
                ];
            }
        }

        return $documentationUrls;
    }

    /**
     * @param Task $task
     * @param array $documentationUrls
     *
     * @return array
     */
    private function getHtmlValidationErrorFixes(Task $task, $documentationUrls)
    {
        $taskOutput = $task->getOutput();

        $fixes = [];

        if (0 === $taskOutput->getErrorCount()) {
            return $fixes;
        }

        $errors = $taskOutput->getResult()->getErrors();

        foreach ($errors as $errorIndex => $error) {
            if (isset($documentationUrls[$errorIndex]) && $documentationUrls[$errorIndex]['exists'] === true) {
                $fixes[] = [
                    'error' => ucfirst($error),
                    'documentation_url' => $documentationUrls[$errorIndex]['url']
                ];
            }
        }

        return $fixes;
    }


    /**
     * @param array $fixes
     *
     * @return array
     */
    private function getDistinctHtmlValidationErrorFixes($fixes)
    {
        $distinctUrls = [];
        $distinctFixes = [];

        foreach ($fixes as $fix) {
            if (!in_array($fix['documentation_url'], $distinctUrls)) {
                $distinctUrls[] = $fix['documentation_url'];
                $distinctFixes[] = $fix;
            }
        }

        return $distinctFixes;
    }

    /**
     * @param CssTextFileMessage[] $issues
     *
     * @return array
     */
    private function getCssValidationIssuesGroupedByRef($issues)
    {
        $groupedByRef = [];

        foreach ($issues as $issue) {
            if (!isset($groupedByRef[$issue->getRef()])) {
                $groupedByRef[$issue->getRef()] = [];
            }

            $groupedByRef[$issue->getRef()][] = $issue;
        }

        if (isset($groupedByRef[''])) {
            $inlineErrors = $groupedByRef[''];
            unset($groupedByRef['']);

            $groupedByRef[''] = $inlineErrors;
        }

        return $groupedByRef;
    }

    /**
     * @param Task $task
     *
     * @return array
     */
    private function getJsStaticAnalysisErrorsGroupedByContext(Task $task)
    {
        $errorsGroupedByContext = [];

        /* @var JsTextFileMessage[] $errors */
        $errors = $task->getOutput()->getResult()->getErrors();

        foreach ($errors as $error) {
            $context = rawurldecode($error->getContext());

            if (!isset($errorsGroupedByContext[$context])) {
                $errorsGroupedByContext[$context] = [];
            }

            $errorsGroupedByContext[$context][] = $error;
        }

        return $errorsGroupedByContext;
    }

    /**
     * @param Task $task
     *
     * @return array
     */
    private function getLinkIntegrityErrorsGroupedByLinkState(Task $task)
    {
        $errorsGroupedByLinkState = [];

        /* @var LinkIntegrityMessage[] $errors */
        $errors = $task->getOutput()->getResult()->getErrors();

        foreach ($errors as $error) {
            $class = $error->getClass();
            $state = $error->getState();

            if (!isset($errorsGroupedByLinkState[$class])) {
                $errorsGroupedByLinkState[$class] = [];
            }

            if (!isset($errorsGroupedByLinkState[$class][$state])) {
                $errorsGroupedByLinkState[$class][$state] = [];
            }

            $errorsGroupedByLinkState[$class][$state][] = $error;
        }

        return $errorsGroupedByLinkState;
    }
}
