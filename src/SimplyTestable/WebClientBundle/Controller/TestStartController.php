<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\TestOptions;
use SimplyTestable\WebClientBundle\Services\RemoteTestService;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use webignition\NormalisedUrl\NormalisedUrl;

class TestStartController extends TestController
{
    const HTTP_AUTH_FEATURE_NAME = 'http-authentication';
    const HTTP_AUTH_FEATURE_USERNAME_KEY = 'http-auth-username';
    const HTTP_AUTH_FEATURE_PASSWORD_KEY = 'http-auth-password';
    const COOKIES_FEATURE_NAME = 'cookies';

    /**
     * @param Request $request
     * @return RedirectResponse
     *
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function startNewAction(Request $request)
    {
        $remoteTestService = $this->container->get(RemoteTestService::class);
        $testOptionsAdapterFactory = $this->container->get('SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Factory');
        $taskTypeService = $this->container->get('SimplyTestable\WebClientBundle\Services\TaskTypeService');
        $session = $this->container->get('session');
        $router = $this->container->get('router');
        $userManager = $this->container->get(UserManager::class);

        $user = $userManager->getUser();

        $taskTypeService->setUser($user);

        if (!SystemUserService::isPublicUser($user)) {
            $taskTypeService->setUserIsAuthenticated();
        }

        $requestData = $request->request;

        if ($requestData->get(Task::TYPE_KEY_LINK_INTEGRITY)) {
            $requestData->set(
                'link-integrity-excluded-domains',
                $this->container->getParameter('link-integrity-excluded-domains')
            );
        }

        $testOptionsAdapter = $testOptionsAdapterFactory->create();
        $testOptionsAdapter->setRequestData($requestData);
        $testOptionsAdapter->setInvertInvertableOptions(true);

        $testOptions = $testOptionsAdapter->getTestOptions();

        if ($testOptions->hasFeatureOptions(self::HTTP_AUTH_FEATURE_NAME)) {
            $httpAuthFeatureOptions = $testOptions->getFeatureOptions(self::HTTP_AUTH_FEATURE_NAME);

            $notHasUsername = ('' === $httpAuthFeatureOptions[self::HTTP_AUTH_FEATURE_USERNAME_KEY]);
            $notHasPassword = ('' === $httpAuthFeatureOptions[self::HTTP_AUTH_FEATURE_PASSWORD_KEY]);

            if ($notHasUsername && $notHasPassword) {
                $testOptions->removeFeatureOptions(self::HTTP_AUTH_FEATURE_NAME);
            }
        }

        $website = trim($requestData->get('website'));
        $redirectRouteParameters = $this->getRedirectRouteParameters($testOptions, $website);
        $flashBag = $session->getFlashBag();

        if (empty($website)) {
            $flashBag->set('test_start_error', 'website-blank');

            return new RedirectResponse($this->createStartErrorRedirectUrl($router, $redirectRouteParameters));
        }

        if (!$testOptions->hasTestTypes()) {
            $flashBag->set('test_start_error', 'no-test-types-selected');

            return new RedirectResponse($this->createStartErrorRedirectUrl($router, $redirectRouteParameters));
        }

        $isFullSiteTest = $this->isFullSiteTest($requestData);
        $testType = $isFullSiteTest ? 'full site' : 'single url';
        $urlToTest = $this->getUrlToTest($isFullSiteTest, $website);

        try {
            $remoteTest = $remoteTestService->start($urlToTest, $testOptions, $testType);

            return new RedirectResponse($router->generate(
                'view_test_progress_index_index',
                [
                    'website' => $remoteTest->getWebsite(),
                    'test_id' => $remoteTest->getId(),
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
            $flashBag->set('test_start_error', 'web_resource_exception');

            return new RedirectResponse($this->createStartErrorRedirectUrl($router, $redirectRouteParameters));
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            if ($coreApplicationRequestException->isCurlException()) {
                $flashBag->set('test_start_error', 'curl-error');
                $flashBag->set('curl_error_code', $coreApplicationRequestException->getCode());
            } else {
                $flashBag->set('test_start_error', 'web_resource_exception');
            }

            return new RedirectResponse($this->createStartErrorRedirectUrl($router, $redirectRouteParameters));
        }
    }

    /**
     * @param bool $isFullSiteTest
     * @param string $website
     *
     * @return string
     */
    private function getUrlToTest($isFullSiteTest, $website)
    {
        $url = new NormalisedUrl($website);
        if (!$url->hasScheme()) {
            $url->setScheme('http');
        }

        if ($isFullSiteTest) {
            $url->setFragment(null);
            $url->setPath('/');
            $url->setQuery(null);
        }

        return (string)$url;
    }

    /**
     * @param ParameterBag $requestData
     *
     * @return bool
     */
    private function isFullSiteTest(ParameterBag $requestData)
    {
        $testTypeKey = 'full-single';

        if (!$requestData->has($testTypeKey)) {
            return true;
        }

        return 'full' === $requestData->get($testTypeKey);
    }

    /**
     * @param TestOptions $testOptions
     * @param string $website
     *
     * @return array
     */
    private function getRedirectRouteParameters(TestOptions $testOptions, $website)
    {
        $redirectRouteParameters = [];

        if (!empty($website)) {
            $redirectRouteParameters['website'] = $website;
        }

        $absoluteTestTypes = $testOptions->getAbsoluteTestTypes();

        foreach ($absoluteTestTypes as $testTypeKey => $selectedValue) {
            $redirectRouteParameters[$testTypeKey] = $selectedValue;
            $redirectRouteParameters = array_merge(
                $redirectRouteParameters,
                $testOptions->getAbsoluteTestTypeOptions($testTypeKey)
            );
        }

        $absoluteFeatures = $testOptions->getAbsoluteFeatures();
        foreach ($absoluteFeatures as $featureKey => $selectedValue) {
            $featureOptions = $testOptions->getAbsoluteFeatureOptions($featureKey);

            foreach ($featureOptions as $optionKey => $optionValue) {
                if (!$this->isIgnoredOnRedirect($optionKey)) {
                    $redirectRouteParameters[$optionKey] = $optionValue;
                }
            }
        }

        return $redirectRouteParameters;
    }

    /**
     * @param string $formKey
     *
     * @return bool
     */
    private function isIgnoredOnRedirect($formKey)
    {
        $testOptionsParameters = $this->container->getParameter('test_options');

        return in_array($formKey, $testOptionsParameters['ignore_on_error']);
    }

    /**
     * @param RouterInterface $router
     * @param array $redirectRouteParameters
     *
     * @return string
     */
    private function createStartErrorRedirectUrl(RouterInterface $router, array $redirectRouteParameters)
    {
        return $router->generate(
            'view_dashboard_index_index',
            $redirectRouteParameters,
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
