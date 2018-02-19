<?php

namespace Tests\WebClientBundle\Unit\Model\RemoteTest;

use SimplyTestable\WebClientBundle\Model\RemoteTest\Rejection;

class RejectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getReasonGetConstraintDataProvider
     *
     * @param array $rejectionData
     * @param string $expectedReason
     * @param string $expectedConstraint
     */
    public function testGetReasonGetConstraint(array $rejectionData, $expectedReason, $expectedConstraint)
    {
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
