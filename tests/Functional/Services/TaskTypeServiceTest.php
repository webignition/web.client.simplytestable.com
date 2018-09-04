<?php

namespace App\Tests\Functional\Services;

use App\Services\SystemUserService;
use App\Services\TaskTypeService;
use App\Services\UserManager;
use App\Tests\Functional\AbstractBaseTestCase;
use webignition\SimplyTestableUserModel\User;

class TaskTypeServiceTest extends AbstractBaseTestCase
{
    const USER_EMAIL = 'user@example.com';
    const EARLY_ACCESS_USER_EMAIL = 'early-access-user@example.com';

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

        $this->taskTypeService = new TaskTypeService(
            self::$container->get(UserManager::class),
            array_merge(self::$container->getParameter('task_types'), $this->testEarlyAccessTaskType),
            self::$container->getParameter('early_access_users')
        );

        $this->user = new User('user@example.com');
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
     * @param User $user
     * @param array $expectedAvailableTaskTypes
     */
    public function testGetAvailable(User $user, $expectedAvailableTaskTypes)
    {
        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser($user);

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
                'user' => SystemUserService::getPublicUser(),
                'expectedAvailableTaskTypes' => [
                    'html-validation' => $this->defaultTaskTypes['html-validation'],
                    'css-validation' => $this->defaultTaskTypes['css-validation'],
                ],
            ],
            'authenticated, not early access' => [
                'user' => new User(self::USER_EMAIL),
                'expectedAvailableTaskTypes' => [
                    'html-validation' => $this->defaultTaskTypes['html-validation'],
                    'css-validation' => $this->defaultTaskTypes['css-validation'],
                    'link-integrity' => $this->defaultTaskTypes['link-integrity'],
                ],
            ],
            'authenticated, early access' => [
                'user' => new User(self::EARLY_ACCESS_USER_EMAIL),
                'expectedAvailableTaskTypes' => [
                    'html-validation' => $this->defaultTaskTypes['html-validation'],
                    'css-validation' => $this->defaultTaskTypes['css-validation'],
                    'link-integrity' => $this->defaultTaskTypes['link-integrity'],
                    'early-access-test' => $this->testEarlyAccessTaskType['early-access-test'],
                ],
            ],
        ];
    }
}
