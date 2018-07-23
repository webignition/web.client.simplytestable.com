<?php

namespace App\Services;

class DocumentationUrlCheckerService
{
    /**
     * @var array
     */
    private $validUrlList = null;

    /**
     * @var string
     */
    private $sitemap = null;

    /**
     * @var string
     */
    private $documentationSitemapPath = null;

    /**
     * @param string $documentationSitemapPath
     */
    public function __construct($documentationSitemapPath)
    {
        $this->documentationSitemapPath = $documentationSitemapPath;
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public function exists($url)
    {
        $validUrlList = $this->getValidUrlList();

        return in_array($url, $validUrlList);
    }

    /**
     * @return array
     */
    private function getValidUrlList()
    {
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
