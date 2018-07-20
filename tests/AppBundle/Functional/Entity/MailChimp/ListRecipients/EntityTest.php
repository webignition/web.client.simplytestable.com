<?php

namespace Tests\AppBundle\Functional\Entity\MailChimp\ListRecipients;

use Tests\AppBundle\Functional\AbstractBaseTestCase;
use AppBundle\Entity\MailChimp\ListRecipients;

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
