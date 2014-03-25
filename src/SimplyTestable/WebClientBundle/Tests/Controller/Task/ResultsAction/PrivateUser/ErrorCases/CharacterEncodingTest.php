<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task\ResultsAction\PrivateUser\ErrorCases;

class CharacterEncodingTest extends WithReasonParagraphAndLinkTest {    
    
    protected function getExpectedReasonParagraphText() {
        return 'character encoding used for the page at';
    }
    
    public function testValidatorCharacterEncodingNoticeIsPresent() {
        $blockquotes = $this->getScopedCrawler()->filter('blockquote');        
        
        $this->assertEquals(1, $blockquotes->count(), "Blockquote notice missing");
    }    
    
    public function testValidatorCharacterEncodingNoticeContent() {
        $blockquotes = $this->getScopedCrawler()->filter('blockquote');        
        
        foreach ($blockquotes as $blockquote) {
            $this->assertDomNodeContainsNext($blockquote, 'foobar');
        }
    }

}


