<?php

namespace Application\Migrations;

use SimplyTestable\BaseMigrationsBundle\Migration\BaseMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130117163556_remote_TaskOutput_Task extends BaseMigration
{
    public function up(Schema $schema)
    {
//        $this->addSql("ALTER TABLE TaskOutput DROP FOREIGN KEY FK_C9B3E5C48DB60186");
//        $this->addSql("DROP INDEX UNIQ_C9B3E5C48DB60186 ON TaskOutput");
//        $this->addSql("ALTER TABLE TaskOutput DROP task_id");        
        
        $this->statements['mysql'] = array(
            "ALTER TABLE TaskOutput DROP FOREIGN KEY FK_C9B3E5C48DB60186",
            "DROP INDEX UNIQ_C9B3E5C48DB60186 ON TaskOutput",
            "ALTER TABLE TaskOutput DROP task_id"            
        );
        
        $this->statements['sqlite'] = array(
            "ALTER TABLE TaskOutput DROP FOREIGN KEY FK_C9B3E5C48DB60186",
            "DROP INDEX UNIQ_C9B3E5C48DB60186 ON TaskOutput",
            "ALTER TABLE TaskOutput DROP task_id"   
        );        
     
        parent::up($schema);
    }

    public function down(Schema $schema)
    { 
//        $this->addSql("ALTER TABLE TaskOutput ADD task_id INT NOT NULL");
//        $this->addSql("ALTER TABLE TaskOutput ADD CONSTRAINT FK_C9B3E5C48DB60186 FOREIGN KEY (task_id) REFERENCES Task (id)");
//        $this->addSql("CREATE UNIQUE INDEX UNIQ_C9B3E5C48DB60186 ON TaskOutput (task_id)");        
        
        $this->statements['mysql'] = array(
            "ALTER TABLE TaskOutput ADD task_id INT NOT NULL",
            "ALTER TABLE TaskOutput ADD CONSTRAINT FK_C9B3E5C48DB60186 FOREIGN KEY (task_id) REFERENCES Task (id)",
            "CREATE UNIQUE INDEX UNIQ_C9B3E5C48DB60186 ON TaskOutput (task_id)"        
        );
        
        $this->statements['sqlite'] = array(
        );
        
        parent::down($schema);
    }
}
