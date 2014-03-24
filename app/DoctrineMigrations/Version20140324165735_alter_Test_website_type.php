<?php

namespace Application\Migrations;

use SimplyTestable\BaseMigrationsBundle\Migration\BaseMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20140324165735_alter_Test_website_type extends BaseMigration
{   
    public function up(Schema $schema)
    {        
        $this->statements['mysql'] = array(
            "ALTER TABLE Test DROP INDEX website_idx",
            "ALTER TABLE Test CHANGE website website LONGTEXT NOT NULL",
            "ALTER TABLE `Test` ADD INDEX  `website_idx` (`website` (255))"
        );
        
        $this->statements['sqlite'] = array(
            "SELECT 1 + 1"
        );     
        
        parent::up($schema);
    }
   

    public function down(Schema $schema)
    {           
        $this->statements['mysql'] = array(
            "ALTER TABLE Test DROP INDEX website_idx",
            "ALTER TABLE  `Test` CHANGE  `website`  `website` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL",
            "ALTER TABLE `Test` ADD INDEX  `website_idx` (`website` (255))"
        );
        
        $this->statements['sqlite'] = array(
            "SELECT 1 + 1"
        );      
        
        parent::down($schema);
    }
}
