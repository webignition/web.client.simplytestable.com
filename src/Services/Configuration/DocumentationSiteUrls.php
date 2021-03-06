<?php

namespace App\Services\Configuration;

class DocumentationSiteUrls
{
    /**
     * @var array
     */
    private $urls;

    /**
     * @param array $urls
     */
    public function __construct(array $urls = [])
    {
        $this->urls = $urls;
    }

    /**
     * @return array
     */
    public function getUrls()
    {
        return $this->urls;
    }
}
