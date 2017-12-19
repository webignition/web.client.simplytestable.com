<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\Team\InviteMemberAction;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Base\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {

    public function testHasResponseLocationHeader() {
        $this->assertResponseHasLocationHeader();
    }

    public function testResponseLocationHeaderValue() {
        $this->assertResponseLocationHeader('/account/team/');
    }

    public function testFlashKeyIsSet() {
        $this->assertHasFlashValue('team_invite_get');
    }

    protected function getExpectedResponseStatusCode() {
        return 302;
    }

}