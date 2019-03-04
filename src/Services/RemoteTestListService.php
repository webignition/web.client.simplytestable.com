<?php

namespace App\Services;

use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\RemoteTest\RemoteTest;
use App\Model\TestList;

class RemoteTestListService
{
    private $coreApplicationHttpClient;
    private $jsonResponseHandler;

    public function __construct(
        CoreApplicationHttpClient $coreApplicationHttpClient,
        JsonResponseHandler $jsonResponseHandler
    ) {
        $this->coreApplicationHttpClient = $coreApplicationHttpClient;
        $this->jsonResponseHandler = $jsonResponseHandler;
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
        $list = new TestList();

        $response = $this->coreApplicationHttpClient->get('tests_list', $routeParameters);
        $responseData = $this->jsonResponseHandler->handle($response);

        $list->setMaxResults($responseData['max_results']);
        $list->setLimit($responseData['limit']);
        $list->setOffset($responseData['offset']);

        foreach ($responseData['jobs'] as $remoteTestData) {
            $list->addRemoteTest(new RemoteTest($remoteTestData));
        }

        return $list;
    }
}
