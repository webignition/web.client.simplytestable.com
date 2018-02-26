<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\Test\Task\Results;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Controller\AbstractController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class ByUrlController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param RouterInterface $router
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(RouterInterface $router, EntityManagerInterface $entityManager)
    {
        parent::__construct($router);

        $this->entityManager = $entityManager;
        $this->router = $router;
    }

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
        $testRepository = $this->entityManager->getRepository(Test::class);
        $taskRepository = $this->entityManager->getRepository(Task::class);

        $test = $testRepository->findOneBy([
            'testId' => $test_id
        ]);

        if (empty($test)) {
            return new RedirectResponse($this->router->generate(
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
            return new RedirectResponse($this->router->generate(
                'app_test_redirector',
                [
                    'website' => $website,
                    'test_id' => $test_id
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        return new RedirectResponse($this->router->generate(
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
