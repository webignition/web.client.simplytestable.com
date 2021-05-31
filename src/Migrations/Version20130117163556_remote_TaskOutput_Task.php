<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130117163556_remote_TaskOutput_Task extends AbstractMigration {

    private $statements = [
        'mysql' => [
            'up' => [
                "ALTER TABLE TaskOutput DROP FOREIGN KEY FK_C9B3E5C48DB60186",
                "DROP INDEX UNIQ_C9B3E5C48DB60186 ON TaskOutput",
                "ALTER TABLE TaskOutput DROP task_id"
            ],
            'down' => [
                "ALTER TABLE TaskOutput ADD task_id INT NOT NULL",
                "ALTER TABLE TaskOutput ADD CONSTRAINT FK_C9B3E5C48DB60186 FOREIGN KEY (task_id) REFERENCES Task (id)",
                "CREATE UNIQUE INDEX UNIQ_C9B3E5C48DB60186 ON TaskOutput (task_id)"
            ]
        ],
        'sqlite' => [
            'up' => [],
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
