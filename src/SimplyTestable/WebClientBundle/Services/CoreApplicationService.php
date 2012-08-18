<?php
namespace SimplyTestable\WebClientBundle\Services;


class CoreApplicationService {
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\WebResourceService 
     */
    private $webResourceService;
    
    
    public function __construct(
        \SimplyTestable\WebClientBundle\Services\WebResourceService $webResourceService
    ) {
        $this->webResourceService = $webResourceService;
    }
    
    
}