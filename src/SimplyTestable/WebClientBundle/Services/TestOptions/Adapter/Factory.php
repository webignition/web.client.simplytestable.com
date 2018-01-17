<?php
namespace SimplyTestable\WebClientBundle\Services\TestOptions\Adapter;

use SimplyTestable\WebClientBundle\Services\TaskTypeService;
use SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request\Adapter;

class Factory
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * @var Adapter
     */
    private $adapter;

    /**
     * @var TaskTypeService
     */
    private $taskTypeService;

    /**
     * @param Adapter $adapter
     * @param TaskTypeService $taskTypeService
     * @param array $parameters
     */
    public function __construct(Adapter $adapter, TaskTypeService $taskTypeService, array $parameters)
    {
        $this->adapter = $adapter;
        $this->taskTypeService = $taskTypeService;
        $this->parameters = $parameters;
    }

    /**
     * @return Adapter
     */
    public function create()
    {
        $adapter = $this->adapter;

        $adapter->setNamesAndDefaultValues($this->parameters['names_and_default_values']);
        $adapter->setAvailableTaskTypes($this->taskTypeService->getAvailable());
        $adapter->setInvertOptionKeys($this->parameters['invert_option_keys']);

        if (isset($testOptionsParameters['features'])) {
            $adapter->setAvailableFeatures($this->parameters['features']);
        }

        return $adapter;
    }
}
