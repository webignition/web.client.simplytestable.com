<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task\ResultsAction\PrivateUser\ErrorCases;

class Http500Test extends WithReasonParagraphTest {
    
    protected function getExpectedReasonParagraphText() {
        return 'failed to return an acceptable response';
    }

}


