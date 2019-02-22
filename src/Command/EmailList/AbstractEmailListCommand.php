<?php

namespace App\Command\EmailList;

use Symfony\Component\Console\Command\Command;
use App\Services\MailChimp\Service as MailChimpService;

abstract class AbstractEmailListCommand extends Command
{
    protected $mailChimpService;

    public function __construct(
        MailChimpService $mailChimpService,
        ?string $name = null
    ) {
        parent::__construct($name);

        $this->mailChimpService = $mailChimpService;
    }
}
