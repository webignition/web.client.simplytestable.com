<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class AppControllerTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    }    
    
    public function testOutdatedBrowserRedirect() { 
        // 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; GTB6.5; QQDownload 534; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; SLCC2; .NET CLR 2.0.50727; Media Center PC 6.0; .NET CLR 3.5.30729; .NET CLR 3.0.30729))'
        
        $outdatedBrowserUserAgentStrings = array(
            // IE6
            'Mozilla/4.0 (compatible; MSIE 6.1; Windows XP; .NET CLR 1.1.4322; .NET CLR 2.0.50727)',
            'Mozilla/4.0 (compatible; MSIE 6.1; Windows XP))',
            'Mozilla/5.0 (Windows; U; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727))',
            'Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 5.1))',
            'Mozilla/4.0 (Windows; MSIE 6.0; Windows NT 6.0))',
            'Mozilla/4.0 (Windows; MSIE 6.0; Windows NT 5.2))',
            'Mozilla/4.0 (Windows; MSIE 6.0; Windows NT 5.0))',
            'Mozilla/4.0 (Windows; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727))',
            'Mozilla/4.0 (MSIE 6.0; Windows NT 5.1))',
            'Mozilla/4.0 (MSIE 6.0; Windows NT 5.0))',
            'Mozilla/4.0 (compatible; U; MSIE 6.0; Windows NT 5.1))',
            'Mozilla/4.0(compatible; MSIE 7.0b; Windows NT 6.0)',
            'Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 6.0)',
            'Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 5.2; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.2; .NET CLR 3.0.04506.30)',
            'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
            'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; el-GR)',
            'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 5.2)'
        );        
        
        $appController = $this->getAppController('indexAction');
        
        foreach ($outdatedBrowserUserAgentStrings as $outdatedBrowserUserAgentString) {
            $_SERVER['HTTP_USER_AGENT'] = $outdatedBrowserUserAgentString;        

            $appController = $this->getAppController('indexAction');

            /* @var $response \Symfony\Component\HttpFoundation\RedirectResponse */
            $response = $appController->indexAction();

            $publicSiteParameters = $this->container->getParameter('public_site');

            $this->assertEquals(302, $response->getStatusCode());
            $this->assertEquals($publicSiteParameters['urls']['home'], $response->getTargetUrl());            
        }
    }
    
}


