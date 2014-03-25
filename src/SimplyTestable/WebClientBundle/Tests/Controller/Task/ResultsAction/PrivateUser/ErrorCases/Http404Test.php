<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task\ResultsAction\PrivateUser\ErrorCases;

class Http404Test extends WithReasonParagraphAndLinkTest {    
    
    protected function getExpectedReasonParagraphText() {
        return "it doesn't exist";
    }

}


