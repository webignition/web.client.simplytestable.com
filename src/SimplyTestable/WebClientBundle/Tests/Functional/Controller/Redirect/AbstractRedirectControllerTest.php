<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Redirect;

use SimplyTestable\WebClientBundle\Controller\RedirectController;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;

abstract class AbstractRedirectControllerTest extends BaseSimplyTestableTestCase
{
    const USERNAME = 'user@example.com';

    /**
     * @var RedirectController
     */
    protected $redirectController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->redirectController = new RedirectController();
        $this->redirectController->setContainer($this->container);

        $userService = $this->container->get('simplytestable.services.userservice');
        $userService->setUser(new User(self::USERNAME));
    }
}
