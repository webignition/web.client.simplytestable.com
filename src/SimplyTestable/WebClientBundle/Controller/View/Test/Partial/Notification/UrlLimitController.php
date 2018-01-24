<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Partial\Notification;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UrlLimitController extends BaseViewController implements RequiresValidUser, RequiresValidOwner
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
        $cacheValidatorService = $this->container->get('simplytestable.services.cachevalidator');
        $templating = $this->container->get('templating');

        $user = $userService->getUser();
        $remoteTestService->setUser($user);

        $test = $testService->get($website, $test_id);
        $remoteTestService->setTest($test);

        $remoteTest = $remoteTestService->get();
        $ammendments = $remoteTest->getAmmendments();

        if (empty($ammendments)) {
            return new Response();
        }

        $response = $cacheValidatorService->createResponse($request, [
            'test_id' => $test_id,
            'is_public_user_test' => $test->getUser() == $userService->getPublicUser()->getUsername(),
        ]);

        if ($cacheValidatorService->isNotModified($response)) {
            return $response;
        }

        $viewData = [
            'remote_test' => $remoteTest,
            'is_public_user_test' => $test->getUser() == $userService->getPublicUser()->getUsername(),
        ];

        $content = $templating->render(
            'SimplyTestableWebClientBundle:bs3/Test/Partial/Notification/UrlLimit:index.html.twig',
            array_merge($this->getDefaultViewParameters(), $viewData)
        );

        $response->setContent($content);
        $response->headers->set('content-type', 'text/html');

        return $response;
    }

    /**
     * @inheritdoc}
     */
    public function getInvalidOwnerResponse(Request $request)
    {
        return new Response('');
    }
}
