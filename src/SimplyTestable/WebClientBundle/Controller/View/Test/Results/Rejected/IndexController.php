<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results\Rejected;

use SimplyTestable\WebClientBundle\Controller\View\Test\AbstractRequiresValidOwnerController;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class IndexController extends AbstractRequiresValidOwnerController implements IEFiltered, RequiresValidUser
{
    /**
     * @param Request $request
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse|Response
     *
     * @throws WebResourceException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function indexAction(Request $request, $website, $test_id)
    {
        $testService = $this->container->get('simplytestable.services.testservice');
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');
        $userService = $this->container->get('simplytestable.services.userservice');
        $router = $this->container->get('router');
        $cacheValidatorService = $this->container->get('simplytestable.services.cachevalidator');
        $templating = $this->container->get('templating');
        $urlViewValuesService = $this->container->get('simplytestable.services.urlviewvalues');

        $test = $testService->get($website, $test_id);
        $remoteTest = $remoteTestService->get();

        $cacheValidatorParameters = [
            'website' => $website,
            'test_id' => $test_id,
        ];

        if ($this->isRejectedDueToCreditLimit($remoteTest)) {
            $userSummary = $userService->getSummary();
            $planConstraints = $userSummary->getPlanConstraints();
            $planCredits = $planConstraints->credits;

            $rejection = $remoteTest->getRejection();
            $constraint = $rejection->getConstraint();

            $cacheValidatorParameters['limits'] = $constraint['limit'] . ':' . $planCredits->limit;
            $cacheValidatorParameters['credits_remaining'] = $planCredits->limit - $planCredits->used;
        }

        $response = $cacheValidatorService->createResponse($request, $cacheValidatorParameters);

        if ($cacheValidatorService->isNotModified($response)) {
            return $response;
        }

        if ($test->getWebsite() != $website) {
            $redirectUrl = $router->generate(
                'app_test_redirector',
                [
                    'website' => $test->getWebsite(),
                    'test_id' => $test_id
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return new RedirectResponse($redirectUrl);
        }

        if (Test::STATE_REJECTED !== $test->getState()) {
            $redirectUrl = $router->generate(
                'view_test_progress_index_index',
                [
                    'website' => $website,
                    'test_id' => $test_id
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return new RedirectResponse($redirectUrl);
        }

        $viewData = [
            'website' => $urlViewValuesService->create($website),
            'remote_test' => $remoteTest,
            'plans' => $this->container->getParameter('plans'),
        ];

        if ($this->isRejectedDueToCreditLimit($remoteTest)) {
            $viewData['userSummary'] = $userService->getSummary();
        }

        $content = $templating->render(
            'SimplyTestableWebClientBundle:bs3/Test/Results/Rejected/Index:index.html.twig',
            array_merge($this->getDefaultViewParameters(), $viewData)
        );

        $response->setContent($content);
        $response->headers->set('content-type', 'text/html');

        return $response;
    }

    /**
     * @param RemoteTest $remoteTest
     *
     * @return bool
     */
    private function isRejectedDueToCreditLimit(RemoteTest $remoteTest)
    {
        $rejection = $remoteTest->getRejection();

        if ('plan-constraint-limit-reached' !== $rejection->getReason()) {
            return false;
        }

        $constraint = $rejection->getConstraint();

        return 'credits_per_month' === $constraint['name'];
    }
}
