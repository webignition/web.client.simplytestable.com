<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results\Partial\FinishedSummary;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends BaseViewController implements RequiresValidUser, RequiresValidOwner
{
    /**
     * @param Request $request
     * @param string $website
     * @param int $test_id
     *
     * @return Response
     *
     * @throws WebResourceException
     */
    public function indexAction(Request $request, $website, $test_id)
    {
        $cacheValidatorService = $this->container->get('simplytestable.services.cachevalidator');
        $templating = $this->container->get('templating');
        $testService = $this->container->get('simplytestable.services.testservice');
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');
        $userService = $this->container->get('simplytestable.services.userservice');

        $user = $userService->getUser();
        $remoteTestService->setUser($user);

        $response = $cacheValidatorService->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
        ]);

        if ($cacheValidatorService->isNotModified($response)) {
            return $response;
        }

        $test = $testService->get($website, $test_id);
        $remoteTest = $remoteTestService->get();

        $viewData = [
            'test' => [
                'test' => $test,
                'remote_test' => $remoteTest,
            ]
        ];

        $content = $templating->render(
            'SimplyTestableWebClientBundle:bs3/Test/Results/Partial/FinishedSummary/Index:index.html.twig',
            array_merge($this->getDefaultViewParameters(), $viewData)
        );

        $response->setContent($content);
        $response->headers->set('content-type', 'text/html');

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getInvalidOwnerResponse()
    {
        return new Response('', 400);
    }
}
