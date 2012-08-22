<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends BaseViewController
{
    private $progressStates = array(
        'new',
        'preparing',
        'queued',        
        'in-progress'        
    );
    
    private $completedStates = array(
        'cancelled',
        'completed'
    );
    
    private $testStateLabelMap = array(
        'new' => 'New, waiting to be noticed',
        'queued' => 'Queued, ready to start',
        'preparing' => 'Running now',
        'in-progress' => 'Running now'        
    );
    
    public function indexAction()
    {
        return $this->render('SimplyTestableWebClientBundle:App:index.html.twig', array(            
            'test_input_action_url' => $this->generateUrl('test_start'),
            'test_start_error' => $this->hasFlash('test_start_error')
        ));
    }
    
    
    public function progressAction($website, $test_id) {        
        if (!$this->getCoreApplicationService()->hasTestStatus($website, $test_id)) {
            return $this->redirect($this->generateUrl('app', array(), true));
        }
        
        $testStatusJsonObject = $this->getCoreApplicationService()->getTestStatus($website, $test_id)->getContentObject();        
        if (in_array($testStatusJsonObject->state, $this->completedStates)) {
            return $this->redirect($this->getResultsUrl($website, $test_id));
        }
        
        $this->getTestStatusService()->setTestData($testStatusJsonObject);
        
        $viewData = array(
            'this_url' => $this->getProgressUrl($website, $test_id),
            'test_input_action_url' => $this->generateUrl('test_cancel', array(
                'website' => $website,
                'test_id' => $test_id
            )),
            'test' => $testStatusJsonObject,
            'completion_percent' => $this->getTestStatusService()->getCompletionPercent(),
            'url_total' => $testStatusJsonObject->url_total,
            'urls' => $this->getTestUrls($website, $test_id),
            'task_total' => $this->getTestStatusService()->getTaskTotal(),
            'task_state_total' => array(
                'queued' => $this->getTestStatusService()->getTaskTotalByState('queued'),
                'completed' => $this->getTestStatusService()->getTaskTotalByState('completed'),
                'in_progress' => $this->getTestStatusService()->getTaskTotalByState('in-progress'),
            ),
            'state_label' => $this->testStateLabelMap[$testStatusJsonObject->state]
        );
        
        $this->setTemplate('SimplyTestableWebClientBundle:App:progress.html.twig');
        return $this->sendResponse($viewData);
    }
    
    
    /**
     *
     * @param string $website
     * @param int $test_id
     * @return array 
     */
    private function getTestUrls($website, $test_id) {
        $urls = array();
        $urlListResponse = $this->getCoreApplicationService()->getTestUrls($website, $test_id);
        
        if (!$urlListResponse) {
            return $urls;
        }
        
        $urlListContentObject = $urlListResponse->getContentObject();
        foreach ($urlListContentObject as $urlObject) {
            $urls[] = $urlObject->url;
        }
        
        return $urls;           
    }
    
    
    public function resultsAction($website, $test_id) {                
        if (!$this->getCoreApplicationService()->hasTestStatus($website, $test_id)) {
            return $this->redirect($this->generateUrl('app', array(), true));
        }     
        
        $testStatusJsonObject = $this->getCoreApplicationService()->getTestStatus($website, $test_id)->getContentObject();
        if (in_array($testStatusJsonObject->state, $this->progressStates)) {
            return $this->redirect($this->getProgressUrl($website, $test_id));
        }  
        
        $this->setTemplate('SimplyTestableWebClientBundle:App:results.html.twig');
        $viewData = array(
            'this_url' => $this->getResultsUrl($website, $test_id),
            'test_input_action_url' => $this->generateUrl('test_start'),            
            'test' => $testStatusJsonObject
        );
        
        return $this->sendResponse($viewData);
    }
    
    /**
     * Get the progress page URL for a given site and test ID
     * 
     * @param string $website
     * @param string $test_id
     * @return string
     */
    private function getProgressUrl($website, $test_id) {
        return $this->generateUrl(
            'app_progress',
            array(
                'website' => $website,
                'test_id' => $test_id
            ),
            true
        );
    }
    
    
    /**
     * Get the results page URL for a given site and test ID
     * 
     * @param string $website
     * @param string $test_id
     * @return string
     */    
    private function getResultsUrl($website, $test_id) {
        return $this->generateUrl(
            'app_results',
            array(
                'website' => $website,
                'test_id' => $test_id
            ),
            true
        );
    }
    
    
    /**
     *
     * @param string $flashKey
     * @return boolean
     */
    private function hasFlash($flashKey) {
        $flashMessages = $this->get('session')->getFlashBag()->get($flashKey);        
        return is_array($flashMessages) && count($flashMessages) > 0;
    }
    
    
    /**
     *
     * @param type $flashKey
     * @param type $messageIndex
     * @return string|null 
     */
    private function getFlash($flashKey, $messageIndex = 0) {        
        $flashMessages = $this->get('session')->getFlashBag()->get($flashKey);         
        if (!isset($flashMessages[$messageIndex])) {
            return false;
        }
        
        return $flashMessages[$messageIndex];
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\CoreApplicationService 
     */
    private function getCoreApplicationService() {
        return $this->container->get('simplytestable.services.coreapplicationservice');
    } 
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestStatusService
     */
    private function getTestStatusService() {
        return $this->container->get('simplytestable.services.teststatusservice');
    }     
}
