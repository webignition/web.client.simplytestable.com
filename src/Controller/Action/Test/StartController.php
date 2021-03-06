<?php

namespace App\Controller\Action\Test;

use App\Controller\AbstractController;
use App\Entity\Task\Task;
use App\Model\Test as TestModel;
use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\TestOptions;
use App\Services\Configuration\LinkIntegrityTestConfiguration;
use App\Services\RemoteTestService;
use App\Services\TestOptions\RequestAdapterFactory as TestOptionsRequestAdapterFactory;
use App\Services\Configuration\TestOptionsConfiguration;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
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
     * @var LinkIntegrityTestConfiguration
     */
    private $linkIntegrityTestConfiguration;

    /**
     * @var TestOptionsConfiguration
     */
    private $testOptionsConfiguration;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(
        RouterInterface $router,
        RemoteTestService $remoteTestService,
        TestOptionsRequestAdapterFactory $testOptionsRequestAdapterFactory,
        LinkIntegrityTestConfiguration $linkIntegrityTestConfiguration,
        TestOptionsConfiguration $testOptionsConfiguration,
        FlashBagInterface $flashBag
    ) {
        parent::__construct($router);

        $this->remoteTestService = $remoteTestService;
        $this->testOptionsRequestAdapterFactory = $testOptionsRequestAdapterFactory;
        $this->linkIntegrityTestConfiguration = $linkIntegrityTestConfiguration;
        $this->testOptionsConfiguration = $testOptionsConfiguration;
        $this->router = $router;
        $this->flashBag = $flashBag;
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
        $requestData = $request->request;

        $honeypotValue = $requestData->get('hp');
        if (null !== $honeypotValue && !empty($honeypotValue) && (bool) $honeypotValue === true) {
            return new RedirectResponse($this->createStartErrorRedirectUrl([]));
        }

        if ($requestData->get(Task::TYPE_KEY_LINK_INTEGRITY)) {
            $requestData->set(
                'link-integrity-excluded-domains',
                $this->linkIntegrityTestConfiguration->getExcludedDomains()
            );
        }

        $testOptionsAdapter = $this->testOptionsRequestAdapterFactory->create();
        $testOptionsAdapter->setRequestData($requestData);

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

        if (empty($website)) {
            $this->flashBag->set('test_start_error', 'website-blank');

            return new RedirectResponse($this->createStartErrorRedirectUrl($redirectRouteParameters));
        }

        if (!$testOptions->hasTestTypes()) {
            $this->flashBag->set('test_start_error', 'no-test-types-selected');

            return new RedirectResponse($this->createStartErrorRedirectUrl($redirectRouteParameters));
        }

        $isFullSiteTest = $this->isFullSiteTest($requestData);
        $testType = $isFullSiteTest ? TestModel::TYPE_FULL_SITE : TestModel::TYPE_SINGLE_URL;
        $urlToTest = $this->getUrlToTest($isFullSiteTest, $website);

        try {
            $testIdentifier = $this->remoteTestService->start($urlToTest, $testOptions, $testType);

            return new RedirectResponse($this->generateUrl(
                'view_test_progress',
                $testIdentifier->toArray()
            ));
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
            $this->flashBag->set('test_start_error', 'web_resource_exception');

            return new RedirectResponse($this->createStartErrorRedirectUrl($redirectRouteParameters));
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            if ($coreApplicationRequestException->isCurlException()) {
                $this->flashBag->set('test_start_error', 'curl-error');
                $this->flashBag->set('curl_error_code', $coreApplicationRequestException->getCode());
            } else {
                $this->flashBag->set('test_start_error', 'web_resource_exception');
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
        return $this->generateUrl('view_dashboard', $redirectRouteParameters);
    }
}
