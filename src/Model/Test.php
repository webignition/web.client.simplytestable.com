<?php

namespace App\Model;

class Test
{
    private $testId;
    private $website;
    private $user;
    private $state;
    private $type;
    private $taskTypes;
    private $urlCount;

    public function __construct(
        int $testId,
        string $website,
        string $user,
        string $state,
        string $type,
        array $taskTypes,
        int $urlCount
    ) {
        $this->testId = $testId;
        $this->website = $website;
        $this->user = $user;
        $this->state = $state;
        $this->type = $type;
        $this->taskTypes = $taskTypes;
        $this->urlCount = $urlCount;
    }
}
