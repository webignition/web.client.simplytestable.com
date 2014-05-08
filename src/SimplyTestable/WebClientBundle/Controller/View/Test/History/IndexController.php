<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\History;

use SimplyTestable\WebClientBundle\Controller\View\CacheableViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;

class IndexController extends CacheableViewController implements IEFiltered, RequiresValidUser {

    const RESULTS_PREPARATION_THRESHOLD = 10;
    const DEFAULT_PAGE_NUMBER = 1;
    const TEST_LIST_LIMIT = 10;

    /**
     * @var \SimplyTestable\WebClientBundle\Model\TestList
     */
    private $testList = null;


    protected function modifyViewName($viewName) {
        return str_replace(array(
            //':User',
            'history.html',
            'all.html'
        ), array(
            //':bs3/User',
            'index.html',
            'index.html'
        ), $viewName);
    }


    public function indexAction() {
        if ($this->isPageNumberBelowRange()) {
            return $this->redirect($this->generateUrl('view_test_history_index_index'));
        }

        if ($this->isPageNumberAboveRange()) {
            $redirectParameters = array(
                'page_number' => $this->getTestList()->getPageCount(),
            );

            if (!is_null($this->get('request')->get('filter'))) {
                $redirectParameters['filter'] = $this->get('request')->get('filter');
            }

            return $this->redirect($this->generateUrl('app_history', $redirectParameters));
        }

        return $this->renderCacheableResponse($this->getViewData());
    }

    private function getViewData() {
        return array(
            'test_list' => $this->getTestList(),
            'pagination_page_numbers' => $this->getTestList()->getPageNumbers(),
            'filter' => $this->get('request')->get('filter'),
            'websites_source' => $this->generateUrl('app_history_websites', array(), true)
        );
    }


    /**
     * @return int
     */
    private function getRequestPageNumber() {
        return (int)$this->getRequest()->attributes->get('page_number');
    }


    public function getCacheValidatorParameters() {
        return array(
            'test_list_hash' => $this->getTestList()->getHash(),
            'filter' => $this->get('request')->get('filter'),
            'page_number' => $this->getRequestPageNumber()
        );
    }
    
    /**
     *

     * @return boolean
     */
    private function isPageNumberBelowRange() {
        return $this->getRequestPageNumber() < self::DEFAULT_PAGE_NUMBER;
    }


    /**
     * @return bool
     */
    private function isPageNumberAboveRange() {
        return $this->getRequestPageNumber() > $this->getTestList()->getPageCount() && $this->getTestList()->getPageCount() > 0;
    }


    /**
     * @return \SimplyTestable\WebClientBundle\Model\TestList
     */
    private function getTestList() {
        if (is_null($this->testList)) {
            $this->testList = $this->getFinishedTests(self::TEST_LIST_LIMIT, ($this->getRequestPageNumber() - 1) * self::TEST_LIST_LIMIT, $this->get('request')->get('filter'));
        }

        return $this->testList;
    }


    /**
     * @param $limit
     * @param $offset
     * @param null $filter
     * @return \SimplyTestable\WebClientBundle\Model\TestList
     */
    private function getFinishedTests($limit, $offset, $filter = null) {
        $testList = $this->getTestService()->getRemoteTestService()->getFinished($limit, $offset, $filter);
        
        foreach ($testList->get() as $testObject) {
            /* @var $remoteTest RemoteTest */
            $remoteTest = $testObject['remote_test'];
            
            $this->getTestService()->getRemoteTestService()->set($remoteTest);
            $test = $this->getTestService()->get($remoteTest->getWebsite(), $remoteTest->getId(), $remoteTest);
            
            $testList->addTest($test);
            
            if ($testList->requiresResults($test) && $remoteTest->isSingleUrl()) {
                $this->getTaskService()->getCollection($test);
            }
        }
        
        return $testList;
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TaskService 
     */
    private function getTaskService() {
        return $this->container->get('simplytestable.services.taskservice');
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestService
     */
    private function getTestService() {
        return $this->container->get('simplytestable.services.testservice');
    }

}
