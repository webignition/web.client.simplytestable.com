<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Event\MailChimp;

use App\Event\MailChimp\Event;
use Symfony\Component\HttpFoundation\ParameterBag;

class EventTest extends \PHPUnit\Framework\TestCase
{
    public function testGetDataThrowsUnexpectedValueException()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(2);
        $this->expectExceptionMessage('Event raw data is missing "data"');

        $event = new Event(new ParameterBag());
        $event->getData();
    }

    /**
     * @dataProvider getDataSuccessDataProvider
     */
    public function testGetDataSuccess(ParameterBag $rawEventData, array $expectedData)
    {
        $event = new Event($rawEventData);
        $this->assertEquals($expectedData, $event->getData());
    }

    public function getDataSuccessDataProvider(): array
    {
        return [
            'empty data' => [
                'rawEventData' => new ParameterBag([
                    'data' => [],
                ]),
                'expectedData' => [],
            ],
            'non-empty data' => [
                'rawEventData' => new ParameterBag([
                    'data' => [
                        'foo' => 'bar',
                    ],
                ]),
                'expectedData' => [
                    'foo' => 'bar',
                ],
            ],
        ];
    }

    public function testGetFiredAtThrowsUnexpectedValueException()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(3);
        $this->expectExceptionMessage('Event raw data is missing "fired_at"');

        $event = new Event(new ParameterBag([
            'data' => [],
        ]));
        $event->getFiredAt();
    }

    public function testGetFiredAtSuccess()
    {
        $firedAt = '2009-03-26 21:31:21';

        $event = new Event(new ParameterBag([
            'data' => [],
            'fired_at' => $firedAt,
        ]));

        $returnFiredAt = $event->getFiredAt();
        $this->assertInstanceOf(\DateTime::class, $returnFiredAt);
        $this->assertEquals($firedAt, $returnFiredAt->format('Y-m-d H:i:s'));
    }

    public function testGetListIdUnexpectedValueException()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(4);
        $this->expectExceptionMessage('Event data is missing "list_id"');

        $event = new Event(new ParameterBag([
            'data' => [],
            'fired_at' => '2009-03-26 21:31:21',
        ]));
        $event->getListId();
    }

    public function testGetListIdSuccess()
    {
        $listId = 'list-id';

        $event = new Event(new ParameterBag([
            'data' => [
                'list_id' => $listId,
            ],
            'fired_at' => '2009-03-26 21:31:21',
        ]));

        $this->assertEquals($listId, $event->getListId());
    }

    public function testGetTypeUnexpectedValueException()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage('Event raw data is missing "type"');

        $event = new Event(new ParameterBag([
            'data' => [
                'list_id' => 'list-id',
            ],
            'fired_at' => '2009-03-26 21:31:21',
        ]));
        $event->getType();
    }

    public function testGetTypeSuccess()
    {
        $type = 'type';

        $event = new Event(new ParameterBag([
            'data' => [
                'list_id' => 'list-id',
            ],
            'fired_at' => '2009-03-26 21:31:21',
            'type' => $type,
        ]));

        $this->assertEquals($type, $event->getType());
    }
}
