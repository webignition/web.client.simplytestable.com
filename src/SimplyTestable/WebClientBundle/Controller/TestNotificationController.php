<?php

namespace SimplyTestable\WebClientBundle\Controller;

class TestNotificationController extends TestViewController
{
    public function urlLimitAction($test_id, $website) {
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());
        
        $testRetrievalOutcome = $this->getTestRetrievalOutcome($website, $test_id);
        if ($testRetrievalOutcome->hasResponse()) {
            return $this->sendNotFoundResponse();
        }  
        
        $test = $testRetrievalOutcome->getTest();
        
        $this->setTemplate('SimplyTestableWebClientBundle:Partials:test-url-limit-notification.html.twig');
        return $this->sendResponse(array(
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'remote_test' => $this->getTestService()->getRemoteTestService()->get(),
            'is_public_user_test' => $test->getUser() == $this->getUserService()->getPublicUser()->getUsername()
        ));        
    }
}
