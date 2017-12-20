<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\MailChimp\Service\RetrieveMembers;

use SimplyTestable\WebClientBundle\Tests\Functional\Services\MailChimp\Service\ServiceTest;

abstract class RetrieveMembersTest extends ServiceTest {

    protected function setUp() {
        parent::setUp();

        $this->setHttpFixtures(
            $this->getHttpFixtures($this->getFixturesDataPath($this->getName())),
            $this->container->get('simplytestable.services.mailchimp.client')
        );
    }

    abstract protected function getExpectedMembersCount();

    public function testMembersCount() {
        $members = $this->getMailchimpService()->retrieveMembers('announcements');
        $this->assertEquals($this->getExpectedMembersCount(), count($members));
    }

}
