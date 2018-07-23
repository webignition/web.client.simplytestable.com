<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130121121212_add_TaskOutput_hash extends AbstractMigration {

    private $statements = [
        'mysql' => [
            'up' => [
                "ALTER TABLE TaskOutput ADD hash VARCHAR(32) DEFAULT NULL",
                "CREATE INDEX hash_idx ON TaskOutput (hash)"
            ],
            'down' => [
                "DROP INDEX hash_idx ON TaskOutput",
                "ALTER TABLE TaskOutput DROP hash"
            ]
        ],
        'sqlite' => [
            'up' => [
                "ALTER TABLE TaskOutput ADD hash VARCHAR(32) DEFAULT NULL",
                "CREATE INDEX hash_idx ON TaskOutput (hash)"
            ],
            'down' => []
        ]
    ];

    public function up(Schema $schema)
    {
        foreach ($this->statements[$this->connection->getDatabasePlatform()->getName()]['up'] as $statement) {
            $this->addSql($statement);
        }
    }

    public function down(Schema $schema)
    {
        foreach ($this->statements[$this->connection->getDatabasePlatform()->getName()]['down'] as $statement) {
            $this->addSql($statement);
        }
    }

}
