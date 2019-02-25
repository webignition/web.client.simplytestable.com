<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Entity;

use App\Entity\TimePeriod;

class TimePeriodTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(\DateTime $startDateTime, ?\DateTime $endDateTime)
    {
        $timePeriod = TimePeriod::create($startDateTime, $endDateTime);

        $this->assertSame($startDateTime, $timePeriod->getStartDateTime());

        if (null === $endDateTime) {
            $this->assertNull($timePeriod->getEndDateTime());
        } else {
            $this->assertSame($endDateTime, $timePeriod->getEndDateTime());
        }
    }

    public function createDataProvider(): array
    {
        return [
            'start date time only' => [
                'startDateTime' => new \DateTime('2019-02-25'),
                'endDateTime' => null,
            ],
            'start date time and end date time' => [
                'startDateTime' => new \DateTime('2019-02-25'),
                'endDateTime' => new \DateTime('2019-02-26'),
            ],
        ];
    }
}
