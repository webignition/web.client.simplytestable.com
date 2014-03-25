<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task\ResultsAction\PrivateUser\ErrorCases;

class CssValidationUnknownErrorTest extends WithReasonParagraphAndLinkTest {    
    
    protected function getExpectedReasonParagraphText() {
        return 'CSS validator went bang when validating';
    }

}


