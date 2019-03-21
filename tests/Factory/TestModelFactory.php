<?php /** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Factory;

use App\Entity\Task\Task;
use App\Entity\Test as TestEntity;
use App\Model\Test as TestModel;

class TestModelFactory extends \PHPUnit\Framework\TestCase
{
    const TEST_ID = 1;
    const WEBSITE = 'http://example.com/';
    const USER = 'user@example.com';
    const STATE = TestModel::STATE_COMPLETED;
    const TYPE = TestEntity::TYPE_FULL_SITE;
    const TASK_TYPES = [
        Task::TYPE_HTML_VALIDATION,
    ];
    const URL_COUNT = 0;
    const REMOTE_TASK_COUNT = 0;
    const TASKS_WITH_ERRORS_COUNT = 0;
    const CANCELLED_TASK_COUNT = 0;
    const ENCODED_PARAMETERS = '';
    const AMENDMENTS = [];
    const COMPLETION_PERCENT = 0;
    const TASK_COUNT_BY_STATE = [
        'in_progress' => 0,
        'queued' => 0,
        'completed' => 0,
        'cancelled' => 0,
        'failed' => 0,
        'skipped' => 0,
    ];
    const CRAWL_DATA = [];
    const REJECTION = [];
    const IS_PUBLIC = false;
    const TASK_OPTIONS = [];
    const OWNERS = [
        self::USER,
    ];

    private static $testProperties = [
        'test_id' => self::TEST_ID,
        'website' => self::WEBSITE,
        'user' => self::USER,
        'state' => self::STATE,
        'type' => self::TYPE,
        'taskTypes' => self::TASK_TYPES,
        'urlCount' => self::URL_COUNT,
        'remoteTaskCount' => self::REMOTE_TASK_COUNT,
        'tasksWithErrorsCount' => self::TASKS_WITH_ERRORS_COUNT,
        'cancelledTaskCount' => self::CANCELLED_TASK_COUNT,
        'encodedParameters' => self::ENCODED_PARAMETERS,
        'amendments' => self::AMENDMENTS,
        'completionPercent' => self::COMPLETION_PERCENT,
        'taskCountByState' => self::TASK_COUNT_BY_STATE,
        'rejection' => self::REJECTION,
        'crawlData' => self::CRAWL_DATA,
        'isPublic' => self::IS_PUBLIC,
        'taskOptions' => self::TASK_OPTIONS,
        'owners' => self::OWNERS,
    ];

    public static function create(array $properties = []): TestModel
    {
        $properties = array_merge(self::$testProperties, $properties);

        if (!isset($properties['entity'])) {
            $properties['entity'] = self::createTestEntity(self::TEST_ID);
        }

        return new TestModel(
            $properties['entity'],
            $properties['website'],
            $properties['user'],
            $properties['state'],
            $properties['type'],
            $properties['taskTypes'],
            $properties['urlCount'],
            $properties['remoteTaskCount'],
            $properties['tasksWithErrorsCount'],
            $properties['cancelledTaskCount'],
            $properties['encodedParameters'],
            $properties['amendments'],
            $properties['completionPercent'],
            $properties['taskCountByState'],
            $properties['crawlData'],
            $properties['rejection'],
            $properties['isPublic'],
            $properties['taskOptions'],
            $properties['owners']
        );
    }

    private static function createTestEntity(int $testId, array $tasks = []): TestEntity
    {
        $entity = TestEntity::create($testId);

        foreach ($tasks as $task) {
            $entity->addTask($task);
        }

        return $entity;
    }
}
