<?php

namespace App\Controller\View\Partials;

use App\Controller\AbstractBaseViewController;
use App\Entity\Test as TestEntity;
use App\Model\Test as TestModel;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\TaskService;
use App\Services\TestListDecorator;
use App\Services\TestListRetriever;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class RecentTestsController extends AbstractBaseViewController
{
    const LIMIT = 3;

    private $taskService;
    private $testListRetriever;
    private $testListDecorator;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        TaskService $taskService,
        TestListRetriever $testListRetriever,
        TestListDecorator $testListDecorator
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->taskService = $taskService;
        $this->testListRetriever = $testListRetriever;
        $this->testListDecorator = $testListDecorator;
    }

    /**
     * @return Response
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function indexAction()
    {
        $testList = $this->testListRetriever->getRecent(self::LIMIT);

        foreach ($testList as $test) {
            if ($test instanceof TestModel) {
                if ($test->requiresRemoteTasks() && TestEntity::TYPE_SINGLE_URL === $test->getType()) {
                    $this->taskService->getCollection($test->getEntity(), $test->getTaskIds());
                }
            }
        }

        return $this->render('Partials/Dashboard/recent-tests.html.twig', [
            'test_list' => $this->testListDecorator->decorate($testList),
        ]);
    }
}
