<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Preparing\Index\IndexAction\Action;

class UnfinishedTestTest extends ActionTest {

    protected function getExpectedResponseStatusCode() {
        return 302;
    }

    public function testRedirectLocationIsProgressUrl() {
        $this->assertEquals(
            $this->getCurrentController()->generateUrl('app_progress', $this->getActionMethodArguments(), true),
            $this->response->headers->get('location')
        );
    }

}