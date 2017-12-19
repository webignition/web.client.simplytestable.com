<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\NewsSubscriptions\UpdateAction;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Base\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {

    abstract protected function getExpectedFlashValues();

    public function getExpectedResponseStatusCode() {
        return 302;
    }

    protected function getExpectedLocationHeaderValue() {
        return 'http://localhost/account/#news-subscriptions';
    }

    protected function getActionMethodArguments() {
        return [];
    }

    public function getRequestPostData() {
        return [
            'announcements' => (int)$this->getRequestIsAnnouncementsSelected(),
            'updates' => (int)$this->getRequestIsUpdatesSelected()
        ];
    }

    protected function getRequestIsAnnouncementsSelected() {
        return (bool)($this->getAnnouncementsUpdatesValuesString()[0]);
    }

    protected function getRequestIsUpdatesSelected() {
        return (bool)($this->getAnnouncementsUpdatesValuesString()[1]);
    }

    /**
     * @return string
     */
    private function getAnnouncementsUpdatesValuesString() {
        return preg_replace('/\D/', '', $this->getTestClassName());
    }


    /**
     * @return string
     */
    private function getTestClassName() {
        $classNameParts = explode('\\', get_class($this));
        return $classNameParts[count($classNameParts) - 1];
    }

    public function testRedirectPath() {
        $this->assertEquals($this->getExpectedLocationHeaderValue(), $this->response->headers->get('location'));
    }

    public function testFlashValues() {
        $this->assertEquals($this->getExpectedFlashValues(), $this->container->get('session')->getFlashBag()->all());
    }

    

}


 