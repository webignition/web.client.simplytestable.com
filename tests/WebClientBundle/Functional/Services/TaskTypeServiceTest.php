<?php

namespace Tests\WebClientBundle\Functional\Services;

use SimplyTestable\WebClientBundle\Services\TaskTypeService;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use webignition\SimplyTestableUserModel\User;

class TaskTypeServiceTest extends AbstractBaseTestCase
{
    /**
     * @var TaskTypeService
     */
    private $taskTypeService;

    /**
     * @var array
     */
    private $defaultTaskTypes = [
        'html-validation' => [
            'name' => 'HTML validation',
            'description' => 'Examine the validity of your HTML markup',
            'access-level' => TaskTypeService::ACCESS_LEVEL_PUBLIC,
        ],
        'css-validation' => [
            'name' => 'CSS validation',
            'description' => 'Check CSS rules defined in linked stylesheets and inline',
            'access-level' => TaskTypeService::ACCESS_LEVEL_PUBLIC,
        ],
        'js-static-analysis' => [
            'name' => 'JS static analysis',
            'description' =>
                'Analyse the quality of your JavaScript with <a href="http://www.jslint.com/lint.html">JSLint</a>',
            'access-level' => TaskTypeService::ACCESS_LEVEL_AUTHENTICATED,
        ],
        'link-integrity' => [
            'name' => 'Link integrity',
            'description' => 'Check all links, report those that do not work',
            'access-level' => TaskTypeService::ACCESS_LEVEL_AUTHENTICATED,
        ],
    ];

    /**
     * @var array
     */
    private $testEarlyAccessTaskType = [
        'early-access-test' => [
            'name' => 'Early access test',
            'description' => 'Check all links, report those that do not work',
            'access-level' => TaskTypeService::ACCESS_LEVEL_EARLY_ACCESS,
        ],
    ];

    /**
     * @var User
     */
    private $user;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->taskTypeService = $this->container->get(TaskTypeService::class);

        $allTaskTypes = array_merge($this->taskTypeService->get(), $this->testEarlyAccessTaskType);
        $this->taskTypeService->setTaskTypes($allTaskTypes);

        $this->user = new User('user@example.com');
        $this->taskTypeService->setUser($this->user);
    }

    public function testGet()
    {
        $this->assertEquals(
            array_merge($this->taskTypeService->get(), $this->testEarlyAccessTaskType),
            $this->taskTypeService->get()
        );
    }

    /**
     * @dataProvider getAvailableDataProvider
     *
     * @param bool $isAuthenticated
     * @param bool $isEarlyAccess
     * @param array $expectedAvailableTaskTypes
     */
    public function testGetAvailable($isAuthenticated, $isEarlyAccess, $expectedAvailableTaskTypes)
    {
        if ($isAuthenticated) {
            $this->taskTypeService->setUserIsAuthenticated();
        }

        if ($isEarlyAccess) {
            $this->taskTypeService->setEarlyAccessUsers([
                $this->user->getUsername(),
            ]);
        }

        $availableTaskTypes = $this->taskTypeService->getAvailable();

        $this->assertEquals($expectedAvailableTaskTypes, $availableTaskTypes);
    }

    /**
     * @return array
     */
    public function getAvailableDataProvider()
    {
        return [
            'not authenticated, not early access' => [
                'isAuthenticated' => false,
                'isEarlyAccess' => false,
                'expectedAvailableTaskTypes' => [
                    'html-validation' => $this->defaultTaskTypes['html-validation'],
                    'css-validation' => $this->defaultTaskTypes['css-validation'],
                ],
            ],
            'authenticated, not early access' => [
                'isAuthenticated' => true,
                'isEarlyAccess' => false,
                'expectedAvailableTaskTypes' => [
                    'html-validation' => $this->defaultTaskTypes['html-validation'],
                    'css-validation' => $this->defaultTaskTypes['css-validation'],
                    'js-static-analysis' => $this->defaultTaskTypes['js-static-analysis'],
                    'link-integrity' => $this->defaultTaskTypes['link-integrity'],
                ],
            ],
            'not authenticated, early access' => [
                'isAuthenticated' => false,
                'isEarlyAccess' => true,
                'expectedAvailableTaskTypes' => [
                    'html-validation' => $this->defaultTaskTypes['html-validation'],
                    'css-validation' => $this->defaultTaskTypes['css-validation'],
                    'early-access-test' => $this->testEarlyAccessTaskType['early-access-test'],
                ],
            ],
            'authenticated, early access' => [
                'isAuthenticated' => true,
                'isEarlyAccess' => true,
                'expectedAvailableTaskTypes' => [
                    'html-validation' => $this->defaultTaskTypes['html-validation'],
                    'css-validation' => $this->defaultTaskTypes['css-validation'],
                    'js-static-analysis' => $this->defaultTaskTypes['js-static-analysis'],
                    'link-integrity' => $this->defaultTaskTypes['link-integrity'],
                    'early-access-test' => $this->testEarlyAccessTaskType['early-access-test'],
                ],
            ],
        ];
    }
}
