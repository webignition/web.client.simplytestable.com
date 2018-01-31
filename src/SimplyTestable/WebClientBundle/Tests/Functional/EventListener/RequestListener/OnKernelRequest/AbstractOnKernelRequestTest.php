<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\RequestListener\OnKernelRequest;

use SimplyTestable\WebClientBundle\EventListener\RequestListener;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

abstract class AbstractOnKernelRequestTest extends AbstractBaseTestCase
{
    /**
     * @var RequestListener
     */
    protected $requestListener;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->requestListener = $this->container->get('simplytestable.controller.action_listener');
    }

    /**
     * @param Request $request
     * @param string $controllerAction
     * @param string $controllerRoute
     * @param User $user
     * @param int $requestType
     *
     * @return GetResponseEvent
     */
    protected function createGetResponseEvent(
        Request $request,
        $controllerAction,
        $controllerRoute,
        $user = null,
        $requestType = HttpKernelInterface::MASTER_REQUEST
    ) {
        $request->attributes->set('_controller', $controllerAction);
        $request->attributes->set('_route', $controllerRoute);

        if (!empty($user)) {
            $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');

            $request->cookies->add(array(
                UserService::USER_COOKIE_KEY => $userSerializerService->serializeToString($user)
            ));
        }

        return new GetResponseEvent(
            $this->container->get('kernel'),
            $request,
            $requestType
        );
    }
}
