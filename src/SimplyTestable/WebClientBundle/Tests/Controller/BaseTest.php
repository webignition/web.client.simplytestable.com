<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

abstract class BaseTest extends BaseSimplyTestableTestCase {
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    }    
    
    public function setUp() {
        parent::setUp();
        $this->removeAllTests();
        $this->container->enterScope('request');        
    }
    
    abstract protected function getControllerName();     
    abstract protected function getActionName();    
    
    protected function getCurrentController($postData = null, $queryData = null) {
        $postData = (is_array($postData)) ? $postData : array();
        $queryData = (is_array($queryData)) ? $queryData : array();
        
        return $this->getController(
            $this->getControllerName(),
            $this->getActionName(),
            $postData,
            $queryData
        );
    }    

}
