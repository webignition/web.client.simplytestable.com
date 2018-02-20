<?php
namespace SimplyTestable\WebClientBundle\Services\TestOptions;

use SimplyTestable\WebClientBundle\Services\TaskTypeService;

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
     * @param array $parameters
     */
    public function __construct(RequestAdapter $requestAdapter, TaskTypeService $taskTypeService, array $parameters)
    {
        $this->requestAdapter = $requestAdapter;
        $this->taskTypeService = $taskTypeService;
        $this->parameters = $parameters;
    }

    /**
     * @return RequestAdapter
     */
    public function create()
    {
        $adapter = $this->requestAdapter;

        $adapter->setNamesAndDefaultValues($this->parameters['names_and_default_values']);
        $adapter->setAvailableTaskTypes($this->taskTypeService->getAvailable());
        $adapter->setInvertOptionKeys($this->parameters['invert_option_keys']);

        if (isset($this->parameters['features'])) {
            $adapter->setAvailableFeatures($this->parameters['features']);
        }

        return $adapter;
    }
}