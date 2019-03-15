<?php

namespace App\Model;

use App\Entity\Test as TestEntity;

class Test
{
    private $entity;
    private $website;
    private $user;
    private $state;
    private $type;
    private $taskTypes;
    private $urlCount;

    public function __construct(
        TestEntity $entity,
        string $website,
        string $user,
        string $state,
        string $type,
        array $taskTypes,
        int $urlCount
    ) {
        $this->entity = $entity;
        $this->website = $website;
        $this->user = $user;
        $this->state = $state;
        $this->type = $type;
        $this->taskTypes = $taskTypes;
        $this->urlCount = $urlCount;
    }
}
