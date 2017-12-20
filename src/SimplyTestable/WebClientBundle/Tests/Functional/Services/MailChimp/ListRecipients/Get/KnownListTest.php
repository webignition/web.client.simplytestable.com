<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\MailChimp\ListRecipients\Get;

use SimplyTestable\WebClientBundle\Tests\Functional\Services\MailChimp\ListRecipients\ServiceTest;

abstract class KnownListTest extends ServiceTest {
    
    /**
     * Derive list name from test namespace
     * 
     * @return string
     */
    protected function getListName() {
        $classNameParts = explode('\\', get_class($this));
        return strtolower(str_replace('Test', '', $classNameParts[count($classNameParts) - 1]));
    }    

}
