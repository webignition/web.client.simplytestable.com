<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130123181653_remove_TestTaskId_add_Test_taskIdCollection extends AbstractMigration {

    private $statements = [
        'mysql' => [
            'up' => [
                "DROP TABLE TestTaskId",
                "ALTER TABLE Test ADD taskIdCollection LONGTEXT DEFAULT NULL"
            ],
            'down' => [
                "CREATE TABLE TestTaskId (id INT AUTO_INCREMENT NOT NULL, test_id INT NOT NULL, taskId INT NOT NULL, INDEX IDX_1A5886461E5D0459 (test_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB",
                "ALTER TABLE TestTaskId ADD CONSTRAINT FK_1A5886461E5D0459 FOREIGN KEY (test_id) REFERENCES Test (id)",
                "ALTER TABLE Test DROP taskIdCollection"
            ]
        ],
        'sqlite' => [
            'up' => [
                "DROP TABLE TestTaskId",
                "ALTER TABLE Test ADD taskIdCollection LONGTEXT DEFAULT NULL"
            ],
            'down' => [
                "CREATE TABLE TestTaskId (id INT AUTO_INCREMENT NOT NULL, test_id INT NOT NULL, taskId INT NOT NULL, INDEX IDX_1A5886461E5D0459 (test_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB",
                "ALTER TABLE TestTaskId ADD CONSTRAINT FK_1A5886461E5D0459 FOREIGN KEY (test_id) REFERENCES Test (id)",
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
