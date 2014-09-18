<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20140501084113_create_ListRecipients extends AbstractMigration {

    private $statements = [
        'mysql' => [
            'up' => [
                "CREATE TABLE ListRecipients (
                     id INT AUTO_INCREMENT NOT NULL,
                     listId VARCHAR(255) NOT NULL,
                     recipients LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json_array)',
                     PRIMARY KEY(id)
                 ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB"
            ],
            'down' => [
                "DROP TABLE ListRecipients"
            ]
        ],
        'sqlite' => [
            'up' => [
                "CREATE TABLE ListRecipients (
                    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    listId VARCHAR(255) NOT NULL DEFAULT '[]',
                    recipients LONGTEXT DEFAULT NULL
                )"
            ],
            'down' => [
                "DROP TABLE ListRecipients"
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
