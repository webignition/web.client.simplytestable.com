<?php

namespace App\Services;

use Pdp\Rules as PdpRules;
use webignition\Url\Url;

class RegisterableDomainService
{
    /**
     * @var PdpRules
     */
    private $pdpRules;

    /**
     * @param PdpRules $pdpRules
     */
    public function __construct(PdpRules $pdpRules)
    {
        $this->pdpRules = $pdpRules;
    }

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

        $pdpDomain = $this->pdpRules->resolve((string)$url->getHost());

        return $pdpDomain->getRegistrableDomain();
    }
}
