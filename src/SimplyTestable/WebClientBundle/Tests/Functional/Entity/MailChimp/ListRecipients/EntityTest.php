<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Entity\MailChimp\ListRecipients;

use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients;

abstract class EntityTest extends BaseSimplyTestableTestCase
{
    /**
     * @var ListRecipients
     */
    protected $listRecipients;

    public function setUp()
    {
        parent::setUp();
        $this->listRecipients = new ListRecipients();
    }
}
