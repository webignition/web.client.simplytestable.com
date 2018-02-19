<?php

namespace Tests\WebClientBundle\Functional\Entity\MailChimp\ListRecipients;

use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients;

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
