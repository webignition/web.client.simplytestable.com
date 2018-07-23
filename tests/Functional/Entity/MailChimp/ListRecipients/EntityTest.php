<?php

namespace App\Tests\Functional\Entity\MailChimp\ListRecipients;

use App\Tests\Functional\AbstractBaseTestCase;
use App\Entity\MailChimp\ListRecipients;

abstract class EntityTest extends AbstractBaseTestCase
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
