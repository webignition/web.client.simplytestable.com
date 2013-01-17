<?php

namespace Application\Migrations;

use SimplyTestable\BaseMigrationsBundle\Migration\BaseMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120925115659 extends BaseMigration
{
    public function up(Schema $schema)
    {        
        $this->statements['mysql'] = array(
            "ALTER TABLE TaskOutput ADD errorCount INT NOT NULL"            
        );
        
        $this->statements['sqlite'] = array(
            "ALTER TABLE TaskOutput ADD errorCount INT NOT NULL DEFAULT 0"
        );        
        
   
        parent::up($schema);
    }

    public function down(Schema $schema)
    {
        $this->statements['mysql'] = array(
            "ALTER TABLE TaskOutput DROP errorCount"            
        );
        
        $this->statements['sqlite'] = array(
            "SELECT 1 + 1"
        );
        
        parent::down($schema);
    }
}
