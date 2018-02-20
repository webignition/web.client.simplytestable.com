<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Dashboard;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request\Adapter as TestOptionsRequestAdapter;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Component\HttpFoundation\ParameterBag;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends BaseViewController implements IEFiltered, RequiresValidUser
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $taskTypeService = $this->container->get('SimplyTestable\WebClientBundle\Services\TaskTypeService');
        $testOptionsAdapterFactory = $this->container->get('SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Factory');
        $urlViewValuesService = $this->container->get('SimplyTestable\WebClientBundle\Services\UrlViewValuesService');
        $cacheValidatorService = $this->container->get('SimplyTestable\WebClientBundle\Services\CacheValidatorService');
        $flashBagValuesService = $this->container->get('SimplyTestable\WebClientBundle\Services\FlashBagValues');
        $templating = $this->container->get('templating');
        $userManager = $this->container->get(UserManager::class);

        $user = $userManager->getUser();
        $isLoggedIn = !SystemUserService::isPublicUser($user);

        $taskTypeService->setUser($user);
        if ($isLoggedIn) {
            $taskTypeService->setUserIsAuthenticated();
        }

        $requestData = $request->query;
        $testOptionsAdapter = $testOptionsAdapterFactory->create();
        $testOptionsAdapter->setRequestData($requestData);

        $testStartError = $flashBagValuesService->getSingle('test_start_error');
        $hasTestStartError = !empty($testStartError);

        $website = $requestData->get('website');
        $availableTaskTypes = $taskTypeService->getAvailable();
        $taskTypes = $taskTypeService->get();
        $testOptions = $this->getTestOptionsArray($testOptionsAdapter, $requestData, $hasTestStartError);
        $cssValidationIgnoreCommonCdns = $this->container->getParameter('css-validation-ignore-common-cdns');
        $jsStaticAnalysisIgnoreCommonCdns = $this->container->getParameter('js-static-analysis-ignore-common-cdns');

        $response = $cacheValidatorService->createResponse($request, [
            'test_start_error' => $testStartError,
            'website' => $website,
            'available_task_types' => json_encode($availableTaskTypes),
            'task_types' => json_encode($taskTypes),
            'test_options' => json_encode($testOptions),
            'css_validation_ignore_common_cdns' => json_encode($cssValidationIgnoreCommonCdns),
            'js_static_analysis_ignore_common_cdns' => json_encode($jsStaticAnalysisIgnoreCommonCdns),
            'is_logged_in' => $isLoggedIn,
        ]);

        if ($cacheValidatorService->isNotModified($response)) {
            return $response;
        }

        $viewData = [
            'available_task_types' => $availableTaskTypes,
            'task_types' => $taskTypes,
            'test_options' => $testOptions,
            'css_validation_ignore_common_cdns' => $cssValidationIgnoreCommonCdns,
            'js_static_analysis_ignore_common_cdns' => $jsStaticAnalysisIgnoreCommonCdns,
            'test_start_error' => $testStartError,
            'website' => $urlViewValuesService->create($website),
        ];

        $content = $templating->render(
            'SimplyTestableWebClientBundle:bs3/Dashboard/Index:index.html.twig',
            array_merge($this->getDefaultViewParameters(), $viewData)
        );

        $response->setContent($content);

        return $response;
    }

    /**
     * @param TestOptionsRequestAdapter $testOptionsAdapter
     * @param ParameterBag $requestData
     * @param bool $hasTestStartError
     *
     * @return array
     */
    private function getTestOptionsArray(
        TestOptionsRequestAdapter $testOptionsAdapter,
        ParameterBag $requestData,
        $hasTestStartError
    ) {
        $testOptionsData = array_merge(
            $this->container->getParameter('test_options')['names_and_default_values'],
            $requestData->all()
        );

        $testOptionsAdapter->setRequestData(new ParameterBag($testOptionsData));

        if ($hasTestStartError) {
            $testOptionsAdapter->setInvertInvertableOptions(true);
        }

        $testOptions = $testOptionsAdapter->getTestOptions()->__toKeyArray();

        if (!isset($testOptions['cookies']) || count($testOptions['cookies']) === 0) {
            $testOptions['cookies'] = [[
                'name' => null,
                'value' => null
            ]];
        }

        return $testOptions;
    }
}
