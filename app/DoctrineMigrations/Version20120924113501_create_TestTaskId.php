<?php

namespace Application\Migrations;

use SimplyTestable\BaseMigrationsBundle\Migration\BaseMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120924113501_create_TestTaskId extends BaseMigration
{
    public function up(Schema $schema)
    {  
        
/**
//        $this->addSql("CREATE TABLE TestTaskId (id INT AUTO_INCREMENT NOT NULL, test_id INT NOT NULL, taskId INT NOT NULL, UNIQUE INDEX test_idx (test_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB");
//        $this->addSql("ALTER TABLE TestTaskId ADD CONSTRAINT FK_1A5886461E5D0459 FOREIGN KEY (test_id) REFERENCES Test (id)");
 */        
        
        $this->statements['mysql'] = array(
            "CREATE TABLE TestTaskId (
                id INT AUTO_INCREMENT NOT NULL,
                test_id INT NOT NULL,
                taskId INT NOT NULL,
                UNIQUE INDEX test_idx (test_id),
                PRIMARY KEY(id))
                DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB",
            "ALTER TABLE TestTaskId ADD CONSTRAINT FK_1A5886461E5D0459 FOREIGN KEY (test_id) REFERENCES Test (id)"
        );
        
        $this->statements['sqlite'] = array(
            "CREATE TABLE TestTaskId (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                test_id INT NOT NULL,
                taskId INT NOT NULL,
                FOREIGN KEY (test_id) REFERENCES Test (id))"            
        );
        
        parent::up($schema);
    }

    public function down(Schema $schema)
    {
        $this->addCommonStatement("DROP TABLE TestTaskId");        
        parent::down($schema);
    }

}
