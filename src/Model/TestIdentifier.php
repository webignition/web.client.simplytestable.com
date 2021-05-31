<?php

namespace App\Model;

class TestIdentifier
{
    private $testId;
    private $website;

    public function __construct(int $testId, string $website)
    {
        $this->testId = $testId;
        $this->website = $website;
    }

    public function toArray(): array
    {
        return [
            'test_id' => $this->testId,
            'website' => $this->website,
        ];
    }
}
