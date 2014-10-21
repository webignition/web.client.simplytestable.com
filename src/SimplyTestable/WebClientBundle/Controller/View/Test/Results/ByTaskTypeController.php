<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results;

//use SimplyTestable\WebClientBundle\Controller\View\Test\CacheableViewController;
//use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
//use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
//use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ByTaskTypeController extends ResultsController {

    const FILTER_BY_PAGE = 'by-page';
    const FILTER_BY_ERROR = 'by-error';
    const DEFAULT_FILTER = self::FILTER_BY_ERROR;

    private $allowedFilters = [
        self::FILTER_BY_PAGE,
        self::FILTER_BY_ERROR
    ];


    protected function modifyViewName($viewName) {
        return str_replace(array(
            ':Test'
        ), array(
            ':bs3/Test'
        ), $viewName);
    }


    public function getRequestWebsiteMismatchResponse() {
        return new RedirectResponse($this->generateUrl('view_test_results_bytasktype_index', array(
            'website' => $this->getRequest()->attributes->get('website'),
            'test_id' => $this->getRequest()->attributes->get('test_id'),
            'task_type' => $this->getRequest()->attributes->get('task_type'),
            'filter' => $this->hasValidFilter() ? $this->getFilter() : self::DEFAULT_FILTER
        ), true));
    }


    public function indexAction($website, $test_id, $task_type, $filter) {
        $task_type = str_replace('+', ' ', $task_type);

        if (!$this->isTaskTypeSelected($task_type)) {
            return $this->redirect($this->generateUrl('view_test_results_index_index', array(
                'website' => $website,
                'test_id' => $test_id
            ), true));
        }

        if (!$this->hasValidFilter()) {
            return $this->issueRedirect($this->generateUrl('view_test_results_bytasktype_index', array(
                'website' => $this->getTest()->getWebsite(),
                'test_id' => $test_id,
                'task_type' => $task_type,
                'filter' => self::DEFAULT_FILTER
            ), true));
        }

        if ($this->requiresPreparation()) {
            return $this->getPreparationRedirectResponse();
        }

        $this->getTaskService()->getCollection($this->getTest());
        $tasks = $this->getTaskService()->getCollection($this->getTest(), $this->getRemoteTaskIds());

        $viewData = [
            'is_owner' => $this->getTestService()->getRemoteTestService()->owns($this->getTest()),
            'is_public_user_test' => $this->getTest()->getUser() == $this->getUserService()->getPublicUser()->getUsername(),
            'website' => $this->getUrlViewValues($website),
            'test' => $this->getTest(),
            'task_type' => $this->getSelectedTaskType($task_type),
            'filter' => $this->getFilter(),
            'tasks' => $tasks
        ];

        $errorUrlMap = [];
        $errorCount = [];
        $errorHashMap = [];
        $taskErrorCount = [];

        foreach ($tasks as $task) {
            if (!$task->getOutput()->hasResult()) {
                $this->getTaskService()->setParsedOutput($task);
            }

            if (!isset($taskErrorCount[$task->getUrl()])) {
                $taskErrorCount[$task->getUrl()] = [];
            }

            foreach ($task->getOutput()->getResult()->getErrors() as $error) {
                if (!isset($errorUrlMap[$error->getMessage()])) {
                    $errorUrlMap[$error->getMessage()] = [];
                }

                if (!isset($errorHashMap[$error->getMessage()])) {
                    $errorHashMap[$error->getMessage()] = md5($error->getMessage());
                }

                if (!isset($taskErrorCount[$task->getUrl()][$error->getMessage()])) {
                    $taskErrorCount[$task->getUrl()][$error->getMessage()] = 0;
                }

                $taskErrorCount[$task->getUrl()][$error->getMessage()]++;

                if (!in_array($task->getUrl(), $errorUrlMap[$error->getMessage()])) {
                    $errorUrlMap[$error->getMessage()][] = $task->getUrl();
                }

                if (!isset($errorCount[$error->getMessage()])) {
                    $errorCount[$error->getMessage()] = 0;
                }

                $errorCount[$error->getMessage()]++;
            }
        }

        $viewData['error_url_map'] = $errorUrlMap;
        $viewData['error_count'] = $errorCount;
        $viewData['error_hash_map'] = $errorHashMap;
        $viewData['task_error_count'] = $taskErrorCount;

        return $this->renderCacheableResponse($viewData);
    }

    public function getCacheValidatorParameters() {
        return [
            'rand' => rand()
        ];
    }


    private function getSchemelessUrl($url) {
        if (preg_match('/^https:\/\//', $url)) {
            $url = preg_replace('/^https:\/\//', '', $url);
        }

        if (preg_match('/^http:\/\//', $url)) {
            $url = preg_replace('/^http:\/\//', '', $url);
        }

        return $url;
    }



    /**
     * @param string $taskType
     * @return bool
     */
    private function isTaskTypeSelected($taskType) {
        return !is_null($this->getSelectedTaskType($taskType));
    }


    /**
     * @param string $taskType
     * @return null
     */
    private function getSelectedTaskType($taskType) {
        $remoteTaskTypes = $this->getRemoteTest()->getTaskTypes();

        foreach ($remoteTaskTypes as $remoteTaskType) {
            if (strtolower($remoteTaskType) == strtolower($taskType)) {
                return $remoteTaskType;
            }
        }

        return null;
    }


    /**
     * @return string
     */
    private function getFilter() {
        return trim($this->getRequest()->attributes->get('filter'));
    }


    /**
     * @return bool
     */
    private function hasValidFilter() {
        return in_array($this->getFilter(), $this->allowedFilters);
    }


    /**
     * @return int[]|null
     */
    private function getRemoteTaskIds() {
        return $this->getFilteredTaskCollectionRemoteIds(
            'with-errors',
            str_replace('+', ' ', strtolower($this->getRequest()->attributes->get('task_type')))
        );
    }




}
