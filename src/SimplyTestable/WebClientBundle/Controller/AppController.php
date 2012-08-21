<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends BaseViewController
{
    private $progressStates = array(
        'new',
        'in-progress'
    );
    
    private $completedStates = array(
        'cancelled',
        'complete'
    );
    
    public function indexAction()
    {   
        return $this->render('SimplyTestableWebClientBundle:App:index.html.twig', array(            
            'test_input_action_url' => $this->generateUrl('test_start'),
            'test_start_error' => $this->hasFlash('test_start_error'),
            'test_state' => 'start'
        ));
    }
    
    
    public function progressAction($website, $test_id) {        
        if (!$this->getCoreApplicationService()->hasTestStatus($website, $test_id)) {
            return $this->redirect($this->generateUrl('app', array(), true));
        }
        
        $testStatusJsonObject = $this->getCoreApplicationService()->getTestStatus($website, $test_id)->getContentObject();        
        if (in_array($testStatusJsonObject->state, $this->completedStates)) {
            return $this->redirect($this->generateUrl(
                'app_results',
                array(
                    'website' => $website,
                    'test_id' => $test_id
                ),
                true
            ));
        }        
        
        return $this->render('SimplyTestableWebClientBundle:App:progress.html.twig', array(
            'test_input_action_url' => $this->generateUrl('test_cancel', array(
                'website' => $website,
                'test_id' => $test_id
            )),
            'test_state' => $testStatusJsonObject->state,
            'test_website' => $testStatusJsonObject->website
        ));
    }
    
    
    public function resultsAction($website, $test_id) {                
        if (!$this->getCoreApplicationService()->hasTestStatus($website, $test_id)) {
            return $this->redirect($this->generateUrl('app', array(), true));
        }     
        
        $testStatusJsonObject = $this->getCoreApplicationService()->getTestStatus($website, $test_id)->getContentObject();
        if (in_array($testStatusJsonObject->state, $this->progressStates)) {
            return $this->redirect($this->generateUrl(
                'app_progress',
                array(
                    'website' => $website,
                    'test_id' => $test_id
                ),
                true
            ));
        }         
        
        return $this->render('SimplyTestableWebClientBundle:App:results.html.twig', array(
            'test_id' => $test_id,
//            'test_input_action_url' => $this->generateUrl('test_start', array(
//                'website' => $website,
//                'test_id' => $test_id
//            )),
//            'test_state' => $testStatusJsonObject->state,
            'test_website' => $testStatusJsonObject->website
        ));
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
}
