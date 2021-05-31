<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120831091608_create_CacheValidatorHeaders extends AbstractMigration {

    private $statements = [
        'mysql' => [
            'up' => [
                "CREATE TABLE CacheValidatorHeaders (
                    id INT AUTO_INCREMENT NOT NULL,
                    identifier VARCHAR(255) NOT NULL,
                    lastModifiedDate DATETIME NOT NULL,
                    PRIMARY KEY(id),
                    UNIQUE INDEX identifier_idx (identifier))
                    DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB"
            ],
            'down' => [
                "DROP TABLE CacheValidatorHeaders"
            ]
        ],
        'sqlite' => [
            'up' => [
                "CREATE TABLE CacheValidatorHeaders (
                    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    identifier VARCHAR(255) NOT NULL COLLATE NOCASE,
                    lastModifiedDate DATETIME NOT NULL)",
                "CREATE UNIQUE INDEX identifier_idx ON CacheValidatorHeaders (identifier)"
            ],
            'down' => [
                "DROP TABLE CacheValidatorHeaders"
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
