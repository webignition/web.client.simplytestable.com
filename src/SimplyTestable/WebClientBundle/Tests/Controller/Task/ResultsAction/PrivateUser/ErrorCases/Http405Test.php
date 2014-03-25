<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task\ResultsAction\PrivateUser\ErrorCases;

class Http405Test extends WithReasonParagraphAndLinkTest {    
    
    protected function getExpectedReasonParagraphText() {
        return "tried to retrieve";
    }

}


