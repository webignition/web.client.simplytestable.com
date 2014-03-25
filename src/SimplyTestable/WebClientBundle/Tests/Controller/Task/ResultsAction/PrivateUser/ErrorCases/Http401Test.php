<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task\ResultsAction\PrivateUser\ErrorCases;

class Http401Test extends WithReasonParagraphAndLinkTest {    
    
    protected function getExpectedReasonParagraphText() {
        return 'requires authorisation';
    }

}


