<?php

namespace Application\Migrations;

use SimplyTestable\BaseMigrationsBundle\Migration\BaseMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130123181653_remove_TestTaskId_add_Test_taskIdCollection extends BaseMigration
{
    public function up(Schema $schema)
    {        
        $this->statements['mysql'] = array(
            "DROP TABLE TestTaskId",
            "ALTER TABLE Test ADD taskIdCollection LONGTEXT DEFAULT NULL"        
        );
        
        $this->statements['sqlite'] = array( 
            "DROP TABLE TestTaskId",
            "ALTER TABLE Test ADD taskIdCollection LONGTEXT DEFAULT NULL" 
        );        
     
        parent::up($schema);
    }

    public function down(Schema $schema)
    {      
        
        $this->statements['mysql'] = array(
            "CREATE TABLE TestTaskId (id INT AUTO_INCREMENT NOT NULL, test_id INT NOT NULL, taskId INT NOT NULL, INDEX IDX_1A5886461E5D0459 (test_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB",
            "ALTER TABLE TestTaskId ADD CONSTRAINT FK_1A5886461E5D0459 FOREIGN KEY (test_id) REFERENCES Test (id)",
            "ALTER TABLE Test DROP taskIdCollection"
        );
        
        $this->statements['sqlite'] = array(
            "CREATE TABLE TestTaskId (id INT AUTO_INCREMENT NOT NULL, test_id INT NOT NULL, taskId INT NOT NULL, INDEX IDX_1A5886461E5D0459 (test_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB",
            "ALTER TABLE TestTaskId ADD CONSTRAINT FK_1A5886461E5D0459 FOREIGN KEY (test_id) REFERENCES Test (id)",
            //"ALTER TABLE Test DROP taskIdCollection"
        );
        
        parent::down($schema);
    }
}
