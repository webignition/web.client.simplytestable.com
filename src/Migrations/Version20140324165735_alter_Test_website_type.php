<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20140324165735_alter_Test_website_type extends AbstractMigration {

    private $statements = [
        'mysql' => [
            'up' => [
                "ALTER TABLE Test DROP INDEX website_idx",
                "ALTER TABLE Test CHANGE website website LONGTEXT NOT NULL",
                "ALTER TABLE `Test` ADD INDEX  `website_idx` (`website` (255))"
            ],
            'down' => [
                "ALTER TABLE Test DROP INDEX website_idx",
                "ALTER TABLE  `Test` CHANGE  `website`  `website` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL",
                "ALTER TABLE `Test` ADD INDEX  `website_idx` (`website` (255))"
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
