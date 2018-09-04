<?php
namespace App\Services\TestOptions;

use App\Services\TaskTypeService;
use App\Services\Configuration\TestOptionsConfiguration;

class RequestAdapterFactory
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * @var RequestAdapter
     */
    private $requestAdapter;

    /**
     * @var TaskTypeService
     */
    private $taskTypeService;

    /**
     * @param RequestAdapter $requestAdapter
     * @param TaskTypeService $taskTypeService
     * @param TestOptionsConfiguration $testOptionsConfiguration
     */
    public function __construct(
        RequestAdapter $requestAdapter,
        TaskTypeService $taskTypeService,
        TestOptionsConfiguration $testOptionsConfiguration
    ) {
        $this->requestAdapter = $requestAdapter;
        $this->taskTypeService = $taskTypeService;
        $this->parameters = $testOptionsConfiguration->getConfiguration();
    }

    /**
     * @return RequestAdapter
     */
    public function create()
    {
        $adapter = $this->requestAdapter;

        $adapter->setNamesAndDefaultValues($this->parameters['names_and_default_values']);
        $adapter->setAvailableTaskTypes($this->taskTypeService->getAvailable());

        if (isset($this->parameters['features'])) {
            $adapter->setAvailableFeatures($this->parameters['features']);
        }

        return $adapter;
    }
}
