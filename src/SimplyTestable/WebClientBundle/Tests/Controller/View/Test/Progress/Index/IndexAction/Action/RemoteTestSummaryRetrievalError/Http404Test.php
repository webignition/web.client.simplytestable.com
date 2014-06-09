<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Progress\Index\IndexAction\Action\RemoteTestSummaryRetrievalError;

class Http404Test extends ActionTest {

    protected function preCall() {
        $this->setExpectedControllerExceptionClass('SimplyTestable\WebClientBundle\Exception\WebResourceException');
        $this->setExpectedControllerExceptionCode(404);
        $this->setExpectedControllerExceptionMessage('Not Found');
    }

    protected function getHttpFixtureItems() {
        return array(
            'HTTP/1.0 404'
        );
    }

}