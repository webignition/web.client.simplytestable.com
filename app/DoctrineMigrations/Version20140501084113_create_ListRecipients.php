<?php

namespace Application\Migrations;

use SimplyTestable\BaseMigrationsBundle\Migration\BaseMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20140501084113_create_ListRecipients extends BaseMigration
{

    public function up(Schema $schema)
    {       
        
        $this->statements['mysql'] = array(
            "CREATE TABLE ListRecipients ("
                . "id INT AUTO_INCREMENT NOT NULL,"
                . "listId VARCHAR(255) NOT NULL,"
                . "recipients LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json_array)',"
                . "PRIMARY KEY(id)"
            . ") DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB"
        );
        
        $this->statements['sqlite'] = array(
            "CREATE TABLE ListRecipients ("
                . "id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,"
                . "listId VARCHAR(255) NOT NULL DEFAULT '[]',"
                . "recipients LONGTEXT DEFAULT NULL"
            . ")"
        );
        
        parent::up($schema);
    }

    public function down(Schema $schema)
    {
        $this->addCommonStatement("DROP TABLE ListRecipients");        
        parent::down($schema);
    }    
}
