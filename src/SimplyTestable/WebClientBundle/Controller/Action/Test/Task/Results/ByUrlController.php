<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\Test\Task\Results;

use SimplyTestable\WebClientBundle\Controller\BaseController;

class ByUrlController extends BaseController {

    public function indexAction($website, $test_id, $task_url, $task_type) {
        $test = $this->getTestService()->getEntityRepository()->findOneBy([
            'testId' => $test_id
        ]);

        $task = $this->getTaskService()->getEntityRepository()->findOneBy([
            'test' => $test,
            'url' => $task_url,
            'type' => $task_type
        ]);

        if (is_null($task)) {
            return $this->redirect($this->generateUrl('app_test_redirector', array(
                'website' => $website,
                'test_id' => $test_id
            )));
        }

        return $this->redirect($this->generateUrl('view_test_task_results_index_index', [
            'website' => $website,
            'test_id' => $test_id,
            'task_id' => $task->getTaskId()
        ]));
    }



    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestService
     */
    private function getTestService() {
        return $this->container->get('simplytestable.services.testservice');
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TaskService
     */
    protected function getTaskService() {
        return $this->container->get('simplytestable.services.taskservice');
    }
}