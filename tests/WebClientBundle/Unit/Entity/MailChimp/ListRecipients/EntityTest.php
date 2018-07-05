<?php

namespace Tests\WebClientBundle\Unit\Entity\MailChimp\ListRecipients;

use SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients;

abstract class EntityTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ListRecipients
     */
    protected $listRecipients;

    protected function setUp()
    {
        parent::setUp();
        $this->listRecipients = new ListRecipients();
    }
}
