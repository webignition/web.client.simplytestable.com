<?php /** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Model\Test;

use App\Entity\Task\Task;
use App\Entity\Test as TestEntity;
use App\Model\RemoteTest\RemoteTest;
use App\Model\Test as TestModel;
use App\Model\Test\DecoratedTest;

class DecoratedTestTest extends \PHPUnit\Framework\TestCase
{
    const TEST_ID = 1;
    const WEBSITE = 'http://example.com/';
    const USER = 'user@example.com';
    const STATE = TestEntity::STATE_COMPLETED;
    const TYPE = TestEntity::TYPE_FULL_SITE;
    const TASK_TYPES = [
        Task::TYPE_HTML_VALIDATION,
    ];
    const URL_COUNT = 12;

    public function testCreate()
    {
        $testModel = $this->createTest();
        $remoteTest = new RemoteTest([]);

        $decoratedTest = new DecoratedTest($testModel, $remoteTest);

        $this->assertEquals(self::TEST_ID, $decoratedTest->getTestId());
        $this->assertEquals(self::WEBSITE, $decoratedTest->getWebsite());
        $this->assertEquals(self::STATE, $decoratedTest->getState());
        $this->assertEquals(true, $decoratedTest->isFullSite());
        $this->assertEquals(false, $decoratedTest->isSingleUrl());
        $this->assertEquals(self::TASK_TYPES, $decoratedTest->getTaskTypes());
        $this->assertEquals(self::URL_COUNT, $decoratedTest->getUrlCount());
    }

    private function createTest(array $properties = []): TestModel
    {
        if (!isset($properties['testId'])) {
            $properties['testId'] = self::TEST_ID;
        }

        if (!isset($properties['website'])) {
            $properties['website'] = self::WEBSITE;
        }

        if (!isset($properties['user'])) {
            $properties['user'] = self::USER;
        }

        if (!isset($properties['state'])) {
            $properties['state'] = self::STATE;
        }

        if (!isset($properties['type'])) {
            $properties['type'] = self::TYPE;
        }

        if (!isset($properties['taskTypes'])) {
            $properties['taskTypes'] = self::TASK_TYPES;
        }

        if (!isset($properties['urlCount'])) {
            $properties['urlCount'] = self::URL_COUNT;
        }

        return new TestModel(
            TestEntity::create($properties['testId']),
            $properties['website'],
            $properties['user'],
            $properties['state'],
            $properties['type'],
            $properties['taskTypes'],
            $properties['urlCount']
        );
    }
}
