<?php

namespace Application\Migrations;

use SimplyTestable\BaseMigrationsBundle\Migration\BaseMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130117145730_add_output_id_to_Task extends BaseMigration
{
    public function up(Schema $schema)
    {        
        $this->statements['mysql'] = array(
            "ALTER TABLE Task ADD output_id INT DEFAULT NULL",
            "ALTER TABLE Task ADD CONSTRAINT FK_F24C741BDE097880 FOREIGN KEY (output_id) REFERENCES TaskOutput (id)",
            "CREATE INDEX IDX_F24C741BDE097880 ON Task (output_id)"
        );
        
        /**
         * Cannot perform required operations with sqlite
         * The relevant changes were added to a past migration (Version20120824212110_create_Task)
         * This is ok to do as sqlite DBs are for testing only and as such we can change past migrations
         */
        $this->statements['sqlite'] = array(
            "SELECT 1 + 1"
        );        
     
        parent::up($schema);
    }

    public function down(Schema $schema)
    {        
        $this->statements['mysql'] = array(
            "ALTER TABLE Task DROP FOREIGN KEY FK_F24C741BDE097880",
            "DROP INDEX IDX_F24C741BDE097880 ON Task",
            "ALTER TABLE Task DROP output_id"
        );
        
        $this->statements['sqlite'] = array(
        );
        
        parent::down($schema);
    } 
}
