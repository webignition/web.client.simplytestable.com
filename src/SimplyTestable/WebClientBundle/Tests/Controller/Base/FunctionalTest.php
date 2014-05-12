<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Base;

abstract class FunctionalTest extends BaseTest {

    const SIGN_IN_BUTTON_TEXT = 'Sign in';
    const SIGN_IN_BUTTON_URL = '/signin/';
    const CREATE_ACCOUNT_BUTTON_TEXT = 'Create an account';
    const CREATE_ACCOUNT_BUTTON_URL = '/signup/';

    /**
     * @var \Symfony\Component\DomCrawler\Crawler
     */
    private $crawler;


    /**
     *
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function getScopedCrawler() {
        if (is_null($this->crawler)) {
            $this->crawler = $this->getCrawler($this->getCurrentRequestUrl());
        }

        return $this->crawler;
    }

    private function getCurrentRequestUrl() {
        return $this->getCurrentController($this->getRequestPostData(), $this->getRequestQueryData())->generateUrl(
            $this->getRouteFromTestNamespace(),
            $this->getRouteParameters()
        );

        return $this->getCurrentController();
    }

    protected function getRouteParameters() {
        return array();
    }

    protected function getRequestPostData() {
        return array();
    }

    protected function getRequestQueryData() {
        return array();
    }


    protected function assertNavbarContainsSignInButton() {
        $this->assertEquals(
            1,
            $this->getSignInButton()->count(),
            '.navbar does not contain "' . self::SIGN_IN_BUTTON_TEXT . '" button'
        );
    }


    protected function assertNavbarSignInButtonUrl() {
        $this->assertEquals(
            self::SIGN_IN_BUTTON_URL,
            $this->getSignInButton()->extract('href')[0],
            '.navbar "' . self::SIGN_IN_BUTTON_TEXT . '" button URL is not "' . self::SIGN_IN_BUTTON_URL . '"'
        );
    }


    protected function assertNavbarContainsCreateAccountButton() {
        $this->assertEquals(
            1,
            $this->getCreateAccountButton()->count(),
            '.navbar does not contain "' . self::CREATE_ACCOUNT_BUTTON_TEXT . '" button'
        );
    }


    protected function assertNavbarCreateAccountButtonUrl() {
        $this->assertEquals(
            self::CREATE_ACCOUNT_BUTTON_URL,
            $this->getCreateAccountButton()->extract('href')[0],
            '.navbar "' . self::CREATE_ACCOUNT_BUTTON_TEXT . '" button URL is not "' . self::CREATE_ACCOUNT_BUTTON_URL . '"'
        );
    }



    /**
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    private function getSignInButton() {
        return $this->getNavbar()->filter('a:contains("' . self::SIGN_IN_BUTTON_TEXT . '")');
    }


    /**
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    private function getCreateAccountButton() {
        return $this->getNavbar()->filter('a:contains("' . self::CREATE_ACCOUNT_BUTTON_TEXT . '")');
    }


//
//    protected function publicUserNavbarContainsSignInButtonTest(\Symfony\Component\DomCrawler\Crawler $crawler) {
//        $button = $this->getNavbar($crawler)->filter('a:contains("Sign in")');
//        $this->assertEquals(1, $button->count());
//    }
//
//    protected function publicUserNavbarSignInButtonUrlTest(\Symfony\Component\DomCrawler\Crawler $crawler) {
//        $button = $this->getNavbar($crawler)->filter('a:contains("Sign in")');
//        $this->assertEquals(array('/signin/'), $button->extract('href'));
//    }
//
//    protected function publicUserNavbarContainsCreateAccountButtonTest(\Symfony\Component\DomCrawler\Crawler $crawler) {
//        $button = $this->getNavbar($crawler)->filter('a:contains("Create account")');
//        $this->assertEquals(1, $button->count());
//    }
//
//    protected function publicUserNavbarCreateAccountButtonUrlTest(\Symfony\Component\DomCrawler\Crawler $crawler) {
//        $button = $this->getNavbar($crawler)->filter('a:contains("Create account")');
//        $this->assertEquals(array('/signup/'), $button->extract('href'));
//    }
//
//    protected function privateUserNavbarContainsAccountLinkTest(\Symfony\Component\DomCrawler\Crawler $crawler) {
//        if (!$this->hasUser()) {
//            $this->fail('Cannot verify authorised user navbar content; no user set');
//        }
//
//        $button = $this->getNavbar($crawler)->filter('a:contains("'.$this->getUser()->getUsername().'")');
//        $this->assertEquals(1, $button->count(), "Navbar does not contain account link");
//    }
//
//    protected function privateUserNavbarAccountLinkUrlTest(\Symfony\Component\DomCrawler\Crawler $crawler) {
//        if (!$this->hasUser()) {
//            $this->fail('Cannot verify authorised user navbar content; no user set');
//        }
//
//        $button = $this->getNavbar($crawler)->filter('a:contains("'.$this->getUser()->getUsername().'")');
//        $this->assertEquals(array('/account/'), $button->extract('href'));
//    }
//
//    protected function privateUserNavbarContainsSignOutButtonTest(\Symfony\Component\DomCrawler\Crawler $crawler) {
//        if (!$this->hasUser()) {
//            $this->fail('Cannot verify authorised user navbar content; no user set');
//        }
//
//        $button = $this->getNavbar($crawler)->filter('button:contains("Sign out")');
//        $this->assertEquals(1, $button->count());
//    }
//
//    protected function privateUserNavbarSignOutFormUrlTest(\Symfony\Component\DomCrawler\Crawler $crawler) {
//        if (!$this->hasUser()) {
//            $this->fail('Cannot verify authorised user navbar content; no user set');
//        }
//
//        $form = $this->getNavbar($crawler)->filter('form');
//        $this->assertEquals(array('/signout/'), $form->extract('action'));
//    }
//
//
    /**
     *
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    private function getNavbar() {
        return $this->getScopedCrawler()->filter('.navbar');
    }


    /**
     * @param \DOMNode $node
     * @return string
     */
    private function domNodeToHtml(\DOMNode $node) {
        return $node->ownerDocument->saveHTML($node);
    }

    protected function assertDomNodeContainsNext(\DOMNode $node, $text) {
        $markup = $this->domNodeToHtml($node);
        $content = strip_tags($markup);
        $content = preg_replace('/\s/', ' ', $content);

        while (substr_count($content, '  ')) {
            $content = str_replace('  ', ' ', $content);
        }

        $content = trim($content);

        if (substr_count($content, $text) < 1) {
            $this->fail('Markup "'.$markup.'" does not contain text "'.$text.'"');
        }
    }

    protected function assertDomNodeDoesNotContainNext(\DOMNode $node, $text) {
        $markup = $this->domNodeToHtml($node);

        if (substr_count($markup, $text) > 0) {
            $this->fail('Markup "'.$markup.'" does contain text "'.$text.'"');
        }
    }


    protected function assertTitleContainsText($text) {
        $titles = $this->getScopedCrawler()->filter('title');
        $this->assertEquals(1, $titles->count());

        foreach ($titles as $title) {
            $this->assertDomNodeContainsNext($title, $text);
        }
    }


    protected function assertTitleDoesNotContainText($text) {
        $titles = $this->getScopedCrawler()->filter('title');
        $this->assertEquals(1, $titles->count());

        foreach ($titles as $title) {
            $this->assertDomNodeDoesNotContainNext($title, $text);
        }
    }
    
}


