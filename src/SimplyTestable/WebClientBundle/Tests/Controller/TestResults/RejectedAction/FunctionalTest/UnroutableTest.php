<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\TestResults\RejectedAction\FunctionalTest;

class UnroutableTest extends FunctionalTest {
    
    const WEBSITE = 'http://abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz/';
    
    protected function getWebsite() {
        return self::WEBSITE;
    }

    public function testFirstParagraphLinkTitle() {
        $links = $this->getScopedCrawler()->filter('.first')->filter('a');        
        $titles = $links->extract('title');
        
        $this->assertEquals($this->getWebsite(), $titles[0]);
    }    
    
    public function testFirstParagraphLinkHref() {
        $links = $this->getScopedCrawler()->filter('.first')->filter('a');        
        $hrefs = $links->extract('href');
        
        $this->assertEquals($this->getWebsite(), $hrefs[0]);
    }      
    
    public function testFirstParagraphLinkContent() {
        $expectedContent = substr($this->getWebsite(), 0, 40) . '…';
        
        $links = $this->getScopedCrawler()->filter('.first')->filter('a');        
        
        foreach ($links as $link) {
            $this->assertDomNodeContainsNext($link, $expectedContent);
        }
    }      

}


