<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\Test;

use SimplyTestable\WebClientBundle\Controller\AbstractController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\TestOptions;
use SimplyTestable\WebClientBundle\Services\Configuration\LinkIntegrityTestConfiguration;
use SimplyTestable\WebClientBundle\Services\RemoteTestService;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\TaskTypeService;
use SimplyTestable\WebClientBundle\Services\TestOptions\RequestAdapterFactory as TestOptionsRequestAdapterFactory;
use SimplyTestable\WebClientBundle\Services\Configuration\TestOptionsConfiguration;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use webignition\NormalisedUrl\NormalisedUrl;

class StartController extends AbstractController
{
    const HTTP_AUTH_FEATURE_NAME = 'http-authentication';
    const HTTP_AUTH_FEATURE_USERNAME_KEY = 'http-auth-username';
    const HTTP_AUTH_FEATURE_PASSWORD_KEY = 'http-auth-password';
    const COOKIES_FEATURE_NAME = 'cookies';

    /**
     * @var RemoteTestService
     */
    private $remoteTestService;

    /**
     * @var TestOptionsRequestAdapterFactory
     */
    private $testOptionsRequestAdapterFactory;

    /**
     * @var TaskTypeService
     */
    private $taskTypeService;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var LinkIntegrityTestConfiguration
     */
    private $linkIntegrityTestConfiguration;

    /**
     * @var TestOptionsConfiguration
     */
    private $testOptionsConfiguration;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param RouterInterface $router
     * @param RemoteTestService $remoteTestService
     * @param TestOptionsRequestAdapterFactory $testOptionsRequestAdapterFactory
     * @param TaskTypeService $taskTypeService
     * @param UserManager $userManager
     * @param LinkIntegrityTestConfiguration $linkIntegrityTestConfiguration
     * @param TestOptionsConfiguration $testOptionsConfiguration
     * @param SessionInterface $session
     */
    public function __construct(
        RouterInterface $router,
        RemoteTestService $remoteTestService,
        TestOptionsRequestAdapterFactory $testOptionsRequestAdapterFactory,
        TaskTypeService $taskTypeService,
        UserManager $userManager,
        LinkIntegrityTestConfiguration $linkIntegrityTestConfiguration,
        TestOptionsConfiguration $testOptionsConfiguration,
        SessionInterface $session
    ) {
        parent::__construct($router);

        $this->remoteTestService = $remoteTestService;
        $this->testOptionsRequestAdapterFactory = $testOptionsRequestAdapterFactory;
        $this->taskTypeService = $taskTypeService;
        $this->userManager = $userManager;
        $this->linkIntegrityTestConfiguration = $linkIntegrityTestConfiguration;
        $this->testOptionsConfiguration = $testOptionsConfiguration;
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     *
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function startNewAction(Request $request)
    {
        $user = $this->userManager->getUser();

        $this->taskTypeService->setUser($user);

        if (!SystemUserService::isPublicUser($user)) {
            $this->taskTypeService->setUserIsAuthenticated();
        }

        $requestData = $request->request;

        if ($requestData->get(Task::TYPE_KEY_LINK_INTEGRITY)) {
            $requestData->set(
                'link-integrity-excluded-domains',
                $this->linkIntegrityTestConfiguration->getExcludedDomains()
            );
        }

        $testOptionsAdapter = $this->testOptionsRequestAdapterFactory->create();
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
        $flashBag = $this->session->getFlashBag();

        if (empty($website)) {
            $flashBag->set('test_start_error', 'website-blank');

            return new RedirectResponse($this->createStartErrorRedirectUrl($redirectRouteParameters));
        }

        if (!$testOptions->hasTestTypes()) {
            $flashBag->set('test_start_error', 'no-test-types-selected');

            return new RedirectResponse($this->createStartErrorRedirectUrl($redirectRouteParameters));
        }

        $isFullSiteTest = $this->isFullSiteTest($requestData);
        $testType = $isFullSiteTest ? 'full site' : 'single url';
        $urlToTest = $this->getUrlToTest($isFullSiteTest, $website);

        try {
            $remoteTest = $this->remoteTestService->start($urlToTest, $testOptions, $testType);

            return new RedirectResponse($this->generateUrl(
                'view_test_progress_index_index',
                [
                    'website' => $remoteTest->getWebsite(),
                    'test_id' => $remoteTest->getId(),
                ]
            ));
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
            $flashBag->set('test_start_error', 'web_resource_exception');

            return new RedirectResponse($this->createStartErrorRedirectUrl($redirectRouteParameters));
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            if ($coreApplicationRequestException->isCurlException()) {
                $flashBag->set('test_start_error', 'curl-error');
                $flashBag->set('curl_error_code', $coreApplicationRequestException->getCode());
            } else {
                $flashBag->set('test_start_error', 'web_resource_exception');
            }

            return new RedirectResponse($this->createStartErrorRedirectUrl($redirectRouteParameters));
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

        if (!$url->isPubliclyRoutable()) {
            return $website;
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
        return in_array($formKey, $this->testOptionsConfiguration->getConfiguration()['ignore_on_error']);
    }

    /**
     * @param array $redirectRouteParameters
     *
     * @return string
     */
    private function createStartErrorRedirectUrl(array $redirectRouteParameters)
    {
        return $this->generateUrl('view_dashboard_index_index', $redirectRouteParameters);
    }
}
