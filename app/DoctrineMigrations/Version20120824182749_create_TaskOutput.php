<?php

namespace Application\Migrations;

use SimplyTestable\BaseMigrationsBundle\Migration\BaseMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120824182749_create_TaskOutput extends BaseMigration
{
    public function up(Schema $schema)
    {       
        
        $this->statements['mysql'] = array(
            "CREATE TABLE TaskOutput (
                id INT AUTO_INCREMENT NOT NULL,
                content LONGTEXT DEFAULT NULL,
                type VARCHAR(255) NOT NULL,
                PRIMARY KEY(id))
                DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB"
        );
        
        $this->statements['sqlite'] = array(
            "CREATE TABLE TaskOutput (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                content LONGTEXT DEFAULT NULL COLLATE NOCASE,
                type VARCHAR(255) NOT NULL COLLATE NOCASE)"
        );
        
        parent::up($schema);
    }

    public function down(Schema $schema)
    {
        $this->addCommonStatement("DROP TABLE TaskOutput");        
        parent::down($schema);
    }
}
