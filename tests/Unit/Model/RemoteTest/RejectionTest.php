<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Model\RemoteTest;

use App\Model\RemoteTest\Rejection;

class RejectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider getReasonGetConstraintDataProvider
     */
    public function testGetReasonGetConstraint(
        array $rejectionData,
        ?string $expectedReason,
        ?array $expectedConstraint
    ) {
        $rejection = new Rejection($rejectionData);

        $this->assertEquals($expectedReason, $rejection->getReason());
        $this->assertEquals($expectedConstraint, $rejection->getConstraint());
    }

    /**
     * @return array
     */
    public function getReasonGetConstraintDataProvider()
    {
        return [
            'no data' => [
                'rejectionData' => [],
                'expectedReason' => null,
                'expectedConstraint' => null,
            ],
            'with reason, with constraint' => [
                'rejectionData' => [
                    'reason' => 'foo',
                    'constraint' => [
                        'name' => 'credits_per_month',
                        'limit' => 1000,
                    ],
                ],
                'expectedReason' => 'foo',
                'expectedConstraint' => [
                    'name' => 'credits_per_month',
                    'limit' => 1000,
                ],
            ],
        ];
    }
}
