<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\ApiBundle\Entity\Website;
use webignition\NormalisedUrl\NormalisedUrl;

/**
 * URL handling tasks 
 */
class WebsiteBlockListService {
    
    /**
     *
     * @var array
     */
    private $blockList = null;
    
    
    /**
     *
     * @var string
     */
    private $blockListResourcePath = null;
    
    
    /**
     * 
     * @param string $website
     * @return boolean
     */
    public function contains($website) {
        $websiteUrl = new NormalisedUrl($website);
        $websiteHost = $websiteUrl->getHost();
        $blocklist = $this->getBlockList();
        
        foreach ($blocklist as $websiteMainDomain) {
            $websitePattern = '/^(www\.)?'.$websiteMainDomain.'.([a-z.]+)$/i';
            
            if (preg_match($websitePattern, $websiteHost) > 0) {
                return true;
            }
        }
        
        return false;
    }
    
    
    /**
     * 
     * @param string $path
     */
    public function setBlockListResourcePath($path) {        
        $this->blockListResourcePath = $path;
    }
    
    
    /**
     * 
     * @return array
     */
    private function getBlockList() {
        if (is_null($this->blockList)) {
            $this->blockList = array();
            
            $rawBlockList = file($this->blockListResourcePath);
            foreach ($rawBlockList as $rawHostPattern) {
                if (!in_array($rawHostPattern, $this->blockList)) {                  
                    $this->blockList[] = trim($rawHostPattern);
                }
            }
            
        }
        
        return $this->blockList;
    }    
    
}