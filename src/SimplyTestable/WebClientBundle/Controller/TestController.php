<?php

namespace SimplyTestable\WebClientBundle\Controller;

use webignition\NormalisedUrl\NormalisedUrl;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends BaseController
{
    const DEFAULT_WEBSITE_SCHEME = 'http';
    
    public function startAction()
    {        
        if (!$this->hasWebsite()) {
            $this->get('session')->setFlash('test_start_error', 'non-blank string');
            return $this->redirect($this->generateUrl('app', array(), true));
        }
        
        $jsonResponseObject = $this->getTestService()->start($this->getWebsite())->getContentObject();        
        return $this->redirect($this->generateUrl(
            'app_progress',
            array(
                'website' => $jsonResponseObject->website,
                'test_id' => $jsonResponseObject->id
            ),
            true
        ));
    }
    
    
    public function cancelAction()
    {
        
        if (!$this->hasWebsite()) {
            $this->get('session')->setFlash('test_start_error', '');
            return $this->redirect($this->generateUrl('app', array(), true));
        }
        
        if ($this->getTestService()->cancel($this->getWebsite(), $this->getTestId())) {
            return $this->redirect($this->generateUrl(
                'app_results',
                array(
                    'website' => $this->getWebsite(),
                    'test_id' => $this->getTestId()
                ),
                true
            ));       
        }
    }
    
    
    public function urlCollectionAction($website, $test_id) {        
//        if (!$this->getTestService()->has($website, $test_id)) {
//            return $this->sendNotFoundResponse();
//        }    
//        
//        $test = $this->getTestService()->get($website, $test_id);
//        
//        //$offset = 
//        
//        $offset = $this->getRequestValue('offset', 0);
//        $limit = $this->getRequestValue('limit', 100);
//        
//        
//        
//        var_dump($offset, $limit);
//        
//        //$this->getR
//        
//        exit();
        
//        if (in_array($test->getState(), $this->testFinishedStates)) {
//            return $this->redirect($this->getResultsUrl($website, $test_id));
//        }
//        
//        $remoteTestSummary = $this->getTestService()->getRemoteTestSummary();
//        
//        $viewData = array(
//            'this_url' => $this->getProgressUrl($website, $test_id),
//            'test_input_action_url' => $this->generateUrl('test_cancel', array(
//                'website' => $website,
//                'test_id' => $test_id
//            )),
//            'test' => $test,
//            'remote_test_summary' => $this->getRemoteTestSummaryArray($remoteTestSummary),
//            'task_count_by_state' => $this->getTaskCountByState($remoteTestSummary),
//            'state_label' => $this->testStateLabelMap[$test->getState()].': ',
//            'state_icon' => $this->testStateIconMap[$test->getState()],
//            'completion_percent' => $this->getCompletionPercent($remoteTestSummary),
//            'public_site' => $this->container->getParameter('public_site')
//        );          
//        
//        $this->setTemplate('SimplyTestableWebClientBundle:App:progress.html.twig');
//        return $this->sendResponse($viewData);
    } 
    
    
    /**
     *
     * @return boolean
     */
    private function hasWebsite() {
        return $this->getWebsite() != '';
    }
    
    
    /**
     *
     * @return string
     */
    private function getWebsite() {
        $website = $this->getRequestValue('website');
        if (is_null($website)) {
            return $website;
        }
        
        $url = new NormalisedUrl($website);
        if (!$url->hasScheme()) {            
            $url->setScheme(self::DEFAULT_WEBSITE_SCHEME);
        }
        
        return (string)$url;
    }
    
    
    /**
     *
     * @return int 
     */
    private function getTestId() {
        return $this->getRequestValue('test_id', 0);
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestService
     */
    private function getTestService() {
        return $this->container->get('simplytestable.services.testservice');
    }
}