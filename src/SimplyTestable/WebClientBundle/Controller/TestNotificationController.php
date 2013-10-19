<?php

namespace SimplyTestable\WebClientBundle\Controller;

class TestNotificationController extends TestViewController
{
    public function urlLimitAction($test_id, $website) {
        $this->getTestService()->setUser($this->getUser());
        
        $testRetrievalOutcome = $this->getTestRetrievalOutcome($website, $test_id);
        if ($testRetrievalOutcome->hasResponse()) {
            return $this->sendNotFoundResponse();
        }        
        
        $this->setTemplate('SimplyTestableWebClientBundle:Partials:test-url-limit-notification.html.twig');
        return $this->sendResponse(array(
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'remote_test_summary' => $this->getTestService()->getRemoteTestSummary()
        ));        
    }
}
