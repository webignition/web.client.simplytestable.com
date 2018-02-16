<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\Test\Task\Results;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Controller\BaseController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ByUrlController extends Controller
{
    /**
     * @param string $website
     * @param int $test_id
     * @param string $task_url
     * @param string $task_type
     *
     * @return RedirectResponse
     */
    public function indexAction($website, $test_id, $task_url, $task_type)
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $router = $this->container->get('router');

        $testRepository = $entityManager->getRepository(Test::class);
        $taskRepository = $entityManager->getRepository(Task::class);

        $test = $testRepository->findOneBy([
            'testId' => $test_id
        ]);

        if (empty($test)) {
            return new RedirectResponse($router->generate(
                'view_dashboard_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $task = $taskRepository->findOneBy([
            'test' => $test,
            'url' => $task_url,
            'type' => $task_type
        ]);

        if (empty($task)) {
            return new RedirectResponse($router->generate(
                'app_test_redirector',
                [
                    'website' => $website,
                    'test_id' => $test_id
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        return new RedirectResponse($router->generate(
            'view_test_task_results_index_index',
            [
                'website' => $website,
                'test_id' => $test_id,
                'task_id' => $task->getTaskId()
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }
}
