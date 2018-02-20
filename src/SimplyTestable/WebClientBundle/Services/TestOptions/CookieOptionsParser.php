<?php

namespace SimplyTestable\WebClientBundle\Services\TestOptions;

class CookieOptionsParser extends OptionsParser
{
    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
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
     *
     * @return bool
     */
    private function containsRequiredNonBlankFields($cookie)
    {
        $requiredNonBlankFields = [
            'name',
            'value'
        ];

        foreach ($requiredNonBlankFields as $fieldName) {
            $value = trim($cookie[$fieldName]);

            if (empty($value)) {
                return false;
            }
        }

        return true;
    }
}
