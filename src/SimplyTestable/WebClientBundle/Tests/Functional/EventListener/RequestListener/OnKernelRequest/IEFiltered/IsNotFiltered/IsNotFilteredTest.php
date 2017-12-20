<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\RequestListener\OnKernelRequest\IEFiltered\IsNotFiltered;

use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\RequestListener\OnKernelRequest\IEFiltered\IEFilteredTest;

abstract class IsNotFilteredTest extends IEFilteredTest {
    
    /**
     * Collection of user agent strings that should not be recognised as an
     * old version of IE and for which a redirect should not occur
     * 
     * Currently this list contains only user agent strings that are not IE but
     * which could be mistaken for IE
     * 
     * @var array
     */
    private $userAgentStrings = array(
        'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; GTB6.5; QQDownload 534; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; SLCC2; .NET CLR 2.0.50727; Media Center PC 6.0; .NET CLR 3.5.30729; .NET CLR 3.0.30729))',
        'Mozilla/4.0 (compatible; MSIE 6.0; X11; Linux i686; de) Opera 10.10',
        'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 6.0; tr) Opera 10.10',
        'Mozilla/4.0 (compatible; MSIE 6.0; Linux i686 ; en) Opera 9.70',
        'Mozilla/4.0 (compatible; MSIE 6.0; X11; Linux x86_64; en) Opera 9.60',
        'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; ru) Opera 9.52',
    );

    protected function getHttpUserAgent() {
        return $this->userAgentStrings[$this->getUserAgentIndex()];
    }
    
    private function getUserAgentIndex() {        
        $localTestNameParts = explode('\\', get_class($this));
        $localTestName = $localTestNameParts[count($localTestNameParts) - 1];
        
        return (int)str_replace(array('UserAgent', 'Test'), '', $localTestName);
    }    
    
    public function testEventHasNoResponse() {
        $this->assertNull($this->getEvent()->getResponse());
    }
}