<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Rejected\Index\IndexAction\Functional;

class UnroutableTest extends FunctionalTest {

    public function testFirstParagraphLinkTitle() {
        $this->assertEquals(self::WEBSITE, $this->getFirstParagraphLink()->extract('title')[0]);
    }

    public function testFirstParagraphLinkHref() {
        $this->assertEquals(self::WEBSITE, $this->getFirstParagraphLink()->extract('href')[0]);
    }

    public function testFirstParagraphLinkContent() {
        $expectedContent = substr(self::WEBSITE, 0, 40) . '…';

        foreach ($this->getFirstParagraphLink() as $link) {
            $this->assertDomNodeContainsText($link, $expectedContent);
        }
    }

    public function testReasonContent() {
        foreach ($this->getFirstParagraph() as $paragraph) {
            $this->assertDomNodeContainsText($paragraph, 'can\'t be accessed over the Internet');
        }
    }


    /**
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    private function getFirstParagraph() {
        return $this->getScopedCrawler()->filter('p.lead:first-of-type');
    }


    /**
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    private function getFirstParagraphLink() {
        return $this->getFirstParagraph()->filter('a');
    }

}