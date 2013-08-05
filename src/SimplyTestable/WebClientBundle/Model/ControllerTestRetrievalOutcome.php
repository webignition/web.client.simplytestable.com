<?php
namespace SimplyTestable\WebClientBundle\Model;

class ControllerTestRetrievalOutcome { 

    /**
     *
     * @var \Symfony\Component\HttpFoundation\Response
     */
    private $response;
    
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Entity\Test\Test
     */
    private $test;
    
    
    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return \SimplyTestable\WebClientBundle\Model\ControllerTestRetrievalOutcome
     */
    public function setResponse(\Symfony\Component\HttpFoundation\Response $response) {
        $this->response = $response;
        return $this;
    }
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Entity\Test\Test $test
     * @return \SimplyTestable\WebClientBundle\Model\ControllerTestRetrievalOutcome
     */
    public function setTest(\SimplyTestable\WebClientBundle\Entity\Test\Test $test) {
        $this->test = $test;
        return $this;
    }
    

    /**
     * 
     * @return boolean
     */    
    public function hasTest() {
        return !is_null($this->test);
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function hasResponse() {
        return !is_null($this->response);
    }
    
    
    /**
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse() {
        return $this->response;
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Entity\Test\Test
     */
    public function getTest() {
        return $this->test;
    }
    
}