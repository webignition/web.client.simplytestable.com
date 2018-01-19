<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Partial\Notification;

use SimplyTestable\WebClientBundle\Controller\View\Test\CacheableViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UrlLimitController extends CacheableViewController implements RequiresValidUser, RequiresValidOwner
{
    /**
     * @param Request $request
     * @param string $website
     * @param int $test_id
     *
     * @return Response
     *
     * @throws \SimplyTestable\WebClientBundle\Exception\WebResourceException
     */
    public function indexAction(Request $request, $website, $test_id)
    {
        $testService = $this->container->get('simplytestable.services.testservice');
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');
        $userService = $this->container->get('simplytestable.services.userservice');
        $cacheableResponseService = $this->container->get('simplytestable.services.cacheableresponseservice');

        $user = $userService->getUser();
        $remoteTestService->setUser($user);

        $test = $testService->get($website, $test_id);
        $remoteTestService->setTest($test);

        $remoteTest = $remoteTestService->get();
        $ammendments = $remoteTest->getAmmendments();

        if (empty($ammendments)) {
            return new Response();
        }

        return $cacheableResponseService->getCachableResponse(
            $request,
            $this->render(
                'SimplyTestableWebClientBundle:bs3/Test/Partial/Notification/UrlLimit:index.html.twig',
                array_merge($this->getDefaultViewParameters(), [
                    'remote_test' => $remoteTest,
                    'is_public_user_test' => $test->getUser() == $userService->getPublicUser()->getUsername(),
                ])
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheValidatorParameters()
    {
        $testService = $this->container->get('simplytestable.services.testservice');
        $userService = $this->container->get('simplytestable.services.userservice');

        $request = $this->getRequest();
        $requestAttributes = $request->attributes;

        $testId = $requestAttributes->get('test_id');

        $test = $testService->get(
            $requestAttributes->get('website'),
            $testId
        );

        return array(
            'test_id' => $testId,
            'is_public_user_test' => $test->getUser() == $userService->getPublicUser()->getUsername(),
        );
    }

    /**
     * @inheritdoc}
     */
    public function getInvalidOwnerResponse()
    {
        return new Response('');
    }
}
