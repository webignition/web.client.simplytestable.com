<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class TestHistoryController extends TestViewController
{ 
    const RESULTS_PREPARATION_THRESHOLD = 10;
    const DEFAULT_PAGE_NUMBER = 1;
    
    public function indexAction($page_number) {        
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }
        
        $this->getUserService()->setUser($this->getUser());
        
        if (!$this->isUserValid()) {            
            return $this->redirect($this->generateUrl('sign_out_submit', array(), true));
        }
        
        if ($this->isPageNumberBelowRange($page_number)) {
            return $this->redirect($this->generateUrl('app_history_all'));
        }
        
        $testList = $this->getFinishedTests(10, ($page_number - 1) * 10, $this->get('request')->get('filter'));        
        if ($page_number > $testList->getPageCount() && $testList->getPageCount() > 0) {            
            $redirectParameters = array(
                'page_number' => $testList->getPageCount(),                
            );
            
            if (!is_null($this->get('request')->get('filter'))) {
                $redirectParameters['filter'] = $this->get('request')->get('filter');
            }
            
            return $this->redirect($this->generateUrl('app_history', $redirectParameters));            
        }
        
        $templateName = 'SimplyTestableWebClientBundle:TestHistory:index.html.twig';
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier(array(
            'template_last_modified_date' => $this->getTemplateLastModifiedDate($templateName)->format('Y-m-d H:i:s'),
            'test_list_hash' => $testList->getHash(),
            'test_list' => md5(json_encode($testList))
        ));
        
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);        
        $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);            
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        } 
        
        $cacheValidatorHeaders->setLastModifiedDate(new \DateTime());
        $this->getCacheValidatorHeadersService()->store($cacheValidatorHeaders);;        
        
        return $this->getCachableResponse($this->render($templateName, array(            
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),         
            'test_list' => $testList,
            'pagination_page_numbers' => $testList->getPageNumbers(),
            'filter' => $this->get('request')->get('filter')
        )), $cacheValidatorHeaders);                
    }
    
    
    /**
     * 
     * @param mixed $pageNumber
     * @return boolean
     */
    private function isPageNumberBelowRange($pageNumber) {
        return (int)$pageNumber < self::DEFAULT_PAGE_NUMBER;
    }
    
    
    
    /**
     * 
     * @return array
     */
    private function getFinishedTests($limit, $offset, $filter = null) {
        $testList = $this->getTestService()->getRemoteTestService()->getFinished($limit, $offset, $filter);
        
        foreach ($testList->get() as $testObject) {
            /* @var $remoteTest RemoteTest */
            $remoteTest = $testObject['remote_test'];
            
            $this->getTestService()->getRemoteTestService()->set($remoteTest);
            $test = $this->getTestService()->get($remoteTest->getWebsite(), $remoteTest->getId(), $remoteTest);
            
            $testList->addTest($test);
            
            if ($testList->requiresResults($test)) {
                if ($remoteTest->isSingleUrl()) {
                    $this->getTaskService()->getCollection($test);
                } else {
                    
//                    
//                    if (($remoteTest->getTaskCount() - self::RESULTS_PREPARATION_THRESHOLD) - $test->getTaskCount()) {
//                        $this->getTaskService()->getCollection($test);
//                    }                   
                }
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

}
