<?php

namespace Application\Migrations;

use SimplyTestable\BaseMigrationsBundle\Migration\BaseMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130122160055_add_Test_type extends BaseMigration
{
    public function up(Schema $schema)
    {        
        $this->statements['mysql'] = array(
            "ALTER TABLE Test ADD type VARCHAR(255) DEFAULT NULL"            
        );
        
        $this->statements['sqlite'] = array( 
            "ALTER TABLE Test ADD type VARCHAR(255) DEFAULT NULL"
        );        
     
        parent::up($schema);
    }

    public function down(Schema $schema)
    {      
        
        $this->statements['mysql'] = array(
            "ALTER TABLE Test DROP type"        
        );
        
        $this->statements['sqlite'] = array(
            "SELECT 1 + 1"
        );
        
        parent::down($schema);
    }
}
