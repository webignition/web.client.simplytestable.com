<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Dashboard;

use SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request\Adapter as TestOptionsRequestAdapter;
use Symfony\Component\HttpFoundation\ParameterBag;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Controller\View\CacheableViewController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends CacheableViewController implements IEFiltered, RequiresValidUser
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $taskTypeService = $this->container->get('simplytestable.services.tasktypeservice');
        $userService = $this->container->get('simplytestable.services.userservice');
        $testOptionsAdapterFactory = $this->container->get('simplytestable.services.testoptions.adapter.factory');
        $session = $this->container->get('session');
        $cacheableResponseService = $this->container->get('simplytestable.services.cacheableresponseservice');
        $urlViewValuesService = $this->container->get('simplytestable.services.urlviewvalues');

        $user = $userService->getUser();
        $taskTypeService->setUser($user);
        if (!$userService->isPublicUser($user)) {
            $taskTypeService->setUserIsAuthenticated();
        }

        $requestData = $request->query;
        $testOptionsAdapter = $testOptionsAdapterFactory->create();
        $testOptionsAdapter->setRequestData($requestData);

        $testStartErrorValues = $session->getFlashBag()->get('test_start_error');
        $hasTestStartError = !empty($testStartErrorValues);
        $testStartError = $hasTestStartError ? $testStartErrorValues[0] : null;

        $viewData = [
            'available_task_types' => $taskTypeService->getAvailable(),
            'task_types' => $taskTypeService->get(),
            'test_options' => $this->getTestOptionsArray($testOptionsAdapter, $requestData, $hasTestStartError),
            'css_validation_ignore_common_cdns' =>
                $this->container->getParameter('css-validation-ignore-common-cdns'),
            'js_static_analysis_ignore_common_cdns' =>
                $this->container->getParameter('js-static-analysis-ignore-common-cdns'),
            'test_start_error' => $testStartError,
            'website' => $urlViewValuesService->create($requestData->get('website')),
        ];

        return $cacheableResponseService->getCachableResponse(
            $request,
            parent::render(
                'SimplyTestableWebClientBundle:bs3/Dashboard/Index:index.html.twig',
                array_merge($this->getDefaultViewParameters(), $viewData)
            )
        );
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

    /**
     * {@inheritdoc}
     */
    public function getCacheValidatorParameters()
    {
        $taskTypeService = $this->container->get('simplytestable.services.tasktypeservice');
        $userService = $this->container->get('simplytestable.services.userservice');
        $testOptionsAdapterFactory = $this->container->get('simplytestable.services.testoptions.adapter.factory');
        $session = $this->container->get('session');

        $user = $userService->getUser();
        $taskTypeService->setUser($user);

        $isAuthenticated = !$userService->isPublicUser($user);

        if ($isAuthenticated) {
            $taskTypeService->setUserIsAuthenticated();
        }

        $requestData = $this->getRequest()->query;
        $testOptionsAdapter = $testOptionsAdapterFactory->create();
        $testOptionsAdapter->setRequestData($requestData);

        $testStartErrorValues = $session->getFlashBag()->peek('test_start_error');
        $hasTestStartError = !empty($testStartErrorValues);
        $testStartError = $hasTestStartError ? $testStartErrorValues[0] : '';

        return [
            'test_start_error' => $testStartError,
            'website' => $this->getRequest()->query->has('website') ? $this->getRequest()->query->get('website') : '',
            'available_task_types' => json_encode($taskTypeService->getAvailable()),
            'task_types' => json_encode($this->container->getParameter('task_types')),
            'test_options' => json_encode($this->getTestOptionsArray(
                $testOptionsAdapter,
                $requestData,
                $hasTestStartError
            )),
            'css_validation_ignore_common_cdns' => json_encode(
                $this->container->getParameter('css-validation-ignore-common-cdns')
            ),
            'js_static_analysis_ignore_common_cdns' => json_encode(
                $this->container->getParameter('js-static-analysis-ignore-common-cdns')
            ),
            'is_logged_in' => $isAuthenticated,
        ];
    }
}
