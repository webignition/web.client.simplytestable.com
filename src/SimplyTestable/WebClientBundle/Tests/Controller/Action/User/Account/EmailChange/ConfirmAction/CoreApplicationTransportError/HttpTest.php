<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\Account\EmailChange\ConfirmAction\CoreApplicationTransportError;

abstract class HttpTest extends ActionTest {

    protected function getCoreApplicationResponseFixture() {
        return 'HTTP/1.1 ' .$this->getHttpStatusCodeFromTestClassName();
    }


    private function getHttpStatusCodeFromTestClassName() {
        $classNameParts = explode('\\', get_class($this));
        return (int)str_replace('Http', 'Test', $classNameParts[count($classNameParts) - 1]);
    }

}


