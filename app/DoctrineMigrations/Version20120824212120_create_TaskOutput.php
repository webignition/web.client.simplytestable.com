<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120824212120_create_TaskOutput extends AbstractMigration {

    private $statements = [
        'mysql' => [
            'up' => [
                "CREATE TABLE TaskOutput (
                    id INT AUTO_INCREMENT NOT NULL,
                    content LONGTEXT DEFAULT NULL,
                    type VARCHAR(255) NOT NULL,
                    task_id INT NOT NULL,
                    PRIMARY KEY(id),
                    UNIQUE INDEX UNIQ_C9B3E5C48DB60186 (task_id))
                    DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB",
                "ALTER TABLE TaskOutput ADD CONSTRAINT FK_C9B3E5C48DB60186 FOREIGN KEY (task_id) REFERENCES Task (id)"
            ],
            'down' => [
                "DROP TABLE TaskOutput"
            ]
        ],
        'sqlite' => [
            'up' => [
                "CREATE TABLE TaskOutput (
                    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    content LONGTEXT DEFAULT NULL COLLATE NOCASE,
                    type VARCHAR(255) NOT NULL COLLATE NOCASE
                 )"
            ],
            'down' => [
                "DROP TABLE TaskOutput"
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
