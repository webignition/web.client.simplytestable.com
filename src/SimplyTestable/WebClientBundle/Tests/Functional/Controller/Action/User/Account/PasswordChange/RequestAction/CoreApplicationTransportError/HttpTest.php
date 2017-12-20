<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\PasswordChange\RequestAction\CoreApplicationTransportError;

abstract class HttpTest extends ActionTest {

    protected function getCoreApplicationResponseFixture() {
        return 'HTTP/1.1 ' .$this->getHttpStatusCodeFromTestClassName();
    }


    private function getHttpStatusCodeFromTestClassName() {
        $classNameParts = explode('\\', get_class($this));
        return (int)str_replace(['Http', 'Test'], '', $classNameParts[count($classNameParts) - 1]);
    }

}


