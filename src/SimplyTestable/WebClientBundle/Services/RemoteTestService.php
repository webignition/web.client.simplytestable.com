<?php
namespace SimplyTestable\WebClientBundle\Services;

use Doctrine\ORM\EntityManager;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\TimePeriod;
use Symfony\Component\HttpKernel\Log\LoggerInterface as Logger;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\TestOptions;

use webignition\NormalisedUrl\NormalisedUrl;


class RemoteTestService extends CoreApplicationService {
    
    /**
     *
     * @var \stdClass
     */
    private $remoteTestSummary = null;
    
    
    /**
     *
     * @var int
     */
    private $remoteTestId = null;
    
    /**
     *
     * @var string
     */
    private $remoteTestCanonicalUrl = null;
    
}