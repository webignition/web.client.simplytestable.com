<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\Account\EmailChange\ConfirmAction\CoreApplicationTransportError;

abstract class CurlTest extends ActionTest {

    protected function getCoreApplicationResponseFixture() {
        return 'CURL/' .$this->getCUrlCodeFromTestClassName();
    }


    private function getCUrlCodeFromTestClassName() {
        $classNameParts = explode('\\', get_class($this));
        return (int)str_replace('Curl', 'Test', $classNameParts[count($classNameParts) - 1]);
    }

}


