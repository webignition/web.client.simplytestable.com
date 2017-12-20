<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\TaskType;

use SimplyTestable\WebClientBundle\Tests\Functional\BaseTestCase;

abstract class ServiceTest extends BaseTestCase {

    /**
     * @var \SimplyTestable\WebClientBundle\Services\TaskTypeService
     */
    private $taskTypeService = null;


//    /**
//     * @var array
//     */
//    private $taskTypes = array();
//
//
//    /**
//     * @var array
//     */
//    private $availableTaskTypes = array();


//    public function setUp() {
//        parent::setUp();
//        //$this->taskTypes = $this->getTaskTypeService()->get();
//        //$this->availableTaskTypes = $this->getTaskTypeService()->getAvailable();
//    }


//    /**
//     * @return array
//     */
//    protected function getTaskTypes() {
//        return $this->taskTypes;
//    }


//    /**
//     * @return array
//     */
//    protected function getAvailableTaskTypes() {
//        return $this->availableTaskTypes;
//    }


    /**
     * @return \SimplyTestable\WebClientBundle\Model\User
     */
    protected function getUser() {
        return $this->getUserService()->getPublicUser();
    }


    /**
     * @return \SimplyTestable\WebClientBundle\Services\TaskTypeService
     */
    protected function getTaskTypeService() {
        if (is_null($this->taskTypeService)) {
            $this->taskTypeService = $this->container->get('simplytestable.services.tasktypeservice');
            $this->taskTypeService->setUser($this->getUser());

            if (!$this->getUser()->equals($this->getUserService()->getPublicUser())) {
                $this->taskTypeService->setUserIsAuthenticated();
            }
        }

        return $this->taskTypeService;
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\UserService
     */
    private function getUserService() {
        return $this->container->get('simplytestable.services.userservice');
    }

}
