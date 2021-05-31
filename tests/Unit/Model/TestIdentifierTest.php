<?php

namespace App\Tests\Unit\Model;

use App\Model\TestIdentifier;

class TestIdentifierTest extends \PHPUnit\Framework\TestCase
{
    public function testToArray()
    {
        $id = 1;
        $website = 'http://example.com/';

        $testIdentifier = new TestIdentifier($id, $website);

        $this->assertEquals(
            [
                'test_id' => $id,
                'website' => $website,
            ],
            $testIdentifier->toArray()
        );
    }
}
