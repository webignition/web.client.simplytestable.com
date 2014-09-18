<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120924113501_create_TestTaskId extends AbstractMigration {

    private $statements = [
        'mysql' => [
            'up' => [
                "CREATE TABLE TestTaskId (
                    id INT AUTO_INCREMENT NOT NULL,
                    test_id INT NOT NULL,
                    taskId INT NOT NULL,
                    INDEX IDX_1A5886461E5D0459 (test_id),
                    PRIMARY KEY(id))
                    DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB",
                "ALTER TABLE TestTaskId ADD CONSTRAINT FK_1A5886461E5D0459 FOREIGN KEY (test_id) REFERENCES Test (id)"
            ],
            'down' => [
                "DROP TABLE TestTaskId"
            ]
        ],
        'sqlite' => [
            'up' => [
                "CREATE TABLE TestTaskId (
                    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    test_id INT NOT NULL,
                    taskId INT NOT NULL,
                    FOREIGN KEY (test_id) REFERENCES Test (id))",
                "CREATE INDEX IDX_1A5886461E5D0459 ON TestTaskId (test_id)"
            ],
            'down' => [
                "DROP TABLE TestTaskId"
            ]
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
