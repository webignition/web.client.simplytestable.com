<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\RequestListener\OnKernelRequest\IEFiltered\IsFiltered;

use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\RequestListener\OnKernelRequest\IEFiltered\IEFilteredTest;

abstract class IsFilteredTest extends IEFilteredTest {
    
    /**
     * Collection of user agent strings that should all be recognised as an
     * old version of IE and for which a redirect will occur
     * 
     * @var array
     */
    private $userAgentStrings = array(
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
        // IE7
        'Mozilla/4.0(compatible; MSIE 7.0b; Windows NT 6.0)',
        'Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 6.0)',
        'Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 5.2; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.2; .NET CLR 3.0.04506.30)',
        'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
        'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; el-GR)',
        'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 5.2)'
    );

    protected function getHttpUserAgent() {
        return $this->userAgentStrings[$this->getUserAgentIndex()];
    }
    
    private function getUserAgentIndex() {        
        $localTestNameParts = explode('\\', get_class($this));
        $localTestName = $localTestNameParts[count($localTestNameParts) - 1];
        
        return (int)str_replace(array('UserAgent', 'Test'), '', $localTestName);
    }

    public function testResponseStatusCode() {
        $this->assertEquals(302, $this->getEvent()->getResponse()->getStatusCode(), 'Event response does not have expected status code "302"');
    }
    
    public function testResponseLocation() {        
        $this->assertEquals($this->container->getParameter('public_site')['urls']['home'], $this->getEvent()->getResponse()->headers->get('location'));
    }    

}