<?php

namespace App\Services;

use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\TestList;

class TestListRetriever
{
    private $coreApplicationHttpClient;
    private $jsonResponseHandler;
    private $testService;
    private $testFactory;

    public function __construct(
        CoreApplicationHttpClient $coreApplicationHttpClient,
        JsonResponseHandler $jsonResponseHandler,
        TestService $testService,
        TestFactory $testFactory
    ) {
        $this->coreApplicationHttpClient = $coreApplicationHttpClient;
        $this->jsonResponseHandler = $jsonResponseHandler;
        $this->testService = $testService;
        $this->testFactory = $testFactory;
    }

    /**
     * @param int $limit
     *
     * @return TestList
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function getRecent(int $limit = 3): TestList
    {
        return $this->createList([
            'limit' => $limit,
            'offset' => 0,
            'exclude-states' => [
                'new',
                'preparing',
                'resolving',
                'resolved',
                'rejected',
                'queued',
            ],
        ]);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @param string $filter
     *
     * @return TestList
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function getFinished(int $limit, int $offset, ?string $filter = null): TestList
    {
        return $this->createList([
            'limit' => $limit,
            'offset' => $offset,
            'exclude-states' => ['rejected'],
            'exclude-current' => 1,
            'url-filter' => $filter,
        ]);
    }

    /**
     * @param array $routeParameters
     *
     * @return TestList
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    private function createList(array $routeParameters): TestList
    {
        $response = $this->coreApplicationHttpClient->get('tests_list', $routeParameters);
        $data = $this->jsonResponseHandler->handle($response);

        $tests = [];

        foreach ($data['jobs'] as $testData) {
            $testId = (int) $testData['id'];
            $entity = $this->testService->getEntity($testId);

            $tests[] = $this->testFactory->create($entity, $testData);
        }

        return new TestList($tests, $data['max_results'], $data['offset'], $data['limit']);
    }
}
