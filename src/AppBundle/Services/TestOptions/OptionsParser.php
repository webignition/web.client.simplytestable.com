<?php
namespace AppBundle\Services\TestOptions;

use Symfony\Component\HttpFoundation\ParameterBag;

class OptionsParser
{
    /**
     * @var ParameterBag
     */
    private $requestData = [];

    /**
     * @var string
     */
    private $formKey = null;

    /**
     * @var array
     */
    private $namesAndDefaultValues= [];

    /**
     * @param ParameterBag $requestData
     */
    public function setRequestData(ParameterBag $requestData)
    {
        $this->requestData = $requestData;
    }

    /**
     * @param string $formKey
     */
    public function setFormKey($formKey)
    {
        $this->formKey = $formKey;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $options = [];

        foreach ($this->requestData as $key => $value) {
            $requestKeyMatchesFeatureKey = substr($key, 0, strlen($this->formKey)) === $this->formKey;
            $isValidKey = array_key_exists($key, $this->namesAndDefaultValues);

            if ($requestKeyMatchesFeatureKey && $isValidKey) {
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
     * @param array $namesAndDefaultValues
     */
    public function setNamesAndDefaultValues($namesAndDefaultValues)
    {
        $this->namesAndDefaultValues = $namesAndDefaultValues;
    }

    /**
     * @param array $rawValues
     *
     * @return array
     */
    private function cleanRawValues($rawValues)
    {
        $cleanedValues = [];

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
}
