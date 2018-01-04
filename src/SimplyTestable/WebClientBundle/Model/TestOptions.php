<?php

namespace SimplyTestable\WebClientBundle\Model;

class TestOptions
{
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
    private $testTypes = [];

    /**
     * @var array
     */
    private $testTypeOptions = [];

    /**
     * @var array
     */
    private $features = [];

    /**
     * @param array $availableTaskTypes
     */
    public function setAvailableTaskTypes($availableTaskTypes)
    {
        $this->availableTaskTypes = $availableTaskTypes;
    }

    /**
     * @param array $availableFeatures
     */
    public function setAvailableFeatures($availableFeatures)
    {
        $this->availableFeatures = $availableFeatures;
    }

    /**
     * @param string $testType
     */
    public function addTestType($testTypeKey, $testType)
    {
        if (!array_key_exists($testTypeKey, $this->testTypes)) {
            $this->testTypes[$testTypeKey] = $testType;
        }
    }

    /**
     * @param string $testTypeKey
     */
    public function removeTestType($testTypeKey)
    {
        if (array_key_exists($testTypeKey, $this->testTypes)) {
            unset($this->testTypes[$testTypeKey]);
        }
    }

    /**
     * @param string $name
     * @param array $options
     */
    public function setFeatureOptions($name, $options)
    {
        $this->features[$name] = $options;
    }

    /**
     * @param string $testType
     * @param array $testTypeOptions
     */
    public function addTestTypeOptions($testType, $testTypeOptions)
    {
        $this->testTypeOptions[$testType] = $testTypeOptions;
    }

    /**
     * @return bool
     */
    public function hasTestTypes()
    {
        return count($this->testTypes) > 0;
    }

    /**
     * @return bool
     */
    public function hasFeatures()
    {
        return count($this->features) > 0;
    }

    /**
     * @param string $testType
     *
     * @return bool
     */
    public function hasTestType($testType)
    {
        foreach ($this->testTypes as $testTypeKey => $testTypeDetails) {
            if ($testTypeDetails['name'] == $testType) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getTestTypes()
    {
        $testTypes = [];

        foreach ($this->testTypes as $key => $name) {
            $testTypes[] = $name;
        }

        return $testTypes;
    }

    /**
     * @return array
     */
    public function getFeatures()
    {
        $features = [];

        foreach ($this->features as $featureKey => $featureOptions) {
            $features[] = $featureKey;
        }

        return $features;
    }

    /**
     * @param string $testType
     *
     * @return bool
     */
    public function hasTestTypeOptions($testType)
    {
        if (!isset($this->testTypeOptions[$testType])) {
            return false;
        }

        if (!is_array($this->testTypeOptions[$testType])) {
            return false;
        }

        return count($this->testTypeOptions[$testType]) > 0;
    }

    /**
     * @param string $testType
     *
     * @return array
     */
    public function getTestTypeOptions($testType)
    {
        if ($this->hasTestTypeOptions($testType)) {
            return $this->testTypeOptions[$testType];
        }

        return [];
    }

    /**
     * @param string $feature
     *
     * @return bool
     */
    public function hasFeatureOptions($feature)
    {
        if (!isset($this->features[$feature])) {
            return false;
        }

        if (!is_array($this->features[$feature])) {
            return false;
        }

        return count($this->features[$feature]) > 0;
    }

    /**
     * @param string $feature
     *
     * @return array
     */
    public function getFeatureOptions($feature)
    {
        if ($this->hasFeatureOptions($feature)) {
            return $this->features[$feature];
        }

        return [];
    }

    /**
     * @param string $feature
     */
    public function removeFeatureOptions($feature)
    {
        if ($this->hasFeatureOptions($feature)) {
            unset($this->features[$feature]);
        }
    }

    /**
     * @param string $testType
     * @param bool $useFullOptionKey
     *
     * @return array
     */
    public function getAbsoluteTestTypeOptions($testType, $useFullOptionKey = true)
    {
        $absoluteTestTypeOptions = [];
        $testTypeOptions = $this->getTestTypeOptions($testType);

        foreach ($testTypeOptions as $optionKey => $optionValue) {
            $key = ($useFullOptionKey) ? $optionKey : str_replace($testType.'-', '', $optionKey);
            $absoluteTestTypeOptions[$key] = $optionValue;
        }

        return $absoluteTestTypeOptions;
    }

    /**
     * @param string $featureKey
     *
     * @return array
     */
    public function getAbsoluteFeatureOptions($featureKey)
    {
        $absoluteFeatureOptions = [];
        $featureOptions = $this->getFeatureOptions($featureKey);

        foreach ($featureOptions as $optionKey => $optionValue) {
            $absoluteFeatureOptions[$optionKey] = $optionValue;
        }

        return $absoluteFeatureOptions;
    }

    /**
     * @param string $testTypeKey
     *
     * @return string
     */
    public function getNameFromKey($testTypeKey)
    {
        foreach ($this->testTypeMap as $key => $value) {
            if ($testTypeKey == $key) {
                return $value;
            }
        }

        return '';
    }

    /**
     * @return array
     */
    public function getAbsoluteTestTypes()
    {
        $absoluteTestTypes = [];

        foreach ($this->availableTaskTypes as $testTypeKey => $testTypeName) {
            if ($this->hasTestType($testTypeName)) {
                $absoluteTestTypes[$testTypeKey] = 1;
            } else {
                $absoluteTestTypes[$testTypeKey] = 0;
            }
        }

        return $absoluteTestTypes;
    }

    /**
     * @return array
     */
    public function getAbsoluteFeatures() {
        $absoluteFeatures = [];

        foreach ($this->availableFeatures as $featureKey => $featureOptions) {
            if ($this->hasFeatureOptions($featureKey)) {
                $absoluteFeatures[$featureKey] = 1;
            } else {
                $absoluteFeatures[$featureKey] = 0;
            }
        }

        return $absoluteFeatures;
    }

    /**
     * @return array
     */
    public function __toArray()
    {
        $optionsAsArray = [];

        if ($this->hasTestTypes()) {
            $optionsAsArray['test-types'] = $this->getTestTypes();

            foreach ($this->testTypes as $testTypeKey => $testType) {
                if (!isset($optionsAsArray['test-type-options'])) {
                    $optionsAsArray['test-type-options'] = [];
                }
            }

            foreach ($this->availableTaskTypes as $taskTypeKey => $taskTypeDetails) {
                $optionsAsArray['test-type-options'][$taskTypeDetails['name']] =
                    $this->getAbsoluteTestTypeOptions($taskTypeKey, false);
            }
        }

        if ($this->hasFeatures()) {
            if (!isset($optionsAsArray['parameters'])) {
                $optionsAsArray['parameters'] = [];
            }

            foreach ($this->getFeatures() as $featureKey) {
                $optionsAsArray['parameters'] = array_merge(
                    $optionsAsArray['parameters'],
                    $this->getAbsoluteFeatureOptions($featureKey)
                );
            }
        }

        return $optionsAsArray;
    }

    /**
     * @return array
     */
    public function __toKeyArray()
    {
        $optionsAsArray = [];

        foreach ($this->features as $featureKey => $featureOptions) {
            $optionsAsArray[$featureKey] = 1;

            foreach ($featureOptions as $optionKey => $optionValue) {
                $optionsAsArray[$optionKey] = $optionValue;
            }
        }

        foreach ($this->testTypes as $testTypeKey => $testType) {
            $optionsAsArray[$testTypeKey] = 1;
        }

        foreach ($this->testTypeOptions as $testTypeKey => $testTypeOptions) {
            foreach ($testTypeOptions as $optionKey => $optionValue) {
                $optionsAsArray[$optionKey] = $optionValue;
            }
        }

        return $optionsAsArray;
    }
}
