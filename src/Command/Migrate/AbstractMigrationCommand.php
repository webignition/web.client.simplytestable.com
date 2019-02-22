<?php

namespace App\Command\Migrate;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;

abstract class AbstractMigrationCommand extends Command
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager, ?string $name = null)
    {
        parent::__construct($name);

        $this->entityManager = $entityManager;
    }
}
