<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Task\Results;

use SimplyTestable\WebClientBundle\Controller\View\Test\CacheableViewController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;

class IndexController extends CacheableViewController implements IEFiltered, RequiresValidUser, RequiresValidOwner {

    protected function modifyViewName($viewName) {
        return str_replace(array(
            ':Test'
        ), array(
            ':bs3/Test'
        ), $viewName);
    }


    public function getCacheValidatorParameters() {
        $test = $this->getTestService()->get(
            $this->getRequest()->attributes->get('website'),
            $this->getRequest()->attributes->get('test_id')
        );

        return array(
            'website' => $this->getRequest()->attributes->get('website'),
            'test_id' => $this->getRequest()->attributes->get('test_id'),
            'task_id' => $this->getRequest()->attributes->get('task_id'),
            'is_public_user_test' => $test->getUser() == $this->getUserService()->getPublicUser()->getUsername(),
        );
    }
    
    
    public function indexAction($website, $test_id, $task_id) {
        $isOwner = $this->getTestService()->getRemoteTestService()->owns();

        $test = $this->getTestService()->get($website, $test_id);
        $task = $this->getTaskService()->get($test, $task_id);

        if (is_null($task)) {
            return $this->redirect($this->generateUrl('app_test_redirector', array(
                'website' => $website,
                'test_id' => $test_id
            )));
        }

        if (!$this->hasErrorsOrWarnings($task) || $this->getTaskService()->isIncomplete($task)) {
            return $this->redirect($this->generateUrl('app_test_redirector', array(
                'website' => $website,
                'test_id' => $test_id
            )));
        }

        $viewData = array(
            'website_url' => $this->getUrlViewValues($test->getWebsite()),
            'test' => $test,
            'task' => $task,
            'task_url' => $this->getUrlViewValues($task->getUrl()),
            'is_owner' => $isOwner,
            'is_public_user_test' => $test->getUser() == $this->getUserService()->getPublicUser()->getUsername(),
        );

        if ($task->getType() == 'HTML validation') {
            $documentationUrls = $this->getHtmlValidationErrorDocumentationUrls($task);
            $fixes = $this->getErrorFixes($task, $documentationUrls);

            $viewData['documentation_urls'] = $documentationUrls;
            $viewData['fixes'] = $fixes;
            $viewData['distinct_fixes'] = $this->getDistinctFixes($fixes);
        }

        if ($task->getType() == 'CSS validation') {
            $viewData['errors_by_ref'] = $this->getCssValidationIssuesGroupedByRef($task, $task->getOutput()->getResult()->getErrors());
            $viewData['warnings_by_ref'] = $this->getCssValidationIssuesGroupedByRef($task, $task->getOutput()->getResult()->getWarnings());
        }

        if ($task->getType() == 'JS static analysis') {
            $viewData['errors_by_js_context'] = $this->getJsStaticAnalysisErrorsGroupedByContext($task);
        }

        if ($task->getType() == 'Link integrity') {
            $viewData['errors_by_link_state'] = $this->getLinkIntegrityErrorsGroupedByLinkState($task);
            $viewData['link_class_labels'] = array(
                'curl' => 'Network-Level (curl)',
                'http' => 'HTTP'
            );
            $viewData['link_state_descriptions'] = array(
                'curl' => array(
                    3 => array(
                        'The URL was not properly formatted',
                        'These URLs (or ones these redirect to) are not formed correctly.'
                    ),
                    6 => array(
                        'Couldn\'t resolve host',
                        'Are the domain names in the given links still valid and working?'
                    ),
                    7 => array(
                        'Failed to connect() to host or proxy',
                        'This could be temporary issue.'
                    ),
                    35 => array(
                        'A problem occurred somewhere in the SSL/TLS handshake'
                    ),
                    52 => array(
                        'Nothing was returned from the server',
                        'Under the circumstances, getting nothing is considered an error.'
                    ),
                    56 => array(
                        'Failure with receiving network data.',
                        'Whatever lives at the given domains isn\'t talking back.'
                    ),
                    60 => array(
                        'Peer certificate cannot be authenticated with known CA certificates',
                        'There is a problem with the SSL certificates these domains are using.'
                    )
                ),
                'http' => array(
                    302 => array(
                        'Too many redirects',
                    ),
                    403 => array(
                        "Access denied",
                        "Are these a password-protected pages?"
                    ),
                    404 => array(
                        "Not found",
                        "These resources appear to no longer exist at the given URLs."
                    ),
                    410 => array(
                        "Gone",
                        "These resources are no longer at the given URLs."
                    ),
                    500 => array(
                        "Internal server error",
                        "The application serving the given content failed."
                    ),
                    503 => array(
                        "Service Unavailable",
                        "The application serving the content is not available right now."
                    )
                )
            );
        }

        return $this->renderCacheableResponse($viewData);
    }


    /**
     * @param Task $task
     * @return bool
     */
    private function hasErrorsOrWarnings(Task $task) {
        return $task->getOutput()->getErrorCount() > 0 || $task->getOutput()->getWarningCount() > 0;
    }


    private function getHtmlValidationErrorDocumentationUrls(Task $task) {
        $documentationUrls = array();

        if ($task->getOutput()->getErrorCount() === 0) {
            return $documentationUrls;
        }

        $documentationSiteProperties = $this->container->getParameter('documentation_site');

        $baseUrl = $documentationSiteProperties['urls']['home']
            . $documentationSiteProperties['urls']['errors']
            . $documentationSiteProperties['urls']['html-validation'];

        $errors = $task->getOutput()->getResult()->getErrors();

        $normaliser = new \webignition\HtmlValidationErrorNormaliser\HtmlValidationErrorNormaliser();
        $linkifier = new \webignition\HtmlValidationErrorLinkifier\HtmlValidationErrorLinkifier();

        $this->getDocumentationUrlCheckerService()->setDocumentationSitemapPath($this->container->get('kernel')->locateResource('@SimplyTestableWebClientBundle/Resources/config/documentation_sitemap.xml'));
        foreach ($errors as $error) {
            $normalisationResult = $normaliser->normalise($error->getMessage());

            if ($normalisationResult->isNormalised()) {
                $parameterisedUrl = $baseUrl . $linkifier->linkify($normalisationResult->getNormalisedError()->getNormalForm()) . '/' . $linkifier->linkify($normalisationResult->getNormalisedError()->getNormalForm(), $normalisationResult->getNormalisedError()->getParameters()) . '/';

                if ($this->getDocumentationUrlCheckerService()->exists($parameterisedUrl)) {
                    $documentationUrls[] = array(
                        'url' => $parameterisedUrl,
                        'exists' => true
                    );
                } else {
                    $url = $baseUrl . $linkifier->linkify($normalisationResult->getNormalisedError()->getNormalForm()) . '/';
                    $documentationUrls[] = array(
                        'url' => $url,
                        'exists' => $this->getDocumentationUrlCheckerService()->exists($url)
                    );
                }
            } else {
                $url = $baseUrl . $linkifier->linkify($normalisationResult->getRawError()) . '/';
                $documentationUrls[] = array(
                    'url' => $url,
                    'exists' => $this->getDocumentationUrlCheckerService()->exists($url)
                );
            }
        }

        return $documentationUrls;
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\DocumentationUrlCheckerService
     */
    private function getDocumentationUrlCheckerService() {
        return $this->container->get('simplytestable.services.documentationurlcheckerservice');
    }



    private function getErrorFixes(Task $task, $documentationUrls) {
        $fixes = array();

        if ($task->getOutput()->getErrorCount() === 0) {
            return $fixes;
        }

        if ($task->getType() != 'HTML validation') {
            return $fixes;
        }

        $errors = $task->getOutput()->getResult()->getErrors();

        foreach ($errors as $errorIndex => $error) {
            if (isset($documentationUrls[$errorIndex]) && $documentationUrls[$errorIndex]['exists'] === true) {
                $fixes[] = array(
                    'error' => ucfirst($error),
                    'documentation_url' => $documentationUrls[$errorIndex]['url']
                );
            }
        }

        return $fixes;
    }



    private function getDistinctFixes($fixes) {
        $distinctUrls = array();
        $distinctFixes = array();

        foreach ($fixes as $fix) {
            if (!in_array($fix['documentation_url'], $distinctUrls)) {
                $distinctUrls[] = $fix['documentation_url'];
                $distinctFixes[] = $fix;
            }
        }

        return $distinctFixes;
    }



    private function getCssValidationIssuesGroupedByRef(Task $task, $issues) {
        if ($task->getType() != 'CSS validation') {
            return array();
        }

        $groupedByRef = array();

        foreach ($issues as $issue) {
            /* @var $error \SimplyTestable\WebClientBundle\Model\TaskOutput\CssTextFileMessage */
            if (!isset($groupedByRef[$issue->getRef()])) {
                $groupedByRef[$issue->getRef()] = array();
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


    private function getJsStaticAnalysisErrorsGroupedByContext(Task $task) {
        if ($task->getType() != 'JS static analysis') {
            return array();
        }

        $errorsGroupedByContext = array();
        $errors = $task->getOutput()->getResult()->getErrors();

        foreach ($errors as $error) {
            $context = rawurldecode($error->getContext());

            /* @var $error \SimplyTestable\WebClientBundle\Model\TaskOutput\JsTextFileMessage */
            if (!isset($errorsGroupedByContext[$context])) {
                $errorsGroupedByContext[$context] = array();
            }

            $errorsGroupedByContext[$context][] = $error;
        }

        return $errorsGroupedByContext;
    }

    private function getLinkIntegrityErrorsGroupedByLinkState(Task $task) {
        if ($task->getType() != 'Link integrity') {
            return array();
        }

        $errorsGroupedByLinkState = array();
        $errors = $task->getOutput()->getResult()->getErrors();

        foreach ($errors as $error) {
            /* @var $error \SimplyTestable\WebClientBundle\Model\TaskOutput\LinkIntegrityMessage */
            if (!isset($errorsGroupedByLinkState[$error->getClass()])) {
                $errorsGroupedByLinkState[$error->getClass()] = array();
            }

            if (!isset($errorsGroupedByLinkState[$error->getClass()][$error->getState()])) {
                $errorsGroupedByLinkState[$error->getClass()][$error->getState()] = array();
            }

            $errorsGroupedByLinkState[$error->getClass()][$error->getState()][] = $error;
        }

        return $errorsGroupedByLinkState;
    }
}