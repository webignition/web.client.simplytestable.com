<?php
namespace SimplyTestable\WebClientBundle\Command\EmailList;

use Symfony\Component\Console\Command\Command;
use SimplyTestable\WebClientBundle\Services\MailChimp\Service as MailChimpService;

abstract class AbstractEmailListCommand extends Command
{
    /**
     * @var MailChimpService
     */
    protected $mailChimpService;

    /**
     * @param MailChimpService $mailChimpService
     * @param string|null $name
     */
    public function __construct(
        MailChimpService $mailChimpService,
        $name = null
    ) {
        parent::__construct($name);

        $this->mailChimpService = $mailChimpService;
    }
}
