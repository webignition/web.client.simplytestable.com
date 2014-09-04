<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\Account\Team\ResendInviteAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {

    public function testHasResponseLocationHeader() {
        $this->assertResponseHasLocationHeader();
    }

    public function testResponseLocationHeaderValue() {
        $this->assertResponseLocationHeader('/account/team/');
    }

    public function testFlashKeyIsSet() {
        $this->assertHasFlashValue('team_invite_resend');
    }

    protected function getExpectedResponseStatusCode() {
        return 302;
    }

}