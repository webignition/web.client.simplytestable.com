<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class TestResultsController extends TestViewController {

    public function finishedSummaryAction($website, $test_id) {
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());
        $testRetrievalOutcome = $this->getTestRetrievalOutcome($website, $test_id);
        if ($testRetrievalOutcome->hasResponse()) {
            return $testRetrievalOutcome->getResponse();
        }

        $test = $testRetrievalOutcome->getTest();

        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier();
        $cacheValidatorIdentifier->setParameter('website', $website);
        $cacheValidatorIdentifier->setParameter('test_id', $test_id);

        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);

        $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }

        $viewData = array(
            'test' => array(
                'test' => $test,
                'remote_test' => $this->getTestService()->getRemoteTestService()->get(),
            )
        );

        $this->setTemplate('SimplyTestableWebClientBundle:Partials:finished-job-summary.html.twig');
        return $this->getCachableResponse(
            $this->sendResponse($viewData),
            $cacheValidatorHeaders
        );
    }

}