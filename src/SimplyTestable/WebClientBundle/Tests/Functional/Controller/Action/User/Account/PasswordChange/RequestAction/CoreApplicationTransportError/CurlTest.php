<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\PasswordChange\RequestAction\CoreApplicationTransportError;

abstract class CurlTest extends ActionTest {

    protected function getCoreApplicationResponseFixture() {
        return 'CURL/' .$this->getCUrlCodeFromTestClassName();
    }


    private function getCUrlCodeFromTestClassName() {
        $classNameParts = explode('\\', get_class($this));
        return (int)str_replace('Curl', 'Test', $classNameParts[count($classNameParts) - 1]);
    }

}


