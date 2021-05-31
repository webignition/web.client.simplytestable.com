<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130122160055_add_Test_type extends AbstractMigration {

    private $statements = [
        'mysql' => [
            'up' => [
                "ALTER TABLE Test ADD type VARCHAR(255) DEFAULT NULL"
            ],
            'down' => [
                "ALTER TABLE Test DROP type"
            ]
        ],
        'sqlite' => [
            'up' => [
                "ALTER TABLE Test ADD type VARCHAR(255) DEFAULT NULL"
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
