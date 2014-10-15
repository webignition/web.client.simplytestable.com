<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results;

//use SimplyTestable\WebClientBundle\Controller\View\Test\CacheableViewController;
//use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
//use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
//use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
//use SimplyTestable\WebClientBundle\Entity\Test\Test;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ByTaskTypeController extends ResultsController {

    const DEFAULT_FILTER = 'by-page';

    private $allowedFilters = [
        'by-page',
        'by-error'
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
            'filter' => $this->hasValidRequestFilter() ? $this->getRequestFilter() : self::DEFAULT_FILTER
        ), true));
    }


    public function indexAction($website, $test_id, $task_type) {
        $task_type = str_replace('+', ' ', $task_type);

        if (!$this->isTaskTypeSelected($task_type)) {
            return $this->redirect($this->generateUrl('view_test_results_index_index', array(
                'website' => $website,
                'test_id' => $test_id
            ), true));
        }


        if (!$this->hasValidRequestFilter()) {
            return $this->issueRedirect($this->generateUrl('view_test_results_bytasktype_index', array(
                'website' => $this->getTest()->getWebsite(),
                'test_id' => $test_id,
                'task_type' => $task_type,
                'filter' => self::DEFAULT_FILTER
            ), true));
        }

        $isOwner = $this->getTestService()->getRemoteTestService()->owns($this->getTest());

        $viewData = [
            'is_owner' => $isOwner,
            'is_public_user_test' => $this->getTest()->getUser() == $this->getUserService()->getPublicUser()->getUsername(),
            'website' => $this->getUrlViewValues($website),
            'test' => $this->getTest(),
            'task_type' => $this->getSelectedTaskType($task_type)
        ];

        return $this->renderCacheableResponse($viewData);
    }

    public function getCacheValidatorParameters() {
        return [];
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
    private function getRequestFilter() {
        return trim($this->getRequest()->query->get('filter'));
    }


    /**
     * @return bool
     */
    private function hasValidRequestFilter() {
        return in_array($this->getRequestFilter(), $this->allowedFilters);
    }

}
