<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130117145730_add_output_id_to_Task extends AbstractMigration {

    private $statements = [
        'mysql' => [
            'up' => [
                "ALTER TABLE Task ADD output_id INT DEFAULT NULL",
                "ALTER TABLE Task ADD CONSTRAINT FK_F24C741BDE097880 FOREIGN KEY (output_id) REFERENCES TaskOutput (id)",
                "CREATE INDEX IDX_F24C741BDE097880 ON Task (output_id)"
            ],
            'down' => [
                "ALTER TABLE Task DROP FOREIGN KEY FK_F24C741BDE097880",
                "DROP INDEX IDX_F24C741BDE097880 ON Task",
                "ALTER TABLE Task DROP output_id"
            ]
        ],

        /**
         * Cannot (easily) perform required operations with sqlite
         * The relevant changes were added to a past migration (Version20120824212110_create_Task)
         * This is ok to do as sqlite DBs are for testing only and as such we can change past migrations
         */
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
