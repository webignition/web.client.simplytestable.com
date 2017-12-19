<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Preparing\Index\IndexAction\Action;

class UnfinishedTestTest extends ActionTest {

    protected function getExpectedResponseStatusCode() {
        return 302;
    }

    public function testRedirectLocationIsProgressUrl() {
        $this->assertEquals(
            $this->getCurrentController()->generateUrl('view_test_progress_index_index', $this->getActionMethodArguments(), true),
            $this->response->headers->get('location')
        );
    }

}