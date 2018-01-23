<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Results;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Controller\View\Test\CacheableViewController;
use SimplyTestable\WebClientBundle\Controller\View\Test\ViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresCompletedTest;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
//use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class ResultsController extends BaseViewController implements
    IEFiltered,
    RequiresValidUser,
    RequiresValidOwner,
    RequiresCompletedTest
{
    const RESULTS_PREPARATION_THRESHOLD = 100;

    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request\Adapter
     */
    private $testOptionsAdapter = null;

    /**
     * @var \SimplyTestable\WebClientBundle\Services\TaskTypeService
     */
    private $taskTypeService = null;

    /**
     * {@inheritdoc}
     */
    public function getFailedNoSitemapTestResponse(Request $request)
    {
        return new RedirectResponse($this->generateUrl('view_test_results_failednourlsdetected_index_index', array(
            'website' => $request->attributes->get('website'),
            'test_id' => $request->attributes->get('test_id')
        ), true));
    }

    /**
     * {@inheritdoc}
     */
    public function getRejectedTestResponse(Request $request)
    {
        return new RedirectResponse($this->generateUrl('view_test_results_rejected_index_index', array(
            'website' => $request->attributes->get('website'),
            'test_id' => $request->attributes->get('test_id')
        ), true));
    }

    /**
     * {@inheritdoc}
     */
    public function getNotFinishedTestResponse(Request $request)
    {
        return new RedirectResponse($this->generateUrl('view_test_progress_index_index', array(
            'website' => $request->attributes->get('website'),
            'test_id' => $request->attributes->get('test_id')
        ), true));
    }


    /**
     * @param RemoteTest $remoteTest
     * @param Test $test
     *
     * @return bool
     */
    protected function requiresPreparation(RemoteTest $remoteTest, Test $test)
    {
        return ($remoteTest->getTaskCount() - self::RESULTS_PREPARATION_THRESHOLD) > $test->getTaskCount();
    }

    /**
     * @return RedirectResponse
     */
    protected function getPreparationRedirectResponse() {
        $urlParameters = array(
            'website' => $this->getTest()->getWebsite(),
            'test_id' => $this->getRequest()->attributes->get('test_id')
        );

        if ($this->get('request')->query->has('output')) {
            $urlParameters['output'] = $this->get('request')->query->get('output');
        }

        return $this->redirect($this->generateUrl('view_test_results_preparing_index_index', $urlParameters, true));
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request\Adapter
     */
    protected function getTestOptionsAdapter() {
        if (is_null($this->testOptionsAdapter)) {
            $testOptionsParameters = $this->container->getParameter('test_options');

            $this->testOptionsAdapter = $this->container->get('simplytestable.services.testoptions.adapter.request');

            $this->testOptionsAdapter->setNamesAndDefaultValues($testOptionsParameters['names_and_default_values']);
            $this->testOptionsAdapter->setAvailableTaskTypes($this->getAvailableTaskTypes());
            $this->testOptionsAdapter->setInvertOptionKeys($testOptionsParameters['invert_option_keys']);
            $this->testOptionsAdapter->setInvertInvertableOptions(true);

            if (isset($testOptionsParameters['features'])) {
                $this->testOptionsAdapter->setAvailableFeatures($testOptionsParameters['features']);
            }
        }

        return $this->testOptionsAdapter;
    }


    /**
     *
     * @return array
     */
    protected function getAvailableTaskTypes() {
        if ($this->getTestService()->getRemoteTestService()->isPublic() && !$this->getTestService()->getRemoteTestService()->owns($this->getTest())) {
            $availableTaskTypes = $this->getTaskTypeService()->get();
            $remoteTestTaskTypes = $this->getRemoteTest()->getTaskTypes();

            foreach ($availableTaskTypes as $taskTypeKey => $taskTypeDetails) {
                if (!in_array($taskTypeDetails['name'], $remoteTestTaskTypes)) {
                    unset($availableTaskTypes[$taskTypeKey]);
                }
            }

            return $availableTaskTypes;
        }

        return $this->getTaskTypeService()->getAvailable();
    }


    /**
     * @return \SimplyTestable\WebClientBundle\Services\TaskTypeService
     */
    protected function getTaskTypeService() {
        if (is_null($this->taskTypeService)) {
            $this->taskTypeService = $this->container->get('simplytestable.services.tasktypeservice');
            $this->taskTypeService->setUser($this->getUser());

            if (!$this->getUser()->equals($this->getUserService()->getPublicUser())) {
                $this->taskTypeService->setUserIsAuthenticated();
            }
        }

        return $this->taskTypeService;
    }


    protected function getFilteredTaskCollectionRemoteIds($taskOutcomeFilter, $taskTypeFilter)
    {
        if ($taskTypeFilter == 'javascript static analysis') {
            $taskTypeFilter = 'js static analysis';
        }

        $this->getTaskCollectionFilterService()->setOutcomeFilter($taskOutcomeFilter);
        $this->getTaskCollectionFilterService()->setTypeFilter($taskTypeFilter);

        return $this->getTaskCollectionFilterService()->getRemoteIds();
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TaskCollectionFilterService
     */
    protected function getTaskCollectionFilterService() {
        $service = $this->container->get('simplytestable.services.taskcollectionfilterservice');
        $service->setTest($this->getTest());

        return $service;
    }

}