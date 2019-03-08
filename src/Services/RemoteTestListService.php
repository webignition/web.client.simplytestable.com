<?php

namespace App\Services;

use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\RemoteTest\RemoteTest;
use App\Model\RemoteTestList;

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
     * @return RemoteTestList
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function getRecent(int $limit = 3): RemoteTestList
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
     * @return RemoteTestList
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function getFinished(int $limit, int $offset, ?string $filter = null): RemoteTestList
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
     * @return RemoteTestList
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    private function createList(array $routeParameters): RemoteTestList
    {
        $response = $this->coreApplicationHttpClient->get('tests_list', $routeParameters);
        $data = $this->jsonResponseHandler->handle($response);

        $remoteTests = [];
        foreach ($data['jobs'] as $remoteTestData) {
            $remoteTests[] = new RemoteTest($remoteTestData);
        }

        return new RemoteTestList($remoteTests, $data['max_results'], $data['offset'], $data['limit']);
    }
}
