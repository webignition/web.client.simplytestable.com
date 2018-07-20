<?php

namespace AppBundle\Services\TestOptions;

use AppBundle\Model\TestOptions;
use Symfony\Component\HttpFoundation\ParameterBag;

class RequestAdapter
{
    /**
     * @var OptionsParser[]
     */
    private $featureOptionsParsers;

    /**
     * @var ParameterBag
     */
    private $requestData = [];

    /**
     * @var array
     */
    private $availableTaskTypes = [];

    /**
     * @var array
     */
    private $availableFeatures = [];

    /**
     * @var array
     */
    private $namesAndDefaultValues= [];

    /**
     * @var array
     */
    private $invertOptionKeys = [];

    /**
     * @var boolean
     */
    private $invertInvertableOptions = false;

    /**
     * @var TestOptions
     */
    private $testOptions = null;

    /**
     * @param OptionsParser $parser
     * @param null|string $featureKey
     */
    public function addFeatureOptionsParser(OptionsParser $parser, $featureKey = null)
    {
        if (is_null($featureKey)) {
            $featureKey = 'default';
        }

        $this->featureOptionsParsers[$featureKey] = $parser;
    }

    /**
     * @param ParameterBag $requestData
     */
    public function setRequestData(ParameterBag $requestData)
    {
        $this->requestData = $requestData;
    }

    /**
     * @param array $namesAndDefaultValues
     */
    public function setNamesAndDefaultValues($namesAndDefaultValues)
    {
        $this->namesAndDefaultValues = $namesAndDefaultValues;
    }

    /**
     * @param array $availableTaskTypes
     */
    public function setAvailableTaskTypes($availableTaskTypes)
    {
        $this->availableTaskTypes = $availableTaskTypes;
    }

    /**
     * @param array $featuresDefinition
     */
    public function setAvailableFeatures($featuresDefinition)
    {
        $this->availableFeatures = $featuresDefinition;
    }

    /**
     * @param array $invertOptionKeys
     */
    public function setInvertOptionKeys($invertOptionKeys)
    {
        $this->invertOptionKeys = $invertOptionKeys;
    }

    /**
     * @param boolean $invertInvertableOptions
     */
    public function setInvertInvertableOptions($invertInvertableOptions)
    {
        $this->invertInvertableOptions = $invertInvertableOptions;
    }

    /**
     * @return TestOptions
     */
    public function getTestOptions()
    {
        if (is_null($this->testOptions)) {
            $this->populateTestOptionsFromRequestData();
        }

        if ($this->invertInvertableOptions) {
            $this->invertInvertableOptions();
        }

        return $this->testOptions;
    }

    private function invertInvertableOptions()
    {
        foreach ($this->invertOptionKeys as $invertOptionKey) {
            $taskTypeKey = $this->getTaskTypeKeyFromTaskTypeOption($invertOptionKey);
            $testTypeOptions = $this->testOptions->getTestTypeOptions($taskTypeKey);

            if (isset($testTypeOptions[$invertOptionKey])) {
                $testTypeOptions[$invertOptionKey] = ($testTypeOptions[$invertOptionKey]) ? 0 : 1;
            } else {
                $testTypeOptions[$invertOptionKey] = 1;
            }

            $this->testOptions->addTestTypeOptions($taskTypeKey, $testTypeOptions);
        }
    }

    /**
     * @param string $taskTypeOption
     *
     * @return null|string
     */
    private function getTaskTypeKeyFromTaskTypeOption($taskTypeOption)
    {
        $matchingTaskTypeKey = null;

        foreach ($this->availableTaskTypes as $taskTypeKey => $taskTypeName) {
            if (!empty($matchingTaskTypeKey)) {
                continue;
            }

            if (substr($taskTypeOption, 0, strlen($taskTypeKey)) == $taskTypeKey) {
                $matchingTaskTypeKey = $taskTypeKey;
            }
        }

        return $matchingTaskTypeKey;
    }

    private function populateTestOptionsFromRequestData()
    {
        $this->testOptions = new TestOptions();
        $this->testOptions->setAvailableTaskTypes($this->availableTaskTypes);
        $this->testOptions->setAvailableFeatures($this->availableFeatures);

        $features = $this->parseFeatures();
        foreach ($features as $featureKey => $featureOptions) {
            $featureOptionsParser = $this->getFeatureOptionsParser($featureKey);
            $featureOptionsParser->setRequestData($this->requestData);
            $featureOptionsParser->setNamesAndDefaultValues($this->namesAndDefaultValues);
            $featureOptionsParser->setFormKey($this->availableFeatures[$featureKey]['form_key']);

            $this->testOptions->setFeatureOptions($featureKey, $featureOptionsParser->getOptions());
        }

        $testTypes = $this->parseTestTypes();

        foreach ($testTypes as $testTypeKey => $testTypeName) {
            $this->testOptions->addTestType($testTypeKey, $testTypeName);
        }

        foreach ($this->availableTaskTypes as $testTypeKey => $testTypeName) {
            $this->testOptions->addTestTypeOptions($testTypeKey, $this->parseTestTypeOptions($testTypeKey));
        }
    }

    /**
     * @param string $featureKey
     *
     * @return OptionsParser
     */
    private function getFeatureOptionsParser($featureKey)
    {
        if (isset($this->featureOptionsParsers[$featureKey])) {
            return $this->featureOptionsParsers[$featureKey];
        }

        return $this->featureOptionsParsers['default'];
    }

    /**
     * @return array
     */
    private function parseTestTypes()
    {
        $testTypes = [];

        foreach ($this->availableTaskTypes as $testTypeKey => $testTypeName) {
            if (filter_var($this->requestData->get($testTypeKey), FILTER_VALIDATE_BOOLEAN)) {
                $testTypes[$testTypeKey] = $testTypeName;
            }
        }

        return $testTypes;
    }

    /**
     * @return array
     */
    private function parseFeatures()
    {
        $features = [];

        foreach ($this->availableFeatures as $featureKey => $featureOptions) {
            if (isset($featureOptions['enabled']) && $featureOptions['enabled'] === true) {
                $features[$featureKey] = $featureOptions;
            }
        }

        return $features;
    }

    /**
     * @param string $testTypeKey
     *
     * @return array
     */
    private function parseTestTypeOptions($testTypeKey)
    {
        $testTypeOptions = [];

        foreach ($this->requestData as $key => $value) {
            $requestKeyMatchesTestTypeKey = $this->requestKeyMatchesTestTypeKey($key, $testTypeKey);

            if ($requestKeyMatchesTestTypeKey && array_key_exists($key, $this->namesAndDefaultValues)) {
                switch (gettype($this->namesAndDefaultValues[$key])) {
                    case 'integer':
                        $testTypeOptions[$key] = (int)$value;
                        break;

                    case 'array':
                        $rawValues = (is_string($value)) ? explode("\n", $value) : $value;
                        $cleanedValues = [];
                        foreach ($rawValues as $rawValue) {
                            $rawValue = trim($rawValue);
                            if ($rawValue != '') {
                                $cleanedValues[] = $rawValue;
                            }
                        }

                        $testTypeOptions[$key] = $cleanedValues;
                        break;

                    default:
                        $testTypeOptions[$key] = $value;
                        break;
                }
            }
        }

        return $testTypeOptions;
    }

    /**
     * @param string $requestKey
     * @param string $testTypeKey
     *
     * @return bool
     */
    private function requestKeyMatchesTestTypeKey($requestKey, $testTypeKey)
    {
        if ($requestKey == $testTypeKey) {
            return false;
        }

        return substr($requestKey, 0, strlen($testTypeKey)) == $testTypeKey;
    }
}
