<?php
namespace SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request\FeatureParser;

class CookieOptionsParser extends OptionsParser {

    private $requiredNonBlankFields = [
        'name',
        'value'
    ];

    public function getOptions() {
        $options = parent::getOptions();

        if (isset($options['cookies'])) {
            $cookies = $options['cookies'];

            foreach ($cookies as $index => $cookie) {
                if (!$this->containsRequiredNonBlankFields($cookie)) {
                    unset($cookies[$index]);
                }
            }

            $options['cookies'] = $cookies;
        }

        return $options;
    }


    /**
     * @param $cookie
     * @return bool
     */
    private function containsRequiredNonBlankFields($cookie) {
        foreach ($this->requiredNonBlankFields as $fieldName) {
            if (!isset($cookie[$fieldName])) {
                return false;
            }

            return trim($cookie[$fieldName]) !== '';
        }
    }

}