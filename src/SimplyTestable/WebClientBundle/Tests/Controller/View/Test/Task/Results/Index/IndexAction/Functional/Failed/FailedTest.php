<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Failed;

use SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction\Functional\FunctionalTest;

abstract class FailedTest extends FunctionalTest {

    abstract protected function getExpectedHeading();

    public function testTitleContainsTruncatedUrl() {
        $this->assertTitleContainsText(
            'Results for ' . substr(str_replace('http://', '', $this->getWebsite()), 0, 64 ) . '…'
        );
    }

    public function testHeadingContent() {
        $this->assertElementContains('h2', $this->getExpectedHeading());
    }

    public function testHeadingStartsWithUppercaseCharacter() {
        $this->assertEquals(
            strtoupper($this->getHeadingWords()[0][0]),
            $this->getHeadingWords()[0][0],
            'Heading "' . $this->getHeadingText() . '" does not start with an uppercase character'
        );
    }

    public function testHeadingNotFirstWordCase() {
        foreach ($this->getHeadingWords() as $index => $word) {
            if ($index === 0) {
                continue;
            }

            if (strtoupper($word) != $word) {
                $this->assertEquals(
                    strtoupper($word[0]),
                    $word[0],
                    'Heading word "' . $word . '" does not start with an uppercase character'
                );
            }
        }
    }


    /**
     * @return array
     */
    private function getHeadingWords() {
        return explode(" ", $this->getHeadingText());
    }


    /**
     * @return string
     */
    private function getHeadingText() {
        return trim($this->getScopedCrawler()->filter('h2')->text());
    }



}