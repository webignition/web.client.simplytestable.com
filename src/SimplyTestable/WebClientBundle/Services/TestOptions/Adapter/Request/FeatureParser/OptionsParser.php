<?php
namespace SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request\FeatureParser;

use Symfony\Component\HttpFoundation\ParameterBag;

class OptionsParser {
    
    /**
     *
     * @var ParameterBag
     */
    private $requestData = array();


    /**
     * @var string
     */
    private $formKey = null;


    /**
     *
     * @var array
     */
    private $namesAndDefaultValues= array();

    /**
     *
     * @param ParameterBag $requestData
     */
    public function setRequestData(ParameterBag $requestData) {
        $this->requestData = $requestData;
    }


    /**
     * @param string $formKey
     */
    public function setFormKey($formKey) {
        $this->formKey = $formKey;
    }


    public function getOptions() {
        $options = array();

        foreach ($this->requestData as $key => $value) {

            if ($this->requestKeyMatchesFeatureKey($key) && array_key_exists($key, $this->namesAndDefaultValues)) {
                switch (gettype($this->namesAndDefaultValues[$key])) {
                    case 'integer':
                        $options[$key] = (int)$value;
                        break;

                    case 'array':
                        $rawValues = (is_string($value)) ? explode("\n", $value) : $value;
                        $options[$key] = $this->cleanRawValues($rawValues);
                        break;

                    default:
                        $options[$key] = $value;
                        break;
                }
            }
        }

        return $options;
    }


    /**
     *
     * @param array $namesAndDefaultValues
     */
    public function setNamesAndDefaultValues($namesAndDefaultValues) {
        $this->namesAndDefaultValues = $namesAndDefaultValues;
    }


    private function cleanRawValues($rawValues) {
        $cleanedValues = array();

        foreach ($rawValues as $key => $rawValue) {
            if (is_string($rawValue)) {
                $value = trim($rawValue);
                $cleanedValues[$key] = ($value == '') ? null : $value;
            } elseif (is_array($rawValue)) {
                $cleanedValues[$key] = $this->cleanRawValues($rawValue);
            } else {
                $cleanedValues[$key] = $rawValue;
            }
        }

        return $cleanedValues;
    }

    /**
     *
     * @param string $requestKey
     * @return boolean
     */
    private function requestKeyMatchesFeatureKey($requestKey) {
        return substr($requestKey, 0, strlen($this->formKey)) == $this->formKey;
    }
        
}