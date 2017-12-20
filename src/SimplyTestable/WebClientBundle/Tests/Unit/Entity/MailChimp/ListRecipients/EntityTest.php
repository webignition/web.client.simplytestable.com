<?php

namespace SimplyTestable\WebClientBundle\Tests\Unit\Entity\MailChimp\ListRecipients;

use SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients;

abstract class EntityTest extends \PHPUnit_Framework_TestCase
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
