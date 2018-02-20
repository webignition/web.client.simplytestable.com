<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Partial\Notification;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\TestService;
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
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function indexAction(Request $request, $website, $test_id)
    {
        $testService = $this->container->get(TestService::class);
        $remoteTestService = $this->container->get('SimplyTestable\WebClientBundle\Services\RemoteTestService');
        $cacheValidatorService = $this->container->get('SimplyTestable\WebClientBundle\Services\CacheValidatorService');
        $templating = $this->container->get('templating');

        $test = $testService->get($website, $test_id);
        $remoteTestService->setTest($test);

        $remoteTest = $remoteTestService->get();
        $ammendments = $remoteTest->getAmmendments();

        if (empty($ammendments)) {
            return new Response();
        }

        $isPublicUserTest = $test->getUser() === SystemUserService::getPublicUser()->getUsername();

        $response = $cacheValidatorService->createResponse($request, [
            'test_id' => $test_id,
            'is_public_user_test' => $isPublicUserTest,
        ]);

        if ($cacheValidatorService->isNotModified($response)) {
            return $response;
        }

        $viewData = [
            'remote_test' => $remoteTest,
            'is_public_user_test' => $isPublicUserTest,
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
