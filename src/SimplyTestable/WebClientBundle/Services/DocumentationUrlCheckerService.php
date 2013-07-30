<?php
namespace SimplyTestable\WebClientBundle\Services;

class DocumentationUrlCheckerService extends UrlCheckerService {
    
    
    /**
     *
     * @var array
     */
    private $validUrlList = null;
    
    
    /**
     *
     * @var string
     */
    private $sitemap = null;
    
    
    /**
     *
     * @var string
     */
    private $documentationSitemapPath = null;
    
    
    /**
     * 
     * @param string $documentationSitemapPath
     */
    public function setDocumentationSitemapPath($documentationSitemapPath) {
        $this->documentationSitemapPath = $documentationSitemapPath;
    }
    
    
    /**
     * 
     * @param string $url
     * @return boolean
     */
    public function exists($url) {
        $validUrlList = $this->getValidUrlList();        
        return in_array($url, $validUrlList);
    }
    
    
    private function getValidUrlList() {
        if (is_null($this->validUrlList)) {
            $this->validUrlList = array();
            
            $sitemapContent = file_get_contents($this->documentationSitemapPath);
            $this->sitemap = new \DOMDocument();
            $this->sitemap->loadXML($sitemapContent);
            
            $locList = $this->sitemap->getElementsByTagName('loc');
            
            foreach ($locList as $item) {
                $this->validUrlList[] = trim($item->nodeValue);
            }           
        }
        
        return $this->validUrlList;
    }      

    
}