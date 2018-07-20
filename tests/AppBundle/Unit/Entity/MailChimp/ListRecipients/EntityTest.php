<?php

namespace Tests\AppBundle\Unit\Entity\MailChimp\ListRecipients;

use AppBundle\Entity\MailChimp\ListRecipients;

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
