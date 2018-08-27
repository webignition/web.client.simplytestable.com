<?php

namespace App\Controller\View\Task;

use App\Controller\AbstractBaseViewController;
use App\Entity\Task\Task;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\TaskOutput\CssTextFileMessage;
use App\Model\TaskOutput\JsTextFileMessage;
use App\Model\TaskOutput\LinkIntegrityMessage;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\Configuration\DocumentationSiteUrls;
use App\Services\DocumentationUrlCheckerService;
use App\Services\Configuration\LinkIntegrityErrorCodeMap;
use App\Services\RemoteTestService;
use App\Services\SystemUserService;
use App\Services\TaskService;
use App\Services\TestService;
use App\Services\UrlViewValuesService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;
use webignition\HtmlValidationErrorLinkifier\HtmlValidationErrorLinkifier;
use webignition\HtmlValidationErrorNormaliser\HtmlValidationErrorNormaliser;
use webignition\HtmlValidationErrorNormaliser\Result as HtmlValidationErrorNormalisationResult;

class ResultsController extends AbstractBaseViewController
{
    /**
     * @var TestService
     */
    private $testService;

    /**
     * @var RemoteTestService
     */
    private $remoteTestService;

    /**
     * @var TaskService
     */
    private $taskService;

    /**
     * @var DocumentationUrlCheckerService
     */
    private $documentationUrlLinkChecker;

    /**
     * @var LinkIntegrityErrorCodeMap
     */
    private $linkIntegrityErrorCodeMap;

    /**
     * @var DocumentationSiteUrls
     */
    private $documentationSiteUrls;

    /**
     * @var UrlViewValuesService
     */
    private $urlViewValues;

    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        UrlViewValuesService $urlViewValues,
        UserManager $userManager,
        TestService $testService,
        RemoteTestService $remoteTestService,
        TaskService $taskService,
        DocumentationUrlCheckerService $documentationUrlChecker,
        LinkIntegrityErrorCodeMap $linkIntegrityErrorCodeMap,
        DocumentationSiteUrls $documentationSiteUrls
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->testService = $testService;
        $this->remoteTestService = $remoteTestService;
        $this->taskService = $taskService;
        $this->documentationUrlLinkChecker = $documentationUrlChecker;
        $this->linkIntegrityErrorCodeMap = $linkIntegrityErrorCodeMap;
        $this->documentationSiteUrls = $documentationSiteUrls;
        $this->urlViewValues = $urlViewValues;
        $this->userManager = $userManager;
    }

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
        $user = $this->userManager->getUser();
        $test = $this->testService->get($website, $test_id);
        $isOwner = $this->remoteTestService->owns($user);

        $task = $this->taskService->get($test, $task_id);

        if (empty($task)) {
            return new RedirectResponse($this->generateUrl(
                'redirect_website_test',
                [
                    'website' => $website,
                    'test_id' => $test_id
                ]
            ));
        }

        $taskOutput = $task->getOutput();
        $taskHasErrorsOrWarnings = $taskOutput->getErrorCount() > 0 || $taskOutput->getWarningCount() > 0;

        if (!$taskHasErrorsOrWarnings || $this->taskService->isIncomplete($task)) {
            return new RedirectResponse($this->generateUrl(
                'redirect_website_test',
                [
                    'website' => $website,
                    'test_id' => $test_id
                ]
            ));
        }

        $isPublicUserTest = $test->getUser() === SystemUserService::getPublicUser()->getUsername();

        $response = $this->cacheableResponseFactory->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
            'task_id' => $task_id,
            'is_public_user_test' => $isPublicUserTest,
        ]);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
            return $response;
        }

        $viewData = array(
            'website_url' => $this->urlViewValues->create($website),
            'test' => $test,
            'task' => $task,
            'task_url' => $this->urlViewValues->create($task->getUrl()),
            'is_owner' => $isOwner,
            'is_public_user_test' => $isPublicUserTest,
        );

        if (Task::TYPE_HTML_VALIDATION === $task->getType()) {
            $documentationUrls = $this->getHtmlValidationErrorDocumentationUrls($task);
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
            $viewData['link_state_descriptions'] = $this->linkIntegrityErrorCodeMap->getErrorCodeMap();
        }

        return $this->renderWithDefaultViewParameters('task-results.html.twig', $viewData, $response);
    }

    /**
     * @param Task $task
     *
     * @return array
     */
    private function getHtmlValidationErrorDocumentationUrls(Task $task)
    {
        $documentationUrls = [];

        if (0 === $task->getOutput()->getErrorCount()) {
            return $documentationUrls;
        }

        $documentationSiteUrls = $this->documentationSiteUrls->getUrls();

        $baseUrl = sprintf(
            '%s%s%s',
            $documentationSiteUrls['home'],
            $documentationSiteUrls['errors'],
            $documentationSiteUrls['html-validation']
        );

        $errors = $task->getOutput()->getResult()->getErrors();

        $normaliser = new HtmlValidationErrorNormaliser();
        $linkifier = new HtmlValidationErrorLinkifier();

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

                if ($this->documentationUrlLinkChecker->exists($parameterisedUrl)) {
                    $documentationUrls[] = [
                        'url' => $parameterisedUrl,
                        'exists' => true
                    ];
                } else {
                    $url = $baseUrl . $linkifier->linkify($normalForm) . '/';
                    $documentationUrls[] = [
                        'url' => $url,
                        'exists' => $this->documentationUrlLinkChecker->exists($url)
                    ];
                }
            } else {
                $url = $baseUrl . $linkifier->linkify($normalisationResult->getRawError()) . '/';
                $documentationUrls[] = [
                    'url' => $url,
                    'exists' => $this->documentationUrlLinkChecker->exists($url)
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
