<?php

namespace SimplyTestable\WebClientBundle\Services;

use Pdp\PublicSuffixListManager as PdpPublicSuffixListManager;
use Pdp\Parser as PdpParser;
use webignition\Url\Url;

class RegisterableDomainService
{
    /**
     * @param string $canonicalUrl
     *
     * @return string
     */
    public function getRegisterableDomain($canonicalUrl)
    {
        $url = new Url($canonicalUrl);

        if (!$url->isPubliclyRoutable()) {
            return null;
        }

        $pslManager = new PdpPublicSuffixListManager();
        $parser = new PdpParser($pslManager->getList());

        return $parser->parseUrl($canonicalUrl)->host->registerableDomain;
    }
}
